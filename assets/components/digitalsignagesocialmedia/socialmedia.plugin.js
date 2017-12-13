/* Javascript for DigitalSignage. (c) Sterc.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- Social Media Plugin --------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Social Media Plugin.
     * @class Social Media Plugin.
     * @public.
     * @param {HTMLElement} element - The element of the Social Media Plugin.
     * @param {Array} options - The options of the Social Media Plugin.
     * @param {Object} core - The DigitalSignage object for the Social Media Plugin.
     */
    function SocialMediaPlugin(element, options, core) {
        /**
         * The DigitalSignage object for the Social Media Plugin.
         * @public.
         */
        this.core = core;

        /**
         * Plugin element.
         * @public.
         */
        this.$element = $(element);

        /**
         * Current settings for the Social Media Plugin.
         * @public.
         */
        this.settings = $.extend({}, SocialMediaPlugin.Defaults, options, this.core.loadCustomPluginSettings(this.$element));

        /**
         * Currently suppressed events to prevent them from beeing retriggered.
         * @protected.
         */
        this._supress = {};

        /**
         * All templates of the Social Media Plugin.
         * @protected.
         */
        this.$templates = this.core.getTemplates(this.$element);

        /**
         * All items of the Social Media Plugin.
         * @protected.
         */
        this.$items = [];

        this.initialize();
    }

    /**
     * Default options for the Social Media Plugin.
     * @public.
     */
    SocialMediaPlugin.Defaults = {
        'animationTime' : 1,
        'timeoutTime'   : 6,

        'feed'          : null,
        'feedType'      : 'JSON',
        'feedInterval'  : 900,

        'limit'         : 10
    };

    /**
     * Enumeration for types.
     * @public.
     * @readonly.
     * @enum {String}.
     */
    SocialMediaPlugin.Type = {
        'Event': 'event'
    };

    /**
     * Initializes the Social Media Plugin.
     * @protected.
     */
    SocialMediaPlugin.prototype.initialize = function() {
        this.core.setLog('[SocialMediaPlugin] initialize');

        if (null === this.settings.feed) {
            this.core.setError('[SocialMediaPlugin] feed is not defined.');
        } else {
            this.loadData();

            if (0 < this.settings.feedInterval) {
                setInterval($.proxy(function(event) {
                    this.loadData();
                }, this), this.settings.feedInterval * 1000);
            }
        }
    };

    /**
     * Loads the data for the Social Media Plugin.
     * @protected.
     */
    SocialMediaPlugin.prototype.loadData = function() {
        this.core.setLog('[SocialMediaPlugin] loadData');

        $.ajax({
           'url'        : this.core.getAjaxUrl(this.settings.feed, this.settings.feedInterval, []),
           'dataType'   : this.settings.feedType.toUpperCase(),
           'complete'   : $.proxy(function(result) {
               if (200 == result.status) {
                   switch (this.settings.feedType.toUpperCase()) {
                       case 'JSON':
                           if (result.responseJSON) {
                               if (0 < result.responseJSON.items.length) {
                                   var data = [];

                                   for (var i = 0; i < result.responseJSON.items.length; i++) {
                                       data.push(result.responseJSON.items[i]);
                                   }

                                   this.core.setData('plugin-social-media', data, null);
                               } else {
                                   this.loadData();
                               }

                               this.core.setLog('[SocialMediaPlugin] loadData: (items: ' + result.responseJSON.items.length + ').');
                           } else {
                               this.core.setError('[SocialMediaPlugin] feed could not be read (Format: ' + this.settings.feedType.toUpperCase() + ').');
                           }

                           break;
                       default:
                           this.core.setError('[SocialMediaPlugin] feed could not be read because the format is not supported (Format: ' + this.settings.feedType.toUpperCase() + ').');

                           break;
                   }
               } else {
                   this.core.setError('[SocialMediaPlugin] feed could not be loaded (HTTP status: ' + result.status + ').');
               }

               if (0 == this.$items.length) {
                   if (0 < this.core.getData('plugin-social-media', 'length')) {
                       this.nextItem();
                   }
               }
           }, this)
       });
    };

    /**
     * Gets a item template and initializes the item.
     * @public.
     * @param {Data} array - The item data.
     */
    SocialMediaPlugin.prototype.getItem = function(data) {
        this.core.setLog('[SocialMediaPlugin] getItem: (title: ' + data.creator + ')');

        if ($item = this.core.getTemplate('item', this.$templates)) {
            $item.prependTo(this.core.getPlaceholder('social-media', this.$element));

            this.core.setPlaceholders($item, data);

            return $item;
        }

        return null;
    };

    /**
     * Sets the item and animate current en next item.
     * @public.
     */
    SocialMediaPlugin.prototype.nextItem = function() {
        var next = this.core.getCurrentDataIndex('plugin-social-media', 'next', this.settings.limit);

        this.core.setLog('[SocialMediaPlugin] nextItem: (next: ' + next + ')');

        if (null !== (data = this.core.getData('plugin-social-media', next))) {
            var data = $.extend({}, data, {
                'idx' : next + 1
            });

            if ($item = this.getItem(data)) {
                $item.hide().fadeIn(this.settings.animationTime * 1000);

                if ($current = this.$items.shift()) {
                    $current.show().fadeOut(this.settings.animationTime * 1000, $.proxy(function() {
                        $current.remove();
                    }, this));
                }

                setTimeout($.proxy(function() {
                    this.nextItem();
                }, this), this.settings.timeoutTime * 1000);

                this.$items.push($item);
            } else {
                this.skipItem('no item available.');
            }
        } else {
            this.skipItem('no data available.');
        }
    };

    /**
     * Skips the current item and animate next item.
     * @public.
     * @param {String} message - The message of skip.
     */
    SocialMediaPlugin.prototype.skipItem = function(message) {
        this.core.setLog('[SocialMediaPlugin] skipItem: (message: ' + message + ')');

        this.nextItem();
    };

    /**
     * Registers an event or state.
     * @public.
     * @param {Object} object - The event or state to register.
     */
    SocialMediaPlugin.prototype.register = function(object) {
        if (object.type === SocialMediaPlugin.Type.Event) {
            if (!$.event.special[object.name]) {
                $.event.special[object.name] = {};
            }

            if (!$.event.special[object.name].digitalsignage) {
                var _default = $.event.special[object.name]._default;

                $.event.special[object.name]._default = function(e) {
                    if (_default && _default.apply && (!e.namespace || -1 === e.namespace.indexOf('digitalsignage'))) {
                        return _default.apply(this, arguments);
                    }

                    return e.namespace && e.namespace.indexOf('digitalsignage') > -1;
                };

                $.event.special[object.name].digitalsignage = true;
            }
        }
    };

    /**
     * Suppresses events.
     * @protected.
     * @param {Array.<String>} events - The events to suppress.
     */
    SocialMediaPlugin.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @protected.
     * @param {Array.<String>} events - The events to release.
     */
    SocialMediaPlugin.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the Social Media Plugin.
     * @public.
     */
    $.fn.SocialMediaPlugin = function(core, option) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('digitalsignage.socialmediaplugin');

            if (!data) {
                data = new SocialMediaPlugin(this, typeof option == 'object' && option, core);

                $this.data('digitalsignage.socialmediaplugin', data);

                $.each([], function(i, event) {
                    data.register({ type: SocialMediaPlugin.Type.Event, name: event });

                    data.$element.on(event + '.digitalsignage.socialmediaplugin.core', $.proxy(function(e) {
                        if (e.namespace && this !== e.relatedTarget) {
                            this.suppress([event]);

                            data[event].apply(this, [].slice.call(arguments, 1));

                            this.release([event]);
                        }
                    }, data));
                });
            }

            if (typeof option == 'string' && '_' !== option.charAt(0)) {
                data[option].apply(data, args);
            }
        });
    };

    /**
     * The constructor for the jQuery Plugin.
     * @public.
     */
    $.fn.SocialMediaPlugin.Constructor = SocialMediaPlugin;

})(window.Zepto || window.jQuery, window, document);
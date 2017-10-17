/* Javascript for Narrowcasting. (c) Sterc.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- Social Media Plugin --------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Social Media Plugin.
     * @class Social Media Plugin.
     * @public.
     * @param {Element} HTMLElement|jQuery - The element of the Social Media Plugin.
     * @param {Options} array - The options of the Social Media Plugin.
     * @param {Core} Object - The Narrowcasting object for the Social Media Plugin.
     */
    function SocialMediaPlugin(element, options, core) {
        /**
         * The Narrowcasting object for the Social Media Plugin.
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
         * The data of the Social Media Plugin.
         * @protected.
         */
        this.data = [];

        /**
         * The number of times that the data of the Social Media Plugin is loaded.
         * @protected.
         */
        this.dataRefresh = 0;

        /**
         * The current item count.
         * @protected.
         */
        this.current = -1;

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
        'animationTime': 1,

        'feed': null,
        'feedType': 'JSON',
        'feedInterval': 900,

        'limit': 5,

        'loop': true
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
           'url'		: this.settings.feed,
           'dataType'	: this.settings.feedType.toUpperCase(),
           'complete'	: $.proxy(function(result) {
               if (200 == result.status) {
                   switch (this.settings.feedType.toUpperCase()) {
                       case 'JSON':
                           if (result.responseJSON) {
                               if (0 < result.responseJSON.items.length) {
                                   this.data = new Array();

                                   for (var i = 0; i < result.responseJSON.items.length; i++) {
                                       this.data.push(result.responseJSON.items[i]);
                                   }
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

                   if (0 == this.$items.length) {
                       this.nextItem();
                   }
               } else {
                   this.core.setError('[SocialMediaPlugin] feed could not be loaded (HTTP status: ' + result.status + ').');
               }

               this.dataRefresh++;
           }, this)
       });
    };

    /**
     * Gets the current item count.
     * @public.
     */
    SocialMediaPlugin.prototype.getCurrent = function() {
        if (this.settings.loop) {
            if (this.current + 1 < this.data.length) {
                this.current = this.current + 1;
            } else {
                this.current = 0;
            }
        } else {
            this.current = this.current + 1;
        }

        return this.current;
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

            setTimeout($.proxy(function() {
                this.nextItem();
            }, this), 5000);

            return $item;
        }

        return null;
    };

    /**
     * Sets the item and animate current en next item.
     * @public.
     */
    SocialMediaPlugin.prototype.nextItem = function() {
        var next = this.getCurrent();

        this.core.setLog('[SocialMediaPlugin] nextItem: (next: ' + next + ')');

        if (this.settings.limit > this.$items.length) {
            if (this.data[next]) {
                var data = $.extend({}, this.data[next], {
                    'idx' : next + 1
                });

                if ($item = this.getItem(data)) {
                    $item.hide().fadeIn(this.settings.animationTime * 1000);

                    if ($current = this.$items.shift()) {
                        $current.show().fadeOut(this.settings.animationTime * 1000, $.proxy(function() {
                            $current.remove();
                        }, this));
                    }

                    this.$items.push($item);
                } else {
                    this.skipItem('[SocialMediaPlugin] nextItem: no item available.');
                }
            } else {
                if (this.settings.loop) {
                    this.skipItem('[SocialMediaPlugin] nextItem: no data available.');
                }
            }
        }
    };

    /**
     * Skips the current item and animate next item.
     * @public.
     * @param {Message} string - The message of skip.
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

            if (!$.event.special[object.name].narrowcasting) {
                var _default = $.event.special[object.name]._default;

                $.event.special[object.name]._default = function(e) {
                    if (_default && _default.apply && (!e.namespace || -1 === e.namespace.indexOf('narrowcasting'))) {
                        return _default.apply(this, arguments);
                    }

                    return e.namespace && e.namespace.indexOf('narrowcasting') > -1;
                };

                $.event.special[object.name].narrowcasting = true;
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
                data = $this.data('narrowcasting.socialmediaplugin');

            if (!data) {
                data = new SocialMediaPlugin(this, typeof option == 'object' && option, core);

                $this.data('narrowcasting.socialmediaplugin', data);

                $.each([], function(i, event) {
                    data.register({ type: SocialMediaPlugin.Type.Event, name: event });

                    data.$element.on(event + '.narrowcasting.socialmediaplugin.core', $.proxy(function(e) {
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
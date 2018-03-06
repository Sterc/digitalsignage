/* Javascript for DigitalSignage. (c) Sterc.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- Social Media Plugin --------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Social Media Plugin.
     * @class Social Media Plugin.
     * @param {HTMLElement} element - The element of the Social Media Plugin.
     * @param {Array} options - The options of the Social Media Plugin.
     * @param {Object} core - The DigitalSignage object for the Social Media Plugin.
     */
    function SocialMediaPlugin(element, options, core) {
        /**
         * The DigitalSignage object for the Social Media Plugin.
         */
        this.core = core;

        /**
         * Currently suppressed events to prevent them from beeing retriggered.
         */
        this._supress = {};

        /**
         * Plugin element of the Social Media Plugin.
         */
        this.$element = $(element);

        /**
         * Current settings for the Social Media Plugin.
         */
        this.settings = $.extend({}, SocialMediaPlugin.Defaults, options, this.core.loadCustomPluginSettings(this.$element));

        /**
         * All templates of the Social Media Plugin.
         */
        this.$templates = this.core.getTemplates(this.$element);

        /**
         * All items of the Social Media Plugin.
         */
        this.$items = [];

        /**
         * The state of data of the Social Media Plugin.
         */
        this.isLoaded = false;

        this.initialize();
    }

    /**
     * Default options for the Social Media Plugin.
     * @public.
     */
    SocialMediaPlugin.Defaults = {
        animationTime   : 1,
        timeoutTime     : 6,

        feed            : null,
        feedType        : 'JSON',
        feedInterval    : 900,

        limit           : 10
    };

    /**
     * Enumeration for types.
     */
    SocialMediaPlugin.Type = {
        Event : 'event'
    };

    /**
     * Initializes the Social Media Plugin.
     */
    SocialMediaPlugin.prototype.initialize = function() {
        this.core.setLog('[' + this.constructor.name + '] initialize');

        if (this.settings.feed === null) {
            this.core.setError('[' + this.constructor.name + '] initialize: ' + this.core.getLexicon('socialmediaplugin_error_feed'));
        } else {
            this.loadFeed();

            if (0 < this.settings.feedInterval) {
                setInterval($.proxy(function(event) {
                    this.loadFeed();
                }, this), this.settings.feedInterval * 1000);
            }
        }
    };

    /**
     * Loads the data for the Social Media Plugin.
     */
    SocialMediaPlugin.prototype.loadFeed = function() {
        this.core.setLog('[' + this.constructor.name + '] loadData');

        $.ajax({
           url          : this.core.getAjaxUrl(this.settings.feed),
           dataType     : this.settings.feedType.toUpperCase(),
           complete     : $.proxy(function(result) {
               if (parseInt(result.status) === 200) {
                   switch (this.settings.feedType.toUpperCase()) {
                       case 'JSON':
                           if (result.responseJSON) {
                               if (result.responseJSON.items.length > 0) {
                                   var data = [];

                                   for (var i = 0; i < result.responseJSON.items.length; i++) {
                                       data.push(result.responseJSON.items[i]);
                                   }

                                   this.core.setData(this.constructor.name.toLowerCase(), data, null);

                                   this.core.setLog('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('socialmediaplugin_feed_loaded', {
                                       items : result.responseJSON.items.length
                                   }));
                               } else {
                                   this.core.setLog('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('socialmediaplugin_error_feed_empty', {
                                       items : result.responseJSON.items.length
                                   }));
                               }
                           } else {
                               this.core.setError('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('socialmediaplugin_error_feed_format', {
                                   format : this.settings.feedType.toUpperCase()
                               }));
                           }

                           break;
                       default:
                           this.core.setError('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('socialmediaplugin_error_feed_format', {
                               format : this.settings.feedType.toUpperCase()
                           }));

                           break;
                   }
               } else {
                   this.core.setError('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('socialmediaplugin_error_feed_http', {
                       status : result.status
                   }));
               }

               this.start();
           }, this)
       });
    };

    /**
     * Sets the first item when the feed is loaded.
     */
    SocialMediaPlugin.prototype.start = function() {
        if (this.core.getData(this.constructor.name.toLowerCase(), 'length') > 0) {
            if (!this.isLoaded) {
                this.nextItem();
            }

            this.isLoaded = true;
        }
    },

    /**
     * Gets an item template and initializes the item.
     * @param {Array} data - The item data.
     */
    SocialMediaPlugin.prototype.getItem = function(data) {
        this.core.setLog('[' + this.constructor.name + '] getItem: (title: ' + data.creator + ')');

        if ($item = this.core.getTemplate('item', this.$templates)) {
            $item.prependTo(this.core.getPlaceholder('social-media', this.$element));

            this.core.setPlaceholders($item, data);

            return $item;
        }

        return null;
    };

    /**
     * Sets the item and animate current en next item.
     */
    SocialMediaPlugin.prototype.nextItem = function() {
        var next = this.core.getCurrentDataIndex(this.constructor.name.toLowerCase(), 'next', this.settings.limit);

        this.core.setLog('[' + this.constructor.name + '] nextItem: (next: ' + next + ')');

        if (next !== null) {
            if (null !== (data = this.core.getData(this.constructor.name.toLowerCase(), next))) {
                var data = $.extend({}, data, {
                    idx : next + 1
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
                    this.core.setLog('[' + this.constructor.name + '] nextItem: ' + this.core.getLexicon('socialmediaplugin_error_no_item'));

                    this.nextItem();
                }
            } else {
                this.core.setLog('[' + this.constructor.name + '] nextItem: ' + this.core.getLexicon('socialmediaplugin_error_no_item_data'));

                this.nextItem();
            }
        } else {
            this.core.setError('[' + this.constructor.name + '] nextItem: ' + this.core.getLexicon('socialmediaplugin_error_no_data'));
        }
    };

    /**
     * Registers an event or state.
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
                    if (_default && _default.apply && (!e.namespace || e.namespace.indexOf('digitalsignage') === -1)) {
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
     * @param {Array} events - The events to suppress.
     */
    SocialMediaPlugin.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @param {Array} events - The events to release.
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
                data = new SocialMediaPlugin(this, typeof option === 'object' && option, core);

                $this.data('digitalsignage.socialmediaplugin', data);

                $.each([], function(i, event) {
                    data.register({type: SocialMediaPlugin.Type.Event, name: event});

                    data.$element.on(event + '.digitalsignage.socialmediaplugin.core', $.proxy(function(e) {
                        if (e.namespace && this !== e.relatedTarget) {
                            this.suppress([event]);

                            data[event].apply(this, [].slice.call(arguments, 1));

                            this.release([event]);
                        }
                    }, data));
                });
            }

            if (typeof option === 'string' && option.charAt(0) !== '_') {
                data[option].apply(data, args);
            }
        });
    };

    /**
     * The constructor for the Social Media Plugin.
     * @public.
     */
    $.fn.SocialMediaPlugin.Constructor = SocialMediaPlugin;

    /**
     * The lexicons for the Social Media Plugin.
     */
    $.extend($.fn.DigitalSignage.lexicons, {
        socialmediaplugin_error_feed            : 'Social Media kon niet geladen worden omdat de feed niet gedefinieerd is.',
        socialmediaplugin_error_feed_http       : 'Feed kon niet geladen worden (HTTP status: %status%).',
        socialmediaplugin_error_feed_format     : 'Feed kon niet geladen worden omdat het formaat niet ondersteund word (Formaat: %format%).',
        socialmediaplugin_error_feed_empty      : 'Feed kon niet geladen worden omdat het geen items bevat.',
        socialmediaplugin_feed_loaded           : 'Feed geladen met %items% items.',

        socialmediaplugin_error_no_data         : 'Geen data beschikbaar.',
        socialmediaplugin_error_no_item         : 'Geen item beschikbaar.',
        socialmediaplugin_error_no_item_data    : 'Geen item data beschikbaar.'
    });
})(window.Zepto || window.jQuery, window, document);
/* Javascript for DigitalSignage. (c) Sterc.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- Slide Feed ------------------------------------------------------------------------ */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Feed Slide.
     * @class SlideFeed.
     * @param {HTMLElement} element - The element of the Feed Slide.
     * @param {Array} options - The options of the Feed Slide.
     * @param {Object} core - The DigitalSignage object for the Feed Slide.
     */
    function SlideFeed(element, options, core) {
        /**
         * The DigitalSignage object for the Feed Slide.
         */
        this.core = core;

        /**
         * Currently suppressed events to prevent them from beeing retriggered.
         */
        this._supress = {};

        /**
         * Plugin element of the Feed Slide.
         */
        this.$element = $(element);

        /**
         * Current settings for the Feed Slide.
         */
        this.settings = $.extend({}, SlideFeed.Defaults, options);

        /**
         * All templates of the Feed Slide.
         */
        this.$templates = this.core.getTemplates(this.$element);

        /**
         * All items of the Feed Slide.
         */
        this.$items = [];

        this.initialize();
    }

    /**
     * Default options for the Feed Slide.
     */
    SlideFeed.Defaults = {
        time            : 15,

        animationTime   : 1,

        feed            : null,
        feedType        : 'JSON',

        limit           : 3
    };

    /**
     * Enumeration for types.
     */
    SlideFeed.Type = {
        Event : 'event'
    };

    /**
     * Initializes the Feed Slide.
     */
    SlideFeed.prototype.initialize = function() {
        this.core.setLog('[' + this.constructor.name + '] initialize');

        this.core.setData('slide-' + this.settings.id, null, null);

        this.core.setPlaceholders(this.$element, this.settings);
        this.core.getPlaceholder('items', this.$element).addClass('feed-limit-' + this.settings.limit);

        if (this.settings.feed === null) {
            this.core.setError('[' + this.constructor.name + '] initialize: ' + this.core.getLexicon('slidefeed_error_feed'));
        } else {
            this.loadFeed();
        }

        this.core.setTimer(this.settings.time);
    };

    /**
     * Loads the data for the Feed Slide.
     */
    SlideFeed.prototype.loadFeed = function() {
        this.core.setLog('[' + this.constructor.name + '] loadData');

        $.ajax({
            url         : this.core.getAjaxUrl(this.settings.feed, ['url=' + this.settings.url]),
            dataType    : this.settings.feedType.toUpperCase(),
            complete    : $.proxy(function(result) {
               if (parseInt(result.status) === 200) {
                   switch (this.settings.feedType.toUpperCase()) {
                       case 'JSON':
                           if (result.responseJSON) {
                               if (result.responseJSON.items.length > 0) {
                                   var data = [];

                                   for (var i = 0; i < result.responseJSON.items.length; i++) {
                                       data.push(result.responseJSON.items[i]);
                                   }

                                   this.core.setData('slide-' + this.settings.id, data, null);

                                   this.core.setLog('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('slidefeed_error_feed_loaded', {
                                       items : result.responseJSON.items.length
                                   }));
                               } else {
                                   this.core.setLog('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('slidefeed_error_feed_empty', {
                                       items : result.responseJSON.items.length
                                   }));
                               }
                           } else {
                               this.core.setError('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('slidefeed_error_feed_format', {
                                   format : this.settings.feedType.toUpperCase()
                               }));
                           }

                           break;
                       case 'XML':
                            if (result.responseText) {
                                var xml = this.core.parseXML(result.responseText, 'item');

                                if (xml.length > 0) {
                                    var data = [];

                                    for (var i = 0; i < xml.length; i++) {
                                        data.push(xml[i]);
                                    }

                                    this.core.setData('slide-' + this.settings.id, data, null);

                                    this.core.setLog('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('slidefeed_error_feed_loaded', {
                                        items : xml.length
                                    }));
                                } else {
                                    this.core.setLog('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('slidefeed_error_feed_empty', {
                                        items : xml.length
                                    }));
                                }
                           } else {
                                this.core.setError('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('slidefeed_error_feed_format', {
                                    format : this.settings.feedType.toUpperCase()
                                }));
                           }

                           break;
                       default:
                           this.core.setError('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('slidefeed_error_feed_format', {
                               format : this.settings.feedType.toUpperCase()
                           }));

                           break;
                   }
               } else {
                   this.core.setError('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('slidefeed_error_feed_http', {
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
    SlideFeed.prototype.start = function() {
        if (this.core.getData('slide-' + this.settings.id, 'length') > 0) {
            this.nextItem();
        }
    },

    /**
     * Gets an item template and initializes the item.
     * @param {Array} data - The item data.
     */
    SlideFeed.prototype.getItem = function(data) {
        this.core.setLog('[' + this.constructor.name + '] getItem: (date: ' + data.title + ')');

        if ($item = this.core.getTemplate('item', this.$templates)) {
            $item.appendTo(this.core.getPlaceholder('items', this.$element));

            this.core.setPlaceholders($item, data);

            return $item;
        }

        return null;
    };

    /**
     * Sets the item and animate current en next item.
     */
    SlideFeed.prototype.nextItem = function() {
        var next = this.core.getCurrentDataIndex('slide-' + this.settings.id, 'next', null);

        this.core.setLog('[' + this.constructor.name + '] nextItem: (next: ' + next + ')');

        if (next !== null) {
            if (this.settings.limit > this.$items.length) {
                if (null !== (data = this.core.getData('slide-' + this.settings.id, next))) {
                    var data = $.extend({}, data, {
                        idx : next + 1
                    });

                    if ($item = this.getItem(data)) {
                        $item.hide().fadeIn(this.settings.animationTime * 1000, $.proxy(function() {
                            this.nextItem();
                        }, this));

                        this.$items.push($item);
                    } else {
                        this.core.setLog('[' + this.constructor.name + '] nextItem: ' + this.core.getLexicon('slidefeed_error_no_item'));

                        this.nextItem();
                    }
                } else {
                    this.core.setLog('[' + this.constructor.name + '] nextItem: ' + this.core.getLexicon('slidefeed_error_no_item_data'));

                    this.nextItem();
                }
            }
        } else {
            this.core.setError('[' + this.constructor.name + '] nextItem: ' + this.core.getLexicon('slidefeed_error_no_data'));
        }
    };

    /**
     * Registers an event or state.
     * @param {Object} object - The event or state to register.
     */
    SlideFeed.prototype.register = function(object) {
        if (object.type === SlideFeed.Type.Event) {
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
    SlideFeed.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @param {Array} events - The events to release.
     */
    SlideFeed.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the Feed Slide.
     */
    $.fn.SlideFeed = function(option, core) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('digitalsignage.slidefeed');

            if (!data) {
                data = new SlideFeed(this, typeof option === 'object' && option, core);

                $this.data('digitalsignage.slidefeed', data);

                $.each([], function(i, event) {
                    data.register({type: SlideFeed.Type.Event, name: event});

                    data.$element.on(event + '.digitalsignage.slidefeed.core', $.proxy(function(e) {
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
     * The constructor for the Feed Slide.
     * @public.
     */
    $.fn.SlideFeed.Constructor = SlideFeed;

    /**
     * The lexicons for the Feed Slide.
     */
    $.extend($.fn.DigitalSignage.lexicons, {
        slidefeed_error_feed            : 'Feed kon niet geladen worden omdat de feed niet gedefinieerd is.',
        slidefeed_error_feed_http       : 'Feed kon niet geladen worden (HTTP status: %status%).',
        slidefeed_error_feed_format     : 'Feed kon niet geladen worden omdat het formaat niet ondersteund word (Formaat: %format%).',
        slidefeed_error_feed_empty      : 'Feed kon niet geladen worden omdat het geen items bevat.',
        slidefeed_feed_loaded           : 'Feed geladen met %items% items.',

        slidefeed_error_no_data         : 'Geen data beschikbaar.',
        slidefeed_error_no_item         : 'Geen item beschikbaar.',
        slidefeed_error_no_item_data    : 'Geen item data beschikbaar.'
    });
})(window.Zepto || window.jQuery, window, document);
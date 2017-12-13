/* Javascript for DigitalSignage. (c) Sterc.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- Slide Feed ------------------------------------------------------------------------ */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Feed Slide.
     * @class SlideFeed.
     * @public.
     * @param {HTMLElement} element - The element of the Feed Slide.
     * @param {Array} options - The options of the Feed Slide.
     * @param {Object} core - The DigitalSignage object for the Feed Slide.
     */
    function SlideFeed(element, options, core) {
        /**
         * The DigitalSignage object for the Feed Slide.
         * @public.
         */
        this.core = core;

        /**
         * Current settings for the Feed Slide.
         * @public.
         */
        this.settings = $.extend({}, SlideFeed.Defaults, options);

        /**
         * Plugin element.
         * @public.
         */
        this.$element = $(element);

        /**
         * Currently suppressed events to prevent them from beeing retriggered.
         * @protected.
         */
        this._supress = {};

        /**
         * All templates of the Feed Slide.
         * @protected.
         */
        this.$templates = this.core.getTemplates(this.$element);

        /**
         * All items of the Feed Slide.
         * @protected.
         */
        this.$items = [];

        this.initialize();
    }

    /**
     * Default options for the Feed Slide.
     * @public.
     */
    SlideFeed.Defaults = {
        'time'          : 15,

        'animationTime' : 1,

        'feed'          : null,
        'feedType'      : 'JSON',

        'limit'         : 3
    };

    /**
     * Enumeration for types.
     * @public.
     * @readonly.
     * @enum {String}.
     */
    SlideFeed.Type = {
        'Event': 'event'
    };

    /**
     * Initializes the Feed Slide.
     * @protected.
     */
    SlideFeed.prototype.initialize = function() {
        this.core.setLog('[SlideFeed] initialize');

        this.core.setData('slide-' + this.settings.id, null, null);

        this.core.setPlaceholders(this.$element, this.settings);
        this.core.getPlaceholder('items', this.$element).addClass('feed-limit-' + this.settings.limit);

        if (null === this.settings.feed) {
            this.core.setError('[SlideFeed] feed is not defined.');
        } else {
            this.loadData();
        }

        this.core.setTimer(this.settings.time);
    };

    /**
     * Loads the data for the Feed Slide.
     * @protected.
     */
    SlideFeed.prototype.loadData = function() {
        this.core.setLog('[SlideFeed] loadData');

        $.ajax({
            'url'       : this.core.getAjaxUrl(this.settings.feed, 0, ['url=' + this.settings.url]),
            'dataType'  : this.settings.feedType.toUpperCase(),
            'complete'  : $.proxy(function(result) {
               if (200 == result.status) {
                   switch (this.settings.feedType.toUpperCase()) {
                       case 'JSON':
                           if (result.responseJSON) {
                               if (0 < result.responseJSON.items.length) {
                                   var data = [];

                                   for (var i = 0; i < result.responseJSON.items.length; i++) {
                                       data.push(result.responseJSON.items[i]);
                                   }

                                   this.core.setData('slide-' + this.settings.id, data, null);
                               } else {
                                   this.loadData();
                               }

                               this.core.setLog('[SlideFeed] loadData: (items: ' + result.responseJSON.items.length + ')');
                           } else {
                               this.core.setError('[SlideFeed] feed could not be read (Format: ' + this.settings.feedType.toUpperCase() + ')');
                           }

                           break;
                       case 'XML':
                            if (result.responseText) {
                                var xml = this.core.parseXML(result.responseText, 'item');

                                if (0 < xml.length) {
                                    var data = [];

                                    for (var i = 0; i < xml.length; i++) {
                                        data.push(xml[i]);
                                    }

                                    this.core.setData('slide-' + this.settings.id, data, null);
                                } else {
                                    this.loadData();
                                }

                               this.core.setLog('[SlideFeed] loadData: (items: ' + xml.length + ')');
                           } else {
                               this.core.setError('[SlideFeed] feed could not be read (Format: ' + this.settings.feedType.toUpperCase() + ')');
                           }

                           break;
                       default:
                           this.core.setError('[SlideFeed] feed could not be read because the format is not supported (Format: ' + this.settings.feedType.toUpperCase() + ')');

                           break;
                   }
               } else {
                   this.core.setError('[SlideFeed] feed could not be loaded (HTTP status: ' + result.status + ')');
               }

                if (0 == this.$items.length) {
                    if (0 < this.core.getData('slide-' + this.settings.id, 'length')) {
                        this.nextItem();
                    }
                }
            }, this)
        });
    };

    /**
     * Gets an item template and initializes the item.
     * @public.
     * @param {Array} data - The item data.
     */
    SlideFeed.prototype.getItem = function(data) {
        this.core.setLog('[SlideFeed] getItem: (date: ' + data.title + ')');

        if ($item = this.core.getTemplate('item', this.$templates)) {
            $item.appendTo(this.core.getPlaceholder('items', this.$element));

            this.core.setPlaceholders($item, data);

            return $item;
        }

        return null;
    };

    /**
     * Sets the item and animate current en next item.
     * @public.
     */
    SlideFeed.prototype.nextItem = function() {
        var next = this.core.getCurrentDataIndex('slide-' + this.settings.id, 'next', null);

        this.core.setLog('[SlideFeed] nextItem: (next: ' + next + ')');

        if (this.settings.limit > this.$items.length) {
            if (null !== (data = this.core.getData('slide-' + this.settings.id, next))) {
                var data = $.extend({}, data, {
                    'idx' : next + 1
                });

                if ($item = this.getItem(data)) {
                    $item.hide().fadeIn(this.settings.animationTime * 1000, $.proxy(function() {
                        this.nextItem();
                    }, this));

                    this.$items.push($item);
                } else {
                    this.skipItem('no data available.');
                }
            } else {
                this.skipItem('no data available.');
            }
        }
    };

    /**
     * Skips the current item and animate next item.
     * @public.
     * @param {Message} string - The message of skip.
     */
    SlideFeed.prototype.skipItem = function(message) {
        this.core.setLog('[SlideFeed] skipItem: (message: ' + message + ')');

        this.nextItem();
    };

    /**
     * Registers an event or state.
     * @public.
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
    SlideFeed.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @protected.
     * @param {Array.<String>} events - The events to release.
     */
    SlideFeed.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the Feed Slide.
     * @public.
     */
    $.fn.SlideFeed = function(option, core) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('digitalsignage.slidefeed');

            if (!data) {
                data = new SlideFeed(this, typeof option == 'object' && option, core);

                $this.data('digitalsignage.slidefeed', data);

                $.each([], function(i, event) {
                    data.register({ type: SlideFeed.Type.Event, name: event });

                    data.$element.on(event + '.digitalsignage.slidefeed.core', $.proxy(function(e) {
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
    $.fn.SlideFeed.Constructor = SlideFeed;

})(window.Zepto || window.jQuery, window, document);
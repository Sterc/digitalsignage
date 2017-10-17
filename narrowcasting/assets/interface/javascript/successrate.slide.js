/* Javascript for Narrowcasting. (c) Sterc.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- SlideSuccess Rate ----------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a SuccessRate Slide.
     * @class SlideSuccessRate.
     * @public.
     * @param {Element} HTMLElement|jQuery - The element of the SuccessRate Slide.
     * @param {Options} array - The options of the SuccessRate Slide.
     * @param {Core} Object - The Narrowcasting object for the SuccessRate Slide.
     */
    function SlideSuccessRate(element, options, core) {
        /**
         * The Narrowcasting object for the SuccessRate Slide.
         * @public.
         */
        this.core = core;

        /**
         * Current settings for the SuccessRate Slide.
         * @public.
         */
        this.settings = $.extend({}, SlideSuccessRate.Defaults, options);

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
         * All templates of the SuccessRate Slide.
         * @protected.
         */
        this.$templates = this.core.getTemplates(this.$element);

        /**
         * The data.
         * @protected.
         */
        this.data = {};

        /**
         * The current rate count.
         * @protected.
         */
        this.current = -1;

        /**
         * All rates of the SuccessRate Slide.
         * @protected.
         */
        this.$rates = [];

        this.initialize();
    }

    /**
     * Default options for the SuccessRate Slide.
     * @public.
     */
    SlideSuccessRate.Defaults = {
        'time': 15,

        'animationTime': 1,

        'feed': null,
        'feedType': 'JSON',

        'limit': 4,

        'loop': false
    };

    /**
     * Enumeration for types.
     * @public.
     * @readonly.
     * @enum {String}.
     */
    SlideSuccessRate.Type = {
        'Event': 'event'
    };

    /**
     * Initializes the SlideSuccessRate Slide.
     * @protected.
     */
    SlideSuccessRate.prototype.initialize = function() {
        this.core.setLog('[SlideSuccessRate] initialize');

        this.core.setPlaceholders(this.$element, this.settings);

        if (null === this.settings.feed) {
            this.core.setError('[SlideSuccessRate] forecast feed is not defined.');
        } else {
            this.loadData();
        }

        this.core.setTimer(this.settings.time);
    };

    /**
     * Loads the data for the SlideSuccessRate Slide.
     * @protected.
     */
    SlideSuccessRate.prototype.loadData = function() {
        this.core.setLog('[SlideSuccessRate] loadData');

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

                               this.core.setLog('[SlideSuccessRate] loadData: (items: ' + result.responseJSON.items.length + ').');
                           } else {
                               this.core.setError('[SlideSuccessRate] feed could not be read (Format: ' + this.settings.feedType.toUpperCase() + ').');
                           }

                           break;
                       default:
                           this.core.setError('[SlideSuccessRate] feed could not be read because the format is not supported (Format: ' + this.settings.forecastType.toUpperCase() + ').');

                           break;
                   }

                   if (0 == this.$rates.length) {
                       this.nextRate();
                   }
               } else {
                   this.core.setError('[SlideSuccessRate] feed could not be loaded (HTTP status: ' + result.status + ').');
               }
            }, this)
        });
    };

    /**
     * Gets the current item count.
     * @public.
     */
    SlideSuccessRate.prototype.getCurrent = function() {
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
     * Gets a rate template and initializes the rate.
     * @public.
     * @param {Data} array - The item data.
     */
    SlideSuccessRate.prototype.getRate = function(data) {
        this.core.setLog('[SlideSuccessRate] getRate: (name: ' + data.name + ')');

        if ($rate = this.core.getTemplate('rate', this.$templates)) {
            $rate.appendTo(this.core.getPlaceholder('rates', this.$element));

            this.core.setPlaceholders($rate, data);

            return $rate;
        }

        return null;
    };

    /**
     * Sets the rate and animate current en next rate.
     * @public.
     */
    SlideSuccessRate.prototype.nextRate = function() {
        var next = this.getCurrent();

        this.core.setLog('[SlideSuccessRate] nextRate: (next: ' + next + ')');

        if (this.settings.limit > this.$rates.length) {
            if (this.data[next]) {
                var data = $.extend({}, this.data[next], {
                    'idx' : next + 1
                });

                if ($rate = this.getRate(data)) {
                    $rate.hide().fadeIn(this.settings.animationTime * 1000, $.proxy(function() {
                        this.nextRate();
                    }, this));

                    this.$rates.push($rate);
                } else {
                    this.skipRate('[SlideSuccessRate] nextRate: no rate available.');
                }
            } else {
                this.skipRate('[SlideSuccessRate] nextRate: no data available.');
            }
        }
    };

    /**
     * Skips the current rate and animate next rate.
     * @public.
     * @param {Message} string - The message of skip.
     */
    SlideSuccessRate.prototype.skipRate = function(message) {
        this.core.setLog('[SlideSuccessRate] skipRow: (message: ' + message + ')');

        this.nextRate();
    };

    /**
     * Registers an event or state.
     * @public.
     * @param {Object} object - The event or state to register.
     */
    SlideSuccessRate.prototype.register = function(object) {
        if (object.type === SlideSuccessRate.Type.Event) {
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
    SlideSuccessRate.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @protected.
     * @param {Array.<String>} events - The events to release.
     */
    SlideSuccessRate.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the SuccessRate Slide.
     * @public.
     */
    $.fn.SlideSuccessRate = function(option, core) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('narrowcasting.slidesuccessrate');

            if (!data) {
                data = new SlideSuccessRate(this, typeof option == 'object' && option, core);

                $this.data('narrowcasting.slidesuccessrate', data);

                $.each([], function(i, event) {
                    data.register({ type: SlideSuccessRate.Type.Event, name: event });

                    data.$element.on(event + '.narrowcasting.slidesuccessrate.core', $.proxy(function(e) {
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
    $.fn.SlideSuccessRate.Constructor = SlideSuccessRate;

})(window.Zepto || window.jQuery, window, document);
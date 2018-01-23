/* Javascript for DigitalSignage. (c) Sterc.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- Slide Buienradar ------------------------------------------------------------------ */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Buienradar Slide.
     * @class SlideBuienradar.
     * @public.
     * @param {Element} HTMLElement|jQuery - The element of the Buienradar Slide.
     * @param {Options} array - The options of the Buienradar Slide.
     * @param {Core} Object - The DigitalSignage object for the Buienradar Slide.
     */
    function SlideBuienradar(element, options, core) {
        /**
         * The DigitalSignage object for the Buienradar Slide.
         * @public.
         */
        this.core = core;

        /**
         * Current settings for the Buienradar Slide.
         * @public.
         */
        this.settings = $.extend({}, SlideBuienradar.Defaults, options);

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
         * All templates of the Buienradar Slide.
         * @protected.
         */
        this.$templates = this.core.getTemplates(this.$element);

        /**
         * The data.
         * @protected.
         */
        this.data = {};

        /**
         * The current forecast count.
         * @protected.
         */
        this.current = -1;

        /**
         * All forecasts of the Buienradar Slide.
         * @protected.
         */
        this.$forecasts = [];

        this.initialize();
    }

    /**
     * Default options for the Buienradar Slide.
     * @public.
     */
    SlideBuienradar.Defaults = {
        'time': 15,

        'location': null,

        'animationTime': 1,

        'forecast': '//api.buienradar.nl/data/forecast/1.1/daily/',
        'forecastType': 'JSON',

        'radar': '//api.buienradar.nl/image/1.0/radarmapnl/?ext=png&l=1',

        'weatherIcon': '/digitalsignage/assets/interface/images/buienradar/weather/{icon}.svg',
        'windIcon': '/digitalsignage/assets/interface/images/buienradar/wind/{icon}.svg',

        'limit': 4,

        'loop': false
    };

    /**
     * Enumeration for types.
     * @public.
     * @readonly.
     * @enum {String}.
     */
    SlideBuienradar.Type = {
        'Event': 'event'
    };

    /**
     * Initializes the Buienradar Slide.
     * @protected.
     */
    SlideBuienradar.prototype.initialize = function() {
        this.core.setLog('[SlideBuienradar] initialize');

        this.core.setPlaceholders(this.$element, this.settings);

        if (null === this.settings.forecast) {
            this.core.setError('[SlideBuienradar] forecast feed is not defined.');
        } else if (null === this.settings.location) {
            this.core.setError('[SlideBuienradar] forecast feed location is not defined.');
        } else {
            this.loadForcast();
        }

        if (null === this.settings.radar) {
            this.core.setError('[SlideBuienradar] radar feed is not defined.');
        } else {
            this.loadRadar();
        }

        this.core.setTimer(this.settings.time);
    };

    /**
     * Loads the data for the Buienradar Slide.
     * @protected.
     */
    SlideBuienradar.prototype.loadForcast = function() {
        this.core.setLog('[SlideBuienradar] loadForcast');

        $.ajax({
            'url'		: this.settings.forecast + this.settings.location,
            'dataType'	: this.settings.forecastType.toUpperCase(),
            'complete'	: $.proxy(function(result) {
               if (200 == result.status) {
                   switch (this.settings.forecastType.toUpperCase()) {
                       case 'JSON':
                           if (result.responseJSON) {
                               if (0 < result.responseJSON.days.length) {
                                   this.data = new Array();

                                   for (var i = 0; i < result.responseJSON.days.length; i++) {
                                       this.data.push(result.responseJSON.days[i]);
                                   }
                               } else {
                                   this.loadForcast();
                               }

                               this.core.setLog('[SlideBuienradar] loadForcast: (items: ' + result.responseJSON.days.length + ').');
                           } else {
                               this.core.setError('[SlideBuienradar] feed could not be read (Format: ' + this.settings.forecastType.toUpperCase() + ').');
                           }

                           break;
                       default:
                           this.core.setError('[SlideBuienradar] feed could not be read because the format is not supported (Format: ' + this.settings.forecastType.toUpperCase() + ').');

                           break;
                   }

                   if (0 == this.$forecasts.length) {
                       this.nextForecast();
                   }
               } else {
                   this.core.setError('[SlideBuienradar] feed could not be loaded (HTTP status: ' + result.status + ').');
               }
            }, this)
        });
    };

    /**
     * Gets the current item count.
     * @public.
     */
    SlideBuienradar.prototype.getCurrent = function() {
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
     * Gets a forecast template and initializes the forecast.
     * @public.
     * @param {Data} array - The item data.
     */
    SlideBuienradar.prototype.getForecast = function(data) {
        this.core.setLog('[SlideBuienradar] getForecast: (date: ' + data.date + ')');

        if ($forecast = this.core.getTemplate('forecast', this.$templates)) {
            $forecast.appendTo(this.core.getPlaceholder('forecasts', this.$element));

            this.core.setPlaceholders($forecast, data);

            return $forecast;
        }

        return null;
    };

    /**
     * Sets the forecast and animate current en next forecast.
     * @public.
     */
    SlideBuienradar.prototype.nextForecast = function() {
        var next = this.getCurrent();

        this.core.setLog('[SlideBuienradar] nextForecast: (next: ' + next + ')');

        if (this.settings.limit > this.$forecasts.length) {
            if (this.data[next]) {
                var data = $.extend({}, this.data[next], {
                    'idx' : next + 1,
                    'date' : this.data[next].date.toString(),
                    'weatherIcon' : this.settings.weatherIcon.replace('{icon}', this.data[next].iconcode.toLowerCase()),
                    'windIcon' : this.settings.windIcon.replace('{icon}', this.data[next].winddirection.toLowerCase())
                });

                if ($forecast = this.getForecast(data)) {
                    $forecast.hide().fadeIn(this.settings.animationTime * 1000, $.proxy(function() {
                        this.nextForecast();
                    }, this));

                    this.$forecasts.push($forecast);
                } else {
                    this.skipForecast('[SlideBuienradar] nextForecast: no forecast available.');
                }
            } else {
                if (this.settings.loop) {
                    this.skipForecast('[SlideBuienradar] nextForecast: no data available.');
                }
            }
        }
    };

    /**
     * Skips the current forecast and animate next forecast.
     * @public.
     * @param {Message} string - The message of skip.
     */
    SlideBuienradar.prototype.skipForecast = function(message) {
        this.core.setLog('[SlideBuienradar] skipForecast: (message: ' + message + ')');

        this.nextForecast();
    };

    /**
     * Loads the radar for the Buienradar Slide.
     * @protected.
     */
    SlideBuienradar.prototype.loadRadar = function() {
        this.core.setLog('[SlideBuienradar] loadRadar');

        this.core.setPlaceholders(this.$element, {
            'radar' : this.settings.radar + '?time=' + (new Date()).getTime()
        });
    };

    /**
     * Registers an event or state.
     * @public.
     * @param {Object} object - The event or state to register.
     */
    SlideBuienradar.prototype.register = function(object) {
        if (object.type === SlideBuienradar.Type.Event) {
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
    SlideBuienradar.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @protected.
     * @param {Array.<String>} events - The events to release.
     */
    SlideBuienradar.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the Buienradar Slide.
     * @public.
     */
    $.fn.SlideBuienradar = function(option, core) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('digitalsignage.slidebuienradar');

            if (!data) {
                data = new SlideBuienradar(this, typeof option == 'object' && option, core);

                $this.data('digitalsignage.slidebBuienradar', data);

                $.each([], function(i, event) {
                    data.register({ type: Slidebuienradar.Type.Event, name: event });

                    data.$element.on(event + '.digitalsignage.slidebuienradar.core', $.proxy(function(e) {
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
    $.fn.SlideBuienradar.Constructor = SlideBuienradar;

})(window.Zepto || window.jQuery, window, document);
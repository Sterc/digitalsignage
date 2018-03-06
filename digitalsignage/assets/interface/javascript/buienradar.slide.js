/* Javascript for DigitalSignage. (c) Sterc.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- Slide Buienradar ------------------------------------------------------------------ */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Buienradar Slide.
     * @class SlideBuienradar.
     * @param {HTMLElement} element - The element of the Social Media Plugin.
     * @param {Array} options - The options of the Social Media Plugin.
     * @param {Object} core - The DigitalSignage object for the Social Media Plugin.
     */
    function SlideBuienradar(element, options, core) {
        /**
         * The DigitalSignage object for the Buienradar Slide.
         */
        this.core = core;

        /**
         * Currently suppressed events to prevent them from beeing retriggered.
         */
        this._supress = {};

        /**
         * Plugin element of the Buienradar slide.
         */
        this.$element = $(element);

        /**
         * Current settings for the Buienradar Slide.
         */
        this.settings = $.extend({}, SlideBuienradar.Defaults, options);

        /**
         * All templates of the Buienradar Slide.
         */
        this.$templates = this.core.getTemplates(this.$element);

        /**
         * All forecasts of the Buienradar Slide.
         */
        this.$items = [];

        this.initialize();
    }

    /**
     * Default options for the Buienradar Slide.
     * @public.
     */
    SlideBuienradar.Defaults = {
        time            : 15,

        location        : null,

        animationTime   : 1,

        forecast        : '//api.buienradar.nl/data/forecast/1.1/daily/',
        forecastType    : 'JSON',

        radar           : '//api.buienradar.nl/image/1.0/radarmapnl/?ext=png&l=1',

        weatherIcon     : '/digitalsignage/assets/interface/images/buienradar/weather/{icon}.svg',
        windIcon        : '/digitalsignage/assets/interface/images/buienradar/wind/{icon}.svg',

        limit           : 4
    };

    /**
     * Enumeration for types.
     */
    SlideBuienradar.Type = {
        Event : 'event'
    };

    /**
     * Initializes the Buienradar Slide.
     */
    SlideBuienradar.prototype.initialize = function() {
        this.core.setLog('[' + this.constructor.name + '] initialize');

        this.core.setData('slide-' + this.settings.id, null, -1);

        this.core.setPlaceholders(this.$element, this.settings);

        if (this.settings.location === null) {
            this.core.setError('[' + this.constructor.name + '] initialize: ' + this.core.getLexicon('slidebuienradar_error_location'));
        } else {
            if (this.settings.forecast === null) {
                this.core.setError('[' + this.constructor.name + '] initialize: ' + this.core.getLexicon('slidebuienradar_error_forecast'));
            } else {
                this.loadForcast();
            }

            if (null === this.settings.radar) {
                this.core.setError('[' + this.constructor.name + '] initialize: ' + this.core.getLexicon('slidebuienradar_error_radar'));
            } else {
                this.loadRadar();
            }
        }

        this.core.setTimer(this.settings.time);
    };

    /**
     * Loads the data for the Buienradar Slide.
     */
    SlideBuienradar.prototype.loadForcast = function() {
        this.core.setLog('[' + this.constructor.name + '] loadForcast');

        $.ajax({
            url         : this.core.getAjaxUrl(this.settings.forecast + this.settings.location),
            dataType    : this.settings.forecastType.toUpperCase(),
            complete    : $.proxy(function(result) {
                if (parseInt(result.status) === 200) {
                    switch (this.settings.forecastType.toUpperCase()) {
                        case 'JSON':
                            if (result.responseJSON) {
                                if (result.responseJSON.days.length > 0) {
                                    var data = [];

                                    for (var i = 0; i < result.responseJSON.days.length; i++) {
                                        data.push(result.responseJSON.days[i]);
                                    }

                                    this.core.setData('slide-' + this.settings.id, data, -1);

                                    this.core.setLog('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('slidebuienradar_forecast_loaded', {
                                        items : result.responseJSON.days.length
                                    }));
                                } else {
                                    this.core.setLog('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('slidebuienradar_error_forecast_empty', {
                                        items : result.responseJSON.days.length
                                    }));
                                }
                            } else {
                                this.core.setError('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('slidebuienradar_error_forecast_format', {
                                    format : this.settings.forecastType.toUpperCase()
                                }));
                            }

                            break;
                        default:
                            this.core.setError('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('slidebuienradar_error_forecast_format', {
                                format : this.settings.forecastType.toUpperCase()
                            }));

                            break;
                    }
                } else {
                    this.core.setError('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('slidebuienradar_error_forecast_http', {
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
    SlideBuienradar.prototype.start = function() {
        if (this.core.getData('slide-' + this.settings.id, 'length') > 0) {
            this.nextForecast();
        }
    },

    /**
     * Gets a forecast template and initializes the forecast.
     * @param {Array} data - The forecast data.
     */
    SlideBuienradar.prototype.getForecast = function(data) {
        this.core.setLog('[' + this.constructor.name + '] getForecast: (date: ' + data.date + ')');

        if ($forecast = this.core.getTemplate('forecast', this.$templates)) {
            $forecast.appendTo(this.core.getPlaceholder('forecasts', this.$element));

            this.core.setPlaceholders($forecast, data);

            return $forecast;
        }

        return null;
    };

    /**
     * Sets the forecast and animate current en next forecast.
     */
    SlideBuienradar.prototype.nextForecast = function() {
        var next = this.core.getCurrentDataIndex('slide-' + this.settings.id, 'next', this.settings.limit);

        this.core.setLog('[' + this.constructor.name + '] nextForecast: (next: ' + next + ')');

        if (next !== null) {
            if (this.settings.limit > this.$items.length) {
                if (null !== (data = this.core.getData('slide-' + this.settings.id, next))) {
                    var data = $.extend({}, data, {
                        idx             : next + 1,
                        date            : data.date.toString(),
                        weatherIcon     : this.settings.weatherIcon.replace('{icon}', data.iconcode.toLowerCase()),
                        windIcon        : this.settings.windIcon.replace('{icon}', data.winddirection.toLowerCase())
                    });

                    if ($forecast = this.getForecast(data)) {
                        $forecast.hide().fadeIn(this.settings.animationTime * 1000, $.proxy(function() {
                            this.nextForecast();
                        }, this));

                        this.$items.push($forecast);
                    } else {
                        this.core.setLog('[' + this.constructor.name + '] nextForecast: ' + this.core.getLexicon('slidebuienradar_error_no_item'));

                        this.nextForecast();
                    }
                } else {
                    this.core.setLog('[' + this.constructor.name + '] nextForecast: ' + this.core.getLexicon('slidebuienradar_error_no_item_data'));

                    this.nextForecast();
                }
            }
        } else {
            this.core.setError('[' + this.constructor.name + '] nextForecast: ' + this.core.getLexicon('slidebuienradar_error_no_data'));
        }
    };

    /**
     * Loads the radar for the Buienradar Slide.
     */
    SlideBuienradar.prototype.loadRadar = function() {
        this.core.setLog('[' + this.constructor.name + '] loadRadar');

        this.core.setPlaceholders(this.$element, {
            radar : this.settings.radar + '&hash=' + (new Date()).getTime()
        }, false);
    };

    /**
     * Registers an event or state.
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
    SlideBuienradar.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @param {Array} events - The events to release.
     */
    SlideBuienradar.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the Buienradar Slide.
     */
    $.fn.SlideBuienradar = function(option, core) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('digitalsignage.slidebuienradar');

            if (!data) {
                data = new SlideBuienradar(this, typeof option === 'object' && option, core);

                $this.data('digitalsignage.slidebBuienradar', data);

                $.each([], function(i, event) {
                    data.register({type: SlideBuienradar.Type.Event, name: event});

                    data.$element.on(event + '.digitalsignage.slidebuienradar.core', $.proxy(function(e) {
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
     * The constructor for the Buienradar Slide.
     */
    $.fn.SlideBuienradar.Constructor = SlideBuienradar;

    /**
     * The lexicons for the Ticker Plugin.
     */
    $.extend($.fn.DigitalSignage.lexicons, {
        slidebuienradar_error_location          : 'Buienradar kon niet geladen worden omdat de locatie niet gedefinieerd is.',
        slidebuienradar_error_forecast          : 'Buienradar weersvoorspelling kon niet geladen worden omdat de weersvoorspelling feed niet gedefinieerd is.',
        slidebuienradar_error_forecast_http     : 'Weersvoorspelling feed kon niet geladen worden (HTTP status: %status%).',
        slidebuienradar_error_forecast_format   : 'Weersvoorspelling feed kon niet geladen worden omdat het formaat niet ondersteund word (Formaat: %format%).',
        slidebuienradar_error_forecast_empty    : 'Weersvoorspelling feed kon niet geladen worden omdat het geen weersvoorspellingen bevat.',
        slidebuienradar_forecast_loaded         : 'Weersvoorspelling feed geladen met %items% weersvoorspellingen.',
        slidebuienradar_error_radar             : 'Buienradar radarkon niet geladen worden omdat de radar feed niet gedefinieerd is.',

        slidebuienradar_error_no_data           : 'Geen data beschikbaar.',
        slidebuienradar_error_no_item           : 'Geen item beschikbaar.',
        slidebuienradar_error_no_item_data      : 'Geen item data beschikbaar.'
    });
})(window.Zepto || window.jQuery, window, document);
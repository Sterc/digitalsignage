/* Javascript for DigitalSignage. (c) Sterc.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- Slide Clock ----------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Clock Slide.
     * @class SlideClock.
     * @param {HTMLElement} element - The element of the Clock Slide.
     * @param {Array} options - The options of the Clock Slide.
     * @param {Object} core - The DigitalSignage object for the Clock Slide.
     */
    function SlideClock(element, options, core) {
        /**
         * The DigitalSignage object for the Clock Slide.
         */
        this.core = core;

        /**
         * Currently suppressed events to prevent them from beeing retriggered.
         */
        this._supress = {};

        /**
         * Plugin element of the Clock Slide.
         */
        this.$element = $(element);

        /**
         * Current settings for the Clock Slide.
         */
        this.settings = $.extend({}, SlideClock.Defaults, options);

        /**
         * The data of the Clock Slide.
         */
        this.data = {};

        this.initialize();
    }

    /**
     * Default options for the Clock Slide.
     */
    SlideClock.Defaults = {
        time        : 15
    };

    /**
     * Enumeration for types.
     */
    SlideClock.Type = {
        Event : 'event'
    };

    /**
     * Initializes the Clock Slide.
     */
    SlideClock.prototype.initialize = function() {
        this.core.setLog('[' + this.constructor.name + '] initialize');

        this.core.setPlaceholders(this.$element, this.settings);

        this.clockInitialize();
        this.clockAnimatate();

        this.core.setTimer(this.settings.time);
    };

    /**
     * Set up Clock.
     */
    SlideClock.prototype.clockInitialize = function() {
        for (var i = 1; i <= 60; i++) {
            this.clockTick(i);
        }
    };

    /**
     * Set up Clock.
     * @param {Number} n.
     */
    SlideClock.prototype.clockTick = function(n) {
        var tickClass   = 'tick',
            tick        = $('<div />'),
            tickBox     = $('<div class="clock-analog--facebox" />');

        if (n % 5 === 0) {
            tickClass = (n % 15 === 0) ? 'tick-large' : 'tick-medium';

            var num = $('<div class="num" />').text(n / 5).css({'transform' : 'rotate(-' + (n * 6) + 'deg)'});

            if (n >= 50) {
                num.css({'left' : '-0.5em'});
            }

            tickBox.append(num);
        }

        $('.clock-analog').append(
            tickBox.append(tick.addClass(tickClass)).css({'transform': 'rotate(' + (n * 6) + 'deg)'})
        );
    };

    /**
     * Set up Clock.
     */
    SlideClock.prototype.clockAnimatate = function() {
        var now             = new Date(),
            hours           = now.getHours(),
            minutes         = now.getMinutes(),
            seconds         = now.getSeconds(),
            milliseconds    = now.getMilliseconds();

        var degSeconds  = (seconds * 6) + (6 / 1000 * milliseconds);
        var degMinutes  = (minutes * 6) + (6 / 60 * seconds) + (6 / (60 * 1000) * milliseconds);
        var degHours    = (hours * 30) + (30 / 60 * minutes);

        $('.clock-analog--second').css({'transform' : 'rotate(' + degSeconds + 'deg)'});
        $('.clock-analog--minute').css({'transform' : 'rotate(' + degMinutes + 'deg)'});
        $('.clock-analog--hour').css({'transform' : 'rotate(' + degHours + 'deg)'});

        requestAnimationFrame($.proxy(function() {
            this.clockAnimatate();
        }).bind(this));
    };

    /**
     * Registers an event or state.
     * @param {Object} object - The event or state to register.
     */
    SlideClock.prototype.register = function(object) {
        if (object.type === SlideClock.Type.Event) {
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
    SlideClock.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @param {Array} events - The events to release.
     */
    SlideClock.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the Clock Slide.
     */
    $.fn.SlideClock = function(option, core) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('digitalsignage.slideclock');

            if (!data) {
                data = new SlideClock(this, typeof option === 'object' && option, core);

                $this.data('digitalsignage.slideclock', data);

                $.each([], function(i, event) {
                    data.register({type: SlideClock.Type.Event, name: event});

                    data.$element.on(event + '.digitalsignage.slideclock.core', $.proxy(function(e) {
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
     * The constructor for the Clock Slide.
     */
    $.fn.SlideClock.Constructor = SlideClock;

    /**
     * The lexicons for the Clock Slide.
     */
    $.extend($.fn.DigitalSignage.lexicons, {

    });
})(window.Zepto || window.jQuery, window, document);
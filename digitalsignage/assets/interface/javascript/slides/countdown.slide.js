/* Javascript for DigitalSignage. (c) Sterc.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- Slide Countdown ----------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Countdown Slide.
     * @class SlideCountdown.
     * @param {HTMLElement} element - The element of the Countdown Slide.
     * @param {Array} options - The options of the Countdown Slide.
     * @param {Object} core - The DigitalSignage object for the Countdown Slide.
     */
    function SlideCountdown(element, options, core) {
        /**
         * The DigitalSignage object for the Countdown Slide.
         */
        this.core = core;

        /**
         * Currently suppressed events to prevent them from beeing retriggered.
         */
        this._supress = {};

        /**
         * Plugin element of the Countdown Slide.
         */
        this.$element = $(element);

        /**
         * Current settings for the Countdown Slide.
         */
        this.settings = $.extend({}, SlideCountdown.Defaults, options);

        /**
         * The data of the Countdown Slide.
         */
        this.data = {};

        this.initialize();
    }

    /**
     * Default options for the Countdown Slide.
     */
    SlideCountdown.Defaults = {
        time        : 15
    };

    /**
     * Enumeration for types.
     */
    SlideCountdown.Type = {
        Event : 'event'
    };

    /**
     * Initializes the Countdown Slide.
     */
    SlideCountdown.prototype.initialize = function() {
        this.core.setLog('[' + this.constructor.name + '] initialize');

        this.core.setPlaceholders(this.$element, this.settings);

        this.countDownInitialize(this.settings.date);

        this.core.setTimer(this.settings.time);
    };

    /**
     * Start countdown
     */
    SlideCountdown.prototype.countDownInitialize = function(date) {
        var $countdown  = $('.countdown-container'),
            currDate    = '00:00:00:00:00',
            nextDate    = '00:00:00:00:00',
            labels      = [
                this.core.getLexicon('slidecountdown_weeks'),
                this.core.getLexicon('slidecountdown_days'),
                this.core.getLexicon('slidecountdown_hours'),
                this.core.getLexicon('slidecountdown_minutes'),
                this.core.getLexicon('slidecountdown_seconds')
            ],
            template = _.template('<div class="time <%= label %>"><span class="count curr top"><span><%= curr %></span></span><span class="count next top"><span><%= next %></span></span><span class="count next bottom"><span><%= next %></span></span><span class="count curr bottom"><span><%= curr %></span></span><span class="label"><%= label.length < 6 ? label : label.substr(0, 3)  %></span></div>');

        function strfobj(str) {
            var obj     = {},
                parsed  = str.match(/([0-9]{2})/gi);

            labels.forEach(function(label, i) {
                obj[label] = parsed[i];
            });

            return obj;
        }

        function diff(obj1, obj2) {
            var diff = [];

            labels.forEach(function(key) {
                if (obj1[key] !== obj2[key]) {
                    diff.push(key);
                }
            });

            return diff;
        }

        var initData = strfobj(currDate);

        labels.forEach(function(label, i) {
            $countdown.append(template({
                curr    : initData[label],
                next    : initData[label],
                label   : label
            }));
        });

        $countdown.countdown(new Date(date), function(event) {
            var data    = {},
                newDate = event.strftime('%w:%d:%H:%M:%S');

            if (newDate !== nextDate) {
                currDate = nextDate;
                nextDate = newDate;

                data = {
                    curr    : strfobj(currDate),
                    next    : strfobj(nextDate)
                };


                diff(data.curr, data.next).forEach(function(label) {
                    var $node = $countdown.find('.' + label);

                    $node.removeClass('flip');
                    $node.find('.curr span').text(data.curr[label]);
                    $node.find('.next span').text(data.next[label]);

                    _.delay(function($node) {
                        $node.addClass('flip');
                    }, 50, $node);
                });
            }
        });
    };

    /**
     * Registers an event or state.
     * @param {Object} object - The event or state to register.
     */
    SlideCountdown.prototype.register = function(object) {
        if (object.type === SlideCountdown.Type.Event) {
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
    SlideCountdown.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @param {Array} events - The events to release.
     */
    SlideCountdown.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the Countdown Slide.
     */
    $.fn.SlideCountdown = function(option, core) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('digitalsignage.slidecountdown');

            if (!data) {
                data = new SlideCountdown(this, typeof option === 'object' && option, core);

                $this.data('digitalsignage.slidecountdown', data);

                $.each([], function(i, event) {
                    data.register({type: SlideCountdown.Type.Event, name: event});

                    data.$element.on(event + '.digitalsignage.slidecountdown.core', $.proxy(function(e) {
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
     * The constructor for the Countdown Slide.
     */
    $.fn.SlideCountdown.Constructor = SlideCountdown;

    /**
     * The lexicons for the Countdown Slide.
     */
    $.extend($.fn.DigitalSignage.lexicons, {
        slidecountdown_weeks    : 'Weken',
        slidecountdown_days     : 'Dagen',
        slidecountdown_hours    : 'Uren',
        slidecountdown_minutes  : 'Minuten',
        slidecountdown_seconds  : 'Seconden'
    });
})(window.Zepto || window.jQuery, window, document);
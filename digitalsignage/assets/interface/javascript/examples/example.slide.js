/* Javascript for DigitalSignage. (c) Sterc.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- Slide Example --------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Example Slide.
     * @class SlideExample.
     * @param {HTMLElement} element - The element of the Example Slide.
     * @param {Array} options - The options of the Example Slide.
     * @param {Object} core - The DigitalSignage object for the Example Slide.
     */
    function SlideExample(element, options, core) {
        /**
         * The DigitalSignage object for the Example Slide.
         */
        this.core = core;

        /**
         * Currently suppressed events to prevent them from beeing retriggered.
         */
        this._supress = {};

        /**
         * Plugin element of the Example Slide.
         */
        this.$element = $(element);

        /**
         * Current settings for the Example Slide.
         */
        this.settings = $.extend({}, SlideExample.Defaults, options);

        /**
         * The data of the Example Slide.
         */
        this.data = {};

        this.initialize();
    }

    /**
     * Default options for the Example Slide.
     */
    SlideExample.Defaults = {
        time        : 15,

        setting1    : 'value-1',
        setting2    : 'value-2'
    };

    /**
     * Enumeration for types.
     */
    SlideExample.Type = {
        Event : 'event'
    };

    /**
     * Initializes the Example Slide.
     */
    SlideExample.prototype.initialize = function() {
        this.core.setLog('[' + this.constructor.name + '] initialize');

        this.core.setPlaceholders(this.$element, this.settings);

        this.core.setTimer(this.settings.time);
    };

    /**
     * Registers an event or state.
     * @param {Object} object - The event or state to register.
     */
    SlideExample.prototype.register = function(object) {
        if (object.type === SlideExample.Type.Event) {
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
    SlideExample.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @param {Array} events - The events to release.
     */
    SlideExample.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the Example Slide.
     */
    $.fn.SlideExample = function(option, core) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('digitalsignage.slideexample');

            if (!data) {
                data = new SlideExample(this, typeof option === 'object' && option, core);

                $this.data('digitalsignage.slideexample', data);

                $.each([], function(i, event) {
                    data.register({type: SlideExample.Type.Event, name: event});

                    data.$element.on(event + '.digitalsignage.slideexample.core', $.proxy(function(e) {
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
     * The constructor for the Example Slide.
     */
    $.fn.SlideExample.Constructor = SlideExample;

    /**
     * The lexicons for the Example Slide.
     */
    $.extend($.fn.DigitalSignage.lexicons, {
        slideexample_lexicon1           : 'Lexicon 1',
        slideexample_lexicon2           : 'Lexicon 2',
        slideexample_lexicon3           : 'Lexicon 3',
    });
})(window.Zepto || window.jQuery, window, document);
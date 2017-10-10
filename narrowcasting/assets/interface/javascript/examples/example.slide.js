/* Javascript for Narrowcasting. (c) Sterc.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- Slide Example --------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Example Slide.
     * @class SlideExample.
     * @public.
     * @param {Element} HTMLElement|jQuery - The element of the Example Slide.
     * @param {Options} array - The options of the Example Slide.
     * @param {Core} Object - The Narrowcasting object for the Example Slide.
     */
    function SlideExample(element, options, core) {
        /**
         * The Narrowcasting object for the Example Slide.
         * @public.
         */
        this.core = core;

        /**
         * Current settings for the Example Slide.
         * @public.
         */
        this.settings = $.extend({}, SlideExample.Defaults, options);

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
         * The data.
         * @protected.
         */
        this.data = {};

        this.initialize();
    }

    /**
     * Default options for the Example Slide.
     * @public.
     */
    SlideExample.Defaults = {
        'time': 15,

        'setting1': 'value-1',
        'setting2': 'value-2'
    };

    /**
     * Enumeration for types.
     * @public.
     * @readonly.
     * @enum {String}.
     */
    SlideExample.Type = {
        'Event': 'event'
    };

    /**
     * Initializes the Example Slide.
     * @protected.
     */
    SlideExample.prototype.initialize = function() {
        this.core.setLog('[SlideExample] initialize');

        this.core.setPlaceholders(this.$element, this.settings);

        this.core.setTimer(this.settings.time);
    };

    /**
     * Registers an event or state.
     * @public.
     * @param {Object} object - The event or state to register.
     */
    SlideExample.prototype.register = function(object) {
        if (object.type === SlideExample.Type.Event) {
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
    SlideExample.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @protected.
     * @param {Array.<String>} events - The events to release.
     */
    SlideExample.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the Example Slide.
     * @public.
     */
    $.fn.SlideExample = function(option, core) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('narrowcasting.slideexample');

            if (!data) {
                data = new SlideExample(this, typeof option == 'object' && option, core);

                $this.data('narrowcasting.slideexample', data);

                $.each([], function(i, event) {
                    data.register({ type: SlideExample.Type.Event, name: event });

                    data.$element.on(event + '.narrowcasting.slideexample.core', $.proxy(function(e) {
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
    $.fn.SlideExample.Constructor = SlideExample;

})(window.Zepto || window.jQuery, window, document);
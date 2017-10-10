/* Javascript for Narrowcasting. (c) Sterc.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- Example Plugin -------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Example Plugin.
     * @class Example Plugin.
     * @public.
     * @param {Element} HTMLElement|jQuery - The element of the Example Plugin.
     * @param {Options} array - The options of the Example Plugin.
     * @param {Core} Object - The Narrowcasting object for the Example Plugin.
     */
    function ExamplePlugin(element, options, core) {
        /**
         * The Narrowcasting object for the Example Plugin.
         * @public.
         */
        this.core = core;

        /**
         * Plugin element.
         * @public.
         */
        this.$element = $(element);

        /**
         * Current settings for the Example Plugin.
         * @public.
         */
        this.settings = $.extend({}, ExamplePlugin.Defaults, options, this.core.loadCustomPluginSettings(this.$element));

        /**
         * Currently suppressed events to prevent them from beeing retriggered.
         * @protected.
         */
        this._supress = {};

        /**
         * All templates of the Example Plugin.
         * @protected.
         */
        this.$templates = this.core.getTemplates(this.$element);

        this.initialize();
    }

    /**
     * Default options for the Example Plugin.
     * @public.
     */
    ExamplePlugin.Defaults = {
        'setting1': 'value-1',
        'setting2': 'value-2'
    };

    /**
     * Enumeration for types.
     * @public.
     * @readonly.
     * @enum {String}.
     */
    ExamplePlugin.Type = {
        'Event': 'event'
    };

    /**
     * Initializes the Example Plugin.
     * @protected.
     */
    ExamplePlugin.prototype.initialize = function() {
        this.core.setLog('[ExamplePlugin] initialize');
    };

    /**
     * Registers an event or state.
     * @public.
     * @param {Object} object - The event or state to register.
     */
    ExamplePlugin.prototype.register = function(object) {
        if (object.type === ExamplePlugin.Type.Event) {
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
    ExamplePlugin.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @protected.
     * @param {Array.<String>} events - The events to release.
     */
    ExamplePlugin.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the Example Plugin.
     * @public.
     */
    $.fn.ExamplePlugin = function(core, option) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('narrowcasting.exampleplugin');

            if (!data) {
                data = new ExamplePlugin(this, typeof option == 'object' && option, core);

                $this.data('narrowcasting.exampleplugin', data);

                $.each([], function(i, event) {
                    data.register({ type: ExamplePlugin.Type.Event, name: event });

                    data.$element.on(event + '.narrowcasting.exampleplugin.core', $.proxy(function(e) {
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
    $.fn.ExamplePlugin.Constructor = ExamplePlugin;

})(window.Zepto || window.jQuery, window, document);
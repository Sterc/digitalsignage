/* Javascript for DigitalSignage. (c) Sterc.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- Example Plugin -------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Example Plugin.
     * @class ExamplePlugin.
     * @param {HTMLElement} element - The element of the Example Plugin.
     * @param {Array} options - The options of the Example Plugin.
     * @param {Object} core - The DigitalSignage object for the SExample Plugin.
     */
    function ExamplePlugin(element, options, core) {
        /**
         * The DigitalSignage object for the Example Plugin.
         */
        this.core = core;

        /**
         * Currently suppressed events to prevent them from beeing retriggered.
         */
        this._supress = {};

        /**
         * Plugin element of the Example Plugin.
         */
        this.$element = $(element);

        /**
         * Current settings for the Example Plugin.
         */
        this.settings = $.extend({}, ExamplePlugin.Defaults, options, this.core.loadCustomPluginSettings(this.$element));

        /**
         * All templates of the Example Plugin.
         */
        this.$templates = this.core.getTemplates(this.$element);

        this.initialize();
    }

    /**
     * Default options for the Example Plugin.
     */
    ExamplePlugin.Defaults = {
        setting1    : 'value-1',
        setting2    : 'value-2'
    };

    /**
     * Enumeration for types.
     */
    ExamplePlugin.Type = {
        Event : 'event'
    };

    /**
     * Initializes the Example Plugin.
     */
    ExamplePlugin.prototype.initialize = function() {
        this.core.setLog('[' + this.constructor.name + '] initialize');
    };

    /**
     * Registers an event or state.
     * @param {Object} object - The event or state to register.
     */
    ExamplePlugin.prototype.register = function(object) {
        if (object.type === ExamplePlugin.Type.Event) {
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
    ExamplePlugin.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @param {Array} events - The events to release.
     */
    ExamplePlugin.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the Example Plugin.
     */
    $.fn.ExamplePlugin = function(core, option) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('digitalsignage.exampleplugin');

            if (!data) {
                data = new ExamplePlugin(this, typeof option === 'object' && option, core);

                $this.data('digitalsignage.exampleplugin', data);

                $.each([], function(i, event) {
                    data.register({type: ExamplePlugin.Type.Event, name: event});

                    data.$element.on(event + '.digitalsignage.exampleplugin.core', $.proxy(function(e) {
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
     * The constructor for the Example Plugin.
     * @public.
     */
    $.fn.ExamplePlugin.Constructor = ExamplePlugin;

    /**
     * The lexicons for the Example Plugin.
     */
    $.extend($.fn.DigitalSignage.lexicons, {
        exampleplugin_lexicon1          : 'Lexicon 1',
        exampleplugin_lexicon2          : 'Lexicon 2',
        exampleplugin_lexicon3          : 'Lexicon 3',
    });
})(window.Zepto || window.jQuery, window, document);
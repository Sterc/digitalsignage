/* Javascript for v.v. Opende narrowcasting project. (c) Oetzie.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- jQuery onload --------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

$(document).ready(function() {
    $('.window').Narrowcasting({
        'debug'		: true,
        'clock'		: {
            'element'	: '.clock'
        },
        'ticker'	: {
            'element'	: '.ticker',
            'settings'	: {
                'feed'		: '/narrowcasting/newsticker.php',
                'feedType'	: 'JSON'
            }
        },
        'feed' : '/narrowcasting/content.php'
    });
});

/* ----------------------------------------------------------------------------------------- */
/* ----- Narrowcasting --------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    function Narrowcasting(element, options) {
        /**
         * Current settings for the Narrowcasting.
         * @public.
         */
        this.settings = $.extend({}, Narrowcasting.Defaults, options);

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
         * All templates of the Narrowcasting.
         * @protected.
         */
        this.$templates = this.getTemplates(this.$element, '.slides');

        /**
         * All special templates of the Narrowcasting.
         * @protected.
         */
        this.$specialTemplates = this.getTemplates(this.$element, true);

        /**
         * All errors.
         * @protected.
         */
        this.$errors = [];

        /**
         * The data.
         * @protected.
         */
        this.data = [];

        /**
         * The number of times that the data of the Narrowcasting is loaded.
         * @protected.
         */
        this.dataRefresh = 0;

        /**
         * The timer.
         * @protected.
         */
        this.timer = null;

        /**
         * The current slide count.
         * @protected.
         */
        this.current = -1;

        /**
         * The current slides of Narrowcasting.
         * @protected.
         */
        this.$slides = [];

        this.$parameters = [];

        this.initialize();
    }

    /**
     * Default options for the Narrowcasting.
     * @public.
     */
    Narrowcasting.Defaults = {
        'debug': false,
        'clock': null,
        'ticker': null,

        'timer': true,
        'timerType': 'vertical',
        'timerClass': 'timer',

        'animation': 'fade',
        'animationTime': 1,

        'feed': null,
        'feedType': 'JSON',

        'loadInterval': 900
    };

    /**
     * Enumeration for types.
     * @public.
     * @readonly.
     * @enum {String}.
     */
    Narrowcasting.Type = {
        'Event': 'event'
    };

    /**
     * Initializes the Narrowcasting.
     * @protected.
     */
    Narrowcasting.prototype.initialize = function() {
        this.setUrlParameters(window.location.href);

        if (null === this.settings.feed) {
            this.setError('Narrowcasting feed is niet ingesteld.');
        } else {
            this.loadData();

            if (0 < this.settings.loadInterval) {
                setInterval($.proxy(function(event) {
                    this.loadData();
                }, this), this.settings.loadInterval * 1000);
            }
        }

        if (null !== this.settings.clock) {
            if (this.settings.clock.element) {
                $(this.settings.clock.element).Clock(this.settings.clock.settings, this);
            }
        }

        if (null !== this.settings.ticker) {
            if (this.settings.ticker.element) {
                $(this.settings.ticker.element).Newsticker(this.settings.ticker.settings, this);
            }
        }

        this.checkSchedule();
    };

    /**
     * Set url parameters.
     *
     * @param url
     */
    Narrowcasting.prototype.setUrlParameters = function(url) {
        url = url.split('+').join(' ');

        var tokens,
            re = /[?&]?([^=]+)=([^&]*)/g;

        while (tokens = re.exec(url)) {
            this.$parameters[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
        }
    };

    /**
     * Check if a new schedule is available.
     */
    Narrowcasting.prototype.checkSchedule = function () {
        var self = this,
            player = /pl=([^&]+)/.exec(window.location.href)[1],
            broadcast = parseInt(/bc=([^&]+)/.exec(window.location.href)[1]);

        setTimeout($.proxy(function () {
            $.ajax({
                url : self.settings.connectorUrl,
                method : 'post',
                data : {
                    action : 'web/player',
                    method : 'checkSchedule',
                    player : player,
                    r : Math.random()
                }
            }).success(function(response) {
                if (response.results.status === 200) {
                    console.log(response.results.data.bc);
                    console.log(broadcast);
                    if (typeof response.results !== 'undefined' && parseInt(response.results.data.bc) !== broadcast) {
                        window.location.href = response.results.data.url.replace(/&amp;/g, '&');
                        return true;
                    }
                }

                self.checkSchedule();
            });
        }, self), 2000);
    };

    /**
     * Loads the data for the Narrowcasting.
     * @protected.
     */
    Narrowcasting.prototype.loadData = function() {
        $.ajax({
            'url'		: this.settings.feed,
            'dataType'	: this.settings.feedType.toUpperCase(),
            'complete'	: $.proxy(function(result) {
                if (200 == result.status) {
                    this.data = new Array();

                    switch (this.settings.feedType.toUpperCase()) {
                        case 'JSON':
                            if (result.responseJSON) {
                                for (var i = 0; i < result.responseJSON.slides.length; i++) {
                                    this.data.push(result.responseJSON.slides[i]);
                                }
                            } else {
                                this.setError('Narrowcasting feed kon niet gelezen worden (Formaat: ' + this.settings.feedType.toUpperCase() + ').');
                            }

                            break;
                        default:
                            this.setError('Narrowcasting feed kon niet gelezen worden omdat het formaat niet ondersteund word (Formaat: ' + this.settings.feedType.toUpperCase() + ').');

                            break;
                    }

                    if (0 == this.$slides.length) {
                        this.nextSlide();
                    }
                } else {
                    this.setError('Narrowcasting feed kon niet geladen worden (HTTP status: ' + result.status + ').');
                }

                this.dataRefresh++;
            }, this)
        });
    };

    /**
     * Display an error.
     * @public.
     * @param {Message} String - The error to display.
     */
    Narrowcasting.prototype.setError = function(message) {
        console.warn(message);

        if ($error = this.getTemplate('error', this.$specialTemplates)) {
            if (typeof message !== 'object') {
                message = {
                    'title'		: 'Foutmelding',
                    'message' 	: message
                };
            }

            this.setPlaceholders($error, message).appendTo(this.$element);

            this.$errors.push($error);

            setTimeout($.proxy(function(event) {
                if ($error = this.$errors.shift()) {
                    $error.remove();
                }
            }, this), 5000);
        }
    };

    /**
     * Sets all the templates of a narrowcasting object.
     * @public.
     * @param {$element} HTMLelement - The HTML object.
     * @param {Level} boolean - Get templates from level.
     */
    Narrowcasting.prototype.getTemplates = function($element, level) {
        var	templates = [],
            $templates = [];

        if (level) {
            if (typeof level === 'string') {
                $templates = $(level, $element).children('[data-template]');
            } else {
                $templates= $element.children('[data-template]');
            }
        } else {
            $templates = $('[data-template]', $element);
        }

        for (var i = 0; i < $templates.length; i++) {
            var $template = $($templates[i]).remove();

            templates[$template.attr('data-template')] = $template;
        }

        return templates;
    };

    /**
     * Gets a templates of a narrowcasting object.
     * @public.
     * @param {Template} string - The name of the template.
     * @param {$templates} array - The available templates.
     */
    Narrowcasting.prototype.getTemplate = function(template, $templates) {
        if ($templates[template]) {
            return $templates[template].clone();
        }

        return null;
    };

    /**
     * Gets a placeholder of a narrowcasting object.
     * @public.
     * @param {Placeholder} string - The name of the placeholder.
     * @param {$element} HTMLelement - The HTML object.
     */
    Narrowcasting.prototype.getPlaceholder = function(placeholder, $element) {
        return $('[data-placeholder="' + placeholder + '"]', $element);
    }

        /**
     * Sets the placeholder of a narrowcasting object.
     * @public.
     * @param {$template} HTMLelement - The HTML object.
     * @param {Data} object - The data of the placeholders.
     */
    Narrowcasting.prototype.setPlaceholders = function($template, data) {
        var placeholders = $('[data-placeholder]', $template);

        for (var i = 0; i < placeholders.length; i++) {
            if ($placeholder = $(placeholders[i])) {
                var type 	= $placeholder.prop('tagName'),
                    name 	= $placeholder.attr('data-placeholder'),
                    value 	= this.getPlaceholderValue(name, data);

                switch (type) {
                    case 'IMG':
                    case 'IFRAME':
                        if (null !== value && '' !== value) {
                            $placeholder.attr('src', value).show();
                        } else {
                            $placeholder.attr('src', '').hide();
                        }

                        break;
                    default:
                        if (null !== value && '' !== value) {
                            $placeholder.html(value).show();
                        } else {
                            $placeholder.html('').hide();
                        }

                        break;
                }
            }
        }

        var placeholders = $('[data-placeholder-class]', $template);

        for (var i = 0; i < placeholders.length; i++) {
            if ($placeholder = $(placeholders[i])) {
                var type 	= $placeholder.prop('tagName'),
                    name 	= $placeholder.attr('data-placeholder-class'),
                    value 	= this.getPlaceholderValue(name, data);

                $placeholder.addClass(value);
            }
        }

        return $template;
    }

    /**
     * Gets the placeholder value.
     * @public.
     * @param {Name} string|object - The name of the placeholder.
     * @param {Data} object - The data of the placeholder.
     */
    Narrowcasting.prototype.getPlaceholderValue = function(name, data) {
        if (typeof name == 'string') {
            name = name.split('.');
        }

        if (value = data[name.shift()]) {
            if (typeof value == 'object') {
                value = this.getPlaceholderValue(name, value);
            }
        }

        return value;
    };

    /**
     * Sets the timer object.
     * @public.
     * @param {Type} string - The type timer to return.
     */
    Narrowcasting.prototype.getTimer = function(type) {
        if (null === this.timer) {
            this.timer = {
                '$timer'	: $('<div class="' + this.settings.timerClass + ' ' + this.settings.timerClass + '-' + this.settings.timerType + '"/>').css({
                    'opacity' 	: this.settings.timer ? 1 : 0
                }),
                '$progress'	: $('<span class="' + this.settings.timerClass + '-inner"/>')
            };

            this.timer.$timer.append(this.timer.$progress).appendTo(this.$element);
        }

        return this.timer['$' + type];
    }

    /**
     * Sets the time of a slide by the timer object.
     * @public.
     * @param {Time} integer - The time of the slide.
     */
    Narrowcasting.prototype.setTimer = function(time) {
        if ($timer = this.getTimer('progress')) {
            if ('vertical' == this.settings.timerType) {
                var startPosition 	= {'height': '0px'};
                var endPosition		= {'height': '100%'};

            } else {
                var startPosition 	= {'width': '0px'};
                var endPosition		= {'width': '100%'};
            }

            $timer.css(startPosition).animate(endPosition, {
                'easing' 	: 'linear',
                'duration' 	: time * 1000,
                'complete' 	: $.proxy(function(event) {
                    this.nextSlide();
                }, this)
            });
        }
    };

    /**
     * Gets the current slide count.
     * @public.
     */
    Narrowcasting.prototype.getCurrent = function() {
        if (this.current + 1 < this.data.length) {
            this.current = this.current + 1;
        } else {
            this.current = 0;
        }

        return this.current;
    };

    /**
     * Gets a slide template and initializes the slide.
     * @public.
     * @param {current} integer - The slide to initialize.
     */
    Narrowcasting.prototype.getSlide = function(current) {
        if (this.data[current]) {
            var data = $.extend({}, {
                'slide' : 'default'
            }, this.data[current]);

            if ($slide = this.getTemplate(data.slide, this.$templates)) {
                $slide.prependTo($('.slides', this.$element));

                if (data.fullscreen) {
                    this.$element.addClass('slide-fullscreen');
                } else {
                    this.$element.removeClass('slide-fullscreen');
                }

                if (plugin = this.getSlidePlugin(data.slide)) {
                    if ($.fn[plugin]) {
                        $slide[plugin](data, this);
                    } else {
                        var plugin = this.getSlidePlugin('default');

                        if ($.fn[plugin]) {
                            $slide[plugin](data, this);
                        } else {
                            this.setTimer(data.time);
                        }
                    }
                } else {
                    this.setTimer(data.time);
                }

                return $slide;
            }
        }

        return null;
    };

    /**
     * Sets the next slide and animate current en next slide.
     * @public.
     */
    Narrowcasting.prototype.nextSlide = function() {
        if ($slide = this.getSlide(this.getCurrent())) {
            $slide.hide().fadeIn(this.settings.animationTime * 1000);

            if ($current = this.$slides.shift()) {
                $current.show().fadeOut(this.settings.animationTime * 1000, $.proxy(function() {
                    $current.remove();
                }, this));
            }

            this.$slides.push($slide);
        }
    };

    /**
     * Gets the plugin name of the slide.
     * @public.
     * @param {Type} string - The type of the slide.
     */
    Narrowcasting.prototype.getSlidePlugin = function(type) {
        var plugin = ('slide-' + type).split('-');

        for (var i = 0 ; i < plugin.length ; i++) {
            plugin[i] = plugin[i].charAt(0).toUpperCase() + plugin[i].substr(1);
        }

        return plugin.join('');
    };

    /**
     * Registers an event or state.
     * @public.
     * @param {Object} object - The event or state to register.
     */
    Narrowcasting.prototype.register = function(object) {
        if (object.type === Narrowcasting.Type.Event) {
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
    Narrowcasting.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @protected.
     * @param {Array.<String>} events - The events to release.
     */
    Narrowcasting.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the Narrowcasting.
     * @public.
     */
    $.fn.Narrowcasting = function(option) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('narrowcasting');

            if (!data) {
                data = new Narrowcasting(this, typeof option == 'object' && option);

                $this.data('narrowcasting', data);

                $.each([

                ], function(i, event) {
                    data.register({ type: Narrowcasting.Type.Event, name: event });
                    data.$element.on(event + '.narrowcasting.core', $.proxy(function(e) {
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
    $.fn.Narrowcasting.Constructor = Narrowcasting;

})(window.Zepto || window.jQuery, window, document);

/* ----------------------------------------------------------------------------------------- */
/* ----- Slide Default --------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Default Slide.
     * @class SlideDefault.
     * @public.
     * @param {Element} HTMLElement|jQuery - The element of the Default Slide.
     * @param {Options} array - The options of the Default Slide.
     * @param {Core} Object - The Narrowcasting object for the Default Slide.
     */
    function SlideDefault(element, options, core) {
        /**
         * The Narrowcasting object for the Default Slide.
         * @public.
         */
        this.core = core;

        /**
         * Current settings for the Default Slide.
         * @public.
         */
        this.settings = $.extend({}, SlideDefault.Defaults, options);

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
     * Default options for the Default Slide.
     * @public.
     */
    SlideDefault.Defaults = {
        'time': 15
    };

    /**
     * Enumeration for types.
     * @public.
     * @readonly.
     * @enum {String}.
     */
    SlideDefault.Type = {
        'Event': 'event'
    };

    /**
     * Initializes the Default Slide.
     * @protected.
     */
    SlideDefault.prototype.initialize = function() {
        this.core.setPlaceholders(this.$element, this.settings);

        this.core.setTimer(this.settings.time);
    };

    /**
     * Registers an event or state.
     * @public.
     * @param {Object} object - The event or state to register.
     */
    SlideDefault.prototype.register = function(object) {
        if (object.type === SlideDefault.Type.Event) {
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
    SlideDefault.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @protected.
     * @param {Array.<String>} events - The events to release.
     */
    SlideDefault.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the Default Slide.
     * @public.
     */
    $.fn.SlideDefault = function(option, core) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('narrowcasting.slidedefault');

            if (!data) {
                data = new SlideDefault(this, typeof option == 'object' && option, core);

                $this.data('narrowcasting.slidedefault', data);

                $.each([

                ], function(i, event) {
                    data.register({ type: SlideDefault.Type.Event, name: event });
                    data.$element.on(event + '.narrowcasting.slidedefault.core', $.proxy(function(e) {
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
    $.fn.SlideDefault.Constructor = SlideDefault;

})(window.Zepto || window.jQuery, window, document);

/* ----------------------------------------------------------------------------------------- */
/* ----- Clock ----------------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Clock.
     * @class Clock.
     * @public.
     * @param {Element} HTMLElement|jQuery - The element of the Clock.
     * @param {Options} array - The options of the Clock.
     * @param {Core} Object - The Narrowcasting object for the Clock.
     */
    function Clock(element, options, core) {
        /**
         * The Narrowcasting object for the Clock.
         * @public.
         */
        this.core = core;

        /**
         * Current settings for the Clock.
         * @public.
         */
        this.settings = $.extend({}, Clock.Defaults, options);

        /**
         * Plugin element.
         * @public.
         */
        this.$element = $(element);

        this.initialize();
    }

    /**
     * Default options for the Clock.
     * @public.
     */
    Clock.Defaults = {
        'formatTime': '%H:%I',
        'formatDate': '%D %d %M',

        'dateText': ['Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'],
        'monthText': ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december']
    };

    /**
     * Enumeration for types.
     * @public.
     * @readonly.
     * @enum {String}.
     */
    Clock.Type = {
        'Event': 'event'
    };

    /**
     * Initializes the Clock.
     * @protected.
     */
    Clock.prototype.initialize = function() {
        var $time = this.core.getPlaceholder('time', this.$element);
        var $date = this.core.getPlaceholder('date', this.$element);

        setInterval($.proxy(function() {
            var date = new Date(),
                formats = {
                    '%h'	: date.getHours(),
                    '%H'	: this.getZero(date.getHours()),
                    '%i'	: date.getMinutes(),
                    '%I'	: this.getZero(date.getMinutes()),
                    '%s'	: date.getSeconds(),
                    '%S'	: this.getZero(date.getSeconds()),
                    '%j'	: date.getDate(),
                    '%d'	: this.getZero(date.getDate()),
                    '%D'	: this.getText(date.getDay(), 'days').toString().substr(0, 3),
                    '%l'	: this.getText(date.getDay(), 'days').toString(),
                    '%W'	: date.getDay(),
                    '%M'	: this.getText(date.getMonth() + 1, 'months').toString().substr(0, 3),
                    '%F'	: this.getText(date.getMonth() + 1, 'months').toString(),
                    '%m'	: this.getZero(date.getMonth() + 1),
                    '%n'	: date.getMonth() + 1,
                    '%y'	: date.getFullYear().toString().substr(2, 2),
                    '%Y'	: date.getFullYear().toString()
                };

            if ($time[0]) {
                var string = this.settings.formatTime;

                for (format in formats) {
                    string = string.replace(format, formats[format]);
                }

                $time.html(string);
            }

            if ($date[0]) {
                var string = this.settings.formatDate;

                for (format in formats) {
                    string = string.replace(format, formats[format]);
                }

                $date.html(string);
            }
        }, this), 1000);
    };

    /**
     * Gets the leading zero.
     * @protected.
     */
    Clock.prototype.getZero = function(format) {
        if (format < 10) {
            return '0' + format;
        }

        return format;
    };

    /**
     * Gets the translated text.
     * @protected.
     */
    Clock.prototype.getText =function(format, type) {
        switch (type) {
            case 'days':
                if (this.settings.dateText[format]) {
                    return this.settings.dateText[format];
                }

                break;
            case 'months':
                if (this.settings.monthText[format]) {
                    return this.settings.monthText[format];
                }

                break;
        }

        return '';
    }

    /**
     * The jQuery Plugin for the Clock.
     * @public.
     */
    $.fn.Clock = function(option, core) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('narrowcasting.clock');

            if (!data) {
                data = new Clock(this, typeof option == 'object' && option, core);

                $this.data('narrowcasting.clock', data);
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
    $.fn.Clock.Constructor = Clock;

})(window.Zepto || window.jQuery, window, document);

/* ----------------------------------------------------------------------------------------- */
/* ----- Newsticker ------------------------------------------------------------------------ */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Newsticker.
     * @class Newsticker.
     * @public.
     * @param {Element} HTMLElement|jQuery - The element of the Newsticker.
     * @param {Options} array - The options of the Newsticker.
     * @param {Core} Object - The Narrowcasting object for the Newsticker.
     */
    function Newsticker(element, options, core) {
        /**
         * The Narrowcasting object for the Newsticker.
         * @public.
         */
        this.core = core;

        /**
         * Current settings for the Newsticker.
         * @public.
         */
        this.settings = $.extend({}, Newsticker.Defaults, options);

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
         * All templates of the Newsticker.
         * @protected.
         */
        this.$templates = this.core.getTemplates(this.$element);

        /**
         * The data of the Newsticker.
         * @protected.
         */
        this.data = [];

        /**
         * The number of times that the data of the Newsticker is loaded.
         * @protected.
         */
        this.dataRefresh = 0;

        /**
         * All elements of the Newsticker.
         * @protected.
         */
        this.$elements = [];

        this.initialize();
    }

    /**
     * Default options for the Newsticker.
     * @public.
     */
    Newsticker.Defaults = {
        'feed': null,
        'feedType': 'JSON',

        'loadInterval': 900
    };

    /**
     * Enumeration for types.
     * @public.
     * @readonly.
     * @enum {String}.
     */
    Newsticker.Type = {
        'Event': 'event'
    };

    /**
     * Initializes the Newsticker.
     * @protected.
     */
    Newsticker.prototype.initialize = function() {
        if (null === this.settings.feed) {
            this.core.setError('Newsticker feed is niet ingesteld.');
        } else {
            this.loadData();

            if (0 < this.settings.loadInterval) {
                setInterval($.proxy(function(event) {
                    this.loadData();
                }, this), this.settings.loadInterval * 1000);
            }
        }
    };

    /**
     * Loads the data for the Newsticker.
     * @protected.
     */
    Newsticker.prototype.loadData = function() {
        $.ajax({
            'url'		: this.settings.feed,
            'dataType'	: this.settings.feedType.toUpperCase(),
            'complete'	: $.proxy(function(result) {
                if (200 == result.status) {
                    this.data = [];

                    switch (this.settings.feedType.toUpperCase()) {
                        case 'JSON':
                            if (result.responseJSON) {
                                for (var i = 0; i < result.responseJSON.items.length; i++) {
                                    this.data.push(result.responseJSON.items[i]);
                                }
                            } else {
                                this.core.setError('Newsticker feed kon niet gelezen worden (Formaat: ' + this.settings.feedType.toUpperCase() + ').');
                            }

                            break;
                        default:
                            this.core.setError('Newsticker feed kon niet gelezen worden omdat het formaat niet ondersteund word (Formaat: ' + this.settings.feedType.toUpperCase() + ').');

                            break;
                    }

                    if (0 == this.dataRefresh) {
                        this.setData();
                        this.setData();

                        this.start();
                    }
                } else {
                    this.core.setError('Newsticker feed kon niet geladen worden (HTTP status: ' + result.status + ').');
                }

                this.dataRefresh++;
            }, this)
        });
    };

    /**
     * Sets the data.
     * @protected.
     */
    Newsticker.prototype.setData = function() {
        var $element = this.core.getTemplate('ticker', this.$templates);

        if ($element) {
            for (var i = 0; i < this.data.length; i++) {
                var $template = this.core.getTemplate('item', this.$templates);

                if ($template) {
                    this.core.setPlaceholders($template, this.data[i]).appendTo($element);
                }
            }

            this.core.getPlaceholder('ticker', this.$element).append($element);

            this.$elements.push($element);
        }
    }

    /**
     * Starts the animation.
     * @protected.
     */
    Newsticker.prototype.start = function() {
        if ($element = this.$elements.shift()) {
            $element.animate({'margin-left': '-' + $element.outerWidth(true) + 'px'}, {
                'easing' 	: 'linear',
                'duration' 	: 40000 * ($element.outerWidth(true) / 2100),
                'complete' 	: $.proxy(function(event) {
                    $element.remove();

                    this.setData();

                    this.start();
                }, this)
            });
        }
    }

    /**
     * Registers an event or state.
     * @public.
     * @param {Object} object - The event or state to register.
     */
    Newsticker.prototype.register = function(object) {
        if (object.type === Newsticker.Type.Event) {
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
    Newsticker.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @protected.
     * @param {Array.<String>} events - The events to release.
     */
    Newsticker.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the Newsticker.
     * @public.
     */
    $.fn.Newsticker = function(option, core) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('narrowcasting.newsticker');

            if (!data) {
                data = new Newsticker(this, typeof option == 'object' && option, core);

                $this.data('narrowcasting.newsticker', data);

                $.each([

                ], function(i, event) {
                    data.register({ type: Newsticker.Type.Event, name: event });
                    data.$element.on(event + '.narrowcasting.newsticker.core', $.proxy(function(e) {
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
    $.fn.Newsticker.Constructor = Newsticker;

})(window.Zepto || window.jQuery, window, document);

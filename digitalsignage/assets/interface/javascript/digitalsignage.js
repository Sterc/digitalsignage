/* Javascript for DigitalSignage. (c) Sterc.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- jQuery ready ---------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

$(document).ready(function() {
    $('.window').DigitalSignage({
                                    'debug'     : settings.debug,

                                    'callback'  : settings.callback,

                                    'feed'      : settings.broadcast.feed,

                                    'vars'      : {
                                        'player'    : settings.player,
                                        'broadcast' : settings.broadcast.id,
                                        'preview'   : settings.preview
                                    },

                                    'domain'    : document.location.origin
                                });
});

/* ----------------------------------------------------------------------------------------- */
/* ----- DigitalSignage --------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    function DigitalSignage(element, options) {
        /**
         * Current settings for the DigitalSignage.
         * @public.
         */
        this.settings = $.extend({}, DigitalSignage.Defaults, options);

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
         * All templates of the DigitalSignage.
         * @protected.
         */
        this.$templates = this.getTemplates(this.getPlaceholder('slides', this.$element), true);

        /**
         * All error templates of the DigitalSignage.
         * @protected.
         */
        this.$errorTemplates = this.getTemplates(this.getPlaceholder('errors', this.$element), true);

        /**
         * All special templates of the DigitalSignage.
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
         * The timer.
         * @protected.
         */
        this.timer = null;

        /**
         * The state of the dev tools.
         */
        this.devTools = true;

        /**
         * The current slides of DigitalSignage.
         * @protected.
         */
        this.$slides = [];

        /**
         * The current slides of DigitalSignage.
         * @protected.
         */
        this.$currenSlides = [];

        /**
         * The current plugins of DigitalSignage.
         */
        this.plugins = [];

        this.initialize();
    }

    /**
     * Default options for the DigitalSignage.
     * @public.
     */
    DigitalSignage.Defaults = {
        'debug'             : false,

        'timer'             : true,
        'timerType'         : 'vertical',
        'timerClass'        : 'timer',

        'animation'         : 'fade',
        'animationTime'     : 1,

        'callback'          : null,
        'callbackType'      : 'JSON',
        'callbackInterval'  : 300,

        'feed'              : null,
        'feedType'          : 'JSON',
        'feedInterval'      : 300,

        'vars'              : {
            'player'            : null,
            'broadcast'         : null,
            'preview'           : false
        },

        'domain'            : '',

        'keys'              : ['fullscreen', 'header', 'ticker'],

        'lexicons'          : {
            'prevSlide'         : 'Vorige slide',
            'nextSlide'         : 'Volgende slide',
            'days'              : ['Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'],
            'months'            : ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december'],
            'dayTypes'          : ['Vandaag', 'Morgen', 'Overmorgen']
        }
    };

    /**
     * Enumeration for types.
     * @public.
     * @readonly.
     * @enum {String}.
     */
    DigitalSignage.Type = {
        'Event': 'event'
    };

    /**
     * Initializes the DigitalSignage.
     */
    DigitalSignage.prototype.initialize = function() {
        this.setLog('[Core] initialize');

        if (this.getRequirements()) {
            this.loadCallback();

            if (0 < this.settings.callbackInterval) {
                setInterval($.proxy(function(event) {
                    this.loadCallback();
                }, this), this.settings.callbackInterval * 1000);
            }

            this.loadData();

            if (0 < this.settings.feedInterval) {
                setInterval($.proxy(function(event) {
                    this.loadData();
                }, this), this.settings.feedInterval * 1000);
            }

            this.setDevTools();
            this.loadCustomPlugins();
        }
    };

    /**
     * Checks the the DigitalSignage.
     */
    DigitalSignage.prototype.getRequirements = function() {
        this.setLog('[Core] getRequirements');

        if (0 == (preview = parseInt(this.settings.vars.preview))) {
            if (-1 !== ['', null, undefined].indexOf(this.settings.vars.player)) {
                return this.setError('[Core] player is not defined.', true);
            }

            if (-1 !== ['', null, undefined].indexOf(this.settings.vars.broadcast)) {
                return this.setError('[Core] broadcast is not defined.', true);
            }

            this.settings.vars.preview = false;
        } else {
            this.settings.vars.preview = true;
        }

        if (null === this.settings.callback) {
            return this.setError('[Core] callback is not defined.', true);
        }

        if (null === this.settings.feed) {
            return this.setError('[Core] feed is not defined.', true);
        }

        return true;
    };

    /**
     * Loads the callback data for the DigitalSignage.
     */
    DigitalSignage.prototype.loadCallback = function() {
        this.setLog('[Core] loadCallback');

        $.ajax({
                   'url'       : this.getAjaxUrl(this.settings.callback, this.settings.callbackInterval, ['type=broadcast', 'data=true']),
                   'dataType'  : this.settings.callbackType.toUpperCase(),
                   'complete'  : $.proxy(function(result) {
                       if (200 == result.status) {
                           switch (this.settings.callbackType.toUpperCase()) {
                               case 'JSON':
                                   if (result.responseJSON) {
                                       if (result.responseJSON.redirect) {
                                           var currentLocation = window.location.href.replace(this.settings.domain, '');
                                           var redirectLocation = result.responseJSON.redirect.replace(this.settings.domain, '');

                                           if (currentLocation != redirectLocation) {
                                               window.location.href = redirectLocation;
                                           }
                                       }

                                       if (result.responseJSON.player) {
                                           if (result.responseJSON.player.restart) {
                                               window.location.reload(false);
                                           }
                                       }
                                   } else {
                                       this.setError('[Core] callback could not be read (Format: ' + this.settings.callbackType.toUpperCase() + ').');
                                   }

                                   break;
                               default:
                                   this.setError('[Core] callback could not be read because the format is not supported (Format: ' + this.settings.callbackType.toUpperCase() + ').');

                                   break;
                           }
                       } else {
                           this.setError('[Core] callback could not be loaded (HTTP status: ' + result.status + ').');
                       }
                   }, this)
               });
    };

    /**
     * Loads the data for the DigitalSignage.
     */
    DigitalSignage.prototype.loadData = function() {
        this.setLog('[Core] loadData');

        $.ajax({
                   'url'       : this.getAjaxUrl(this.settings.feed, this.settings.feedInterval, ['type=broadcast', 'data=true']),
                   'dataType'  : this.settings.feedType.toUpperCase(),
                   'complete'  : $.proxy(function(result) {
                       if (200 == result.status) {
                           switch (this.settings.feedType.toUpperCase()) {
                               case 'JSON':
                                   if (result.responseJSON) {
                                       if (0 < result.responseJSON.slides.length) {
                                           var data = [];

                                           for (var i = 0; i < result.responseJSON.slides.length; i++) {
                                               data.push(result.responseJSON.slides[i]);
                                           }

                                           this.setData('slides', data, null);
                                       } else {
                                           this.loadData();
                                       }

                                       this.setLog('[Core] loadData: (slides: ' + result.responseJSON.slides.length + ')');
                                   } else {
                                       this.setError('[Core] feed could not be read (Format: ' + this.settings.feedType.toUpperCase() + ').');
                                   }

                                   break;
                               default:
                                   this.setError('[Core] feed could not be read because the format is not supported (Format: ' + this.settings.feedType.toUpperCase() + ').');

                                   break;
                           }

                           if (0 == this.$slides.length) {
                               this.nextSlide();
                           }
                       } else {
                           this.setError('[Core] could not be loaded (HTTP status: ' + result.status + ').');
                       }
                   }, this)
               });
    };

    /**
     * Gets the debug state for the DigitalSignage.
     */
    DigitalSignage.prototype.isDebug = function() {
        return this.settings.debug;
    };

    /**
     * Sets the dev tools for the DigitalSignage.
     */
    DigitalSignage.prototype.setDevTools = function() {
        if (this.settings.vars.preview) {
            $(window).on('keyup', $.proxy(function(event) {
                if (this.isDevTools()) {
                    if (37 == event.keyCode) {
                        this.nextSlide('prev');
                    } else if (39 == event.keyCode) {
                        this.nextSlide('next');
                    }
                }
            }, this));

            $('body').addClass('window-preview').append(
                $('<div>').addClass('dev-tools').append(
                    $('<a>').attr({'href': '#', 'class': 'dev-tools-btn dev-tools-prev', 'title': this.getLexicon('prevSlide')}).html(this.getLexicon('prevSlide')).on('click', $.proxy(function(event) {
                        if (this.isDevTools()) {
                            this.nextSlide('prev');
                        }

                        return false;
                    }, this)),
                    $('<a>').attr({'href': '#', 'class': 'dev-tools-btn dev-tools-next', 'title': this.getLexicon('nextSlide')}).html(this.getLexicon('nextSlide')).on('click', $.proxy(function(event) {
                        if (this.isDevTools()) {
                            this.nextSlide('next');
                        }

                        return false;
                    }, this))
                )
            );
        }
    };

    /**
     * Gets the dev tools state for the DigitalSignage.
     */
    DigitalSignage.prototype.isDevTools = function() {
        return this.settings.devTools;
    };

    /**
     * Sets the dev tools state the DigitalSignage.
     * @param {Boolean} enable - The state of the dev tools.
     */
    DigitalSignage.prototype.enableDevTools = function(enable) {
        return this.settings.devTools = enable;
    };

    /**
     * Returns a URL with the required parameters.
     * @param {String} url - The current URL.
     * @param {Integer} interval = The current interval time.
     * @param {Array} parameters - The current URL parameters.
     */
    DigitalSignage.prototype.getAjaxUrl = function(url, interval, parameters) {
        $.each(this.settings.vars, $.proxy(function(index, value) {
            switch (index) {
                case 'player':
                    parameters.push('pl=' + value);

                    break;
                case 'broadcast':
                    parameters.push('bc=' + value);

                    break;
                case 'preview':
                    if (this.settings.vars.preview) {
                        parameters.push('preview=true');
                    }

                    break;
            }
        }).bind(this));

        parameters.push('time=' + interval);
        parameters.push('hash=' + (new Date()).getTime());

        if (0 < parameters.length) {
            if (-1 == url.search(/\?/i)) {
                return url + '?' + parameters.join('&');
            } else {
                return url + '&' + parameters.join('&');
            }
        }

        return url;
    };

    /**
     * Gets all custom plugins for the DigitalSignage.
     */
    DigitalSignage.prototype.loadCustomPlugins = function() {
        this.setLog('[Core] loadCustomPlugins');

        $('[data-plugin]', this.$element).each($.proxy(function(index, element) {
            var $element    = $(element),
                plugin      = $element.attr('data-plugin');

            if ($.fn[plugin]) {
                this.setLog('[Core] loadCustomPlugins: (plugin: ' + plugin + ')');

                this.plugins[plugin.toLowerCase()] = $element[plugin](this);
            }
        }, this));
    };

    /**
     * Gets all custom plugin settings for the DigitalSignage.
     * @param {HTMLElement} $element - The element of the plugin.
     */
    DigitalSignage.prototype.loadCustomPluginSettings = function($element) {
        if (undefined !== (settings = $element.attr('data-plugin-settings'))) {
            if (data = JSON.parse(settings.replace(/'/g, "\""))) {
                return $.extend({}, data, {
                    'vars' : this.settings.vars
                });
            }
        }

        return $.extend({}, {
            'vars' : this.settings.vars
        });
    };

    /**
     * Logs a message.
     * @param {String} message - The message to to log.
     * @param {Boolean} error - If the message is an error.
     * @param {Boolean} fatal  - If the message is fatal or not.
     */
    DigitalSignage.prototype.setLog = function(message, error, fatal) {
        if (this.isDebug() || error || fatal) {
            if (error || fatal) {
                console.warn(message);
            } else {
                console.log(message);
            }
        }

        return true;
    };

    /**
     * Display an error.
     * @param {String} message - The error to display.
     * @param {Boolean} fatal - If the error is fatal or not.
     */
    DigitalSignage.prototype.setError = function(message, fatal) {
        this.setLog(message, true, fatal);

        if (this.isDebug() || fatal) {
            if ($error = this.getTemplate('error', this.$errorTemplates)) {
                if (typeof message === 'string') {
                    this.setPlaceholders($error, {
                        'title'     : fatal ? 'Error' : 'Warning',
                        'error'     : fatal ? 'error' : 'warning',
                        'message'   : message
                    }).appendTo(this.getPlaceholder('errors', this.$element));
                } else {
                    this.setPlaceholders($error, message).appendTo(this.getPlaceholder('errors', this.$element));
                }

                this.$errors.push($error);

                if (!fatal) {
                    setTimeout($.proxy(function(event) {
                        if ($error = this.$errors.shift()) {
                            $error.remove();
                        }
                    }, this), 5000);
                }
            }
        }

        return false;
    };

    /**
     * Sets the data of a slide.
     * @param {String} key - The key of the slide.
     * @param {Array} data - The data of the slide.
     * @param {Integer} current - The current count.
     */
    DigitalSignage.prototype.setData = function(key, data, current) {
        if (null === data) {
            data = this.getData(key, 'data');
        }

        if (null === current) {
            current = this.getData(key, 'current');
        }

        this.data[key] = {
            current : current,
            output  : data
        };
    };

    /**
     * Gets the data of a slide.
     * @param {String} key - The key of the slide.
     * @param {String} index - The index of the data type.
     */
    DigitalSignage.prototype.getData = function(key, index) {
        if ('current' == index) {
            if (this.data[key]) {
                if (this.data[key]['current']) {
                    return this.data[key]['current'];
                }
            }

            return -1;
        } else if ('data' == index) {
            if (this.data[key]) {
                if (this.data[key]['output']) {
                    return this.data[key]['output'];
                }
            }

            return [];
        } else if ('length' == index) {
            if (this.data[key]) {
                if (this.data[key]['output']) {
                    return this.data[key]['output'].length;
                }
            }

            return 0;
        } else {
            if (this.data[key]) {
                if (this.data[key]['output'][index]) {
                    return this.data[key]['output'][index];
                }
            }

            return null;
        }
    };

    /**
     * Gets the current data count of a slide.
     * @param {String} key - The key of the slide.
     * @param {String} type - The type to calculate the next count.
     * @param {Integer} max - Te maximum length of the data count.
     */
    DigitalSignage.prototype.getCurrentDataIndex = function(key, type, max) {
        if (this.data[key]) {
            var current = this.data[key]['current'],
                length  = max;

            if (null == length || length > this.data[key]['output'].length) {
                length = this.data[key]['output'].length;
            }

            if ('prev' == type) {
                if ((current - 1) >= 0) {
                    current = current - 1;
                } else {
                    current = length - 1;
                }
            } else {
                if ((current + 1) <= (length - 1)) {
                    current = current + 1;
                } else {
                    current = 0;
                }
            }

            return this.data[key]['current'] = current;
        }

        return null;
    };

    /**
     * Sets all the templates of a DigitalSignage object.
     * @param {HTMLElement} $element - The HTML object.
     * @param {Boolean} level - Get templates from level.
     */
    DigitalSignage.prototype.getTemplates = function($element, level) {
        var templates   = [],
            $templates  = [];

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
     * Gets a templates of a DigitalSignage object.
     * @param {String} template - The name of the template.
     * @param {Array} $templates - The available templates.
     */
    DigitalSignage.prototype.getTemplate = function(template, $templates) {
        if ($templates[template]) {
            return $templates[template].clone();
        }

        return null;
    };

    /**
     * Gets the translated text.
     * @param {String} key - The key of the lexicon.
     * @param {String|Object} category - The type lexicon.
     * @param {String} lexicon - The default return.
     */
    DigitalSignage.prototype.getLexicon = function(key, category, lexicon) {
        var lexicons = [];

        if (typeof category == 'object') {
            if (category[key]) {
                return category[key];
            }
        } else {
            if (this.settings.lexicons[category]) {
                if (this.settings.lexicons[category][key]) {
                    return this.settings.lexicons[category][key];
                }
            } else if (this.settings.lexicons[key]) {
                return this.settings.lexicons[key];
            }
        }

        if (undefined !== lexicon) {
            return lexicon;
        }

        return 'undefined';
    };

    /**
     * Gets a placeholder of a DigitalSignage object.
     * @param {String} placeholder - The name of the placeholder.
     * @param {HTMLElement} $element - The HTML object.
     */
    DigitalSignage.prototype.getPlaceholder = function(placeholder, $element) {
        if (undefined !== ($placeholder = $('[data-placeholder="' + placeholder + '"]', $element))) {
            return $placeholder.show();
        }

        return null;
    };

    /**
     * Sets the placeholder of a DigitalSignage object.
     * @param {HTMLElement} $template - The HTML object.
     * @param {Object} data - The data of the placeholders.
     */
    DigitalSignage.prototype.setPlaceholders = function($template, data) {
        var placeholders = $('[data-placeholder]', $template);

        for (var i = 0; i < placeholders.length; i++) {
            if ($placeholder = $(placeholders[i])) {
                var type    = $placeholder.prop('tagName'),
                    name    = $placeholder.attr('data-placeholder'),
                    wrapper = $placeholder.attr('data-placeholder-wrapper'),
                    renders = $placeholder.attr('data-placeholder-renders'),
                    value   = this.getPlaceholderValue(name, data, renders),
                    isEmpty = null === value || undefined === value || '' === value;

                switch (type) {
                    case 'IMG':
                    case 'IFRAME':
                        if (isEmpty) {
                            $placeholder.attr('src', '').hide();
                        } else {
                            $placeholder.attr('src', value).show();
                        }

                        break;
                    default:
                        if (isEmpty) {
                            $placeholder.html('').hide();
                        } else {
                            $placeholder.html(value).show();
                        }

                        break;
                }

                if (wrapper) {
                    if ($placeholder.parents('.' + wrapper)) {
                        if (isEmpty) {
                            $placeholder.parents('.' + wrapper).addClass('is-empty');
                        } else {
                            $placeholder.parents('.' + wrapper).removeClass('is-empty');
                        }
                    }
                }
            }
        }

        var placeholders = $('[data-placeholder-class]', $template);

        if (undefined != $template.attr('data-placeholder-class')) {
            placeholders.push($template);
        }

        for (var i = 0; i < placeholders.length; i++) {
            if ($placeholder = $(placeholders[i])) {
                var type    = $placeholder.prop('tagName'),
                    name    = $placeholder.attr('data-placeholder-class'),
                    value   = this.getPlaceholderValue(name, data);

                $placeholder.addClass(value);
            }
        }

        return $template;
    };

    /**
     * Gets the placeholder value.
     * @param {String|Object} name - The name of the placeholder.
     * @param {Object} data - The data of the placeholder.
     * @param {String|Array} renders - The renders of the placeholder.
     */
    DigitalSignage.prototype.getPlaceholderValue = function(name, data, renders) {
        if (typeof name == 'string') {
            name = name.split('.');
        }

        if (value = data[name.shift()]) {
            if (typeof value == 'object') {
                value = this.getPlaceholderValue(name, value);
            }
        }

        return this.getPlaceholderValueRenders(value, renders);
    };

    /**
     * Gets the placeholder value.
     * @public.
     * @param {Value} string|null - The value of the placeholder.
     * @param {Renders} string|array - The renders of the placeholder.
     */
    DigitalSignage.prototype.getPlaceholderValueRenders = function(value, renders) {
        if (renders) {
            if (typeof renders == 'string') {
                renders = renders.split(',');
            }

            if (typeof value == 'string' && '' != value) {
                for (var i = 0; i < renders.length; i++) {
                    var param   = null,
                        render  = renders[i];

                    if (-1 !== (pos = render.search(':'))) {
                        param = render.substr(pos + 1);
                        render = render.substr(0, pos);
                    }

                    switch (render) {
                        case 'striptags':
                            var regex = param ? new RegExp('<(?!\/?(' + param + ')+)[^>]+>', 'gi') : new RegExp('<\/?[^>]+>', 'gi');

                            value = value.replace(regex, '');

                            break;
                        case 'ellipsis':
                            var ellipsis = param ? parseInt(param) : 100;

                            if (value.length > ellipsis) {
                                var firstPart 	= value.substring(0, ellipsis);
                                var secondPart	= value.substring(ellipsis);

                                if (-1 === (secondSpace = secondPart.indexOf(' '))) {
                                    secondSpace = secondPart.lenght - 1;
                                }

                                value = firstPart + (secondPart.substr(0, secondSpace)) + '...';
                            }

                            break;
                        case 'youtube':
                            var videoID = value.match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/);

                            if (videoID[1]) {
                                value = '//www.youtube.com/embed/' + videoID[1] + (param ? param : '?autoplay=1&controls=0&rel=0&showinfo=0');
                            }

                            break;
                        case 'date':
                            var date    = new Date(value),
                                hours   = date.getHours(),
                                minutes = date.getMinutes(),
                                seconds = date.getSeconds(),
                                dates   = date.getDate(),
                                day     = date.getDay(),
                                month   = date.getMonth(),
                                year    = date.getFullYear(),
                                now     = new Date(),
                                today   = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 0, 0, 0, 0),
                                days    = Math.round(Math.abs(today.getTime() - date.getTime()) / (1000 * 3600 * 24)),
                                format  = param ? param : '%l %d %F';

                            var formats = {
                                '%h'    : hours,
                                '%H'    : hours < 10 ? ('0' + hours) : hours,
                                '%i'    : minutes,
                                '%I'    : minutes < 10 ? ('0' + minutes) : minutes,
                                '%s'    : seconds,
                                '%S'    : seconds < 10 ? ('0' + seconds) : seconds,
                                '%j'    : dates,
                                '%d'    : dates < 10 ? ('0' + dates) : dates,
                                '%D'    : this.getLexicon(day, 'days').substr(0, 3),
                                '%l'    : this.getLexicon(day, 'days'),
                                '%W'    : day,
                                '%M'    : this.getLexicon(month, 'months').substr(0, 3),
                                '%F'    : this.getLexicon(month, 'months'),
                                '%m'    : month < 10 ? ('0' + month) : month,
                                '%n'    : month + 1,
                                '%y'    : year.toString().substr(2, 2),
                                '%Y'    : year.toString(),
                                '%q'    : this.getLexicon(days, 'dayTypes', this.getLexicon(day, 'days').toString())
                            };

                            if (dateFormats = format.match(/%[a-z]/gi)) {
                                for (var i = 0; i < dateFormats.length; i++) {
                                    format = format.replace(dateFormats[i], formats[dateFormats[i]]);
                                }
                            }

                            value = format;

                            break;
                    }
                }

                if (undefined !== value) {
                    value = value.replace(/<\s*(?!img)(\w+).*?>/gi, '<\$1>');
                    value = value.replace(/<\/?(span|a)[^>]*>/gi, '');
                }
            }
        }

        return value;
    };

    /**
     * Sets the timer object.
     * @param {String} type - The type timer to return.
     */
    DigitalSignage.prototype.getTimer = function(type) {
        if (null === this.timer) {
            this.timer = {
                $timer      : $('<div class="' + this.settings.timerClass + ' ' + this.settings.timerClass + '-' + this.settings.timerType + '">').css({'opacity' : this.settings.timer ? 1 : 0}),
                $progress   : $('<span class="' + this.settings.timerClass + '-inner">')
            };

            this.timer.$timer.append(this.timer.$progress).appendTo(this.$element);
        }

        return this.timer['$' + type];
    };

    /**
     * Sets the time of a slide by the timer object.
     * @param {Integer} time - The time of the slide.
     */
    DigitalSignage.prototype.setTimer = function(time) {
        this.setLog('[Core] setTimer: (time: ' + time + ')');

        if ($timer = this.getTimer('progress')) {
            if ('vertical' == this.settings.timerType) {
                var startPosition   = {'height': '0px'};
                var endPosition     = {'height': '100%'};
            } else {
                var startPosition   = {'width': '0px'};
                var endPosition     = {'width': '100%'};
            }

            $timer.css(startPosition).stop().animate(endPosition, {
                'easing'    : 'linear',
                'duration'  : time * 1000,
                'complete'  : $.proxy(function(event) {
                    this.nextSlide();
                }, this)
            });
        }
    };

    /**
     * Gets a slide template and initializes the slide.
     * @param {Object} data - The slide data.
     */
    DigitalSignage.prototype.getSlide = function(data) {
        this.setLog('[Core] getSlide: (title: ' + data.title + ')');

        if (null === ($slide = this.getTemplate(data.slide.replace('_', '-'), this.$templates))) {
            $slide = this.getTemplate('default', this.$templates);
        }

        if (null !== $slide) {
            $slide.prependTo($(this.getPlaceholder('slides', this.$element)));

            if (plugin = this.getSlidePlugin($slide, data.slide)) {
                data = $.extend({}, data, this.loadCustomPluginSettings($slide));

                if ($.fn[plugin]) {
                    $slide[plugin](data, this);
                } else {
                    var plugin = this.getSlidePlugin($slide, 'default');

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

        return null;
    };

    /**
     * Sets the next slide and animate current en next slide.
     * @param {String} type - The type to calculate the current slide.
     */
    DigitalSignage.prototype.nextSlide = function(type) {
        var next = this.getCurrentDataIndex('slides', type, null);

        this.setLog('[Core] nextSlide: (next: ' + next + ')');

        if (null !== (data = this.getData('slides', next))) {
            var data = $.extend({}, {
                'slide'         : 'default',
                'fullscreen'    : false
            }, data);

            if ($slide = this.getSlide(data)) {
                this.enableDevTools(false);

                var zIndex = null;

                for (var i = 0; i < this.settings.keys.length; i++) {
                    var key = this.settings.keys[i];

                    if (data[key]) {
                        if (1 == data[key] ||'true' == data[key]) {
                            $slide.addClass('slide-' + key);

                            this.$element.addClass('window-' + key);

                            if ('fullscreen' === key) {
                                zIndex = 9999;
                            }
                        } else {
                            this.$element.removeClass('window-' + key);
                        }
                    } else {
                        this.$element.removeClass('window-' + key);
                    }
                }

                if (undefined !== (fullScreen = $slide.attr('data-slide-fullscreen'))) {
                    if (1 == fullScreen ||'true' == fullScreen) {
                        zIndex = 9999;
                    }
                }

                if (null !== zIndex || undefined !== (zIndex = $slide.attr('data-slide-index'))) {
                    $slide.css({'z-index': zIndex, 'position': 'absolute', 'top': 0, 'bottom': 0, 'left': 0, 'right': 0});
                }

                $slide.hide().fadeIn(this.settings.animationTime * 1000);

                if ($current = this.$slides.shift()) {
                    this.$currenSlides.push($current);

                    $current.show().fadeOut(this.settings.animationTime * 1000, $.proxy(function(event) {
                        if (!data.fullscreen) {
                            this.$element.removeClass('window-fullscreen');
                        }

                        if ($current = this.$currenSlides.shift()) {
                            $current.remove();
                        }

                        this.enableDevTools(true);
                    }, this));
                } else {
                    this.enableDevTools(true);
                }

                this.$slides.push($slide);
            } else {
                this.skipSlide('No slide available.');
            }
        } else {
            this.skipSlide('No slide data available.');
        }
    };

    /**
     * Skips the current slide and animate next slide.
     * @public.
     * @param {String} message - The message of skip.
     */
    DigitalSignage.prototype.skipSlide = function(message) {
        this.setLog('[Core] skipSlide: (message: ' + message + ')');

        this.nextSlide();
    };

    /**
     * Gets the plugin name of the slide.
     * @public.
     * @param {$slide} HTMLElement - The slide.
     * @param {Type} string - The type of the slide.
     */
    DigitalSignage.prototype.getSlidePlugin = function($slide, type) {
        if (undefined !== (plugin = $slide.attr('data-plugin'))) {
            return plugin;
        }

        var plugin = ('slide-' + type).replace('_', '-').split('-');

        for (var i = 0 ; i < plugin.length ; i++) {
            plugin[i] = plugin[i].charAt(0).toUpperCase() + plugin[i].substr(1);
        }

        return plugin.join('');
    };

    /**
     * Converts XML data to a readable Array.
     * @public.
     * @param {Input} string - The XML data.
     * @param {Element} string - The elements.
     */
    DigitalSignage.prototype.parseXML = function(input, element) {
        var data = new Array(),
            $xml = $($.parseXML(input));

        if (0 < $(element, $xml).length) {
            $(element, $xml).each($.proxy(function(index, value) {
                var item = {};

                $(value).children().each(function(index, value) {
                    var type = $(this).prop('tagName');

                    if ('enclosure' == type) {
                        item['image'] = $(this).attr('url');
                    } else {
                        item[type] = $(this).text();
                    }
                });

                data.push(item);
            }, this));
        }

        return data;
    };

    /**
     * Registers an event or state.
     * @public.
     * @param {Object} object - The event or state to register.
     */
    DigitalSignage.prototype.register = function(object) {
        if (object.type === DigitalSignage.Type.Event) {
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
    DigitalSignage.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @protected.
     * @param {Array.<String>} events - The events to release.
     */
    DigitalSignage.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the DigitalSignage.
     * @public.
     */
    $.fn.DigitalSignage = function(option) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('digitalsignage');

            if (!data) {
                data = new DigitalSignage(this, typeof option == 'object' && option);

                $this.data('digitalsignage', data);

                $.each([], function(i, event) {
                    data.register({ type: DigitalSignage.Type.Event, name: event });

                    data.$element.on(event + '.digitalsignage.core', $.proxy(function(e) {
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
    $.fn.DigitalSignage.Constructor = DigitalSignage;

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
     * @param {Core} Object - The DigitalSignage object for the Default Slide.
     */
    function SlideDefault(element, options, core) {
        /**
         * The DigitalSignage object for the Default Slide.
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
        this.core.setLog('[SlideDefault] initialize');

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
                data = $this.data('digitalsignage.slidedefault');

            if (!data) {
                data = new SlideDefault(this, typeof option == 'object' && option, core);

                $this.data('digitalsignage.slidedefault', data);

                $.each([], function(i, event) {
                    data.register({ type: SlideDefault.Type.Event, name: event });

                    data.$element.on(event + '.digitalsignage.slidedefault.core', $.proxy(function(e) {
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
/* ----- Clock Plugin ---------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Clock Plugin.
     * @class Clock Plugin.
     * @public.
     * @param {Element} HTMLElement|jQuery - The element of the Clock Plugin.
     * @param {Options} array - The options of the Clock Plugin.
     * @param {Core} Object - The DigitalSignage object for the Clock Plugin.
     */
    function ClockPlugin(element, options, core) {
        /**
         * The DigitalSignage object for the Clock Plugin.
         * @public.
         */
        this.core = core;

        /**
         * Plugin element.
         * @public.
         */
        this.$element = $(element);

        /**
         * Current settings for the Clock Plugin.
         * @public.
         */
        this.settings = $.extend({}, ClockPlugin.Defaults, options, this.core.loadCustomPluginSettings(this.$element));

        this.initialize();
    }

    /**
     * Default options for the Clock Plugin.
     * @public.
     */
    ClockPlugin.Defaults = {

    };

    /**
     * Enumeration for types.
     * @public.
     * @readonly.
     * @enum {String}.
     */
    ClockPlugin.Type = {
        'Event': 'event'
    };

    /**
     * Initializes the Clock Plugin.
     * @protected.
     */
    ClockPlugin.prototype.initialize = function() {
        this.core.setLog('[ClockPlugin] initialize');

        setInterval($.proxy(function() {
            var date = new Date();

            this.core.setPlaceholders(this.$element, {
                'time' : date.toString(),
                'date' : date.toString()
            });
        }, this), 1000);
    };

    /**
     * The jQuery Plugin for the ClockPlugin.
     * @public.
     */
    $.fn.ClockPlugin = function(core, option) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('digitalsignage.clockplugin');

            if (!data) {
                data = new ClockPlugin(this, typeof option == 'object' && option, core);

                $this.data('digitalsignage.clockplugin', data);
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
    $.fn.ClockPlugin.Constructor = ClockPlugin;

})(window.Zepto || window.jQuery, window, document);

/* ----------------------------------------------------------------------------------------- */
/* ----- Ticker Plugin --------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Ticker Plugin.
     * @class Ticker Plugin.
     * @public.
     * @param {HTMLElement} element - The element of the Ticker Plugin.
     * @param {Array} options - The options of the Ticker Plugin.
     * @param {Object} core - The DigitalSignage object for the Ticker Plugin.
     */
    function TickerPlugin(element, options, core) {
        /**
         * The DigitalSignage object for the Ticker Plugin.
         * @public.
         */
        this.core = core;

        /**
         * Plugin element.
         * @public.
         */
        this.$element = $(element);

        /**
         * Current settings for the Ticker Plugin.
         * @public.
         */
        this.settings = $.extend({}, TickerPlugin.Defaults, options, this.core.loadCustomPluginSettings(this.$element));

        /**
         * Currently suppressed events to prevent them from beeing retriggered.
         * @protected.
         */
        this._supress = {};

        /**
         * All templates of the Ticker Plugin.
         * @protected.
         */
        this.$templates = this.core.getTemplates(this.$element);

        /**
         * All elements of the Ticker Plugin.
         * @protected.
         */
        this.$items = [];

        this.initialize();
    }

    /**
     * Default options for the Ticker Plugin.
     * @public.
     */
    TickerPlugin.Defaults = {
        'feed'          : null,
        'feedType'      : 'JSON',
        'feedInterval'  : 900,

        'vars'          : {
            'player'        : null,
            'broadcast'     : null,
            'preview'       : false
        }
    };

    /**
     * Enumeration for types.
     * @public.
     * @readonly.
     * @enum {String}.
     */
    TickerPlugin.Type = {
        'Event': 'event'
    };

    /**
     * Initializes the Ticker Plugin.
     * @protected.
     */
    TickerPlugin.prototype.initialize = function() {
        this.core.setLog('[TickerPlugin] initialize');

        if (null === this.settings.feed) {
            this.core.setError('[TickerPlugin] feed is not defined.');
        } else {
            this.loadData();

            if (0 < this.settings.feedInterval) {
                setInterval($.proxy(function(event) {
                    this.loadData();
                }, this), this.settings.feedInterval * 1000);
            }
        }
    };

    /**
     * Loads the data for the Ticker Plugin.
     * @protected.
     */
    TickerPlugin.prototype.loadData = function() {
        this.core.setLog('[TickerPlugin] loadData');

        $.ajax({
                   'url'       : this.core.getAjaxUrl(this.settings.feed, this.settings.feedInterval, ['type=ticker', 'data=true']),
                   'dataType'  : this.settings.feedType.toUpperCase(),
                   'complete'  : $.proxy(function(result) {
                       if (200 == result.status) {
                           switch (this.settings.feedType.toUpperCase()) {
                               case 'JSON':
                                   if (result.responseJSON) {
                                       if (0 < result.responseJSON.items.length) {
                                           var data = [];

                                           for (var i = 0; i < result.responseJSON.items.length; i++) {
                                               data.push(result.responseJSON.items[i]);
                                           }

                                           this.core.setData('tickerplugin', data);
                                       } else {
                                           this.loadData();
                                       }

                                       this.core.setLog('[TickerPlugin] loadData: (slides: ' + result.responseJSON.items.length + ')');
                                   } else {
                                       this.core.setError('[TickerPlugin] feed could not be read (Format: ' + this.settings.feedType.toUpperCase() + ').');
                                   }

                                   break;
                               default:
                                   this.core.setError('[TickerPlugin] feed could not be read because the format is not supported (Format: ' + this.settings.feedType.toUpperCase() + ').');

                                   break;
                           }
                       } else {
                           this.core.setError('[TickerPlugin] feed could not be loaded (HTTP status: ' + result.status + ').');
                       }

                       if (0 == this.$items.length) {
                           if (0 < this.core.getData('tickerplugin', 'length')) {
                               this.addItem();
                               this.addItem();

                               this.nextItem();
                           }
                       }
                   }, this)
               });
    };

    /**
     * Returns all the URL parameters.
     * @public.
     */
    TickerPlugin.prototype.getUrlParameters = function() {
        var parameters = new Array('type=ticker', 'data=true');

        $.each(this.settings.vars, $.proxy(function(index, value) {
            switch (index) {
                case 'player':
                    parameters.push('pl=' + value);

                    break;
                case 'broadcast':
                    parameters.push('bc=' + value);

                    break;
                case 'preview':
                    if (this.settings.vars.preview) {
                        parameters.push('preview=true');
                    }

                    break;
            }
        }).bind(this));

        if (0 < parameters.length) {
            if (-1 == this.settings.feed.search(/\?/i)) {
                return '?' + parameters.join('&');
            } else {
                return '&' + parameters.join('&');
            }
        }

        return '';
    };

    /**
     * Sets the data.
     * @protected.
     */
    TickerPlugin.prototype.addItem = function() {
        if ($item = this.core.getTemplate('ticker', this.$templates)) {
            var data = this.core.getData('tickerplugin', 'data');

            //for (var i = 0; i < data.length; i++) {
            for (var i = 0; i < 3; i++) {
                if ($subItem = this.core.getTemplate('item', this.$templates)) {
                    this.core.setPlaceholders($subItem, data[i]).appendTo($item);
                }
            }

            this.core.getPlaceholder('ticker', this.$element).append($item);

            this.$items.push($item);
        }
    };

    /**
     * Starts the animation.
     * @protected.
     */
    TickerPlugin.prototype.nextItem = function() {
        if ($item = this.$items.shift()) {
            var proxy = this;

            $item.animate({'margin-left': '-' + $item.outerWidth(true) + 'px'}, {
                'easing'    : 'linear',
                'duration'  : 40000 * ($item.outerWidth(true) / 2100),
                'complete'  : function() {
                    $(this).remove();

                    proxy.addItem();
                    proxy.nextItem();
                }
            });
        }
    };

    /**
     * Registers an event or state.
     * @public.
     * @param {Object} object - The event or state to register.
     */
    TickerPlugin.prototype.register = function(object) {
        if (object.type === TickerPlugin.Type.Event) {
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
    TickerPlugin.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @protected.
     * @param {Array.<String>} events - The events to release.
     */
    TickerPlugin.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the Ticker Plugin.
     * @public.
     */
    $.fn.TickerPlugin = function(core, option) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('digitalsignage.tickerplugin');

            if (!data) {
                data = new TickerPlugin(this, typeof option == 'object' && option, core);

                $this.data('digitalsignage.tickerplugin', data);

                $.each([], function(i, event) {
                    data.register({ type: TickerPlugin.Type.Event, name: event });

                    data.$element.on(event + '.digitalsignage.tickerplugin.core', $.proxy(function(e) {
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
    $.fn.TickerPlugin.Constructor = TickerPlugin;

})(window.Zepto || window.jQuery, window, document);
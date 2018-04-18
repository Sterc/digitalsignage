/* Javascript for DigitalSignage. (c) Sterc.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- jQuery ready ---------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

$(document).ready(function() {
    $('.window').DigitalSignage({
        debug       : settings.debug,
        callback    : settings.callback,
        feed        : settings.broadcast.feed,
        vars        : {
            player      : settings.player,
            broadcast   : settings.broadcast.id,
            preview     : settings.preview
        },
        domain      : document.location.origin
    });
});

/* ----------------------------------------------------------------------------------------- */
/* ----- DigitalSignage --------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Digital Signage.
     * @class DigitalSignage.
     * @param {HTMLElement} element - The element of the Digital Signage.
     * @param {Array} options - The options of the Digital Signage.
     * @param {Object} core - The DigitalSignage object for the Digital Signage.
     */
    function DigitalSignage(element, options) {
        /**
         * Currently suppressed events to prevent them from beeing retriggered.
         */
        this._supress = {};

        /**
         * Plugin element of the Digital Signage.
         */
        this.$element = $(element);

        /**
         * Current settings of the Digital Signage.
         */
        this.settings = $.extend({}, DigitalSignage.Defaults, options);

        /**
         * All templates of the Digital Signage.
         */
        this.$templates = this.getTemplates(this.getPlaceholder('slides', this.$element), true);

        /**
         * All error templates of the Digital Signage.
         */
        this.$errorTemplates = this.getTemplates(this.getPlaceholder('errors', this.$element), true);

        /**
         * All special templates of the Digital Signage.
         */
        this.$specialTemplates = this.getTemplates(this.$element, true);

        /**
         * All errors of the Digital Signage.
         */
        this.$errors = [];

        /**
         * The state of the dev tools of the Digital Signage.
         */
        this.devTools = true;

        /**
         * The timer of the Digital Signage.
         */
        this.timer = null;

        /**
         * The current plugins of the Digital Signage.
         */
        this.plugins = [];

        /**
         * The data of the Digital Signage.
         */
        this.data = [];

        /**
         * The state of the data of the Digital Signage.
         */
        this.isLoaded = false;

        /**
         * The current slides of the Digital Signage.
         */
        this.$slides = [];

        this.initialize();
    }

    /**
    * Default options for the DigitalSignage.
    * @public.
    */
    DigitalSignage.Defaults = {
        debug               : false,
        showErrors          : true,

        timer               : true,
        timerType           : 'vertical',
        timerClass          : 'timer',

        animation           : 'fade',
        animationTime       : 1,

        syncInterval        : 120,

        feed                : null,
        feedType            : 'JSON',

        callback            : null,
        callbackType        : 'JSON',

        vars                : {
            player              : null,
            broadcast           : null,
            preview             : false
        },

        domain              : document.location.origin,

        keys                : ['fullscreen', 'header', 'ticker']
    };

    /**
     * Enumeration for types.
     * @enum {String}.
     */
    DigitalSignage.Type = {
        Event: 'event'
    };

    /**
     * Initializes the Digital Signage.
     */
    DigitalSignage.prototype.initialize = function() {
        this.setLog('[' + this.constructor.name + '] initialize');

        if (this.checkRequirements()) {
            this.loadFeed();
            this.loadCallback();

            this.loadDevTools();
            this.loadCustomPlugins();

            var syncIntervals = this.getIntervalTimes(parseInt(this.settings.syncInterval));

            setInterval($.proxy(function(event) {
                var currentTime = new Date();

                if (syncIntervals.indexOf(currentTime.getMinutes()) !== -1) {
                    this.loadFeed();
                    this.loadCallback();
                }
            }, this), 60 * 1000);
        }
    };

    /**
     * Checks requirements of the Digital Signage.
     */
    DigitalSignage.prototype.checkRequirements = function() {
        this.setLog('[' + this.constructor.name + '] checkRequirements');

        if (parseInt(this.settings.vars.preview) === 0) {
            this.settings.vars.preview = false;

            if (!this.settings.vars.player || this.settings.vars.player === '') {
                return this.setError('[' + this.constructor.name + '] checkRequirements: ' + this.getLexicon('error_player'), true);
            }

            if (!this.settings.vars.broadcast || this.settings.vars.broadcast === '') {
                return this.setError('[' + this.constructor.name + '] checkRequirements: ' + this.getLexicon('error_broadcast'), true);
            }
        } else {
            this.settings.vars.preview = true;
        }

        if (this.settings.feed === null) {
            return this.setError('[' + this.constructor.name + '] checkRequirements: ' + this.getLexicon('error_feed'), true);
        }

        if (this.settings.callback === null) {
            return this.setError('[' + this.constructor.name + '] checkRequirements: ' + this.getLexicon('error_callback'), true);
        }

        return true;
    };

    /**
     * Get the interval times for the feed and callback data calls.
     * @param {Integer} time - The interval time in minutes.
     */
    DigitalSignage.prototype.getIntervalTimes = function(time) {
        var intervals   = [],
            interval    = Math.ceil(time / 60);

        if (interval > 0) {
            for (var i = 0; i < (60 / interval); i++) {
                intervals.push(i * interval);
            }
        }

        return intervals;
    };

    /**
     * Loads the data of the Digital Signage.
     */
    DigitalSignage.prototype.loadFeed = function() {
        this.setLog('[' + this.constructor.name + '] loadFeed');

        $.ajax({
            url         : this.getAjaxUrl(this.settings.feed, ['type=broadcast', 'data=true']),
            dataType    : this.settings.feedType.toUpperCase(),
            complete    : $.proxy(function(result) {
                if (parseInt(result.status) === 200) {
                    switch (this.settings.feedType.toUpperCase()) {
                        case 'JSON':
                            if (result.responseJSON) {
                                if (result.responseJSON.slides.length > 0) {
                                    var data = [];

                                    for (var i = 0; i < result.responseJSON.slides.length; i++) {
                                        data.push(result.responseJSON.slides[i]);
                                    }

                                    this.setData('slides', data, null);

                                    this.setLog('[' + this.constructor.name + '] loadFeed: ' + this.getLexicon('feed_loaded', {
                                        items : result.responseJSON.slides.length
                                    }));
                                } else {
                                    this.setLog('[' + this.constructor.name + '] loadFeed: ' + this.getLexicon('error_feed_empty', {
                                        items : result.responseJSON.slides.length
                                    }));
                                }
                            } else {
                                this.setError('[' + this.constructor.name + '] loadFeed: ' + this.getLexicon('error_feed_format', {
                                    format : this.settings.feedType.toUpperCase()
                                }));
                            }

                            break;
                        default:
                            this.setError('[' + this.constructor.name + '] loadFeed: ' + this.getLexicon('error_feed_format', {
                                format : this.settings.feedType.toUpperCase()
                            }));

                            break;
                    }

                    if (this.getData('slides', 'length') > 0) {
                        if (!this.isLoaded) {
                            this.nextSlide();
                        }

                        this.isLoaded = true;
                    }
                } else {
                    this.setError('[' + this.constructor.name + '] loadFeed: ' + this.getLexicon('error_feed_http', {
                        status : result.status
                    }));
                }
            }, this)
        });
    };

    /**
     * Loads the callback data of the Digital Signage.
     */
    DigitalSignage.prototype.loadCallback = function() {
        this.setLog('[' + this.constructor.name + '] loadCallback');

        $.ajax({
            url         : this.getAjaxUrl(this.settings.callback, ['type=broadcast', 'data=true']),
            dataType    : this.settings.callbackType.toUpperCase(),
            complete    : $.proxy(function(result) {
                if (parseInt(result.status) === 200) {
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
                                this.setError('[' + this.constructor.name + '] loadCallback: ' + this.getLexicon('error_callback_format', {
                                    format : this.settings.callbackType.toUpperCase()
                                }));
                            }

                             break;
                        default:
                            this.setError('[' + this.constructor.name + '] loadCallback: ' + this.getLexicon('error_callback_format', {
                                format : this.settings.callbackType.toUpperCase()
                            }));

                            break;
                    }
                } else {
                    this.setError('[' + this.constructor.name + '] loadCallback: ' + this.getLexicon('error_callback_http', {
                        status : result.status
                    }));
                }
            }, this)
        });
    };

    /**
     * Gets the debug state of the Digital Signage.
     */
    DigitalSignage.prototype.isDebug = function() {
        return this.settings.debug;
    };

    /**
     * Gets the debug state of the Digital Signage.
     */
    DigitalSignage.prototype.showErrors = function() {
        return this.settings.showErrors;
    };

    /**
     * Gets the dev tools state of the Digital Signage.
     */
    DigitalSignage.prototype.isDevTools = function() {
        return this.settings.devTools;
    };

    /**
     * Sets the dev tools state of the Digital Signage.
     * @param {Boolean} devTools - The state of the dev tools.
     */
    DigitalSignage.prototype.enableDevTools = function(devTools) {
        return this.settings.devTools = devTools;
    };

    /**
     * Sets the dev tools of the Digital Signage.
     */
    DigitalSignage.prototype.loadDevTools = function() {
        if (this.settings.vars.preview) {
            $(window).on('keyup', $.proxy(function(event) {
                if (this.isDevTools()) {
                    if (parseInt(event.keyCode) === 37) {
                        this.nextSlide('prev');
                    } else if (parseInt(event.keyCode) === 39) {
                        this.nextSlide('next');
                    }
                }
            }, this));

            this.$element.addClass('window-preview').append(
                $('<div>').addClass('dev-tools').append(
                    $('<a>').attr({'href': '#', 'class': 'dev-tools-btn dev-tools-prev', 'title': this.getLexicon('prev_slide')}).html(this.getLexicon('prev_slide')).on('click', $.proxy(function(event) {
                        if (this.isDevTools()) {
                            this.nextSlide('prev');
                        }

                        return false;
                    }, this)),
                    $('<a>').attr({'href': '#', 'class': 'dev-tools-btn dev-tools-next', 'title': this.getLexicon('next_slide')}).html(this.getLexicon('next_slide')).on('click', $.proxy(function(event) {
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
     * Returns a URL with the required parameters.
     * @param {String} url - The current URL.
     * @param {Array} parameters - The current URL parameters.
     */
    DigitalSignage.prototype.getAjaxUrl = function(url, parameters) {
        if (undefined === parameters) {
            parameters = [];
        }

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

        parameters.push('time=' + this.settings.syncInterval);
        parameters.push('hash=' + (new Date()).getTime());

        if (0 < parameters.length) {
            if (url.search(/\?/i) === -1) {
                return url + '?' + parameters.join('&');
            } else {
                return url + '&' + parameters.join('&');
            }
        }

        return url;
    };

    /**
     * Gets all custom plugins for the Digital Signage.
     */
    DigitalSignage.prototype.loadCustomPlugins = function() {
        this.setLog('[' + this.constructor.name + '] loadCustomPlugins');

        $('[data-plugin]', this.$element).each($.proxy(function(index, element) {
            var $element    = $(element),
                name        = $element.attr('data-plugin');

            if ($.fn[name]) {
                this.setLog('[' + this.constructor.name + '] loadCustomPlugins: ' + this.getLexicon('custom_plugin', {
                    plugin : name
                }));

                this.plugins[name.toLowerCase()] = $element[name](this);
            } else {
                this.setError('[' + this.constructor.name + '] loadCustomPlugins: ' + this.getLexicon('error_custom_plugin', {
                    plugin : name
                }));
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
                    vars : this.settings.vars
                });
            }
        }

        return $.extend({}, {
            vars : this.settings.vars
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
     * Display an error message.
     * @param {String} message - The error to display.
     * @param {Boolean} fatal - If the error is fatal or not.
     */
    DigitalSignage.prototype.setError = function(message, fatal) {
        this.setLog(message, true, fatal);

        if (this.showErrors() || fatal) {
            if ($error = this.getTemplate('error', this.$errorTemplates)) {
                if (typeof message === 'string') {
                    this.setPlaceholders($error, {
                        title   : fatal ? this.getLexicon('error') : this.getLexicon('warning'),
                        error   : fatal ? 'error' : 'warning',
                        message : message
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
        if (index === 'current') {
            if (this.data[key]) {
                if (this.data[key]['current']) {
                    return this.data[key]['current'];
                }
            }

            return -1;
        } else if (index === 'data') {
            if (this.data[key]) {
                if (this.data[key]['output']) {
                    return this.data[key]['output'];
                }
            }

            return [];
        } else if (index === 'length') {
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

            if (null === length || length > this.data[key]['output'].length) {
                length = this.data[key]['output'].length;
            }

            if (type === 'prev') {
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
     * Sets all the templates of a Digital Signage.
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
     * Gets a templates of the DigitalSignage.
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
     * @param {Object} data - The data of the lexicon.
     * @param {String} emptyLexicon - The default return.
     */
    DigitalSignage.prototype.getLexicon = function(key, data, emptyLexicon) {
        var lexicon = 'undefined';

        if (emptyLexicon !== undefined) {
            lexicon = emptyLexicon;
        }

        if ($.fn.DigitalSignage.lexicons[key]) {
            lexicon = $.fn.DigitalSignage.lexicons[key];
        }

        if (data !== null && 'object' === typeof data) {
            $.each(data, function(key, value) {
                lexicon = lexicon.replace('%' + key + '%', value);
            });
        }

        return lexicon;
    };

    /**
     * Gets a placeholder of a template.
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
     * Sets the placeholder of a template.
     * @param {HTMLElement} $template - The template.
     * @param {Object} data - The data of the placeholders.
     * @param {Boolean} reset - Reset all placeholders if empty.
     */
    DigitalSignage.prototype.setPlaceholders = function($template, data, reset) {
        var placeholders = $('[data-placeholder]', $template);

        for (var i = 0; i < placeholders.length; i++) {
            if ($placeholder = $(placeholders[i])) {
                var type    = $placeholder.prop('tagName').toUpperCase(),
                    name    = $placeholder.attr('data-placeholder'),
                    wrapper = $placeholder.attr('data-placeholder-wrapper'),
                    renders = $placeholder.attr('data-placeholder-renders'),
                    value   = this.getPlaceholderValue(name, data, renders),
                    isEmpty = value === null || value === undefined || value === '';

                switch (type) {
                    case 'IMG':
                    case 'IFRAME':
                        if (isEmpty) {
                            if (reset === undefined || reset) {
                                $placeholder.attr('src', '').hide();
                            }
                        } else {
                            $placeholder.attr('src', value).show();
                        }

                        break;
                    default:
                        if (isEmpty) {
                            if (reset === undefined || reset) {
                                $placeholder.html('').hide();
                            }
                        } else {
                            $placeholder.html(value).show();
                        }

                        break;
                }

                if (wrapper) {
                    if ($placeholder.parents('.' + wrapper)) {
                        if (isEmpty) {
                            if (reset === undefined || reset) {
                                $placeholder.parents('.' + wrapper).addClass('is-empty');
                            }
                        } else {
                            $placeholder.parents('.' + wrapper).removeClass('is-empty');
                        }
                    }
                }
            }
        }

        var placeholders = $('[data-placeholder-class]', $template);

        if ($template.attr('data-placeholder-class') !== undefined) {
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
        if (typeof name === 'string') {
            name = name.split('.');
        }

        if (value = data[name.shift()]) {
            if (typeof value === 'object') {
                value = this.getPlaceholderValue(name, value);
            }
        }

        return this.getPlaceholderValueRenders(value, renders);
    };

    /**
     * Gets the placeholder value.
     * @param {String|Null} value - The value of the placeholder.
     * @param {String|Array} renders - The renders of the placeholder.
     */
    DigitalSignage.prototype.getPlaceholderValueRenders = function(value, renders) {
        if (renders) {
            if (typeof renders === 'string') {
                renders = renders.split(',');
            }

            if (typeof value === 'string' && value !== '') {
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
                                var firstPart   = value.substring(0, ellipsis);
                                var secondPart  = value.substring(ellipsis);

                                if (-1 === (secondSpace = secondPart.indexOf(' '))) {
                                    secondSpace = secondPart.length - 1;
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
                                month   = date.getMonth() + 1,
                                year    = date.getFullYear(),
                                now     = new Date(),
                                today   = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 0, 0, 0, 0),
                                days    = Math.round(Math.abs(today.getTime() - date.getTime()) / (1000 * 3600 * 24)),
                                format  = param ? param : '%l %d %F';

                            var formats = {
                                '%h'    : hours,
                                '%H'    : ('0' + hours).slice(-2),
                                '%i'    : minutes,
                                '%I'    : ('0' + minutes).slice(-2),
                                '%s'    : seconds,
                                '%S'    : ('0' + seconds).slice(-2),
                                '%j'    : dates,
                                '%d'    : ('0' + dates).slice(-2),
                                '%D'    : this.getLexicon('day_' + day).substr(0, 3),
                                '%l'    : this.getLexicon('day_' + day),
                                '%W'    : day,
                                '%M'    : this.getLexicon('month_' + month).substr(0, 3),
                                '%F'    : this.getLexicon('month_' + month),
                                '%m'    : ('0' + month).slice(-2),
                                '%n'    : month,
                                '%y'    : year.toString().substr(2, 2),
                                '%Y'    : year.toString(),
                                '%q'    : this.getLexicon('day_replace_' + days, null, this.getLexicon('day_' + day).toString())
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
        this.setLog('[' + this.constructor.name + '] setTimer: (time: ' + time + ')');

        if ($timer = this.getTimer('progress')) {
            if (this.settings.timerType === 'vertical') {
                var pos1    = {height: '0px'};
                var pos2    = {height: '100%'};
            } else {
                var pos1    = {width: '0px'};
                var pos2    = {width: '100%'};
            }

            $timer.css(pos1).stop().animate(pos2, {
                easing      : 'linear',
                duration    : time * 1000,
                complete    : $.proxy(function(event) {
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
        this.setLog('[' + this.constructor.name + '] getSlide: (title: ' + data.title + ')');

        if (null === ($slide = this.getTemplate(data.slide.replace('_', '-'), this.$templates))) {
            $slide = this.getTemplate('default', this.$templates);
        }

        if ($slide !== null) {
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

        this.setLog('[' + this.constructor.name + '] nextSlide: (next: ' + next + ')');

        if (next !== null) {
            if (null !== (data = this.getData('slides', next))) {
                var data = $.extend({}, {
                    slide       : 'default',
                    fullscreen  : false
                }, data);

                if ($slide = this.getSlide(data)) {
                    this.enableDevTools(false);

                    var zIndex = null;

                    for (var i = 0; i < this.settings.keys.length; i++) {
                        var key = this.settings.keys[i];

                        if (data[key]) {
                            if (parseInt(data[key]) === 1 || data[key] === 'true') {
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

                    if (zIndex !== null || undefined !== (zIndex = $slide.attr('data-slide-index'))) {
                        $slide.css({'z-index': zIndex, 'position': 'absolute', 'top': 0, 'bottom': 0, 'left': 0, 'right': 0});
                    }

                    $slide.hide().fadeIn(this.settings.animationTime * 1000);

                    if ($current = this.$slides.shift()) {
                        $current.show().fadeOut(this.settings.animationTime * 1000, $.proxy(function () {
                            if (!data.fullscreen) {
                                this.$element.removeClass('window-fullscreen');
                            }

                            if ($current) {
                                $current.remove();
                            }

                            this.enableDevTools(true);
                        }, this));
                    } else {
                        this.enableDevTools(true);
                    }

                    this.$slides.push($slide);
                } else {
                    this.setError('[' + this.constructor.name + '] nextSlide: ' + this.core.getLexicon('error_no_slide'));

                    this.nextSlide();
                }
            } else {
                this.setError('[' + this.constructor.name + '] nextSlide: ' + this.core.getLexicon('error_no_slide_data'));

                this.nextSlide();
            }
        } else {
            this.setError('[' + this.constructor.name + '] nextSlide: ' + this.core.getLexicon('error_no_data'));
        }
    };

    /**
     * Gets the plugin name of the slide.
     * @param {HTMLElement} $slide - The slide.
     * @param {String} type - The type of the slide.
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
     * @param {String} input - The XML data.
     * @param {String} element - The elements.
     */
    DigitalSignage.prototype.parseXML = function(input, element) {
        var data = [],
            $xml = $($.parseXML(input));

        if (0 < $(element, $xml).length) {
            $(element, $xml).each($.proxy(function(index, value) {
                var item = {};

                $(value).children().each(function(index, value) {
                    var type = $(this).prop('tagName');

                    if ('enclosure' === type) {
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
                    data.register({type: DigitalSignage.Type.Event, name: event});

                    data.$element.on(event + '.digitalsignage.core', $.proxy(function(e) {
                        if (e.namespace && this !== e.relatedTarget) {
                            this.suppress([event]);

                            data[event].apply(this, [].slice.call(arguments, 1));

                            this.release([event]);
                        }
                    }, data));
                });
            }

            if (typeof option === 'string' && '_' !== option.charAt(0)) {
                data[option].apply(data, args);
            }
        });
    };

    /**
     * The constructor for the jQuery Plugin.
     * @public.
     */
    $.fn.DigitalSignage.Constructor = DigitalSignage;

    /**
     * The lexicons for the jQuery Plugin.
     * @public.
     */
    $.fn.DigitalSignage.lexicons = {
        error                   : 'Fout',
        warning                 : 'Waarschuwing',

        error_player            : 'Digital Signage kon niet geladen worden omdat de mediaspeler niet gedefinieerd is.',
        error_broadcast         : 'Digital Signage kon niet geladen worden omdat de uitzending niet gedefinieerd is.',

        error_feed              : 'Feed kon niet geladen worden omdat deze niet gedefinieerd is.',
        error_feed_http         : 'Feed kon niet geladen worden (HTTP status: %status%).',
        error_feed_format       : 'Feed kon niet geladen worden omdat het formaat niet ondersteund word (Formaat: %format%).',
        error_feed_empty        : 'Feed kon niet geladen worden omdat het geen items bevat.',
        feed_loaded             : 'Feed geladen met %items% items.',

        error_callback          : 'Callback kon niet geladen worden omdat deze niet gedefinieerd is.',
        error_callback_http     : 'Callback kon niet geladen worden (HTTP status: %status%).',
        error_callback_format   : 'Callback kon niet geladen worden omdat het formaat niet ondersteund word (Formaat: %format%).',

        error_custom_plugin     : 'Plugin "%plugin%" kon niet gevonden worden.',
        custom_plugin           : 'Plugin "%plugin%" gevonden.',

        error_no_data           : 'Geen data beschikbaar.',
        error_no_slide          : 'Geen slide beschikbaar.',
        error_no_slide_data     : 'Geen slide data beschikbaar.',

        prev_slide              : 'Vorige slide',
        next_slide              : 'Volgende slide',

        day_0                   : 'Zondag',
        day_1                   : 'Maandag',
        day_2                   : 'Dinsdag',
        day_3                   : 'Woensdag',
        day_4                   : 'Donderdag',
        day_5                   : 'Vrijdag',
        day_6                   : 'Zaterdag',

        month_1                 : 'januari',
        month_2                 : 'februari',
        month_3                 : 'maart',
        month_4                 : 'april',
        month_5                 : 'mei',
        month_6                 : 'juni',
        month_7                 : 'juli',
        month_8                 : 'augustus',
        month_9                 : 'september',
        month_10                : 'oktober',
        month_11                : 'november',
        month_12                : 'december',

        day_replace_0           : 'Vandaag',
        day_replace_1           : 'Morgen',
        day_replace_2           : 'Overmorgen'
    };
})(window.Zepto || window.jQuery, window, document);

/* ----------------------------------------------------------------------------------------- */
/* ----- Slide Default --------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Default Slide.
     * @class SlideDefault.
     * @param {HTMLElement} element - The element of the Clock Plugin.
     * @param {Array} options - The options of the Clock Plugin.
     * @param {Object} core - The DigitalSignage object for the Clock Plugin.
     */
    function SlideDefault(element, options, core) {
        /**
         * The DigitalSignage object for the Default Slide.
         */
        this.core = core;

        /**
         * Currently suppressed events to prevent them from beeing retriggered.
         */
        this._supress = {};

        /**
         * Plugin element of the Default Slide.
         */
        this.$element = $(element);

        /**
         * Current settings of the Default Slide.
         */
        this.settings = $.extend({}, SlideDefault.Defaults, options);

        this.initialize();
    }

    /**
     * Default options for the Default Slide.
     */
    SlideDefault.Defaults = {
        time : 15
    };

    /**
     * Enumeration for types.
     */
    SlideDefault.Type = {
        Event : 'event'
    };

    /**
     * Initializes the Default Slide.
     */
    SlideDefault.prototype.initialize = function() {
        this.core.setLog('[' + this.constructor.name + '] initialize');

        this.core.setPlaceholders(this.$element, this.settings);

        this.core.setTimer(this.settings.time);
    };

    /**
     * Registers an event or state.
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
    SlideDefault.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @param {Array} events - The events to release.
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
                data = new SlideDefault(this, typeof option === 'object' && option, core);

                $this.data('digitalsignage.slidedefault', data);

                $.each([], function(i, event) {
                    data.register({type: SlideDefault.Type.Event, name: event});

                    data.$element.on(event + '.digitalsignage.slidedefault.core', $.proxy(function(e) {
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
     * The constructor for the Default Slide.
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
     * @class ClockPlugin.
     * @param {HTMLElement} element - The element of the Clock Plugin.
     * @param {Array} options - The options of the Clock Plugin.
     * @param {Object} core - The DigitalSignage object for the Clock Plugin.
     */
    function ClockPlugin(element, options, core) {
        /**
         * The DigitalSignage object for the Clock Plugin.
         */
        this.core = core;

        /**
         * Plugin element of the Clock Plugin.
         */
        this.$element = $(element);

        this.initialize();
    }

    /**
     * Initializes the Clock Plugin.
     */
    ClockPlugin.prototype.initialize = function() {
        this.core.setLog('[' + this.constructor.name + '] initialize');

        setInterval($.proxy(function() {
            var date = new Date();

            this.core.setPlaceholders(this.$element, {
                time : date.toString(),
                date : date.toString()
            });
        }, this), 1000);
    };

    /**
     * The jQuery Plugin for the Clock Plugin.
     */
    $.fn.ClockPlugin = function(core, option) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('digitalsignage.clockplugin');

            if (!data) {
                data = new ClockPlugin(this, typeof option === 'object' && option, core);

                $this.data('digitalsignage.clockplugin', data);
            }

            if (typeof option === 'string' && option.charAt(0) !== '_') {
                data[option].apply(data, args);
            }
        });
    };

    /**
     * The constructor for the Clock Plugin.
     */
    $.fn.ClockPlugin.Constructor = ClockPlugin;
})(window.Zepto || window.jQuery, window, document);

/* ----------------------------------------------------------------------------------------- */
/* ----- Ticker Plugin --------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Ticker Plugin.
     * @class TickerPlugin.
     * @param {HTMLElement} element - The element of the Ticker Plugin.
     * @param {Array} options - The options of the Ticker Plugin.
     * @param {Object} core - The DigitalSignage object for the Ticker Plugin.
     */
    function TickerPlugin(element, options, core) {
        /**
         * The DigitalSignage object for the Ticker Plugin.
         */
        this.core = core;

        /**
         * Currently suppressed events to prevent them from beeing retriggered.
         */
        this._supress = {};

        /**
         * Plugin element of the Ticker Plugin.
         */
        this.$element = $(element);

        /**
         * Current settings of the Ticker Plugin.
         */
        this.settings = $.extend({}, TickerPlugin.Defaults, options, this.core.loadCustomPluginSettings(this.$element));

        /**
         * All templates of the Ticker Plugin.
         */
        this.$templates = this.core.getTemplates(this.$element);

        /**
         * The state of data of the Ticker Plugin.
         */
        this.isLoaded = false;

        /**
         * All elements of the Ticker Plugin.
         */
        this.$items = [];

        this.initialize();
    }

    /**
     * Default options for the Ticker Plugin.
     */
    TickerPlugin.Defaults = {
        feed            : null,
        feedType        : 'JSON',
        feedInterval    : 900,

        vars            : {
            player          : null,
            broadcast       : null,
            preview         : false
        }
    };

    /**
     * Enumeration for types.
     */
    TickerPlugin.Type = {
        Event : 'event'
    };

    /**
     * Initializes the Ticker Plugin.
     */
    TickerPlugin.prototype.initialize = function() {
        this.core.setLog('[' + this.constructor.name + '] initialize');

        if (this.settings.feed === null) {
            this.core.setError('[' + this.constructor.name + '] initialize: ' + this.core.getLexicon('tickerplugin_error_feed'));
        } else {
            this.loadFeed();

            if (0 < this.settings.feedInterval) {
                setInterval($.proxy(function(event) {
                    this.loadFeed();
                }, this), this.settings.feedInterval * 1000);
            }
        }
    };

    /**
     * Loads the data for the Ticker Plugin.
     */
    TickerPlugin.prototype.loadFeed = function() {
        this.core.setLog('[' + this.constructor.name + '] loadFeed');

        $.ajax({
            url         : this.core.getAjaxUrl(this.settings.feed, ['type=ticker', 'data=true']),
            dataType    : this.settings.feedType.toUpperCase(),
            complete    : $.proxy(function(result) {
                if (parseInt(result.status) === 200) {
                    switch (this.settings.feedType.toUpperCase()) {
                        case 'JSON':
                            if (result.responseJSON) {
                                if (result.responseJSON.items.length > 0) {
                                    var data = [];

                                    for (var i = 0; i < result.responseJSON.items.length; i++) {
                                        data.push(result.responseJSON.items[i]);
                                    }

                                    this.core.setData(this.constructor.name.toLowerCase(), data);

                                    this.core.setLog('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('tickerplugin_feed_loaded', {
                                        items : result.responseJSON.items.length
                                    }));
                                } else {
                                    this.core.setLog('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('tickerplugin_error_feed_empty', {
                                        items : result.responseJSON.items.length
                                    }));
                                }
                            } else {
                                this.core.setError('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('tickerplugin_error_feed_format', {
                                    format : this.settings.feedType.toUpperCase()
                                }));
                            }

                            break;
                        default:
                            this.core.setError('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('tickerplugin_error_feed_format', {
                                format : this.settings.feedType.toUpperCase()
                            }));

                            break;
                    }
                } else {
                    this.core.setError('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('tickerplugin_error_feed_http', {
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
    TickerPlugin.prototype.start = function() {
        if (this.core.getData(this.constructor.name.toLowerCase(), 'length') > 0) {
            if (!this.isLoaded) {
                this.addItem();
                this.addItem();

                this.nextItem();
            }

            this.isLoaded = true;
        }
    },

    /**
     * Sets the current item
     */
    TickerPlugin.prototype.addItem = function() {
        if ($item = this.core.getTemplate('ticker', this.$templates)) {
            var data = this.core.getData(this.constructor.name.toLowerCase(), 'data');

            for (var i = 0; i < data.length; i++) {
                if ($subItem = this.core.getTemplate('item', this.$templates)) {
                    this.core.setPlaceholders($subItem, data[i]).appendTo($item);
                }
            }

            this.core.getPlaceholder('ticker', this.$element).append($item);

            this.$items.push($item);
        }
    };

    /**
     * Animate the current item.
     */
    TickerPlugin.prototype.nextItem = function() {
        if ($item = this.$items.shift()) {
            var proxy = this;
            
            $item.animate({'margin-left': '-' + $item.outerWidth(true) + 'px'}, {
                easing      : 'linear',
                duration    : 40000 * ($item.outerWidth(true) / 2100),
                complete    : function() {
                    $(this).remove();

                    proxy.addItem();
                    proxy.nextItem();
                }
            });
        }
    };

    /**
     * Registers an event or state.
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
    TickerPlugin.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @param {Array} events - The events to release.
     */
    TickerPlugin.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the Ticker Plugin.
     */
    $.fn.TickerPlugin = function(core, option) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('digitalsignage.tickerplugin');

            if (!data) {
                data = new TickerPlugin(this, typeof option === 'object' && option, core);

                $this.data('digitalsignage.tickerplugin', data);

                $.each([], function(i, event) {
                    data.register({type: TickerPlugin.Type.Event, name: event});

                    data.$element.on(event + '.digitalsignage.tickerplugin.core', $.proxy(function(e) {
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
     * The constructor for the Ticker Plugin.
     */
    $.fn.TickerPlugin.Constructor = TickerPlugin;

    /**
     * The lexicons for the Ticker Plugin.
     */
    $.extend($.fn.DigitalSignage.lexicons, {
        tickerplugin_error_feed             : 'Ticker kon niet geladen worden omdat de feed niet gedefinieerd is.',
        tickerplugin_error_feed_http        : 'Feed kon niet geladen worden (HTTP status: %status%).',
        tickerplugin_error_feed_format      : 'Feed kon niet geladen worden omdat het formaat niet ondersteund word (Formaat: %format%).',
        tickerplugin_error_feed_empty       : 'Feed kon niet geladen worden omdat het geen items bevat.',
        tickerplugin_feed_loaded            : 'Feed geladen met %items% items.'
    });
})(window.Zepto || window.jQuery, window, document);
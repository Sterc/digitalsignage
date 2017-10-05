/* Javascript for Narrowcasting. (c) Sterc.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- jQuery onload --------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

$(document).ready(function() {
    $('.window').Narrowcasting({
        'debug'     : settings.debug,

        'callback'  : settings.callback,

        'feed' 		: settings.broadcast.feed,
        
        'vars'		: {
	    	'player'	: settings.player,
	    	'broadcast' : settings.broadcast.id,
	    	'preview'	: settings.preview
        },
        
        'domain'	: document.location.origin
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
        this.$templates = this.getTemplates(this.getPlaceholder('slides', this.$element), true);

        /**
         * All error templates of the Narrowcasting.
         * @protected.
         */
        this.$errorTemplates = this.getTemplates(this.getPlaceholder('errors', this.$element), true);

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

        /**
         * The current plugins of Narrowcasting.
         */
        this.plugins = [];

        this.initialize();
    }

    /**
     * Default options for the Narrowcasting.
     * @public.
     */
    Narrowcasting.Defaults = {
	    'debug': false,

        'timer': true,
        'timerType': 'vertical',
        'timerClass': 'timer',

        'animation': 'fade',
        'animationTime': 1,

		'callback': null,
		'callbackType': 'JSON',
		'callbackInterval': 300,

        'feed': null,
        'feedType': 'JSON',
		'feedInterval': 300,
		
		'vars': {
			'player': null,
			'broadcast': null,
			'preview': false
		},

		'domain': '',

        'lexicons': {
            'days' : ['Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'],
            'months' : ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december'],
            'dayTypes': ['Vandaag', 'Morgen', 'Overmorgen']
        }
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

	        this.loadCustomPlugins();
	    }
    };

    /**
     * Checks the the Narrowcasting.
     * @protected.
     */
    Narrowcasting.prototype.getRequirements = function() {
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
     * Loads the callback data for the Narrowcasting.
     * @protected.
     */
    Narrowcasting.prototype.loadCallback = function() {
	    this.setLog('[Core] loadCallback');
	    
	    $.ajax({
            'url'		: this.settings.callback + this.getUrlParameters('callback'),
            'dataType'	: this.settings.callbackType.toUpperCase(),
            'complete'	: $.proxy(function(result) {
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
     * Gets the debug state for the Narrowcasting.
     * @protected.
     */
    Narrowcasting.prototype.isDebug = function() {
	    return this.settings.debug;
    };

    /**
     * Loads the data for the Narrowcasting.
     * @protected.
     */
    Narrowcasting.prototype.loadData = function() {
	    this.setLog('[Core] loadData');

        $.ajax({
            'url'		: this.settings.feed + this.getUrlParameters('feed'),
            'dataType'	: this.settings.feedType.toUpperCase(),
            'complete'	: $.proxy(function(result) {
                if (200 == result.status) {
                    switch (this.settings.feedType.toUpperCase()) {
                        case 'JSON':
                            if (result.responseJSON) {
	                            if (0 < result.responseJSON.slides.length) {
		                            this.data = new Array();
		                            
	                                for (var i = 0; i < result.responseJSON.slides.length; i++) {
	                                    this.data.push(result.responseJSON.slides[i]);
	                                }
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

                this.dataRefresh++;
            }, this)
        });
    };
    
    /**
     * Returns all the URL parameters.
     * @param {Type} String - The URL type.
     * @public.
     */
    Narrowcasting.prototype.getUrlParameters = function(type) {
	    var parameters = new Array('type=broadcast', 'data=true');

        if ('callback' == type) {
            parameters.push('time=' + this.settings.callbackInterval);
        } else {
            parameters.push('time=' + this.settings.feedInterval);
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
		
		if (0 < parameters.length) {
            if ('callback' == type) {
                if (-1 == this.settings.callback.search(/\?/i)) {
                    return '?' + parameters.join('&');
                } else {
                    return '&' + parameters.join('&');
                }
            } else {
                if (-1 == this.settings.feed.search(/\?/i)) {
                    return '?' + parameters.join('&');
                } else {
                    return '&' + parameters.join('&');
                }
            }
		}
		
		return '';
    };

    /**
     * Gets all custom plugins for the Narrowcasting.
     * @protected.
     */
    Narrowcasting.prototype.loadCustomPlugins = function() {
        this.setLog('[Core] loadCustomPlugins');

        $('[data-plugin]', this.$element).each($.proxy(function(index, element) {
            var $element    = $(element),
                plugin       = $element.attr('data-plugin');

            if ($.fn[plugin]) {
                this.setLog('[Core] loadCustomPlugins: (plugin: ' + plugin + ')');

                this.plugins[plugin.toLowerCase()] = $element[plugin](this);
            }
        }, this));
    };

    /**
     * Gets all custom plugin settings for the Narrowcasting.
     * @param {$element} Object - The element of the plugin.
     * @protected.
     */
    Narrowcasting.prototype.loadCustomPluginSettings = function($element) {
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
     * @public.
     * @param {Message} String - The message to to.
     * @param {Error} Boolean - If the message is an error.
     * @param {Fatal} Boolean - If the message is fatal or not.
     */
    Narrowcasting.prototype.setLog = function(message, error, fatal) {
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
     * @public.
     * @param {Message} String - The error to display.
     * @param {Fatal} Boolean - If the error is fatal or not.
     */
    Narrowcasting.prototype.setError = function(message, fatal) {
        this.setLog(message, true, fatal);

        if ($error = this.getTemplate('error', this.$errorTemplates)) {
            if (typeof message === 'string') {
                this.setPlaceholders($error, {
                    'title'		: fatal ? 'Error' : 'Warning',
                    'error'     : fatal ? 'error' : 'warning',
                    'message' 	: message
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

        return false;
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
     * Gets the translated text.
     * @public.
     * @param {Key} string - The key of the lexicon.
     * @param {Category} string|object - The type lexicon.
     * @param {Default} string - The default return.
     */
    Narrowcasting.prototype.getLexicon = function(key, category, lexicon) {
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
            }
        }

        if (undefined !== lexicon) {
            return lexicon;
        }

        return 'undefined';
    };

    /**
     * Gets a placeholder of a narrowcasting object.
     * @public.
     * @param {Placeholder} string - The name of the placeholder.
     * @param {$element} HTMLelement - The HTML object.
     */
    Narrowcasting.prototype.getPlaceholder = function(placeholder, $element) {
        if (undefined !== ($placeholder = $('[data-placeholder="' + placeholder + '"]', $element))) {
            return $placeholder.show();
        }

        return null;
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
                    wrapper = $placeholder.attr('data-placeholder-wrapper'),
                    renders	= $placeholder.attr('data-placeholder-renders'),
                    value 	= this.getPlaceholderValue(name, data, renders),
                    isEmpty	= null === value || undefined === value || '' === value;

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
     * @param {Renders} string|array - The renders of the placeholder.
     */
    Narrowcasting.prototype.getPlaceholderValue = function(name, data, renders) {
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
    Narrowcasting.prototype.getPlaceholderValueRenders = function(value, renders) {
	    if (renders) {
		    if (typeof renders == 'string') {
	            renders = renders.split(',');
	        }

	        if (typeof value == 'string') {
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
			    			var parts = value.replace(/\/$/gm, '').split('/');

			    			if (undefined !== (value = parts[parts.length - 1])) {
				    			if (undefined !== (value = value.replace(/watch\?v=/gi, '').split(/[?#]/)[0])) {
						    		value = '//www.youtube.com/embed/' + value + (param ? param : '?autoplay=1&controls=0&rel=0&showinfo=0');
				    			}
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
                                '%h'	: hours,
                                '%H'	: hours < 10 ? ('0' + hours) : hours,
                                '%i'	: minutes,
                                '%I'	: minutes < 10 ? ('0' + minutes) : minutes,
                                '%s'	: seconds,
                                '%S'	: seconds < 10 ? ('0' + seconds) : seconds,
                                '%j'	: dates,
                                '%d'	: dates < 10 ? ('0' + dates) : dates,
                                '%D'	: this.getLexicon(day, 'days').substr(0, 3),
                                '%l'	: this.getLexicon(day, 'days'),
                                '%W'	: day,
                                '%M'	: this.getLexicon(month, 'months').substr(0, 3),
                                '%F'	: this.getLexicon(month, 'months'),
                                '%m'	: month < 10 ? ('0' + month) : month,
                                '%n'	: month + 1,
                                '%y'	: year.toString().substr(2, 2),
                                '%Y'	: year.toString(),
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
		    		value = value.replace(/<\s*(\w+).*?>/gi, '<\$1>');
					value = value.replace(/<\/?(span|a)[^>]*>/gi, '');
				}
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
	    this.setLog('[Core] setTimer: (time: ' + time + ')');
	    
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
     * @param {Data} array - The slide data.
     */
    Narrowcasting.prototype.getSlide = function(data) {
        this.setLog('[Core] getSlide: (title: ' + data.title + ')');

        if (null === ($slide = this.getTemplate(data.slide, this.$templates))) {
            $slide = this.getTemplate('default', this.$templates);
        }

        if (null !== $slide) {
            $slide.prependTo($(this.getPlaceholder('slides', this.$element)));

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
        
        return null;
    };
	
    /**
     * Sets the next slide and animate current en next slide.
     * @public.
     */
    Narrowcasting.prototype.nextSlide = function() {
	    var next = this.getCurrent();
	    
	    this.setLog('[Core] nextSlide: (next: ' + next + ')');
	    
	    if (this.data[next]) {
		    var data = $.extend({}, {
                'slide' : 'default'
            }, this.data[next]);
            
	        if ($slide = this.getSlide(data)) {
		        if (data.fullscreen) {
			        this.$element.addClass('slide-fullscreen');
			        
			        this.$element.addClass('window-fullscreen');
                } else {
	                this.$element.removeClass('window-fullscreen');
                }
	                
	            $slide.hide().fadeIn(this.settings.animationTime * 1000);
	
	            if ($current = this.$slides.shift()) {
	                $current.show().fadeOut(this.settings.animationTime * 1000, $.proxy(function() {
		                if (!data.fullscreen) {
							this.$element.removeClass('slide-fullscreen');
                		}
	                
	                    $current.remove();
	                }, this));
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
     * @param {Message} string - The message of skip.
     */
    Narrowcasting.prototype.skipSlide = function(message) {
	    this.setLog('[Core] skipSlide: (message: ' + message + ')');
	    
	    this.nextSlide();
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

                $.each([], function(i, event) {
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

                $.each([], function(i, event) {
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
/* ----- Clock Plugin ---------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Clock Plugin.
     * @class Clock Plugin.
     * @public.
     * @param {Element} HTMLElement|jQuery - The element of the Clock Plugin.
     * @param {Options} array - The options of the Clock Plugin.
     * @param {Core} Object - The Narrowcasting object for the Clock Plugin.
     */
    function ClockPlugin(element, options, core) {
        /**
         * The Narrowcasting object for the Clock Plugin.
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
                data = $this.data('narrowcasting.clockplugin');

            if (!data) {
                data = new ClockPlugin(this, typeof option == 'object' && option, core);

                $this.data('narrowcasting.clockplugin', data);
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
/* ----- Newsticker ------------------------------------------------------------------------ */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Newsticker Plugin.
     * @class Newsticker Plugin.
     * @public.
     * @param {Element} HTMLElement|jQuery - The element of the Newsticker Plugin.
     * @param {Options} array - The options of the Newsticker Plugin.
     * @param {Core} Object - The Narrowcasting object for the Newsticker Plugin.
     */
    function NewstickerPlugin(element, options, core) {
        /**
         * The Narrowcasting object for the Newsticker Plugin.
         * @public.
         */
        this.core = core;

        /**
         * Plugin element.
         * @public.
         */
        this.$element = $(element);

        /**
         * Current settings for the Newsticker Plugin.
         * @public.
         */
        this.settings = $.extend({}, NewstickerPlugin.Defaults, options, this.core.loadCustomPluginSettings(this.$element));

        /**
         * Currently suppressed events to prevent them from beeing retriggered.
         * @protected.
         */
        this._supress = {};

        /**
         * All templates of the Newsticker Plugin.
         * @protected.
         */
        this.$templates = this.core.getTemplates(this.$element);

        /**
         * The data of the Newsticker Plugin.
         * @protected.
         */
        this.data = [];

        /**
         * The number of times that the data of the Newsticker Plugin is loaded.
         * @protected.
         */
        this.dataRefresh = 0;

        /**
         * All elements of the Newsticker Plugin.
         * @protected.
         */
        this.$elements = [];

        this.initialize();
    }

    /**
     * Default options for the Newsticker Plugin.
     * @public.
     */
    NewstickerPlugin.Defaults = {
        'feed': null,
        'feedType': 'JSON',
        'feedInterval': 900,
        
        'vars': {
			'player': null,
			'broadcast': null,
			'preview': false
		}
    };

    /**
     * Enumeration for types.
     * @public.
     * @readonly.
     * @enum {String}.
     */
    NewstickerPlugin.Type = {
        'Event': 'event'
    };

    /**
     * Initializes the Newsticker Plugin.
     * @protected.
     */
    NewstickerPlugin.prototype.initialize = function() {
	    this.core.setLog('[NewstickerPlugin] initialize');

        if (null === this.settings.feed) {
            this.core.setError('[NewstickerPlugin] feed is not defined.');
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
     * Loads the data for the Newsticker Plugin.
     * @protected.
     */
    NewstickerPlugin.prototype.loadData = function() {
	    this.core.setLog('[NewstickerPlugin] loadData');
	    
        $.ajax({
            'url'		: this.settings.feed + this.getUrlParameters(),
            'dataType'	: this.settings.feedType.toUpperCase(),
            'complete'	: $.proxy(function(result) {
                if (200 == result.status) {
                    switch (this.settings.feedType.toUpperCase()) {
                        case 'JSON':
                            if (result.responseJSON) {
	                            if (0 < result.responseJSON.items.length) {
		                            this.data = new Array();
		                            
                                	for (var i = 0; i < result.responseJSON.items.length; i++) {
										this.data.push(result.responseJSON.items[i]);
                                	}
                                } else {
		                            this.loadData();
	                            }
                                
                                this.core.setLog('[NewstickerPlugin] loadData: (slides: ' + result.responseJSON.items.length + ')');
                            } else {
                                this.core.setError('[NewstickerPlugin] feed could not be read (Format: ' + this.settings.feedType.toUpperCase() + ').');
                            }

                            break;
                        default:
                            this.core.setError('[NewstickerPlugin] feed could not be read because the format is not supported (Format: ' + this.settings.feedType.toUpperCase() + ').');

                            break;
                    }

                    if (0 == this.dataRefresh) {
                        this.setData();
                        this.setData();

                        this.start();
                    }
                } else {
                    this.core.setError('[NewstickerPlugin] feed could not be loaded (HTTP status: ' + result.status + ').');
                }

                this.dataRefresh++;
            }, this)
        });
    };
    
    /**
     * Returns all the URL parameters.
     * @public.
     */
    NewstickerPlugin.prototype.getUrlParameters = function() {
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
    NewstickerPlugin.prototype.setData = function() {
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
    NewstickerPlugin.prototype.start = function() {
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
    NewstickerPlugin.prototype.register = function(object) {
        if (object.type === NewstickerPlugin.Type.Event) {
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
    NewstickerPlugin.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @protected.
     * @param {Array.<String>} events - The events to release.
     */
    NewstickerPlugin.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the Newsticker Plugin.
     * @public.
     */
    $.fn.NewstickerPlugin = function(core, option) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('narrowcasting.newstickerplugin');

            if (!data) {
                data = new NewstickerPlugin(this, typeof option == 'object' && option, core);

                $this.data('narrowcasting.newstickerplugin', data);

                $.each([], function(i, event) {
                    data.register({ type: NewstickerPlugin.Type.Event, name: event });

                    data.$element.on(event + '.narrowcasting.newstickerplugin.core', $.proxy(function(e) {
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
    $.fn.NewstickerPlugin.Constructor = NewstickerPlugin;

})(window.Zepto || window.jQuery, window, document);

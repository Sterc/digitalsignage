/* Javascript for narrowcasting project. (c) Oetzie.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- Slide Buienradar ------------------------------------------------------------------ */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
	/**
	 * Creates a Buienradar Slide.
	 * @class Buienradar.
	 * @public.
	 * @param {Element} HTMLElement|jQuery - The element of the Buienradar Slide.
	 * @param {Options} array - The options of the Buienradar Slide.
	 * @param {Core} Object - The Narrowcasting object for the Buienradar Slide.
	 */
	function SlideBuienradar(element, options, core) {
		/**
		 * The Narrowcasting object for the Buienradar Slide.
		 * @public.
		 */
		this.core = core;
		
		/**
		 * Current settings for the Buienradar Slide.
		 * @public.
		 */
		this.settings = $.extend({}, SlideBuienradar.Defaults, options);
		
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
	 * Default options for the Buienradar Slide.
	 * @public.
	 */
	SlideBuienradar.Defaults = {
		'time': 15,
		'lat': null,
		'lng': null,
		
		'radar': '//api.buienradar.nl/image/1.0/radarmapnl',
		
		'forecastFeed': '//api.buienradar.nl/data/forecast/1.1/daily/',
		'forecastCount': 3,
		'forecastText': ['Vandaag', 'Morgen', 'Overmorgen'],
		'forecastLabelText': ['Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'],
		
		'precipitationFeed': '//graphdata.buienradar.nl/forecast/json/',
		'precipitationText': [
			'Er is geen neerslag verwacht.',
			'Er is %label neerslag verwacht om %start, om %end is het weer droog.',
			'Er is %label neerslag verwacht om %start.'
		],
		'precipitationLabelText': ['lichte', 'matige', 'zware']
	};
	
	/**
	 * Enumeration for types.
	 * @public.
	 * @readonly.
	 * @enum {String}.
	 */
	SlideBuienradar.Type = {
		'Event': 'event'
	};

	/**
	 * Initializes the Buienradar Slide.
	 * @protected.
	 */
	SlideBuienradar.prototype.initialize = function() {
		this.setRadar();
		this.setForecast();
		this.setPrecipitation();
				
		this.core.setPlaceholders(this.$element, this.settings);
		
		this.core.setTimer(this.settings.time);
	};
	
	/**
	 * Sets the radar of the Buienradar Slide.
	 * @protected.
	 */
	SlideBuienradar.prototype.setRadar = function() {
		if ($radar = this.core.getPlaceholder('radar', this.$element)) {
			if (this.settings.radar) {
				var date = new Date();

				$radar.attr('src', this.settings.radar + '?d=' + date.getTime()).show();
			} else {
				$radar.attr('src', '').hide();
			}
		} else {
			$radar.attr('src', '').hide();
		}
	};
	
	/**
	 * Sets the forecast of the Buienradar Slide.
	 * @protected.
	 */
	SlideBuienradar.prototype.setForecast = function() {
		var $placeholder = this.core.getPlaceholder('forecast', this.$element);
		
		if ($placeholder) {
			var $templates = this.core.getTemplates($placeholder);

			if (this.settings.forecastFeed) {
				$.ajax({
					'url'		: this.settings.forecastFeed + '?lat=' + this.settings.lat + '&lon=' + this.settings.lng,
					'dataType'	: 'JSON',
					'complete'	: $.proxy(function(result) {
						if (200 == result.status) {
							var data = [];	
							
							if (result.responseJSON) {
								for (var i = 0; i < result.responseJSON.days.length; i++) {
									if (i < this.settings.forecastCount || null == this.settings.forecastCount) {
										data.push(result.responseJSON.days[i]);
									}
								}
							} else {
								this.core.setError('Buienradar weersverwachting feed kon niet gelezen worden (Formaat: JSON).');
							}

							for (var i = 0; i < data.length; i++) {
								var $template = this.core.getTemplate('forecast', $templates);
								
								if ($template) {
									if (data[i]['date']) {
										var dateNow 	= new Date(),
											dateNext 	= new Date(data[i]['date']),
											dates		= Math.abs(Math.floor((dateNext.setHours(0, 0, 0, 0) - dateNow.setHours(0, 0, 0, 0)) / (1000 * 60 * 60 * 24)));
									
										if (this.settings.forecastText[dates]) {
											data[i]['date'] = this.settings.forecastText[dates];
										} else if (this.settings.forecastLabelText[dateNext.getDay()]) {
											data[i]['date'] = this.settings.forecastLabelText[dateNext.getDay()];
										}
									}
									
									this.core.setPlaceholders($template, data[i]).appendTo($placeholder);
								}
							}
						} else {
							this.core.setError('Buienradar weersverwachting feed kon niet geladen worden (HTTP status: ' + result.status + ').');
						}
					}, this)
				});
			}
		}
	};
	
	/**
	 * Sets the precipitation of the Buienradar Slide.
	 * @protected.
	 */
	SlideBuienradar.prototype.setPrecipitation = function() {
		var $placeholder = this.core.getPlaceholder('precipitation', this.$element);
		
		if ($placeholder) {
			if (this.settings.precipitationFeed) {
				$.ajax({
					'url'		: this.settings.precipitationFeed + '?lat=' + this.settings.lat + '&lon=' + this.settings.lng,
					'dataType'	: 'JSON',
					'complete'	: $.proxy(function(result) {
						if (200 == result.status) {
							var start 	= null,
								end		= null;
								
							if (result.responseJSON) {
								var borders = result.responseJSON.borders;
	
								for (var i = 0; i < result.responseJSON.forecasts.length; i++) {
									if (null == start || null == end) {
										var time = new Date(result.responseJSON.forecasts[i].datetime);
	
										if (null == start) {
											if (0 < result.responseJSON.forecasts[i].value) {
												var label = '';
												
												for (var ii = 0; ii < borders.length; ii++) {
													if (result.responseJSON.forecasts[i].value >= borders[ii].lower && result.responseJSON.forecasts[i].value <= borders[ii].upper) {
														if (this.settings.precipitationLabelText[ii]) {
															label = this.settings.precipitationLabelText[ii];
														}
													}
												}
												
												start = {
													'time'	: this.getZero(time.getHours()) + ':' + this.getZero(time.getMinutes()),
													'value'	: result.responseJSON.forecasts[i].value,
													'label'	: label
												};
											}	
										} else {
											if (0 == result.responseJSON.forecasts[i].value) {
												end = {
													'time'	: this.getZero(time.getHours()) + ':' + this.getZero(time.getMinutes()),
													'value'	: result.responseJSON.forecasts[i].value,
												};
											}
										}
									} else {
										break;
									}
								}
							} else {
								this.core.setError('Buienradar neerslag feed kon niet gelezen worden (Formaat: JSON).');
							}
							
							if (null == start && null == end) {
								var result = this.settings.precipitationText[0];
							} else if (null != start && null != end) {
								var result = this.settings.precipitationText[1];
							} else {
								var result = this.settings.precipitationText[2];
							}
							
							if (null !== start) {
								result = result.replace('%label', start.label);
								result = result.replace('%start', start.time);
							}
							
							if (null !== end) {
								result = result.replace('%end', end.time);
							}
							
							$placeholder.html(result);
						} else {
							this.core.setError('Buienradar neerslag feed kon niet geladen worden (HTTP status: ' + result.status + ').');
						}
					}, this)
				});
			}
		}
	};
	
	/**
	 * Gets the leading zero.
	 * @protected.
	 */
	SlideBuienradar.prototype.getZero = function(format) {
		if (format < 10) {
			return '0' + format;
		}
		
		return format;
	};
		
	/**
	 * Registers an event or state.
	 * @public.
	 * @param {Object} object - The event or state to register.
	 */
	SlideBuienradar.prototype.register = function(object) {
		if (object.type === SlideBuienradar.Type.Event) {
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
	SlideBuienradar.prototype.suppress = function(events) {
		$.each(events, $.proxy(function(index, event) {
			this._supress[event] = true;
		}, this));
	};

	/**
	 * Releases suppressed events.
	 * @protected.
	 * @param {Array.<String>} events - The events to release.
	 */
	SlideBuienradar.prototype.release = function(events) {
		$.each(events, $.proxy(function(index, event) {
			delete this._supress[event];
		}, this));
	};

	/**
	 * The jQuery Plugin for the Buienradar Slide.
	 * @public.
	 */
	$.fn.SlideBuienradar = function(option, core) {
		var args = Array.prototype.slice.call(arguments, 1);

		return this.each(function() {
			var $this = $(this),
				data = $this.data('narrowcasting.buienradar');

			if (!data) {
				data = new SlideBuienradar(this, typeof option == 'object' && option, core);
				
				$this.data('narrowcasting.buienradar', data);
				
				$.each([
					
				], function(i, event) {
					data.register({ type: SlideBuienradar.Type.Event, name: event });
					data.$element.on(event + '.narrowcasting.buienradar.core', $.proxy(function(e) {
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
	$.fn.SlideBuienradar.Constructor = SlideBuienradar;

})(window.Zepto || window.jQuery, window, document);
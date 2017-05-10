/* Javascript for narrowcasting project. (c) Oetzie.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- Slide Social Media ---------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
	/**
	 * Creates a Social Media Slide.
	 * @class SocialMedia.
	 * @public.
	 * @param {Element} HTMLElement|jQuery - The element of the Social Media Slide.
	 * @param {Options} array - The options of the Social Media Slide.
	 * @param {Core} Object - The Narrowcasting object for the Social Media Slide.
	 */
	function SlideSocialMedia(element, options, core) {
		/**
		 * The Narrowcasting object for the Social Media Slide.
		 * @public.
		 */
		this.core = core;

		/**
		 * Current settings for the Social Media Slide.
		 * @public.
		 */
		this.settings = $.extend({}, SlideSocialMedia.Defaults, options);

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
	 * Default options for the Social Media Slide.
	 * @public.
	 */
	SlideSocialMedia.Defaults = {
		'time': 15,

		'feed': null,
		'feedType': 'JSON',

		'count': 6
	};

	/**
	 * Enumeration for types.
	 * @public.
	 * @readonly.
	 * @enum {String}.
	 */
	SlideSocialMedia.Type = {
		'Event': 'event'
	};

	/**
	 * Initializes the Social Media Slide.
	 * @protected.
	 */
	SlideSocialMedia.prototype.initialize = function() {
		if (null === this.settings.feed) {
			this.core.setError('Social media feed is niet ingesteld.');
		} else {
			this.setData();
		}

		this.core.setPlaceholders(this.$element, this.settings);

		//this.core.setTimer(this.settings.time);
	};

	/**
	 * Sets the data of the Social Media Slide.
	 * @protected.
	 */
	SlideSocialMedia.prototype.setData = function() {
		var $placeholder = this.core.getPlaceholder('social-media', this.$element);

		if ($placeholder) {
			var $templates = this.core.getTemplates($placeholder);

			$.ajax({
				'url'		: this.settings.feed,
				'dataType'	: this.settings.feedType.toUpperCase(),
				'complete'	: $.proxy(function(result) {
					if (200 == result.status) {
						var data = [];

						switch (this.settings.feedType.toUpperCase()) {
							case 'JSON':
								if (result.responseJSON) {
									for (var i = 0; i < result.responseJSON.items.length; i++) {
										if (i < this.settings.count || null == this.settings.count) {
											data.push(result.responseJSON.items[i]);
										}
									}
								} else {
									this.core.setError('Social media feed kon niet gelezen worden (Formaat: ' + this.settings.feedType.toUpperCase() + ').');
								}

								break;
							case 'XML':
								if (result.responseXML) {
									$('item', result.responseXML).each(function(index, item) {
										data.push({
											'source'	: $('type', item).text(),
											'date'		: $('pubDate', item).text(),
											'name'		: $('name', item).text(),
											'username'	: $('username', item).text(),
											'image'		: $('enclosure', item).attr('url'),
											'content'	: $('description', item).text(),
											'media'		: $('media', item).text()
										});
									});
								} else {
									this.core.setError('Social media feed kon niet gelezen worden (Formaat: ' + this.settings.feedType.toUpperCase() + ').');
								}

								break;
							default:
								this.core.setError('Social media feed kon niet gelezen worden omdat het formaat niet ondersteund word (Formaat: ' + this.settings.feedType.toUpperCase() + ').');

								break;
						}

						for (var i = 0; i < data.length; i++) {
							if (data[i].source) {
								var $template = this.core.getTemplate(data[i].source, $templates);

								if ($template) {
									if (data[i].content) {
										data[i].content = this.autoHtml(data[i].content);
									}

									this.core.setPlaceholders($template, data[i]).appendTo($placeholder);
								}
							}
						}
					} else {
						this.core.setError('Social media feed kon niet geladen worden (HTTP status: ' + result.status + ').');
					}

					if (this.startAnimation()) {
						return this.core.setTimer(this.settings.time);
					}
				}, this)
			});
		}
	};

	/**
	 * Replaces hastags and accounts for HTML tag.
	 * @public.
	 * @param {Content} string - The content to replace.
	 */
	SlideSocialMedia.prototype.autoHtml = function(content) {
		content = content.replace(/#(\w+)/g, '<strong>#$1</strong>');
		content = content.replace(/@(\w+)/g, '<strong>@$1</strong>');
		content = content.replace(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/i, '<strong>$1</strong>');

		return content;
	};

	/**
	 * Sets the animation.
	 * @public.
	 */
	SlideSocialMedia.prototype.startAnimation = function() {
		var $placeholder = this.core.getPlaceholder('social-media', this.$element);

		if ($placeholder) {
			var $wrapper = $('<div>').css({
				'position' 	: 'absolute',
				'top'		: $placeholder.position().top + 'px',
				'bottom'	: '0px',
				'width'		: '100%',
				'overflow'	: 'hidden'
			}).insertAfter($placeholder);

			var $content = $('<div>').css({
				'position'	: 'relative',
			}).append($placeholder).appendTo($wrapper);

			if ($content.outerHeight(true) > $wrapper.outerHeight(true)) {
				$content.animate({
					'top' 		: '-' + ($content.outerHeight(true) - $wrapper.outerHeight(true)) + 'px'
				}, {
					'easing' 	: 'linear',
					'duration' 	: 40000 * ($content.outerHeight(true) / 2100)
				});

				this.settings.time = (40000 * ($content.outerHeight(true) / 2100)) / 1000;
			}
		}

		return true;
	};

	/*startAnimation: function() {
			var _ = this;

			_.data['feed'].wrapper.after(
				wrapper = $('<div>').css({
					'position' 	: 'absolute',
					'top'		: _.data['feed'].wrapper.position().top + 'px',
					'bottom'	: '0px',
					'width'		: '100%',
					'overflow'	: 'hidden'
				}).append(
					content = $('<div>').css({
						'position'	: 'absolute',
						'top'		: '0px',
						'width'		: '100%'
					})
				)
			);

			_.data['feed'].wrapper.appendTo(content);

			if (content.outerHeight(true) > wrapper.outerHeight(true)) {
				content.css({
					'top' : '0px'
				}).animate({
					'top' : '-' + (content.outerHeight(true) - wrapper.outerHeight(true)) + 'px'
				}, {
					easing 		: 'linear',
					duration 	: _.settings.ticker.time * (content.outerHeight(true) / _.settings.ticker.height)
				})

				_.core.setTimer((_.settings.ticker.time * (content.outerHeight(true) / _.settings.ticker.height)) / 1000);
			} else {
				_.core.setTimer(_.settings.data.time);
			}
		}*/

	/**
	 * Registers an event or state.
	 * @public.
	 * @param {Object} object - The event or state to register.
	 */
	SlideSocialMedia.prototype.register = function(object) {
		if (object.type === SlideSocialMedia.Type.Event) {
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
	SlideSocialMedia.prototype.suppress = function(events) {
		$.each(events, $.proxy(function(index, event) {
			this._supress[event] = true;
		}, this));
	};

	/**
	 * Releases suppressed events.
	 * @protected.
	 * @param {Array.<String>} events - The events to release.
	 */
	SlideSocialMedia.prototype.release = function(events) {
		$.each(events, $.proxy(function(index, event) {
			delete this._supress[event];
		}, this));
	};

	/**
	 * The jQuery Plugin for the Social Media Slide.
	 * @public.
	 */
	$.fn.SlideSocialMedia = function(option, core) {
		var args = Array.prototype.slice.call(arguments, 1);

		return this.each(function() {
			var $this = $(this),
				data = $this.data('narrowcasting.social-media');

			if (!data) {
				data = new SlideSocialMedia(this, typeof option == 'object' && option, core);

				$this.data('narrowcasting.social-media', data);

				$.each([

				], function(i, event) {
					data.register({ type: SlideSocialMedia.Type.Event, name: event });
					data.$element.on(event + '.narrowcasting.social-media.core', $.proxy(function(e) {
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
	$.fn.SlideSocialMedia.Constructor = SlideSocialMedia;

})(window.Zepto || window.jQuery, window, document);

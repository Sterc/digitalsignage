/* Javascript for narrowcasting project. (c) Oetzie.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- Slide Sponsor --------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
	/**
	 * Creates a Sponsor Slide.
	 * @class SlideSponsor.
	 * @public.
	 * @param {Element} HTMLElement|jQuery - The element of the Sponsor Slide.
	 * @param {Options} array - The options of the Sponsor Slide.
	 * @param {Core} Object - The Narrowcasting object for the Sponsor Slide.
	 */
	function SlideSponsor(element, options, core) {
		/**
		 * The Narrowcasting object for the Sponsor Slide.
		 * @public.
		 */
		this.core = core;
		
		/**
		 * Current settings for the Sponsor Slide.
		 * @public.
		 */
		this.settings = $.extend({}, SlideSponsor.Defaults, options);
		
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
	 * Default options for the Sponsor Slide.
	 * @public.
	 */
	SlideSponsor.Defaults = {
		'time': 5
	};
	
	/**
	 * Enumeration for types.
	 * @public.
	 * @readonly.
	 * @enum {String}.
	 */
	SlideSponsor.Type = {
		'Event': 'event'
	};

	/**
	 * Initializes the Sponsor Slide.
	 * @protected.
	 */
	SlideSponsor.prototype.initialize = function() {
		this.core.setPlaceholders(this.$element, this.settings);
		
		this.core.setTimer(this.settings.time);
	};
		
	/**
	 * Registers an event or state.
	 * @public.
	 * @param {Object} object - The event or state to register.
	 */
	SlideSponsor.prototype.register = function(object) {
		if (object.type === SlideSponsor.Type.Event) {
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
	SlideSponsor.prototype.suppress = function(events) {
		$.each(events, $.proxy(function(index, event) {
			this._supress[event] = true;
		}, this));
	};

	/**
	 * Releases suppressed events.
	 * @protected.
	 * @param {Array.<String>} events - The events to release.
	 */
	SlideSponsor.prototype.release = function(events) {
		$.each(events, $.proxy(function(index, event) {
			delete this._supress[event];
		}, this));
	};

	/**
	 * The jQuery Plugin for the Sponsor Slide.
	 * @public.
	 */
	$.fn.SlideSponsor = function(option, core) {
		var args = Array.prototype.slice.call(arguments, 1);

		return this.each(function() {
			var $this = $(this),
				data = $this.data('narrowcasting.slidesponsor');

			if (!data) {
				data = new SlideSponsor(this, typeof option == 'object' && option, core);
				
				$this.data('narrowcasting.slidesponsor', data);
				
				$.each([
					
				], function(i, event) {
					data.register({ type: SlideSponsor.Type.Event, name: event });
					data.$element.on(event + '.narrowcasting.slidesponsor.core', $.proxy(function(e) {
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
	$.fn.SlideSponsor.Constructor = SlideSponsor;

})(window.Zepto || window.jQuery, window, document);
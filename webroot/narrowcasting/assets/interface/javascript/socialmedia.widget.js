/* Javascript for Narrowcasting. (c) Sterc.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- Social Media Widget --------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Social Media Widget.
     * @class Social Media Widget.
     * @public.
     * @param {Element} HTMLElement|jQuery - The element of the Social Media Widget.
     * @param {Options} array - The options of the Social Media Widget.
     * @param {Core} Object - The Narrowcasting object for the Social Media Widget.
     */
    function SocialMediaWidget(element, options, core) {
        /**
         * The Narrowcasting object for the Social Media Widget.
         * @public.
         */
        this.core = core;

        /**
         * Current settings for the Social Media Widget.
         * @public.
         */
        this.settings = $.extend({}, SocialMediaWidget.Defaults, options);

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
         * All templates of the Social Media Widget.
         * @protected.
         */
        this.$templates = this.core.getTemplates(this.$element);

        /**
         * The data of the Social Media Widget.
         * @protected.
         */
        this.data = [];

        /**
         * The number of times that the data of the Social Media Widget is loaded.
         * @protected.
         */
        this.dataRefresh = 0;

        /**
         * The current item count.
         * @protected.
         */
        this.current = -1;

        /**
         * All items of the Social Media Widget.
         * @protected.
         */
        this.$items = [];

        this.initialize();
    }

    /**
     * Default options for the Social Media Widget.
     * @public.
     */
    SocialMediaWidget.Defaults = {
        'animation': 'fade',
        'animationTime': 1,

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
    SocialMediaWidget.Type = {
        'Event': 'event'
    };

    /**
     * Initializes the Social Media Widget.
     * @protected.
     */
    SocialMediaWidget.prototype.initialize = function() {
        console.log('SocialMediaWidget initialize');

        this.settings = $.extend({}, this.settings, this.core.loadCustomPluginSettings(this.$element));

        if (null === this.settings.feed) {
            this.core.setError('SocialMediaWidget feed is niet ingesteld.');
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
     * Loads the data for the Social Media Widget.
     * @protected.
     */
    SocialMediaWidget.prototype.loadData = function() {
        console.log('SocialMediaWidget loadData');

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

                               console.log('SocialMediaWidget loadData: (items: ' + result.responseJSON.items.length + ')');
                           } else {
                               this.core.setError('SocialMediaWidget feed kon niet gelezen worden (Formaat: ' + this.settings.feedType.toUpperCase() + ').');
                           }

                           break;
                       default:
                           this.core.setError('SocialMediaWidget feed kon niet gelezen worden omdat het formaat niet ondersteund word (Formaat: ' + this.settings.feedType.toUpperCase() + ').');

                           break;
                   }

                   if (0 == this.$items.length) {
                       this.nextItem();
                   }
               } else {
                   this.core.setError('SocialMediaWidget feed kon niet geladen worden (HTTP status: ' + result.status + ').');
               }

               this.dataRefresh++;
           }, this)
       });
    };

    /**
     * Returns all the URL parameters.
     * @public.
     */
    SocialMediaWidget.prototype.getUrlParameters = function() {
        var parameters = new Array('type=feed', 'data=true');

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
            return '?' + parameters.join('&');
        }

        return '';
    };

    /**
     * Gets the current item count.
     * @public.
     */
    SocialMediaWidget.prototype.getCurrent = function() {
        if (this.current + 1 < this.data.length) {
            this.current = this.current + 1;
        } else {
            this.current = 0;
        }

        return this.current;
    };

    /**
     * Gets a item template and initializes the item.
     * @public.
     * @param {Data} array - The item data.
     */
    SocialMediaWidget.prototype.getItem = function(data) {
        console.log('SocialMediaWidget getItem: (title: ' + data.name + ')');

        if ($item = this.core.getTemplate('item', this.$templates)) {
            $item.prependTo(this.core.getPlaceholder('social-media', this.$element));

            this.core.setPlaceholders($item, data);

            setTimeout($.proxy(function() {
                this.nextItem();
            }, this), 5000);

            return $item;
        }

        return null;
    };

    /**
     * Sets the item and animate current en next item.
     * @public.
     */
    SocialMediaWidget.prototype.nextItem = function() {
        var next = this.getCurrent();

        console.log('SocialMediaWidget nextItem: (next: ' + next + ')');

        if (this.data[next]) {
            var data = this.data[next];

            if ($item = this.getItem(data)) {
                $item.hide().fadeIn(this.settings.animationTime * 1000);

                if ($current = this.$items.shift()) {
                    $current.show().fadeOut(this.settings.animationTime * 1000, $.proxy(function() {
                        $current.remove();
                    }, this));
                }

                this.$items.push($item);
            } else {
                this.skipItem('Geen slide aanwezig');
            }
        } else {
            this.skipItem('Geen data aanwezig');
        }
    };

    /**
     * Skips the current item and animate next item.
     * @public.
     * @param {Message} string - The message of skip.
     */
    SocialMediaWidget.prototype.skipItem = function(message) {
        console.log('SocialMediaWidget skipItem: (message: ' + message + ')');

        this.nextItem();
    };

    /**
     * Registers an event or state.
     * @public.
     * @param {Object} object - The event or state to register.
     */
    SocialMediaWidget.prototype.register = function(object) {
        if (object.type === SocialMediaWidget.Type.Event) {
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
    SocialMediaWidget.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @protected.
     * @param {Array.<String>} events - The events to release.
     */
    SocialMediaWidget.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the Social Media Widget.
     * @public.
     */
    $.fn.SocialMediaWidget = function(core, option) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('narrowcasting.socialmediawidget');

            if (!data) {
                data = new SocialMediaWidget(this, typeof option == 'object' && option, core);

                $this.data('narrowcasting.socialmediawidget', data);

                $.each([

                       ], function(i, event) {
                    data.register({ type: SocialMediaWidget.Type.Event, name: event });
                    data.$element.on(event + '.narrowcasting.socialmediawidget.core', $.proxy(function(e) {
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
    $.fn.SocialMediaWidget.Constructor = SocialMediaWidget;

})(window.Zepto || window.jQuery, window, document);
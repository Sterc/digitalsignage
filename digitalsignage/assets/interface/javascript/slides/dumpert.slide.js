/* Javascript for DigitalSignage. (c) Sterc.nl. All rights reserved. */

/* ----------------------------------------------------------------------------------------- */
/* ----- Slide Dumpert --------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
    /**
     * Creates a Dumpert Slide.
     * @class SlideDumpert.
     * @param {HTMLElement} element - The element of the Dumpert Slide.
     * @param {Array} options - The options of the Dumpert Slide.
     * @param {Object} core - The DigitalSignage object for the Dumpert Slide.
     */
    function SlideDumpert(element, options, core) {
        /**
         * The DigitalSignage object for the Dumpert Slide.
         */
        this.core = core;

        /**
         * Currently suppressed events to prevent them from beeing retriggered.
         */
        this._supress = {};

        /**
         * Plugin element of the Dumpert slide.
         */
        this.$element = $(element);

        /**
         * Current settings for the Dumpert Slide.
         */
        this.settings = $.extend({}, SlideDumpert.Defaults, options);

        /**
         * All templates of the Dumpert Slide.
         */
        this.$templates = this.core.getTemplates(this.$element);

        /**
         * All top5 of the Dumpert Slide.
         */
        this.$top5Items = [];

        this.initialize();
    }

    /**
     * Default options for the Dumpert Slide.
     * @public.
     */
    SlideDumpert.Defaults = {
        time                : 15,

        top5                : 'http://dumpert.nl/mobile_api/json/top5/dag/{date}',
        top5Type            : 'JSON',

        randomVideo         : 'http://dumpert.nl/mobile_api/json/latest/{page}',
        randomVideoType     : 'JSON',
        randomVideoDuration : 20,

        limit               : 5
    };

    /**
     * Enumeration for types.
     */
    SlideDumpert.Type = {
        Event : 'event'
    };

    /**
     * Initializes the Dumpert Slide.
     */
    SlideDumpert.prototype.initialize = function() {
        this.core.setLog('[' + this.constructor.name + '] initialize');

        this.core.setData('slide-' + this.settings.id, null, -1);

        this.core.setPlaceholders(this.$element, this.settings);

        if (this.settings.top5 === null) {
            this.core.setError('[' + this.constructor.name + '] initialize: ' + this.core.getLexicon('slidedumpert_error_top5'));
        } else {
            this.loadTop5();
        }

        if (this.settings.randomVideo === null) {
            this.core.setError('[' + this.constructor.name + '] initialize: ' + this.core.getLexicon('slidedumpert_error_randomvideo'));
        } else {
            this.loadRandomVideo();
        }

        this.core.setTimer(this.settings.time);
    };

    /**
     * Loads the top5 data for the Dumpert Slide.
     */
    SlideDumpert.prototype.loadTop5 = function() {
        this.core.setLog('[' + this.constructor.name + '] loadTop5');

        $.ajax({
            url         : this.core.getAjaxUrl(this.settings.top5.replace('{date}', '2019-04-25')),
            dataType    : this.settings.top5Type.toUpperCase(),
            cache       : false,
            complete    : $.proxy(function(result) {
                if (parseInt(result.status) === 200) {
                    switch (this.settings.top5Type.toUpperCase()) {
                        case 'JSON':
                            if (result.responseJSON) {
                                if (result.responseJSON.items.length > 0) {
                                    var data = [];

                                    for (var i = 0; i < result.responseJSON.items.length; i++) {
                                        data.push(result.responseJSON.items[i]);
                                    }

                                    this.core.setData('slide-' + this.settings.id, data, -1);

                                    this.core.setLog('[' + this.constructor.name + '] loadTop5: ' + this.core.getLexicon('slidedumpert_top5_loaded', {
                                        items : result.responseJSON.items.length
                                    }));
                                } else {
                                    this.core.setLog('[' + this.constructor.name + '] loadTop5: ' + this.core.getLexicon('slidedumpert_error_top5_empty', {
                                        items : result.responseJSON.items.length
                                    }));
                                }
                            } else {
                                this.core.setError('[' + this.constructor.name + '] loadTop5: ' + this.core.getLexicon('slidedumpert_error_top5_format', {
                                    format : this.settings.top5Type.toUpperCase()
                                }));
                            }

                            break;
                        default:
                            this.core.setError('[' + this.constructor.name + '] loadTop5: ' + this.core.getLexicon('slidedumpert_error_top5_format', {
                                format : this.settings.top5Type.toUpperCase()
                            }));

                            break;
                    }
                } else {
                    this.core.setError('[' + this.constructor.name + '] loadTop5: ' + this.core.getLexicon('slidedumpert_error_top5_http', {
                        status : result.status
                    }));
                }

                this.startTop5();
            }, this)
        });
    };

    /**
     * Sets the first item when the feed is loaded.
     */
    SlideDumpert.prototype.startTop5 = function() {
        if (this.core.getData('slide-' + this.settings.id, 'length') > 0) {
            this.nextTop5();
        }
    },

    /**
     * Gets a top5 template and initializes the top5.
     * @param {Array} data - The top5 data.
     */
    SlideDumpert.prototype.getTop5 = function(data) {
        this.core.setLog('[' + this.constructor.name + '] getTop5: (date: ' + data.title + ')');

        if ($top5 = this.core.getTemplate('top5', this.$templates)) {
            $top5.appendTo(this.core.getPlaceholder('top5s', this.$element));

            this.core.setPlaceholders($top5, data);

            return $top5;
        }

        return null;
    };

    /**
     * Sets the top5 and animate current en next top5.
     */
    SlideDumpert.prototype.nextTop5 = function() {
        var next = this.core.getCurrentDataIndex('slide-' + this.settings.id, 'next', this.settings.limit);

        this.core.setLog('[' + this.constructor.name + '] nextTop5: (next: ' + next + ')');

        if (next !== null) {
            if (this.settings.limit > this.$top5Items.length) {
                if (null !== (data = this.core.getData('slide-' + this.settings.id, next))) {
                    var data = $.extend({}, data, {
                        idx     : next + 1,
                        rank    : ('0' + (next + 1)).slice(-2)
                    });

                    if ($top5 = this.getTop5(data)) {
                        this.$top5Items.push($top5);

                        this.nextTop5();
                    } else {
                        this.core.setLog('[' + this.constructor.name + '] nextTop5: ' + this.core.getLexicon('slidedumpert_error_no_top5_item'));

                        this.nextTop5();
                    }
                } else {
                    this.core.setLog('[' + this.constructor.name + '] nextTop5: ' + this.core.getLexicon('slidedumpert_error_no_top5_item_data'));

                    this.nextTop5();
                }
            }
        } else {
            this.core.setError('[' + this.constructor.name + '] nextTop5: ' + this.core.getLexicon('slidedumpert_error_no_data'));
        }
    };

    /**
     * Loads the random video data for the Dumpert Slide.
     */
    SlideDumpert.prototype.loadRandomVideo = function() {
        this.core.setLog('[' + this.constructor.name + '] loadRandomVideo');

        $.ajax({
            url         : this.core.getAjaxUrl(this.settings.randomVideo.replace('{page}', Math.floor(Math.random() * 100) + 1)),
            dataType    : this.settings.randomVideoType.toUpperCase(),
            cache       : false,
            complete    : $.proxy(function(result) {
                if (parseInt(result.status) === 200) {
                    switch (this.settings.randomVideoType.toUpperCase()) {
                        case 'JSON':
                            if (result.responseJSON) {
                                if (result.responseJSON.items.length > 0) {
                                    var data = null;

                                    for (var i = 0; i < result.responseJSON.items.length; i++) {
                                        if (data === null) {
                                            var item = result.responseJSON.items[i];

                                            for (var ii = 0; ii < item.media.length; ii++) {
                                                var media = item.media[ii];

                                                if (media.mediatype === 'VIDEO' && parseInt(media.duration) <= parseInt(this.settings.randomVideoDuration)) {
                                                    for (var iii = 0; iii < media.variants.length; iii++) {
                                                        if (media.variants[iii].version === '720p') {
                                                            data = {
                                                                title       : item.title,
                                                                description : item.description,
                                                                date        : item.date,
                                                                stats       : item.stats,
                                                                media       : media.variants[iii]
                                                            };
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    if (data !== null) {
                                        this.getRandomVideo(data);
                                    }

                                    this.core.setLog('[' + this.constructor.name + '] loadRandomVideo: ' + this.core.getLexicon('slidedumpert_randomvideo_loaded', {
                                        items : result.responseJSON.items.length
                                    }));
                                } else {
                                    this.core.setLog('[' + this.constructor.name + '] loadRandomVideo: ' + this.core.getLexicon('slidedumpert_error_randomvideo_empty', {
                                        items : result.responseJSON.items.length
                                    }));
                                }
                            } else {
                                this.core.setError('[' + this.constructor.name + '] loadRandomVideo: ' + this.core.getLexicon('slidedumpert_error_randomvideo_format', {
                                    format : this.settings.randomVideoType.toUpperCase()
                                }));
                            }

                            break;
                        default:
                            this.core.setError('[' + this.constructor.name + '] loadRandomVideo: ' + this.core.getLexicon('slidedumpert_error_randomvideo_format', {
                                format : this.settings.randomVideoType.toUpperCase()
                            }));

                            break;
                    }
                } else {
                    this.core.setError('[' + this.constructor.name + '] loadFeed: ' + this.core.getLexicon('slidedumpert_error_randomvideo_http', {
                        status : result.status
                    }));
                }
            }, this)
        });
    };

    /**
     * Gets a random video template and initializes the random video.
     * @param {Array} data - The top5 data.
     */
    SlideDumpert.prototype.getRandomVideo = function(data) {
        this.core.setLog('[' + this.constructor.name + '] getRandoVideo: (date: ' + data.title + ')');

        if ($video = this.core.getTemplate('video', this.$templates)) {
            $video.appendTo(this.core.getPlaceholder('videos', this.$element));

            this.core.setPlaceholders($video, data);
        }
    };

    /**
     * Registers an event or state.
     * @param {Object} object - The event or state to register.
     */
    SlideDumpert.prototype.register = function(object) {
        if (object.type === SlideDumpert.Type.Event) {
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
    SlideDumpert.prototype.suppress = function(events) {
        $.each(events, $.proxy(function(index, event) {
            this._supress[event] = true;
        }, this));
    };

    /**
     * Releases suppressed events.
     * @param {Array} events - The events to release.
     */
    SlideDumpert.prototype.release = function(events) {
        $.each(events, $.proxy(function(index, event) {
            delete this._supress[event];
        }, this));
    };

    /**
     * The jQuery Plugin for the Dumpert Slide.
     */
    $.fn.SlideDumpert = function(option, core) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function() {
            var $this = $(this),
                data = $this.data('digitalsignage.slidedumpert');

            if (!data) {
                data = new SlideDumpert(this, typeof option === 'object' && option, core);

                $this.data('digitalsignage.slidedumpert', data);

                $.each([], function(i, event) {
                    data.register({type: SlideDumpert.Type.Event, name: event});

                    data.$element.on(event + '.digitalsignage.slidedumpert.core', $.proxy(function(e) {
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
     * The constructor for the Dumpert Slide.
     */
    $.fn.SlideDumpert.Constructor = SlideDumpert;

    /**
     * The lexicons for the Dumpert Slide.
     */
    $.extend($.fn.DigitalSignage.lexicons, {
        slidedumpert_error_top5                 : 'Dumpert top5 kon niet geladen worden omdat de top5 feed niet gedefinieerd is.',
        slidedumpert_error_top5_http            : 'Dumpert top5 feed kon niet geladen worden (HTTP status: %status%).',
        slidedumpert_error_top5_format          : 'Dumpert top5 feed kon niet geladen worden omdat het formaat niet ondersteund word (Formaat: %format%).',
        slidedumpert_error_top5_empty           : 'Dumpert top5 feed kon niet geladen worden omdat het geen video\'s bevat.',
        slidedumpert_top5_loaded                : 'Dumpert top5 feed geladen met %items% video\'s.',

        slidedumpert_error_top5_data            : 'Geen data beschikbaar.',
        slidedumpert_error_no_top5_item         : 'Geen item beschikbaar.',
        slidedumpert_error_no_top5_item_data    : 'Geen item data beschikbaar.',

        slidedumpert_error_randomvideo          : 'Dumpert random video kon niet geladen worden omdat de random visdeo feed niet gedefinieerd is.',
        slidedumpert_error_randomvideo_http     : 'Dumpert random video feed kon niet geladen worden (HTTP status: %status%).',
        slidedumpert_error_randomvideo_format   : 'Dumpert random video feed kon niet geladen worden omdat het formaat niet ondersteund word (Formaat: %format%).',
        slidedumpert_error_randomvideo_empty    : 'Dumpert random video feed kon niet geladen worden omdat het geen video\'s bevat.',
        slidedumpert_randomvideo_loaded         : 'Dumpert random video feed geladen met %items% video\'s.',
    });
})(window.Zepto || window.jQuery, window, document);
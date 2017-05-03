/*global jQuery */
/*jslint devel: true, browser: true */
(
    function ($) {
        'use strict';

        var Site = {

            $searchContainer   : $('.search-container'),
            $window            : $(window),
            $btnTop            : $('.btn-top'),
            $mainNavHeader     : $('.main-navigation__header'),
            $lightBox          : $('.lightbox'),
            $socialShare       : $('.social-share'),
            $customerLogoSlider: $('.customer-logo__slider'),
            $slider            : $('.slider'),
            $testimonial       : $('.testimonial'),
            $projectSlider     : $('.project--slider'),
            $projectNav        : $('.project--navigation'),

            /**
             * Function to check if search field is not empty
             */
            checkSearchField: function () {
                var self = this,
                    seaObj = self.$searchContainer,
                    input = $('input.search-form-input', seaObj);

                if ('' === input.val() || input.val() === input.attr('placeholder')) {
                    alert($('form', seaObj).attr('data-alert'));
                    return false;
                }
            },

            /**
             * Toggle language selecter
             *
             * @param element
             */
            toggleLanguage: function (element) {
                var language = $(element.currentTarget);
                language.toggleClass('open');
            },

            /**
             * Show search field
             * With effect
             *
             * @param element
             */
            showSearchField: function (element) {
                element.preventDefault();
                var body = $('body');
                $(body).removeClass('searchclosed');
                $(body).addClass('searchopen');
            },

            /**
             * Hide search field
             * With effect
             *
             * @param element
             */
            hideSearchField: function (element) {
                element.preventDefault();
                var body = $('body');
                $(body).removeClass('searchopen');
            },

            /**
             * Toggle filter
             *
             * @param element
             */
            toggleFilter: function (element) {
                var filter = $(element.currentTarget);
                filter.toggleClass('open');
                filter.next().stop().slideToggle();
            },

            /**
             * Toggle submenu touch devices
             *
             * @param element
             */
            showSubmenu: function (element) {
                element.preventDefault();
                var subMenuItem = $(element.currentTarget);
                subMenuItem.next().stop().slideToggle();
            },

            /**
             * Toggle menu
             *
             * @param element
             */
            toggleMenu: function (element) {
                var currentTarget = $(element.currentTarget),
                    parentTarget = currentTarget.closest('.navigation');
                if (parentTarget.hasClass('on')) {
                    parentTarget.removeClass('on');

                    if (parentTarget.hasClass('navigation__fade-fullscreen') ||
                            parentTarget.hasClass('navigation__corner-fullscreen') ||
                            parentTarget.hasClass('navigation__slide-right')) {
                        $('body').removeClass('modal-open');
                    }

                } else {
                    parentTarget.addClass('on');

                    if (parentTarget.hasClass('navigation__fade-fullscreen') ||
                            parentTarget.hasClass('navigation__corner-fullscreen') ||
                            parentTarget.hasClass('navigation__slide-right')) {
                        $('body').addClass('modal-open');
                    }

                }
            },

            /**
             * Toggle submenu
             *
             * @param element
             */
            toggleSubmenu: function (element) {
                element.preventDefault();
                $('.mobile-navigation__header .submenu ul').slideToggle();
            },

            /**
             * Toggle searchbox
             */
            toggleSearchbox: function () {
                var self = this;
                self.$searchContainer.stop().slideToggle();
            },

            /**
             * Slide to anchor
             *
             * @param element
             */
            slideToAnchor: function (element) {
                // target element id
                // target element
                // top position relative to the document
                var id = $(element).data('link'),
                    $id = $(id),
                    pos = $(id).offset().top;

                if ($id.length === 0) {
                    return;
                }

                // prevent standard hash navigation (avoid blinking in IE)
                element.preventDefault();

                // animated top scrolling
                $('body, html').animate({scrollTop: pos});
            },

            /**
             * Function show more text, overview blocks
             *
             * @param element
             */
            showMoreText: function (element) {
                $(element).parent().toggleClass('active');
            },

            /**
             * Function go to top of page
             */
            goToTopOfPage: function () {
                $('html,body').animate({
                    scrollTop: 0
                });
            },

            /**
             * Function call on page load
             */
            onPageLoad: function () {
                var self = this,
                    flag = false,
                    hash = '',
                    duration = 300;

                if (self.$lightBox.length) {
                    self.$lightBox.nivoLightbox({
                        afterHideLightbox: function () {
                            $('.nivo-lightbox-content').empty();
                        }
                    });
                }

                if (self.$socialShare.length) {
                    $('.lightbox.hash').each(function () {
                        $(this).attr('href', '#' + $(this).attr('href').split('#')[1]);
                    });

                    if (window.location.hash) {
                        hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character

                        if (hash === 'mail-popup') {
                            if ($(".has-error").parents("#mail-popup").length === 1) {
                                $('#gtm-share-mail').trigger('click');
                            }
                        }
                    }
                }

                if (self.$customerLogoSlider.length) {
                    self.$customerLogoSlider.owlCarousel(
                        {
                            margin      : 10,
                            items       : 10,
                            mouseDrag   : false,
                            stagePadding: 50,
                            autoWidth   : true,
                            nav         : false
                        }
                    );
                }

                if (self.$slider.length) {
                    self.$slider.owlCarousel(
                        {
                            items             : 1,
                            animateOut        : 'fadeOut',
                            mouseDrag         : false,
                            autoplay          : true,
                            autoplayTimeout   : 3500,
                            autoplayHoverPause: true,
                            loop              : true,
                            nav               : false
                        }
                    );
                }

                if (self.$testimonial.length) {
                    self.$testimonial.owlCarousel(
                        {
                            items     : 1,
                            animateOut: 'fadeOut',
                            loop      : true,
                            nav       : false
                        }
                    );
                }

                if (self.$projectSlider.length && self.$projectNav.length) {

                    self.$projectSlider
                        .owlCarousel({
                            items: 1,
                            loop : false,
                            nav  : true,
                            dots : true
                        }).on('changed.owl.carousel', function (e) {
                            if (!flag) {
                                self.$projectNav.trigger('to.owl.carousel', [e.item.index, duration, true]);
                            }
                        });

                    self.$projectNav.owlCarousel({
                        margin      : 10,
                        items       : 5,
                        stagePadding: 50,
                        autoWidth   : true,
                        loop        : false,
                        nav         : true
                    }).on('click', '.owl-item', function () {
                        self.$projectSlider.trigger('to.owl.carousel', [$(this).index(), duration, true]);
                    }).on('changed.owl.carousel', function (e) {
                        if (!flag) {
                            self.$projectSlider.trigger('to.owl.carousel', [e.item.index, duration, true]);
                        }
                    });
                }

                $(window).scroll(
                    function () {
                        if ($(window).scrollTop > $(window).height()) {
                            self.$btnTop.fadeIn();
                        } else {
                            self.$btnTop.fadeOut();
                        }
                    }
                );

            }
        };

        $(document).on({
            click: $.proxy(Site, 'checkSearchField')
        }, '.search-container input[type="submit"]');

        $(document).on({
            click: $.proxy(Site, 'slideToAnchor')
        }, 'span[data-link^="#"]');

        $(document).on({
            click: $.proxy(Site, 'toggleLanguage')
        }, '.language');

        $(document).on({
            click: $.proxy(Site, 'toggleFilter')
        }, '.filter');

        $(document).on({
            click: $.proxy(Site, 'toggleMenu')
        }, '.nav-toggle');

        $(document).on({
            touchstart: $.proxy(Site, 'showSubmenu')
        }, '.has-submenu .icon');

        $(document).on({
            click: $.proxy(Site, 'toggleSearchbox')
        }, '.search-toggle');

        $(document).on({
            click: $.proxy(Site, 'showMoreText')
        }, '.item--more');

        $(document).on({
            click: $.proxy(Site, 'goToTopOfPage')
        }, '.go-to-top');

        $(document).on({
            click: $.proxy(Site, 'showSearchField')
        }, '.search-nav');

        $(document).on({
            click: $.proxy(Site, 'showSearchField')
        }, '.search-trigger');

        $(document).on({
            click: $.proxy(Site, 'hideSearchField')
        }, '.search-close');

        window.onload = function () {
            Site.onPageLoad();
        };
    }(jQuery)
);
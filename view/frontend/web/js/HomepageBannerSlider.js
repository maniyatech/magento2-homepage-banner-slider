/**
 * ManiyaTech
 *
 * @author        Milan Maniya
 * @package       ManiyaTech_HomepageBannerSlider
 */

require(
    [
    "jquery",
    "homepagebannerslider/owl.carousel"
    ], function ($) {
        "use strict";

        const {
            bannerSlider_items: items,
            bannerSlider_infinite: loop,
            bannerSlider_showDots: dots,
            bannerSlider_autoplay: autoplay,
            bannerSlider_showArrow: nav,
            bannerSlider_itemMobile: mobileItems,
            bannerSlider_itemDesktop: desktopItems,
            bannerSlider_autoplayTimeout: autoplayTimeout
        } = window.bannerSliderData || {};

        /**
         * Toggle between mobile and desktop banners.
         */
        function toggleBannerView()
        {
            if (window.innerWidth < 768) {
                $(".desktop-section-bannerslider").remove();
                $(".mobile-section-bannerslider").show();
            } else {
                $(".mobile-section-bannerslider").remove();
                $(".desktop-section-bannerslider").show();
            }
        }

        /**
         * Initialize Owl Carousel on the banner slider.
         */
        function initializeBannerSlider()
        {
            if (items > 1) {
                const $slider = $(".main-home-slider");

                if ($slider.length && typeof $slider.owlCarousel === "function") {
                    $slider.css("display", "block").owlCarousel(
                        {
                            loop: loop,
                            dots: dots,
                            nav: nav,
                            autoplay: autoplay,
                            autoplayTimeout: autoplayTimeout,
                            responsiveClass: true,
                            responsive: {
                                0: {
                                    nav: false,
                                    items: mobileItems
                                },
                                768: {
                                    nav: nav,
                                    items: desktopItems
                                }
                            }
                        }
                    );

                    // Re-trigger layout to help LCP in some browsers
                    window.dispatchEvent(new Event("resize"));
                }
            }
        }

        /**
         * Debounce resize event
         */
        function debounce(func, wait)
        {
            let timeout;
            return function () {
                clearTimeout(timeout);
                timeout = setTimeout(func, wait);
            };
        }

        $(document).ready(
            function () {
                toggleBannerView();
                initializeBannerSlider();

                $(window).on("resize", debounce(toggleBannerView, 200));
            }
        );
    }
);
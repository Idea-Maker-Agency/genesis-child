/**
 * Genesis Sample entry point.
 *
 * @package GenesisSample\JS
 * @author  StudioPress
 * @license GPL-2.0+
 */

var GenesisSample = (function ($) {
	"use strict";

	const siteHeader = $(".site-header");
	const siteInner = $(".site-inner");
	const footerWidgets = $(".footer-widgets");
	const siteFooter = $(".site-footer");

	/**
	 * Adjust site inner margin top to compensate for sticky header height.
	 *
	 * @since 2.6.0
	 */
	const compensateStickyHeader = function () {
		var siteInnerMarginTop = 0;

		if (siteHeader.css("position") === "fixed")
			siteInnerMarginTop = siteHeader.outerHeight();

		siteInner.css({ marginTop: siteInnerMarginTop });
	};

	/**
	 * Adjust site inner margin bottom to compensate for sticky footer height.
	 *
	 * @since 	2.6.0
	 */
	const compensateStickyFooter = function () {
		var siteInnerMarginBottom = 0,
			docHeight = siteHeader.outerHeight() + siteInner.outerHeight(),
			docHeight2 =
				$(window).height() -
				(footerWidgets.outerHeight() + siteFooter.outerHeight());

		if (docHeight <= docHeight2) {
			if (0 < footerWidgets.length) {
				footerWidgets.css({ position: "fixed" });

				if (0 < siteFooter.length) {
					siteFooter.css({ position: "fixed" });
					footerWidgets.css({
						marginBottom: siteFooter.outerHeight(),
					});
				}

				siteInnerMarginBottom = footerWidgets.outerHeight(true);
			}
		}

		siteInner.css({ marginBottom: siteInnerMarginBottom });
	};
	/**
	 * Initialize Genesis Sample.
	 *
	 * Internal functions to execute on document load can be called here.
	 *
	 * @since 2.6.0
	 */
	const init = function () {
		// Run on first load.
		compensateStickyHeader();
		compensateStickyFooter();

		// Run after window resize.
		$(window).resize(function () {
			compensateStickyHeader();
			compensateStickyFooter();
		});

		$(window).scroll(function () {
			if ($(this).scrollTop() > 1) siteHeader.addClass("sticky");
			else siteHeader.removeClass("sticky");
		});

		// Run after the Customizer updates.
		// 1.5s delay is to allow logo area reflow.
		if (typeof wp.customize != "undefined") {
			wp.customize.bind("change", function (setting) {
				setTimeout(function () {
					moveContentBelowFixedHeader();
				}, 1500);
			});
		}
	};

	// Expose the init function only.
	return {
		init: init,
	};
})(jQuery);

jQuery(window).on("load", GenesisSample.init);

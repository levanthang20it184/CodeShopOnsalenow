/**
 * Label.js
 *
 * Copyright, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

/**
 * This class creates a label element. A label is a simple text control
 * that can be bound to other controls.
 *
 * @-x-less Label.less
 * @class tinymce.ui.Label
 * @extends tinymce.ui.Widget
 */
define("tinymce/ui/Label", [
    "tinymce/ui/Widget"
], function (Widget) {
    "use strict";

    return Widget.extend({
        /**
         * Constructs a instance with the specified settings.
         *
         * @constructor
         * @param {Object} settings Name/value object with settings.
         * @param {Boolean} multiline Multiline label.
         */
        init: function (settings) {
            var self = this;

            self._super(settings);
            self.addClass('widget');
            self.addClass('label');
            self.canFocus = false;

            if (settings.multiline) {
                self.addClass('autoscroll');
            }

            if (settings.strong) {
                self.addClass('strong');
            }
        },

        /**
         * Initializes the current controls layout rect.
         * This will be executed by the layout managers to determine the
         * default minWidth/minHeight etc.
         *
         * @method initLayoutRect
         * @return {Object} Layout rect instance.
         */
        initLayoutRect: function () {
            var self = this, layoutRect = self._super();

            if (self.settings.multiline) {
                // Check if the text fits within maxW if not then try word wrapping it
                if (self.getEl().offsetWidth > layoutRect.maxW) {
                    layoutRect.minW = layoutRect.maxW;
                    self.addClass('multiline');
                }

                self.getEl().style.width = layoutRect.minW + 'px';
                layoutRect.startMinH = layoutRect.h = layoutRect.minH = Math.min(layoutRect.maxH, self.getEl().offsetHeight);
            }

            return layoutRect;
        },

        /**
         * Sets/gets the disabled state on the control.
         *
         * @method disabled
         * @param {Boolean} state Value to set to control.
         * @return {Boolean/tinymce.ui.Label} Current control on a set operation or current state on a get.
         */
        disabled: function (state) {
            var self = this, undef;

            if (state !== undef) {
                self.toggleClass('label-disabled', state);

                if (self._rendered) {
                    self.getEl()[0].className = self.classes();
                }
            }

            return self._super(state);
        },

        /**
         * Repaints the control after a layout operation.
         *
         * @method repaint
         */
        repaint: function () {
            var self = this;

            if (!self.settings.multiline) {
                self.getEl().style.lineHeight = self.layoutRect().h + 'px';
            }

            return self._super();
        },

        /**
         * Sets/gets the current label text.
         *
         * @method text
         * @param {String} [text] New label text.
         * @return {String|tinymce.ui.Label} Current text or current label instance.
         */
        text: function (text) {
            var self = this;

            if (self._rendered && text) {
                self.getEl().innerHTML = self.encode(text);
            }

            return self._super(text);
        },

        /**
         * Renders the control as a HTML string.
         *
         * @method renderHtml
         * @return {String} HTML representing the control.
         */
        renderHtml: function () {
            var self = this, forId = self.settings.forId;

            return (
                '<label id="' + self._id + '" class="' + self.classes() + '"' + (forId ? ' for="' + forId : '') + '">' +
                self.encode(self._text) +
                '</label>'
            );
        }
    });
});
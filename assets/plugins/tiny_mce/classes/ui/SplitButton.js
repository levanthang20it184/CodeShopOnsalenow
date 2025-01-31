/**
 * SplitButton.js
 *
 * Copyright, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

/**
 * Creates a split button.
 *
 * @-x-less SplitButton.less
 * @class tinymce.ui.SplitButton
 * @extends tinymce.ui.Button
 */
define("tinymce/ui/SplitButton", [
    "tinymce/ui/MenuButton",
    "tinymce/dom/DOMUtils"
], function (MenuButton, DomUtils) {
    var DOM = DomUtils.DOM;

    return MenuButton.extend({
        Defaults: {
            classes: "widget btn splitbtn",
            role: "splitbutton"
        },

        /**
         * Repaints the control after a layout operation.
         *
         * @method repaint
         */
        repaint: function () {
            var self = this, elm = self.getEl(), rect = self.layoutRect(), mainButtonElm, menuButtonElm, btnStyle;

            self._super();

            mainButtonElm = elm.firstChild;
            menuButtonElm = elm.lastChild;

            DOM.css(mainButtonElm, {
                width: rect.w - menuButtonElm.offsetWidth,
                height: rect.h - 2
            });

            DOM.css(menuButtonElm, {
                height: rect.h - 2
            });

            btnStyle = mainButtonElm.firstChild.style;
            btnStyle.width = btnStyle.height = "100%";

            btnStyle = menuButtonElm.firstChild.style;
            btnStyle.width = btnStyle.height = "100%";

            return self;
        },

        /**
         * Sets the active menu state.
         *
         * @private
         */
        activeMenu: function (state) {
            var self = this;

            DOM.toggleClass(self.getEl().lastChild, self.classPrefix + 'active', state);
        },

        /**
         * Renders the control as a HTML string.
         *
         * @method renderHtml
         * @return {String} HTML representing the control.
         */
        renderHtml: function () {
            var self = this, id = self._id, prefix = self.classPrefix;
            var icon = self.settings.icon ? prefix + 'ico ' + prefix + 'i-' + self.settings.icon : '';

            return (
                '<div id="' + id + '" class="' + self.classes() + '">' +
                '<button type="button" hidefocus tabindex="-1">' +
                (icon ? '<i class="' + icon + '"></i>' : '') +
                (self._text ? (icon ? ' ' : '') + self._text : '') +
                '</button>' +
                '<button type="button" class="' + prefix + 'open" hidefocus tabindex="-1">' +
                //(icon ? '<i class="' + icon + '"></i>' : '') +
                (self._menuBtnText ? (icon ? ' ' : '') + self._menuBtnText : '') +
                ' <i class="' + prefix + 'caret"></i>' +
                '</button>' +
                '</div>'
            );
        },

        /**
         * Called after the control has been rendered.
         *
         * @method postRender
         */
        postRender: function () {
            var self = this, onClickHandler = self.settings.onclick;

            self.on('click', function (e) {
                if (e.control == this && !DOM.getParent(e.target, '.' + this.classPrefix + 'open')) {
                    e.stopImmediatePropagation();
                    onClickHandler.call(this, e);
                }
            });

            delete self.settings.onclick;

            return self._super();
        }
    });
});
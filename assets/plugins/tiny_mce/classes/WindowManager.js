/**
 * WindowManager.js
 *
 * Copyright, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

/**
 * This class handles the creation of native windows and dialogs. This class can be extended to provide for example inline dialogs.
 *
 * @class tinymce.WindowManager
 * @example
 * // Opens a new dialog with the file.htm file and the size 320x240
 * // It also adds a custom parameter this can be retrieved by using tinyMCEPopup.getWindowArg inside the dialog.
 * tinymce.activeEditor.windowManager.open({
 *    url: 'file.htm',
 *    width: 320,
 *    height: 240
 * }, {
 *    custom_param: 1
 * });
 *
 * // Displays an alert box using the active editors window manager instance
 * tinymce.activeEditor.windowManager.alert('Hello world!');
 *
 * // Displays an confirm box and an alert message will be displayed depending on what you choose in the confirm
 * tinymce.activeEditor.windowManager.confirm("Do you want to do something", function(s) {
 *    if (s)
 *       tinymce.activeEditor.windowManager.alert("Ok");
 *    else
 *       tinymce.activeEditor.windowManager.alert("Cancel");
 * });
 */
define("tinymce/WindowManager", [
    "tinymce/ui/Window",
    "tinymce/ui/MessageBox"
], function (Window, MessageBox) {
    return function (editor) {
        var self = this, windows = [];

        self.windows = windows;

        /**
         * Opens a new window.
         *
         * @method open
         * @param {Object} args Optional name/value settings collection contains things like width/height/url etc.
         * @option {String} title Window title.
         * @option {String} file URL of the file to open in the window.
         * @option {Number} width Width in pixels.
         * @option {Number} height Height in pixels.
         * @option {Boolean} resizable Specifies whether the popup window is resizable or not.
         * @option {Boolean} maximizable Specifies whether the popup window has a "maximize" button and can get maximized or not.
         * @option {String/bool} scrollbars Specifies whether the popup window can have scrollbars if required (i.e. content
         * larger than the popup size specified).
         */
        self.open = function (args, params) {
            var win;

            // Handle URL
            args.url = args.url || args.file; // Legacy
            if (args.url) {
                args.width = parseInt(args.width || 320, 10);
                args.height = parseInt(args.height || 240, 10);
            }

            // Handle body
            if (args.body) {
                args.items = {
                    defaults: args.defaults,
                    type: args.bodyType || 'form',
                    items: args.body
                };
            }

            if (!args.url && !args.buttons) {
                args.buttons = [
                    {
                        text: 'Ok', subtype: 'primary', onclick: function () {
                            win.find('form')[0].submit();
                            win.close();
                        }
                    },

                    {
                        text: 'Cancel', onclick: function () {
                            win.close();
                        }
                    }
                ];
            }

            win = new Window(args);
            windows.push(win);

            win.on('close', function () {
                var i = windows.length;

                while (i--) {
                    if (windows[i] === win) {
                        windows.splice(i, 1);
                    }
                }

                editor.focus();
            });

            // Handle data
            if (args.data) {
                win.on('postRender', function () {
                    this.find('*').each(function (ctrl) {
                        var name = ctrl.name();

                        if (name in args.data) {
                            ctrl.value(args.data[name]);
                        }
                    });
                });
            }

            // store parameters
            win.params = params || {};

            // Takes a snapshot in the FocusManager of the selection before focus is lost to dialog
            editor.nodeChanged();

            return win.renderTo(document.body).reflow();
        };

        /**
         * Creates a alert dialog. Please don't use the blocking behavior of this
         * native version use the callback method instead then it can be extended.
         *
         * @method alert
         * @param {String} message Text to display in the new alert dialog.
         * @param {function} callback Callback function to be executed after the user has selected ok.
         * @param {Object} scope Optional scope to execute the callback in.
         * @example
         * // Displays an alert box using the active editors window manager instance
         * tinymce.activeEditor.windowManager.alert('Hello world!');
         */
        self.alert = function (message, callback, scope) {
            MessageBox.alert(message, function () {
                callback.call(scope || this);
            });
        };

        /**
         * Creates a confirm dialog. Please don't use the blocking behavior of this
         * native version use the callback method instead then it can be extended.
         *
         * @method confirm
         * @param {String} messageText to display in the new confirm dialog.
         * @param {function} callback Callback function to be executed after the user has selected ok or cancel.
         * @param {Object} scope Optional scope to execute the callback in.
         * @example
         * // Displays an confirm box and an alert message will be displayed depending on what you choose in the confirm
         * tinymce.activeEditor.windowManager.confirm("Do you want to do something", function(s) {
         *    if (s)
         *       tinymce.activeEditor.windowManager.alert("Ok");
         *    else
         *       tinymce.activeEditor.windowManager.alert("Cancel");
         * });
         */
        self.confirm = function (message, callback, scope) {
            MessageBox.confirm(message, function (state) {
                callback.call(scope || this, state);
            });
        };

        /**
         * Closes the top most window.
         *
         * @method close
         */
        self.close = function () {
            if (windows.length) {
                windows[windows.length - 1].close();
            }
        };

        /**
         * Returns the params of the last window open call. This can be used in iframe based
         * dialog to get params passed from the tinymce plugin.
         *
         * @example
         * var dialogArguments = top.tinymce.activeEditor.windowManager.getParams();
         *
         * @method getParams
         * @return {Object} Name/value object with parameters passed from windowManager.open call.
         */
        self.getParams = function () {
            if (windows.length) {
                return windows[windows.length - 1].params;
            }

            return null;
        };

        /**
         * Sets the params of the last opened window.
         *
         * @method setParams
         * @param {Object} params Params object to set for the last opened window.
         */
        self.setParams = function (params) {
            if (windows.length) {
                windows[windows.length - 1].params = params;
            }
        };
    };
});

(function () {
    var i, nl = document.getElementsByTagName('script'), base, src, p, li, query = '', it, scripts = [];

    if (window.tinyMCEPreInit) {
        base = tinyMCEPreInit.base;
        query = tinyMCEPreInit.query || '';
    } else {
        for (i = 0; i < nl.length; i++) {
            src = nl[i].src;

            if (src && src.indexOf("tiny_mce_dev.js") != -1) {
                base = src.substring(0, src.lastIndexOf('/'));

                if ((p = src.indexOf('?')) != -1)
                    query = src.substring(p + 1);
            }
        }
    }

    // Parse query string
    li = query.split('&');
    query = {};
    for (i = 0; i < li.length; i++) {
        it = li[i].split('=');
        query[unescape(it[0])] = unescape(it[1]);
    }

    nl = null; // IE leak fix

    function include(u) {
        scripts.push(base + '/classes/' + u);
    }

    function load() {
        var i, html = '';

        for (i = 0; i < scripts.length; i++)
            html += '<script type="text/javascript" src="' + scripts[i] + '"></script>\n';

        document.write(html);
    }

    // Firebug
    if (query.debug && !("console" in window)) {
        include('firebug/firebug-lite.js');
    }

    // Load coverage version
    if (query.coverage) {
        base = base + '/../../tmp/jscoverage';
        window.tinyMCEPreInit = {base: base, suffix: '_src', query: ''};
    }

    // Core ns
    include('tinymce.js');

    // Load framework adapter
    if (query.api)
        include('adapter/' + query.api + '/adapter.js');

    // tinymce.util.*
    include('util/Dispatcher.js');
    include('util/URI.js');
    include('util/Cookie.js');
    include('util/JSON.js');
    include('util/JSONP.js');
    include('util/XHR.js');
    include('util/JSONRequest.js');
    include('util/VK.js');
    include('util/Quirks.js');

    // tinymce.html.*
    include('html/Entities.js');
    include('html/Styles.js');
    include('html/Schema.js');
    include('html/SaxParser.js');
    include('html/Node.js');
    include('html/DomParser.js');
    include('html/Serializer.js');
    include('html/Writer.js');

    // tinymce.dom.*
    include('dom/EventUtils.js');
    include('dom/TreeWalker.js');
    include('dom/DOMUtils.js');
    include('dom/Range.js');
    include('dom/TridentSelection.js');
    include('dom/Sizzle.js');
    include('dom/Element.js');
    include('dom/Selection.js');
    include('dom/Serializer.js');
    include('dom/ScriptLoader.js');
    include('dom/RangeUtils.js');

    // tinymce.ui.*
    include('ui/KeyboardNavigation.js');
    include('ui/Control.js');
    include('ui/Container.js');
    include('ui/Separator.js');
    include('ui/MenuItem.js');
    include('ui/Menu.js');
    include('ui/DropMenu.js');
    include('ui/Button.js');
    include('ui/ListBox.js');
    include('ui/NativeListBox.js');
    include('ui/MenuButton.js');
    include('ui/SplitButton.js');
    include('ui/ColorSplitButton.js');
    include('ui/ToolbarGroup.js');
    include('ui/Toolbar.js');

    // tinymce.*
    include('AddOnManager.js');
    include('EditorManager.js');
    include('Editor.js');
    include('Editor.Events.js');
    include('EditorCommands.js');
    include('UndoManager.js');
    include('ForceBlocks.js');
    include('ControlManager.js');
    include('WindowManager.js');
    include('Formatter.js');
    include('LegacyInput.js');
    include('EnterKey.js');

    load();
}());

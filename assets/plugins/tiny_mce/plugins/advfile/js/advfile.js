tinyMCEPopup.requireLangPack();
var allowedextension = Array("zip", "pdf", "rar", "exe", "doc", "ppt", "psd", "sitx", "sit", "eps", "cdr", "ai", "xls", "txt", "pps", "pub", "qbb", "indd", "dat", "mdb", "chm", "dmg", "iso", "wpd", "7z", "gz", "fla", "qxd", "rtf", "msi", "cab", "ttf", "qbw", "ps", "csv", "dxf", "docx", "xlsx", "pptx", "ppsx");
var AdvfileDialog = {
    init: function () {
        tinyMCEPopup.resizeToInnerSize();

        var pl = "", f = document.forms[0];
        ed = tinyMCEPopup.editor;
        fe = ed.selection.getNode();

        //Populating the target combobox value.
        document.getElementById('targetlistcontainer').innerHTML = getTargetListHTML('targetlist', 'target');

        if (ed.dom.getAttrib(fe, 'class') == 'mceItemFile') {
            pl = fe.title;
            f.insert.value = ed.getLang('update', 'Insert', true);
        }
        if (pl != "") {
            pl = tinyMCEPopup.editor.plugins.advfile._parse(pl);
            setStr(pl, null, 'href');
            setStr(pl, null, 'targetlist');
            setStr(pl, null, 'title');
        }
        document.getElementById('hrefbrowsercontainer').innerHTML = getBrowserHTML('hrefbrowsercontainer', 'href', 'advfile', 'advfile');
    },

    insert: function () {
        if (document.forms[0].elements["href"].value == '')
            tinyMCEPopup.alert("Please entere Link URL.", function (s) {
                return false;
            });
        else {
            otype = 'file';
            oclass = 'mceItemFile';
            ohref = document.forms[0].elements['href'].value;
            for (i = 0; i < allowedextension.length; i++) {
                if (eval('/(.' + allowedextension[i] + ')$/i.test(ohref)')) {
                    otype = allowedextension[i];
                    break;
                }
            }

            // Insert the contents from the input into the document
            h = '<img src="' + tinyMCEPopup.getWindowArg("plugin_url") + '/img/' + otype + '.gif" class="' + oclass + '"';
            h += ' title="' + serializeParameters() + '" />';
            ed.execCommand('mceInsertContent', false, h);
            tinyMCEPopup.close();
        }
    }
};

function serializeParameters() {
    var d = document, f = d.forms[0], s = '';
    s += getStr(null, 'href');
    s += getStr(null, 'targetlist');
    s += getStr(null, 'title');

    s = s.length > 0 ? s.substring(0, s.length - 1) : s;
    return s;
}

function setBool(pl, p, n) {
    if (typeof (pl[n]) == "undefined")
        return;

    document.forms[0].elements[p + "_" + n].checked = pl[n] != 'false';
}

function setStr(pl, p, n) {
    var f = document.forms[0], e = f.elements[(p != null ? p + "_" : '') + n];

    if (typeof (pl[n]) == "undefined")
        return;

    if (e.type == "text")
        e.value = pl[n];
    else
        selectByValue(f, (p != null ? p + "_" : '') + n, pl[n]);
}

function getBool(p, n, d, tv, fv) {
    var v = document.forms[0].elements[p + "_" + n].checked;

    tv = typeof (tv) == 'undefined' ? 'true' : "'" + jsEncode(tv) + "'";
    fv = typeof (fv) == 'undefined' ? 'false' : "'" + jsEncode(fv) + "'";

    return (v == d) ? '' : n + (v ? ':' + tv + ',' : ":\'" + fv + "\',");
}

function getStr(p, n, d) {
    var e = document.forms[0].elements[(p != null ? p + "_" : "") + n];
    var v = e.type == "text" ? e.value : e.options[e.selectedIndex].value;

    if (n == 'src')
        v = tinyMCEPopup.editor.convertURL(v, 'src', null);

    return ((n == d || v == '') ? '' : n + ":'" + jsEncode(v) + "',");
}

function getInt(p, n, d) {
    var e = document.forms[0].elements[(p != null ? p + "_" : "") + n];
    var v = e.type == "text" ? e.value : e.options[e.selectedIndex].value;

    return ((n == d || v == '') ? '' : n + ":" + v.replace(/[^0-9]+/g, '') + ",");
}

function jsEncode(s) {
    s = s.replace(new RegExp('\\\\', 'g'), '\\\\');
    s = s.replace(new RegExp('"', 'g'), '\\"');
    s = s.replace(new RegExp("'", 'g'), "\\'");

    return s;
}

function getBrowserHTML(id, target_form_element, type, prefix) {
    var option = prefix + "_" + type + "_browser_callback", cb, html;
    cb = tinyMCEPopup.getParam(option, tinyMCEPopup.getParam("file_browser_callback"));

    if (!cb)
        return "";

    html = "";
    html += '<a id="' + id + '_link" href="javascript:openBrowser(\'' + id + '\',\'' + target_form_element + '\', \'' + type + '\',\'' + option + '\');" onmousedown="return false;" class="browse">';
    html += '<span id="' + id + '" title="' + tinyMCEPopup.getLang('browse') + '">&nbsp;</span></a>';

    return html;
}

function getTargetListHTML(elm_id, target_form_element) {
    var targets = tinyMCEPopup.getParam('theme_advanced_link_targets', '').split(';');
    var html = '';

    html += '<select id="' + elm_id + '" name="' + elm_id + '" onf2ocus="tinyMCE.addSelectAccessibility(event, this, window);" onchange="this.form.' + target_form_element + '.value=';
    html += 'this.options[this.selectedIndex].value;">';
    html += '<option value="_self">Open in this window / frame</option>';
    html += '<option value="_blank">Open in new window (_blank)</option>';
    html += '<option value="_parent">Open in parent window / frame (_parent)</option>';
    html += '<option value="_top">Open in top frame (replaces all frames) (_top)</option>';

    for (var i = 0; i < targets.length; i++) {
        var key, value;

        if (targets[i] == "")
            continue;

        key = targets[i].split('=')[0];
        value = targets[i].split('=')[1];

        html += '<option value="' + key + '">' + value + ' (' + key + ')</option>';
    }

    html += '</select>';

    return html;
}

tinyMCEPopup.onInit.add(AdvfileDialog.init, AdvfileDialog);

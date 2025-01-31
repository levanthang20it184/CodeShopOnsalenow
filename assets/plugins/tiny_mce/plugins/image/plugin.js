tinymce.PluginManager.add("image", function (e) {
    function openmanager() {
        var win, data, dom = e.dom,
            imgElm = e.selection.getNode();
        var width, height, imageListCtrl;
        win = e.windowManager.open({
            title: 'Image Manager',
            data: data,
            classes: 'filemanager',
            file: tinyMCE.baseURL + '/plugins/filemanager/dialog.php?type=1&editor=' + e.id + '&lang=' + tinymce.settings.language + '&subfolder=' + tinymce.settings.subfolder,
            filetype: 'image',
            width: 900,
            height: 600,
            inline: 1
        })
    }

    function t() {
        function t() {
            var t = [{
                text: "None",
                value: ""
            }];
            return tinymce.each(e.settings.image_list, function (e) {
                t.push({
                    text: e.text || e.title,
                    value: e.value || e.url,
                    menu: e.menu
                })
            }), t
        }

        function n(e) {
            var t, n, r, i;
            t = a.find("#width")[0], n = a.find("#height")[0], r = t.value(), i = n.value(), a.find("#constrain")[0].checked() && l && c && r && i && (e.control == t ? (i = Math.round(r / l * i), n.value(i)) : (r = Math.round(i / c * r), t.value(r))), l = r, c = i
        }

        function r() {
            function t(t) {
                t.onload = t.onerror = function () {
                    t.onload = t.onerror = null, e.selection.select(t), e.nodeChanged()
                }
            }

            var n = a.toJSON();
            "" === n.width && (n.width = null), "" === n.height && (n.height = null), "" === n.style && (n.style = null), n = {
                src: n.src,
                alt: n.alt,
                width: n.width,
                height: n.height,
                style: n.style
            }, f ? d.setAttribs(f, n) : (n.id = "__mcenew", e.insertContent(d.createHTML("img", n)), f = d.get("__mcenew"), d.setAttrib(f, "id", null)), t(f)
        }

        function i(e) {
            return e && (e = e.replace(/px$/, "")), e
        }

        function o() {
            function e(e) {
                return e.length > 0 && /^[0-9]+$/.test(e) && (e += "px"), e
            }

            var t = a.toJSON(),
                n = d.parseStyle(t.style);
            delete n.margin, n["margin-top"] = n["margin-bottom"] = e(t.vspace), n["margin-left"] = n["margin-right"] = e(t.hspace), n["border-width"] = e(t.border), a.find("#style").value(d.serializeStyle(d.parseStyle(d.serializeStyle(n))))
        }

        var a, s, l, c, u, d = e.dom,
            f = e.selection.getNode();
        l = d.getAttrib(f, "width"), c = d.getAttrib(f, "height"), "IMG" != f.nodeName || f.getAttribute("data-mce-object") ? f = null : s = {
            src: d.getAttrib(f, "src"),
            alt: d.getAttrib(f, "alt"),
            width: l,
            height: c
        }, e.settings.image_list && (u = {
            name: "target",
            type: "listbox",
            label: "Image list",
            values: t(),
            onselect: function (e) {
                var t = a.find("#alt");
                (!t.value() || e.lastControl && t.value() == e.lastControl.text()) && t.value(e.control.text()), a.find("#src").value(e.control.value())
            }
        });
        var p = [{
            type: 'container',
            layout: 'flex',
            classes: 'combobox has-open',
            label: 'Source',
            direction: 'row',
            items: [{
                name: 'src',
                type: 'textbox',
                filetype: 'image',
                size: 35,
                classes: 'img_' + e.id,
                autofocus: true
            }, {
                name: 'upl_img',
                type: 'button',
                classes: 'btn open',
                icon: 'browse',
                onclick: openmanager,
                tooltip: 'Upload image'
            }]
        },
            u,
            {
                name: "alt",
                type: "textbox",
                label: "Image description"
            }, {
                type: "container",
                label: "Dimensions",
                layout: "flex",
                direction: "row",
                align: "center",
                spacing: 5,
                items: [{
                    name: "width",
                    type: "textbox",
                    maxLength: 3,
                    size: 3,
                    onchange: n
                }, {
                    type: "label",
                    text: "x"
                }, {
                    name: "height",
                    type: "textbox",
                    maxLength: 3,
                    size: 3,
                    onchange: n
                }, {
                    name: "constrain",
                    type: "checkbox",
                    checked: !0,
                    text: "Constrain proportions"
                }]
            }];
        e.settings.image_advtab ? (f && (s.hspace = i(f.style.marginLeft || f.style.marginRight), s.vspace = i(f.style.marginTop || f.style.marginBottom), s.border = i(f.style.borderWidth), s.style = e.dom.serializeStyle(e.dom.parseStyle(e.dom.getAttrib(f, "style")))), a = e.windowManager.open({
            title: "Edit image",
            data: s,
            bodyType: "tabpanel",
            body: [{
                title: "General",
                type: "form",
                items: p
            }, {
                title: "Advanced",
                type: "form",
                pack: "start",
                items: [{
                    label: "Style",
                    name: "style",
                    type: "textbox"
                }, {
                    type: "form",
                    layout: "grid",
                    packV: "start",
                    columns: 2,
                    padding: 0,
                    alignH: ["left", "right"],
                    defaults: {
                        type: "textbox",
                        maxWidth: 50,
                        onchange: o
                    },
                    items: [{
                        label: "Vertical space",
                        name: "vspace"
                    }, {
                        label: "Horizontal space",
                        name: "hspace"
                    }, {
                        label: "Border",
                        name: "border"
                    }]
                }]
            }],
            onSubmit: r
        })) : a = e.windowManager.open({
            title: "Edit image",
            data: s,
            body: p,
            onSubmit: r
        })
    }

    e.addButton("image", {
        icon: "image",
        tooltip: "Insert/edit image",
        onclick: t,
        stateSelector: "img:not([data-mce-object])"
    }), e.addMenuItem("image", {
        icon: "image",
        text: "Insert image",
        onclick: t,
        context: "insert",
        prependToContext: !0
    })
});

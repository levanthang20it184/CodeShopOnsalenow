/**
 * ## Advance File
 *
 */
(function () {
    // Load plugin specific language pack
    tinymce.PluginManager.requireLangPack('advfile');

    var allowedextension = Array("zip", "pdf", "rar", "exe", "doc", "ppt", "psd", "sitx", "sit", "eps", "cdr", "ai", "xls", "txt", "pps", "pub", "qbb", "indd", "dat", "mdb", "chm", "dmg", "iso", "wpd", "7z", "gz", "fla", "qxd", "rtf", "msi", "cab", "ttf", "qbw", "ps", "csv", "dxf", "docx", "xlsx", "pptx", "ppsx");

    // Create Plugin
    tinymce.create('tinymce.plugins.AdvfilePlugin', {

        init: function (ed, url) {
            var t = this;
            t.editor = ed;
            t.url = url;

            // Custom Functions
            function isDocElm(n) {
                return /^(mceItemFile)$/.test(n.className);
            }

            function allowedfiles(ohref) {
                for (i = 0; i < allowedextension.length; i++) {
                    if (eval('/(' + allowedextension[i] + '.gif)$/i.test(ohref)'))
                        return allowedextension[i];
                }
                return "file";
            }

            ed.onPreInit.add(function () {
                // Force in _value parameter this extra parameter is required for older Opera versions
                ed.serializer.addRules('param[name|value|_mce_value]');
            });

            // Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceAdvfile');
            ed.addCommand('mceAdvfile', function () {
                ed.windowManager.open({
                    file: url + '/advfile.htm',
                    width: 480 + parseInt(ed.getLang('advfile.delta_width', 0)),
                    height: 400 + parseInt(ed.getLang('advfile.delta_height', 0)),
                    inline: 1
                }, {
                    plugin_url: url // Plugin absolute URL
                    //some_custom_arg : 'custom arg' // Custom argument
                });
            });

            // Register advfile button
            ed.addButton('advfile', {
                title: 'advfile.desc',
                cmd: 'mceAdvfile',
                image: url + '/img/advfile.gif'
            });

            // Add a node change handler, selects the button in the UI when a image is selected
            ed.onNodeChange.add(function (ed, cm, n) {
                cm.setActive('advfile', n.nodeName == 'IMG' && isDocElm(n));
            });

            ed.onInit.add(function () {
                ed.selection.onSetContent.add(function () {
                    t._spansToImgs(ed.getBody());
                });

                ed.selection.onBeforeSetContent.add(t._objectsToSpans, t);

                if (ed.theme.onResolveName) {
                    ed.theme.onResolveName.add(function (th, o) {
                        if (o.name == 'img') {
                            if (ed.dom.hasClass(o.node, 'mceItemFile')) {
                                o.name = allowedfiles(o.node.src);
                                o.title = ed.dom.getAttrib(o.node, 'title');
                                return false;
                            }
                        }
                    });
                }

            });

            ed.onBeforeSetContent.add(t._objectsToSpans, t);

            ed.onSetContent.add(function () {
                t._spansToImgs(ed.getBody());
            });

            ed.onPreProcess.add(function (ed, o) {
                var dom = ed.dom;
                if (o.get) {
                    tinymce.each(dom.select('img', o.node), function (n) {
                        if (n.className == 'mceItemFile')
                            dom.replace(t._buildMyObj(n, allowedfiles(n.src), url), n);
                    });
                }
            });

            ed.onPostProcess.add(function (ed, o) {
                o.content = o.content.replace(/_mce_value=/g, 'value=');
            });
        },

        // Private methods
        _objectsToSpans: function (ed, o) {
            var t = this, h = o.content;
            h = h.replace(/<a (.*)([^>]*)><img (.*)src=\"(.*)\/editor\/plugins\/advfile\/img\/(.*)\.gif\"(.*)([^>]*)\/?><\/a([^>]*)\/?>/gi, '<span class="mceItemFile" $1></span>');
            o.content = h;
        },

        _buildMyObj: function (n, type, url) {

            var ob, ed = this.editor, dom = ed.dom, linkurl, p = this._parse(n.title);
            linkurl = ed.convertURL(p.href, 'href', n);

            ob = dom.create('a', {
                mce_name: 'a',
                href: linkurl,
                target: p.targetlist,
                title: p.title
            });

            dom.add(ob, 'img', {mce_name: 'img', src: url + "/img/" + type + ".gif", border: "0", align: "absmiddle"});
            return ob;
        },

        _spansToImgs: function (p) {
            var t = this, dom = t.editor.dom, ci;

            tinymce.each(dom.select('span', p), function (n) {
                // Convert object into image
                if (dom.getAttrib(n, 'class') == 'mceItemFile') {
                    ohref = dom.getAttrib(n, "href").toLowerCase().replace(/\s+/g, '');

                    otype = 'file';
                    for (i = 0; i < allowedextension.length; i++) {
                        if (eval('/(' + allowedextension[i] + ')$/i.test(ohref)'))
                            otype = allowedextension[i];
                    }
                    dom.replace(t._createImg('mceItemFile', otype, n), n);

                }
            });
        },

        _createImg: function (cl, ot, n) {
            var im, dom = this.editor.dom, pa = {};
            // Create image
            im = dom.create('img', {
                src: this.url + '/img/' + ot + '.gif',
                'class': cl
            });
            pa['href'] = dom.getAttrib(n, 'href');
            pa['targetlist'] = dom.getAttrib(n, "target");
            pa['title'] = dom.getAttrib(n, "title");
            im.title = this._serialize(pa);
            return im;
        },

        createControl: function (n, cm) {
            return null;
        },

        getInfo: function () {
            return {
                longname: 'Advfile plugin',
                author: 'Akshaya',
                authorurl: 'http://tinymce.moxiecode.com',
                infourl: 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/example',
                version: "1.0"
            };
        },
        _parse: function (s) {
            return tinymce.util.JSON.parse('{' + s + '}');
        },

        _serialize: function (o) {
            return tinymce.util.JSON.serialize(o).replace(/[{}]/g, '');
        }
    });

    // Register plugin
    tinymce.PluginManager.add('advfile', tinymce.plugins.AdvfilePlugin);
})();
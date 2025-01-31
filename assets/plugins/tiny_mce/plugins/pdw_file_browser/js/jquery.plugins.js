/**
 * jquery.treeview.js
 * JQuery Treeview Plugin
 *
 * Copyright (c) 2010 Guido Neele <http://www.neele.name>
 * Dual licensed under the MIT and GPL licenses.
 **/
(function (a) {
    a.fn.TreeView = function () {
        return this.each(function () {
            obj = a(this);
            a("a", obj).append('<span class="endcap"><img src="img/spacer.gif" width="6" height="22" /></span>').each(function () {
                a(this).prepend('<span class="icon"><img src="img/spacer.gif" width="26" height="22" /></span>')
            }).addClass("link").end();
            a("li", obj).each(function () {
                depth = parseInt(a(this).parents("ul").length);
                width = (depth - 1) * 12;
                a(this).prepend('<a class="handler"><img src="img/spacer.gif" width="16" height="22" /></a>');
                a(this).prepend('<a class="spacer"><img src="img/spacer.gif" width="' + width + '" height="22" /></a>')
            });
            a("li:has(ul) > a.handler", obj).addClass("children");
            a("a.children", obj).bind("click", function () {
                a(this).toggleClass("open");
                a(this).next().next().toggle();
                return false
            })
        })
    }
})(jQuery);

/**
 * jquery.contextmenu.js
 * jQuery Plugin for Context Menus
 * http://www.JavascriptToolbox.com/lib/contextmenu/
 *
 * Copyright (c) 2008 Matt Kruse (javascripttoolbox.com)
 * Dual licensed under the MIT and GPL licenses.
 **/
(function (d) {
    d.contextMenu = {
        shadow: true,
        shadowOffset: 0,
        shadowOffsetX: 5,
        shadowOffsetY: 5,
        shadowWidthAdjust: -3,
        shadowHeightAdjust: -3,
        shadowOpacity: 0.2,
        shadowClass: "context-menu-shadow",
        shadowColor: "black",
        offsetX: 0,
        offsetY: 0,
        appendTo: "body",
        direction: "down",
        constrainToScreen: true,
        showTransition: "show",
        hideTransition: "hide",
        showSpeed: "",
        hideSpeed: "",
        showCallback: null,
        hideCallback: null,
        className: "context-menu",
        itemClassName: "context-menu-item",
        itemHoverClassName: "context-menu-item-hover",
        disabledItemClassName: "context-menu-item-disabled",
        disabledItemHoverClassName: "context-menu-item-disabled-hover",
        separatorClassName: "context-menu-separator",
        innerDivClassName: "context-menu-item-inner",
        themePrefix: "context-menu-theme-",
        theme: "default",
        separator: "context-menu-separator",
        target: null,
        menu: null,
        shadowObj: null,
        bgiframe: null,
        shown: false,
        useIframe: false,
        create: function (b, c) {
            var a = d.extend({}, this, c);
            if (typeof b == "string") a.menu = d(b); else if (typeof b == "function") a.menuFunction = b; else a.menu = a.createMenu(b, a);
            if (a.menu) {
                a.menu.css({display: "none"});
                d(a.appendTo).append(a.menu)
            }
            if (a.shadow) {
                a.createShadow(a);
                if (a.shadowOffset) a.shadowOffsetX = a.shadowOffsetY = a.shadowOffset
            }
            d("body").bind("contextmenu", function () {
                a.hide()
            });
            return a
        },
        createIframe: function () {
            return d('<iframe frameborder="0" tabindex="-1" src="javascript:false" style="display:block;position:absolute;z-index:-1;filter:Alpha(Opacity=0);"/>')
        },
        createMenu: function (b, c) {
            var a = c.className;
            d.each(c.theme.split(","), function (l, k) {
                a += " " + c.themePrefix + k
            });
            for (var e = d('<table cellspacing="0" cellpadding="0" class="contextmenu"></table>').click(function () {
                c.hide();
                return false
            }), f = d("<tr></tr>"), g = d("<td></td>"), h = d('<div class="' + a + '"></div>'), i = 0; i < b.length; i++) if (b[i] == d.contextMenu.separator) h.append(c.createSeparator()); else for (var j in b[i]) h.append(c.createMenuItem(j, b[i][j]));
            c.useIframe && g.append(c.createIframe());
            e.append(f.append(g.append(h)));
            return e
        },
        createMenuItem: function (b, c) {
            var a = this;
            if (typeof c == "function") c = {onclick: c};
            var e = d.extend({
                onclick: function () {
                }, className: "", hoverClassName: a.itemHoverClassName, icon: "", disabled: false, title: "",
                hoverItem: a.hoverItem, hoverItemOut: a.hoverItemOut
            }, c), f = e.icon ? "background-image:url(" + e.icon + ");" : "";
            c = d('<div class="' + a.itemClassName + " " + e.className + (e.disabled ? " " + a.disabledItemClassName : "") + '" title="' + e.title + '"></div>').click(function (g) {
                return a.isItemDisabled(this) ? false : e.onclick.call(a.target, this, a, g)
            }).hover(function () {
                e.hoverItem.call(this, a.isItemDisabled(this) ? a.disabledItemHoverClassName : e.hoverClassName)
            }, function () {
                e.hoverItemOut.call(this, a.isItemDisabled(this) ? a.disabledItemHoverClassName :
                    e.hoverClassName)
            });
            b = d('<div class="' + a.innerDivClassName + '" style="' + f + '">' + b + "</div>");
            c.append(b);
            return c
        },
        createSeparator: function () {
            return d('<div class="' + this.separatorClassName + '"></div>')
        },
        isItemDisabled: function (b) {
            return d(b).is("." + this.disabledItemClassName)
        },
        hoverItem: function (b) {
            d(this).addClass(b)
        },
        hoverItemOut: function (b) {
            d(this).removeClass(b)
        },
        createShadow: function (b) {
            b.shadowObj = d('<div class="' + b.shadowClass + '"></div>').css({
                display: "none", position: "absolute", zIndex: 9998, opacity: b.shadowOpacity,
                backgroundColor: b.shadowColor
            });
            d(b.appendTo).append(b.shadowObj)
        },
        showShadow: function (b, c) {
            var a = this;
            a.shadow && a.shadowObj.css({
                width: a.menu.width() + a.shadowWidthAdjust + "px",
                height: a.menu.height() + a.shadowHeightAdjust + "px",
                top: c + a.shadowOffsetY + "px",
                left: b + a.shadowOffsetX + "px"
            }).addClass(a.shadowClass).show()
        },
        beforeShow: function () {
            return true
        },
        show: function (b, c) {
            var a = this, e = c.pageX, f = c.pageY;
            a.target = b;
            if (a.beforeShow() !== false) {
                if (a.menuFunction) {
                    a.menu && d(a.menu).remove();
                    a.menu = a.createMenu(a.menuFunction(a,
                        b), a);
                    a.menu.css({display: "none"});
                    d(a.appendTo).append(a.menu)
                }
                b = a.menu;
                e += a.offsetX;
                f += a.offsetY;
                e = a.getPosition(e, f, a, c);
                a.showShadow(e.x, e.y, c);
                a.useIframe && b.find("iframe").css({
                    width: b.width() + a.shadowOffsetX + a.shadowWidthAdjust,
                    height: b.height() + a.shadowOffsetY + a.shadowHeightAdjust
                });
                b.css({top: e.y + "px", left: e.x + "px", position: "absolute", zIndex: 9999}).show();
                a.shown = true;
                d(document).one("click", null, function () {
                    a.hide()
                })
            }
        },
        getPosition: function (b, c, a) {
            b = b + a.offsetX;
            c = c + a.offsetY;
            var e = d(a.menu).height(),
                f = d(a.menu).width(), g = a.direction;
            if (a.constrainToScreen) {
                var h = d(window), i = h.height();
                a = h.width();
                if (g == "down" && c + e - h.scrollTop() > i) g = "up";
                f = b + f - h.scrollLeft();
                if (f > a) b -= f - a
            }
            if (g == "up") c -= e;
            return {x: b, y: c}
        },
        hide: function () {
            var b = this;
            if (b.shown) {
                b.iframe && d(b.iframe).hide();
                b.menu && b.menu.hide();
                b.shadow && b.shadowObj.hide()
            }
            b.shown = false
        }
    };
    d.fn.contextMenu = function (b, c) {
        var a = d.contextMenu.create(b, c);
        return this.each(function () {
            d(this).bind("contextmenu", function (e) {
                a.show(this, e);
                return false
            })
        })
    }
})(jQuery);

/**
 * Slimbox v2.04 - The ultimate lightweight Lightbox clone for jQuery
 * (c) 2007-2010 Christophe Beyls <http://www.digitalia.be>
 * MIT-style license.
 *
 * Modified to be even more leightweight and optimized for PDW File Browser
 * Compiled Size: 3.38KB (1.51KB gzipped)
 **/
(function (a) {
    function v() {
        var b = i.scrollLeft(), c = i.width();
        a(e).css("left", b + c / 2);
        w && a(h).css({left: b, top: i.scrollTop(), width: c, height: i.height()})
    }

    function x(b) {
        if (b) a("object").add(y ? "select" : "embed").each(function (c, d) {
            t[c] = [d, d.style.visibility];
            d.style.visibility = "hidden"
        }); else {
            a.each(t, function (c, d) {
                d[0].style.visibility = d[1]
            });
            t = []
        }
        b = b ? "bind" : "unbind";
        i[b]("scroll resize", v);
        a(document)[b]("keydown", E)
    }

    function E(b) {
        var c = a.inArray;
        return c(b.keyCode, f.closeKeys) >= 0 ? z() : false
    }

    function F() {
        e.className =
            "";
        imgWidth = j.width;
        imgHeight = j.height;
        var b = G(), c = imgWidth / imgHeight;
        if (c > b[2] / b[3]) {
            b = b[2] - 40 - 100;
            var d = Math.round(b / c)
        } else {
            d = b[3] - 50 - b[3] / 15 - 50;
            b = Math.round(d * c)
        }
        if (imgWidth > b || imgHeight > d) {
            imgWidth = b;
            imgHeight = d
        }
        a(k).css({visibility: "hidden", display: ""});
        a(q).attr("src", r);
        a(q).width(imgWidth);
        a([q]).height(imgHeight);
        l = k.offsetWidth;
        m = k.offsetHeight;
        c = Math.max(0, u - m / 2);
        e.offsetHeight != m && a(e).animate({height: m, top: c}, f.resizeDuration, f.resizeEasing);
        e.offsetWidth != l && a(e).animate({
            width: l, marginLeft: -l /
                2
        }, f.resizeDuration, f.resizeEasing);
        a(e).queue(function () {
            a(k).css({display: "none", visibility: "", opacity: ""}).fadeIn(f.imageFadeDuration)
        })
    }

    function A() {
        j.onload = null;
        j.src = r;
        a([e, k]).stop(true);
        a([k]).hide()
    }

    function z() {
        if (s >= 0) {
            A();
            s = -1;
            a(e).hide();
            a(h).stop().fadeOut(f.overlayFadeDuration, x)
        }
        return false
    }

    function G() {
        var b, c;
        if (window.innerHeight && window.scrollMaxY) {
            b = window.innerWidth + window.scrollMaxX;
            c = window.innerHeight + window.scrollMaxY
        } else if (document.body.scrollHeight > document.body.offsetHeight) {
            b =
                document.body.scrollWidth;
            c = document.body.scrollHeight
        } else {
            b = document.body.offsetWidth;
            c = document.body.offsetHeight
        }
        var d, g;
        if (self.innerHeight) {
            d = document.documentElement.clientWidth ? document.documentElement.clientWidth : self.innerWidth;
            g = self.innerHeight
        } else if (document.documentElement && document.documentElement.clientHeight) {
            d = document.documentElement.clientWidth;
            g = document.documentElement.clientHeight
        } else if (document.body) {
            d = document.body.clientWidth;
            g = document.body.clientHeight
        }
        pageHeight = c <
        g ? g : c;
        pageWidth = b < d ? b : d;
        return [pageWidth, pageHeight, d, g]
    }

    var i = a(window), f, B, s = -1, r, w, u, l, m, y = !window.XMLHttpRequest, t = [], j = {}, h, e, k, q;
    a(function () {
        a("body").append(a([h = a('<div id="lbOverlay" />')[0], e = a('<div id="lbCenter" />').append(a('<a id="lbCloseLink" href="#" />').add(h).click(z)[0])[0]]).css("display", "none"));
        k = a('<div id="lbImage" />').appendTo(e).append(q = a('<img src="" style="position: relative;" />')[0])[0]
    });
    a.slimbox = function (b, c, d) {
        f = a.extend({
            overlayOpacity: 0.8,
            overlayFadeDuration: 400,
            resizeDuration: 400,
            resizeEasing: "swing",
            initialWidth: 250,
            initialHeight: 250,
            imageFadeDuration: 400,
            captionAnimationDuration: 400,
            closeKeys: [27, 88, 67]
        }, d);
        if (typeof b == "string") {
            b = [[b, c]];
            c = 0
        }
        u = i.scrollTop() + i.height() / 2;
        l = f.initialWidth;
        m = f.initialHeight;
        a(e).css({top: Math.max(0, u - m / 2), width: l, height: m, marginLeft: -l / 2}).show();
        if (w = y || h.currentStyle && h.currentStyle.position != "fixed") h.style.position = "absolute";
        a(h).css("opacity", f.overlayOpacity).fadeIn(f.overlayFadeDuration);
        v();
        x(1);
        B = b;
        if (c >= 0) {
            s =
                c;
            r = B[s][0];
            A();
            e.className = "lbLoading";
            j = new Image;
            j.onload = F;
            j.src = r
        }
        return false
    };
    a.fn.slimbox = function (b, c, d) {
        c = c || function (p) {
            return [p.href, p.title]
        };
        d = d || function () {
            return true
        };
        var g = this;
        return g.unbind("click").click(function () {
            var p = this, C = 0, o, n = 0, D;
            o = a.grep(g, function (H, I) {
                return d.call(p, H, I)
            });
            for (D = o.length; n < D; ++n) {
                if (o[n] == p) C = n;
                o[n] = c(o[n], n)
            }
            return a.slimbox(o, C, b)
        })
    }
})(jQuery);
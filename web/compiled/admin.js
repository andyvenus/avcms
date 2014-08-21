/*! jQuery v1.11.0 | (c) 2005, 2014 jQuery Foundation, Inc. | jquery.org/license */
!function (e, t) {
    "object" == typeof module && "object" == typeof module.exports ? module.exports = e.document ? t(e, !0) : function (e) {
        if (!e.document)throw new Error("jQuery requires a window with a document");
        return t(e)
    } : t(e)
}("undefined" != typeof window ? window : this, function (t, n) {
    var h = [], p = h.slice, Se = h.concat, te = h.push, Te = h.indexOf, M = {}, Yt = M.toString, E = M.hasOwnProperty, V = "".trim, r = {}, je = "1.11.0", e = function (t, n) {
        return new e.fn.init(t, n)
    }, Kt = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, tn = /^-ms-/, en = /-([\da-z])/gi, Zt = function (e, t) {
        return t.toUpperCase()
    };
    e.fn = e.prototype = {jquery: je, constructor: e, selector: "", length: 0, toArray: function () {
        return p.call(this)
    }, get: function (e) {
        return null != e ? 0 > e ? this[e + this.length] : this[e] : p.call(this)
    }, pushStack: function (t) {
        var n = e.merge(this.constructor(), t);
        return n.prevObject = this, n.context = this.context, n
    }, each: function (t, n) {
        return e.each(this, t, n)
    }, map: function (t) {
        return this.pushStack(e.map(this, function (e, n) {
            return t.call(e, n, e)
        }))
    }, slice: function () {
        return this.pushStack(p.apply(this, arguments))
    }, first: function () {
        return this.eq(0)
    }, last: function () {
        return this.eq(-1)
    }, eq: function (e) {
        var n = this.length, t = +e + (0 > e ? n : 0);
        return this.pushStack(t >= 0 && n > t ? [this[t]] : [])
    }, end: function () {
        return this.prevObject || this.constructor(null)
    }, push: te, sort: h.sort, splice: h.splice}, e.extend = e.fn.extend = function () {
        var i, u, n, o, s, l, t = arguments[0] || {}, r = 1, c = arguments.length, a = !1;
        for ("boolean" == typeof t && (a = t, t = arguments[r] || {}, r++), "object" == typeof t || e.isFunction(t) || (t = {}), r === c && (t = this, r--); c > r; r++)if (null != (s = arguments[r]))for (o in s)i = t[o], n = s[o], t !== n && (a && n && (e.isPlainObject(n) || (u = e.isArray(n))) ? (u ? (u = !1, l = i && e.isArray(i) ? i : []) : l = i && e.isPlainObject(i) ? i : {}, t[o] = e.extend(a, l, n)) : void 0 !== n && (t[o] = n));
        return t
    }, e.extend({expando: "jQuery" + (je + Math.random()).replace(/\D/g, ""), isReady: !0, error: function (e) {
        throw new Error(e);
    }, noop: function () {
    }, isFunction: function (t) {
        return"function" === e.type(t)
    }, isArray: Array.isArray || function (t) {
        return"array" === e.type(t)
    }, isWindow: function (e) {
        return null != e && e == e.window
    }, isNumeric: function (e) {
        return e - parseFloat(e) >= 0
    }, isEmptyObject: function (e) {
        var t;
        for (t in e)return!1;
        return!0
    }, isPlainObject: function (t) {
        var i;
        if (!t || "object" !== e.type(t) || t.nodeType || e.isWindow(t))return!1;
        try {
            if (t.constructor && !E.call(t, "constructor") && !E.call(t.constructor.prototype, "isPrototypeOf"))return!1
        } catch (n) {
            return!1
        }
        ;
        if (r.ownLast)for (i in t)return E.call(t, i);
        for (i in t);
        return void 0 === i || E.call(t, i)
    }, type: function (e) {
        return null == e ? e + "" : "object" == typeof e || "function" == typeof e ? M[Yt.call(e)] || "object" : typeof e
    }, globalEval: function (n) {
        n && e.trim(n) && (t.execScript || function (e) {
            t.eval.call(t, e)
        })(n)
    }, camelCase: function (e) {
        return e.replace(tn, "ms-").replace(en, Zt)
    }, nodeName: function (e, t) {
        return e.nodeName && e.nodeName.toLowerCase() === t.toLowerCase()
    }, each: function (e, t, n) {
        var i, r = 0, a = e.length, o = re(e);
        if (n) {
            if (o) {
                for (; a > r; r++)if (i = t.apply(e[r], n), i === !1)break
            } else for (r in e)if (i = t.apply(e[r], n), i === !1)break
        } else if (o) {
            for (; a > r; r++)if (i = t.call(e[r], r, e[r]), i === !1)break
        } else for (r in e)if (i = t.call(e[r], r, e[r]), i === !1)break;
        return e
    }, trim: V && !V.call("\ufeff\xa0") ? function (e) {
        return null == e ? "" : V.call(e)
    } : function (e) {
        return null == e ? "" : (e + "").replace(Kt, "")
    }, makeArray: function (t, n) {
        var r = n || [];
        return null != t && (re(Object(t)) ? e.merge(r, "string" == typeof t ? [t] : t) : te.call(r, t)), r
    }, inArray: function (e, t, n) {
        var r;
        if (t) {
            if (Te)return Te.call(t, e, n);
            for (r = t.length, n = n ? 0 > n ? Math.max(0, r + n) : n : 0; r > n; n++)if (n in t && t[n] === e)return n
        }
        ;
        return-1
    }, merge: function (e, t) {
        var i = +t.length, n = 0, r = e.length;
        while (i > n)e[r++] = t[n++];
        if (i !== i)while (void 0 !== t[n])e[r++] = t[n++];
        return e.length = r, e
    }, grep: function (e, t, n) {
        for (var i, o = [], r = 0, s = e.length, a = !n; s > r; r++)i = !t(e[r], r), i !== a && o.push(e[r]);
        return o
    }, map: function (e, t, n) {
        var i, r = 0, s = e.length, a = re(e), o = [];
        if (a)for (; s > r; r++)i = t(e[r], r, n), null != i && o.push(i); else for (r in e)i = t(e[r], r, n), null != i && o.push(i);
        return Se.apply([], o)
    }, guid: 1, proxy: function (t, n) {
        var o, r, i;
        return"string" == typeof n && (i = t[n], n = t, t = i), e.isFunction(t) ? (o = p.call(arguments, 2), r = function () {
            return t.apply(n || this, o.concat(p.call(arguments)))
        }, r.guid = t.guid = t.guid || e.guid++, r) : void 0
    }, now: function () {
        return+new Date
    }, support: r}), e.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function (e, t) {
        M["[object " + t + "]"] = t.toLowerCase()
    });
    function re(t) {
        var n = t.length, r = e.type(t);
        return"function" === r || e.isWindow(t) ? !1 : 1 === t.nodeType && n ? !0 : "array" === r || 0 === n || "number" == typeof n && n > 0 && n - 1 in t
    };
    var k = function (e) {
        var E, r, t, D, oe, V, M, g, k, b, s, h, p, l, w, H, j, a = "sizzle" + -new Date, d = e.document, f = 0, de = 0, ie = R(), re = R(), te = R(), W = function (e, t) {
            return e === t && (k = !0), 0
        }, N = "undefined", ne = 1 << 31, ye = {}.hasOwnProperty, v = [], Ce = v.pop, Ne = v.push, m = v.push, se = v.slice, C = v.indexOf || function (e) {
            for (var t = 0, n = this.length; n > t; t++)if (this[t] === e)return t;
            return-1
        }, J = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped", n = "[\\x20\\t\\r\\n\\f]", S = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+", ee = S.replace("w", "w#"), ae = "\\[" + n + "*(" + S + ")" + n + "*(?:([*^$|!~]?=)" + n + "*(?:(['\"])((?:\\\\.|[^\\\\])*?)\\3|(" + ee + ")|)|)" + n + "*\\]", Q = ":(" + S + ")(?:\\(((['\"])((?:\\\\.|[^\\\\])*?)\\3|((?:\\\\.|[^\\\\()[\\]]|" + ae.replace(3, 8) + ")*)|.*)\\)|)", B = new RegExp("^" + n + "+|((?:^|[^\\\\])(?:\\\\.)*)" + n + "+$", "g"), xe = new RegExp("^" + n + "*," + n + "*"), Ee = new RegExp("^" + n + "*([>+~]|" + n + ")" + n + "*"), we = new RegExp("=" + n + "*([^\\]'\"]*?)" + n + "*\\]", "g"), ge = new RegExp(Q), ce = new RegExp("^" + ee + "$"), L = {ID: new RegExp("^#(" + S + ")"), CLASS: new RegExp("^\\.(" + S + ")"), TAG: new RegExp("^(" + S.replace("w", "w*") + ")"), ATTR: new RegExp("^" + ae), PSEUDO: new RegExp("^" + Q), CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + n + "*(even|odd|(([+-]|)(\\d*)n|)" + n + "*(?:([+-]|)" + n + "*(\\d+)|))" + n + "*\\)|)", "i"), bool: new RegExp("^(?:" + J + ")$", "i"), needsContext: new RegExp("^" + n + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + n + "*((?:-\\d)?\\d*)" + n + "*\\)|)(?=[^-]|$)", "i")}, ue = /^(?:input|select|textarea|button)$/i, le = /^h\d$/i, A = /^[^{]+\{\s*\[native \w/, pe = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/, U = /[+~]/, Te = /'|\\/g, x = new RegExp("\\\\([\\da-f]{1,6}" + n + "?|(" + n + ")|.)", "ig"), y = function (e, t, n) {
            var r = "0x" + t - 65536;
            return r !== r || n ? t : 0 > r ? String.fromCharCode(r + 65536) : String.fromCharCode(r >> 10 | 55296, 1023 & r | 56320)
        };
        try {
            m.apply(v = se.call(d.childNodes), d.childNodes), v[d.childNodes.length].nodeType
        } catch (o) {
            m = {apply: v.length ? function (e, t) {
                Ne.apply(e, se.call(t))
            } : function (e, t) {
                var n = e.length, r = 0;
                while (e[n++] = t[r++]);
                e.length = n - 1
            }}
        }
        ;
        function i(e, t, n, i) {
            var w, u, c, g, x, h, v, f, T, y;
            if ((t ? t.ownerDocument || t : d) !== s && b(t), t = t || s, n = n || [], !e || "string" != typeof e)return n;
            if (1 !== (g = t.nodeType) && 9 !== g)return[];
            if (p && !i) {
                if (w = pe.exec(e))if (c = w[1]) {
                    if (9 === g) {
                        if (u = t.getElementById(c), !u || !u.parentNode)return n;
                        if (u.id === c)return n.push(u), n
                    } else if (t.ownerDocument && (u = t.ownerDocument.getElementById(c)) && j(t, u) && u.id === c)return n.push(u), n
                } else {
                    if (w[2])return m.apply(n, t.getElementsByTagName(e)), n;
                    if ((c = w[3]) && r.getElementsByClassName && t.getElementsByClassName)return m.apply(n, t.getElementsByClassName(c)), n
                }
                ;
                if (r.qsa && (!l || !l.test(e))) {
                    if (f = v = a, T = t, y = 9 === g && e, 1 === g && "object" !== t.nodeName.toLowerCase()) {
                        h = O(e), (v = t.getAttribute("id")) ? f = v.replace(Te, "\\$&") : t.setAttribute("id", f), f = "[id='" + f + "'] ", x = h.length;
                        while (x--)h[x] = f + F(h[x]);
                        T = U.test(e) && G(t.parentNode) || t, y = h.join(",")
                    }
                    ;
                    if (y)try {
                        return m.apply(n, T.querySelectorAll(y)), n
                    } catch (o) {
                    } finally {
                        v || t.removeAttribute("id")
                    }
                }
            }
            ;
            return he(e.replace(B, "$1"), t, n, i)
        };
        function R() {
            var n = [];

            function e(r, i) {
                return n.push(r + " ") > t.cacheLength && delete e[n.shift()], e[r + " "] = i
            };
            return e
        };
        function u(e) {
            return e[a] = !0, e
        };
        function c(e) {
            var n = s.createElement("div");
            try {
                return!!e(n)
            } catch (t) {
                return!1
            } finally {
                n.parentNode && n.parentNode.removeChild(n), n = null
            }
        };
        function P(e, n) {
            var i = e.split("|"), r = e.length;
            while (r--)t.attrHandle[i[r]] = n
        };
        function K(e, t) {
            var n = t && e, r = n && 1 === e.nodeType && 1 === t.nodeType && (~t.sourceIndex || ne) - (~e.sourceIndex || ne);
            if (r)return r;
            if (n)while (n = n.nextSibling)if (n === t)return-1;
            return e ? 1 : -1
        };
        function be(e) {
            return function (t) {
                var n = t.nodeName.toLowerCase();
                return"input" === n && t.type === e
            }
        };
        function ve(e) {
            return function (t) {
                var n = t.nodeName.toLowerCase();
                return("input" === n || "button" === n) && t.type === e
            }
        };
        function T(e) {
            return u(function (t) {
                return t = +t, u(function (n, r) {
                    var i, a = e([], n.length, t), o = a.length;
                    while (o--)n[i = a[o]] && (n[i] = !(r[i] = n[i]))
                })
            })
        };
        function G(e) {
            return e && typeof e.getElementsByTagName !== N && e
        };
        r = i.support = {}, oe = i.isXML = function (e) {
            var t = e && (e.ownerDocument || e).documentElement;
            return t ? "HTML" !== t.nodeName : !1
        }, b = i.setDocument = function (e) {
            var u, i = e ? e.ownerDocument || e : d, o = i.defaultView;
            return i !== s && 9 === i.nodeType && i.documentElement ? (s = i, h = i.documentElement, p = !oe(i), o && o !== o.top && (o.addEventListener ? o.addEventListener("unload", function () {
                b()
            }, !1) : o.attachEvent && o.attachEvent("onunload", function () {
                b()
            })), r.attributes = c(function (e) {
                return e.className = "i", !e.getAttribute("className")
            }), r.getElementsByTagName = c(function (e) {
                return e.appendChild(i.createComment("")), !e.getElementsByTagName("*").length
            }), r.getElementsByClassName = A.test(i.getElementsByClassName) && c(function (e) {
                return e.innerHTML = "<div class='a'></div><div class='a i'></div>", e.firstChild.className = "i", 2 === e.getElementsByClassName("i").length
            }), r.getById = c(function (e) {
                return h.appendChild(e).id = a, !i.getElementsByName || !i.getElementsByName(a).length
            }), r.getById ? (t.find.ID = function (e, t) {
                if (typeof t.getElementById !== N && p) {
                    var n = t.getElementById(e);
                    return n && n.parentNode ? [n] : []
                }
            }, t.filter.ID = function (e) {
                var t = e.replace(x, y);
                return function (e) {
                    return e.getAttribute("id") === t
                }
            }) : (delete t.find.ID, t.filter.ID = function (e) {
                var t = e.replace(x, y);
                return function (e) {
                    var n = typeof e.getAttributeNode !== N && e.getAttributeNode("id");
                    return n && n.value === t
                }
            }), t.find.TAG = r.getElementsByTagName ? function (e, t) {
                return typeof t.getElementsByTagName !== N ? t.getElementsByTagName(e) : void 0
            } : function (e, t) {
                var n, r = [], o = 0, i = t.getElementsByTagName(e);
                if ("*" === e) {
                    while (n = i[o++])1 === n.nodeType && r.push(n);
                    return r
                }
                ;
                return i
            }, t.find.CLASS = r.getElementsByClassName && function (e, t) {
                return typeof t.getElementsByClassName !== N && p ? t.getElementsByClassName(e) : void 0
            }, w = [], l = [], (r.qsa = A.test(i.querySelectorAll)) && (c(function (e) {
                e.innerHTML = "<select t=''><option selected=''></option></select>", e.querySelectorAll("[t^='']").length && l.push("[*^$]=" + n + "*(?:''|\"\")"), e.querySelectorAll("[selected]").length || l.push("\\[" + n + "*(?:value|" + J + ")"), e.querySelectorAll(":checked").length || l.push(":checked")
            }), c(function (e) {
                var t = i.createElement("input");
                t.setAttribute("type", "hidden"), e.appendChild(t).setAttribute("name", "D"), e.querySelectorAll("[name=d]").length && l.push("name" + n + "*[*^$|!~]?="), e.querySelectorAll(":enabled").length || l.push(":enabled", ":disabled"), e.querySelectorAll("*,:x"), l.push(",.*:")
            })), (r.matchesSelector = A.test(H = h.webkitMatchesSelector || h.mozMatchesSelector || h.oMatchesSelector || h.msMatchesSelector)) && c(function (e) {
                r.disconnectedMatch = H.call(e, "div"), H.call(e, "[s!='']:x"), w.push("!=", Q)
            }), l = l.length && new RegExp(l.join("|")), w = w.length && new RegExp(w.join("|")), u = A.test(h.compareDocumentPosition), j = u || A.test(h.contains) ? function (e, t) {
                var r = 9 === e.nodeType ? e.documentElement : e, n = t && t.parentNode;
                return e === n || !(!n || 1 !== n.nodeType || !(r.contains ? r.contains(n) : e.compareDocumentPosition && 16 & e.compareDocumentPosition(n)))
            } : function (e, t) {
                if (t)while (t = t.parentNode)if (t === e)return!0;
                return!1
            }, W = u ? function (e, t) {
                if (e === t)return k = !0, 0;
                var n = !e.compareDocumentPosition - !t.compareDocumentPosition;
                return n ? n : (n = (e.ownerDocument || e) === (t.ownerDocument || t) ? e.compareDocumentPosition(t) : 1, 1 & n || !r.sortDetached && t.compareDocumentPosition(e) === n ? e === i || e.ownerDocument === d && j(d, e) ? -1 : t === i || t.ownerDocument === d && j(d, t) ? 1 : g ? C.call(g, e) - C.call(g, t) : 0 : 4 & n ? -1 : 1)
            } : function (e, t) {
                if (e === t)return k = !0, 0;
                var r, n = 0, l = e.parentNode, s = t.parentNode, a = [e], o = [t];
                if (!l || !s)return e === i ? -1 : t === i ? 1 : l ? -1 : s ? 1 : g ? C.call(g, e) - C.call(g, t) : 0;
                if (l === s)return K(e, t);
                r = e;
                while (r = r.parentNode)a.unshift(r);
                r = t;
                while (r = r.parentNode)o.unshift(r);
                while (a[n] === o[n])n++;
                return n ? K(a[n], o[n]) : a[n] === d ? -1 : o[n] === d ? 1 : 0
            }, i) : s
        }, i.matches = function (e, t) {
            return i(e, null, null, t)
        }, i.matchesSelector = function (e, t) {
            if ((e.ownerDocument || e) !== s && b(e), t = t.replace(we, "='$1']"), !(!r.matchesSelector || !p || w && w.test(t) || l && l.test(t)))try {
                var o = H.call(e, t);
                if (o || r.disconnectedMatch || e.document && 11 !== e.document.nodeType)return o
            } catch (n) {
            }
            ;
            return i(t, s, null, [e]).length > 0
        }, i.contains = function (e, t) {
            return(e.ownerDocument || e) !== s && b(e), j(e, t)
        }, i.attr = function (e, n) {
            (e.ownerDocument || e) !== s && b(e);
            var o = t.attrHandle[n.toLowerCase()], i = o && ye.call(t.attrHandle, n.toLowerCase()) ? o(e, n, !p) : void 0;
            return void 0 !== i ? i : r.attributes || !p ? e.getAttribute(n) : (i = e.getAttributeNode(n)) && i.specified ? i.value : null
        }, i.error = function (e) {
            throw new Error("Syntax error, unrecognized expression: " + e);
        }, i.uniqueSort = function (e) {
            var o, i = [], n = 0, t = 0;
            if (k = !r.detectDuplicates, g = !r.sortStable && e.slice(0), e.sort(W), k) {
                while (o = e[t++])o === e[t] && (n = i.push(t));
                while (n--)e.splice(i[n], 1)
            }
            ;
            return g = null, e
        }, D = i.getText = function (e) {
            var r, n = "", i = 0, t = e.nodeType;
            if (t) {
                if (1 === t || 9 === t || 11 === t) {
                    if ("string" == typeof e.textContent)return e.textContent;
                    for (e = e.firstChild; e; e = e.nextSibling)n += D(e)
                } else if (3 === t || 4 === t)return e.nodeValue
            } else while (r = e[i++])n += D(r);
            return n
        }, t = i.selectors = {cacheLength: 50, createPseudo: u, match: L, attrHandle: {}, find: {}, relative: {">": {dir: "parentNode", first: !0}, " ": {dir: "parentNode"}, "+": {dir: "previousSibling", first: !0}, "~": {dir: "previousSibling"}}, preFilter: {ATTR: function (e) {
            return e[1] = e[1].replace(x, y), e[3] = (e[4] || e[5] || "").replace(x, y), "~=" === e[2] && (e[3] = " " + e[3] + " "), e.slice(0, 4)
        }, CHILD: function (e) {
            return e[1] = e[1].toLowerCase(), "nth" === e[1].slice(0, 3) ? (e[3] || i.error(e[0]), e[4] = +(e[4] ? e[5] + (e[6] || 1) : 2 * ("even" === e[3] || "odd" === e[3])), e[5] = +(e[7] + e[8] || "odd" === e[3])) : e[3] && i.error(e[0]), e
        }, PSEUDO: function (e) {
            var n, t = !e[5] && e[2];
            return L.CHILD.test(e[0]) ? null : (e[3] && void 0 !== e[4] ? e[2] = e[4] : t && ge.test(t) && (n = O(t, !0)) && (n = t.indexOf(")", t.length - n) - t.length) && (e[0] = e[0].slice(0, n), e[2] = t.slice(0, n)), e.slice(0, 3))
        }}, filter: {TAG: function (e) {
            var t = e.replace(x, y).toLowerCase();
            return"*" === e ? function () {
                return!0
            } : function (e) {
                return e.nodeName && e.nodeName.toLowerCase() === t
            }
        }, CLASS: function (e) {
            var t = ie[e + " "];
            return t || (t = new RegExp("(^|" + n + ")" + e + "(" + n + "|$)")) && ie(e, function (e) {
                return t.test("string" == typeof e.className && e.className || typeof e.getAttribute !== N && e.getAttribute("class") || "")
            })
        }, ATTR: function (e, t, n) {
            return function (r) {
                var o = i.attr(r, e);
                return null == o ? "!=" === t : t ? (o += "", "=" === t ? o === n : "!=" === t ? o !== n : "^=" === t ? n && 0 === o.indexOf(n) : "*=" === t ? n && o.indexOf(n) > -1 : "$=" === t ? n && o.slice(-n.length) === n : "~=" === t ? (" " + o + " ").indexOf(n) > -1 : "|=" === t ? o === n || o.slice(0, n.length + 1) === n + "-" : !1) : !0
            }
        }, CHILD: function (e, t, n, r, i) {
            var l = "nth" !== e.slice(0, 3), s = "last" !== e.slice(-4), o = "of-type" === t;
            return 1 === r && 0 === i ? function (e) {
                return!!e.parentNode
            } : function (t, n, u) {
                var h, v, c, d, p, y, g = l !== s ? "nextSibling" : "previousSibling", m = t.parentNode, x = o && t.nodeName.toLowerCase(), b = !u && !o;
                if (m) {
                    if (l) {
                        while (g) {
                            c = t;
                            while (c = c[g])if (o ? c.nodeName.toLowerCase() === x : 1 === c.nodeType)return!1;
                            y = g = "only" === e && !y && "nextSibling"
                        }
                        ;
                        return!0
                    }
                    ;
                    if (y = [s ? m.firstChild : m.lastChild], s && b) {
                        v = m[a] || (m[a] = {}), h = v[e] || [], p = h[0] === f && h[1], d = h[0] === f && h[2], c = p && m.childNodes[p];
                        while (c = ++p && c && c[g] || (d = p = 0) || y.pop())if (1 === c.nodeType && ++d && c === t) {
                            v[e] = [f, p, d];
                            break
                        }
                    } else if (b && (h = (t[a] || (t[a] = {}))[e]) && h[0] === f)d = h[1]; else while (c = ++p && c && c[g] || (d = p = 0) || y.pop())if ((o ? c.nodeName.toLowerCase() === x : 1 === c.nodeType) && ++d && (b && ((c[a] || (c[a] = {}))[e] = [f, d]), c === t))break;
                    return d -= i, d === r || d % r === 0 && d / r >= 0
                }
            }
        }, PSEUDO: function (e, n) {
            var o, r = t.pseudos[e] || t.setFilters[e.toLowerCase()] || i.error("unsupported pseudo: " + e);
            return r[a] ? r(n) : r.length > 1 ? (o = [e, e, "", n], t.setFilters.hasOwnProperty(e.toLowerCase()) ? u(function (e, t) {
                var o, a = r(e, n), i = a.length;
                while (i--)o = C.call(e, a[i]), e[o] = !(t[o] = a[i])
            }) : function (e) {
                return r(e, 0, o)
            }) : r
        }}, pseudos: {not: u(function (e) {
            var n = [], r = [], t = V(e.replace(B, "$1"));
            return t[a] ? u(function (e, n, r, i) {
                var a, s = t(e, null, i, []), o = e.length;
                while (o--)(a = s[o]) && (e[o] = !(n[o] = a))
            }) : function (e, i, o) {
                return n[0] = e, t(n, null, o, r), !r.pop()
            }
        }), has: u(function (e) {
            return function (t) {
                return i(e, t).length > 0
            }
        }), contains: u(function (e) {
            return function (t) {
                return(t.textContent || t.innerText || D(t)).indexOf(e) > -1
            }
        }), lang: u(function (e) {
            return ce.test(e || "") || i.error("unsupported lang: " + e), e = e.replace(x, y).toLowerCase(), function (t) {
                var n;
                do if (n = p ? t.lang : t.getAttribute("xml:lang") || t.getAttribute("lang"))return n = n.toLowerCase(), n === e || 0 === n.indexOf(e + "-"); while ((t = t.parentNode) && 1 === t.nodeType);
                return!1
            }
        }), target: function (t) {
            var n = e.location && e.location.hash;
            return n && n.slice(1) === t.id
        }, root: function (e) {
            return e === h
        }, focus: function (e) {
            return e === s.activeElement && (!s.hasFocus || s.hasFocus()) && !!(e.type || e.href || ~e.tabIndex)
        }, enabled: function (e) {
            return e.disabled === !1
        }, disabled: function (e) {
            return e.disabled === !0
        }, checked: function (e) {
            var t = e.nodeName.toLowerCase();
            return"input" === t && !!e.checked || "option" === t && !!e.selected
        }, selected: function (e) {
            return e.parentNode && e.parentNode.selectedIndex, e.selected === !0
        }, empty: function (e) {
            for (e = e.firstChild; e; e = e.nextSibling)if (e.nodeType < 6)return!1;
            return!0
        }, parent: function (e) {
            return!t.pseudos.empty(e)
        }, header: function (e) {
            return le.test(e.nodeName)
        }, input: function (e) {
            return ue.test(e.nodeName)
        }, button: function (e) {
            var t = e.nodeName.toLowerCase();
            return"input" === t && "button" === e.type || "button" === t
        }, text: function (e) {
            var t;
            return"input" === e.nodeName.toLowerCase() && "text" === e.type && (null == (t = e.getAttribute("type")) || "text" === t.toLowerCase())
        }, first: T(function () {
            return[0]
        }), last: T(function (e, t) {
            return[t - 1]
        }), eq: T(function (e, t, n) {
            return[0 > n ? n + t : n]
        }), even: T(function (e, t) {
            for (var n = 0; t > n; n += 2)e.push(n);
            return e
        }), odd: T(function (e, t) {
            for (var n = 1; t > n; n += 2)e.push(n);
            return e
        }), lt: T(function (e, t, n) {
            for (var r = 0 > n ? n + t : n; --r >= 0;)e.push(r);
            return e
        }), gt: T(function (e, t, n) {
            for (var r = 0 > n ? n + t : n; ++r < t;)e.push(r);
            return e
        })}}, t.pseudos.nth = t.pseudos.eq;
        for (E in{radio: !0, checkbox: !0, file: !0, password: !0, image: !0})t.pseudos[E] = be(E);
        for (E in{submit: !0, reset: !0})t.pseudos[E] = ve(E);
        function Z() {
        };
        Z.prototype = t.filters = t.pseudos, t.setFilters = new Z;
        function O(e, n) {
            var a, o, u, s, r, c, l, d = re[e + " "];
            if (d)return n ? 0 : d.slice(0);
            r = e, c = [], l = t.preFilter;
            while (r) {
                (!a || (o = xe.exec(r))) && (o && (r = r.slice(o[0].length) || r), c.push(u = [])), a = !1, (o = Ee.exec(r)) && (a = o.shift(), u.push({value: a, type: o[0].replace(B, " ")}), r = r.slice(a.length));
                for (s in t.filter)!(o = L[s].exec(r)) || l[s] && !(o = l[s](o)) || (a = o.shift(), u.push({value: a, type: s, matches: o}), r = r.slice(a.length));
                if (!a)break
            }
            ;
            return n ? r.length : r ? i.error(e) : re(e, c).slice(0)
        };
        function F(e) {
            for (var t = 0, r = e.length, n = ""; r > t; t++)n += e[t].value;
            return n
        };
        function Y(e, t, n) {
            var r = t.dir, i = n && "parentNode" === r, o = de++;
            return t.first ? function (t, n, o) {
                while (t = t[r])if (1 === t.nodeType || i)return e(t, n, o)
            } : function (t, n, s) {
                var l, c, u = [f, o];
                if (s) {
                    while (t = t[r])if ((1 === t.nodeType || i) && e(t, n, s))return!0
                } else while (t = t[r])if (1 === t.nodeType || i) {
                    if (c = t[a] || (t[a] = {}), (l = c[r]) && l[0] === f && l[1] === o)return u[2] = l[2];
                    if (c[r] = u, u[2] = e(t, n, s))return!0
                }
            }
        };
        function z(e) {
            return e.length > 1 ? function (t, n, r) {
                var i = e.length;
                while (i--)if (!e[i](t, n, r))return!1;
                return!0
            } : e[0]
        };
        function q(e, t, n, r, i) {
            for (var a, s = [], o = 0, u = e.length, l = null != t; u > o; o++)(a = e[o]) && (!n || n(a, r, i)) && (s.push(a), l && t.push(o));
            return s
        };
        function I(e, t, n, r, i, o) {
            return r && !r[a] && (r = I(r)), i && !i[a] && (i = I(i, o)), u(function (o, a, s, l) {
                var d, c, f, g = [], h = [], v = a.length, y = o || me(t || "*", s.nodeType ? [s] : s, []), p = !e || !o && t ? y : q(y, g, e, s, l), u = n ? i || (o ? e : v || r) ? [] : a : p;
                if (n && n(p, u, s, l), r) {
                    d = q(u, h), r(d, [], s, l), c = d.length;
                    while (c--)(f = d[c]) && (u[h[c]] = !(p[h[c]] = f))
                }
                ;
                if (o) {
                    if (i || e) {
                        if (i) {
                            d = [], c = u.length;
                            while (c--)(f = u[c]) && d.push(p[c] = f);
                            i(null, u = [], d, l)
                        }
                        ;
                        c = u.length;
                        while (c--)(f = u[c]) && (d = i ? C.call(o, f) : g[c]) > -1 && (o[d] = !(a[d] = f))
                    }
                } else u = q(u === a ? u.splice(v, u.length) : u), i ? i(null, a, u, l) : m.apply(a, u)
            })
        };
        function X(e) {
            for (var u, i, r, s = e.length, l = t.relative[e[0].type], c = l || t.relative[" "], n = l ? 1 : 0, d = Y(function (e) {
                return e === u
            }, c, !0), f = Y(function (e) {
                return C.call(u, e) > -1
            }, c, !0), o = [function (e, t, n) {
                return!l && (n || t !== M) || ((u = t).nodeType ? d(e, t, n) : f(e, t, n))
            }]; s > n; n++)if (i = t.relative[e[n].type])o = [Y(z(o), i)]; else {
                if (i = t.filter[e[n].type].apply(null, e[n].matches), i[a]) {
                    for (r = ++n; s > r; r++)if (t.relative[e[r].type])break;
                    return I(n > 1 && z(o), n > 1 && F(e.slice(0, n - 1).concat({value: " " === e[n - 2].type ? "*" : ""})).replace(B, "$1"), i, r > n && X(e.slice(n, r)), s > r && X(e = e.slice(r)), s > r && F(e))
                }
                ;
                o.push(i)
            }
            ;
            return z(o)
        };
        function fe(e, n) {
            var r = n.length > 0, a = e.length > 0, o = function (o, l, u, c, d) {
                var h, b, y, v = 0, p = "0", x = o && [], g = [], T = M, C = o || a && t.find.TAG("*", d), w = f += null == T ? 1 : Math.random() || .1, N = C.length;
                for (d && (M = l !== s && l); p !== N && null != (h = C[p]); p++) {
                    if (a && h) {
                        b = 0;
                        while (y = e[b++])if (y(h, l, u)) {
                            c.push(h);
                            break
                        }
                        ;
                        d && (f = w)
                    }
                    ;
                    r && ((h = !y && h) && v--, o && x.push(h))
                }
                ;
                if (v += p, r && p !== v) {
                    b = 0;
                    while (y = n[b++])y(x, g, l, u);
                    if (o) {
                        if (v > 0)while (p--)x[p] || g[p] || (g[p] = Ce.call(c));
                        g = q(g)
                    }
                    ;
                    m.apply(c, g), d && !o && g.length > 0 && v + n.length > 1 && i.uniqueSort(c)
                }
                ;
                return d && (f = w, M = T), x
            };
            return r ? u(o) : o
        };
        V = i.compile = function (e, t) {
            var r, i = [], o = [], n = te[e + " "];
            if (!n) {
                t || (t = O(e)), r = t.length;
                while (r--)n = X(t[r]), n[a] ? i.push(n) : o.push(n);
                n = te(e, fe(o, i))
            }
            ;
            return n
        };
        function me(e, t, n) {
            for (var r = 0, o = t.length; o > r; r++)i(e, t[r], n);
            return n
        };
        function he(e, n, i, o) {
            var u, a, s, c, d, l = O(e);
            if (!o && 1 === l.length) {
                if (a = l[0] = l[0].slice(0), a.length > 2 && "ID" === (s = a[0]).type && r.getById && 9 === n.nodeType && p && t.relative[a[1].type]) {
                    if (n = (t.find.ID(s.matches[0].replace(x, y), n) || [])[0], !n)return i;
                    e = e.slice(a.shift().value.length)
                }
                ;
                u = L.needsContext.test(e) ? 0 : a.length;
                while (u--) {
                    if (s = a[u], t.relative[c = s.type])break;
                    if ((d = t.find[c]) && (o = d(s.matches[0].replace(x, y), U.test(a[0].type) && G(n.parentNode) || n))) {
                        if (a.splice(u, 1), e = o.length && F(a), !e)return m.apply(i, o), i;
                        break
                    }
                }
            }
            ;
            return V(e, l)(o, n, !p, i, U.test(e) && G(n.parentNode) || n), i
        };
        return r.sortStable = a.split("").sort(W).join("") === a, r.detectDuplicates = !!k, b(), r.sortDetached = c(function (e) {
            return 1 & e.compareDocumentPosition(s.createElement("div"))
        }), c(function (e) {
            return e.innerHTML = "<a href='#'></a>", "#" === e.firstChild.getAttribute("href")
        }) || P("type|href|height|width", function (e, t, n) {
            return n ? void 0 : e.getAttribute(t, "type" === t.toLowerCase() ? 1 : 2)
        }), r.attributes && c(function (e) {
            return e.innerHTML = "<input/>", e.firstChild.setAttribute("value", ""), "" === e.firstChild.getAttribute("value")
        }) || P("value", function (e, t, n) {
            return n || "input" !== e.nodeName.toLowerCase() ? void 0 : e.defaultValue
        }), c(function (e) {
            return null == e.getAttribute("disabled")
        }) || P(J, function (e, t, n) {
            var r;
            return n ? void 0 : e[t] === !0 ? t.toLowerCase() : (r = e.getAttributeNode(t)) && r.specified ? r.value : null
        }), i
    }(t);
    e.find = k, e.expr = k.selectors, e.expr[":"] = e.expr.pseudos, e.unique = k.uniqueSort, e.text = k.getText, e.isXMLDoc = k.isXML, e.contains = k.contains;
    var xe = e.expr.match.needsContext, Le = /^<(\w+)\s*\/?>(?:<\/\1>|)$/, Tt = /^.[^:#\[\.,]*$/;

    function le(t, n, r) {
        if (e.isFunction(n))return e.grep(t, function (e, t) {
            return!!n.call(e, t, e) !== r
        });
        if (n.nodeType)return e.grep(t, function (e) {
            return e === n !== r
        });
        if ("string" == typeof n) {
            if (Tt.test(n))return e.filter(n, t, r);
            n = e.filter(n, t)
        }
        ;
        return e.grep(t, function (t) {
            return e.inArray(t, n) >= 0 !== r
        })
    };
    e.filter = function (t, n, r) {
        var i = n[0];
        return r && (t = ":not(" + t + ")"), 1 === n.length && 1 === i.nodeType ? e.find.matchesSelector(i, t) ? [i] : [] : e.find.matches(t, e.grep(n, function (e) {
            return 1 === e.nodeType
        }))
    }, e.fn.extend({find: function (t) {
        var n, r = [], o = this, i = o.length;
        if ("string" != typeof t)return this.pushStack(e(t).filter(function () {
            for (n = 0; i > n; n++)if (e.contains(o[n], this))return!0
        }));
        for (n = 0; i > n; n++)e.find(t, o[n], r);
        return r = this.pushStack(i > 1 ? e.unique(r) : r), r.selector = this.selector ? this.selector + " " + t : t, r
    }, filter: function (e) {
        return this.pushStack(le(this, e || [], !1))
    }, not: function (e) {
        return this.pushStack(le(this, e || [], !0))
    }, is: function (t) {
        return!!le(this, "string" == typeof t && xe.test(t) ? e(t) : t || [], !1).length
    }});
    var L, i = t.document, dn = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/, cn = e.fn.init = function (t, n) {
        var r, o;
        if (!t)return this;
        if ("string" == typeof t) {
            if (r = "<" === t.charAt(0) && ">" === t.charAt(t.length - 1) && t.length >= 3 ? [null, t, null] : dn.exec(t), !r || !r[1] && n)return!n || n.jquery ? (n || L).find(t) : this.constructor(n).find(t);
            if (r[1]) {
                if (n = n instanceof e ? n[0] : n, e.merge(this, e.parseHTML(r[1], n && n.nodeType ? n.ownerDocument || n : i, !0)), Le.test(r[1]) && e.isPlainObject(n))for (r in n)e.isFunction(this[r]) ? this[r](n[r]) : this.attr(r, n[r]);
                return this
            }
            ;
            if (o = i.getElementById(r[2]), o && o.parentNode) {
                if (o.id !== r[2])return L.find(t);
                this.length = 1, this[0] = o
            }
            ;
            return this.context = i, this.selector = t, this
        }
        ;
        return t.nodeType ? (this.context = this[0] = t, this.length = 1, this) : e.isFunction(t) ? "undefined" != typeof L.ready ? L.ready(t) : t(e) : (void 0 !== t.selector && (this.selector = t.selector, this.context = t.context), e.makeArray(t, this))
    };
    cn.prototype = e.fn, L = e(i);
    var ln = /^(?:parents|prev(?:Until|All))/, un = {children: !0, contents: !0, next: !0, prev: !0};
    e.extend({dir: function (t, n, r) {
        var o = [], i = t[n];
        while (i && 9 !== i.nodeType && (void 0 === r || 1 !== i.nodeType || !e(i).is(r)))1 === i.nodeType && o.push(i), i = i[n];
        return o
    }, sibling: function (e, t) {
        for (var n = []; e; e = e.nextSibling)1 === e.nodeType && e !== t && n.push(e);
        return n
    }}), e.fn.extend({has: function (t) {
        var n, r = e(t, this), i = r.length;
        return this.filter(function () {
            for (n = 0; i > n; n++)if (e.contains(this, r[n]))return!0
        })
    }, closest: function (t, n) {
        for (var r, o = 0, s = this.length, i = [], a = xe.test(t) || "string" != typeof t ? e(t, n || this.context) : 0; s > o; o++)for (r = this[o]; r && r !== n; r = r.parentNode)if (r.nodeType < 11 && (a ? a.index(r) > -1 : 1 === r.nodeType && e.find.matchesSelector(r, t))) {
            i.push(r);
            break
        }
        ;
        return this.pushStack(i.length > 1 ? e.unique(i) : i)
    }, index: function (t) {
        return t ? "string" == typeof t ? e.inArray(this[0], e(t)) : e.inArray(t.jquery ? t[0] : t, this) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
    }, add: function (t, n) {
        return this.pushStack(e.unique(e.merge(this.get(), e(t, n))))
    }, addBack: function (e) {
        return this.add(null == e ? this.prevObject : this.prevObject.filter(e))
    }});
    function de(e, t) {
        do e = e[t]; while (e && 1 !== e.nodeType);
        return e
    };
    e.each({parent: function (e) {
        var t = e.parentNode;
        return t && 11 !== t.nodeType ? t : null
    }, parents: function (t) {
        return e.dir(t, "parentNode")
    }, parentsUntil: function (t, n, r) {
        return e.dir(t, "parentNode", r)
    }, next: function (e) {
        return de(e, "nextSibling")
    }, prev: function (e) {
        return de(e, "previousSibling")
    }, nextAll: function (t) {
        return e.dir(t, "nextSibling")
    }, prevAll: function (t) {
        return e.dir(t, "previousSibling")
    }, nextUntil: function (t, n, r) {
        return e.dir(t, "nextSibling", r)
    }, prevUntil: function (t, n, r) {
        return e.dir(t, "previousSibling", r)
    }, siblings: function (t) {
        return e.sibling((t.parentNode || {}).firstChild, t)
    }, children: function (t) {
        return e.sibling(t.firstChild)
    }, contents: function (t) {
        return e.nodeName(t, "iframe") ? t.contentDocument || t.contentWindow.document : e.merge([], t.childNodes)
    }}, function (t, n) {
        e.fn[t] = function (r, i) {
            var o = e.map(this, n, r);
            return"Until" !== t.slice(-5) && (i = r), i && "string" == typeof i && (o = e.filter(i, o)), this.length > 1 && (un[t] || (o = e.unique(o)), ln.test(t) && (o = o.reverse())), this.pushStack(o)
        }
    });
    var c = /\S+/g, fe = {};

    function pn(t) {
        var n = fe[t] = {};
        return e.each(t.match(c) || [], function (e, t) {
            n[t] = !0
        }), n
    };
    e.Callbacks = function (t) {
        t = "string" == typeof t ? fe[t] || pn(t) : e.extend({}, t);
        var s, i, c, a, o, d, n = [], r = !t.once && [], u = function (e) {
            for (i = t.memory && e, c = !0, o = d || 0, d = 0, a = n.length, s = !0; n && a > o; o++)if (n[o].apply(e[0], e[1]) === !1 && t.stopOnFalse) {
                i = !1;
                break
            }
            ;
            s = !1, n && (r ? r.length && u(r.shift()) : i ? n = [] : l.disable())
        }, l = {add: function () {
            if (n) {
                var r = n.length;
                !function o(r) {
                    e.each(r, function (r, i) {
                        var a = e.type(i);
                        "function" === a ? t.unique && l.has(i) || n.push(i) : i && i.length && "string" !== a && o(i)
                    })
                }(arguments), s ? a = n.length : i && (d = r, u(i))
            }
            ;
            return this
        }, remove: function () {
            return n && e.each(arguments, function (t, r) {
                var i;
                while ((i = e.inArray(r, n, i)) > -1)n.splice(i, 1), s && (a >= i && a--, o >= i && o--)
            }), this
        }, has: function (t) {
            return t ? e.inArray(t, n) > -1 : !(!n || !n.length)
        }, empty: function () {
            return n = [], a = 0, this
        }, disable: function () {
            return n = r = i = void 0, this
        }, disabled: function () {
            return!n
        }, lock: function () {
            return r = void 0, i || l.disable(), this
        }, locked: function () {
            return!r
        }, fireWith: function (e, t) {
            return!n || c && !r || (t = t || [], t = [e, t.slice ? t.slice() : t], s ? r.push(t) : u(t)), this
        }, fire: function () {
            return l.fireWith(this, arguments), this
        }, fired: function () {
            return!!c
        }};
        return l
    }, e.extend({Deferred: function (t) {
        var i = [
            ["resolve", "done", e.Callbacks("once memory"), "resolved"],
            ["reject", "fail", e.Callbacks("once memory"), "rejected"],
            ["notify", "progress", e.Callbacks("memory")]
        ], o = "pending", r = {state: function () {
            return o
        }, always: function () {
            return n.done(arguments).fail(arguments), this
        }, then: function () {
            var t = arguments;
            return e.Deferred(function (o) {
                e.each(i, function (i, a) {
                    var s = e.isFunction(t[i]) && t[i];
                    n[a[1]](function () {
                        var t = s && s.apply(this, arguments);
                        t && e.isFunction(t.promise) ? t.promise().done(o.resolve).fail(o.reject).progress(o.notify) : o[a[0] + "With"](this === r ? o.promise() : this, s ? [t] : arguments)
                    })
                }), t = null
            }).promise()
        }, promise: function (t) {
            return null != t ? e.extend(t, r) : r
        }}, n = {};
        return r.pipe = r.then, e.each(i, function (e, t) {
            var a = t[2], s = t[3];
            r[t[1]] = a.add, s && a.add(function () {
                o = s
            }, i[1 ^ e][2].disable, i[2][2].lock), n[t[0]] = function () {
                return n[t[0] + "With"](this === n ? r : this, arguments), this
            }, n[t[0] + "With"] = a.fireWith
        }), r.promise(n), t && t.call(n, n), n
    }, when: function (t) {
        var n = 0, i = p.call(arguments), r = i.length, a = 1 !== r || t && e.isFunction(t.promise) ? r : 0, o = 1 === a ? t : e.Deferred(), u = function (e, t, n) {
            return function (r) {
                t[e] = this, n[e] = arguments.length > 1 ? p.call(arguments) : r, n === l ? o.notifyWith(t, n) : --a || o.resolveWith(t, n)
            }
        }, l, c, s;
        if (r > 1)for (l = new Array(r), c = new Array(r), s = new Array(r); r > n; n++)i[n] && e.isFunction(i[n].promise) ? i[n].promise().done(u(n, s, i)).fail(o.reject).progress(u(n, c, l)) : --a;
        return a || o.resolveWith(s, i), o.promise()
    }});
    var q;
    e.fn.ready = function (t) {
        return e.ready.promise().done(t), this
    }, e.extend({isReady: !1, readyWait: 1, holdReady: function (t) {
        t ? e.readyWait++ : e.ready(!0)
    }, ready: function (t) {
        if (t === !0 ? !--e.readyWait : !e.isReady) {
            if (!i.body)return setTimeout(e.ready);
            e.isReady = !0, t !== !0 && --e.readyWait > 0 || (q.resolveWith(i, [e]), e.fn.trigger && e(i).trigger("ready").off("ready"))
        }
    }});
    function rt() {
        i.addEventListener ? (i.removeEventListener("DOMContentLoaded", d, !1), t.removeEventListener("load", d, !1)) : (i.detachEvent("onreadystatechange", d), t.detachEvent("onload", d))
    };
    function d() {
        (i.addEventListener || "load" === event.type || "complete" === i.readyState) && (rt(), e.ready())
    };
    e.ready.promise = function (n) {
        if (!q)if (q = e.Deferred(), "complete" === i.readyState)setTimeout(e.ready); else if (i.addEventListener)i.addEventListener("DOMContentLoaded", d, !1), t.addEventListener("load", d, !1); else {
            i.attachEvent("onreadystatechange", d), t.attachEvent("onload", d);
            var o = !1;
            try {
                o = null == t.frameElement && i.documentElement
            } catch (r) {
            }
            ;
            o && o.doScroll && !function a() {
                if (!e.isReady) {
                    try {
                        o.doScroll("left")
                    } catch (t) {
                        return setTimeout(a, 50)
                    }
                    ;
                    rt(), e.ready()
                }
            }()
        }
        ;
        return q.promise(n)
    };
    var u = "undefined", ye;
    for (ye in e(r))break;
    r.ownLast = "0" !== ye, r.inlineBlockNeedsLayout = !1, e(function () {
        var t, e, n = i.getElementsByTagName("body")[0];
        n && (t = i.createElement("div"), t.style.cssText = "border:0;width:0;height:0;position:absolute;top:0;left:-9999px;margin-top:1px", e = i.createElement("div"), n.appendChild(t).appendChild(e), typeof e.style.zoom !== u && (e.style.cssText = "border:0;margin:0;width:1px;padding:1px;display:inline;zoom:1", (r.inlineBlockNeedsLayout = 3 === e.offsetWidth) && (n.style.zoom = 1)), n.removeChild(t), t = e = null)
    }), function () {
        var t = i.createElement("div");
        if (null == r.deleteExpando) {
            r.deleteExpando = !0;
            try {
                delete t.test
            } catch (e) {
                r.deleteExpando = !1
            }
        }
        ;
        t = null
    }(), e.acceptData = function (t) {
        var n = e.noData[(t.nodeName + " ").toLowerCase()], r = +t.nodeType || 1;
        return 1 !== r && 9 !== r ? !1 : !n || n !== !0 && t.getAttribute("classid") === n
    };
    var Xt = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/, Vt = /([A-Z])/g;

    function ce(t, n, r) {
        if (void 0 === r && 1 === t.nodeType) {
            var o = "data-" + n.replace(Vt, "-$1").toLowerCase();
            if (r = t.getAttribute(o), "string" == typeof r) {
                try {
                    r = "true" === r ? !0 : "false" === r ? !1 : "null" === r ? null : +r + "" === r ? +r : Xt.test(r) ? e.parseJSON(r) : r
                } catch (i) {
                }
                ;
                e.data(t, n, r)
            } else r = void 0
        }
        ;
        return r
    };
    function se(t) {
        var n;
        for (n in t)if (("data" !== n || !e.isEmptyObject(t[n])) && "toJSON" !== n)return!1;
        return!0
    };
    function we(t, n, r, i) {
        if (e.acceptData(t)) {
            var l, a, u = e.expando, c = t.nodeType, s = c ? e.cache : t, o = c ? t[u] : t[u] && u;
            if (o && s[o] && (i || s[o].data) || void 0 !== r || "string" != typeof n)return o || (o = c ? t[u] = h.pop() || e.guid++ : u), s[o] || (s[o] = c ? {} : {toJSON: e.noop}), ("object" == typeof n || "function" == typeof n) && (i ? s[o] = e.extend(s[o], n) : s[o].data = e.extend(s[o].data, n)), a = s[o], i || (a.data || (a.data = {}), a = a.data), void 0 !== r && (a[e.camelCase(n)] = r), "string" == typeof n ? (l = a[n], null == l && (l = a[e.camelCase(n)])) : l = a, l
        }
    };
    function Ee(t, n, i) {
        if (e.acceptData(t)) {
            var s, l, u = t.nodeType, o = u ? e.cache : t, a = u ? t[e.expando] : e.expando;
            if (o[a]) {
                if (n && (s = i ? o[a] : o[a].data)) {
                    e.isArray(n) ? n = n.concat(e.map(n, e.camelCase)) : n in s ? n = [n] : (n = e.camelCase(n), n = n in s ? [n] : n.split(" ")), l = n.length;
                    while (l--)delete s[n[l]];
                    if (i ? !se(s) : !e.isEmptyObject(s))return
                }
                (i || (delete o[a].data, se(o[a]))) && (u ? e.cleanData([t], !0) : r.deleteExpando || o != o.window ? delete o[a] : o[a] = null)
            }
        }
    };
    e.extend({cache: {}, noData: {"applet ": !0, "embed ": !0, "object ": "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"}, hasData: function (t) {
        return t = t.nodeType ? e.cache[t[e.expando]] : t[e.expando], !!t && !se(t)
    }, data: function (e, t, n) {
        return we(e, t, n)
    }, removeData: function (e, t) {
        return Ee(e, t)
    }, e$: function (e, t, n) {
        return we(e, t, n, !0)
    }, n$: function (e, t) {
        return Ee(e, t, !0)
    }}), e.fn.extend({data: function (t, n) {
        var a, i, o, r = this[0], s = r && r.attributes;
        if (void 0 === t) {
            if (this.length && (o = e.data(r), 1 === r.nodeType && !e.e$(r, "parsedAttrs"))) {
                a = s.length;
                while (a--)i = s[a].name, 0 === i.indexOf("data-") && (i = e.camelCase(i.slice(5)), ce(r, i, o[i]));
                e.e$(r, "parsedAttrs", !0)
            }
            ;
            return o
        }
        ;
        return"object" == typeof t ? this.each(function () {
            e.data(this, t)
        }) : arguments.length > 1 ? this.each(function () {
            e.data(this, t, n)
        }) : r ? ce(r, t, e.data(r, t)) : void 0
    }, removeData: function (t) {
        return this.each(function () {
            e.removeData(this, t)
        })
    }}), e.extend({queue: function (t, n, r) {
        var i;
        return t ? (n = (n || "fx") + "queue", i = e.e$(t, n), r && (!i || e.isArray(r) ? i = e.e$(t, n, e.makeArray(r)) : i.push(r)), i || []) : void 0
    }, dequeue: function (t, n) {
        n = n || "fx";
        var i = e.queue(t, n), a = i.length, r = i.shift(), o = e.r$(t, n), s = function () {
            e.dequeue(t, n)
        };
        "inprogress" === r && (r = i.shift(), a--), r && ("fx" === n && i.unshift("inprogress"), delete o.stop, r.call(t, s, o)), !a && o && o.empty.fire()
    }, r$: function (t, n) {
        var r = n + "queueHooks";
        return e.e$(t, r) || e.e$(t, r, {empty: e.Callbacks("once memory").add(function () {
            e.n$(t, n + "queue"), e.n$(t, r)
        })})
    }}), e.fn.extend({queue: function (t, n) {
        var r = 2;
        return"string" != typeof t && (n = t, t = "fx", r--), arguments.length < r ? e.queue(this[0], t) : void 0 === n ? this : this.each(function () {
            var r = e.queue(this, t, n);
            e.r$(this, t), "fx" === t && "inprogress" !== r[0] && e.dequeue(this, t)
        })
    }, dequeue: function (t) {
        return this.each(function () {
            e.dequeue(this, t)
        })
    }, clearQueue: function (e) {
        return this.queue(e || "fx", [])
    }, promise: function (t, n) {
        var r, s = 1, l = e.Deferred(), i = this, a = this.length, o = function () {
            --s || l.resolveWith(i, [i])
        };
        "string" != typeof t && (n = t, t = void 0), t = t || "fx";
        while (a--)r = e.e$(i[a], t + "queueHooks"), r && r.empty && (s++, r.empty.add(o));
        return o(), l.promise(n)
    }});
    var R = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source, g = ["Top", "Right", "Bottom", "Left"], D = function (t, n) {
        return t = n || t, "none" === e.css(t, "display") || !e.contains(t.ownerDocument, t)
    }, x = e.access = function (t, n, r, i, o, l, u) {
        var a = 0, c = t.length, s = null == r;
        if ("object" === e.type(r)) {
            o = !0;
            for (a in r)e.access(t, n, a, r[a], !0, l, u)
        } else if (void 0 !== i && (o = !0, e.isFunction(i) || (u = !0), s && (u ? (n.call(t, i), n = null) : (s = n, n = function (t, n, r) {
            return s.call(e(t), r)
        })), n))for (; c > a; a++)n(t[a], r, u ? i : i.call(t[a], a, n(t[a], r)));
        return o ? t : s ? n.call(t) : c ? n(t[0], r) : l
    }, I = /^(?:checkbox|radio)$/i;
    !function () {
        var o = i.createDocumentFragment(), t = i.createElement("div"), n = i.createElement("input");
        if (t.setAttribute("className", "t"), t.innerHTML = "  <link/><table></table><a href='/a'>a</a>", r.leadingWhitespace = 3 === t.firstChild.nodeType, r.tbody = !t.getElementsByTagName("tbody").length, r.htmlSerialize = !!t.getElementsByTagName("link").length, r.html5Clone = "<:nav></:nav>" !== i.createElement("nav").cloneNode(!0).outerHTML, n.type = "checkbox", n.checked = !0, o.appendChild(n), r.appendChecked = n.checked, t.innerHTML = "<textarea>x</textarea>", r.noCloneChecked = !!t.cloneNode(!0).lastChild.defaultValue, o.appendChild(t), t.innerHTML = "<input type='radio' checked='checked' name='t'/>", r.checkClone = t.cloneNode(!0).cloneNode(!0).lastChild.checked, r.noCloneEvent = !0, t.attachEvent && (t.attachEvent("onclick", function () {
            r.noCloneEvent = !1
        }), t.cloneNode(!0).click()), null == r.deleteExpando) {
            r.deleteExpando = !0;
            try {
                delete t.test
            } catch (e) {
                r.deleteExpando = !1
            }
        }
        ;
        o = t = n = null
    }(), function () {
        var e, n, o = i.createElement("div");
        for (e in{submit: !0, change: !0, focusin: !0})n = "on" + e, (r[e + "Bubbles"] = n in t) || (o.setAttribute(n, "t"), r[e + "Bubbles"] = o.attributes[n].expando === !1);
        o = null
    }();
    var G = /^(?:input|select|textarea)$/i, Dt = /^key/, jt = /^(?:mouse|contextmenu)|click/, Fe = /^(?:focusinfocus|focusoutblur)$/, it = /^([^.]*)(?:\.(.+)|)$/;

    function B() {
        return!0
    };
    function C() {
        return!1
    };
    function Ne() {
        try {
            return i.activeElement
        } catch (e) {
        }
    };
    e.event = {global: {}, add: function (t, n, r, i, o) {
        var g, m, v, h, s, l, f, d, a, y, b, p = e.e$(t);
        if (p) {
            r.handler && (h = r, r = h.handler, o = h.selector), r.guid || (r.guid = e.guid++), (m = p.events) || (m = p.events = {}), (l = p.handle) || (l = p.handle = function (t) {
                return typeof e === u || t && e.event.triggered === t.type ? void 0 : e.event.dispatch.apply(l.elem, arguments)
            }, l.elem = t), n = (n || "").match(c) || [""], v = n.length;
            while (v--)g = it.exec(n[v]) || [], a = b = g[1], y = (g[2] || "").split(".").sort(), a && (s = e.event.special[a] || {}, a = (o ? s.delegateType : s.bindType) || a, s = e.event.special[a] || {}, f = e.extend({type: a, origType: b, data: i, handler: r, guid: r.guid, selector: o, needsContext: o && e.expr.match.needsContext.test(o), namespace: y.join(".")}, h), (d = m[a]) || (d = m[a] = [], d.delegateCount = 0, s.setup && s.setup.call(t, i, y, l) !== !1 || (t.addEventListener ? t.addEventListener(a, l, !1) : t.attachEvent && t.attachEvent("on" + a, l))), s.add && (s.add.call(t, f), f.handler.guid || (f.handler.guid = r.guid)), o ? d.splice(d.delegateCount++, 0, f) : d.push(f), e.event.global[a] = !0);
            t = null
        }
    }, remove: function (t, n, r, i, o) {
        var m, s, u, y, h, p, l, d, a, g, v, f = e.hasData(t) && e.e$(t);
        if (f && (p = f.events)) {
            n = (n || "").match(c) || [""], h = n.length;
            while (h--)if (u = it.exec(n[h]) || [], a = v = u[1], g = (u[2] || "").split(".").sort(), a) {
                l = e.event.special[a] || {}, a = (i ? l.delegateType : l.bindType) || a, d = p[a] || [], u = u[2] && new RegExp("(^|\\.)" + g.join("\\.(?:.*\\.|)") + "(\\.|$)"), y = m = d.length;
                while (m--)s = d[m], !o && v !== s.origType || r && r.guid !== s.guid || u && !u.test(s.namespace) || i && i !== s.selector && ("**" !== i || !s.selector) || (d.splice(m, 1), s.selector && d.delegateCount--, l.remove && l.remove.call(t, s));
                y && !d.length && (l.teardown && l.teardown.call(t, g, f.handle) !== !1 || e.removeEvent(t, a, f.handle), delete p[a])
            } else for (a in p)e.event.remove(t, a + n[h], r, i, !0);
            e.isEmptyObject(p) && (delete f.handle, e.n$(t, "events"))
        }
    }, trigger: function (n, r, o, a) {
        var f, p, u, g, d, c, y, m = [o || i], l = E.call(n, "type") ? n.type : n, h = E.call(n, "namespace") ? n.namespace.split(".") : [];
        if (u = c = o = o || i, 3 !== o.nodeType && 8 !== o.nodeType && !Fe.test(l + e.event.triggered) && (l.indexOf(".") >= 0 && (h = l.split("."), l = h.shift(), h.sort()), p = l.indexOf(":") < 0 && "on" + l, n = n[e.expando] ? n : new e.Event(l, "object" == typeof n && n), n.isTrigger = a ? 2 : 3, n.namespace = h.join("."), n.namespace_re = n.namespace ? new RegExp("(^|\\.)" + h.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, n.result = void 0, n.target || (n.target = o), r = null == r ? [n] : e.makeArray(r, [n]), d = e.event.special[l] || {}, a || !d.trigger || d.trigger.apply(o, r) !== !1)) {
            if (!a && !d.noBubble && !e.isWindow(o)) {
                for (g = d.delegateType || l, Fe.test(g + l) || (u = u.parentNode); u; u = u.parentNode)m.push(u), c = u;
                c === (o.ownerDocument || i) && m.push(c.defaultView || c.parentWindow || t)
            }
            ;
            y = 0;
            while ((u = m[y++]) && !n.isPropagationStopped())n.type = y > 1 ? g : d.bindType || l, f = (e.e$(u, "events") || {})[n.type] && e.e$(u, "handle"), f && f.apply(u, r), f = p && u[p], f && f.apply && e.acceptData(u) && (n.result = f.apply(u, r), n.result === !1 && n.preventDefault());
            if (n.type = l, !a && !n.isDefaultPrevented() && (!d.t$ || d.t$.apply(m.pop(), r) === !1) && e.acceptData(o) && p && o[l] && !e.isWindow(o)) {
                c = o[p], c && (o[p] = null), e.event.triggered = l;
                try {
                    o[l]()
                } catch (s) {
                }
                ;
                e.event.triggered = void 0, c && (o[p] = c)
            }
            ;
            return n.result
        }
    }, dispatch: function (t) {
        t = e.event.fix(t);
        var a, o, n, i, u, l = [], s = p.call(arguments), c = (e.e$(this, "events") || {})[t.type] || [], r = e.event.special[t.type] || {};
        if (s[0] = t, t.delegateTarget = this, !r.preDispatch || r.preDispatch.call(this, t) !== !1) {
            l = e.event.handlers.call(this, t, c), a = 0;
            while ((i = l[a++]) && !t.isPropagationStopped()) {
                t.currentTarget = i.elem, u = 0;
                while ((n = i.handlers[u++]) && !t.isImmediatePropagationStopped())(!t.namespace_re || t.namespace_re.test(n.namespace)) && (t.handleObj = n, t.data = n.data, o = ((e.event.special[n.origType] || {}).handle || n.handler).apply(i.elem, s), void 0 !== o && (t.result = o) === !1 && (t.preventDefault(), t.stopPropagation()))
            }
            ;
            return r.postDispatch && r.postDispatch.call(this, t), t.result
        }
    }, handlers: function (t, n) {
        var o, l, i, s, u = [], a = n.delegateCount, r = t.target;
        if (a && r.nodeType && (!t.button || "click" !== t.type))for (; r != this; r = r.parentNode || this)if (1 === r.nodeType && (r.disabled !== !0 || "click" !== t.type)) {
            for (i = [], s = 0; a > s; s++)l = n[s], o = l.selector + " ", void 0 === i[o] && (i[o] = l.needsContext ? e(o, this).index(r) >= 0 : e.find(o, this, null, [r]).length), i[o] && i.push(l);
            i.length && u.push({elem: r, handlers: i})
        }
        ;
        return a < n.length && u.push({elem: this, handlers: n.slice(a)}), u
    }, fix: function (t) {
        if (t[e.expando])return t;
        var s, l, a, r = t.type, o = t, n = this.fixHooks[r];
        n || (this.fixHooks[r] = n = jt.test(r) ? this.mouseHooks : Dt.test(r) ? this.keyHooks : {}), a = n.props ? this.props.concat(n.props) : this.props, t = new e.Event(o), s = a.length;
        while (s--)l = a[s], t[l] = o[l];
        return t.target || (t.target = o.srcElement || i), 3 === t.target.nodeType && (t.target = t.target.parentNode), t.metaKey = !!t.metaKey, n.filter ? n.filter(t, o) : t
    }, props: "altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "), fixHooks: {}, keyHooks: {props: "char charCode key keyCode".split(" "), filter: function (e, t) {
        return null == e.which && (e.which = null != t.charCode ? t.charCode : t.keyCode), e
    }}, mouseHooks: {props: "button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "), filter: function (e, t) {
        var r, s, n, o = t.button, a = t.fromElement;
        return null == e.pageX && null != t.clientX && (s = e.target.ownerDocument || i, n = s.documentElement, r = s.body, e.pageX = t.clientX + (n && n.scrollLeft || r && r.scrollLeft || 0) - (n && n.clientLeft || r && r.clientLeft || 0), e.pageY = t.clientY + (n && n.scrollTop || r && r.scrollTop || 0) - (n && n.clientTop || r && r.clientTop || 0)), !e.relatedTarget && a && (e.relatedTarget = a === e.target ? t.toElement : a), e.which || void 0 === o || (e.which = 1 & o ? 1 : 2 & o ? 3 : 4 & o ? 2 : 0), e
    }}, special: {load: {noBubble: !0}, focus: {trigger: function () {
        if (this !== Ne() && this.focus)try {
            return this.focus(), !1
        } catch (e) {
        }
    }, delegateType: "focusin"}, blur: {trigger: function () {
        return this === Ne() && this.blur ? (this.blur(), !1) : void 0
    }, delegateType: "focusout"}, click: {trigger: function () {
        return e.nodeName(this, "input") && "checkbox" === this.type && this.click ? (this.click(), !1) : void 0
    }, t$: function (t) {
        return e.nodeName(t.target, "a")
    }}, beforeunload: {postDispatch: function (e) {
        void 0 !== e.result && (e.originalEvent.returnValue = e.result)
    }}}, simulate: function (t, n, r, i) {
        var o = e.extend(new e.Event, r, {type: t, isSimulated: !0, originalEvent: {}});
        i ? e.event.trigger(o, null, n) : e.event.dispatch.call(n, o), o.isDefaultPrevented() && r.preventDefault()
    }}, e.removeEvent = i.removeEventListener ? function (e, t, n) {
        e.removeEventListener && e.removeEventListener(t, n, !1)
    } : function (e, t, n) {
        var r = "on" + t;
        e.detachEvent && (typeof e[r] === u && (e[r] = null), e.detachEvent(r, n))
    }, e.Event = function (t, n) {
        return this instanceof e.Event ? (t && t.type ? (this.originalEvent = t, this.type = t.type, this.isDefaultPrevented = t.defaultPrevented || void 0 === t.defaultPrevented && (t.returnValue === !1 || t.getPreventDefault && t.getPreventDefault()) ? B : C) : this.type = t, n && e.extend(this, n), this.timeStamp = t && t.timeStamp || e.now(), void(this[e.expando] = !0)) : new e.Event(t, n)
    }, e.Event.prototype = {isDefaultPrevented: C, isPropagationStopped: C, isImmediatePropagationStopped: C, preventDefault: function () {
        var e = this.originalEvent;
        this.isDefaultPrevented = B, e && (e.preventDefault ? e.preventDefault() : e.returnValue = !1)
    }, stopPropagation: function () {
        var e = this.originalEvent;
        this.isPropagationStopped = B, e && (e.stopPropagation && e.stopPropagation(), e.cancelBubble = !0)
    }, stopImmediatePropagation: function () {
        this.isImmediatePropagationStopped = B, this.stopPropagation()
    }}, e.each({mouseenter: "mouseover", mouseleave: "mouseout"}, function (t, n) {
        e.event.special[t] = {delegateType: n, bindType: n, handle: function (t) {
            var a, o = this, r = t.relatedTarget, i = t.handleObj;
            return(!r || r !== o && !e.contains(o, r)) && (t.type = i.origType, a = i.handler.apply(this, arguments), t.type = n), a
        }}
    }), r.submitBubbles || (e.event.special.submit = {setup: function () {
        return e.nodeName(this, "form") ? !1 : void e.event.add(this, "click._submit keypress._submit", function (t) {
            var r = t.target, n = e.nodeName(r, "input") || e.nodeName(r, "button") ? r.form : void 0;
            n && !e.e$(n, "submitBubbles") && (e.event.add(n, "submit._submit", function (e) {
                e.a$ = !0
            }), e.e$(n, "submitBubbles", !0))
        })
    }, postDispatch: function (t) {
        t.a$ && (delete t.a$, this.parentNode && !t.isTrigger && e.event.simulate("submit", this.parentNode, t, !0))
    }, teardown: function () {
        return e.nodeName(this, "form") ? !1 : void e.event.remove(this, "._submit")
    }}), r.changeBubbles || (e.event.special.change = {setup: function () {
        return G.test(this.nodeName) ? (("checkbox" === this.type || "radio" === this.type) && (e.event.add(this, "propertychange._change", function (e) {
            "checked" === e.originalEvent.propertyName && (this.o$ = !0)
        }), e.event.add(this, "click._change", function (t) {
            this.o$ && !t.isTrigger && (this.o$ = !1), e.event.simulate("change", this, t, !0)
        })), !1) : void e.event.add(this, "beforeactivate._change", function (t) {
            var n = t.target;
            G.test(n.nodeName) && !e.e$(n, "changeBubbles") && (e.event.add(n, "change._change", function (t) {
                !this.parentNode || t.isSimulated || t.isTrigger || e.event.simulate("change", this.parentNode, t, !0)
            }), e.e$(n, "changeBubbles", !0))
        })
    }, handle: function (e) {
        var t = e.target;
        return this !== t || e.isSimulated || e.isTrigger || "radio" !== t.type && "checkbox" !== t.type ? e.handleObj.handler.apply(this, arguments) : void 0
    }, teardown: function () {
        return e.event.remove(this, "._change"), !G.test(this.nodeName)
    }}), r.focusinBubbles || e.each({focus: "focusin", blur: "focusout"}, function (t, n) {
        var r = function (t) {
            e.event.simulate(n, t.target, e.event.fix(t), !0)
        };
        e.event.special[n] = {setup: function () {
            var i = this.ownerDocument || this, o = e.e$(i, n);
            o || i.addEventListener(t, r, !0), e.e$(i, n, (o || 0) + 1)
        }, teardown: function () {
            var i = this.ownerDocument || this, o = e.e$(i, n) - 1;
            o ? e.e$(i, n, o) : (i.removeEventListener(t, r, !0), e.n$(i, n))
        }}
    }), e.fn.extend({on: function (t, n, r, i, o) {
        var s, a;
        if ("object" == typeof t) {
            "string" != typeof n && (r = r || n, n = void 0);
            for (s in t)this.on(s, n, r, t[s], o);
            return this
        }
        ;
        if (null == r && null == i ? (i = n, r = n = void 0) : null == i && ("string" == typeof n ? (i = r, r = void 0) : (i = r, r = n, n = void 0)), i === !1)i = C; else if (!i)return this;
        return 1 === o && (a = i, i = function (t) {
            return e().off(t), a.apply(this, arguments)
        }, i.guid = a.guid || (a.guid = e.guid++)), this.each(function () {
            e.event.add(this, t, i, r, n)
        })
    }, one: function (e, t, n, r) {
        return this.on(e, t, n, r, 1)
    }, off: function (t, n, r) {
        var i, o;
        if (t && t.preventDefault && t.handleObj)return i = t.handleObj, e(t.delegateTarget).off(i.namespace ? i.origType + "." + i.namespace : i.origType, i.selector, i.handler), this;
        if ("object" == typeof t) {
            for (o in t)this.off(o, n, t[o]);
            return this
        }
        ;
        return(n === !1 || "function" == typeof n) && (r = n, n = void 0), r === !1 && (r = C), this.each(function () {
            e.event.remove(this, t, r, n)
        })
    }, trigger: function (t, n) {
        return this.each(function () {
            e.event.trigger(t, n, this)
        })
    }, triggerHandler: function (t, n) {
        var r = this[0];
        return r ? e.event.trigger(t, n, r, !0) : void 0
    }});
    function Ae(e) {
        var n = Re.split("|"), t = e.createDocumentFragment();
        if (t.createElement)while (n.length)t.createElement(n.pop());
        return t
    };
    var Re = "abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video", kt = / jQuery\d+="(?:null|\d+)"/g, ze = new RegExp("<(?:" + Re + ")[\\s/>]", "i"), U = /^\s+/, Ze = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi, Ke = /<([\w:]+)/, nt = /<tbody/i, Et = /<|&#?\w+;/, Nt = /<(?:script|style|link)/i, Ct = /checked\s*(?:[^=]|=\s*.checked.)/i, ct = /^$|\/(?:java|ecma)script/i, St = /^true\/(.*)/, At = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g, l = {option: [1, "<select multiple='multiple'>", "</select>"], legend: [1, "<fieldset>", "</fieldset>"], area: [1, "<map>", "</map>"], param: [1, "<object>", "</object>"], thead: [1, "<table>", "</table>"], tr: [2, "<table><tbody>", "</tbody></table>"], col: [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"], td: [3, "<table><tbody><tr>", "</tr></tbody></table>"], t$: r.htmlSerialize ? [0, "", ""] : [1, "X<div>", "</div>"]}, Lt = Ae(i), K = Lt.appendChild(i.createElement("div"));
    l.optgroup = l.option, l.tbody = l.tfoot = l.colgroup = l.caption = l.thead, l.th = l.td;
    function a(t, n) {
        var o, i, s = 0, r = typeof t.getElementsByTagName !== u ? t.getElementsByTagName(n || "*") : typeof t.querySelectorAll !== u ? t.querySelectorAll(n || "*") : void 0;
        if (!r)for (r = [], o = t.childNodes || t; null != (i = o[s]); s++)!n || e.nodeName(i, n) ? r.push(i) : e.merge(r, a(i, n));
        return void 0 === n || n && e.nodeName(t, n) ? e.merge([t], r) : r
    };
    function pt(e) {
        I.test(e.type) && (e.defaultChecked = e.checked)
    };
    function He(t, n) {
        return e.nodeName(t, "table") && e.nodeName(11 !== n.nodeType ? n : n.firstChild, "tr") ? t.getElementsByTagName("tbody")[0] || t.appendChild(t.ownerDocument.createElement("tbody")) : t
    };
    function De(t) {
        return t.type = (null !== e.find.attr(t, "type")) + "/" + t.type, t
    };
    function Me(e) {
        var t = St.exec(e.type);
        return t ? e.type = t[1] : e.removeAttribute("type"), e
    };
    function J(t, n) {
        for (var i, r = 0; null != (i = t[r]); r++)e.e$(i, "globalEval", !n || e.e$(n[r], "globalEval"))
    };
    function ue(t, n) {
        if (1 === n.nodeType && e.hasData(t)) {
            var a, o, s, l = e.e$(t), r = e.e$(n, l), i = l.events;
            if (i) {
                delete r.handle, r.events = {};
                for (a in i)for (o = 0, s = i[a].length; s > o; o++)e.event.add(n, a, i[a][o])
            }
            ;
            r.data && (r.data = e.extend({}, r.data))
        }
    };
    function xt(t, n) {
        var i, a, o;
        if (1 === n.nodeType) {
            if (i = n.nodeName.toLowerCase(), !r.noCloneEvent && n[e.expando]) {
                o = e.e$(n);
                for (a in o.events)e.removeEvent(n, a, o.handle);
                n.removeAttribute(e.expando)
            }
            ;
            "script" === i && n.text !== t.text ? (De(n).text = t.text, Me(n)) : "object" === i ? (n.parentNode && (n.outerHTML = t.outerHTML), r.html5Clone && t.innerHTML && !e.trim(n.innerHTML) && (n.innerHTML = t.innerHTML)) : "input" === i && I.test(t.type) ? (n.defaultChecked = n.checked = t.checked, n.value !== t.value && (n.value = t.value)) : "option" === i ? n.defaultSelected = n.selected = t.defaultSelected : ("input" === i || "textarea" === i) && (n.defaultValue = t.defaultValue)
        }
    };
    e.extend({clone: function (t, n, i) {
        var o, c, l, s, u, d = e.contains(t.ownerDocument, t);
        if (r.html5Clone || e.isXMLDoc(t) || !ze.test("<" + t.nodeName + ">") ? l = t.cloneNode(!0) : (K.innerHTML = t.outerHTML, K.removeChild(l = K.firstChild)), !(r.noCloneEvent && r.noCloneChecked || 1 !== t.nodeType && 11 !== t.nodeType || e.isXMLDoc(t)))for (o = a(l), u = a(t), s = 0; null != (c = u[s]); ++s)o[s] && xt(c, o[s]);
        if (n)if (i)for (u = u || a(t), o = o || a(l), s = 0; null != (c = u[s]); s++)ue(c, o[s]); else ue(t, l);
        return o = a(l, "script"), o.length > 0 && J(o, !d && a(t, "script")), o = u = c = null, l
    }, buildFragment: function (t, n, i, o) {
        for (var c, s, y, u, m, g, h, v = t.length, p = Ae(n), d = [], f = 0; v > f; f++)if (s = t[f], s || 0 === s)if ("object" === e.type(s))e.merge(d, s.nodeType ? [s] : s); else if (Et.test(s)) {
            u = u || p.appendChild(n.createElement("div")), m = (Ke.exec(s) || ["", ""])[1].toLowerCase(), h = l[m] || l.t$, u.innerHTML = h[1] + s.replace(Ze, "<$1></$2>") + h[2], c = h[0];
            while (c--)u = u.lastChild;
            if (!r.leadingWhitespace && U.test(s) && d.push(n.createTextNode(U.exec(s)[0])), !r.tbody) {
                s = "table" !== m || nt.test(s) ? "<table>" !== h[1] || nt.test(s) ? 0 : u : u.firstChild, c = s && s.childNodes.length;
                while (c--)e.nodeName(g = s.childNodes[c], "tbody") && !g.childNodes.length && s.removeChild(g)
            }
            ;
            e.merge(d, u.childNodes), u.textContent = "";
            while (u.firstChild)u.removeChild(u.firstChild);
            u = p.lastChild
        } else d.push(n.createTextNode(s));
        u && p.removeChild(u), r.appendChecked || e.grep(a(d, "input"), pt), f = 0;
        while (s = d[f++])if ((!o || -1 === e.inArray(s, o)) && (y = e.contains(s.ownerDocument, s), u = a(p.appendChild(s), "script"), y && J(u), i)) {
            c = 0;
            while (s = u[c++])ct.test(s.type || "") && i.push(s)
        }
        ;
        return u = null, p
    }, cleanData: function (t, n) {
        for (var i, l, o, s, d = 0, a = e.expando, c = e.cache, p = r.deleteExpando, f = e.event.special; null != (i = t[d]); d++)if ((n || e.acceptData(i)) && (o = i[a], s = o && c[o])) {
            if (s.events)for (l in s.events)f[l] ? e.event.remove(i, l) : e.removeEvent(i, l, s.handle);
            c[o] && (delete c[o], p ? delete i[a] : typeof i.removeAttribute !== u ? i.removeAttribute(a) : i[a] = null, h.push(o))
        }
    }}), e.fn.extend({text: function (t) {
        return x(this, function (t) {
            return void 0 === t ? e.text(this) : this.empty().append((this[0] && this[0].ownerDocument || i).createTextNode(t))
        }, null, t, arguments.length)
    }, append: function () {
        return this.domManip(arguments, function (e) {
            if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                var t = He(this, e);
                t.appendChild(e)
            }
        })
    }, prepend: function () {
        return this.domManip(arguments, function (e) {
            if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                var t = He(this, e);
                t.insertBefore(e, t.firstChild)
            }
        })
    }, before: function () {
        return this.domManip(arguments, function (e) {
            this.parentNode && this.parentNode.insertBefore(e, this)
        })
    }, after: function () {
        return this.domManip(arguments, function (e) {
            this.parentNode && this.parentNode.insertBefore(e, this.nextSibling)
        })
    }, remove: function (t, n) {
        for (var r, o = t ? e.filter(t, this) : this, i = 0; null != (r = o[i]); i++)n || 1 !== r.nodeType || e.cleanData(a(r)), r.parentNode && (n && e.contains(r.ownerDocument, r) && J(a(r, "script")), r.parentNode.removeChild(r));
        return this
    }, empty: function () {
        for (var t, n = 0; null != (t = this[n]); n++) {
            1 === t.nodeType && e.cleanData(a(t, !1));
            while (t.firstChild)t.removeChild(t.firstChild);
            t.options && e.nodeName(t, "select") && (t.options.length = 0)
        }
        ;
        return this
    }, clone: function (t, n) {
        return t = null == t ? !1 : t, n = null == n ? t : n, this.map(function () {
            return e.clone(this, t, n)
        })
    }, html: function (t) {
        return x(this, function (t) {
            var i = this[0] || {}, o = 0, s = this.length;
            if (void 0 === t)return 1 === i.nodeType ? i.innerHTML.replace(kt, "") : void 0;
            if (!("string" != typeof t || Nt.test(t) || !r.htmlSerialize && ze.test(t) || !r.leadingWhitespace && U.test(t) || l[(Ke.exec(t) || ["", ""])[1].toLowerCase()])) {
                t = t.replace(Ze, "<$1></$2>");
                try {
                    for (; s > o; o++)i = this[o] || {}, 1 === i.nodeType && (e.cleanData(a(i, !1)), i.innerHTML = t);
                    i = 0
                } catch (n) {
                }
            }
            ;
            i && this.empty().append(t)
        }, null, t, arguments.length)
    }, replaceWith: function () {
        var t = arguments[0];
        return this.domManip(arguments, function (n) {
            t = this.parentNode, e.cleanData(a(this)), t && t.replaceChild(n, this)
        }), t && (t.length || t.nodeType) ? this : this.remove()
    }, detach: function (e) {
        return this.remove(e, !0)
    }, domManip: function (t, n) {
        t = Se.apply([], t);
        var d, i, f, l, h, s, o = 0, u = this.length, m = this, g = u - 1, c = t[0], p = e.isFunction(c);
        if (p || u > 1 && "string" == typeof c && !r.checkClone && Ct.test(c))return this.each(function (e) {
            var r = m.eq(e);
            p && (t[0] = c.call(this, e, r.html())), r.domManip(t, n)
        });
        if (u && (s = e.buildFragment(t, this[0].ownerDocument, !1, this), d = s.firstChild, 1 === s.childNodes.length && (s = d), d)) {
            for (l = e.map(a(s, "script"), De), f = l.length; u > o; o++)i = s, o !== g && (i = e.clone(i, !0, !0), f && e.merge(l, a(i, "script"))), n.call(this[o], i, o);
            if (f)for (h = l[l.length - 1].ownerDocument, e.map(l, Me), o = 0; f > o; o++)i = l[o], ct.test(i.type || "") && !e.e$(i, "globalEval") && e.contains(h, i) && (i.src ? e.i$ && e.i$(i.src) : e.globalEval((i.text || i.textContent || i.innerHTML || "").replace(At, "")));
            s = d = null
        }
        ;
        return this
    }}), e.each({appendTo: "append", prependTo: "prepend", insertBefore: "before", insertAfter: "after", replaceAll: "replaceWith"}, function (t, n) {
        e.fn[t] = function (t) {
            for (var i, r = 0, a = [], s = e(t), o = s.length - 1; o >= r; r++)i = r === o ? this : this.clone(!0), e(s[r])[n](i), te.apply(a, i.get());
            return this.pushStack(a)
        }
    });
    var j, Pe = {};

    function be(n, r) {
        var i = e(r.createElement(n)).appendTo(r.body), o = t.getDefaultComputedStyle ? t.getDefaultComputedStyle(i[0]).display : e.css(i[0], "display");
        return i.detach(), o
    };
    function pe(t) {
        var r = i, n = Pe[t];
        return n || (n = be(t, r), "none" !== n && n || (j = (j || e("<iframe frameborder='0' width='0' height='0'/>")).appendTo(r.documentElement), r = (j[0].contentWindow || j[0].contentDocument).document, r.write(), r.close(), n = be(t, r), j.detach()), Pe[t] = n), n
    };
    !function () {
        var t, n, e = i.createElement("div"), o = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;padding:0;margin:0;border:0";
        e.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", t = e.getElementsByTagName("a")[0], t.style.cssText = "float:left;opacity:.5", r.opacity = /^0.5/.test(t.style.opacity), r.cssFloat = !!t.style.cssFloat, e.style.backgroundClip = "content-box", e.cloneNode(!0).style.backgroundClip = "", r.clearCloneStyle = "content-box" === e.style.backgroundClip, t = e = null, r.shrinkWrapBlocks = function () {
            var t, r, e, a;
            if (null == n) {
                if (t = i.getElementsByTagName("body")[0], !t)return;
                a = "border:0;width:0;height:0;position:absolute;top:0;left:-9999px", r = i.createElement("div"), e = i.createElement("div"), t.appendChild(r).appendChild(e), n = !1, typeof e.style.zoom !== u && (e.style.cssText = o + ";width:1px;padding:1px;zoom:1", e.innerHTML = "<div></div>", e.firstChild.style.width = "5px", n = 3 !== e.offsetWidth), t.removeChild(r), t = r = e = null
            }
            ;
            return n
        }
    }();
    var Xe = /^margin/, P = new RegExp("^(" + R + ")(?!px)[a-z%]+$", "i"), b, y, Ht = /^(top|right|bottom|left)$/;
    t.getComputedStyle ? (b = function (e) {
        return e.ownerDocument.defaultView.getComputedStyle(e, null)
    }, y = function (t, n, r) {
        var a, s, l, o, i = t.style;
        return r = r || b(t), o = r ? r.getPropertyValue(n) || r[n] : void 0, r && ("" !== o || e.contains(t.ownerDocument, t) || (o = e.style(t, n)), P.test(o) && Xe.test(n) && (a = i.width, s = i.minWidth, l = i.maxWidth, i.minWidth = i.maxWidth = i.width = o, o = r.width, i.width = a, i.minWidth = s, i.maxWidth = l)), void 0 === o ? o : o + ""
    }) : i.documentElement.currentStyle && (b = function (e) {
        return e.currentStyle
    }, y = function (e, t, n) {
        var s, o, a, r, i = e.style;
        return n = n || b(e), r = n ? n[t] : void 0, null == r && i && i[t] && (r = i[t]), P.test(r) && !Ht.test(t) && (s = i.left, o = e.runtimeStyle, a = o && o.left, a && (o.left = e.currentStyle.left), i.left = "fontSize" === t ? "1em" : r, r = i.pixelLeft + "px", i.left = s, a && (o.left = a)), void 0 === r ? r : r + "" || "auto"
    });
    function ge(e, t) {
        return{get: function () {
            var n = e();
            if (null != n)return n ? void delete this.get : (this.get = t).apply(this, arguments)
        }}
    };
    !function () {
        var o, u, f, l, s, a, n = i.createElement("div"), d = "border:0;width:0;height:0;position:absolute;top:0;left:-9999px", p = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;padding:0;margin:0;border:0";
        n.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", o = n.getElementsByTagName("a")[0], o.style.cssText = "float:left;opacity:.5", r.opacity = /^0.5/.test(o.style.opacity), r.cssFloat = !!o.style.cssFloat, n.style.backgroundClip = "content-box", n.cloneNode(!0).style.backgroundClip = "", r.clearCloneStyle = "content-box" === n.style.backgroundClip, o = n = null, e.extend(r, {reliableHiddenOffsets: function () {
            if (null != u)return u;
            var r, t, o, e = i.createElement("div"), n = i.getElementsByTagName("body")[0];
            if (n)return e.setAttribute("className", "t"), e.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", r = i.createElement("div"), r.style.cssText = d, n.appendChild(r).appendChild(e), e.innerHTML = "<table><tr><td></td><td>t</td></tr></table>", t = e.getElementsByTagName("td"), t[0].style.cssText = "padding:0;margin:0;border:0;display:none", o = 0 === t[0].offsetHeight, t[0].style.display = "", t[1].style.display = "none", u = o && 0 === t[0].offsetHeight, n.removeChild(r), e = n = null, u
        }, boxSizing: function () {
            return null == f && c(), f
        }, boxSizingReliable: function () {
            return null == l && c(), l
        }, pixelPosition: function () {
            return null == s && c(), s
        }, reliableMarginRight: function () {
            var o, r, n, e;
            if (null == a && t.getComputedStyle) {
                if (o = i.getElementsByTagName("body")[0], !o)return;
                r = i.createElement("div"), n = i.createElement("div"), r.style.cssText = d, o.appendChild(r).appendChild(n), e = n.appendChild(i.createElement("div")), e.style.cssText = n.style.cssText = p, e.style.marginRight = e.style.width = "0", n.style.width = "1px", a = !parseFloat((t.getComputedStyle(e, null) || {}).marginRight), o.removeChild(r)
            }
            ;
            return a
        }});
        function c() {
            var o, n, r = i.getElementsByTagName("body")[0];
            r && (o = i.createElement("div"), n = i.createElement("div"), o.style.cssText = d, r.appendChild(o).appendChild(n), n.style.cssText = "-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;position:absolute;display:block;padding:1px;border:1px;width:4px;margin-top:1%;top:1%", e.swap(r, null != r.style.zoom ? {zoom: 1} : {}, function () {
                f = 4 === n.offsetWidth
            }), l = !0, s = !1, a = !0, t.getComputedStyle && (s = "1%" !== (t.getComputedStyle(n, null) || {}).top, l = "4px" === (t.getComputedStyle(n, null) || {width: "4px"}).width), r.removeChild(o), n = r = null)
        }
    }(), e.swap = function (e, t, n, r) {
        var a, i, o = {};
        for (i in t)o[i] = e.style[i], e.style[i] = t[i];
        a = n.apply(e, r || []);
        for (i in t)e.style[i] = o[i];
        return a
    };
    var ne = /alpha\([^)]*\)/i, Mt = /opacity\s*=\s*([^)]*)/, Pt = /^(none|table(?!-c[ea]).+)/, Rt = new RegExp("^(" + R + ")(.*)$", "i"), It = new RegExp("^([+-])=(" + R + ")", "i"), zt = {position: "absolute", visibility: "hidden", display: "block"}, Ge = {letterSpacing: 0, fontWeight: 400}, Qe = ["Webkit", "O", "Moz", "ms"];

    function he(e, t) {
        if (t in e)return t;
        var r = t.charAt(0).toUpperCase() + t.slice(1), i = t, n = Qe.length;
        while (n--)if (t = Qe[n] + r, t in e)return t;
        return i
    };
    function me(t, n) {
        for (var o, r, s, a = [], i = 0, l = t.length; l > i; i++)r = t[i], r.style && (a[i] = e.e$(r, "olddisplay"), o = r.style.display, n ? (a[i] || "none" !== o || (r.style.display = ""), "" === r.style.display && D(r) && (a[i] = e.e$(r, "olddisplay", pe(r.nodeName)))) : a[i] || (s = D(r), (o && "none" !== o || !s) && e.e$(r, "olddisplay", s ? o : e.css(r, "display"))));
        for (i = 0; l > i; i++)r = t[i], r.style && (n && "none" !== r.style.display && "" !== r.style.display || (r.style.display = n ? a[i] || "" : "none"));
        return t
    };
    function et(e, t, n) {
        var r = Rt.exec(t);
        return r ? Math.max(0, r[1] - (n || 0)) + (r[2] || "px") : t
    };
    function tt(t, n, r, i, o) {
        for (var a = r === (i ? "border" : "content") ? 4 : "width" === n ? 1 : 0, s = 0; 4 > a; a += 2)"margin" === r && (s += e.css(t, r + g[a], !0, o)), i ? ("content" === r && (s -= e.css(t, "padding" + g[a], !0, o)), "margin" !== r && (s -= e.css(t, "border" + g[a] + "Width", !0, o))) : (s += e.css(t, "padding" + g[a], !0, o), "padding" !== r && (s += e.css(t, "border" + g[a] + "Width", !0, o)));
        return s
    };
    function lt(t, n, i) {
        var l = !0, o = "width" === n ? t.offsetWidth : t.offsetHeight, a = b(t), s = r.boxSizing() && "border-box" === e.css(t, "boxSizing", !1, a);
        if (0 >= o || null == o) {
            if (o = y(t, n, a), (0 > o || null == o) && (o = t.style[n]), P.test(o))return o;
            l = s && (r.boxSizingReliable() || o === t.style[n]), o = parseFloat(o) || 0
        }
        ;
        return o + tt(t, n, i || (s ? "border" : "content"), l, a) + "px"
    };
    e.extend({cssHooks: {opacity: {get: function (e, t) {
        if (t) {
            var n = y(e, "opacity");
            return"" === n ? "1" : n
        }
    }}}, cssNumber: {columnCount: !0, fillOpacity: !0, fontWeight: !0, lineHeight: !0, opacity: !0, order: !0, orphans: !0, widows: !0, zIndex: !0, zoom: !0}, cssProps: {"float": r.cssFloat ? "cssFloat" : "styleFloat"}, style: function (t, n, i, o) {
        if (t && 3 !== t.nodeType && 8 !== t.nodeType && t.style) {
            var u, d, s, c = e.camelCase(n), l = t.style;
            if (n = e.cssProps[c] || (e.cssProps[c] = he(l, c)), s = e.cssHooks[n] || e.cssHooks[c], void 0 === i)return s && "get"in s && void 0 !== (u = s.get(t, !1, o)) ? u : l[n];
            if (d = typeof i, "string" === d && (u = It.exec(i)) && (i = (u[1] + 1) * u[2] + parseFloat(e.css(t, n)), d = "number"), null != i && i === i && ("number" !== d || e.cssNumber[c] || (i += "px"), r.clearCloneStyle || "" !== i || 0 !== n.indexOf("background") || (l[n] = "inherit"), !(s && "set"in s && void 0 === (i = s.set(t, i, o)))))try {
                l[n] = "", l[n] = i
            } catch (a) {
            }
        }
    }, css: function (t, n, r, i) {
        var l, o, s, a = e.camelCase(n);
        return n = e.cssProps[a] || (e.cssProps[a] = he(t.style, a)), s = e.cssHooks[n] || e.cssHooks[a], s && "get"in s && (o = s.get(t, !0, r)), void 0 === o && (o = y(t, n, i)), "normal" === o && n in Ge && (o = Ge[n]), "" === r || r ? (l = parseFloat(o), r === !0 || e.isNumeric(l) ? l || 0 : o) : o
    }}), e.each(["height", "width"], function (t, n) {
        e.cssHooks[n] = {get: function (t, r, i) {
            return r ? 0 === t.offsetWidth && Pt.test(e.css(t, "display")) ? e.swap(t, zt, function () {
                return lt(t, n, i)
            }) : lt(t, n, i) : void 0
        }, set: function (t, i, o) {
            var a = o && b(t);
            return et(t, i, o ? tt(t, n, o, r.boxSizing() && "border-box" === e.css(t, "boxSizing", !1, a), a) : 0)
        }}
    }), r.opacity || (e.cssHooks.opacity = {get: function (e, t) {
        return Mt.test((t && e.currentStyle ? e.currentStyle.filter : e.style.filter) || "") ? .01 * parseFloat(RegExp.$1) + "" : t ? "1" : ""
    }, set: function (t, n) {
        var r = t.style, i = t.currentStyle, a = e.isNumeric(n) ? "alpha(opacity=" + 100 * n + ")" : "", o = i && i.filter || r.filter || "";
        r.zoom = 1, (n >= 1 || "" === n) && "" === e.trim(o.replace(ne, "")) && r.removeAttribute && (r.removeAttribute("filter"), "" === n || i && !i.filter) || (r.filter = ne.test(o) ? o.replace(ne, a) : o + " " + a)
    }}), e.cssHooks.marginRight = ge(r.reliableMarginRight, function (t, n) {
        return n ? e.swap(t, {display: "inline-block"}, y, [t, "marginRight"]) : void 0
    }), e.each({margin: "", padding: "", border: "Width"}, function (t, n) {
        e.cssHooks[t + n] = {expand: function (e) {
            for (var r = 0, o = {}, i = "string" == typeof e ? e.split(" ") : [e]; 4 > r; r++)o[t + g[r] + n] = i[r] || i[r - 2] || i[0];
            return o
        }}, Xe.test(t) || (e.cssHooks[t + n].set = et)
    }), e.fn.extend({css: function (t, n) {
        return x(this, function (t, n, r) {
            var a, s, o = {}, i = 0;
            if (e.isArray(n)) {
                for (a = b(t), s = n.length; s > i; i++)o[n[i]] = e.css(t, n[i], !1, a);
                return o
            }
            ;
            return void 0 !== r ? e.style(t, n, r) : e.css(t, n)
        }, t, n, arguments.length > 1)
    }, show: function () {
        return me(this, !0)
    }, hide: function () {
        return me(this)
    }, toggle: function (t) {
        return"boolean" == typeof t ? t ? this.show() : this.hide() : this.each(function () {
            D(this) ? e(this).show() : e(this).hide()
        })
    }});
    function s(e, t, n, r, i) {
        return new s.prototype.init(e, t, n, r, i)
    };
    e.Tween = s, s.prototype = {constructor: s, init: function (t, n, r, i, o, a) {
        this.elem = t, this.prop = r, this.easing = o || "swing", this.options = n, this.start = this.now = this.cur(), this.end = i, this.unit = a || (e.cssNumber[r] ? "" : "px")
    }, cur: function () {
        var e = s.propHooks[this.prop];
        return e && e.get ? e.get(this) : s.propHooks.t$.get(this)
    }, run: function (t) {
        var r, n = s.propHooks[this.prop];
        return this.pos = r = this.options.duration ? e.easing[this.easing](t, this.options.duration * t, 0, 1, this.options.duration) : t, this.now = (this.end - this.start) * r + this.start, this.options.step && this.options.step.call(this.elem, this.now, this), n && n.set ? n.set(this) : s.propHooks.t$.set(this), this
    }}, s.prototype.init.prototype = s.prototype, s.propHooks = {t$: {get: function (t) {
        var n;
        return null == t.elem[t.prop] || t.elem.style && null != t.elem.style[t.prop] ? (n = e.css(t.elem, t.prop, ""), n && "auto" !== n ? n : 0) : t.elem[t.prop]
    }, set: function (t) {
        e.fx.step[t.prop] ? e.fx.step[t.prop](t) : t.elem.style && (null != t.elem.style[e.cssProps[t.prop]] || e.cssHooks[t.prop]) ? e.style(t.elem, t.prop, t.now + t.unit) : t.elem[t.prop] = t.now
    }}}, s.propHooks.scrollTop = s.propHooks.scrollLeft = {set: function (e) {
        e.elem.nodeType && e.elem.parentNode && (e.elem[e.prop] = e.now)
    }}, e.easing = {linear: function (e) {
        return e
    }, swing: function (e) {
        return.5 - Math.cos(e * Math.PI) / 2
    }}, e.fx = s.prototype.init, e.fx.step = {};
    var T, H, Bt = /^(?:toggle|show|hide)$/, Ue = new RegExp("^(?:([+-])=|)(" + R + ")([a-z%]*)$", "i"), Ot = /queueHooks$/, F = [ft], S = {"*": [function (t, n) {
        var o = this.createTween(t, n), l = o.cur(), i = Ue.exec(n), a = i && i[3] || (e.cssNumber[t] ? "" : "px"), r = (e.cssNumber[t] || "px" !== a && +l) && Ue.exec(e.css(o.elem, t)), s = 1, u = 20;
        if (r && r[3] !== a) {
            a = a || r[3], i = i || [], r = +l || 1;
            do s = s || ".5", r /= s, e.style(o.elem, t, r + a); while (s !== (s = o.cur() / l) && 1 !== s && --u)
        }
        ;
        return i && (r = o.start = +r || +l || 0, o.unit = a, o.end = i[1] ? r + (i[1] + 1) * i[2] : +i[2]), o
    }]};

    function dt() {
        return setTimeout(function () {
            T = void 0
        }), T = e.now()
    };
    function O(e, t) {
        var i, n = {height: e}, r = 0;
        for (t = t ? 1 : 0; 4 > r; r += 2 - t)i = g[r], n["margin" + i] = n["padding" + i] = e;
        return t && (n.opacity = n.width = e), n
    };
    function ut(e, t, n) {
        for (var i, o = (S[t] || []).concat(S["*"]), r = 0, a = o.length; a > r; r++)if (i = o[r].call(n, t, e))return i
    };
    function ft(t, n, i) {
        var o, d, g, f, l, y, h, m, c = this, p = {}, s = t.style, u = t.nodeType && D(t), a = e.e$(t, "fxshow");
        i.queue || (l = e.r$(t, "fx"), null == l.unqueued && (l.unqueued = 0, y = l.empty.fire, l.empty.fire = function () {
            l.unqueued || y()
        }), l.unqueued++, c.always(function () {
            c.always(function () {
                l.unqueued--, e.queue(t, "fx").length || l.empty.fire()
            })
        })), 1 === t.nodeType && ("height"in n || "width"in n) && (i.overflow = [s.overflow, s.overflowX, s.overflowY], h = e.css(t, "display"), m = pe(t.nodeName), "none" === h && (h = m), "inline" === h && "none" === e.css(t, "float") && (r.inlineBlockNeedsLayout && "inline" !== m ? s.zoom = 1 : s.display = "inline-block")), i.overflow && (s.overflow = "hidden", r.shrinkWrapBlocks() || c.always(function () {
            s.overflow = i.overflow[0], s.overflowX = i.overflow[1], s.overflowY = i.overflow[2]
        }));
        for (o in n)if (d = n[o], Bt.exec(d)) {
            if (delete n[o], g = g || "toggle" === d, d === (u ? "hide" : "show")) {
                if ("show" !== d || !a || void 0 === a[o])continue;
                u = !0
            }
            ;
            p[o] = a && a[o] || e.style(t, o)
        }
        ;
        if (!e.isEmptyObject(p)) {
            a ? "hidden"in a && (u = a.hidden) : a = e.e$(t, "fxshow", {}), g && (a.hidden = !u), u ? e(t).show() : c.done(function () {
                e(t).hide()
            }), c.done(function () {
                var n;
                e.n$(t, "fxshow");
                for (n in p)e.style(t, n, p[n])
            });
            for (o in p)f = ut(u ? a[o] : 0, o, c), o in a || (a[o] = f.start, u && (f.end = f.start, f.start = "width" === o || "height" === o ? 1 : 0))
        }
    };
    function gt(t, n) {
        var r, o, s, i, a;
        for (r in t)if (o = e.camelCase(r), s = n[o], i = t[r], e.isArray(i) && (s = i[1], i = t[r] = i[0]), r !== o && (t[o] = i, delete t[r]), a = e.cssHooks[o], a && "expand"in a) {
            i = a.expand(i), delete t[o];
            for (r in i)r in t || (t[r] = i[r], n[r] = s)
        } else n[o] = s
    };
    function Oe(t, n, r) {
        var u, l, s = 0, d = F.length, o = e.Deferred().always(function () {
            delete c.elem
        }), c = function () {
            if (l)return!1;
            for (var u = T || dt(), n = Math.max(0, i.startTime + i.duration - u), s = n / i.duration || 0, e = 1 - s, r = 0, a = i.tweens.length; a > r; r++)i.tweens[r].run(e);
            return o.notifyWith(t, [i, e, n]), 1 > e && a ? n : (o.resolveWith(t, [i]), !1)
        }, i = o.promise({elem: t, props: e.extend({}, n), opts: e.extend(!0, {specialEasing: {}}, r), originalProperties: n, originalOptions: r, startTime: T || dt(), duration: r.duration, tweens: [], createTween: function (n, r) {
            var o = e.Tween(t, i.opts, n, r, i.opts.specialEasing[n] || i.opts.easing);
            return i.tweens.push(o), o
        }, stop: function (e) {
            var n = 0, r = e ? i.tweens.length : 0;
            if (l)return this;
            for (l = !0; r > n; n++)i.tweens[n].run(1);
            return e ? o.resolveWith(t, [i, e]) : o.rejectWith(t, [i, e]), this
        }}), a = i.props;
        for (gt(a, i.opts.specialEasing); d > s; s++)if (u = F[s].call(i, t, a, i.opts))return u;
        return e.map(a, ut, i), e.isFunction(i.opts.start) && i.opts.start.call(t, i), e.fx.timer(e.extend(c, {elem: t, anim: i, queue: i.opts.queue})), i.progress(i.opts.progress).done(i.opts.done, i.opts.complete).fail(i.opts.fail).always(i.opts.always)
    };
    e.Animation = e.extend(Oe, {tweener: function (t, n) {
        e.isFunction(t) ? (n = t, t = ["*"]) : t = t.split(" ");
        for (var r, i = 0, o = t.length; o > i; i++)r = t[i], S[r] = S[r] || [], S[r].unshift(n)
    }, prefilter: function (e, t) {
        t ? F.unshift(e) : F.push(e)
    }}), e.speed = function (t, n, r) {
        var i = t && "object" == typeof t ? e.extend({}, t) : {complete: r || !r && n || e.isFunction(t) && t, duration: t, easing: r && n || n && !e.isFunction(n) && n};
        return i.duration = e.fx.off ? 0 : "number" == typeof i.duration ? i.duration : i.duration in e.fx.speeds ? e.fx.speeds[i.duration] : e.fx.speeds.t$, (null == i.queue || i.queue === !0) && (i.queue = "fx"), i.old = i.complete, i.complete = function () {
            e.isFunction(i.old) && i.old.call(this), i.queue && e.dequeue(this, i.queue)
        }, i
    }, e.fn.extend({fadeTo: function (e, t, n, r) {
        return this.filter(D).css("opacity", 0).show().end().animate({opacity: t}, e, n, r)
    }, animate: function (t, n, r, i) {
        var s = e.isEmptyObject(t), a = e.speed(n, r, i), o = function () {
            var n = Oe(this, e.extend({}, t), a);
            (s || e.e$(this, "finish")) && n.stop(!0)
        };
        return o.finish = o, s || a.queue === !1 ? this.each(o) : this.queue(a.queue, o)
    }, stop: function (t, n, r) {
        var i = function (e) {
            var t = e.stop;
            delete e.stop, t(r)
        };
        return"string" != typeof t && (r = n, n = t, t = void 0), n && t !== !1 && this.queue(t || "fx", []), this.each(function () {
            var s = !0, n = null != t && t + "queueHooks", a = e.timers, o = e.e$(this);
            if (n)o[n] && o[n].stop && i(o[n]); else for (n in o)o[n] && o[n].stop && Ot.test(n) && i(o[n]);
            for (n = a.length; n--;)a[n].elem !== this || null != t && a[n].queue !== t || (a[n].anim.stop(r), s = !1, a.splice(n, 1));
            (s || !r) && e.dequeue(this, t)
        })
    }, finish: function (t) {
        return t !== !1 && (t = t || "fx"), this.each(function () {
            var n, o = e.e$(this), i = o[t + "queue"], a = o[t + "queueHooks"], r = e.timers, s = i ? i.length : 0;
            for (o.finish = !0, e.queue(this, t, []), a && a.stop && a.stop.call(this, !0), n = r.length; n--;)r[n].elem === this && r[n].queue === t && (r[n].anim.stop(!0), r.splice(n, 1));
            for (n = 0; s > n; n++)i[n] && i[n].finish && i[n].finish.call(this);
            delete o.finish
        })
    }}), e.each(["toggle", "show", "hide"], function (t, n) {
        var r = e.fn[n];
        e.fn[n] = function (e, t, i) {
            return null == e || "boolean" == typeof e ? r.apply(this, arguments) : this.animate(O(n, !0), e, t, i)
        }
    }), e.each({slideDown: O("show"), slideUp: O("hide"), slideToggle: O("toggle"), fadeIn: {opacity: "show"}, fadeOut: {opacity: "hide"}, fadeToggle: {opacity: "toggle"}}, function (t, n) {
        e.fn[t] = function (e, t, r) {
            return this.animate(n, e, t, r)
        }
    }), e.timers = [], e.fx.tick = function () {
        var r, n = e.timers, t = 0;
        for (T = e.now(); t < n.length; t++)r = n[t], r() || n[t] !== r || n.splice(t--, 1);
        n.length || e.fx.stop(), T = void 0
    }, e.fx.timer = function (t) {
        e.timers.push(t), t() ? e.fx.start() : e.timers.pop()
    }, e.fx.interval = 13, e.fx.start = function () {
        H || (H = setInterval(e.fx.tick, e.fx.interval))
    }, e.fx.stop = function () {
        clearInterval(H), H = null
    }, e.fx.speeds = {slow: 600, fast: 200, t$: 400}, e.fn.delay = function (t, n) {
        return t = e.fx ? e.fx.speeds[t] || t : t, n = n || "fx", this.queue(n, function (e, n) {
            var r = setTimeout(e, t);
            n.stop = function () {
                clearTimeout(r)
            }
        })
    }, function () {
        var n, e, o, a, t = i.createElement("div");
        t.setAttribute("className", "t"), t.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", n = t.getElementsByTagName("a")[0], o = i.createElement("select"), a = o.appendChild(i.createElement("option")), e = t.getElementsByTagName("input")[0], n.style.cssText = "top:1px", r.getSetAttribute = "t" !== t.className, r.style = /top/.test(n.getAttribute("style")), r.hrefNormalized = "/a" === n.getAttribute("href"), r.checkOn = !!e.value, r.optSelected = a.selected, r.enctype = !!i.createElement("form").enctype, o.disabled = !0, r.optDisabled = !a.disabled, e = i.createElement("input"), e.setAttribute("value", ""), r.input = "" === e.getAttribute("value"), e.value = "t", e.setAttribute("type", "radio"), r.radioValue = "t" === e.value, n = e = o = a = t = null
    }();
    var rn = /\r/g;
    e.fn.extend({val: function (t) {
        var n, r, o, i = this[0];
        {
            if (arguments.length)return o = e.isFunction(t), this.each(function (r) {
                var i;
                1 === this.nodeType && (i = o ? t.call(this, r, e(this).val()) : t, null == i ? i = "" : "number" == typeof i ? i += "" : e.isArray(i) && (i = e.map(i, function (e) {
                    return null == e ? "" : e + ""
                })), n = e.valHooks[this.type] || e.valHooks[this.nodeName.toLowerCase()], n && "set"in n && void 0 !== n.set(this, i, "value") || (this.value = i))
            });
            if (i)return n = e.valHooks[i.type] || e.valHooks[i.nodeName.toLowerCase()], n && "get"in n && void 0 !== (r = n.get(i, "value")) ? r : (r = i.value, "string" == typeof r ? r.replace(rn, "") : null == r ? "" : r)
        }
    }}), e.extend({valHooks: {option: {get: function (t) {
        var n = e.find.attr(t, "value");
        return null != n ? n : e.text(t)
    }}, select: {get: function (t) {
        for (var s, n, c = t.options, i = t.selectedIndex, a = "select-one" === t.type || 0 > i, u = a ? null : [], l = a ? i + 1 : c.length, o = 0 > i ? l : a ? i : 0; l > o; o++)if (n = c[o], !(!n.selected && o !== i || (r.optDisabled ? n.disabled : null !== n.getAttribute("disabled")) || n.parentNode.disabled && e.nodeName(n.parentNode, "optgroup"))) {
            if (s = e(n).val(), a)return s;
            u.push(s)
        }
        ;
        return u
    }, set: function (t, n) {
        var s, i, o = t.options, l = e.makeArray(n), a = o.length;
        while (a--)if (i = o[a], e.inArray(e.valHooks.option.get(i), l) >= 0)try {
            i.selected = s = !0
        } catch (r) {
            i.scrollHeight
        } else i.selected = !1;
        return s || (t.selectedIndex = -1), o
    }}}}), e.each(["radio", "checkbox"], function () {
        e.valHooks[this] = {set: function (t, n) {
            return e.isArray(n) ? t.checked = e.inArray(e(t).val(), n) >= 0 : void 0
        }}, r.checkOn || (e.valHooks[this].get = function (e) {
            return null === e.getAttribute("value") ? "on" : e.value
        })
    });
    var N, Ye, m = e.expr.attrHandle, oe = /^(?:checked|selected)$/i, v = r.getSetAttribute, z = r.input;
    e.fn.extend({attr: function (t, n) {
        return x(this, e.attr, t, n, arguments.length > 1)
    }, removeAttr: function (t) {
        return this.each(function () {
            e.removeAttr(this, t)
        })
    }}), e.extend({attr: function (t, n, r) {
        var i, o, a = t.nodeType;
        if (t && 3 !== a && 8 !== a && 2 !== a)return typeof t.getAttribute === u ? e.prop(t, n, r) : (1 === a && e.isXMLDoc(t) || (n = n.toLowerCase(), i = e.attrHooks[n] || (e.expr.match.bool.test(n) ? Ye : N)), void 0 === r ? i && "get"in i && null !== (o = i.get(t, n)) ? o : (o = e.find.attr(t, n), null == o ? void 0 : o) : null !== r ? i && "set"in i && void 0 !== (o = i.set(t, r, n)) ? o : (t.setAttribute(n, r + ""), r) : void e.removeAttr(t, n))
    }, removeAttr: function (t, n) {
        var r, i, a = 0, o = n && n.match(c);
        if (o && 1 === t.nodeType)while (r = o[a++])i = e.propFix[r] || r, e.expr.match.bool.test(r) ? z && v || !oe.test(r) ? t[i] = !1 : t[e.camelCase("default-" + r)] = t[i] = !1 : e.attr(t, r, ""), t.removeAttribute(v ? r : i)
    }, attrHooks: {type: {set: function (t, n) {
        if (!r.radioValue && "radio" === n && e.nodeName(t, "input")) {
            var i = t.value;
            return t.setAttribute("type", n), i && (t.value = i), n
        }
    }}}}), Ye = {set: function (t, n, r) {
        return n === !1 ? e.removeAttr(t, r) : z && v || !oe.test(r) ? t.setAttribute(!v && e.propFix[r] || r, r) : t[e.camelCase("default-" + r)] = t[r] = !0, r
    }}, e.each(e.expr.match.bool.source.match(/\w+/g), function (t, n) {
        var r = m[n] || e.find.attr;
        m[n] = z && v || !oe.test(n) ? function (e, t, n) {
            var i, o;
            return n || (o = m[t], m[t] = i, i = null != r(e, t, n) ? t.toLowerCase() : null, m[t] = o), i
        } : function (t, n, r) {
            return r ? void 0 : t[e.camelCase("default-" + n)] ? n.toLowerCase() : null
        }
    }), z && v || (e.attrHooks.value = {set: function (t, n, r) {
        return e.nodeName(t, "input") ? void(t.defaultValue = n) : N && N.set(t, n, r)
    }}), v || (N = {set: function (e, t, n) {
        var r = e.getAttributeNode(n);
        return r || e.setAttributeNode(r = e.ownerDocument.createAttribute(n)), r.value = t += "", "value" === n || t === e.getAttribute(n) ? t : void 0
    }}, m.id = m.name = m.coords = function (e, t, n) {
        var r;
        return n ? void 0 : (r = e.getAttributeNode(t)) && "" !== r.value ? r.value : null
    }, e.valHooks.button = {get: function (e, t) {
        var n = e.getAttributeNode(t);
        return n && n.specified ? n.value : void 0
    }, set: N.set}, e.attrHooks.contenteditable = {set: function (e, t, n) {
        N.set(e, "" === t ? !1 : t, n)
    }}, e.each(["width", "height"], function (t, n) {
        e.attrHooks[n] = {set: function (e, t) {
            return"" === t ? (e.setAttribute(n, "auto"), t) : void 0
        }}
    })), r.style || (e.attrHooks.style = {get: function (e) {
        return e.style.cssText || void 0
    }, set: function (e, t) {
        return e.style.cssText = t + ""
    }});
    var Wt = /^(?:input|select|textarea|button|object)$/i, Ft = /^(?:a|area)$/i;
    e.fn.extend({prop: function (t, n) {
        return x(this, e.prop, t, n, arguments.length > 1)
    }, removeProp: function (t) {
        return t = e.propFix[t] || t, this.each(function () {
            try {
                this[t] = void 0, delete this[t]
            } catch (e) {
            }
        })
    }}), e.extend({propFix: {"for": "htmlFor", "class": "className"}, prop: function (t, n, r) {
        var a, i, s, o = t.nodeType;
        if (t && 3 !== o && 8 !== o && 2 !== o)return s = 1 !== o || !e.isXMLDoc(t), s && (n = e.propFix[n] || n, i = e.propHooks[n]), void 0 !== r ? i && "set"in i && void 0 !== (a = i.set(t, r, n)) ? a : t[n] = r : i && "get"in i && null !== (a = i.get(t, n)) ? a : t[n]
    }, propHooks: {tabIndex: {get: function (t) {
        var n = e.find.attr(t, "tabindex");
        return n ? parseInt(n, 10) : Wt.test(t.nodeName) || Ft.test(t.nodeName) && t.href ? 0 : -1
    }}}}), r.hrefNormalized || e.each(["href", "src"], function (t, n) {
        e.propHooks[n] = {get: function (e) {
            return e.getAttribute(n, 4)
        }}
    }), r.optSelected || (e.propHooks.selected = {get: function (e) {
        var t = e.parentNode;
        return t && (t.selectedIndex, t.parentNode && t.parentNode.selectedIndex), null
    }}), e.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function () {
        e.propFix[this.toLowerCase()] = this
    }), r.enctype || (e.propFix.enctype = "encoding");
    var Y = /[\t\r\n\f]/g;
    e.fn.extend({addClass: function (t) {
        var l, n, r, a, s, o, i = 0, d = this.length, u = "string" == typeof t && t;
        if (e.isFunction(t))return this.each(function (n) {
            e(this).addClass(t.call(this, n, this.className))
        });
        if (u)for (l = (t || "").match(c) || []; d > i; i++)if (n = this[i], r = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(Y, " ") : " ")) {
            s = 0;
            while (a = l[s++])r.indexOf(" " + a + " ") < 0 && (r += a + " ");
            o = e.trim(r), n.className !== o && (n.className = o)
        }
        ;
        return this
    }, removeClass: function (t) {
        var l, n, r, a, s, o, i = 0, d = this.length, u = 0 === arguments.length || "string" == typeof t && t;
        if (e.isFunction(t))return this.each(function (n) {
            e(this).removeClass(t.call(this, n, this.className))
        });
        if (u)for (l = (t || "").match(c) || []; d > i; i++)if (n = this[i], r = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(Y, " ") : "")) {
            s = 0;
            while (a = l[s++])while (r.indexOf(" " + a + " ") >= 0)r = r.replace(" " + a + " ", " ");
            o = t ? e.trim(r) : "", n.className !== o && (n.className = o)
        }
        ;
        return this
    }, toggleClass: function (t, n) {
        var r = typeof t;
        return"boolean" == typeof n && "string" === r ? n ? this.addClass(t) : this.removeClass(t) : this.each(e.isFunction(t) ? function (r) {
            e(this).toggleClass(t.call(this, r, this.className, n), n)
        } : function () {
            if ("string" === r) {
                var n, a = 0, i = e(this), o = t.match(c) || [];
                while (n = o[a++])i.hasClass(n) ? i.removeClass(n) : i.addClass(n)
            } else(r === u || "boolean" === r) && (this.className && e.e$(this, "__className__", this.className), this.className = this.className || t === !1 ? "" : e.e$(this, "__className__") || "")
        })
    }, hasClass: function (e) {
        for (var r = " " + e + " ", t = 0, n = this.length; n > t; t++)if (1 === this[t].nodeType && (" " + this[t].className + " ").replace(Y, " ").indexOf(r) >= 0)return!0;
        return!1
    }}), e.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function (t, n) {
        e.fn[n] = function (e, t) {
            return arguments.length > 0 ? this.on(n, null, e, t) : this.trigger(n)
        }
    }), e.fn.extend({hover: function (e, t) {
        return this.mouseenter(e).mouseleave(t || e)
    }, bind: function (e, t, n) {
        return this.on(e, null, t, n)
    }, unbind: function (e, t) {
        return this.off(e, null, t)
    }, delegate: function (e, t, n, r) {
        return this.on(t, e, n, r)
    }, undelegate: function (e, t, n) {
        return 1 === arguments.length ? this.off(e, "**") : this.off(t, e || "**", n)
    }});
    var Z = e.now(), X = /\?/, qt = /(,)|(\[|{)|(}|])|"(?:[^"\\\r\n]|\\["\\\/bfnrt]|\\u[\da-fA-F]{4})*"\s*:?|true|false|null|-?(?!0\d)\d+(?:\.\d+|)(?:[eE][+-]?\d+|)/g;
    e.parseJSON = function (n) {
        if (t.JSON && t.JSON.parse)return t.JSON.parse(n + "");
        var o, r = null, i = e.trim(n + "");
        return i && !e.trim(i.replace(qt, function (e, t, n, i) {
            return o && t && (r = 0), 0 === r ? e : (o = n || t, r += !i - !n, "")
        })) ? Function("return " + i)() : e.error("Invalid JSON: " + n)
    }, e.parseXML = function (n) {
        var i, o;
        if (!n || "string" != typeof n)return null;
        try {
            t.DOMParser ? (o = new DOMParser, i = o.parseFromString(n, "text/xml")) : (i = new ActiveXObject("Microsoft.XMLDOM"), i.async = "false", i.loadXML(n))
        } catch (r) {
            i = void 0
        }
        ;
        return i && i.documentElement && !i.getElementsByTagName("parsererror").length || e.error("Invalid XML: " + n), i
    };
    var w, f, nn = /#.*$/, ve = /([?&])_=[^&]*/, fn = /^(.*?):[ \t]*([^\r\n]*)\r?$/gm, on = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/, an = /^(?:GET|HEAD)$/, sn = /^\/\//, We = /^([\w.+-]+:)(?:\/\/(?:[^\/?#]*@|)([^\/?#:]*)(?::(\d+)|)|)/, ot = {}, Q = {}, at = "*/".concat("*");
    try {
        f = location.href
    } catch (o) {
        f = i.createElement("a"), f.href = "", f = f.href
    }
    ;
    w = We.exec(f.toLowerCase()) || [];
    function Be(t) {
        return function (n, r) {
            "string" != typeof n && (r = n, n = "*");
            var i, a = 0, o = n.toLowerCase().match(c) || [];
            if (e.isFunction(r))while (i = o[a++])"+" === i.charAt(0) ? (i = i.slice(1) || "*", (t[i] = t[i] || []).unshift(r)) : (t[i] = t[i] || []).push(r)
        }
    };
    function Ie(t, n, r, i) {
        var o = {}, s = t === Q;

        function a(l) {
            var u;
            return o[l] = !0, e.each(t[l] || [], function (e, t) {
                var l = t(n, r, i);
                return"string" != typeof l || s || o[l] ? s ? !(u = l) : void 0 : (n.dataTypes.unshift(l), a(l), !1)
            }), u
        };
        return a(n.dataTypes[0]) || !o["*"] && a("*")
    };
    function ae(t, n) {
        var i, r, o = e.ajaxSettings.flatOptions || {};
        for (r in n)void 0 !== n[r] && ((o[r] ? t : i || (i = {}))[r] = n[r]);
        return i && e.extend(!0, t, i), t
    };
    function vt(e, t, n) {
        var s, a, i, o, l = e.contents, r = e.dataTypes;
        while ("*" === r[0])r.shift(), void 0 === a && (a = e.mimeType || t.getResponseHeader("Content-Type"));
        if (a)for (o in l)if (l[o] && l[o].test(a)) {
            r.unshift(o);
            break
        }
        ;
        if (r[0]in n)i = r[0]; else {
            for (o in n) {
                if (!r[0] || e.converters[o + " " + r[0]]) {
                    i = o;
                    break
                }
                ;
                s || (s = o)
            }
            ;
            i = i || s
        }
        ;
        return i ? (i !== r[0] && r.unshift(i), n[i]) : void 0
    };
    function bt(e, t, n, r) {
        var c, o, a, u, l, s = {}, d = e.dataTypes.slice();
        if (d[1])for (a in e.converters)s[a.toLowerCase()] = e.converters[a];
        o = d.shift();
        while (o)if (e.responseFields[o] && (n[e.responseFields[o]] = t), !l && r && e.dataFilter && (t = e.dataFilter(t, e.dataType)), l = o, o = d.shift())if ("*" === o)o = l; else if ("*" !== l && l !== o) {
            if (a = s[l + " " + o] || s["* " + o], !a)for (c in s)if (u = c.split(" "), u[1] === o && (a = s[l + " " + u[0]] || s["* " + u[0]])) {
                a === !0 ? a = s[c] : s[c] !== !0 && (o = u[0], d.unshift(u[1]));
                break
            }
            ;
            if (a !== !0)if (a && e["throws"])t = a(t); else try {
                t = a(t)
            } catch (i) {
                return{state: "parsererror", error: a ? i : "No conversion from " + l + " to " + o}
            }
        }
        ;
        return{state: "success", data: t}
    };
    e.extend({active: 0, lastModified: {}, etag: {}, ajaxSettings: {url: f, type: "GET", isLocal: on.test(w[1]), global: !0, processData: !0, async: !0, contentType: "application/x-www-form-urlencoded; charset=UTF-8", accepts: {"*": at, text: "text/plain", html: "text/html", xml: "application/xml, text/xml", json: "application/json, text/javascript"}, contents: {xml: /xml/, html: /html/, json: /json/}, responseFields: {xml: "responseXML", text: "responseText", json: "responseJSON"}, converters: {"* text": String, "text html": !0, "text json": e.parseJSON, "text xml": e.parseXML}, flatOptions: {url: !0, context: !0}}, ajaxSetup: function (t, n) {
        return n ? ae(ae(t, e.ajaxSettings), n) : ae(e.ajaxSettings, t)
    }, ajaxPrefilter: Be(ot), ajaxTransport: Be(Q), ajax: function (t, n) {
        "object" == typeof t && (n = t, t = void 0), n = n || {};
        var u, d, a, x, v, p, h, y, r = e.ajaxSetup({}, n), l = r.context || r, b = r.context && (l.nodeType || l.jquery) ? e(l) : e.event, T = e.Deferred(), k = e.Callbacks("once memory"), g = r.statusCode || {}, C = {}, N = {}, s = 0, E = "canceled", i = {readyState: 0, getResponseHeader: function (e) {
            var t;
            if (2 === s) {
                if (!y) {
                    y = {};
                    while (t = fn.exec(x))y[t[1].toLowerCase()] = t[2]
                }
                ;
                t = y[e.toLowerCase()]
            }
            ;
            return null == t ? null : t
        }, getAllResponseHeaders: function () {
            return 2 === s ? x : null
        }, setRequestHeader: function (e, t) {
            var n = e.toLowerCase();
            return s || (e = N[n] = N[n] || e, C[e] = t), this
        }, overrideMimeType: function (e) {
            return s || (r.mimeType = e), this
        }, statusCode: function (e) {
            var t;
            if (e)if (2 > s)for (t in e)g[t] = [g[t], e[t]]; else i.always(e[i.status]);
            return this
        }, abort: function (e) {
            var t = e || E;
            return h && h.abort(t), m(0, t), this
        }};
        if (T.promise(i).complete = k.add, i.success = i.done, i.error = i.fail, r.url = ((t || r.url || f) + "").replace(nn, "").replace(sn, w[1] + "//"), r.type = n.method || n.type || r.method || r.type, r.dataTypes = e.trim(r.dataType || "*").toLowerCase().match(c) || [""], null == r.crossDomain && (u = We.exec(r.url.toLowerCase()), r.crossDomain = !(!u || u[1] === w[1] && u[2] === w[2] && (u[3] || ("http:" === u[1] ? "80" : "443")) === (w[3] || ("http:" === w[1] ? "80" : "443")))), r.data && r.processData && "string" != typeof r.data && (r.data = e.param(r.data, r.traditional)), Ie(ot, r, n, i), 2 === s)return i;
        p = r.global, p && 0 === e.active++ && e.event.trigger("ajaxStart"), r.type = r.type.toUpperCase(), r.hasContent = !an.test(r.type), a = r.url, r.hasContent || (r.data && (a = r.url += (X.test(a) ? "&" : "?") + r.data, delete r.data), r.cache === !1 && (r.url = ve.test(a) ? a.replace(ve, "$1_=" + Z++) : a + (X.test(a) ? "&" : "?") + "_=" + Z++)), r.ifModified && (e.lastModified[a] && i.setRequestHeader("If-Modified-Since", e.lastModified[a]), e.etag[a] && i.setRequestHeader("If-None-Match", e.etag[a])), (r.data && r.hasContent && r.contentType !== !1 || n.contentType) && i.setRequestHeader("Content-Type", r.contentType), i.setRequestHeader("Accept", r.dataTypes[0] && r.accepts[r.dataTypes[0]] ? r.accepts[r.dataTypes[0]] + ("*" !== r.dataTypes[0] ? ", " + at + "; q=0.01" : "") : r.accepts["*"]);
        for (d in r.headers)i.setRequestHeader(d, r.headers[d]);
        if (r.beforeSend && (r.beforeSend.call(l, i, r) === !1 || 2 === s))return i.abort();
        E = "abort";
        for (d in{success: 1, error: 1, complete: 1})i[d](r[d]);
        if (h = Ie(Q, r, n, i)) {
            i.readyState = 1, p && b.trigger("ajaxSend", [i, r]), r.async && r.timeout > 0 && (v = setTimeout(function () {
                i.abort("timeout")
            }, r.timeout));
            try {
                s = 1, h.send(C, m)
            } catch (o) {
                if (!(2 > s))throw o;
                m(-1, o)
            }
        } else m(-1, "No Transport");
        function m(t, n, o, u) {
            var d, w, y, m, f, c = n;
            2 !== s && (s = 2, v && clearTimeout(v), h = void 0, x = u || "", i.readyState = t > 0 ? 4 : 0, d = t >= 200 && 300 > t || 304 === t, o && (m = vt(r, i, o)), m = bt(r, m, i, d), d ? (r.ifModified && (f = i.getResponseHeader("Last-Modified"), f && (e.lastModified[a] = f), f = i.getResponseHeader("etag"), f && (e.etag[a] = f)), 204 === t || "HEAD" === r.type ? c = "nocontent" : 304 === t ? c = "notmodified" : (c = m.state, w = m.data, y = m.error, d = !y)) : (y = c, (t || !c) && (c = "error", 0 > t && (t = 0))), i.status = t, i.statusText = (n || c) + "", d ? T.resolveWith(l, [w, c, i]) : T.rejectWith(l, [i, c, y]), i.statusCode(g), g = void 0, p && b.trigger(d ? "ajaxSuccess" : "ajaxError", [i, r, d ? w : y]), k.fireWith(l, [i, c]), p && (b.trigger("ajaxComplete", [i, r]), --e.active || e.event.trigger("ajaxStop")))
        };
        return i
    }, getJSON: function (t, n, r) {
        return e.get(t, n, r, "json")
    }, getScript: function (t, n) {
        return e.get(t, void 0, n, "script")
    }}), e.each(["get", "post"], function (t, n) {
        e[n] = function (t, r, i, o) {
            return e.isFunction(r) && (o = o || i, i = r, r = void 0), e.ajax({url: t, type: n, dataType: o, data: r, success: i})
        }
    }), e.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function (t, n) {
        e.fn[n] = function (e) {
            return this.on(n, e)
        }
    }), e.i$ = function (t) {
        return e.ajax({url: t, type: "GET", dataType: "script", async: !1, global: !1, "throws": !0})
    }, e.fn.extend({wrapAll: function (t) {
        if (e.isFunction(t))return this.each(function (n) {
            e(this).wrapAll(t.call(this, n))
        });
        if (this[0]) {
            var n = e(t, this[0].ownerDocument).eq(0).clone(!0);
            this[0].parentNode && n.insertBefore(this[0]), n.map(function () {
                var e = this;
                while (e.firstChild && 1 === e.firstChild.nodeType)e = e.firstChild;
                return e
            }).append(this)
        }
        ;
        return this
    }, wrapInner: function (t) {
        return this.each(e.isFunction(t) ? function (n) {
            e(this).wrapInner(t.call(this, n))
        } : function () {
            var r = e(this), n = r.contents();
            n.length ? n.wrapAll(t) : r.append(t)
        })
    }, wrap: function (t) {
        var n = e.isFunction(t);
        return this.each(function (r) {
            e(this).wrapAll(n ? t.call(this, r) : t)
        })
    }, unwrap: function () {
        return this.parent().each(function () {
            e.nodeName(this, "body") || e(this).replaceWith(this.childNodes)
        }).end()
    }}), e.expr.filters.hidden = function (t) {
        return t.offsetWidth <= 0 && t.offsetHeight <= 0 || !r.reliableHiddenOffsets() && "none" === (t.style && t.style.display || e.css(t, "display"))
    }, e.expr.filters.visible = function (t) {
        return!e.expr.filters.hidden(t)
    };
    var Gt = /%20/g, Ut = /\[\]$/, Ce = /\r?\n/g, Jt = /^(?:submit|button|image|reset|file)$/i, Qt = /^(?:input|select|textarea|keygen)/i;

    function ie(t, n, r, i) {
        var o;
        if (e.isArray(n))e.each(n, function (e, n) {
            r || Ut.test(t) ? i(t, n) : ie(t + "[" + ("object" == typeof n ? e : "") + "]", n, r, i)
        }); else if (r || "object" !== e.type(n))i(t, n); else for (o in n)ie(t + "[" + o + "]", n[o], r, i)
    };
    e.param = function (t, n) {
        var i, r = [], o = function (t, n) {
            n = e.isFunction(n) ? n() : null == n ? "" : n, r[r.length] = encodeURIComponent(t) + "=" + encodeURIComponent(n)
        };
        if (void 0 === n && (n = e.ajaxSettings && e.ajaxSettings.traditional), e.isArray(t) || t.jquery && !e.isPlainObject(t))e.each(t, function () {
            o(this.name, this.value)
        }); else for (i in t)ie(i, t[i], n, o);
        return r.join("&").replace(Gt, "+")
    }, e.fn.extend({serialize: function () {
        return e.param(this.serializeArray())
    }, serializeArray: function () {
        return this.map(function () {
            var t = e.prop(this, "elements");
            return t ? e.makeArray(t) : this
        }).filter(function () {
            var t = this.type;
            return this.name && !e(this).is(":disabled") && Qt.test(this.nodeName) && !Jt.test(t) && (this.checked || !I.test(t))
        }).map(function (t, n) {
            var r = e(this).val();
            return null == r ? null : e.isArray(r) ? e.map(r, function (e) {
                return{name: n.name, value: e.replace(Ce, "\r\n")}
            }) : {name: n.name, value: r.replace(Ce, "\r\n")}
        }).get()
    }}), e.ajaxSettings.xhr = void 0 !== t.ActiveXObject ? function () {
        return!this.isLocal && /^(get|post|head|put|delete|options)$/i.test(this.type) && Je() || wt()
    } : Je;
    var ht = 0, W = {}, A = e.ajaxSettings.xhr();
    t.ActiveXObject && e(t).on("unload", function () {
        for (var e in W)W[e](void 0, !0)
    }), r.cors = !!A && "withCredentials"in A, A = r.ajax = !!A, A && e.ajaxTransport(function (t) {
        if (!t.crossDomain || r.cors) {
            var n;
            return{send: function (r, i) {
                var a, o = t.xhr(), s = ++ht;
                if (o.open(t.type, t.url, t.async, t.username, t.password), t.xhrFields)for (a in t.xhrFields)o[a] = t.xhrFields[a];
                t.mimeType && o.overrideMimeType && o.overrideMimeType(t.mimeType), t.crossDomain || r["X-Requested-With"] || (r["X-Requested-With"] = "XMLHttpRequest");
                for (a in r)void 0 !== r[a] && o.setRequestHeader(a, r[a] + "");
                o.send(t.hasContent && t.data || null), n = function (r, a) {
                    var u, d, c;
                    if (n && (a || 4 === o.readyState))if (delete W[s], n = void 0, o.onreadystatechange = e.noop, a)4 !== o.readyState && o.abort(); else {
                        c = {}, u = o.status, "string" == typeof o.responseText && (c.text = o.responseText);
                        try {
                            d = o.statusText
                        } catch (l) {
                            d = ""
                        }
                        ;
                        u || !t.isLocal || t.crossDomain ? 1223 === u && (u = 204) : u = c.text ? 200 : 404
                    }
                    ;
                    c && i(u, d, c, o.getAllResponseHeaders())
                }, t.async ? 4 === o.readyState ? setTimeout(n) : o.onreadystatechange = W[s] = n : n()
            }, abort: function () {
                n && n(void 0, !0)
            }}
        }
    });
    function Je() {
        try {
            return new t.XMLHttpRequest
        } catch (e) {
        }
    };
    function wt() {
        try {
            return new t.ActiveXObject("Microsoft.XMLHTTP")
        } catch (e) {
        }
    };
    e.ajaxSetup({accepts: {script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"}, contents: {script: /(?:java|ecma)script/}, converters: {"text script": function (t) {
        return e.globalEval(t), t
    }}}), e.ajaxPrefilter("script", function (e) {
        void 0 === e.cache && (e.cache = !1), e.crossDomain && (e.type = "GET", e.global = !1)
    }), e.ajaxTransport("script", function (t) {
        if (t.crossDomain) {
            var n, r = i.head || e("head")[0] || i.documentElement;
            return{send: function (e, o) {
                n = i.createElement("script"), n.async = !0, t.scriptCharset && (n.charset = t.scriptCharset), n.src = t.url, n.onload = n.onreadystatechange = function (e, t) {
                    (t || !n.readyState || /loaded|complete/.test(n.readyState)) && (n.onload = n.onreadystatechange = null, n.parentNode && n.parentNode.removeChild(n), n = null, t || o(200, "success"))
                }, r.insertBefore(n, r.firstChild)
            }, abort: function () {
                n && n.onload(void 0, !0)
            }}
        }
    });
    var ke = [], ee = /(=)\?(?=&|$)|\?\?/;
    e.ajaxSetup({jsonp: "callback", jsonpCallback: function () {
        var t = ke.pop() || e.expando + "_" + Z++;
        return this[t] = !0, t
    }}), e.ajaxPrefilter("json jsonp", function (n, r, i) {
        var o, s, a, l = n.jsonp !== !1 && (ee.test(n.url) ? "url" : "string" == typeof n.data && !(n.contentType || "").indexOf("application/x-www-form-urlencoded") && ee.test(n.data) && "data");
        return l || "jsonp" === n.dataTypes[0] ? (o = n.jsonpCallback = e.isFunction(n.jsonpCallback) ? n.jsonpCallback() : n.jsonpCallback, l ? n[l] = n[l].replace(ee, "$1" + o) : n.jsonp !== !1 && (n.url += (X.test(n.url) ? "&" : "?") + n.jsonp + "=" + o), n.converters["script json"] = function () {
            return a || e.error(o + " was not called"), a[0]
        }, n.dataTypes[0] = "json", s = t[o], t[o] = function () {
            a = arguments
        }, i.always(function () {
            t[o] = s, n[o] && (n.jsonpCallback = r.jsonpCallback, ke.push(o)), a && e.isFunction(s) && s(a[0]), a = s = void 0
        }), "script") : void 0
    }), e.parseHTML = function (t, n, r) {
        if (!t || "string" != typeof t)return null;
        "boolean" == typeof n && (r = n, n = !1), n = n || i;
        var a = Le.exec(t), o = !r && [];
        return a ? [n.createElement(a[1])] : (a = e.buildFragment([t], n, o), o && o.length && e(o).remove(), e.merge([], a.childNodes))
    };
    var qe = e.fn.load;
    e.fn.load = function (t, n, r) {
        if ("string" != typeof t && qe)return qe.apply(this, arguments);
        var a, s, l, o = this, i = t.indexOf(" ");
        return i >= 0 && (a = t.slice(i, t.length), t = t.slice(0, i)), e.isFunction(n) ? (r = n, n = void 0) : n && "object" == typeof n && (l = "POST"), o.length > 0 && e.ajax({url: t, type: l, dataType: "html", data: n}).done(function (t) {
            s = arguments, o.html(a ? e("<div>").append(e.parseHTML(t)).find(a) : t)
        }).complete(r && function (e, t) {
            o.each(r, s || [e.responseText, t, e])
        }), this
    }, e.expr.filters.animated = function (t) {
        return e.grep(e.timers,function (e) {
            return t === e.elem
        }).length
    };
    var st = t.document.documentElement;

    function Ve(t) {
        return e.isWindow(t) ? t : 9 === t.nodeType ? t.defaultView || t.parentWindow : !1
    };
    e.offset = {setOffset: function (t, n, r) {
        var c, d, f, u, i, a, p, l = e.css(t, "position"), s = e(t), o = {};
        "static" === l && (t.style.position = "relative"), i = s.offset(), f = e.css(t, "top"), a = e.css(t, "left"), p = ("absolute" === l || "fixed" === l) && e.inArray("auto", [f, a]) > -1, p ? (c = s.position(), u = c.top, d = c.left) : (u = parseFloat(f) || 0, d = parseFloat(a) || 0), e.isFunction(n) && (n = n.call(t, r, i)), null != n.top && (o.top = n.top - i.top + u), null != n.left && (o.left = n.left - i.left + d), "using"in n ? n.using.call(t, o) : s.css(o)
    }}, e.fn.extend({offset: function (t) {
        if (arguments.length)return void 0 === t ? this : this.each(function (n) {
            e.offset.setOffset(this, t, n)
        });
        var n, a, i = {top: 0, left: 0}, r = this[0], o = r && r.ownerDocument;
        if (o)return n = o.documentElement, e.contains(n, r) ? (typeof r.getBoundingClientRect !== u && (i = r.getBoundingClientRect()), a = Ve(o), {top: i.top + (a.pageYOffset || n.scrollTop) - (n.clientTop || 0), left: i.left + (a.pageXOffset || n.scrollLeft) - (n.clientLeft || 0)}) : i
    }, position: function () {
        if (this[0]) {
            var n, i, t = {top: 0, left: 0}, r = this[0];
            return"fixed" === e.css(r, "position") ? i = r.getBoundingClientRect() : (n = this.offsetParent(), i = this.offset(), e.nodeName(n[0], "html") || (t = n.offset()), t.top += e.css(n[0], "borderTopWidth", !0), t.left += e.css(n[0], "borderLeftWidth", !0)), {top: i.top - t.top - e.css(r, "marginTop", !0), left: i.left - t.left - e.css(r, "marginLeft", !0)}
        }
    }, offsetParent: function () {
        return this.map(function () {
            var t = this.offsetParent || st;
            while (t && !e.nodeName(t, "html") && "static" === e.css(t, "position"))t = t.offsetParent;
            return t || st
        })
    }}), e.each({scrollLeft: "pageXOffset", scrollTop: "pageYOffset"}, function (t, n) {
        var r = /Y/.test(n);
        e.fn[t] = function (i) {
            return x(this, function (t, i, o) {
                var a = Ve(t);
                return void 0 === o ? a ? n in a ? a[n] : a.document.documentElement[i] : t[i] : void(a ? a.scrollTo(r ? e(a).scrollLeft() : o, r ? o : e(a).scrollTop()) : t[i] = o)
            }, t, i, arguments.length, null)
        }
    }), e.each(["top", "left"], function (t, n) {
        e.cssHooks[n] = ge(r.pixelPosition, function (t, r) {
            return r ? (r = y(t, n), P.test(r) ? e(t).position()[n] + "px" : r) : void 0
        })
    }), e.each({Height: "height", Width: "width"}, function (t, n) {
        e.each({padding: "inner" + t, content: n, "": "outer" + t}, function (r, i) {
            e.fn[i] = function (i, o) {
                var s = arguments.length && (r || "boolean" != typeof i), a = r || (i === !0 || o === !0 ? "margin" : "border");
                return x(this, function (n, r, i) {
                    var o;
                    return e.isWindow(n) ? n.document.documentElement["client" + t] : 9 === n.nodeType ? (o = n.documentElement, Math.max(n.body["scroll" + t], o["scroll" + t], n.body["offset" + t], o["offset" + t], o["client" + t])) : void 0 === i ? e.css(n, r, a) : e.style(n, r, i, a)
                }, n, s ? i : void 0, s, null)
            }
        })
    }), e.fn.size = function () {
        return this.length
    }, e.fn.andSelf = e.fn.addBack, "function" == typeof define && define.amd && define("jquery", [], function () {
        return e
    });
    var mt = t.jQuery, yt = t.$;
    return e.noConflict = function (n) {
        return t.$ === e && (t.$ = yt), n && t.jQuery === e && (t.jQuery = mt), e
    }, typeof n === u && (t.jQuery = t.$ = e), e
});
(function (t) {
    function G() {
        return{empty: !1, unusedTokens: [], unusedInput: [], overflow: -2, charsLeftOver: 0, nullInput: !1, invalidMonth: null, invalidFormat: !1, userInvalidated: !1, iso: !1}
    };
    function v(t, e) {
        function s() {
            n.suppressDeprecationWarnings === !1 && "undefined" != typeof console && console.warn && console.warn("Deprecation warning: " + t)
        };
        var r = !0;
        return d(function () {
            return r && (s(), r = !1), e.apply(this, arguments)
        }, e)
    };
    function gt(t, n) {
        return function (e) {
            return r(t.call(this, e), n)
        }
    };
    function Jt(t, n) {
        return function (e) {
            return this.lang().ordinal(t.call(this, e), n)
        }
    };
    function ut() {
    };
    function k(t) {
        R(t), d(this, t)
    };
    function b(t) {
        var n = et(t), u = n.year || 0, o = n.quarter || 0, c = n.month || 0, a = n.week || 0, h = n.day || 0, s = n.hour || 0, e = n.minute || 0, i = n.second || 0, r = n.millisecond || 0;
        this.o$ = +r + 1e3 * i + 6e4 * e + 36e5 * s, this.u$ = +h + 7 * a, this.r$ = +c + 3 * o + 12 * u, this.O$ = {}, this.v$()
    };
    function d(t, n) {
        for (var e in n)n.hasOwnProperty(e) && (t[e] = n[e]);
        return n.hasOwnProperty("toString") && (t.toString = n.toString), n.hasOwnProperty("valueOf") && (t.valueOf = n.valueOf), t
    };
    function nn(t) {
        var n, e = {};
        for (n in t)t.hasOwnProperty(n) && lt.hasOwnProperty(n) && (e[n] = t[n]);
        return e
    };
    function m(t) {
        return 0 > t ? Math.ceil(t) : Math.floor(t)
    };
    function r(t, n, e) {
        for (var r = "" + Math.abs(t), s = t >= 0; r.length < n;)r = "0" + r;
        return(s ? e ? "+" : "" : "-") + r
    };
    function W(t, e, r, s) {
        var u = e.o$, a = e.u$, i = e.r$;
        s = null == s ? !0 : s, u && t._d.setTime(+t._d + u * r), a && J(t, "Date", A(t, "Date") + a * r), i && N(t, A(t, "Month") + i * r), s && n.updateOffset(t, a || i)
    };
    function F(t) {
        return"[object Array]" === Object.prototype.toString.call(t)
    };
    function Qt(t) {
        return"[object Date]" === Object.prototype.toString.call(t) || t instanceof Date
    };
    function st(t, n, r) {
        var s, u = Math.min(t.length, n.length), a = Math.abs(t.length - n.length), i = 0;
        for (s = 0; u > s; s++)(r && t[s] !== n[s] || !r && e(t[s]) !== e(n[s])) && i++;
        return i + a
    };
    function c(t) {
        if (t) {
            var n = t.toLowerCase().replace(/(.)s$/, "$1");
            t = Rt[t] || Xt[n] || n
        }
        ;
        return t
    };
    function et(t) {
        var e, n, r = {};
        for (n in t)t.hasOwnProperty(n) && (e = c(n), e && (r[e] = t[n]));
        return r
    };
    function qt(e) {
        var s, r;
        if (0 === e.indexOf("week"))s = 7, r = "day"; else {
            if (0 !== e.indexOf("month"))return;
            s = 12, r = "month"
        }
        ;
        n[e] = function (i, a) {
            var u, o, h = n.fn.n$[e], c = [];
            if ("number" == typeof i && (a = i, i = t), o = function (t) {
                var e = n().utc().set(r, t);
                return h.call(n.fn.n$, e, i || "")
            }, null != a)return o(a);
            for (u = 0; s > u; u++)c.push(o(u));
            return c
        }
    };
    function e(t) {
        var n = +t, e = 0;
        return 0 !== n && isFinite(n) && (e = n >= 0 ? Math.floor(n) : Math.ceil(n)), e
    };
    function O(t, n) {
        return new Date(Date.UTC(t, n + 1, 0)).getUTCDate()
    };
    function it(t, e, r) {
        return Y(n([t, 11, 31 + e - r]), e, r).week
    };
    function at(t) {
        return q(t) ? 366 : 365
    };
    function q(t) {
        return t % 4 === 0 && t % 100 !== 0 || t % 400 === 0
    };
    function R(t) {
        var n;
        t._a && -2 === t.t$.overflow && (n = t._a[l] < 0 || t._a[l] > 11 ? l : t._a[o] < 1 || t._a[o] > O(t._a[a], t._a[l]) ? o : t._a[i] < 0 || t._a[i] > 23 ? i : t._a[p] < 0 || t._a[p] > 59 ? p : t._a[M] < 0 || t._a[M] > 59 ? M : t._a[D] < 0 || t._a[D] > 999 ? D : -1, t.t$.G$ && (a > n || n > o) && (n = o), t.t$.overflow = n)
    };
    function rt(t) {
        return null == t.m$ && (t.m$ = !isNaN(t._d.getTime()) && t.t$.overflow < 0 && !t.t$.empty && !t.t$.invalidMonth && !t.t$.nullInput && !t.t$.invalidFormat && !t.t$.userInvalidated, t.c$ && (t.m$ = t.m$ && 0 === t.t$.charsLeftOver && 0 === t.t$.unusedTokens.length)), t.m$
    };
    function E(t) {
        return t ? t.toLowerCase().replace("_", "-") : t
    };
    function Z(t, e) {
        return e.e$ ? n(t).zone(e.h$ || 0) : n(t).local()
    };
    function Bt(t, n) {
        return n.abbr = t, y[t] || (y[t] = new ut), y[t].set(n), y[t]
    };
    function un(t) {
        delete y[t]
    };
    function u(t) {
        var r, a, e, i, s = 0, u = function (t) {
            if (!y[t] && ct)try {
                require("./lang/" + t)
            } catch (n) {
            }
            ;
            return y[t]
        };
        if (!t)return n.fn.n$;
        if (!F(t)) {
            if (a = u(t))return a;
            t = [t]
        }
        ;
        for (; s < t.length;) {
            for (i = E(t[s]).split("-"), r = i.length, e = E(t[s + 1]), e = e ? e.split("-") : null; r > 0;) {
                if (a = u(i.slice(0, r).join("-")))return a;
                if (e && e.length >= r && st(i, e, !0) >= r - 1)break;
                r--
            }
            ;
            s++
        }
        ;
        return n.fn.n$
    };
    function cn(t) {
        return t.match(/\[[\s\S]/) ? t.replace(/^\[|\]$/g, "") : t.replace(/\\/g, "")
    };
    function an(t) {
        var n, r, e = t.match(ot);
        for (n = 0, r = e.length; r > n; n++)e[n] = h[e[n]] ? h[e[n]] : cn(e[n]);
        return function (s) {
            var i = "";
            for (n = 0; r > n; n++)i += e[n]instanceof Function ? e[n].call(s, t) : e[n];
            return i
        }
    };
    function C(t, n) {
        return t.isValid() ? (n = nt(n, t.lang()), L[n] || (L[n] = an(n)), L[n](t)) : t.lang().invalidDate()
    };
    function nt(t, n) {
        function r(t) {
            return n.longDateFormat(t) || t
        };
        var e = 5;
        for (w.lastIndex = 0; e >= 0 && w.test(t);)t = t.replace(w, r), w.lastIndex = 0, e -= 1;
        return t
    };
    function Ut(t, n) {
        var r, e = n.c$;
        switch (t) {
            case"Q":
                return ft;
            case"DDDD":
                return ht;
            case"YYYY":
            case"GGGG":
            case"gggg":
                return e ? Vt : Wt;
            case"Y":
            case"G":
            case"g":
                return Et;
            case"YYYYYY":
            case"YYYYY":
            case"GGGGG":
            case"ggggg":
                return e ? Nt : Tt;
            case"S":
                if (e)return ft;
            case"SS":
                if (e)return dt;
            case"SSS":
                if (e)return ht;
            case"DDD":
                return Gt;
            case"MMM":
            case"MMMM":
            case"dd":
            case"ddd":
            case"dddd":
                return en;
            case"a":
            case"A":
                return u(n._l).L$;
            case"X":
                return sn;
            case"Z":
            case"ZZ":
                return H;
            case"T":
                return tn;
            case"SSSS":
                return hn;
            case"MM":
            case"DD":
            case"YY":
            case"GG":
            case"gg":
            case"HH":
            case"hh":
            case"mm":
            case"ss":
            case"ww":
            case"WW":
                return e ? dt : mt;
            case"M":
            case"D":
            case"d":
            case"H":
            case"h":
            case"m":
            case"s":
            case"w":
            case"W":
            case"e":
            case"E":
                return mt;
            case"Do":
                return on;
            default:
                return r = new RegExp(St(Ot(t.replace("\\", "")), "i"))
        }
    };
    function tt(t) {
        t = t || "";
        var r = t.match(H) || [], i = r[r.length - 1] || [], n = (i + "").match(jt) || ["-", 0, 0], s = +(60 * n[1]) + e(n[2]);
        return"+" === n[0] ? -s : s
    };
    function rn(t, r, s) {
        var h, c = s._a;
        switch (t) {
            case"Q":
                null != r && (c[l] = 3 * (e(r) - 1));
                break;
            case"M":
            case"MM":
                null != r && (c[l] = e(r) - 1);
                break;
            case"MMM":
            case"MMMM":
                h = u(s._l).monthsParse(r), null != h ? c[l] = h : s.t$.invalidMonth = r;
                break;
            case"D":
            case"DD":
                null != r && (c[o] = e(r));
                break;
            case"Do":
                null != r && (c[o] = e(parseInt(r, 10)));
                break;
            case"DDD":
            case"DDDD":
                null != r && (s.M$ = e(r));
                break;
            case"YY":
                c[a] = n.parseTwoDigitYear(r);
                break;
            case"YYYY":
            case"YYYYY":
            case"YYYYYY":
                c[a] = e(r);
                break;
            case"a":
            case"A":
                s.b$ = u(s._l).isPM(r);
                break;
            case"H":
            case"HH":
            case"h":
            case"hh":
                c[i] = e(r);
                break;
            case"m":
            case"mm":
                c[p] = e(r);
                break;
            case"s":
            case"ss":
                c[M] = e(r);
                break;
            case"S":
            case"SS":
            case"SSS":
            case"SSSS":
                c[D] = e(1e3 * ("0." + r));
                break;
            case"X":
                s._d = new Date(1e3 * parseFloat(r));
                break;
            case"Z":
            case"ZZ":
                s.k$ = !0, s.D$ = tt(r);
                break;
            case"w":
            case"ww":
            case"W":
            case"WW":
            case"d":
            case"dd":
            case"ddd":
            case"dddd":
            case"e":
            case"E":
                t = t.substr(0, 1);
            case"gg":
            case"gggg":
            case"GG":
            case"GGGG":
            case"GGGGG":
                t = t.substr(0, 2), r && (s._w = s._w || {}, s._w[t] = r)
        }
    };
    function P(t) {
        var s, g, Y, M, y, r, d, c, m, f, h = [];
        if (!t._d) {
            for (Y = bt(t), t._w && null == t._a[o] && null == t._a[l] && (y = function (e) {
                var r = parseInt(e, 10);
                return e ? e.length < 3 ? r > 68 ? 1900 + r : 2e3 + r : r : null == t._a[a] ? n().weekYear() : t._a[a]
            }, r = t._w, null != r.GG || null != r.W || null != r.E ? d = V(y(r.GG), r.W || 1, r.E, 4, 1) : (c = u(t._l), m = null != r.d ? B(r.d, c) : null != r.e ? parseInt(r.e, 10) + c.s$.dow : 0, f = parseInt(r.w, 10) || 1, null != r.d && m < c.s$.dow && f++, d = V(y(r.gg), f, m, c.s$.doy, c.s$.dow)), t._a[a] = d.year, t.M$ = d.dayOfYear), t.M$ && (M = null == t._a[a] ? Y[a] : t._a[a], t.M$ > at(M) && (t.t$.G$ = !0), g = x(M, 0, t.M$), t._a[l] = g.getUTCMonth(), t._a[o] = g.getUTCDate()), s = 0; 3 > s && null == t._a[s]; ++s)t._a[s] = h[s] = Y[s];
            for (; 7 > s; s++)t._a[s] = h[s] = null == t._a[s] ? 2 === s ? 1 : 0 : t._a[s];
            h[i] += e((t.D$ || 0) / 60), h[p] += e((t.D$ || 0) % 60), t._d = (t.k$ ? x : vt).apply(null, h)
        }
    };
    function Ft(t) {
        var n;
        t._d || (n = et(t._i), t._a = [n.year, n.month, n.day, n.hour, n.minute, n.second, n.millisecond], P(t))
    };
    function bt(t) {
        var n = new Date;
        return t.k$ ? [n.getUTCFullYear(), n.getUTCMonth(), n.getUTCDate()] : [n.getFullYear(), n.getMonth(), n.getDate()]
    };
    function I(t) {
        t._a = [], t.t$.empty = !0;
        var s, n, a, r, o, d = u(t._l), e = "" + t._i, f = e.length, c = 0;
        for (a = nt(t._f, d).match(ot) || [], s = 0; s < a.length; s++)r = a[s], n = (e.match(Ut(r, t)) || [])[0], n && (o = e.substr(0, e.indexOf(n)), o.length > 0 && t.t$.unusedInput.push(o), e = e.slice(e.indexOf(n) + n.length), c += n.length), h[r] ? (n ? t.t$.empty = !1 : t.t$.unusedTokens.push(r), rn(r, n, t)) : t.c$ && !n && t.t$.unusedTokens.push(r);
        t.t$.charsLeftOver = f - c, e.length > 0 && t.t$.unusedInput.push(e), t.b$ && t._a[i] < 12 && (t._a[i] += 12), t.b$ === !1 && 12 === t._a[i] && (t._a[i] = 0), P(t), R(t)
    };
    function Ot(t) {
        return t.replace(/\\(\[)|\\(\])|\[([^\]\[]*)\]|\\(.)/g, function (t, n, e, r, s) {
            return n || e || r || s
        })
    };
    function St(t) {
        return t.replace(/[-\/\\^$*+?.()|[\]{}]/g, "\\$&")
    };
    function kt(t) {
        var n, i, s, r, e;
        if (0 === t._f.length)return t.t$.invalidFormat = !0, void(t._d = new Date(0 / 0));
        for (r = 0; r < t._f.length; r++)e = 0, n = d({}, t), n.t$ = G(), n._f = t._f[r], I(n), rt(n) && (e += n.t$.charsLeftOver, e += 10 * n.t$.unusedTokens.length, n.t$.score = e, (null == s || s > e) && (s = e, i = n));
        d(t, i || n)
    };
    function Dt(t) {
        var e, s, r = t._i, i = Pt.exec(r);
        if (i) {
            for (t.t$.iso = !0, e = 0, s = T.length; s > e; e++)if (T[e][1].exec(r)) {
                t._f = T[e][0] + (i[6] || " ");
                break
            }
            ;
            for (e = 0, s = S.length; s > e; e++)if (S[e][1].exec(r)) {
                t._f += S[e][0];
                break
            }
            ;
            r.match(H) && (t._f += "Z"), I(t)
        } else n.createFromInputFallback(t)
    };
    function wt(e) {
        var r = e._i, s = Lt.exec(r);
        r === t ? e._d = new Date : s ? e._d = new Date(+s[1]) : "string" == typeof r ? Dt(e) : F(r) ? (e._a = r.slice(0), P(e)) : Qt(r) ? e._d = new Date(+r) : "object" == typeof r ? Ft(e) : "number" == typeof r ? e._d = new Date(r) : n.createFromInputFallback(e)
    };
    function vt(t, n, e, r, s, a, u) {
        var i = new Date(t, n, e, r, s, a, u);
        return 1970 > t && i.setFullYear(t), i
    };
    function x(t) {
        var n = new Date(Date.UTC.apply(null, arguments));
        return 1970 > t && n.setUTCFullYear(t), n
    };
    function B(t, n) {
        if ("string" == typeof t)if (isNaN(t)) {
            if (t = n.weekdaysParse(t), "number" != typeof t)return null
        } else t = parseInt(t, 10);
        return t
    };
    function Ct(t, n, e, r, s) {
        return s.relativeTime(n || 1, !!e, t, r)
    };
    function Ht(t, n, e) {
        var u = g(Math.abs(t) / 1e3), i = g(u / 60), a = g(i / 60), r = g(a / 24), o = g(r / 365), s = 45 > u && ["s", u] || 1 === i && ["m"] || 45 > i && ["mm", i] || 1 === a && ["h"] || 22 > a && ["hh", a] || 1 === r && ["d"] || 25 >= r && ["dd", r] || 45 >= r && ["M"] || 345 > r && ["MM", g(r / 30)] || 1 === o && ["y"] || ["yy", o];
        return s[2] = n, s[3] = t > 0, s[4] = e, Ct.apply({}, s)
    };
    function Y(t, e, r) {
        var i, a = r - e, s = r - t.day();
        return s > a && (s -= 7), a - 7 > s && (s += 7), i = n(t).add("d", s), {week: Math.ceil(i.dayOfYear() / 7), year: i.year()}
    };
    function V(t, n, e, r, s) {
        var u, i, a = x(t, 0, 1).getUTCDay();
        return e = null != e ? e : s, u = s - a + (a > r ? 7 : 0) - (s > a ? 7 : 0), i = 7 * (n - 1) + (e - s) + u + 1, {year: i > 0 ? t : t - 1, dayOfYear: i > 0 ? i : at(t - 1) + i}
    };
    function j(e) {
        var r = e._i, s = e._f;
        return null === r || s === t && "" === r ? n.invalid({nullInput: !0}) : ("string" == typeof r && (e._i = r = u().preparse(r)), n.isMoment(r) ? (e = nn(r), e._d = new Date(+r._d)) : s ? F(s) ? kt(e) : I(e) : wt(e), new k(e))
    };
    function N(t, n) {
        var e;
        return"string" == typeof n && (n = t.lang().monthsParse(n), "number" != typeof n) ? t : (e = Math.min(t.date(), O(t.year(), n)), t._d["set" + (t.e$ ? "UTC" : "") + "Month"](n, e), t)
    };
    function A(t, n) {
        return t._d["get" + (t.e$ ? "UTC" : "") + n]()
    };
    function J(t, n, e) {
        return"Month" === n ? N(t, e) : t._d["set" + (t.e$ ? "UTC" : "") + n](e)
    };
    function f(t, e) {
        return function (r) {
            return null != r ? (J(this, t, r), n.updateOffset(this, e), this) : A(this, t)
        }
    };
    function xt(t) {
        n.duration.fn[t] = function () {
            return this.O$[t]
        }
    };
    function X(t, e) {
        n.duration.fn["as" + t] = function () {
            return+this / e
        }
    };
    function Q(t) {
        "undefined" == typeof ender && (Yt = U.moment, U.moment = t ? v("Accessing Moment through the global scope is deprecated, and will be removed in an upcoming release.", n) : n)
    };
    for (var n, Yt, s, It = "2.6.0", U = "undefined" != typeof global ? global : this, g = Math.round, a = 0, l = 1, o = 2, i = 3, p = 4, M = 5, D = 6, y = {}, lt = {W$: null, _i: null, _f: null, _l: null, c$: null, e$: null, h$: null, t$: null, n$: null}, ct = "undefined" != typeof module && module.exports, Lt = /^\/?Date\((\-?\d+)/i, At = /(\-)?(?:(\d*)\.)?(\d+)\:(\d+)(?:\:(\d+)\.?(\d{3})?)?/, zt = /^(-)?P(?:(?:([0-9,.]*)Y)?(?:([0-9,.]*)M)?(?:([0-9,.]*)D)?(?:T(?:([0-9,.]*)H)?(?:([0-9,.]*)M)?(?:([0-9,.]*)S)?)?|([0-9,.]*)W)$/, ot = /(\[[^\[]*\])|(\\)?(Mo|MM?M?M?|Do|DDDo|DD?D?D?|ddd?d?|do?|w[o|w]?|W[o|W]?|Q|YYYYYY|YYYYY|YYYY|YY|gg(ggg?)?|GG(GGG?)?|e|E|a|A|hh?|HH?|mm?|ss?|S{1,4}|X|zz?|ZZ?|.)/g, w = /(\[[^\[]*\])|(\\)?(LT|LL?L?L?|l{1,4})/g, mt = /\d\d?/, Gt = /\d{1,3}/, Wt = /\d{1,4}/, Tt = /[+\-]?\d{1,6}/, hn = /\d+/, en = /[0-9]*['a-z\u00A0-\u05FF\u0700-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+|[\u0600-\u06FF\/]+(\s*?[\u0600-\u06FF]+){1,2}/i, H = /Z|[\+\-]\d\d:?\d\d/gi, tn = /T/i, sn = /[\+\-]?\d+(\.\d{1,3})?/, on = /\d{1,2}/, ft = /\d/, dt = /\d\d/, ht = /\d{3}/, Vt = /\d{4}/, Nt = /[+-]?\d{6}/, Et = /[+-]?\d+/, Pt = /^\s*(?:[+-]\d{6}|\d{4})-(?:(\d\d-\d\d)|(W\d\d$)|(W\d\d-\d)|(\d\d\d))((T| )(\d\d(:\d\d(:\d\d(\.\d+)?)?)?)?([\+\-]\d\d(?::?\d\d)?|\s*Z)?)?$/, Zt = "YYYY-MM-DDTHH:mm:ssZ", T = [
        ["YYYYYY-MM-DD", /[+-]\d{6}-\d{2}-\d{2}/],
        ["YYYY-MM-DD", /\d{4}-\d{2}-\d{2}/],
        ["GGGG-[W]WW-E", /\d{4}-W\d{2}-\d/],
        ["GGGG-[W]WW", /\d{4}-W\d{2}/],
        ["YYYY-DDD", /\d{4}-\d{3}/]
    ], S = [
        ["HH:mm:ss.SSSS", /(T| )\d\d:\d\d:\d\d\.\d+/],
        ["HH:mm:ss", /(T| )\d\d:\d\d:\d\d/],
        ["HH:mm", /(T| )\d\d:\d\d/],
        ["HH", /(T| )\d\d/]
    ], jt = /([\+\-]|\d\d)/gi, z = ("Date|Hours|Minutes|Seconds|Milliseconds".split("|"), {Milliseconds: 1, Seconds: 1e3, Minutes: 6e4, Hours: 36e5, Days: 864e5, Months: 2592e6, Years: 31536e6}), Rt = {ms: "millisecond", s: "second", m: "minute", h: "hour", d: "day", D: "date", w: "week", W: "isoWeek", M: "month", Q: "quarter", y: "year", DDD: "dayOfYear", e: "weekday", E: "isoWeekday", gg: "weekYear", GG: "isoWeekYear"}, Xt = {dayofyear: "dayOfYear", isoweekday: "isoWeekday", isoweek: "isoWeek", weekyear: "weekYear", isoweekyear: "isoWeekYear"}, L = {}, Mt = "DDD w W M D d".split(" "), pt = "M D H h m s w W".split(" "), h = {M: function () {
        return this.month() + 1
    }, MMM: function (t) {
        return this.lang().monthsShort(this, t)
    }, MMMM: function (t) {
        return this.lang().months(this, t)
    }, D: function () {
        return this.date()
    }, DDD: function () {
        return this.dayOfYear()
    }, d: function () {
        return this.day()
    }, dd: function (t) {
        return this.lang().weekdaysMin(this, t)
    }, ddd: function (t) {
        return this.lang().weekdaysShort(this, t)
    }, dddd: function (t) {
        return this.lang().weekdays(this, t)
    }, w: function () {
        return this.week()
    }, W: function () {
        return this.isoWeek()
    }, YY: function () {
        return r(this.year() % 100, 2)
    }, YYYY: function () {
        return r(this.year(), 4)
    }, YYYYY: function () {
        return r(this.year(), 5)
    }, YYYYYY: function () {
        var t = this.year(), n = t >= 0 ? "+" : "-";
        return n + r(Math.abs(t), 6)
    }, gg: function () {
        return r(this.weekYear() % 100, 2)
    }, gggg: function () {
        return r(this.weekYear(), 4)
    }, ggggg: function () {
        return r(this.weekYear(), 5)
    }, GG: function () {
        return r(this.isoWeekYear() % 100, 2)
    }, GGGG: function () {
        return r(this.isoWeekYear(), 4)
    }, GGGGG: function () {
        return r(this.isoWeekYear(), 5)
    }, e: function () {
        return this.weekday()
    }, E: function () {
        return this.isoWeekday()
    }, a: function () {
        return this.lang().meridiem(this.hours(), this.minutes(), !0)
    }, A: function () {
        return this.lang().meridiem(this.hours(), this.minutes(), !1)
    }, H: function () {
        return this.hours()
    }, h: function () {
        return this.hours() % 12 || 12
    }, m: function () {
        return this.minutes()
    }, s: function () {
        return this.seconds()
    }, S: function () {
        return e(this.milliseconds() / 100)
    }, SS: function () {
        return r(e(this.milliseconds() / 10), 2)
    }, SSS: function () {
        return r(this.milliseconds(), 3)
    }, SSSS: function () {
        return r(this.milliseconds(), 3)
    }, Z: function () {
        var t = -this.zone(), n = "+";
        return 0 > t && (t = -t, n = "-"), n + r(e(t / 60), 2) + ":" + r(e(t) % 60, 2)
    }, ZZ: function () {
        var t = -this.zone(), n = "+";
        return 0 > t && (t = -t, n = "-"), n + r(e(t / 60), 2) + r(e(t) % 60, 2)
    }, z: function () {
        return this.zoneAbbr()
    }, zz: function () {
        return this.zoneName()
    }, X: function () {
        return this.unix()
    }, Q: function () {
        return this.quarter()
    }}, yt = ["months", "monthsShort", "weekdays", "weekdaysShort", "weekdaysMin"]; Mt.length;)s = Mt.pop(), h[s + "o"] = Jt(h[s], s);
    for (; pt.length;)s = pt.pop(), h[s + s] = gt(h[s], 2);
    for (h.DDDD = gt(h.DDD, 3), d(ut.prototype, {set: function (t) {
        var e, n;
        for (n in t)e = t[n], "function" == typeof e ? this[n] = e : this["_" + n] = e
    }, r$: "January_February_March_April_May_June_July_August_September_October_November_December".split("_"), months: function (t) {
        return this.r$[t.month()]
    }, A$: "Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec".split("_"), monthsShort: function (t) {
        return this.A$[t.month()]
    }, monthsParse: function (t) {
        var e, r, s;
        for (this.g$ || (this.g$ = []), e = 0; 12 > e; e++)if (this.g$[e] || (r = n.utc([2e3, e]), s = "^" + this.months(r, "") + "|^" + this.monthsShort(r, ""), this.g$[e] = new RegExp(s.replace(".", ""), "i")), this.g$[e].test(t))return e
    }, x$: "Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"), weekdays: function (t) {
        return this.x$[t.day()]
    }, C$: "Sun_Mon_Tue_Wed_Thu_Fri_Sat".split("_"), weekdaysShort: function (t) {
        return this.C$[t.day()]
    }, I$: "Su_Mo_Tu_We_Th_Fr_Sa".split("_"), weekdaysMin: function (t) {
        return this.I$[t.day()]
    }, weekdaysParse: function (t) {
        var e, r, s;
        for (this.Y$ || (this.Y$ = []), e = 0; 7 > e; e++)if (this.Y$[e] || (r = n([2e3, 1]).day(e), s = "^" + this.weekdays(r, "") + "|^" + this.weekdaysShort(r, "") + "|^" + this.weekdaysMin(r, ""), this.Y$[e] = new RegExp(s.replace(".", ""), "i")), this.Y$[e].test(t))return e
    }, y$: {LT: "h:mm A", L: "MM/DD/YYYY", LL: "MMMM D YYYY", LLL: "MMMM D YYYY LT", LLLL: "dddd, MMMM D YYYY LT"}, longDateFormat: function (t) {
        var n = this.y$[t];
        return!n && this.y$[t.toUpperCase()] && (n = this.y$[t.toUpperCase()].replace(/MMMM|MM|DD|dddd/g, function (t) {
            return t.slice(1)
        }), this.y$[t] = n), n
    }, isPM: function (t) {
        return"p" === (t + "").toLowerCase().charAt(0)
    }, L$: /[ap]\.?m?\.?/i, meridiem: function (t, n, e) {
        return t > 11 ? e ? "pm" : "PM" : e ? "am" : "AM"
    }, F$: {sameDay: "[Today at] LT", nextDay: "[Tomorrow at] LT", nextWeek: "dddd [at] LT", lastDay: "[Yesterday at] LT", lastWeek: "[Last] dddd [at] LT", sameElse: "L"}, calendar: function (t, n) {
        var e = this.F$[t];
        return"function" == typeof e ? e.apply(n) : e
    }, T$: {future: "in %s", past: "%s ago", s: "a few seconds", m: "a minute", mm: "%d minutes", h: "an hour", hh: "%d hours", d: "a day", dd: "%d days", M: "a month", MM: "%d months", y: "a year", yy: "%d years"}, relativeTime: function (t, n, e, r) {
        var s = this.T$[e];
        return"function" == typeof s ? s(t, n, e, r) : s.replace(/%d/i, t)
    }, pastFuture: function (t, n) {
        var e = this.T$[t > 0 ? "future" : "past"];
        return"function" == typeof e ? e(n) : e.replace(/%s/i, n)
    }, ordinal: function (t) {
        return this.z$.replace("%d", t)
    }, z$: "%d", preparse: function (t) {
        return t
    }, postformat: function (t) {
        return t
    }, week: function (t) {
        return Y(t, this.s$.dow, this.s$.doy).week
    }, s$: {dow: 0, doy: 6}, H$: "Invalid date", invalidDate: function () {
        return this.H$
    }}), n = function (n, e, r, i) {
        var s;
        return"boolean" == typeof r && (i = r, r = t), s = {}, s.W$ = !0, s._i = n, s._f = e, s._l = r, s.c$ = i, s.e$ = !1, s.t$ = G(), j(s)
    }, n.suppressDeprecationWarnings = !1, n.createFromInputFallback = v("moment construction falls back to js Date. This is discouraged and will be removed in upcoming major release. Please refer to https://github.com/moment/moment/issues/1407 for more info.", function (t) {
        t._d = new Date(t._i)
    }), n.utc = function (n, e, r, i) {
        var s;
        return"boolean" == typeof r && (i = r, r = t), s = {}, s.W$ = !0, s.k$ = !0, s.e$ = !0, s._l = r, s._i = n, s._f = e, s.c$ = i, s.t$ = G(), j(s).utc()
    }, n.unix = function (t) {
        return n(1e3 * t)
    }, n.duration = function (t, r) {
        var u, h, a, c = t, s = null;
        return n.isDuration(t) ? c = {ms: t.o$, d: t.u$, M: t.r$} : "number" == typeof t ? (c = {}, r ? c[r] = t : c.milliseconds = t) : (s = At.exec(t)) ? (u = "-" === s[1] ? -1 : 1, c = {y: 0, d: e(s[o]) * u, h: e(s[i]) * u, m: e(s[p]) * u, s: e(s[M]) * u, ms: e(s[D]) * u}) : (s = zt.exec(t)) && (u = "-" === s[1] ? -1 : 1, a = function (t) {
            var n = t && parseFloat(t.replace(",", "."));
            return(isNaN(n) ? 0 : n) * u
        }, c = {y: a(s[2]), M: a(s[3]), d: a(s[4]), h: a(s[5]), m: a(s[6]), s: a(s[7]), w: a(s[8])}), h = new b(c), n.isDuration(t) && t.hasOwnProperty("_lang") && (h.n$ = t.n$), h
    }, n.version = It, n.defaultFormat = Zt, n.momentProperties = lt, n.updateOffset = function () {
    }, n.lang = function (t, e) {
        var r;
        return t ? (e ? Bt(E(t), e) : null === e ? (un(t), t = "en") : y[t] || u(t), r = n.duration.fn.n$ = n.fn.n$ = u(t), r.p$) : n.fn.n$.p$
    }, n.langData = function (t) {
        return t && t.n$ && t.n$.p$ && (t = t.n$.p$), u(t)
    }, n.isMoment = function (t) {
        return t instanceof k || null != t && t.hasOwnProperty("_isAMomentObject")
    }, n.isDuration = function (t) {
        return t instanceof b
    }, s = yt.length - 1; s >= 0; --s)qt(yt[s]);
    n.normalizeUnits = function (t) {
        return c(t)
    }, n.invalid = function (t) {
        var e = n.utc(0 / 0);
        return null != t ? d(e.t$, t) : e.t$.userInvalidated = !0, e
    }, n.parseZone = function () {
        return n.apply(null, arguments).parseZone()
    }, n.parseTwoDigitYear = function (t) {
        return e(t) + (e(t) > 68 ? 1900 : 2e3)
    }, d(n.fn = k.prototype, {clone: function () {
        return n(this)
    }, valueOf: function () {
        return+this._d + 6e4 * (this.h$ || 0)
    }, unix: function () {
        return Math.floor(+this / 1e3)
    }, toString: function () {
        return this.clone().lang("en").format("ddd MMM DD YYYY HH:mm:ss [GMT]ZZ")
    }, toDate: function () {
        return this.h$ ? new Date(+this) : this._d
    }, toISOString: function () {
        var t = n(this).utc();
        return 0 < t.year() && t.year() <= 9999 ? C(t, "YYYY-MM-DD[T]HH:mm:ss.SSS[Z]") : C(t, "YYYYYY-MM-DD[T]HH:mm:ss.SSS[Z]")
    }, toArray: function () {
        var t = this;
        return[t.year(), t.month(), t.date(), t.hours(), t.minutes(), t.seconds(), t.milliseconds()]
    }, isValid: function () {
        return rt(this)
    }, isDSTShifted: function () {
        return this._a ? this.isValid() && st(this._a, (this.e$ ? n.utc(this._a) : n(this._a)).toArray()) > 0 : !1
    }, parsingFlags: function () {
        return d({}, this.t$)
    }, invalidAt: function () {
        return this.t$.overflow
    }, utc: function () {
        return this.zone(0)
    }, local: function () {
        return this.zone(0), this.e$ = !1, this
    }, format: function (t) {
        var e = C(this, t || n.defaultFormat);
        return this.lang().postformat(e)
    }, add: function (t, e) {
        var r;
        return r = "string" == typeof t ? n.duration(+e, t) : n.duration(t, e), W(this, r, 1), this
    }, subtract: function (t, e) {
        var r;
        return r = "string" == typeof t ? n.duration(+e, t) : n.duration(t, e), W(this, r, -1), this
    }, diff: function (t, e, r) {
        var s, a, i = Z(t, this), u = 6e4 * (this.zone() - i.zone());
        return e = c(e), "year" === e || "month" === e ? (s = 432e5 * (this.daysInMonth() + i.daysInMonth()), a = 12 * (this.year() - i.year()) + (this.month() - i.month()), a += (this - n(this).startOf("month") - (i - n(i).startOf("month"))) / s, a -= 6e4 * (this.zone() - n(this).startOf("month").zone() - (i.zone() - n(i).startOf("month").zone())) / s, "year" === e && (a /= 12)) : (s = this - i, a = "second" === e ? s / 1e3 : "minute" === e ? s / 6e4 : "hour" === e ? s / 36e5 : "day" === e ? (s - u) / 864e5 : "week" === e ? (s - u) / 6048e5 : s), r ? a : m(a)
    }, from: function (t, e) {
        return n.duration(this.diff(t)).lang(this.lang().p$).humanize(!e)
    }, fromNow: function (t) {
        return this.from(n(), t)
    }, calendar: function () {
        var r = Z(n(), this).startOf("day"), t = this.diff(r, "days", !0), e = -6 > t ? "sameElse" : -1 > t ? "lastWeek" : 0 > t ? "lastDay" : 1 > t ? "sameDay" : 2 > t ? "nextDay" : 7 > t ? "nextWeek" : "sameElse";
        return this.format(this.lang().calendar(e, this))
    }, isLeapYear: function () {
        return q(this.year())
    }, isDST: function () {
        return this.zone() < this.clone().month(0).zone() || this.zone() < this.clone().month(5).zone()
    }, day: function (t) {
        var n = this.e$ ? this._d.getUTCDay() : this._d.getDay();
        return null != t ? (t = B(t, this.lang()), this.add({d: t - n})) : n
    }, month: f("Month", !0), startOf: function (t) {
        switch (t = c(t)) {
            case"year":
                this.month(0);
            case"quarter":
            case"month":
                this.date(1);
            case"week":
            case"isoWeek":
            case"day":
                this.hours(0);
            case"hour":
                this.minutes(0);
            case"minute":
                this.seconds(0);
            case"second":
                this.milliseconds(0)
        }
        ;
        return"week" === t ? this.weekday(0) : "isoWeek" === t && this.isoWeekday(1), "quarter" === t && this.month(3 * Math.floor(this.month() / 3)), this
    }, endOf: function (t) {
        return t = c(t), this.startOf(t).add("isoWeek" === t ? "week" : t, 1).subtract("ms", 1)
    }, isAfter: function (t, e) {
        return e = "undefined" != typeof e ? e : "millisecond", +this.clone().startOf(e) > +n(t).startOf(e)
    }, isBefore: function (t, e) {
        return e = "undefined" != typeof e ? e : "millisecond", +this.clone().startOf(e) < +n(t).startOf(e)
    }, isSame: function (t, n) {
        return n = n || "ms", +this.clone().startOf(n) === +Z(t, this).startOf(n)
    }, min: function (t) {
        return t = n.apply(null, arguments), this > t ? this : t
    }, max: function (t) {
        return t = n.apply(null, arguments), t > this ? this : t
    }, zone: function (t, e) {
        var r = this.h$ || 0;
        return null == t ? this.e$ ? r : this._d.getTimezoneOffset() : ("string" == typeof t && (t = tt(t)), Math.abs(t) < 16 && (t = 60 * t), this.h$ = t, this.e$ = !0, r !== t && (!e || this.S$ ? W(this, n.duration(r - t, "m"), 1, !1) : this.S$ || (this.S$ = !0, n.updateOffset(this, !0), this.S$ = null)), this)
    }, zoneAbbr: function () {
        return this.e$ ? "UTC" : ""
    }, zoneName: function () {
        return this.e$ ? "Coordinated Universal Time" : ""
    }, parseZone: function () {
        return this.D$ ? this.zone(this.D$) : "string" == typeof this._i && this.zone(this._i), this
    }, hasAlignedHourOffset: function (t) {
        return t = t ? n(t).zone() : 0, (this.zone() - t) % 60 === 0
    }, daysInMonth: function () {
        return O(this.year(), this.month())
    }, dayOfYear: function (t) {
        var e = g((n(this).startOf("day") - n(this).startOf("year")) / 864e5) + 1;
        return null == t ? e : this.add("d", t - e)
    }, quarter: function (t) {
        return null == t ? Math.ceil((this.month() + 1) / 3) : this.month(3 * (t - 1) + this.month() % 3)
    }, weekYear: function (t) {
        var n = Y(this, this.lang().s$.dow, this.lang().s$.doy).year;
        return null == t ? n : this.add("y", t - n)
    }, isoWeekYear: function (t) {
        var n = Y(this, 1, 4).year;
        return null == t ? n : this.add("y", t - n)
    }, week: function (t) {
        var n = this.lang().week(this);
        return null == t ? n : this.add("d", 7 * (t - n))
    }, isoWeek: function (t) {
        var n = Y(this, 1, 4).week;
        return null == t ? n : this.add("d", 7 * (t - n))
    }, weekday: function (t) {
        var n = (this.day() + 7 - this.lang().s$.dow) % 7;
        return null == t ? n : this.add("d", t - n)
    }, isoWeekday: function (t) {
        return null == t ? this.day() || 7 : this.day(this.day() % 7 ? t : t - 7)
    }, isoWeeksInYear: function () {
        return it(this.year(), 1, 4)
    }, weeksInYear: function () {
        var t = this.n$.s$;
        return it(this.year(), t.dow, t.doy)
    }, get: function (t) {
        return t = c(t), this[t]()
    }, set: function (t, n) {
        return t = c(t), "function" == typeof this[t] && this[t](n), this
    }, lang: function (n) {
        return n === t ? this.n$ : (this.n$ = u(n), this)
    }}), n.fn.millisecond = n.fn.milliseconds = f("Milliseconds", !1), n.fn.second = n.fn.seconds = f("Seconds", !1), n.fn.minute = n.fn.minutes = f("Minutes", !1), n.fn.hour = n.fn.hours = f("Hours", !0), n.fn.date = f("Date", !0), n.fn.dates = v("dates accessor is deprecated. Use date instead.", f("Date", !0)), n.fn.year = f("FullYear", !0), n.fn.years = v("years accessor is deprecated. Use year instead.", f("FullYear", !0)), n.fn.days = n.fn.day, n.fn.months = n.fn.month, n.fn.weeks = n.fn.week, n.fn.isoWeeks = n.fn.isoWeek, n.fn.quarters = n.fn.quarter, n.fn.toJSON = n.fn.toISOString, d(n.duration.fn = b.prototype, {v$: function () {
        var e, s, r, a, u = this.o$, n = this.u$, i = this.r$, t = this.O$;
        t.milliseconds = u % 1e3, e = m(u / 1e3), t.seconds = e % 60, s = m(e / 60), t.minutes = s % 60, r = m(s / 60), t.hours = r % 24, n += m(r / 24), t.days = n % 30, i += m(n / 30), t.months = i % 12, a = m(i / 12), t.years = a
    }, weeks: function () {
        return m(this.days() / 7)
    }, valueOf: function () {
        return this.o$ + 864e5 * this.u$ + this.r$ % 12 * 2592e6 + 31536e6 * e(this.r$ / 12)
    }, humanize: function (t) {
        var e = +this, n = Ht(e, !t, this.lang());
        return t && (n = this.lang().pastFuture(e, n)), this.lang().postformat(n)
    }, add: function (t, e) {
        var r = n.duration(t, e);
        return this.o$ += r.o$, this.u$ += r.u$, this.r$ += r.r$, this.v$(), this
    }, subtract: function (t, e) {
        var r = n.duration(t, e);
        return this.o$ -= r.o$, this.u$ -= r.u$, this.r$ -= r.r$, this.v$(), this
    }, get: function (t) {
        return t = c(t), this[t.toLowerCase() + "s"]()
    }, as: function (t) {
        return t = c(t), this["as" + t.charAt(0).toUpperCase() + t.slice(1) + "s"]()
    }, lang: n.fn.lang, toIsoString: function () {
        var i = Math.abs(this.years()), s = Math.abs(this.months()), r = Math.abs(this.days()), e = Math.abs(this.hours()), t = Math.abs(this.minutes()), n = Math.abs(this.seconds() + this.milliseconds() / 1e3);
        return this.asSeconds() ? (this.asSeconds() < 0 ? "-" : "") + "P" + (i ? i + "Y" : "") + (s ? s + "M" : "") + (r ? r + "D" : "") + (e || t || n ? "T" : "") + (e ? e + "H" : "") + (t ? t + "M" : "") + (n ? n + "S" : "") : "P0D"
    }});
    for (s in z)z.hasOwnProperty(s) && (X(s, z[s]), xt(s.toLowerCase()));
    X("Weeks", 6048e5), n.duration.fn.asMonths = function () {
        return(+this - 31536e6 * this.years()) / 2592e6 + 12 * this.years()
    }, n.lang("en", {ordinal: function (t) {
        var n = t % 10, r = 1 === e(t % 100 / 10) ? "th" : 1 === n ? "st" : 2 === n ? "nd" : 3 === n ? "rd" : "th";
        return t + r
    }}), ct ? module.exports = n : "function" == typeof define && define.amd ? (define("moment", function (t, e, r) {
        return r.config && r.config() && r.config().noGlobal === !0 && (U.moment = Yt), n
    }), Q(!0)) : Q()
}).call(this);
;
var a = {test: 'something'};
;
var a = {test: 'yes'};
;
$(document).ready(function () {
    avcms.event.addEvent("page-modified", function () {
        $(".user_selector").filter(":visible").select2({placeholder: "Find user", minimumInputLength: 2, ajax: {url: avcms.config.site_url + "admin/username_suggest", dataType: "json", data: function (e, t) {
            return{q: e}
        }, results: function (e, t) {
            return{results: e}
        }}, initSelection: function (e, t) {
            var a = $(e).val();
            if (a !== "") {
                $.ajax(avcms.config.site_url + "admin/username_suggest", {data: {q: a, id_search: 1}, dataType: "json"}).done(function (e) {
                    t(e[0])
                })
            }
        }});
        $("[name=publish_date]").datetimepicker({format: "YYYY-MM-DD HH:mm", defaultDate: new Date()});
        $("[name='tags']").select2({tags: [], tokenSeparators: [","], width: "100%"})
    });
    $("body").on("keyup", "[data-slug-target]", avcms.misc.generateSlugDelay);
    $("body").on("change", "[name=slug]", avcms.misc.disableAutoGenerateSlug);
    $("body").on("click", ".slug_refresh_button", avcms.misc.generateSlugButton);
    $(document).ajaxSuccess(function (t, e) {
        if (e.responseJSON !== undefined) {
            if (e.responseJSON.error !== undefined) {
                console.log(e.responseJSON.error)
            }
        }
    });
    $.ajaxPrefilter(function (e, t, n) {
        if (e.type === "POST" && e.data.indexOf("csrf_token") < 1) {
            if (e.data != "") {
                e.data = e.data + "&"
            }
            ;
            var a = avcms.misc.getCookie("avcms_csrf_token");
            e.data = e.data + "_csrf_token=" + encodeURIComponent(a)
        }
    })
});
avcms.misc = {typingTimer: null, slugInput: null, autoGenerateSlug: function () {
    var e = avcms.misc.slugInput;
    if (!e.val()) {
        return
    }
    ;
    var n = encodeURIComponent(e.val()), a = e.data("slug-target"), t = e.closest("form").find("[name=" + a + "]");
    $.ajax({url: avcms.config.site_url + "admin/generate_slug/" + n, dataType: "json", success: function (e) {
        t.val(e.slug)
    }})
}, mainLoaderOn: function () {
    $(".lightbar").show()
}, mainLoaderOff: function () {
    $(".lightbar").fadeOut(1000)
}, generateSlugDelay: function () {
    avcms.misc.slugInput = $(this);
    var e = $(this), a = e.data("slug-target"), t = e.closest("form").find("[name=" + a + "]");
    if (t.attr("value") == null && t.data("modified") == null) {
        if (avcms.misc.typingTimer) {
            clearTimeout(avcms.misc.typingTimer)
        }
        ;
        avcms.misc.typingTimer = setTimeout(avcms.misc.autoGenerateSlug, 600)
    }
    ;
    return!0
}, generateSlugButton: function () {
    avcms.misc.slugInput = $(this).closest("form").find("[data-slug-target]");
    avcms.misc.autoGenerateSlug()
}, disableAutoGenerateSlug: function () {
    $(this).data("modified", "true")
}, getCookie: function (e) {
    var s = e + "=", n = document.cookie.split(";");
    for (var a = 0; a < n.length; a++) {
        var t = n[a];
        while (t.charAt(0) == " ")t = t.substring(1, t.length);
        if (t.indexOf(s) == 0)return t.substring(s.length, t.length)
    }
    ;
    return null
}};
;
var avcms = avcms || {};
$(document).ready(function () {
    var e = $('body');
    e.on('submit', 'form', avcms.form.submitForm);
    e.on('click', '.reset-button', avcms.form.resetForm)
});
avcms.form = {submitForm: function () {
    var s = $(this).serialize(), e = $(this), r;
    if (e.attr('action')) {
        r = e.attr('action')
    } else {
        r = document.URL
    }
    ;
    avcms.misc.mainLoaderOn();
    $.ajax({type: 'POST', url: r, dataType: 'json', data: s, success: function (r) {
        avcms.misc.mainLoaderOff();
        e.find('.has-error').removeClass('has-error');
        var s = $(e).find('.form-messages');
        s.html('');
        if (r.form.has_errors === !0) {
            for (var a in r.form.errors) {
                e.find('[name=' + r.form['errors'][a]['param'] + ']').closest('.form-group').addClass('has-error');
                s.append('<div class="alert alert-danger animated bounce">' + r.form['errors'][a]['message'] + '</div>')
            }
        } else {
            if (r.redirect) {
                avcms.nav.goToPage(r.redirect)
            }
            ;
            if (r.form.success_message != undefined && r.form.success_message != null) {
                s.append('<div class="alert alert-success animated bounce">' + r.form.success_message + '</div>')
            }
            ;
            avcms.event.fireEvent('submit-form-success', [e, r])
        }
        ;
        avcms.event.fireEvent('submit-form-complete', [e, r])
    }, error: function (s, t, r) {
        var a = $(e).find('.form-messages');
        a.html('<div class="alert alert-danger animated bounce">Save Error: ' + r + '</div>')
    }});
    return!1
}, resetForm: function () {
    $(this).parent('form')[0].reset();
    var e = $(this).parent('form').attr('data-item-id');
    $('.browser-finder-item[data-id="' + e + '"]').removeClass('finder-item-edited');
    $('.header-label-container:visible').html('&nbsp;')
}};
!function (e) {
    if ('function' == typeof define && define.amd)define(['jquery', 'moment'], e); else {
        if (!jQuery)throw'bootstrap-datetimepicker requires jQuery to be loaded first';
        if (!moment)throw'bootstrap-datetimepicker requires moment.js to be loaded first';
        e(jQuery, moment);
    }
}(function (e, a) {
    if (void 0 === a)throw alert('momentjs is requried'), Error('momentjs is required');
    var n = 0, t = a, o = function (o, h) {
        var L = {pickDate: !0, pickTime: !0, useMinutes: !0, useSeconds: !1, useCurrent: !0, minuteStepping: 1, minDate: new t({y: 1900}), maxDate: (new t).add(100, 'y'), showToday: !0, collapse: !0, language: 'en', defaultDate: '', disabledDates: !1, enabledDates: !1, icons: {}, useStrict: !1, direction: 'auto', sideBySide: !1, daysOfWeekDisabled: !1}, O = {time: 'glyphicon glyphicon-time', date: 'glyphicon glyphicon-calendar', up: 'glyphicon glyphicon-chevron-up', down: 'glyphicon glyphicon-chevron-down'}, a = this, Y = function () {
            var s, i = !1;
            if (a.options = e.extend({}, L, h), a.options.icons = e.extend({}, O, a.options.icons), a.element = e(o), H(), !a.options.pickTime && !a.options.pickDate)throw Error('Must choose at least one picker');
            if (a.id = n++, t.lang(a.options.language), a.date = t(), a.unset = !1, a.isInput = a.element.is('input'), a.component = !1, a.element.hasClass('input-group') && (a.component = a.element.find(0 == a.element.find('.datepickerbutton').size() ? '[class^=\'input-group-\']' : '.datepickerbutton')), a.format = a.options.format, s = t().e$.i$, a.format || (a.format = a.options.pickDate ? s.L : '', a.options.pickDate && a.options.pickTime && (a.format += ' '), a.format += a.options.pickTime ? s.LT : '', a.options.useSeconds && (~s.LT.indexOf(' A') ? a.format = a.format.split(' A')[0] + ':ss A' : a.format += ':ss')), a.use24hours = a.format.toLowerCase().indexOf('a') < 1, a.component && (i = a.component.find('span')), a.options.pickTime && i && i.addClass(a.options.icons.time), a.options.pickDate && i && (i.removeClass(a.options.icons.time), i.addClass(a.options.icons.date)), a.widget = e(V()).appendTo('body'), a.options.useSeconds && !a.use24hours && a.widget.width(300), a.minViewMode = a.options.minViewMode || 0, 'string' == typeof a.minViewMode)switch (a.minViewMode) {
                case'months':
                    a.minViewMode = 1;
                    break;
                case'years':
                    a.minViewMode = 2;
                    break;
                default:
                    a.minViewMode = 0
            }
            ;
            if (a.viewMode = a.options.viewMode || 0, 'string' == typeof a.viewMode)switch (a.viewMode) {
                case'months':
                    a.viewMode = 1;
                    break;
                case'years':
                    a.viewMode = 2;
                    break;
                default:
                    a.viewMode = 0
            }
            ;
            if (a.options.disabledDates = w(a.options.disabledDates), a.options.enabledDates = w(a.options.enabledDates), a.startViewMode = a.viewMode, a.setMinDate(a.options.minDate), a.setMaxDate(a.options.maxDate), N(), U(), F(), E(), j(), l(), g(), S(), '' !== a.options.defaultDate && '' == v().val() && a.setValue(a.options.defaultDate), 1 !== a.options.minuteStepping) {
                var d = a.options.minuteStepping;
                a.date.minutes(Math.round(a.date.minutes() / d) * d % 60).seconds(0)
            }
        }, v = function () {
            return a.isInput ? a.element : dateStr = a.element.find('input')
        }, H = function () {
            var e;
            e = (a.element.is('input'), a.element.data()), void 0 !== e.dateFormat && (a.options.format = e.dateFormat), void 0 !== e.datePickdate && (a.options.pickDate = e.datePickdate), void 0 !== e.datePicktime && (a.options.pickTime = e.datePicktime), void 0 !== e.dateUseminutes && (a.options.useMinutes = e.dateUseminutes), void 0 !== e.dateUseseconds && (a.options.useSeconds = e.dateUseseconds), void 0 !== e.dateUsecurrent && (a.options.useCurrent = e.dateUsecurrent), void 0 !== e.dateMinutestepping && (a.options.minuteStepping = e.dateMinutestepping), void 0 !== e.dateMindate && (a.options.minDate = e.dateMindate), void 0 !== e.dateMaxdate && (a.options.maxDate = e.dateMaxdate), void 0 !== e.dateShowtoday && (a.options.showToday = e.dateShowtoday), void 0 !== e.dateCollapse && (a.options.collapse = e.dateCollapse), void 0 !== e.dateLanguage && (a.options.language = e.dateLanguage), void 0 !== e.dateDefaultdate && (a.options.defaultDate = e.dateDefaultdate), void 0 !== e.dateDisableddates && (a.options.disabledDates = e.dateDisableddates), void 0 !== e.dateEnableddates && (a.options.enabledDates = e.dateEnableddates), void 0 !== e.dateIcons && (a.options.icons = e.dateIcons), void 0 !== e.dateUsestrict && (a.options.useStrict = e.dateUsestrict), void 0 !== e.dateDirection && (a.options.direction = e.dateDirection), void 0 !== e.dateSidebyside && (a.options.sideBySide = e.dateSidebyside)
        }, C = function () {
            var n = 'absolute', t = a.component ? a.component.offset() : a.element.offset(), i = e(window);
            a.width = a.component ? a.component.outerWidth() : a.element.outerWidth(), t.top = t.top + a.element.outerHeight();
            var o;
            'up' === a.options.direction ? o = 'top' : 'bottom' === a.options.direction ? o = 'bottom' : 'auto' === a.options.direction && (o = t.top + a.widget.height() > i.height() + i.scrollTop() && a.widget.height() + a.element.outerHeight() < t.top ? 'top' : 'bottom'), 'top' === o ? (t.top -= a.widget.height() + a.element.outerHeight() + 15, a.widget.addClass('top').removeClass('bottom')) : (t.top += 1, a.widget.addClass('bottom').removeClass('top')), void 0 !== a.options.width && a.widget.width(a.options.width), 'left' === a.options.orientation && (a.widget.addClass('left-oriented'), t.left = t.left - a.widget.width() + 20), q() && (n = 'fixed', t.top -= i.scrollTop(), t.left -= i.scrollLeft()), i.width() < t.left + a.widget.outerWidth() ? (t.right = i.width() - t.left - a.width, t.left = 'auto', a.widget.addClass('pull-right')) : (t.right = 'auto', a.widget.removeClass('pull-right')), a.widget.css({position: n, top: t.top, left: t.left, right: t.right})
        }, m = function (e, i) {
            t(a.date).isSame(t(e)) || (a.element.trigger({type: 'dp.change', date: t(a.date), oldDate: t(e)}), 'change' !== i && a.element.change())
        }, y = function (e) {
            a.element.trigger({type: 'dp.error', date: t(e)})
        }, l = function (e) {
            t.lang(a.options.language);
            var i = e;
            i || (i = v().val(), i && (a.date = t(i, a.format, a.options.useStrict)), a.date || (a.date = t())), a.viewDate = t(a.date).startOf('month'), u(), k()
        }, N = function () {
            t.lang(a.options.language);
            var i, n = e('<tr>'), o = t.weekdaysMin();
            if (0 == t().e$.t$.dow)for (i = 0; 7 > i; i++)n.append('<th class="dow">' + o[i] + '</th>'); else for (i = 1; 8 > i; i++)n.append(7 == i ? '<th class="dow">' + o[0] + '</th>' : '<th class="dow">' + o[i] + '</th>');
            a.widget.find('.datepicker-days thead').append(n)
        }, U = function () {
            t.lang(a.options.language);
            for (var e = '', i = 0, o = t.monthsShort(); 12 > i;)e += '<span class="month">' + o[i++] + '</span>';
            a.widget.find('.datepicker-months td').append(e)
        }, u = function () {
            t.lang(a.options.language);
            var o, h, u, s, n, w, k, m, i = a.viewDate.year(), l = a.viewDate.month(), d = a.options.minDate.year(), v = a.options.minDate.month(), r = a.options.maxDate.year(), g = a.options.maxDate.month(), p = [], c = t.months();
            for (a.widget.find('.datepicker-days').find('.disabled').removeClass('disabled'), a.widget.find('.datepicker-months').find('.disabled').removeClass('disabled'), a.widget.find('.datepicker-years').find('.disabled').removeClass('disabled'), a.widget.find('.datepicker-days th:eq(1)').text(c[l] + ' ' + i), o = t(a.viewDate).subtract('months', 1), w = o.daysInMonth(), o.date(w).startOf('week'), (i == d && v >= l || d > i) && a.widget.find('.datepicker-days th:eq(0)').addClass('disabled'), (i == r && l >= g || i > r) && a.widget.find('.datepicker-days th:eq(2)').addClass('disabled'), h = t(o).add(42, 'd'); o.isBefore(h);) {
                if (o.weekday() === t().startOf('week').weekday() && (u = e('<tr>'), p.push(u)), s = '', o.year() < i || o.year() == i && o.month() < l ? s += ' old' : (o.year() > i || o.year() == i && o.month() > l) && (s += ' new'), o.isSame(t({y: a.date.year(), M: a.date.month(), d: a.date.date()})) && (s += ' active'), (f(o) || !T(o)) && (s += ' disabled'), a.options.showToday === !0 && o.isSame(t(), 'day') && (s += ' today'), a.options.daysOfWeekDisabled)for (n in a.options.daysOfWeekDisabled)if (o.day() == a.options.daysOfWeekDisabled[n]) {
                    s += ' disabled';
                    break
                }
                ;
                u.append('<td class="day' + s + '">' + o.date() + '</td>'), o.add(1, 'd')
            }
            ;
            for (a.widget.find('.datepicker-days tbody').empty().append(p), m = a.date.year(), c = a.widget.find('.datepicker-months').find('th:eq(1)').text(i).end().find('span').removeClass('active'), m === i && c.eq(a.date.month()).addClass('active'), d > m - 1 && a.widget.find('.datepicker-months th:eq(0)').addClass('disabled'), m + 1 > r && a.widget.find('.datepicker-months th:eq(2)').addClass('disabled'), n = 0; 12 > n; n++)i == d && v > n || d > i ? e(c[n]).addClass('disabled') : (i == r && n > g || i > r) && e(c[n]).addClass('disabled');
            for (p = '', i = 10 * parseInt(i / 10, 10), k = a.widget.find('.datepicker-years').find('th:eq(1)').text(i + '-' + (i + 9)).end().find('td'), a.widget.find('.datepicker-years').find('th').removeClass('disabled'), d > i && a.widget.find('.datepicker-years').find('th:eq(0)').addClass('disabled'), i + 9 > r && a.widget.find('.datepicker-years').find('th:eq(2)').addClass('disabled'), i -= 1, n = -1; 11 > n; n++)p += '<span class="year' + (-1 === n || 10 === n ? ' old' : '') + (m === i ? ' active' : '') + (d > i || i > r ? ' disabled' : '') + '">' + i + '</span>', i += 1;
            k.html(p)
        }, F = function () {
            t.lang(a.options.language);
            var i, n, o, s = a.widget.find('.timepicker .timepicker-hours table'), e = '';
            if (s.parent().hide(), a.use24hours)for (i = 0, n = 0; 6 > n; n += 1) {
                for (e += '<tr>', o = 0; 4 > o; o += 1)e += '<td class="hour">' + d('' + i) + '</td>', i++;
                e += '</tr>'
            } else for (i = 1, n = 0; 3 > n; n += 1) {
                for (e += '<tr>', o = 0; 4 > o; o += 1)e += '<td class="hour">' + d('' + i) + '</td>', i++;
                e += '</tr>'
            }
            ;
            s.html(e)
        }, E = function () {
            var o, n, s = a.widget.find('.timepicker .timepicker-minutes table'), e = '', i = 0, t = a.options.minuteStepping;
            for (s.parent().hide(), 1 == t && (t = 5), o = 0; o < Math.ceil(60 / t / 4); o++) {
                for (e += '<tr>', n = 0; 4 > n; n += 1)60 > i ? (e += '<td class="minute">' + d('' + i) + '</td>', i += t) : e += '<td></td>';
                e += '</tr>'
            }
            ;
            s.html(e)
        }, j = function () {
            var t, i, n = a.widget.find('.timepicker .timepicker-seconds table'), e = '', o = 0;
            for (n.parent().hide(), t = 0; 3 > t; t++) {
                for (e += '<tr>', i = 0; 4 > i; i += 1)e += '<td class="second">' + d('' + o) + '</td>', o += 5;
                e += '</tr>'
            }
            ;
            n.html(e)
        }, k = function () {
            if (a.date) {
                var t = a.widget.find('.timepicker span[data-time-component]'), e = a.date.hours(), i = 'AM';
                a.use24hours || (e >= 12 && (i = 'PM'), 0 === e ? e = 12 : 12 != e && (e %= 12), a.widget.find('.timepicker [data-action=togglePeriod]').text(i)), t.filter('[data-time-component=hours]').text(d(e)), t.filter('[data-time-component=minutes]').text(d(a.date.minutes())), t.filter('[data-time-component=seconds]').text(d(a.date.second()))
            }
        }, I = function (i) {
            i.stopPropagation(), i.preventDefault(), a.unset = !1;
            var n, d, r, c, o = e(i.target).closest('span, td, th'), l = t(a.date);
            if (1 === o.length && !o.is('.disabled'))switch (o[0].nodeName.toLowerCase()) {
                case'th':
                    switch (o[0].className) {
                        case'switch':
                            g(1);
                            break;
                        case'prev':
                        case'next':
                            r = s.modes[a.viewMode].navStep, 'prev' === o[0].className && (r = -1 * r), a.viewDate.add(r, s.modes[a.viewMode].navFnc), u()
                    }
                    ;
                    break;
                case'span':
                    o.is('.month') ? (n = o.parent().find('span').index(o), a.viewDate.month(n)) : (d = parseInt(o.text(), 10) || 0, a.viewDate.year(d)), a.viewMode === a.minViewMode && (a.date = t({y: a.viewDate.year(), M: a.viewDate.month(), d: a.viewDate.date(), h: a.date.hours(), m: a.date.minutes(), s: a.date.seconds()}), m(l, i.type), p()), g(-1), u();
                    break;
                case'td':
                    o.is('.day') && (c = parseInt(o.text(), 10) || 1, n = a.viewDate.month(), d = a.viewDate.year(), o.is('.old') ? 0 === n ? (n = 11, d -= 1) : n -= 1 : o.is('.new') && (11 == n ? (n = 0, d += 1) : n += 1), a.date = t({y: d, M: n, d: c, h: a.date.hours(), m: a.date.minutes(), s: a.date.seconds()}), a.viewDate = t({y: d, M: n, d: Math.min(28, c)}), u(), p(), m(l, i.type))
            }
        }, b = {incrementHours: function () {
            c('add', 'hours', 1)
        }, incrementMinutes: function () {
            c('add', 'minutes', a.options.minuteStepping)
        }, incrementSeconds: function () {
            c('add', 'seconds', 1)
        }, decrementHours: function () {
            c('subtract', 'hours', 1)
        }, decrementMinutes: function () {
            c('subtract', 'minutes', a.options.minuteStepping)
        }, decrementSeconds: function () {
            c('subtract', 'seconds', 1)
        }, togglePeriod: function () {
            var e = a.date.hours();
            e >= 12 ? e -= 12 : e += 12, a.date.hours(e)
        }, showPicker: function () {
            a.widget.find('.timepicker > div:not(.timepicker-picker)').hide(), a.widget.find('.timepicker .timepicker-picker').show()
        }, showHours: function () {
            a.widget.find('.timepicker .timepicker-picker').hide(), a.widget.find('.timepicker .timepicker-hours').show()
        }, showMinutes: function () {
            a.widget.find('.timepicker .timepicker-picker').hide(), a.widget.find('.timepicker .timepicker-minutes').show()
        }, showSeconds: function () {
            a.widget.find('.timepicker .timepicker-picker').hide(), a.widget.find('.timepicker .timepicker-seconds').show()
        }, selectHour: function (t) {
            var o = i.widget.find('.timepicker [data-action=togglePeriod]').text(), a = parseInt(e(t.target).text(), 10)
            'PM' == o && (a += 12), i.date.hours(a), n.showPicker.call(i)
        }, selectMinute: function (t) {
            a.date.minutes(parseInt(e(t.target).text(), 10)), b.showPicker.call(a)
        }, selectSecond: function (t) {
            a.date.seconds(parseInt(e(t.target).text(), 10)), b.showPicker.call(a)
        }}, W = function (i) {
            var n = t(a.date), s = e(i.currentTarget).data('action'), o = b[s].apply(a, arguments);
            return D(i), a.date || (a.date = t({y: 1970})), p(), k(), m(n, i.type), o
        }, D = function (e) {
            e.stopPropagation(), e.preventDefault()
        }, M = function (i) {
            t.lang(a.options.language);
            var s = e(i.target), n = t(a.date), o = t(s.val(), a.format, a.options.useStrict);
            o.isValid() && !f(o) && T(o) ? (l(), a.setValue(o), m(n, i.type), p()) : (a.viewDate = n, m(n, i.type), y(o), a.unset = !0)
        }, g = function (e) {
            e && (a.viewMode = Math.max(a.minViewMode, Math.min(2, a.viewMode + e)));
            s.modes[a.viewMode].clsName;
            a.widget.find('.datepicker > div').hide().filter('.datepicker-' + s.modes[a.viewMode].clsName).show()
        }, S = function () {
            var o, n, t, s, i;
            a.widget.on('click', '.datepicker *', e.proxy(I, this)), a.widget.on('click', '[data-action]', e.proxy(W, this)), a.widget.on('mousedown', e.proxy(D, this)), a.options.pickDate && a.options.pickTime && a.widget.on('click.togglePicker', '.accordion-toggle', function (d) {
                if (d.stopPropagation(), o = e(this), n = o.closest('ul'), t = n.find('.in'), s = n.find('.collapse:not(.in)'), t && t.length) {
                    if (i = t.data('collapse'), i && i.date - transitioning)return;
                    t.collapse('hide'), s.collapse('show'), o.find('span').toggleClass(a.options.icons.time + ' ' + a.options.icons.date), a.element.find('.input-group-addon span').toggleClass(a.options.icons.time + ' ' + a.options.icons.date)
                }
            }), a.isInput ? a.element.on({focus: e.proxy(a.show, this), change: e.proxy(M, this), blur: e.proxy(a.hide, this)}) : (a.element.on({change: e.proxy(M, this)}, 'input'), a.component ? a.component.on('click', e.proxy(a.show, this)) : a.element.on('click', e.proxy(a.show, this)))
        }, B = function () {
            e(window).on('resize.datetimepicker' + a.id, e.proxy(C, this)), a.isInput || e(document).on('mousedown.datetimepicker' + a.id, e.proxy(a.hide, this))
        }, P = function () {
            a.widget.off('click', '.datepicker *', a.click), a.widget.off('click', '[data-action]'), a.widget.off('mousedown', a.stopEvent), a.options.pickDate && a.options.pickTime && a.widget.off('click.togglePicker'), a.isInput ? a.element.off({focus: a.show, change: a.change}) : (a.element.off({change: a.change}, 'input'), a.component ? a.component.off('click', a.show) : a.element.off('click', a.show))
        }, x = function () {
            e(window).off('resize.datetimepicker' + a.id), a.isInput || e(document).off('mousedown.datetimepicker' + a.id)
        }, q = function () {
            if (a.element) {
                var t, o = a.element.parents(), i = !1;
                for (t = 0; t < o.length; t++)if ('fixed' == e(o[t]).css('position')) {
                    i = !0;
                    break
                }
                ;
                return i
            }
            ;
            return!1
        }, p = function () {
            t.lang(a.options.language);
            var e = '';
            a.unset || (e = t(a.date).format(a.format)), v().val(e), a.element.data('date', e), a.options.pickTime || a.hide()
        }, c = function (e, i, n) {
            t.lang(a.options.language);
            var o;
            return'add' == e ? (o = t(a.date), 23 == o.hours() && o.add(n, i), o.add(n, i)) : o = t(a.date).subtract(n, i), f(t(o.subtract(n, i))) || f(o) ? void y(o.format(a.format)) : ('add' == e ? a.date.add(n, i) : a.date.subtract(n, i), void(a.unset = !1))
        }, f = function (e) {
            return t.lang(a.options.language), e.isAfter(a.options.maxDate) || e.isBefore(a.options.minDate) ? !0 : a.options.disabledDates === !1 ? !1 : a.options.disabledDates[t(e).format('YYYY-MM-DD')] === !0
        }, T = function (e) {
            return t.lang(a.options.language), a.options.enabledDates === !1 ? !0 : a.options.enabledDates[t(e).format('YYYY-MM-DD')] === !0
        }, w = function (e) {
            var o = {}, a = 0;
            for (i = 0; i < e.length; i++)dDate = t(e[i]), dDate.isValid() && (o[dDate.format('YYYY-MM-DD')] = !0, a++);
            return a > 0 ? o : !1
        }, d = function (e) {
            return e = '' + e, e.length >= 2 ? e : '0' + e
        }, V = function () {
            if (a.options.pickDate && a.options.pickTime) {
                var e = '';
                return e = '<div class="bootstrap-datetimepicker-widget' + (a.options.sideBySide ? ' timepicker-sbs' : '') + ' dropdown-menu" style="z-index:9999 !important;">', e += a.options.sideBySide ? '<div class="row"><div class="col-sm-6 datepicker">' + s.template + '</div><div class="col-sm-6 timepicker">' + r.getTemplate() + '</div></div>' : '<ul class="list-unstyled"><li' + (a.options.collapse ? ' class="collapse in"' : '') + '><div class="datepicker">' + s.template + '</div></li><li class="picker-switch accordion-toggle"><a class="btn" style="width:100%"><span class="' + a.options.icons.time + '"></span></a></li><li' + (a.options.collapse ? ' class="collapse"' : '') + '><div class="timepicker">' + r.getTemplate() + '</div></li></ul>', e += '</div>'
            }
            ;
            return a.options.pickTime ? '<div class="bootstrap-datetimepicker-widget dropdown-menu"><div class="timepicker">' + r.getTemplate() + '</div></div>' : '<div class="bootstrap-datetimepicker-widget dropdown-menu"><div class="datepicker">' + s.template + '</div></div>'
        }, s = {modes: [
            {clsName: 'days', navFnc: 'month', navStep: 1},
            {clsName: 'months', navFnc: 'year', navStep: 1},
            {clsName: 'years', navFnc: 'year', navStep: 10}
        ], headTemplate: '<thead><tr><th class="prev">&lsaquo;</th><th colspan="5" class="switch"></th><th class="next">&rsaquo;</th></tr></thead>', contTemplate: '<tbody><tr><td colspan="7"></td></tr></tbody>'}, r = {hourTemplate: '<span data-action="showHours"   data-time-component="hours"   class="timepicker-hour"></span>', minuteTemplate: '<span data-action="showMinutes" data-time-component="minutes" class="timepicker-minute"></span>', secondTemplate: '<span data-action="showSeconds"  data-time-component="seconds" class="timepicker-second"></span>'};
        s.template = '<div class="datepicker-days"><table class="table-condensed">' + s.headTemplate + '<tbody></tbody></table></div><div class="datepicker-months"><table class="table-condensed">' + s.headTemplate + s.contTemplate + '</table></div><div class="datepicker-years"><table class="table-condensed">' + s.headTemplate + s.contTemplate + '</table></div>', r.getTemplate = function () {
            return'<div class="timepicker-picker"><table class="table-condensed"><tr><td><a href="#" class="btn" data-action="incrementHours"><span class="' + a.options.icons.up + '"></span></a></td><td class="separator"></td><td>' + (a.options.useMinutes ? '<a href="#" class="btn" data-action="incrementMinutes"><span class="' + a.options.icons.up + '"></span></a>' : '') + '</td>' + (a.options.useSeconds ? '<td class="separator"></td><td><a href="#" class="btn" data-action="incrementSeconds"><span class="' + a.options.icons.up + '"></span></a></td>' : '') + (a.use24hours ? '' : '<td class="separator"></td>') + '</tr><tr><td>' + r.hourTemplate + '</td> <td class="separator">:</td><td>' + (a.options.useMinutes ? r.minuteTemplate : '<span class="timepicker-minute">00</span>') + '</td> ' + (a.options.useSeconds ? '<td class="separator">:</td><td>' + r.secondTemplate + '</td>' : '') + (a.use24hours ? '' : '<td class="separator"></td><td><button type="button" class="btn btn-primary" data-action="togglePeriod"></button></td>') + '</tr><tr><td><a href="#" class="btn" data-action="decrementHours"><span class="' + a.options.icons.down + '"></span></a></td><td class="separator"></td><td>' + (a.options.useMinutes ? '<a href="#" class="btn" data-action="decrementMinutes"><span class="' + a.options.icons.down + '"></span></a>' : '') + '</td>' + (a.options.useSeconds ? '<td class="separator"></td><td><a href="#" class="btn" data-action="decrementSeconds"><span class="' + a.options.icons.down + '"></span></a></td>' : '') + (a.use24hours ? '' : '<td class="separator"></td>') + '</tr></table></div><div class="timepicker-hours" data-action="selectHour"><table class="table-condensed"></table></div><div class="timepicker-minutes" data-action="selectMinute"><table class="table-condensed"></table></div>' + (a.options.useSeconds ? '<div class="timepicker-seconds" data-action="selectSecond"><table class="table-condensed"></table></div>' : '')
        }, a.destroy = function () {
            P(), x(), a.widget.remove(), a.element.removeData('DateTimePicker'), a.component && a.component.removeData('DateTimePicker')
        }, a.show = function (e) {
            if (a.options.useCurrent && '' == v().val())if (1 !== a.options.minuteStepping) {
                var i = t(), o = a.options.minuteStepping;
                i.minutes(Math.round(i.minutes() / o) * o % 60).seconds(0), a.setValue(i.format(a.format))
            } else a.setValue(t().format(a.format));
            a.widget.show(), a.height = a.component ? a.component.outerHeight() : a.element.outerHeight(), C(), a.element.trigger({type: 'dp.show', date: t(a.date)}), B(), e && D(e)
        }, a.disable = function () {
            var e = a.element.find('input');
            e.prop('disabled') || (e.prop('disabled', !0), P())
        }, a.enable = function () {
            var e = a.element.find('input');
            e.prop('disabled') && (e.prop('disabled', !1), S())
        }, a.hide = function (i) {
            if (!i || !e(i.target).is(a.element.attr('id'))) {
                var o, n, s = a.widget.find('.collapse');
                for (o = 0; o < s.length; o++)if (n = s.eq(o).data('collapse'), n && n.date - transitioning)return;
                a.widget.hide(), a.viewMode = a.startViewMode, g(), a.element.trigger({type: 'dp.hide', date: t(a.date)}), x()
            }
        }, a.setValue = function (e) {
            t.lang(a.options.language), e ? a.unset = !1 : (a.unset = !0, p()), t.isMoment(e) || (e = t(e, a.format)), e.isValid() ? (a.date = e, p(), a.viewDate = t({y: a.date.year(), M: a.date.month()}), u(), k()) : y(e)
        }, a.getDate = function () {
            return a.unset ? null : a.date
        }, a.setDate = function (e) {
            var i = t(a.date);
            a.setValue(e ? e : null), m(i, 'function')
        }, a.setDisabledDates = function (e) {
            a.options.disabledDates = w(e), a.viewDate && l()
        }, a.setEnabledDates = function (e) {
            a.options.enabledDates = w(e), a.viewDate && l()
        }, a.setMaxDate = function (e) {
            void 0 != e && (a.options.maxDate = t(e), a.viewDate && l())
        }, a.setMinDate = function (e) {
            void 0 != e && (a.options.minDate = t(e), a.viewDate && l())
        }, Y()
    };
    e.fn.datetimepicker = function (t) {
        return this.each(function () {
            var i = e(this), a = i.data('DateTimePicker');
            a || i.data('DateTimePicker', new o(this, t))
        })
    }
})
;
var avcms = avcms || {};
$(document).ready(function () {
    var e = $('body');
    e.on('change', '.ajax-editor form', avcms.browser.editorFormChanged);
    e.on('change', 'form[name="filter_form"] select', avcms.browser.changeFinderFilters);
    e.on('keyup', 'form[name="filter_form"] input', avcms.browser.changeFinderFilters);
    e.on('click', '.clear-search', avcms.browser.clearSearch);
    e.on('click', '.select-all-button', avcms.browser.selectAllFinderResults);
    e.on('click', '.deselect-all-button', avcms.browser.deselectAllFinderResults);
    e.on('click', '.finder-item-checkbox-container :checkbox', avcms.browser.checkFinderChecked);
    e.on('click', '[data-bulk-delete-url]', avcms.browser.deleteCheckedResults);
    e.on('click', '[data-toggle-published-url], [data-toggle-unpublished-url]', avcms.browser.togglePublishedCheckedResults);
    e.on('click', '.avcms-delete-item', avcms.browser.deleteItemButton);
    e.on('click', '.avcms-toggle-published', avcms.browser.togglePublishedButton);
    avcms.event.addEvent('page-modified', avcms.browser.setBrowserFocus);
    avcms.browser.setBrowserFocus();
    avcms.event.addEvent('submit-form-complete', avcms.browser.browserFormSubmitted);
    avcms.event.addEvent('submit-form-success', avcms.browser.editorAddItemFormSubmitEvent);
    avcms.event.addEvent('page-modified', function () {
        $('.nano-content').on('scroll', avcms.browser.finderLoadMore);
        $(window).on('scroll', avcms.browser.finderLoadMore);
        avcms.browser.finder_loading = 0
    })
});
avcms.browser = {finder_loading: 0, browserFormSubmitted: function (e, r) {
    if ($('.browser').length < 1) {
        return
    }
    ;
    if (r.form.has_errors === !1) {
        var a = e.attr('data-item-id');
        if (a !== undefined) {
            $(e).find('*').filter(':input:not(button)').each(function (e, r) {
                r = $(r);
                var t = r.attr('name');
                if (r.prop('tagName') != 'SELECT') {
                    var i = r.val()
                } else {
                    var i = r.find(':selected').text()
                }
                ;
                $('[data-id="' + a + '"][data-field="' + t + '"], [data-id="' + a + '"] [data-field="' + t + '"]').text(i).text()
            });
            if (a != 0) {
                $('.header-label-container:visible').html('<span class="label label-success animated flipInY">Saved</span>')
            }
            ;
            $('.browser-finder-item[data-id="' + a + '"]').addClass('finder-item-saved').removeClass('finder-item-edited')
        }
        ;
        var t = $('.finder-ajax > [data-url]'), i = t.data('url');
        $.get(i + '?id=' + r.id, function (e) {
            if (e) {
                if (a != 0) {
                    var i = $($.parseHTML(e)).filter('[data-id="' + a + '"]').html();
                    t.find('[data-id="' + a + '"]').html(i)
                } else {
                    $('.remove-header').remove();
                    var r = $($.parseHTML(e));
                    r.filter('.browser-finder-header').addClass('remove-header');
                    r.find('.page').html('New');
                    t.prepend(r)
                }
            }
        })
    }
}, setBrowserFocus: function () {
    var a = $('.browser-finder'), e = $('.browser-editor');
    if ($('.ajax-editor-inner').children().filter(':visible').length < 1) {
        if (!a.hasClass('finder-has-focus')) {
            a.addClass('finder-has-focus');
            e.removeClass('editor-has-focus')
        }
    } else {
        if (!e.hasClass('editor-has-focus')) {
            a.removeClass('finder-has-focus');
            e.addClass('editor-has-focus')
        }
    }
}, finderLoadMore: function () {
    if ($('.browser-finder').length < 1) {
        return
    }
    ;
    var e, a;
    if (!$(this).hasClass('nano-content')) {
        e = $('.browser-finder-results').find('.nano-content');
        a = $(document).height()
    } else {
        e = $(this);
        a = $(this)[0].scrollHeight
    }
    ;
    if (($(this).scrollTop() + ($(this).innerHeight() + 300) >= a) && (window.avcms.browser.finder_loading != 1)) {
        avcms.browser.finder_loading = 1;
        if (avcms.browser.finder_page === undefined) {
            avcms.browser.finder_page = 1
        }
        ;
        var r = e.data('page');
        if (!r) {
            r = 1
        }
        ;
        var i = r + 1, s = $('form[name="filter_form"]').serialize() + '&page=' + i, t = e.find('[data-url]');
        $.get(t.data('url') + '?' + s, function (a) {
            if (a) {
                if (a.indexOf('NORESULTS') <= 0) {
                    t.append(a);
                    avcms.browser.finder_loading = 0;
                    e.data('page', i);
                    $('.nano').nanoScroller({iOSNativeScrolling: !1})
                }
            }
        })
    }
}, changeFinderFilters: function () {
    var a = $('form[name="filter_form"]').serialize(), e = $('.finder-ajax').find('[data-url]');
    avcms.browser.finder_loading = 1;
    $.get(e.data('url') + '?' + a, function (a) {
        if (a) {
            e.html(a);
            avcms.browser.finder_loading = 0;
            $('.finder-ajax').parents('.nano-content').data('page', 1);
            $('.nano').nanoScroller({iOSNativeScrolling: !1})
        }
    })
}, editorFormChanged: function () {
    var e = $(this).attr('data-item-id');
    if (e != 0) {
        $('.header-label-container:visible').html('<span class="label label-warning animated flipInY">Edited</span>');
        $('.browser-finder-item[data-id="' + e + '"]').addClass('finder-item-edited');
        $('.browser-finder-item[data-id="' + e + '"]').removeClass('finder-item-saved')
    }
}, editorAddItemFormSubmitEvent: function (e, a) {
    var r = e.data('item-id');
    if (r === 0) {
        e[0].reset();
        e.find('.form-messages').html('')
    }
}, clearSearch: function () {
    $(this).closest('.input-group').find('input').val('');
    avcms.browser.changeFinderFilters()
}, checkFinderChecked: function () {
    var e = $('.finder-item-checkbox-container :checkbox:checked').length, a = $('.browser-finder-checked-options').outerHeight();
    if (e) {
        $('.browser-finder-checked-options').show();
        $('.finder-ajax').css('padding-bottom', a)
    } else {
        $('.browser-finder-checked-options').hide();
        $('.finder-ajax').css('padding-bottom', 0)
    }
    ;
    $('.selected-count').text(e);
    avcms.browser.getFinderSelectedIds()
}, selectAllFinderResults: function () {
    $('.finder-item-checkbox-container :checkbox').prop('checked', !0);
    avcms.browser.checkFinderChecked()
}, deselectAllFinderResults: function () {
    $('.finder-item-checkbox-container :checkbox').prop('checked', !1);
    avcms.browser.checkFinderChecked()
}, getFinderSelectedIds: function () {
    var e = [];
    $('.finder-item-checkbox-container :checkbox:checked').each(function () {
        e.push($(this).parents('.browser-finder-item').data('id'))
    });
    return e
}, deleteCheckedResults: function () {
    if (confirm('Are you sure you want to delete these ' + $('.selected-count').text() + ' items?')) {
        var e = $(this).data('bulk-delete-url');
        $.ajax({type: 'POST', url: e, data: {'ids': avcms.browser.getFinderSelectedIds()}, dataType: 'json', success: function (e) {
            $('.finder-item-checkbox-container :checkbox:checked').each(function () {
                $(this).parents('.browser-finder-item').remove();
                avcms.browser.checkFinderChecked()
            })
        }})
    }
}, deleteItemButton: function () {
    if (confirm('Are you sure you want to delete this item?')) {
        var a = $(this), e = a.parents('[data-id]').data('id');
        $.ajax({type: 'POST', url: $(this).data('delete-url'), data: {'id': e}, dataType: 'json', success: function (r) {
            a.parents('[data-id]').remove();
            avcms.browser.checkFinderChecked();
            var t = $('.ajax-editor-inner').filter(':visible').data('ajax-url');
            if (t.indexOf(e) > -1) {
                avcms.nav.goToPage($('.add-item > a').attr('href'))
            }
        }})
    }
}, deleteCheckedResults: function () {
    if (confirm('Are you sure you want to delete these ' + $('.selected-count').text() + ' items?')) {
        var e = $(this).data('bulk-delete-url');
        $.ajax({type: 'POST', url: e, data: {'ids': avcms.browser.getFinderSelectedIds()}, dataType: 'json', success: function (e) {
            $('.finder-item-checkbox-container :checkbox:checked').each(function () {
                $(this).parents('.browser-finder-item').remove();
                avcms.browser.checkFinderChecked()
            })
        }})
    }
}, togglePublishedButton: function () {
    var r = $(this), a = r.parents('[data-id]').data('id');
    $(this).children('.glyphicon').toggleClass('glyphicon-eye-open glyphicon-eye-close');
    $(this).toggleClass('btn-default btn-danger');
    var e;
    if ($(this).hasClass('btn-danger')) {
        e = 0
    } else {
        e = 1
    }
    ;
    $.ajax({type: 'POST', url: $(this).data('toggle-publish-url'), data: {'id': a, 'published': e}, dataType: 'json', success: function (r) {
        var t = $('form[data-item-id="' + a + '"');
        t.find('[name="published"][value="' + e + '"]').prop('checked', !0)
    }})
}, togglePublishedCheckedResults: function () {
    var e;
    if ($(this).data('toggle-published-url')) {
        e = $(this).data('toggle-published-url');
        published = 1
    } else {
        e = $(this).data('toggle-unpublished-url');
        published = 0
    }
    ;
    var a = avcms.browser.getFinderSelectedIds();
    $.ajax({type: 'POST', url: e, data: {'ids': a, 'published': published}, dataType: 'json', success: function (e) {
        $.each(a, function (e, r) {
            var t = $('form[data-item-id="' + r + '"');
            t.find('[name="published"][value="' + published + '"]').prop('checked', !0);
            var i = $('.browser-finder-item[data-id=' + r + ']'), a = i.find('.avcms-toggle-published');
            if (published === 1) {
                a.removeClass('btn-danger');
                a.addClass('btn-default');
                a.children('.glyphicon').removeClass('glyphicon-eye-close');
                a.children('.glyphicon').addClass('glyphicon-eye-open')
            } else {
                a.addClass('btn-danger');
                a.removeClass('btn-default');
                a.children('.glyphicon').addClass('glyphicon-eye-close');
                a.children('.glyphicon').removeClass('glyphicon-eye-open')
            }
        })
    }})
}};
;
var avcms = avcms || {};
$(document).ready(function () {
    var a = $('body');
    History.Adapter.bind(window, 'statechange', avcms.nav.pageChange);
    a.on('click', 'a', avcms.nav.goToPage);
    avcms.nav.onPageModified();
    avcms.nav.setDataAjaxUrl($('.ajax-editor'), 'editor')
});
avcms.nav = {goToPage: function (a, e) {
    if (a === undefined || typeof a != 'string') {
        a = $(this).attr('href')
    }
    ;
    if (a.indexOf('admin') <= 0) {
        return!0
    }
    ;
    if (a != '#') {
        History.pushState({state: 1}, document.title, a);
        return!1
    } else if (set_url !== undefined && typeof set_url == 'string') {
        if (a != '#') {
            History.pushState({state: 1}, e, set_url);
            return!1
        }
    }
    ;
    return!0
}, pageChange: function () {
    var e = avcms.nav.getCurrentUrl(), o = avcms.nav.getPreviousUrl(), v = o.split('admin/').pop().split('/'), l = e.split('admin/').pop().split('/'), s = avcms.fn.removeAllAfter('?', v[0]), t, a;
    if ($.trim(s) == $.trim(l[0]) && $('.ajax-editor').length > 0) {
        a = 'editor';
        t = 'append'
    } else {
        a = 'main';
        t = 'html'
    }
    ;
    var r = $('.ajax-' + a), n = !0;
    if (a == 'editor') {
        var i = $('div[data-ajax-url="' + e + '"]');
        if (i.length > 0) {
            $('.ajax-editor-inner').hide();
            i.show();
            avcms.nav.setPageTitle(e);
            avcms.browser.setBrowserFocus();
            n = !1
        }
    }
    ;
    if (e.indexOf('?') < 0) {
        var d = e + '?'
    }
    ;
    if (n === !0) {
        avcms.misc.mainLoaderOn();
        $.get(d + '&ajax_depth=' + a, function (n) {
            $('.ajax-' + a + '-inner').hide();
            r[t](n);
            avcms.nav.setDataAjaxUrl(r, a);
            avcms.nav.setPageTitle(e);
            window.scrollTo(0, 0);
            avcms.nav.onPageModified();
            avcms.misc.mainLoaderOff()
        })
    }
}, onPageModified: function () {
    $('textarea[name=body]').markdown({autofocus: !1, savable: !1});
    $('select:not(.no_select2)').select2({minimumResultsForSearch: 10});
    $('.nano').nanoScroller({iOSNativeScrolling: !1});
    avcms.event.fireEvent('page-modified')
}, setPageTitle: function (a) {
    var e = $('[data-ajax-url="' + a + '"]').find('.ajax_page_title');
    if (e.length) {
        if (e.html() != '') {
            document.title = e.text()
        } else {
            document.title = 'Unnamed Page'
        }
    }
}, setDataAjaxUrl: function (a, t) {
    var e = a.find('.ajax-' + t + '-inner').last();
    if (e.data('ajax-url') == undefined) {
        e.attr('data-ajax-url', avcms.nav.getCurrentUrl())
    }
}, getCurrentUrl: function (e) {
    var i = History.getState(), t = History.savedStates, n = t.length - 1, a = t[n].hash;
    if (!e) {
        a = avcms.fn.removeParameter(a, '_suid')
    }
    ;
    if (a.indexOf('=') <= 0) {
        a = a.replace('?', '')
    }
    ;
    return a
}, getPreviousUrl: function () {
    var t = History.getState(), a = History.savedStates, e = a.length - 2;
    return a[e].hash
}};
(function (e) {
    var t = e.Markdown = function f(e) {
        switch (typeof e) {
            case"undefined":
                this.dialect = f.dialects.Gruber;
                break;
            case"object":
                this.dialect = e;
                break;
            default:
                if (e in f.dialects) {
                    this.dialect = f.dialects[e]
                } else {
                    throw new Error("Unknown Markdown dialect '" + String(e) + "'");
                }
                ;
                break
        }
        ;
        this.em_state = [];
        this.strong_state = [];
        this.debug_indent = ""
    };
    e.parse = function (e, n) {
        var r = new t(n);
        return r.toTree(e)
    };
    e.toHTML = function (t, n, r) {
        var i = e.toHTMLTree(t, n, r);
        return e.renderJsonML(i)
    };
    e.toHTMLTree = function (e, t, r) {
        if (typeof e === "string")e = this.parse(e, t);
        var i = n(e), l = {};
        if (i && i.references) {
            l = i.references
        }
        ;
        var s = g(e, l, r);
        d(s);
        return s
    };
    function k() {
        return"Markdown.mk_block( " + uneval(this.toString()) + ", " + uneval(this.trailing) + ", " + uneval(this.lineNumber) + " )"
    };
    function v() {
        var e = require("util");
        return"Markdown.mk_block( " + e.inspect(this.toString()) + ", " + e.inspect(this.trailing) + ", " + e.inspect(this.lineNumber) + " )"
    };
    var r = t.mk_block = function (e, t, n) {
        if (arguments.length == 1)t = "\n\n";
        var r = new String(e);
        r.trailing = t;
        r.inspect = v;
        r.toSource = k;
        if (n != undefined)r.lineNumber = n;
        return r
    };

    function h(e) {
        var n = 0, t = -1;
        while ((t = e.indexOf("\n", t + 1)) !== -1)n++;
        return n
    };
    t.prototype.split_blocks = function (e, t) {
        var l = /([\s\S]+?)($|\n(?:\s*\n|$)+)/g, s = [], n, i = 1;
        if ((n = /^(\s*\n)/.exec(e)) != null) {
            i += h(n[0]);
            l.lastIndex = n[0].length
        }
        while ((n = l.exec(e)) !== null) {
            s.push(r(n[1], n[2], i));
            i += h(n[0])
        }
        ;
        return s
    };
    t.prototype.processBlock = function (e, t) {
        var r = this.dialect.block, l = r.__order__;
        if ("__call__" in r) {
            return r.__call__.call(this, e, t)
        }
        ;
        for (var i = 0; i < l.length; i++) {
            var n = r[l[i]].call(this, e, t);
            if (n) {
                if (!c(n) || (n.length > 0 && !(c(n[0]))))this.debug(l[i], "didn't return a proper array");
                return n
            }
        }
        ;
        return[]
    };
    t.prototype.processInline = function (e) {
        return this.dialect.inline.__call__.call(this, String(e))
    };
    t.prototype.toTree = function (e, t) {
        var n = e instanceof Array ? e : this.split_blocks(e), i = this.tree;
        try {
            this.tree = t || this.tree || ["markdown"];
            n:while (n.length) {
                var r = this.processBlock(n.shift(), n);
                if (!r.length)continue;
                n;
                this.tree.push.apply(this.tree, r)
            }
            ;
            return this.tree
        } finally {
            if (t) {
                this.tree = i
            }
        }
    };
    t.prototype.debug = function () {
        var e = Array.prototype.slice.call(arguments);
        e.unshift(this.debug_indent);
        if (typeof print !== "undefined")print.apply(print, e);
        if (typeof console !== "undefined" && typeof console.log !== "undefined")console.log.apply(null, e)
    };
    t.prototype.loop_re_over_block = function (e, t, n) {
        var i, r = t.valueOf();
        while (r.length && (i = e.exec(r)) != null) {
            r = r.substr(i[0].length);
            n.call(this, i)
        }
        ;
        return r
    };
    t.dialects = {};
    t.dialects.Gruber = {block: {atxHeader: function (e, t) {
        var n = e.match(/^(#{1,6})\s*(.*?)\s*#*\s*(?:\n|$)/);
        if (!n)return undefined;
        var i = ["header", {level: n[1].length}];
        Array.prototype.push.apply(i, this.processInline(n[2]));
        if (n[0].length < e.length)t.unshift(r(e.substr(n[0].length), e.trailing, e.lineNumber + 2));
        return[i]
    }, setextHeader: function (e, t) {
        var n = e.match(/^(.*)\n([-=])\2\2+(?:\n|$)/);
        if (!n)return undefined;
        var l = (n[2] === "=") ? 1 : 2, i = ["header", {level: l}, n[1]];
        if (n[0].length < e.length)t.unshift(r(e.substr(n[0].length), e.trailing, e.lineNumber + 2));
        return[i]
    }, code: function (e, t) {
        var i = [], n = /^(?: {0,3}\t| {4})(.*)\n?/, s;
        if (!e.match(n))return undefined;
        block_search:do {
            var l = this.loop_re_over_block(n, e.valueOf(), function (e) {
                i.push(e[1])
            });
            if (l.length) {
                t.unshift(r(l, e.trailing));
                break block_search
            } else if (t.length) {
                if (!t[0].match(n))break block_search;
                i.push(e.trailing.replace(/[^\n]/g, "").substring(2));
                e = t.shift()
            } else {
                break block_search
            }
        } while (!0);
        return[
            ["code_block", i.join("\n")]
        ]
    }, horizRule: function (e, t) {
        var n = e.match(/^(?:([\s\S]*?)\n)?[ \t]*([-_*])(?:[ \t]*\2){2,}[ \t]*(?:\n([\s\S]*))?$/);
        if (!n) {
            return undefined
        }
        ;
        var i = [
            ["hr"]
        ];
        if (n[1]) {
            i.unshift.apply(i, this.processBlock(n[1], []))
        }
        ;
        if (n[3]) {
            t.unshift(r(n[3]))
        }
        ;
        return i
    }, lists: (function () {
        var t = "[*+-]|\\d+\\.", o = /[*+-]/, f = /\d+\./, i = new RegExp("^( {0,3})(" + t + ")[ \t]+"), e = "(?: {0,3}\\t| {4})";

        function c(n) {
            return new RegExp("(?:^(" + e + "{0," + n + "} {0,3})(" + t + ")\\s+)|(^" + e + "{0," + (n - 1) + "}[ ]{0,4})")
        };
        function u(e) {
            return e.replace(/ {0,3}\t/g, "    ")
        };
        function n(e, t, n, s) {
            if (t) {
                e.push(["para"].concat(n));
                return
            }
            ;
            var r = e[e.length - 1]instanceof Array && e[e.length - 1][0] == "para" ? e[e.length - 1] : e;
            if (s && e.length > 1)n.unshift(s);
            for (var l = 0; l < n.length; l++) {
                var i = n[l], a = typeof i == "string";
                if (a && r.length > 1 && typeof r[r.length - 1] == "string") {
                    r[r.length - 1] += i
                } else {
                    r.push(i)
                }
            }
        };
        function a(t, n) {
            var c = new RegExp("^(" + e + "{" + t + "}.*?\\n?)*$"), s = new RegExp("^" + e + "{" + t + "}", "gm"), l = [];
            while (n.length > 0) {
                if (c.exec(n[0])) {
                    var i = n.shift(), a = i.replace(s, "");
                    l.push(r(a, i.trailing, i.lineNumber))
                }
                ;
                break
            }
            ;
            return l
        };
        function s(e, t, n) {
            var i = e.list, r = i[i.length - 1];
            if (r[1]instanceof Array && r[1][0] == "para") {
                return
            }
            ;
            if (t + 1 == n.length) {
                r.push(["para"].concat(r.splice(1)))
            } else {
                var l = r.pop();
                r.push(["para"].concat(r.splice(1)), l)
            }
        };
        return function (e, t) {
            var f = e.match(i);
            if (!f)return undefined;
            function m(e) {
                var t = o.exec(e[2]) ? ["bulletlist"] : ["numberlist"];
                r.push({list: t, indent: e[1]});
                return t
            };
            var r = [], p = m(f), h, b = !1, y = [r[0].list], d;
            loose_search:while (!0) {
                var E = e.split(/(?=\n)/), g = "";
                tight_search:for (var w = 0; w < E.length; w++) {
                    var k = "", M = E[w].replace(/^\n/, function (e) {
                        k = e;
                        return""
                    }), I = c(r.length);
                    f = M.match(I);
                    if (f[1] !== undefined) {
                        if (g.length) {
                            n(h, b, this.processInline(g), k);
                            b = !1;
                            g = ""
                        }
                        ;
                        f[1] = u(f[1]);
                        var v = Math.floor(f[1].length / 4) + 1;
                        if (v > r.length) {
                            p = m(f);
                            h.push(p);
                            h = p[1] = ["listitem"]
                        } else {
                            var A = !1;
                            for (d = 0; d < r.length; d++) {
                                if (r[d].indent != f[1])continue;
                                p = r[d].list;
                                r.splice(d + 1);
                                A = !0;
                                break
                            }
                            ;
                            if (!A) {
                                v++;
                                if (v <= r.length) {
                                    r.splice(v);
                                    p = r[v - 1].list
                                } else {
                                    p = m(f);
                                    h.push(p)
                                }
                            }
                            ;
                            h = ["listitem"];
                            p.push(h)
                        }
                        ;
                        k = ""
                    }
                    ;
                    if (M.length > f[0].length) {
                        g += k + M.substr(f[0].length)
                    }
                }
                ;
                if (g.length) {
                    n(h, b, this.processInline(g), k);
                    b = !1;
                    g = ""
                }
                ;
                var j = a(r.length, t);
                if (j.length > 0) {
                    l(r, s, this);
                    h.push.apply(h, this.toTree(j, []))
                }
                ;
                var S = t[0] && t[0].valueOf() || "";
                if (S.match(i) || S.match(/^ /)) {
                    e = t.shift();
                    var x = this.dialect.block.horizRule(e, t);
                    if (x) {
                        y.push.apply(y, x);
                        break
                    }
                    ;
                    l(r, s, this);
                    b = !0;
                    continue;
                    loose_search
                }
                ;
                break
            }
            ;
            return y
        }
    })(), blockquote: function (e, t) {
        if (!e.match(/^>/m))return undefined;
        var n = [];
        if (e[0] != ">") {
            var r = e.split(/\n/), l = [];
            while (r.length && r[0][0] != ">") {
                l.push(r.shift())
            }
            ;
            e = r.join("\n");
            n.push.apply(n, this.processBlock(l.join("\n"), []))
        }
        while (t.length && t[0][0] == ">") {
            var i = t.shift();
            e = new String(e + e.trailing + i);
            e.trailing = i.trailing
        }
        ;
        var s = e.replace(/^> ?/gm, ""), a = this.tree;
        n.push(this.toTree(s, ["blockquote"]));
        return n
    }, referenceDefn: function (e, t) {
        var l = /^\s*\[(.*?)\]:\s*(\S+)(?:\s+(?:(['"])(.*?)\3|\((.*?)\)))?\n?/;
        if (!e.match(l))return undefined;
        if (!n(this.tree)) {
            this.tree.splice(1, 0, {})
        }
        ;
        var i = n(this.tree);
        if (i.references === undefined) {
            i.references = {}
        }
        ;
        var s = this.loop_re_over_block(l, e, function (e) {
            if (e[2] && e[2][0] == "<" && e[2][e[2].length - 1] == ">")e[2] = e[2].substring(1, e[2].length - 1);
            var t = i.references[e[1].toLowerCase()] = {href: e[2]};
            if (e[4] !== undefined)t.title = e[4]; else if (e[5] !== undefined)t.title = e[5]
        });
        if (s.length)t.unshift(r(s, e.trailing));
        return[]
    }, para: function (e, t) {
        return[["para"].concat(this.processInline(e))]
    }}};
    t.dialects.Gruber.inline = {__oneElement__: function (e, t, r) {
        var n, i, s = 0;
        t = t || this.dialect.inline.__patterns__;
        var l = new RegExp("([\\s\\S]*?)(" + (t.source || t) + ")");
        n = l.exec(e);
        if (!n) {
            return[e.length, e]
        } else if (n[1]) {
            return[n[1].length, n[1]]
        }
        ;
        var i;
        if (n[2]in this.dialect.inline) {
            i = this.dialect.inline[n[2]].call(this, e.substr(n.index), n, r || [])
        }
        ;
        i = i || [n[2].length, n[2]];
        return i
    }, __call__: function (e, t) {
        var n = [], r;

        function i(e) {
            if (typeof e == "string" && typeof n[n.length - 1] == "string")n[n.length - 1] += e; else n.push(e)
        }

        while (e.length > 0) {
            r = this.dialect.inline.__oneElement__.call(this, e, t, n);
            e = e.substr(r.shift());
            l(r, i)
        }
        ;
        return n
    }, "]": function () {
    }, "}": function () {
    }, "\\": function (e) {
        if (e.match(/^\\[\\`\*_{}\[\]()#\+.!\-]/))return[2, e[1]]; else return[1, "\\"]
    }, "![": function (e) {
        var t = e.match(/^!\[(.*?)\][ \t]*\([ \t]*(\S*)(?:[ \t]+(["'])(.*?)\3)?[ \t]*\)/);
        if (t) {
            if (t[2] && t[2][0] == "<" && t[2][t[2].length - 1] == ">")t[2] = t[2].substring(1, t[2].length - 1);
            t[2] = this.dialect.inline.__call__.call(this, t[2], /\\/)[0];
            var n = {alt: t[1], href: t[2] || ""};
            if (t[4] !== undefined)n.title = t[4];
            return[t[0].length, ["img", n]]
        }
        ;
        t = e.match(/^!\[(.*?)\][ \t]*\[(.*?)\]/);
        if (t) {
            return[t[0].length, ["img_ref", {alt: t[1], ref: t[2].toLowerCase(), original: t[0]}]]
        }
        ;
        return[2, "!["]
    }, "[": function i(e) {
        var u = String(e), c = t.DialectHelpers.inline_until_char.call(this, e.substr(1), "]");
        if (!c)return[1, "["];
        var i = 1 + c[0], s = c[1], i, l;
        e = e.substr(i);
        var r = e.match(/^\s*\([ \t]*(\S+)(?:[ \t]+(["'])(.*?)\2)?[ \t]*\)/);
        if (r) {
            var n = r[1];
            i += r[0].length;
            if (n && n[0] == "<" && n[n.length - 1] == ">")n = n.substring(1, n.length - 1);
            if (!r[3]) {
                var o = 1;
                for (var a = 0; a < n.length; a++) {
                    switch (n[a]) {
                        case"(":
                            o++;
                            break;
                        case")":
                            if (--o == 0) {
                                i -= n.length - a;
                                n = n.substring(0, a)
                            }
                            ;
                            break
                    }
                }
            }
            ;
            n = this.dialect.inline.__call__.call(this, n, /\\/)[0];
            l = {href: n || ""};
            if (r[3] !== undefined)l.title = r[3];
            i = ["link", l].concat(s);
            return[i, i]
        }
        ;
        r = e.match(/^\s*\[(.*?)\]/);
        if (r) {
            i += r[0].length;
            l = {ref: (r[1] || String(s)).toLowerCase(), original: u.substr(0, i)};
            i = ["link_ref", l].concat(s);
            return[i, i]
        }
        ;
        if (s.length == 1 && typeof s[0] == "string") {
            l = {ref: s[0].toLowerCase(), original: u.substr(0, i)};
            i = ["link_ref", l, s[0]];
            return[i, i]
        }
        ;
        return[1, "["]
    }, "<": function (e) {
        var t;
        if ((t = e.match(/^<(?:((https?|ftp|mailto):[^>]+)|(.*?@.*?\.[a-zA-Z]+))>/)) != null) {
            if (t[3]) {
                return[t[0].length, ["link", {href: "mailto:" + t[3]}, t[3]]]
            } else if (t[2] == "mailto") {
                return[t[0].length, ["link", {href: t[1]}, t[1].substr("mailto:".length)]]
            } else return[t[0].length, ["link", {href: t[1]}, t[1]]]
        }
        ;
        return[1, "<"]
    }, "`": function (e) {
        var t = e.match(/(`+)(([\s\S]*?)\1)/);
        if (t && t[2])return[t[1].length + t[2].length, ["inlinecode", t[3]]]; else {
            return[1, "`"]
        }
    }, "  \n": function (e) {
        return[3, ["linebreak"]]
    }};
    function s(e, t) {
        var n = e + "_state", i = e == "strong" ? "em_state" : "strong_state";

        function r(e) {
            this.len_after = e;
            this.name = "close_" + t
        };
        return function (l, s) {
            if (this[n][0] == t) {
                this[n].shift();
                return[l.length, new r(l.length - t.length)]
            } else {
                var u = this[i].slice(), f = this[n].slice();
                this[n].unshift(t);
                var a = this.processInline(l.substr(t.length)), c = a[a.length - 1], h = this[n].shift();
                if (c instanceof r) {
                    a.pop();
                    var o = l.length - c.len_after;
                    return[o, [e].concat(a)]
                } else {
                    this[i] = u;
                    this[n] = f;
                    return[t.length, t]
                }
            }
        }
    };
    t.dialects.Gruber.inline["**"] = s("strong", "**");
    t.dialects.Gruber.inline["__"] = s("strong", "__");
    t.dialects.Gruber.inline["*"] = s("em", "*");
    t.dialects.Gruber.inline["_"] = s("em", "_");
    t.buildBlockOrder = function (e) {
        var n = [];
        for (var t in e) {
            if (t == "__order__" || t == "__call__")continue;
            n.push(t)
        }
        ;
        e.__order__ = n
    };
    t.buildInlinePatterns = function (e) {
        var t = [];
        for (var n in e) {
            if (n.match(/^__.*__$/))continue;
            var r = n.replace(/([\\.*+?|()\[\]{}])/g, "\\$1").replace(/\n/, "\\n");
            t.push(n.length == 1 ? r : "(?:" + r + ")")
        }
        ;
        t = t.join("|");
        e.__patterns__ = t;
        var i = e.__call__;
        e.__call__ = function (e, n) {
            if (n != undefined) {
                return i.call(this, e, n)
            } else {
                return i.call(this, e, t)
            }
        }
    };
    t.DialectHelpers = {};
    t.DialectHelpers.inline_until_char = function (e, t) {
        var n = 0, r = [];
        while (!0) {
            if (e[n] == t) {
                n++;
                return[n, r]
            }
            ;
            if (n >= e.length) {
                return null
            }
            ;
            var i = this.dialect.inline.__oneElement__.call(this, e.substr(n));
            n += i[0];
            r.push.apply(r, i.slice(1))
        }
    };
    t.subclassDialect = function (e) {
        function n() {
        };
        n.prototype = e.block;
        function t() {
        };
        t.prototype = e.inline;
        return{block: new n(), inline: new t()}
    };
    t.buildBlockOrder(t.dialects.Gruber.block);
    t.buildInlinePatterns(t.dialects.Gruber.inline);
    t.dialects.Maruku = t.subclassDialect(t.dialects.Gruber);
    t.dialects.Maruku.processMetaHash = function (e) {
        var n = b(e), r = {};
        for (var t = 0; t < n.length; ++t) {
            if (/^#/.test(n[t])) {
                r.id = n[t].substring(1)
            } else if (/^\./.test(n[t])) {
                if (r["class"]) {
                    r["class"] = r["class"] + n[t].replace(/./, " ")
                } else {
                    r["class"] = n[t].substring(1)
                }
            } else if (/\=/.test(n[t])) {
                var i = n[t].split(/\=/);
                r[i[0]] = i[1]
            }
        }
        ;
        return r
    };
    function b(e) {
        var i = e.split(""), t = [""], r = !1;
        while (i.length) {
            var n = i.shift();
            switch (n) {
                case" ":
                    if (r) {
                        t[t.length - 1] += n
                    } else {
                        t.push("")
                    }
                    ;
                    break;
                case"'":
                case"\"":
                    r = !r;
                    break;
                case"\\":
                    n = i.shift();
                default:
                    t[t.length - 1] += n;
                    break
            }
        }
        ;
        return t
    };
    t.dialects.Maruku.block.document_meta = function (e, t) {
        if (e.lineNumber > 1)return undefined;
        if (!e.match(/^(?:\w+:.*\n)*\w+:.*$/))return undefined;
        if (!n(this.tree)) {
            this.tree.splice(1, 0, {})
        }
        ;
        var r = e.split(/\n/);
        for (p in r) {
            var i = r[p].match(/(\w+):\s*(.*)$/), l = i[1].toLowerCase(), s = i[2];
            this.tree[1][l] = s
        }
        ;
        return[]
    };
    t.dialects.Maruku.block.block_meta = function (e, t) {
        var l = e.match(/(^|\n) {0,3}\{:\s*((?:\\\}|[^\}])*)\s*\}$/);
        if (!l)return undefined;
        var i = this.dialect.processMetaHash(l[2]), r;
        if (l[1] === "") {
            var c = this.tree[this.tree.length - 1];
            r = n(c);
            if (typeof c === "string")return undefined;
            if (!r) {
                r = {};
                c.splice(1, 0, r)
            }
            ;
            for (a in i) {
                r[a] = i[a]
            }
            ;
            return[]
        }
        ;
        var u = e.replace(/\n.*$/, ""), s = this.processBlock(u, []);
        r = n(s[0]);
        if (!r) {
            r = {};
            s[0].splice(1, 0, r)
        }
        ;
        for (a in i) {
            r[a] = i[a]
        }
        ;
        return s
    };
    t.dialects.Maruku.block.definition_list = function (e, t) {
        var s = /^((?:[^\s:].*\n)+):\s+([\s\S]+)$/, a = ["dl"], n;
        if ((l = e.match(s))) {
            var r = [e];
            while (t.length && s.exec(t[0])) {
                r.push(t.shift())
            }
            ;
            for (var i = 0; i < r.length; ++i) {
                var l = r[i].match(s), u = l[1].replace(/\n$/, "").split(/\n/), c = l[2].split(/\n:\s+/);
                for (n = 0; n < u.length; ++n) {
                    a.push(["dt", u[n]])
                }
                ;
                for (n = 0; n < c.length; ++n) {
                    a.push(["dd"].concat(this.processInline(c[n].replace(/(\n)\s+/, "$1"))))
                }
            }
        } else {
            return undefined
        }
        ;
        return[a]
    };
    t.dialects.Maruku.inline["{:"] = function (e, t, r) {
        if (!r.length) {
            return[2, "{:"]
        }
        ;
        var s = r[r.length - 1];
        if (typeof s === "string") {
            return[2, "{:"]
        }
        ;
        var l = e.match(/^\{:\s*((?:\\\}|[^\}])*)\s*\}/);
        if (!l) {
            return[2, "{:"]
        }
        ;
        var c = this.dialect.processMetaHash(l[1]), i = n(s);
        if (!i) {
            i = {};
            s.splice(1, 0, i)
        }
        ;
        for (var a in c) {
            i[a] = c[a]
        }
        ;
        return[l[0].length, ""]
    };
    t.buildBlockOrder(t.dialects.Maruku.block);
    t.buildInlinePatterns(t.dialects.Maruku.inline);
    var c = Array.isArray || function (e) {
        return Object.prototype.toString.call(e) == "[object Array]"
    }, l;
    if (Array.prototype.forEach) {
        l = function (e, t, n) {
            return e.forEach(t, n)
        }
    } else {
        l = function (e, t, n) {
            for (var r = 0; r < e.length; r++) {
                t.call(n || e, e[r], r, e)
            }
        }
    }
    ;
    function n(e) {
        return c(e) && e.length > 1 && typeof e[1] === "object" && !(c(e[1])) ? e[1] : undefined
    };
    e.renderJsonML = function (e, t) {
        t = t || {};
        t.root = t.root || !1;
        var n = [];
        if (t.root) {
            n.push(o(e))
        } else {
            e.shift();
            if (e.length && typeof e[0] === "object" && !(e[0]instanceof Array)) {
                e.shift()
            }
            while (e.length) {
                n.push(o(e.shift()))
            }
        }
        ;
        return n.join("\n\n")
    };
    function u(e) {
        return e.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#39;")
    };
    function o(e) {
        if (typeof e === "string") {
            return u(e)
        }
        ;
        var t = e.shift(), n = {}, i = [];
        if (e.length && typeof e[0] === "object" && !(e[0]instanceof Array)) {
            n = e.shift()
        }
        while (e.length) {
            i.push(arguments.callee(e.shift()))
        }
        ;
        var r = "";
        for (var l in n) {
            r += " " + l + "=\"" + u(n[l]) + "\""
        }
        ;
        if (t == "img" || t == "br" || t == "hr") {
            return"<" + t + r + "/>"
        } else {
            return"<" + t + r + ">" + i.join("") + "</" + t + ">"
        }
    };
    function g(e, t, l) {
        var s;
        l = l || {};
        var r = e.slice(0);
        if (typeof l.preprocessTreeNode === "function") {
            r = l.preprocessTreeNode(r, t)
        }
        ;
        var i = n(r);
        if (i) {
            r[1] = {};
            for (s in i) {
                r[1][s] = i[s]
            }
            ;
            i = r[1]
        }
        ;
        if (typeof r === "string") {
            return r
        }
        ;
        switch (r[0]) {
            case"header":
                r[0] = "h" + r[1].level;
                delete r[1].level;
                break;
            case"bulletlist":
                r[0] = "ul";
                break;
            case"numberlist":
                r[0] = "ol";
                break;
            case"listitem":
                r[0] = "li";
                break;
            case"para":
                r[0] = "p";
                break;
            case"markdown":
                r[0] = "html";
                if (i)delete i.references;
                break;
            case"code_block":
                r[0] = "pre";
                s = i ? 2 : 1;
                var c = ["code"];
                c.push.apply(c, r.splice(s));
                r[s] = c;
                break;
            case"inlinecode":
                r[0] = "code";
                break;
            case"img":
                r[1].src = r[1].href;
                delete r[1].href;
                break;
            case"linebreak":
                r[0] = "br";
                break;
            case"link":
                r[0] = "a";
                break;
            case"link_ref":
                r[0] = "a";
                var a = t[i.ref];
                if (a) {
                    delete i.ref;
                    i.href = a.href;
                    if (a.title) {
                        i.title = a.title
                    }
                    ;
                    delete i.original
                } else {
                    return i.original
                }
                ;
                break;
            case"img_ref":
                r[0] = "img";
                var a = t[i.ref];
                if (a) {
                    delete i.ref;
                    i.src = a.href;
                    if (a.title) {
                        i.title = a.title
                    }
                    ;
                    delete i.original
                } else {
                    return i.original
                }
                ;
                break
        }
        ;
        s = 1;
        if (i) {
            for (var u in r[1]) {
                s = 2
            }
            ;
            if (s === 1) {
                r.splice(s, 1)
            }
        }
        ;
        for (; s < r.length; ++s) {
            r[s] = arguments.callee(r[s], t, l)
        }
        ;
        return r
    };
    function d(e) {
        var t = n(e) ? 2 : 1;
        while (t < e.length) {
            if (typeof e[t] === "string") {
                if (t + 1 < e.length && typeof e[t + 1] === "string") {
                    e[t] += e.splice(t + 1, 1)[0]
                } else {
                    ++t
                }
            } else {
                arguments.callee(e[t]);
                ++t
            }
        }
    }
})((function () {
    if (typeof exports === "undefined") {
        window.markdown = {};
        return window.markdown
    } else {
        return exports
    }
})());
!function (t) {
    'use strict';
    var e = function (e, n) {
        this.h$ = 'bootstrap-markdown';
        this.i$ = t(e);
        this.r$ = {el: null, type: null, attrKeys: [], attrValues: [], content: null};
        this.n$ = t.extend(!0, {}, t.fn.markdown.defaults, n);
        this.c$ = null;
        this.l$ = !1;
        this.e$ = null;
        this.t$ = null;
        this.a$ = [];
        this.o$ = [];
        this.s$ = [];
        this.showEditor()
    };
    e.prototype = {constructor: e, __alterButtons: function (e, n) {
        var a = this.a$, s = (e == 'all'), i = this;
        t.each(a, function (t, a) {
            var r = !0;
            if (s) {
                r = !1
            } else {
                r = a.indexOf(e) < 0
            }
            ;
            if (r == !1) {
                n(i.e$.find('button[data-handler="' + a + '"]'))
            }
        })
    }, __buildButtons: function (e, i) {
        var r, d = this.h$, f = this.a$, p = this.o$;
        for (r = 0; r < e.length; r++) {
            var a, c = e[r];
            for (a = 0; a < c.length; a++) {
                var s, l = c[a].data, o = t('<div/>', {'class': 'btn-group'});
                for (s = 0; s < l.length; s++) {
                    var n = l[s], h = '', u = d + '-' + n.name, v = n.icon instanceof Object ? n.icon[this.n$.iconlibrary] : n.icon, m = n.btnText ? n.btnText : '', b = n.btnClass ? n.btnClass : 'btn', g = n.tabIndex ? n.tabIndex : '-1';
                    if (n.toggle == !0) {
                        h = ' data-toggle="button"'
                    }
                    ;
                    o.append('<button type="button" class="' + b + ' btn-default btn-sm" title="' + n.title + '" tabindex="' + g + '" data-provider="' + d + '" data-handler="' + u + '"' + h + '><span class="' + v + '"></span> ' + m + '</button>');
                    f.push(u);
                    p.push(n.callback)
                }
                ;
                i.append(o)
            }
        }
        ;
        return i
    }, __setListener: function () {
        var i = typeof this.t$.attr('rows') != 'undefined', n = this.t$.val().split('\n').length > 5 ? this.t$.val().split('\n').length : '5', e = i ? this.t$.attr('rows') : n;
        this.t$.attr('rows', e);
        this.t$.css('resize', 'none');
        this.t$.on('focus', t.proxy(this.focus, this)).on('keypress', t.proxy(this.keypress, this)).on('keyup', t.proxy(this.keyup, this));
        if (this.eventSupported('keydown')) {
            this.t$.on('keydown', t.proxy(this.keydown, this))
        }
        ;
        this.t$.data('markdown', this)
    }, __handle: function (e) {
        var r = t(e.currentTarget), o = this.a$, s = this.o$, n = r.attr('data-handler'), a = o.indexOf(n), i = s[a];
        t(e.currentTarget).focus();
        i(this);
        if (n.indexOf('cmdSave') < 0) {
            this.t$.focus()
        }
        ;
        e.preventDefault()
    }, showEditor: function () {
        var p = this, s, u = this.h$, e = this.i$, g = e.css('height'), v = e.css('width'), a = this.r$, c = this.a$, h = this.o$, n = this.n$, i = t('<div/>', {'class': 'md-editor', click: function () {
            p.focus()
        }});
        if (this.e$ == null) {
            var r = t('<div/>', {'class': 'md-header btn-toolbar'});
            if (n.buttons.length > 0) {
                r = this.__buildButtons(n.buttons, r)
            }
            ;
            if (n.additionalButtons.length > 0) {
                r = this.__buildButtons(n.additionalButtons, r)
            }
            ;
            i.append(r);
            if (e.is('textarea')) {
                e.before(i);
                s = e;
                s.addClass('md-input');
                i.append(s)
            } else {
                var d = (typeof toMarkdown == 'function') ? toMarkdown(e.html()) : e.html(), f = t.trim(d);
                s = t('<textarea/>', {'class': 'md-input', 'val': f});
                i.append(s);
                a.el = e;
                a.type = e.prop('tagName').toLowerCase();
                a.content = e.html();
                t(e[0].attributes).each(function () {
                    a.attrKeys.push(this.nodeName);
                    a.attrValues.push(this.nodeValue)
                });
                e.replaceWith(i)
            }
            ;
            if (n.savable) {
                var l = t('<div/>', {'class': 'md-footer'}), o = 'cmdSave';
                c.push(o);
                h.push(n.onSave);
                l.append('<button class="btn btn-success" data-provider="' + u + '" data-handler="' + o + '"><i class="icon icon-white icon-ok"></i> Save</button>');
                i.append(l)
            }
            ;
            t.each(['height', 'width'], function (t, e) {
                if (n[e] != 'inherit') {
                    if (jQuery.isNumeric(n[e])) {
                        i.css(e, n[e] + 'px')
                    } else {
                        i.addClass(n[e])
                    }
                }
            });
            this.e$ = i;
            this.t$ = s;
            this.r$ = a;
            this.c$ = this.getContent();
            this.__setListener();
            this.e$.attr('id', (new Date).getTime());
            this.e$.on('click', '[data-provider="bootstrap-markdown"]', t.proxy(this.__handle, this))
        } else {
            this.e$.show()
        }
        ;
        if (n.autofocus) {
            this.t$.focus();
            this.e$.addClass('active')
        }
        ;
        n.onShow(this);
        return this
    }, showPreview: function () {
        var o = this.n$, r = o.onPreview(this), i = this.t$, s = i.next(), n = t('<div/>', {'class': 'md-preview', 'data-provider': 'markdown-preview'}), e;
        this.l$ = !0;
        this.disableButtons('all').enableButtons('cmdPreview');
        if (typeof r == 'string') {
            e = r
        } else {
            var a = i.val();
            if (typeof markdown == 'object') {
                e = markdown.toHTML(a)
            } else if (typeof marked == 'function') {
                e = marked(a)
            } else {
                e = a
            }
        }
        ;
        n.html(e);
        if (s && s.attr('class') == 'md-footer') {
            n.insertBefore(s)
        } else {
            i.parent().append(n)
        }
        ;
        i.hide();
        n.data('markdown', this);
        return this
    }, hidePreview: function () {
        this.l$ = !1;
        var t = this.e$.find('div[data-provider="markdown-preview"]');
        t.remove();
        this.enableButtons('all');
        this.t$.show();
        this.__setListener();
        return this
    }, isDirty: function () {
        return this.c$ != this.getContent()
    }, getContent: function () {
        return this.t$.val()
    }, setContent: function (t) {
        this.t$.val(t);
        return this
    }, findSelection: function (t) {
        var a = this.getContent(), e;
        if (e = a.indexOf(t), e >= 0 && t.length > 0) {
            var i = this.getSelection(), n;
            this.setSelection(e, e + t.length);
            n = this.getSelection();
            this.setSelection(i.start, i.end);
            return n
        } else {
            return null
        }
    }, getSelection: function () {
        var t = this.t$[0];
        return(('selectionStart' in t && function () {
            var e = t.selectionEnd - t.selectionStart;
            return{start: t.selectionStart, end: t.selectionEnd, length: e, text: t.value.substr(t.selectionStart, e)}
        }) || function () {
            return null
        })()
    }, setSelection: function (t, e) {
        var n = this.t$[0];
        return(('selectionStart' in n && function () {
            n.selectionStart = t;
            n.selectionEnd = e;
            return
        }) || function () {
            return null
        })()
    }, replaceSelection: function (t) {
        var e = this.t$[0];
        return(('selectionStart' in e && function () {
            e.value = e.value.substr(0, e.selectionStart) + t + e.value.substr(e.selectionEnd, e.value.length);
            e.selectionStart = e.value.length;
            return this
        }) || function () {
            e.value += t;
            return jQuery(e)
        })()
    }, getNextTab: function () {
        if (this.s$.length == 0) {
            return null
        } else {
            var e, t = this.s$.shift();
            if (typeof t == 'function') {
                e = t()
            } else if (typeof t == 'object' && t.length > 0) {
                e = t
            }
            ;
            return e
        }
    }, setNextTab: function (t, e) {
        if (typeof t == 'string') {
            var i = this;
            this.s$.push(function () {
                return i.findSelection(t)
            })
        } else if (typeof t == 'numeric' && typeof e == 'numeric') {
            var n = this.getSelection();
            this.setSelection(t, e);
            this.s$.push(this.getSelection());
            this.setSelection(n.start, n.end)
        }
        ;
        return
    }, enableButtons: function (t) {
        var e = function (t) {
            t.removeAttr('disabled')
        };
        this.__alterButtons(t, e);
        return this
    }, disableButtons: function (t) {
        var e = function (t) {
            t.attr('disabled', 'disabled')
        };
        this.__alterButtons(t, e);
        return this
    }, eventSupported: function (t) {
        var e = t in this.i$;
        if (!e) {
            this.i$.setAttribute(t, 'return;');
            e = typeof this.i$[t] === 'function'
        }
        ;
        return e
    }, keydown: function (e) {
        this.suppressKeyPressRepeat = ~t.inArray(e.keyCode, [40, 38, 9, 13, 27]);
        this.keyup(e)
    }, keypress: function (t) {
        if (this.suppressKeyPressRepeat)return;
        this.keyup(t)
    }, keyup: function (t) {
        var e = !1;
        switch (t.keyCode) {
            case 40:
            case 38:
            case 16:
            case 17:
            case 18:
                break;
            case 9:
                var n;
                if (n = this.getNextTab(), n != null) {
                    var a = this;
                    setTimeout(function () {
                        a.setSelection(n.start, n.end)
                    }, 500);
                    e = !0
                } else {
                    var i = this.getSelection();
                    if (i.start == i.end && i.end == this.getContent().length) {
                        e = !1
                    } else {
                        this.setSelection(this.getContent().length, this.getContent().length);
                        e = !0
                    }
                }
                ;
                break;
            case 13:
            case 27:
                e = !1;
                break;
            default:
                e = !1
        }
        ;
        if (e) {
            t.stopPropagation();
            t.preventDefault()
        }
    }, focus: function (e) {
        var i = this.n$, a = i.hideable, n = this.e$;
        n.addClass('active');
        t(document).find('.md-editor').each(function () {
            if (t(this).attr('id') != n.attr('id')) {
                var e;
                if (e = t(this).find('textarea').data('markdown'), e == null) {
                    e = t(this).find('div[data-provider="markdown-preview"]').data('markdown')
                }
                ;
                if (e) {
                    e.blur()
                }
            }
        });
        i.onFocus(this);
        return this
    }, blur: function (e) {
        var r = this.n$, l = r.hideable, i = this.e$, n = this.r$;
        if (i.hasClass('active') || this.i$.parent().length == 0) {
            i.removeClass('active');
            if (l) {
                if (n.el != null) {
                    var a = t('<' + n.type + '/>'), s = this.getContent(), o = (typeof markdown == 'object') ? markdown.toHTML(s) : s;
                    t(n.attrKeys).each(function (t, e) {
                        a.attr(n.attrKeys[t], n.attrValues[t])
                    });
                    a.html(o);
                    i.replaceWith(a)
                } else {
                    i.hide()
                }
            }
            ;
            r.onBlur(this)
        }
        ;
        return this
    }};
    var a = t.fn.markdown;
    t.fn.markdown = function (n) {
        return this.each(function () {
            var i = t(this), a = i.data('markdown'), s = typeof n == 'object' && n;
            if (!a)i.data('markdown', (a = new e(this, s)))
        })
    };
    t.fn.markdown.defaults = {autofocus: !1, hideable: !1, savable: !1, width: 'inherit', height: 'inherit', iconlibrary: 'glyph', buttons: [
        [
            {name: 'groupFont', data: [
                {name: 'cmdBold', title: 'Bold', icon: {glyph: 'glyphicon glyphicon-bold', fa: 'fa fa-bold'}, callback: function (t) {
                    var n, i, e = t.getSelection(), a = t.getContent();
                    if (e.length == 0) {
                        n = 'strong text'
                    } else {
                        n = e.text
                    }
                    ;
                    if (a.substr(e.start - 2, 2) == '**' && a.substr(e.end, 2) == '**') {
                        t.setSelection(e.start - 2, e.end + 2);
                        t.replaceSelection(n);
                        i = e.start - 2
                    } else {
                        t.replaceSelection('**' + n + '**');
                        i = e.start + 2
                    }
                    ;
                    t.setSelection(i, i + n.length)
                }},
                {name: 'cmdItalic', title: 'Italic', icon: {glyph: 'glyphicon glyphicon-italic', fa: 'fa fa-italic'}, callback: function (t) {
                    var n, i, e = t.getSelection(), a = t.getContent();
                    if (e.length == 0) {
                        n = 'emphasized text'
                    } else {
                        n = e.text
                    }
                    ;
                    if (a.substr(e.start - 1, 1) == '*' && a.substr(e.end, 1) == '*') {
                        t.setSelection(e.start - 1, e.end + 1);
                        t.replaceSelection(n);
                        i = e.start - 1
                    } else {
                        t.replaceSelection('*' + n + '*');
                        i = e.start + 1
                    }
                    ;
                    t.setSelection(i, i + n.length)
                }},
                {name: 'cmdHeading', title: 'Heading', icon: {glyph: 'glyphicon glyphicon-header', fa: 'fa fa-font'}, callback: function (t) {
                    var i, a, e = t.getSelection(), r = t.getContent(), n, s;
                    if (e.length == 0) {
                        i = 'heading text'
                    } else {
                        i = e.text + '\n'
                    }
                    ;
                    if ((n = 4, r.substr(e.start - n, n) == '### ') || (n = 3, r.substr(e.start - n, n) == '###')) {
                        t.setSelection(e.start - n, e.end);
                        t.replaceSelection(i);
                        a = e.start - n
                    } else if (e.start > 0 && (s = r.substr(e.start - 1, 1), !!s && s != '\n')) {
                        t.replaceSelection('\n\n### ' + i);
                        a = e.start + 6
                    } else {
                        t.replaceSelection('### ' + i);
                        a = e.start + 4
                    }
                    ;
                    t.setSelection(a, a + i.length)
                }}
            ]},
            {name: 'groupLink', data: [
                {name: 'cmdUrl', title: 'URL/Link', icon: {glyph: 'glyphicon glyphicon-globe', fa: 'fa fa-globe'}, callback: function (t) {
                    var n, i, a = t.getSelection(), s = t.getContent(), e;
                    if (a.length == 0) {
                        n = 'enter link description here'
                    } else {
                        n = a.text
                    }
                    ;
                    e = prompt('Insert Hyperlink', 'http://');
                    if (e != null && e != '' && e != 'http://') {
                        t.replaceSelection('[' + n + '](' + e + ')');
                        i = a.start + 1;
                        t.setSelection(i, i + n.length)
                    }
                }},
                {name: 'cmdImage', title: 'Image', icon: {glyph: 'glyphicon glyphicon-picture', fa: 'fa fa-picture-o'}, callback: function (t) {
                    var e, i, a = t.getSelection(), s = t.getContent(), n;
                    if (a.length == 0) {
                        e = 'enter image description here'
                    } else {
                        e = a.text
                    }
                    ;
                    n = prompt('Insert Image Hyperlink', 'http://');
                    if (n != null) {
                        t.replaceSelection('![' + e + '](' + n + ' "enter image title here")');
                        i = a.start + 2;
                        t.setNextTab('enter image title here');
                        t.setSelection(i, i + e.length)
                    }
                }}
            ]},
            {name: 'groupMisc', data: [
                {name: 'cmdList', title: 'List', icon: {glyph: 'glyphicon glyphicon-list', fa: 'fa fa-list'}, callback: function (e) {
                    var i, a, n = e.getSelection(), r = e.getContent();
                    if (n.length == 0) {
                        i = 'list text here';
                        e.replaceSelection('- ' + i);
                        a = n.start + 2
                    } else {
                        if (n.text.indexOf('\n') < 0) {
                            i = n.text;
                            e.replaceSelection('- ' + i);
                            a = n.start + 2
                        } else {
                            var s = [];
                            s = n.text.split('\n');
                            i = s[0];
                            t.each(s, function (t, e) {
                                s[t] = '- ' + e
                            });
                            e.replaceSelection('\n\n' + s.join('\n'));
                            a = n.start + 4
                        }
                    }
                    ;
                    e.setSelection(a, a + i.length)
                }}
            ]},
            {name: 'groupUtil', data: [
                {name: 'cmdPreview', toggle: !0, title: 'Preview', btnText: 'Preview', btnClass: 'btn btn-primary btn-sm', icon: {glyph: 'glyphicon glyphicon-search', fa: 'fa fa-search'}, callback: function (t) {
                    var e = t.l$, n;
                    if (e == !1) {
                        t.showPreview()
                    } else {
                        t.hidePreview()
                    }
                }}
            ]}
        ]
    ], additionalButtons: [], onShow: function (t) {
    }, onPreview: function (t) {
    }, onSave: function (t) {
    }, onBlur: function (t) {
    }, onFocus: function (t) {
    }, };
    t.fn.markdown.Constructor = e;
    t.fn.markdown.noConflict = function () {
        t.fn.markdown = a;
        return this
    };
    var i = function (t) {
        var e = t;
        if (e.data('markdown')) {
            e.data('markdown').showEditor();
            return
        }
        ;
        e.markdown(e.data())
    }, n = function (e) {
        var a = !1, n, i = t(e.currentTarget);
        if ((e.type == 'focusin' || e.type == 'click') && i.length == 1 && typeof i[0] == 'object') {
            n = i[0].activeElement;
            if (!t(n).data('markdown')) {
                if (typeof t(n).parent().parent().parent().attr('class') == 'undefined' || t(n).parent().parent().parent().attr('class').indexOf('md-editor') < 0) {
                    if (typeof t(n).parent().parent().attr('class') == 'undefined' || t(n).parent().parent().attr('class').indexOf('md-editor') < 0) {
                        a = !0
                    }
                } else {
                    a = !1
                }
            }
            ;
            if (a) {
                t(document).find('.md-editor').each(function () {
                    var i = t(n).parent();
                    if (t(this).attr('id') != i.attr('id')) {
                        var e;
                        if (e = t(this).find('textarea').data('markdown'), e == null) {
                            e = t(this).find('div[data-provider="markdown-preview"]').data('markdown')
                        }
                        ;
                        if (e) {
                            e.blur()
                        }
                    }
                })
            }
            ;
            e.stopPropagation()
        }
    };
    t(document).on('click.markdown.data-api', '[data-provide="markdown-editable"]',function (e) {
        i(t(this));
        e.preventDefault()
    }).on('click',function (t) {
        n(t)
    }).on('focusin',function (t) {
        n(t)
    }).ready(function () {
        t('textarea[data-provide="markdown"]').each(function () {
            i(t(this))
        })
    })
}(window.jQuery);
;
var toMarkdown = function (e) {
    var t = [
        {patterns: 'p', replacement: function (e, n, t) {
            return t ? '\n\n' + t + '\n' : ''
        }},
        {patterns: 'br', type: 'void', replacement: '\n'},
        {patterns: 'h([1-6])', replacement: function (e, n, t, c) {
            var r = '';
            for (var a = 0; a < n; a++) {
                r += '#'
            }
            ;
            return'\n\n' + r + ' ' + c + '\n'
        }},
        {patterns: 'hr', type: 'void', replacement: '\n\n* * *\n'},
        {patterns: 'a', replacement: function (e, n, t) {
            var c = n.match(r('href')), a = n.match(r('title'));
            return c ? '[' + t + '](' + c[1] + (a && a[1] ? ' "' + a[1] + '"' : '') + ')' : e
        }},
        {patterns: ['b', 'strong'], replacement: function (e, n, t) {
            return t ? '**' + t + '**' : ''
        }},
        {patterns: ['i', 'em'], replacement: function (e, n, t) {
            return t ? '_' + t + '_' : ''
        }},
        {patterns: 'code', replacement: function (e, n, t) {
            return t ? '`' + t + '`' : ''
        }},
        {patterns: 'img', type: 'void', replacement: function (e, n, t) {
            var p = n.match(r('src')), c = n.match(r('alt')), a = n.match(r('title'));
            return'![' + (c && c[1] ? c[1] : '') + '](' + p[1] + (a && a[1] ? ' "' + a[1] + '"' : '') + ')'
        }}
    ];
    for (var n = 0, a = t.length; n < a; n++) {
        if (typeof t[n].patterns === 'string') {
            e = o(e, {tag: t[n].patterns, replacement: t[n].replacement, type: t[n].type})
        } else {
            for (var c = 0, g = t[n].patterns.length; c < g; c++) {
                e = o(e, {tag: t[n].patterns[c], replacement: t[n].replacement, type: t[n].type})
            }
        }
    }
    ;
    function o(e, n) {
        var a = n.type === 'void' ? '<' + n.tag + '\\b([^>]*)\\/?>' : '<' + n.tag + '\\b([^>]*)>([\\s\\S]*?)<\\/' + n.tag + '>', r = new RegExp(a, 'gi'), t = '';
        if (typeof n.replacement === 'string') {
            t = e.replace(r, n.replacement)
        } else {
            t = e.replace(r, function (e, t, r, a) {
                return n.replacement.call(this, e, t, r, a)
            })
        }
        ;
        return t
    };
    function r(e) {
        return new RegExp(e + '\\s*=\\s*["\']?([^"\']*)["\']?', 'i')
    };
    e = e.replace(/<pre\b[^>]*>`([\s\S]*)`<\/pre>/gi, function (e, n) {
        n = n.replace(/^\t+/g, '  ');
        n = n.replace(/\n/g, '\n    ');
        return'\n\n    ' + n + '\n'
    });
    e = e.replace(/^(\s{0,3}\d+)\. /g, '$1\\. ');
    var i = /<(ul|ol)\b[^>]*>(?:(?!<ul|<ol)[\s\S])*?<\/\1>/gi;
    while (e.match(i)) {
        e = e.replace(i, function (e) {
            return s(e)
        })
    }
    ;
    function s(e) {
        e = e.replace(/<(ul|ol)\b[^>]*>([\s\S]*?)<\/\1>/gi, function (e, t, r) {
            var c = r.split('</li>');
            c.splice(c.length - 1, 1);
            for (n = 0, a = c.length; n < a; n++) {
                if (c[n]) {
                    var p = (t === 'ol') ? (n + 1) + '.  ' : '*   ';
                    c[n] = c[n].replace(/\s*<li[^>]*>([\s\S]*)/i, function (n, e) {
                        e = e.replace(/^\s+/, '');
                        e = e.replace(/\n\n/g, '\n\n    ');
                        e = e.replace(/\n([ ]*)+(\*|\d+\.) /g, '\n$1    $2 ');
                        return p + e
                    })
                }
            }
            ;
            return c.join('\n')
        });
        return'\n\n' + e.replace(/[ \t]+\n|\s+$/g, '')
    };
    var l = /<blockquote\b[^>]*>((?:(?!<blockquote)[\s\S])*?)<\/blockquote>/gi;
    while (e.match(l)) {
        e = e.replace(l, function (e) {
            return u(e)
        })
    }
    ;
    function u(e) {
        e = e.replace(/<blockquote\b[^>]*>([\s\S]*?)<\/blockquote>/gi, function (n, e) {
            e = e.replace(/^\s+|\s+$/g, '');
            e = p(e);
            e = e.replace(/^/gm, '> ');
            e = e.replace(/^(>([ \t]{2,}>)+)/gm, '> >');
            return e
        });
        return e
    };
    function p(e) {
        e = e.replace(/^[\t\r\n]+|[\t\r\n]+$/g, '');
        e = e.replace(/\n\s+\n/g, '\n\n');
        e = e.replace(/\n{3,}/g, '\n\n');
        return e
    };
    return p(e)
};
if (typeof exports === 'object') {
    exports.toMarkdown = toMarkdown
}
;
!function (e) {
    "undefined" == typeof e.fn.each2 && e.extend(e.fn, {each2: function (t) {
        for (var s = e([0]), i = -1, n = this.length; ++i < n && (s.context = s[0] = this[i]) && t.call(s[0], i, s) !== !1;);
        return this
    }})
}(jQuery), function (e, t) {
    "use strict";
    function O(t) {
        var s = e(document.createTextNode(""));
        t.before(s), s.before(t), s.remove()
    };
    function u(e) {
        function t(e) {
            return M[e] || e
        };
        return e.replace(/[^\u0000-\u007E]/g, t)
    };
    function a(e, t) {
        for (var s = 0, i = t.length; i > s; s += 1)if (o(e, t[s]))return s;
        return-1
    };
    function z() {
        var t = e(R);
        t.appendTo("body");
        var s = {width: t.width() - t[0].clientWidth, height: t.height() - t[0].clientHeight};
        return t.remove(), s
    };
    function o(e, s) {
        return e === s ? !0 : e === t || s === t ? !1 : null === e || null === s ? !1 : e.constructor === String ? e + "" == s + "" : s.constructor === String ? s + "" == e + "" : !1
    };
    function f(t, s) {
        var n, i, o;
        if (null === t || t.length < 1)return[];
        for (n = t.split(s), i = 0, o = n.length; o > i; i += 1)n[i] = e.trim(n[i]);
        return n
    };
    function A(e) {
        return e.outerWidth(!1) - e.width()
    };
    function T(s) {
        var i = "keyup-change-value";
        s.on("keydown", function () {
            e.data(s, i) === t && e.data(s, i, s.val())
        }), s.on("keyup", function () {
            var n = e.data(s, i);
            n !== t && s.val() !== n && (e.removeData(s, i), s.trigger("keyup-change"))
        })
    };
    function U(s) {
        s.on("mousemove", function (s) {
            var i = g;
            (i === t || i.x !== s.pageX || i.y !== s.pageY) && e(s.target).trigger("mousemove-filtered", s)
        })
    };
    function k(e, s, i) {
        i = i || t;
        var n;
        return function () {
            var t = arguments;
            window.clearTimeout(n), n = window.setTimeout(function () {
                s.apply(i, t)
            }, e)
        }
    };
    function j(e, t) {
        var s = k(e, function (e) {
            t.trigger("scroll-debounced", e)
        });
        t.on("scroll", function (e) {
            a(e.target, t.get()) >= 0 && s(e)
        })
    };
    function N(e) {
        e[0] !== document.activeElement && window.setTimeout(function () {
            var s, t = e[0], i = e.val().length;
            e.focus();
            var n = t.offsetWidth > 0 || t.offsetHeight > 0;
            n && t === document.activeElement && (t.setSelectionRange ? t.setSelectionRange(i, i) : t.createTextRange && (s = t.createTextRange(), s.collapse(!1), s.select()))
        }, 0)
    };
    function F(t) {
        t = e(t)[0];
        var s = 0, i = 0;
        if ("selectionStart"in t)s = t.selectionStart, i = t.selectionEnd - s; else if ("selection"in document) {
            t.focus();
            var n = document.selection.createRange();
            i = document.selection.createRange().text.length, n.moveStart("character", -t.value.length), s = n.text.length - i
        }
        ;
        return{offset: s, length: i}
    };
    function i(e) {
        e.preventDefault(), e.stopPropagation()
    };
    function L(e) {
        e.preventDefault(), e.stopImmediatePropagation()
    };
    function H(t) {
        if (!r) {
            var s = t[0].currentStyle || window.getComputedStyle(t[0], null);
            r = e(document.createElement("div")).css({position: "absolute", left: "-10000px", top: "-10000px", display: "none", fontSize: s.fontSize, fontFamily: s.fontFamily, fontStyle: s.fontStyle, fontWeight: s.fontWeight, letterSpacing: s.letterSpacing, textTransform: s.textTransform, whiteSpace: "nowrap"}), r.attr("class", "select2-sizer"), e("body").append(r)
        }
        ;
        return r.text(t.val()), r.width()
    };
    function d(t, s, i) {
        var n, a, o = [];
        n = e.trim(t.attr("class")), n && (n = "" + n, e(n.split(/\s+/)).each2(function () {
            0 === this.indexOf("select2-") && o.push(this)
        })), n = e.trim(s.attr("class")), n && (n = "" + n, e(n.split(/\s+/)).each2(function () {
            0 !== this.indexOf("select2-") && (a = i(this), a && o.push(a))
        })), t.attr("class", o.join(" "))
    };
    function w(e, t, s, i) {
        var n = u(e.toUpperCase()).indexOf(u(t.toUpperCase())), o = t.length;
        return 0 > n ? (s.push(i(e)), void 0) : (s.push(i(e.substring(0, n))), s.push("<span class='select2-match'>"), s.push(i(e.substring(n, n + o))), s.push("</span>"), s.push(i(e.substring(n + o, e.length))), void 0)
    };
    function S(e) {
        var t = {"\\": "&#92;", "&": "&amp;", "<": "&lt;", ">": "&gt;", "\"": "&quot;", "'": "&#39;", "/": "&#47;"};
        return String(e).replace(/[&<>"'\/\\]/g, function (e) {
            return t[e]
        })
    };
    function x(s) {
        var o, n = null, c = s.quietMillis || 100, a = s.url, i = this;
        return function (r) {
            window.clearTimeout(o), o = window.setTimeout(function () {
                var c = s.data, o = a, l = s.transport || e.fn.select2.ajaxDefaults.transport, h = {type: s.type || "GET", cache: s.cache || !1, jsonpCallback: s.jsonpCallback || t, dataType: s.dataType || "json"}, u = e.extend({}, e.fn.select2.ajaxDefaults.params, h);
                c = c ? c.call(i, r.term, r.page, r.context) : null, o = "function" == typeof o ? o.call(i, r.term, r.page, r.context) : o, n && "function" == typeof n.abort && n.abort(), s.params && (e.isFunction(s.params) ? e.extend(u, s.params.call(i)) : e.extend(u, s.params)), e.extend(u, {url: o, dataType: s.dataType, data: c, success: function (e) {
                    var t = s.results(e, r.page, r);
                    r.callback(t)
                }, error: function (e, t, s) {
                    var i = {hasError: !0, jqXHR: e, textStatus: t, errorThrown: s};
                    r.callback(i)
                }}), n = l.call(i, u)
            }, c)
        }
    };
    function E(t) {
        var a, n, s = t, i = function (e) {
            return"" + e.text
        };
        e.isArray(s) && (n = s, s = {results: n}), e.isFunction(s) === !1 && (n = s, s = function () {
            return n
        });
        var o = s();
        return o.text && (i = o.text, e.isFunction(i) || (a = o.text, i = function (e) {
            return e[a]
        })), function (t) {
            var o, n = t.term, a = {results: []};
            return"" === n ? (t.callback(s()), void 0) : (o = function (s, a) {
                var c, r;
                if (s = s[0], s.children) {
                    c = {};
                    for (r in s)s.hasOwnProperty(r) && (c[r] = s[r]);
                    c.children = [], e(s.children).each2(function (e, t) {
                        o(t, c.children)
                    }), (c.children.length || t.matcher(n, i(c), s)) && a.push(c)
                } else t.matcher(n, i(s), s) && a.push(s)
            }, e(s().results).each2(function (e, t) {
                o(t, a.results)
            }), t.callback(a), void 0)
        }
    };
    function y(s) {
        var i = e.isFunction(s);
        return function (n) {
            var a = n.term, c = {results: []}, o = i ? s(n) : s;
            e.isArray(o) && (e(o).each(function () {
                var e = this.text !== t, s = e ? this.text : this;
                ("" === a || n.matcher(a, s)) && c.results.push(e ? this : {id: this, text: this})
            }), n.callback(c))
        }
    };
    function c(t, s) {
        if (e.isFunction(t))return!0;
        if (!t)return!1;
        if ("string" == typeof t)return!0;
        throw new Error(s + " must be a string, function, or falsy value");
    };
    function n(t, s) {
        if (e.isFunction(t)) {
            var i = Array.prototype.slice.call(arguments, 2);
            return t.apply(s, i)
        }
        ;
        return t
    };
    function C(t) {
        var s = 0;
        return e.each(t, function (e, t) {
            t.children ? s += C(t.children) : s++
        }), s
    };
    function D(e, s, i, n) {
        var a, r, c, u, h, d = e, l = !1;
        if (!n.createSearchChoice || !n.tokenSeparators || n.tokenSeparators.length < 1)return t;
        for (; ;) {
            for (r = -1, c = 0, u = n.tokenSeparators.length; u > c && (h = n.tokenSeparators[c], r = e.indexOf(h), !(r >= 0)); c++);
            if (0 > r)break;
            if (a = e.substring(0, r), e = e.substring(r + h.length), a.length > 0 && (a = n.createSearchChoice.call(this, a, s), a !== t && null !== a && n.id(a) !== t && null !== n.id(a))) {
                for (l = !1, c = 0, u = s.length; u > c; c++)if (o(n.id(a), n.id(s[c]))) {
                    l = !0;
                    break
                }
                ;
                l || i(a)
            }
        }
        ;
        return d !== e ? e : void 0
    };
    function v() {
        var t = this;
        e.each(arguments, function (e, s) {
            t[s].remove(), t[s] = null
        })
    };
    function m(t, s) {
        var i = function () {
        };
        return i.prototype = new t, i.prototype.constructor = i, i.prototype.parent = t.prototype, i.prototype = e.extend(i.prototype, s), i
    };
    if (window.Select2 === t) {
        var s, h, I, P, l, r, p, b, g = {x: 0, y: 0}, s = {TAB: 9, ENTER: 13, ESC: 27, SPACE: 32, LEFT: 37, UP: 38, RIGHT: 39, DOWN: 40, SHIFT: 16, CTRL: 17, ALT: 18, PAGE_UP: 33, PAGE_DOWN: 34, HOME: 36, END: 35, BACKSPACE: 8, DELETE: 46, isArrow: function (e) {
            switch (e = e.which ? e.which : e) {
                case s.LEFT:
                case s.RIGHT:
                case s.UP:
                case s.DOWN:
                    return!0
            }
            ;
            return!1
        }, isControl: function (e) {
            var t = e.which;
            switch (t) {
                case s.SHIFT:
                case s.CTRL:
                case s.ALT:
                    return!0
            }
            ;
            return e.metaKey ? !0 : !1
        }, isFunctionKey: function (e) {
            return e = e.which ? e.which : e, e >= 112 && 123 >= e
        }}, R = "<div class='select2-measure-scrollbar'></div>", M = {"\u24b6": "A", "\uff21": "A", "\xc0": "A", "\xc1": "A", "\xc2": "A", "\u1ea6": "A", "\u1ea4": "A", "\u1eaa": "A", "\u1ea8": "A", "\xc3": "A", "\u0100": "A", "\u0102": "A", "\u1eb0": "A", "\u1eae": "A", "\u1eb4": "A", "\u1eb2": "A", "\u0226": "A", "\u01e0": "A", "\xc4": "A", "\u01de": "A", "\u1ea2": "A", "\xc5": "A", "\u01fa": "A", "\u01cd": "A", "\u0200": "A", "\u0202": "A", "\u1ea0": "A", "\u1eac": "A", "\u1eb6": "A", "\u1e00": "A", "\u0104": "A", "\u023a": "A", "\u2c6f": "A", "\ua732": "AA", "\xc6": "AE", "\u01fc": "AE", "\u01e2": "AE", "\ua734": "AO", "\ua736": "AU", "\ua738": "AV", "\ua73a": "AV", "\ua73c": "AY", "\u24b7": "B", "\uff22": "B", "\u1e02": "B", "\u1e04": "B", "\u1e06": "B", "\u0243": "B", "\u0182": "B", "\u0181": "B", "\u24b8": "C", "\uff23": "C", "\u0106": "C", "\u0108": "C", "\u010a": "C", "\u010c": "C", "\xc7": "C", "\u1e08": "C", "\u0187": "C", "\u023b": "C", "\ua73e": "C", "\u24b9": "D", "\uff24": "D", "\u1e0a": "D", "\u010e": "D", "\u1e0c": "D", "\u1e10": "D", "\u1e12": "D", "\u1e0e": "D", "\u0110": "D", "\u018b": "D", "\u018a": "D", "\u0189": "D", "\ua779": "D", "\u01f1": "DZ", "\u01c4": "DZ", "\u01f2": "Dz", "\u01c5": "Dz", "\u24ba": "E", "\uff25": "E", "\xc8": "E", "\xc9": "E", "\xca": "E", "\u1ec0": "E", "\u1ebe": "E", "\u1ec4": "E", "\u1ec2": "E", "\u1ebc": "E", "\u0112": "E", "\u1e14": "E", "\u1e16": "E", "\u0114": "E", "\u0116": "E", "\xcb": "E", "\u1eba": "E", "\u011a": "E", "\u0204": "E", "\u0206": "E", "\u1eb8": "E", "\u1ec6": "E", "\u0228": "E", "\u1e1c": "E", "\u0118": "E", "\u1e18": "E", "\u1e1a": "E", "\u0190": "E", "\u018e": "E", "\u24bb": "F", "\uff26": "F", "\u1e1e": "F", "\u0191": "F", "\ua77b": "F", "\u24bc": "G", "\uff27": "G", "\u01f4": "G", "\u011c": "G", "\u1e20": "G", "\u011e": "G", "\u0120": "G", "\u01e6": "G", "\u0122": "G", "\u01e4": "G", "\u0193": "G", "\ua7a0": "G", "\ua77d": "G", "\ua77e": "G", "\u24bd": "H", "\uff28": "H", "\u0124": "H", "\u1e22": "H", "\u1e26": "H", "\u021e": "H", "\u1e24": "H", "\u1e28": "H", "\u1e2a": "H", "\u0126": "H", "\u2c67": "H", "\u2c75": "H", "\ua78d": "H", "\u24be": "I", "\uff29": "I", "\xcc": "I", "\xcd": "I", "\xce": "I", "\u0128": "I", "\u012a": "I", "\u012c": "I", "\u0130": "I", "\xcf": "I", "\u1e2e": "I", "\u1ec8": "I", "\u01cf": "I", "\u0208": "I", "\u020a": "I", "\u1eca": "I", "\u012e": "I", "\u1e2c": "I", "\u0197": "I", "\u24bf": "J", "\uff2a": "J", "\u0134": "J", "\u0248": "J", "\u24c0": "K", "\uff2b": "K", "\u1e30": "K", "\u01e8": "K", "\u1e32": "K", "\u0136": "K", "\u1e34": "K", "\u0198": "K", "\u2c69": "K", "\ua740": "K", "\ua742": "K", "\ua744": "K", "\ua7a2": "K", "\u24c1": "L", "\uff2c": "L", "\u013f": "L", "\u0139": "L", "\u013d": "L", "\u1e36": "L", "\u1e38": "L", "\u013b": "L", "\u1e3c": "L", "\u1e3a": "L", "\u0141": "L", "\u023d": "L", "\u2c62": "L", "\u2c60": "L", "\ua748": "L", "\ua746": "L", "\ua780": "L", "\u01c7": "LJ", "\u01c8": "Lj", "\u24c2": "M", "\uff2d": "M", "\u1e3e": "M", "\u1e40": "M", "\u1e42": "M", "\u2c6e": "M", "\u019c": "M", "\u24c3": "N", "\uff2e": "N", "\u01f8": "N", "\u0143": "N", "\xd1": "N", "\u1e44": "N", "\u0147": "N", "\u1e46": "N", "\u0145": "N", "\u1e4a": "N", "\u1e48": "N", "\u0220": "N", "\u019d": "N", "\ua790": "N", "\ua7a4": "N", "\u01ca": "NJ", "\u01cb": "Nj", "\u24c4": "O", "\uff2f": "O", "\xd2": "O", "\xd3": "O", "\xd4": "O", "\u1ed2": "O", "\u1ed0": "O", "\u1ed6": "O", "\u1ed4": "O", "\xd5": "O", "\u1e4c": "O", "\u022c": "O", "\u1e4e": "O", "\u014c": "O", "\u1e50": "O", "\u1e52": "O", "\u014e": "O", "\u022e": "O", "\u0230": "O", "\xd6": "O", "\u022a": "O", "\u1ece": "O", "\u0150": "O", "\u01d1": "O", "\u020c": "O", "\u020e": "O", "\u01a0": "O", "\u1edc": "O", "\u1eda": "O", "\u1ee0": "O", "\u1ede": "O", "\u1ee2": "O", "\u1ecc": "O", "\u1ed8": "O", "\u01ea": "O", "\u01ec": "O", "\xd8": "O", "\u01fe": "O", "\u0186": "O", "\u019f": "O", "\ua74a": "O", "\ua74c": "O", "\u01a2": "OI", "\ua74e": "OO", "\u0222": "OU", "\u24c5": "P", "\uff30": "P", "\u1e54": "P", "\u1e56": "P", "\u01a4": "P", "\u2c63": "P", "\ua750": "P", "\ua752": "P", "\ua754": "P", "\u24c6": "Q", "\uff31": "Q", "\ua756": "Q", "\ua758": "Q", "\u024a": "Q", "\u24c7": "R", "\uff32": "R", "\u0154": "R", "\u1e58": "R", "\u0158": "R", "\u0210": "R", "\u0212": "R", "\u1e5a": "R", "\u1e5c": "R", "\u0156": "R", "\u1e5e": "R", "\u024c": "R", "\u2c64": "R", "\ua75a": "R", "\ua7a6": "R", "\ua782": "R", "\u24c8": "S", "\uff33": "S", "\u1e9e": "S", "\u015a": "S", "\u1e64": "S", "\u015c": "S", "\u1e60": "S", "\u0160": "S", "\u1e66": "S", "\u1e62": "S", "\u1e68": "S", "\u0218": "S", "\u015e": "S", "\u2c7e": "S", "\ua7a8": "S", "\ua784": "S", "\u24c9": "T", "\uff34": "T", "\u1e6a": "T", "\u0164": "T", "\u1e6c": "T", "\u021a": "T", "\u0162": "T", "\u1e70": "T", "\u1e6e": "T", "\u0166": "T", "\u01ac": "T", "\u01ae": "T", "\u023e": "T", "\ua786": "T", "\ua728": "TZ", "\u24ca": "U", "\uff35": "U", "\xd9": "U", "\xda": "U", "\xdb": "U", "\u0168": "U", "\u1e78": "U", "\u016a": "U", "\u1e7a": "U", "\u016c": "U", "\xdc": "U", "\u01db": "U", "\u01d7": "U", "\u01d5": "U", "\u01d9": "U", "\u1ee6": "U", "\u016e": "U", "\u0170": "U", "\u01d3": "U", "\u0214": "U", "\u0216": "U", "\u01af": "U", "\u1eea": "U", "\u1ee8": "U", "\u1eee": "U", "\u1eec": "U", "\u1ef0": "U", "\u1ee4": "U", "\u1e72": "U", "\u0172": "U", "\u1e76": "U", "\u1e74": "U", "\u0244": "U", "\u24cb": "V", "\uff36": "V", "\u1e7c": "V", "\u1e7e": "V", "\u01b2": "V", "\ua75e": "V", "\u0245": "V", "\ua760": "VY", "\u24cc": "W", "\uff37": "W", "\u1e80": "W", "\u1e82": "W", "\u0174": "W", "\u1e86": "W", "\u1e84": "W", "\u1e88": "W", "\u2c72": "W", "\u24cd": "X", "\uff38": "X", "\u1e8a": "X", "\u1e8c": "X", "\u24ce": "Y", "\uff39": "Y", "\u1ef2": "Y", "\xdd": "Y", "\u0176": "Y", "\u1ef8": "Y", "\u0232": "Y", "\u1e8e": "Y", "\u0178": "Y", "\u1ef6": "Y", "\u1ef4": "Y", "\u01b3": "Y", "\u024e": "Y", "\u1efe": "Y", "\u24cf": "Z", "\uff3a": "Z", "\u0179": "Z", "\u1e90": "Z", "\u017b": "Z", "\u017d": "Z", "\u1e92": "Z", "\u1e94": "Z", "\u01b5": "Z", "\u0224": "Z", "\u2c7f": "Z", "\u2c6b": "Z", "\ua762": "Z", "\u24d0": "a", "\uff41": "a", "\u1e9a": "a", "\xe0": "a", "\xe1": "a", "\xe2": "a", "\u1ea7": "a", "\u1ea5": "a", "\u1eab": "a", "\u1ea9": "a", "\xe3": "a", "\u0101": "a", "\u0103": "a", "\u1eb1": "a", "\u1eaf": "a", "\u1eb5": "a", "\u1eb3": "a", "\u0227": "a", "\u01e1": "a", "\xe4": "a", "\u01df": "a", "\u1ea3": "a", "\xe5": "a", "\u01fb": "a", "\u01ce": "a", "\u0201": "a", "\u0203": "a", "\u1ea1": "a", "\u1ead": "a", "\u1eb7": "a", "\u1e01": "a", "\u0105": "a", "\u2c65": "a", "\u0250": "a", "\ua733": "aa", "\xe6": "ae", "\u01fd": "ae", "\u01e3": "ae", "\ua735": "ao", "\ua737": "au", "\ua739": "av", "\ua73b": "av", "\ua73d": "ay", "\u24d1": "b", "\uff42": "b", "\u1e03": "b", "\u1e05": "b", "\u1e07": "b", "\u0180": "b", "\u0183": "b", "\u0253": "b", "\u24d2": "c", "\uff43": "c", "\u0107": "c", "\u0109": "c", "\u010b": "c", "\u010d": "c", "\xe7": "c", "\u1e09": "c", "\u0188": "c", "\u023c": "c", "\ua73f": "c", "\u2184": "c", "\u24d3": "d", "\uff44": "d", "\u1e0b": "d", "\u010f": "d", "\u1e0d": "d", "\u1e11": "d", "\u1e13": "d", "\u1e0f": "d", "\u0111": "d", "\u018c": "d", "\u0256": "d", "\u0257": "d", "\ua77a": "d", "\u01f3": "dz", "\u01c6": "dz", "\u24d4": "e", "\uff45": "e", "\xe8": "e", "\xe9": "e", "\xea": "e", "\u1ec1": "e", "\u1ebf": "e", "\u1ec5": "e", "\u1ec3": "e", "\u1ebd": "e", "\u0113": "e", "\u1e15": "e", "\u1e17": "e", "\u0115": "e", "\u0117": "e", "\xeb": "e", "\u1ebb": "e", "\u011b": "e", "\u0205": "e", "\u0207": "e", "\u1eb9": "e", "\u1ec7": "e", "\u0229": "e", "\u1e1d": "e", "\u0119": "e", "\u1e19": "e", "\u1e1b": "e", "\u0247": "e", "\u025b": "e", "\u01dd": "e", "\u24d5": "f", "\uff46": "f", "\u1e1f": "f", "\u0192": "f", "\ua77c": "f", "\u24d6": "g", "\uff47": "g", "\u01f5": "g", "\u011d": "g", "\u1e21": "g", "\u011f": "g", "\u0121": "g", "\u01e7": "g", "\u0123": "g", "\u01e5": "g", "\u0260": "g", "\ua7a1": "g", "\u1d79": "g", "\ua77f": "g", "\u24d7": "h", "\uff48": "h", "\u0125": "h", "\u1e23": "h", "\u1e27": "h", "\u021f": "h", "\u1e25": "h", "\u1e29": "h", "\u1e2b": "h", "\u1e96": "h", "\u0127": "h", "\u2c68": "h", "\u2c76": "h", "\u0265": "h", "\u0195": "hv", "\u24d8": "i", "\uff49": "i", "\xec": "i", "\xed": "i", "\xee": "i", "\u0129": "i", "\u012b": "i", "\u012d": "i", "\xef": "i", "\u1e2f": "i", "\u1ec9": "i", "\u01d0": "i", "\u0209": "i", "\u020b": "i", "\u1ecb": "i", "\u012f": "i", "\u1e2d": "i", "\u0268": "i", "\u0131": "i", "\u24d9": "j", "\uff4a": "j", "\u0135": "j", "\u01f0": "j", "\u0249": "j", "\u24da": "k", "\uff4b": "k", "\u1e31": "k", "\u01e9": "k", "\u1e33": "k", "\u0137": "k", "\u1e35": "k", "\u0199": "k", "\u2c6a": "k", "\ua741": "k", "\ua743": "k", "\ua745": "k", "\ua7a3": "k", "\u24db": "l", "\uff4c": "l", "\u0140": "l", "\u013a": "l", "\u013e": "l", "\u1e37": "l", "\u1e39": "l", "\u013c": "l", "\u1e3d": "l", "\u1e3b": "l", "\u017f": "l", "\u0142": "l", "\u019a": "l", "\u026b": "l", "\u2c61": "l", "\ua749": "l", "\ua781": "l", "\ua747": "l", "\u01c9": "lj", "\u24dc": "m", "\uff4d": "m", "\u1e3f": "m", "\u1e41": "m", "\u1e43": "m", "\u0271": "m", "\u026f": "m", "\u24dd": "n", "\uff4e": "n", "\u01f9": "n", "\u0144": "n", "\xf1": "n", "\u1e45": "n", "\u0148": "n", "\u1e47": "n", "\u0146": "n", "\u1e4b": "n", "\u1e49": "n", "\u019e": "n", "\u0272": "n", "\u0149": "n", "\ua791": "n", "\ua7a5": "n", "\u01cc": "nj", "\u24de": "o", "\uff4f": "o", "\xf2": "o", "\xf3": "o", "\xf4": "o", "\u1ed3": "o", "\u1ed1": "o", "\u1ed7": "o", "\u1ed5": "o", "\xf5": "o", "\u1e4d": "o", "\u022d": "o", "\u1e4f": "o", "\u014d": "o", "\u1e51": "o", "\u1e53": "o", "\u014f": "o", "\u022f": "o", "\u0231": "o", "\xf6": "o", "\u022b": "o", "\u1ecf": "o", "\u0151": "o", "\u01d2": "o", "\u020d": "o", "\u020f": "o", "\u01a1": "o", "\u1edd": "o", "\u1edb": "o", "\u1ee1": "o", "\u1edf": "o", "\u1ee3": "o", "\u1ecd": "o", "\u1ed9": "o", "\u01eb": "o", "\u01ed": "o", "\xf8": "o", "\u01ff": "o", "\u0254": "o", "\ua74b": "o", "\ua74d": "o", "\u0275": "o", "\u01a3": "oi", "\u0223": "ou", "\ua74f": "oo", "\u24df": "p", "\uff50": "p", "\u1e55": "p", "\u1e57": "p", "\u01a5": "p", "\u1d7d": "p", "\ua751": "p", "\ua753": "p", "\ua755": "p", "\u24e0": "q", "\uff51": "q", "\u024b": "q", "\ua757": "q", "\ua759": "q", "\u24e1": "r", "\uff52": "r", "\u0155": "r", "\u1e59": "r", "\u0159": "r", "\u0211": "r", "\u0213": "r", "\u1e5b": "r", "\u1e5d": "r", "\u0157": "r", "\u1e5f": "r", "\u024d": "r", "\u027d": "r", "\ua75b": "r", "\ua7a7": "r", "\ua783": "r", "\u24e2": "s", "\uff53": "s", "\xdf": "s", "\u015b": "s", "\u1e65": "s", "\u015d": "s", "\u1e61": "s", "\u0161": "s", "\u1e67": "s", "\u1e63": "s", "\u1e69": "s", "\u0219": "s", "\u015f": "s", "\u023f": "s", "\ua7a9": "s", "\ua785": "s", "\u1e9b": "s", "\u24e3": "t", "\uff54": "t", "\u1e6b": "t", "\u1e97": "t", "\u0165": "t", "\u1e6d": "t", "\u021b": "t", "\u0163": "t", "\u1e71": "t", "\u1e6f": "t", "\u0167": "t", "\u01ad": "t", "\u0288": "t", "\u2c66": "t", "\ua787": "t", "\ua729": "tz", "\u24e4": "u", "\uff55": "u", "\xf9": "u", "\xfa": "u", "\xfb": "u", "\u0169": "u", "\u1e79": "u", "\u016b": "u", "\u1e7b": "u", "\u016d": "u", "\xfc": "u", "\u01dc": "u", "\u01d8": "u", "\u01d6": "u", "\u01da": "u", "\u1ee7": "u", "\u016f": "u", "\u0171": "u", "\u01d4": "u", "\u0215": "u", "\u0217": "u", "\u01b0": "u", "\u1eeb": "u", "\u1ee9": "u", "\u1eef": "u", "\u1eed": "u", "\u1ef1": "u", "\u1ee5": "u", "\u1e73": "u", "\u0173": "u", "\u1e77": "u", "\u1e75": "u", "\u0289": "u", "\u24e5": "v", "\uff56": "v", "\u1e7d": "v", "\u1e7f": "v", "\u028b": "v", "\ua75f": "v", "\u028c": "v", "\ua761": "vy", "\u24e6": "w", "\uff57": "w", "\u1e81": "w", "\u1e83": "w", "\u0175": "w", "\u1e87": "w", "\u1e85": "w", "\u1e98": "w", "\u1e89": "w", "\u2c73": "w", "\u24e7": "x", "\uff58": "x", "\u1e8b": "x", "\u1e8d": "x", "\u24e8": "y", "\uff59": "y", "\u1ef3": "y", "\xfd": "y", "\u0177": "y", "\u1ef9": "y", "\u0233": "y", "\u1e8f": "y", "\xff": "y", "\u1ef7": "y", "\u1e99": "y", "\u1ef5": "y", "\u01b4": "y", "\u024f": "y", "\u1eff": "y", "\u24e9": "z", "\uff5a": "z", "\u017a": "z", "\u1e91": "z", "\u017c": "z", "\u017e": "z", "\u1e93": "z", "\u1e95": "z", "\u01b6": "z", "\u0225": "z", "\u0240": "z", "\u2c6c": "z", "\ua763": "z", "\u0386": "\u0391", "\u0388": "\u0395", "\u0389": "\u0397", "\u038a": "\u0399", "\u03aa": "\u0399", "\u038c": "\u039f", "\u038e": "\u03a5", "\u03ab": "\u03a5", "\u038f": "\u03a9", "\u03ac": "\u03b1", "\u03ad": "\u03b5", "\u03ae": "\u03b7", "\u03af": "\u03b9", "\u03ca": "\u03b9", "\u0390": "\u03b9", "\u03cc": "\u03bf", "\u03cd": "\u03c5", "\u03cb": "\u03c5", "\u03b0": "\u03c5", "\u03c9": "\u03c9", "\u03c2": "\u03c3"};
        p = e(document), l = function () {
            var e = 1;
            return function () {
                return e++
            }
        }(), h = m(Object, {bind: function (e) {
            var t = this;
            return function () {
                e.apply(t, arguments)
            }
        }, init: function (s) {
            var o, c, a = ".select2-results";
            this.opts = s = this.prepareOpts(s), this.id = s.id, s.element.data("select2") !== t && null !== s.element.data("select2") && s.element.data("select2").destroy(), this.container = this.createContainer(), this.liveRegion = e("<span>", {role: "status", "aria-live": "polite"}).addClass("select2-hidden-accessible").appendTo(document.body), this.containerId = "s2id_" + (s.element.attr("id") || "autogen" + l()), this.containerEventName = this.containerId.replace(/([.])/g, "_").replace(/([;&,\-\.\+\*\~':"\!\^#$%@\[\]\(\)=>\|])/g, "\\$1"), this.container.attr("id", this.containerId), this.container.attr("title", s.element.attr("title")), this.body = e("body"), d(this.container, this.opts.element, this.opts.adaptContainerCssClass), this.container.attr("style", s.element.attr("style")), this.container.css(n(s.containerCss, this.opts.element)), this.container.addClass(n(s.containerCssClass, this.opts.element)), this.elementTabIndex = this.opts.element.attr("tabindex"), this.opts.element.data("select2", this).attr("tabindex", "-1").before(this.container).on("click.select2", i), this.container.data("select2", this), this.dropdown = this.container.find(".select2-drop"), d(this.dropdown, this.opts.element, this.opts.adaptDropdownCssClass), this.dropdown.addClass(n(s.dropdownCssClass, this.opts.element)), this.dropdown.data("select2", this), this.dropdown.on("click", i), this.results = o = this.container.find(a), this.search = c = this.container.find("input.select2-input"), this.queryCount = 0, this.resultsPage = 0, this.context = null, this.initContainer(), this.container.on("click", i), U(this.results), this.dropdown.on("mousemove-filtered", a, this.bind(this.highlightUnderEvent)), this.dropdown.on("touchstart touchmove touchend", a, this.bind(function (e) {
                this.n$ = !0, this.highlightUnderEvent(e)
            })), this.dropdown.on("touchmove", a, this.bind(this.touchMoved)), this.dropdown.on("touchstart touchend", a, this.bind(this.clearTouchMoved)), this.dropdown.on("click", this.bind(function () {
                this.n$ && (this.n$ = !1, this.selectHighlighted())
            })), j(80, this.results), this.dropdown.on("scroll-debounced", a, this.bind(this.loadMoreIfNeeded)), e(this.container).on("change", ".select2-input", function (e) {
                e.stopPropagation()
            }), e(this.dropdown).on("change", ".select2-input", function (e) {
                e.stopPropagation()
            }), e.fn.mousewheel && o.mousewheel(function (e, t, s, n) {
                var a = o.scrollTop();
                n > 0 && 0 >= a - n ? (o.scrollTop(0), i(e)) : 0 > n && o.get(0).scrollHeight - o.scrollTop() + n <= o.height() && (o.scrollTop(o.get(0).scrollHeight - o.height()), i(e))
            }), T(c), c.on("keyup-change input paste", this.bind(this.updateResults)), c.on("focus", function () {
                c.addClass("select2-focused")
            }), c.on("blur", function () {
                c.removeClass("select2-focused")
            }), this.dropdown.on("mouseup", a, this.bind(function (t) {
                e(t.target).closest(".select2-result-selectable").length > 0 && (this.highlightUnderEvent(t), this.selectHighlighted(t))
            })), this.dropdown.on("click mouseup mousedown touchstart touchend focusin", function (e) {
                e.stopPropagation()
            }), this.nextSearchTerm = t, e.isFunction(this.opts.initSelection) && (this.initSelection(), this.monitorSource()), null !== s.maximumInputLength && this.search.attr("maxlength", s.maximumInputLength);
            var r = s.element.prop("disabled");
            r === t && (r = !1), this.enable(!r);
            var u = s.element.prop("readonly");
            u === t && (u = !1), this.readonly(u), b = b || z(), this.autofocus = s.element.prop("autofocus"), s.element.prop("autofocus", !1), this.autofocus && this.focus(), this.search.attr("placeholder", s.searchInputPlaceholder)
        }, destroy: function () {
            var e = this.opts.element, s = e.data("select2"), i = this;
            this.close(), e.length && e[0].detachEvent && e.each(function () {
                this.detachEvent("onpropertychange", i.e$)
            }), this.propertyObserver && (this.propertyObserver.disconnect(), this.propertyObserver = null), this.e$ = null, s !== t && (s.container.remove(), s.liveRegion.remove(), s.dropdown.remove(), e.removeClass("select2-offscreen").removeData("select2").off(".select2").prop("autofocus", this.autofocus || !1), this.elementTabIndex ? e.attr({tabindex: this.elementTabIndex}) : e.removeAttr("tabindex"), e.show()), v.call(this, "container", "liveRegion", "dropdown", "results", "search")
        }, optionToData: function (e) {
            return e.is("option") ? {id: e.prop("value"), text: e.text(), element: e.get(), css: e.attr("class"), disabled: e.prop("disabled"), locked: o(e.attr("locked"), "locked") || o(e.data("locked"), !0)} : e.is("optgroup") ? {text: e.attr("label"), children: [], element: e.get(), css: e.attr("class")} : void 0
        }, prepareOpts: function (s) {
            var c, a, r, n, i = this;
            if (c = s.element, "select" === c.get(0).tagName.toLowerCase() && (this.select = a = s.element), a && e.each(["id", "multiple", "ajax", "query", "createSearchChoice", "initSelection", "data", "tags"], function () {
                if (this in s)throw new Error("Option '" + this + "' is not allowed for Select2 when attached to a <select> element.");
            }), s = e.extend({}, {populateResults: function (n, o, a) {
                var c, u = this.opts.id, r = this.liveRegion;
                c = function (n, o, h) {
                    var m, S, f, C, w, b, d, p, g, v;
                    n = s.sortResults(n, o, a);
                    var x = [];
                    for (m = 0, S = n.length; S > m; m += 1)f = n[m], w = f.disabled === !0, C = !w && u(f) !== t, b = f.children && f.children.length > 0, d = e("<li></li>"), d.addClass("select2-results-dept-" + h), d.addClass("select2-result"), d.addClass(C ? "select2-result-selectable" : "select2-result-unselectable"), w && d.addClass("select2-disabled"), b && d.addClass("select2-result-with-children"), d.addClass(i.opts.formatResultCssClass(f)), d.attr("role", "presentation"), p = e(document.createElement("div")), p.addClass("select2-result-label"), p.attr("id", "select2-result-label-" + l()), p.attr("role", "option"), v = s.formatResult(f, p, a, i.opts.escapeMarkup), v !== t && (p.html(v), d.append(p)), b && (g = e("<ul></ul>"), g.addClass("select2-result-sub"), c(f.children, g, h + 1), d.append(g)), d.data("select2-data", f), x.push(d[0]);
                    o.append(x), r.text(s.formatMatches(n.length))
                }, c(o, n, 0)
            }}, e.fn.select2.defaults, s), "function" != typeof s.id && (r = s.id, s.id = function (e) {
                return e[r]
            }), e.isArray(s.element.data("select2Tags"))) {
                if ("tags"in s)throw"tags specified as both an attribute 'data-select2-tags' and in options of Select2 " + s.element.attr("id");
                s.tags = s.element.data("select2Tags")
            }
            ;
            if (a ? (s.query = this.bind(function (e) {
                var s, o, n, a = {results: [], more: !1}, r = e.term;
                n = function (t, s) {
                    var o;
                    t.is("option") ? e.matcher(r, t.text(), t) && s.push(i.optionToData(t)) : t.is("optgroup") && (o = i.optionToData(t), t.children().each2(function (e, t) {
                        n(t, o.children)
                    }), o.children.length > 0 && s.push(o))
                }, s = c.children(), this.getPlaceholder() !== t && s.length > 0 && (o = this.getPlaceholderOption(), o && (s = s.not(o))), s.each2(function (e, t) {
                    n(t, a.results)
                }), e.callback(a)
            }), s.id = function (e) {
                return e.id
            }) : "query"in s || ("ajax"in s ? (n = s.element.data("ajax-url"), n && n.length > 0 && (s.ajax.url = n), s.query = x.call(s.element, s.ajax)) : "data"in s ? s.query = E(s.data) : "tags"in s && (s.query = y(s.tags), s.createSearchChoice === t && (s.createSearchChoice = function (t) {
                return{id: e.trim(t), text: e.trim(t)}
            }), s.initSelection === t && (s.initSelection = function (t, i) {
                var n = [];
                e(f(t.val(), s.separator)).each(function () {
                    var i = {id: this, text: this}, t = s.tags;
                    e.isFunction(t) && (t = t()), e(t).each(function () {
                        return o(this.id, i.id) ? (i = this, !1) : void 0
                    }), n.push(i)
                }), i(n)
            }))), "function" != typeof s.query)throw"query function not defined for Select2 " + s.element.attr("id");
            if ("top" === s.createSearchChoicePosition)s.createSearchChoicePosition = function (e, t) {
                e.unshift(t)
            }; else if ("bottom" === s.createSearchChoicePosition)s.createSearchChoicePosition = function (e, t) {
                e.push(t)
            }; else if ("function" != typeof s.createSearchChoicePosition)throw"invalid createSearchChoicePosition option must be 'top', 'bottom' or a custom function";
            return s
        }, monitorSource: function () {
            var i, s = this.opts.element, o = this;
            s.on("change.select2", this.bind(function () {
                this.opts.element.data("select2-change-triggered") !== !0 && this.initSelection()
            })), this.e$ = this.bind(function () {
                var e = s.prop("disabled");
                e === t && (e = !1), this.enable(!e);
                var i = s.prop("readonly");
                i === t && (i = !1), this.readonly(i), d(this.container, this.opts.element, this.opts.adaptContainerCssClass), this.container.addClass(n(this.opts.containerCssClass, this.opts.element)), d(this.dropdown, this.opts.element, this.opts.adaptDropdownCssClass), this.dropdown.addClass(n(this.opts.dropdownCssClass, this.opts.element))
            }), s.length && s[0].attachEvent && s.each(function () {
                this.attachEvent("onpropertychange", o.e$)
            }), i = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver, i !== t && (this.propertyObserver && (delete this.propertyObserver, this.propertyObserver = null), this.propertyObserver = new i(function (t) {
                e.each(t, o.e$)
            }), this.propertyObserver.observe(s.get(0), {attributes: !0, subtree: !1}))
        }, triggerSelect: function (t) {
            var s = e.Event("select2-selecting", {val: this.id(t), object: t, choice: t});
            return this.opts.element.trigger(s), !s.isDefaultPrevented()
        }, triggerChange: function (t) {
            t = t || {}, t = e.extend({}, t, {type: "change", val: this.val()}), this.opts.element.data("select2-change-triggered", !0), this.opts.element.trigger(t), this.opts.element.data("select2-change-triggered", !1), this.opts.element.click(), this.opts.blurOnChange && this.opts.element.blur()
        }, isInterfaceEnabled: function () {
            return this.enabledInterface === !0
        }, enableInterface: function () {
            var e = this.s$ && !this.t$, t = !e;
            return e === this.enabledInterface ? !1 : (this.container.toggleClass("select2-container-disabled", t), this.close(), this.enabledInterface = e, !0)
        }, enable: function (e) {
            e === t && (e = !0), this.s$ !== e && (this.s$ = e, this.opts.element.prop("disabled", !e), this.enableInterface())
        }, disable: function () {
            this.enable(!1)
        }, readonly: function (e) {
            e === t && (e = !1), this.t$ !== e && (this.t$ = e, this.opts.element.prop("readonly", e), this.enableInterface())
        }, opened: function () {
            return this.container ? this.container.hasClass("select2-dropdown-open") : !1
        }, positionDropdown: function () {
            var g, l, m, i, v, t = this.dropdown, o = this.container.offset(), f = this.container.outerHeight(!1), u = this.container.outerWidth(!1), r = t.outerHeight(!1), c = e(window), y = c.width(), w = c.height(), d = c.scrollLeft() + y, C = c.scrollTop() + w, h = o.top + f, a = o.left, x = C >= h + r, S = o.top - r >= c.scrollTop(), s = t.outerWidth(!1), p = d >= a + s, E = t.hasClass("select2-drop-above");
            E ? (l = !0, !S && x && (m = !0, l = !1)) : (l = !1, !x && S && (m = !0, l = !0)), m && (t.hide(), o = this.container.offset(), f = this.container.outerHeight(!1), u = this.container.outerWidth(!1), r = t.outerHeight(!1), d = c.scrollLeft() + y, C = c.scrollTop() + w, h = o.top + f, a = o.left, s = t.outerWidth(!1), p = d >= a + s, t.show(), this.focusSearch()), this.opts.dropdownAutoWidth ? (v = e(".select2-results", t)[0], t.addClass("select2-drop-auto-width"), t.css("width", ""), s = t.outerWidth(!1) + (v.scrollHeight === v.clientHeight ? 0 : b.width), s > u ? u = s : s = u, r = t.outerHeight(!1), p = d >= a + s) : this.container.removeClass("select2-drop-auto-width"), "static" !== this.body.css("position") && (g = this.body.offset(), h -= g.top, a -= g.left), p || (a = o.left + this.container.outerWidth(!1) - s), i = {left: a, width: u}, l ? (i.top = o.top - r, i.bottom = "auto", this.container.addClass("select2-drop-above"), t.addClass("select2-drop-above")) : (i.top = h, i.bottom = "auto", this.container.removeClass("select2-drop-above"), t.removeClass("select2-drop-above")), i = e.extend(i, n(this.opts.dropdownCss, this.opts.element)), t.css(i)
        }, shouldOpen: function () {
            var t;
            return this.opened() ? !1 : this.s$ === !1 || this.t$ === !0 ? !1 : (t = e.Event("select2-opening"), this.opts.element.trigger(t), !t.isDefaultPrevented())
        }, clearDropdownAlignmentPreference: function () {
            this.container.removeClass("select2-drop-above"), this.dropdown.removeClass("select2-drop-above")
        }, open: function () {
            return this.shouldOpen() ? (this.opening(), p.on("mousemove.select2Event", function (e) {
                g.x = e.pageX, g.y = e.pageY
            }), !0) : !1
        }, opening: function () {
            var t, s = this.containerEventName, o = "scroll." + s, a = "resize." + s, n = "orientationchange." + s;
            this.container.addClass("select2-dropdown-open").addClass("select2-container-active"), this.clearDropdownAlignmentPreference(), this.dropdown[0] !== this.body.children().last()[0] && this.dropdown.detach().appendTo(this.body), t = e("#select2-drop-mask"), 0 == t.length && (t = e(document.createElement("div")), t.attr("id", "select2-drop-mask").attr("class", "select2-drop-mask"), t.hide(), t.appendTo(this.body), t.on("mousedown touchstart click", function (s) {
                O(t);
                var i, n = e("#select2-drop");
                n.length > 0 && (i = n.data("select2"), i.opts.selectOnBlur && i.selectHighlighted({noFocus: !0}), i.close(), s.preventDefault(), s.stopPropagation())
            })), this.dropdown.prev()[0] !== t[0] && this.dropdown.before(t), e("#select2-drop").removeAttr("id"), this.dropdown.attr("id", "select2-drop"), t.show(), this.positionDropdown(), this.dropdown.show(), this.positionDropdown(), this.dropdown.addClass("select2-drop-active");
            var i = this;
            this.container.parents().add(window).each(function () {
                e(this).on(a + " " + o + " " + n, function () {
                    i.opened() && i.positionDropdown()
                })
            })
        }, close: function () {
            if (this.opened()) {
                var t = this.containerEventName, i = "scroll." + t, n = "resize." + t, s = "orientationchange." + t;
                this.container.parents().add(window).each(function () {
                    e(this).off(i).off(n).off(s)
                }), this.clearDropdownAlignmentPreference(), e("#select2-drop-mask").hide(), this.dropdown.removeAttr("id"), this.dropdown.hide(), this.container.removeClass("select2-dropdown-open").removeClass("select2-container-active"), this.results.empty(), p.off("mousemove.select2Event"), this.clearSearch(), this.search.removeClass("select2-active"), this.opts.element.trigger(e.Event("select2-close"))
            }
        }, externalSearch: function (e) {
            this.open(), this.search.val(e), this.updateResults(!1)
        }, clearSearch: function () {
        }, getMaximumSelectionSize: function () {
            return n(this.opts.maximumSelectionSize, this.opts.element)
        }, ensureHighlightVisible: function () {
            var r, s, n, i, c, u, o, a, t = this.results;
            if (s = this.highlight(), !(0 > s)) {
                if (0 == s)return t.scrollTop(0), void 0;
                r = this.findHighlightableChoices().find(".select2-result-label"), n = e(r[s]), a = (n.offset() || {}).top || 0, i = a + n.outerHeight(!0), s === r.length - 1 && (o = t.find("li.select2-more-results"), o.length > 0 && (i = o.offset().top + o.outerHeight(!0))), c = t.offset().top + t.outerHeight(!0), i > c && t.scrollTop(t.scrollTop() + (i - c)), u = a - t.offset().top, 0 > u && "none" != n.css("display") && t.scrollTop(t.scrollTop() + u)
            }
        }, findHighlightableChoices: function () {
            return this.results.find(".select2-result-selectable:not(.select2-disabled):not(.select2-selected)")
        }, moveHighlight: function (t) {
            for (var n = this.findHighlightableChoices(), s = this.highlight(); s > -1 && s < n.length;) {
                s += t;
                var i = e(n[s]);
                if (i.hasClass("select2-result-selectable") && !i.hasClass("select2-disabled") && !i.hasClass("select2-selected")) {
                    this.highlight(s);
                    break
                }
            }
        }, highlight: function (t) {
            var i, n, s = this.findHighlightableChoices();
            return 0 === arguments.length ? a(s.filter(".select2-highlighted")[0], s.get()) : (t >= s.length && (t = s.length - 1), 0 > t && (t = 0), this.removeHighlight(), i = e(s[t]), i.addClass("select2-highlighted"), this.search.attr("aria-activedescendant", i.find(".select2-result-label").attr("id")), this.ensureHighlightVisible(), this.liveRegion.text(i.text()), n = i.data("select2-data"), n && this.opts.element.trigger({type: "select2-highlight", val: this.id(n), choice: n}), void 0)
        }, removeHighlight: function () {
            this.results.find(".select2-highlighted").removeClass("select2-highlighted")
        }, touchMoved: function () {
            this.i$ = !0
        }, clearTouchMoved: function () {
            this.i$ = !1
        }, countSelectableResults: function () {
            return this.findHighlightableChoices().length
        }, highlightUnderEvent: function (t) {
            var s = e(t.target).closest(".select2-result-selectable");
            if (s.length > 0 && !s.is(".select2-highlighted")) {
                var i = this.findHighlightableChoices();
                this.highlight(i.index(s))
            } else 0 == s.length && this.removeHighlight()
        }, loadMoreIfNeeded: function () {
            var c, s = this.results, t = s.find("li.select2-more-results"), i = this.resultsPage + 1, e = this, a = this.search.val(), o = this.context;
            0 !== t.length && (c = t.offset().top - s.offset().top - s.height(), c <= this.opts.loadMorePadding && (t.addClass("select2-active"), this.opts.query({element: this.opts.element, term: a, page: i, context: o, matcher: this.opts.matcher, callback: this.bind(function (c) {
                e.opened() && (e.opts.populateResults.call(this, s, c.results, {term: a, page: i, context: o}), e.postprocessResults(c, !1, !1), c.more === !0 ? (t.detach().appendTo(s).text(n(e.opts.formatLoadMore, e.opts.element, i + 1)), window.setTimeout(function () {
                    e.loadMoreIfNeeded()
                }, 10)) : t.remove(), e.positionDropdown(), e.resultsPage = i, e.context = c.context, this.opts.element.trigger({type: "select2-loaded", items: c}))
            })})))
        }, tokenize: function () {
        }, updateResults: function (s) {
            function p() {
                a.removeClass("select2-active"), r.positionDropdown(), l.find(".select2-no-results,.select2-selection-limit,.select2-searching").length ? r.liveRegion.text(l.text()) : r.liveRegion.text(r.opts.formatMatches(l.find(".select2-result-selectable").length))
            };
            function u(e) {
                l.html(e), p()
            };
            var f, h, m, a = this.search, l = this.results, i = this.opts, r = this, g = a.val(), v = e.data(this.container, "select2-last-term");
            if ((s === !0 || !v || !o(g, v)) && (e.data(this.container, "select2-last-term", g), s === !0 || this.showSearchInput !== !1 && this.opened())) {
                m = ++this.queryCount;
                var d = this.getMaximumSelectionSize();
                if (d >= 1 && (f = this.data(), e.isArray(f) && f.length >= d && c(i.formatSelectionTooBig, "formatSelectionTooBig")))return u("<li class='select2-selection-limit'>" + n(i.formatSelectionTooBig, i.element, d) + "</li>"), void 0;
                if (a.val().length < i.minimumInputLength)return c(i.formatInputTooShort, "formatInputTooShort") ? u("<li class='select2-no-results'>" + n(i.formatInputTooShort, i.element, a.val(), i.minimumInputLength) + "</li>") : u(""), s && this.showSearch && this.showSearch(!0), void 0;
                if (i.maximumInputLength && a.val().length > i.maximumInputLength)return c(i.formatInputTooLong, "formatInputTooLong") ? u("<li class='select2-no-results'>" + n(i.formatInputTooLong, i.element, a.val(), i.maximumInputLength) + "</li>") : u(""), void 0;
                i.formatSearching && 0 === this.findHighlightableChoices().length && u("<li class='select2-searching'>" + n(i.formatSearching, i.element) + "</li>"), a.addClass("select2-active"), this.removeHighlight(), h = this.tokenize(), h != t && null != h && a.val(h), this.resultsPage = 1, i.query({element: i.element, term: a.val(), page: this.resultsPage, context: null, matcher: i.matcher, callback: this.bind(function (h) {
                    var d;
                    if (m == this.queryCount) {
                        if (!this.opened())return this.search.removeClass("select2-active"), void 0;
                        if (h.hasError !== t && c(i.formatAjaxError, "formatAjaxError"))return u("<li class='select2-ajax-error'>" + n(i.formatAjaxError, i.element, h.jqXHR, h.textStatus, h.errorThrown) + "</li>"), void 0;
                        if (this.context = h.context === t ? null : h.context, this.opts.createSearchChoice && "" !== a.val() && (d = this.opts.createSearchChoice.call(r, a.val(), h.results), d !== t && null !== d && r.id(d) !== t && null !== r.id(d) && 0 === e(h.results).filter(function () {
                            return o(r.id(this), r.id(d))
                        }).length && this.opts.createSearchChoicePosition(h.results, d)), 0 === h.results.length && c(i.formatNoMatches, "formatNoMatches"))return u("<li class='select2-no-results'>" + n(i.formatNoMatches, i.element, a.val()) + "</li>"), void 0;
                        l.empty(), r.opts.populateResults.call(this, l, h.results, {term: a.val(), page: this.resultsPage, context: null}), h.more === !0 && c(i.formatLoadMore, "formatLoadMore") && (l.append("<li class='select2-more-results'>" + i.escapeMarkup(n(i.formatLoadMore, i.element, this.resultsPage)) + "</li>"), window.setTimeout(function () {
                            r.loadMoreIfNeeded()
                        }, 10)), this.postprocessResults(h, s), p(), this.opts.element.trigger({type: "select2-loaded", items: h})
                    }
                })})
            }
        }, cancel: function () {
            this.close()
        }, blur: function () {
            this.opts.selectOnBlur && this.selectHighlighted({noFocus: !0}), this.close(), this.container.removeClass("select2-container-active"), this.search[0] === document.activeElement && this.search.blur(), this.clearSearch(), this.selection.find(".select2-search-choice-focus").removeClass("select2-search-choice-focus")
        }, focusSearch: function () {
            N(this.search)
        }, selectHighlighted: function (e) {
            if (this.i$)return this.clearTouchMoved(), void 0;
            var s = this.highlight(), i = this.results.find(".select2-highlighted"), t = i.closest(".select2-result").data("select2-data");
            t ? (this.highlight(s), this.onSelect(t, e)) : e && e.noFocus && this.close()
        }, getPlaceholder: function () {
            var e;
            return this.opts.element.attr("placeholder") || this.opts.element.attr("data-placeholder") || this.opts.element.data("placeholder") || this.opts.placeholder || ((e = this.getPlaceholderOption()) !== t ? e.text() : t)
        }, getPlaceholderOption: function () {
            if (this.select) {
                var s = this.select.children("option").first();
                if (this.opts.placeholderOption !== t)return"first" === this.opts.placeholderOption && s || "function" == typeof this.opts.placeholderOption && this.opts.placeholderOption(this.select);
                if ("" === e.trim(s.text()) && "" === s.val())return s
            }
        }, initContainerWidth: function () {
            function i() {
                var s, o, n, i, c, a;
                if ("off" === this.opts.width)return null;
                if ("element" === this.opts.width)return 0 === this.opts.element.outerWidth(!1) ? "auto" : this.opts.element.outerWidth(!1) + "px";
                if ("copy" === this.opts.width || "resolve" === this.opts.width) {
                    if (s = this.opts.element.attr("style"), s !== t)for (o = s.split(";"), i = 0, c = o.length; c > i; i += 1)if (a = o[i].replace(/\s/g, ""), n = a.match(/^width:(([-+]?([0-9]*\.)?[0-9]+)(px|em|ex|%|in|cm|mm|pt|pc))/i), null !== n && n.length >= 1)return n[1];
                    return"resolve" === this.opts.width ? (s = this.opts.element.css("width"), s.indexOf("%") > 0 ? s : 0 === this.opts.element.outerWidth(!1) ? "auto" : this.opts.element.outerWidth(!1) + "px") : null
                }
                ;
                return e.isFunction(this.opts.width) ? this.opts.width() : this.opts.width
            };
            var s = i.call(this);
            null !== s && this.container.css("width", s)
        }}), I = m(h, {createContainer: function () {
            var t = e(document.createElement("div")).attr({"class": "select2-container"}).html(["<a href='javascript:void(0)' class='select2-choice' tabindex='-1'>", "   <span class='select2-chosen'>&#160;</span><abbr class='select2-search-choice-close'></abbr>", "   <span class='select2-arrow' role='presentation'><b role='presentation'></b></span>", "</a>", "<label for='' class='select2-offscreen'></label>", "<input class='select2-focusser select2-offscreen' type='text' aria-haspopup='true' role='button' />", "<div class='select2-drop select2-display-none'>", "   <div class='select2-search'>", "       <label for='' class='select2-offscreen'></label>", "       <input type='text' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' class='select2-input' role='combobox' aria-expanded='true'", "       aria-autocomplete='list' />", "   </div>", "   <ul class='select2-results' role='listbox'>", "   </ul>", "</div>"].join(""));
            return t
        }, enableInterface: function () {
            this.parent.enableInterface.apply(this, arguments) && this.focusser.prop("disabled", !this.isInterfaceEnabled())
        }, opening: function () {
            var s, n, i;
            this.opts.minimumResultsForSearch >= 0 && this.showSearch(!0), this.parent.opening.apply(this, arguments), this.showSearchInput !== !1 && this.search.val(this.focusser.val()), this.opts.shouldFocusInput(this) && (this.search.focus(), s = this.search.get(0), s.createTextRange ? (n = s.createTextRange(), n.collapse(!1), n.select()) : s.setSelectionRange && (i = this.search.val().length, s.setSelectionRange(i, i))), "" === this.search.val() && this.nextSearchTerm != t && (this.search.val(this.nextSearchTerm), this.search.select()), this.focusser.prop("disabled", !0).val(""), this.updateResults(!0), this.opts.element.trigger(e.Event("select2-open"))
        }, close: function () {
            this.opened() && (this.parent.close.apply(this, arguments), this.focusser.prop("disabled", !1), this.opts.shouldFocusInput(this) && this.focusser.focus())
        }, focus: function () {
            this.opened() ? this.close() : (this.focusser.prop("disabled", !1), this.opts.shouldFocusInput(this) && this.focusser.focus())
        }, isFocused: function () {
            return this.container.hasClass("select2-container-active")
        }, cancel: function () {
            this.parent.cancel.apply(this, arguments), this.focusser.prop("disabled", !1), this.opts.shouldFocusInput(this) && this.focusser.focus()
        }, destroy: function () {
            e("label[for='" + this.focusser.attr("id") + "']").attr("for", this.opts.element.attr("id")), this.parent.destroy.apply(this, arguments), v.call(this, "selection", "focusser")
        }, initContainer: function () {
            var t, o, a = this.container, r = this.dropdown, n = l();
            this.opts.minimumResultsForSearch < 0 ? this.showSearch(!1) : this.showSearch(!0), this.selection = t = a.find(".select2-choice"), this.focusser = a.find(".select2-focusser"), t.find(".select2-chosen").attr("id", "select2-chosen-" + n), this.focusser.attr("aria-labelledby", "select2-chosen-" + n), this.results.attr("id", "select2-results-" + n), this.search.attr("aria-owns", "select2-results-" + n), this.focusser.attr("id", "s2id_autogen" + n), o = e("label[for='" + this.opts.element.attr("id") + "']"), this.focusser.prev().text(o.text()).attr("for", this.focusser.attr("id"));
            var c = this.opts.element.attr("title");
            this.opts.element.attr("title", c || o.text()), this.focusser.attr("tabindex", this.elementTabIndex), this.search.attr("id", this.focusser.attr("id") + "_search"), this.search.prev().text(e("label[for='" + this.focusser.attr("id") + "']").text()).attr("for", this.search.attr("id")), this.search.on("keydown", this.bind(function (e) {
                if (this.isInterfaceEnabled() && 229 != e.keyCode) {
                    if (e.which === s.PAGE_UP || e.which === s.PAGE_DOWN)return i(e), void 0;
                    switch (e.which) {
                        case s.UP:
                        case s.DOWN:
                            return this.moveHighlight(e.which === s.UP ? -1 : 1), i(e), void 0;
                        case s.ENTER:
                            return this.selectHighlighted(), i(e), void 0;
                        case s.TAB:
                            return this.selectHighlighted({noFocus: !0}), void 0;
                        case s.ESC:
                            return this.cancel(e), i(e), void 0
                    }
                }
            })), this.search.on("blur", this.bind(function () {
                document.activeElement === this.body.get(0) && window.setTimeout(this.bind(function () {
                    this.opened() && this.search.focus()
                }), 0)
            })), this.focusser.on("keydown", this.bind(function (e) {
                if (this.isInterfaceEnabled() && e.which !== s.TAB && !s.isControl(e) && !s.isFunctionKey(e) && e.which !== s.ESC) {
                    if (this.opts.openOnEnter === !1 && e.which === s.ENTER)return i(e), void 0;
                    if (e.which == s.DOWN || e.which == s.UP || e.which == s.ENTER && this.opts.openOnEnter) {
                        if (e.altKey || e.ctrlKey || e.shiftKey || e.metaKey)return;
                        return this.open(), i(e), void 0
                    }
                    ;
                    return e.which == s.DELETE || e.which == s.BACKSPACE ? (this.opts.allowClear && this.clear(), i(e), void 0) : void 0
                }
            })), T(this.focusser), this.focusser.on("keyup-change input", this.bind(function (e) {
                if (this.opts.minimumResultsForSearch >= 0) {
                    if (e.stopPropagation(), this.opened())return;
                    this.open()
                }
            })), t.on("mousedown touchstart", "abbr", this.bind(function (e) {
                this.isInterfaceEnabled() && (this.clear(), L(e), this.close(), this.selection.focus())
            })), t.on("mousedown touchstart", this.bind(function (s) {
                O(t), this.container.hasClass("select2-container-active") || this.opts.element.trigger(e.Event("select2-focus")), this.opened() ? this.close() : this.isInterfaceEnabled() && this.open(), i(s)
            })), r.on("mousedown touchstart", this.bind(function () {
                this.opts.shouldFocusInput(this) && this.search.focus()
            })), t.on("focus", this.bind(function (e) {
                i(e)
            })), this.focusser.on("focus", this.bind(function () {
                this.container.hasClass("select2-container-active") || this.opts.element.trigger(e.Event("select2-focus")), this.container.addClass("select2-container-active")
            })).on("blur", this.bind(function () {
                this.opened() || (this.container.removeClass("select2-container-active"), this.opts.element.trigger(e.Event("select2-blur")))
            })), this.search.on("focus", this.bind(function () {
                this.container.hasClass("select2-container-active") || this.opts.element.trigger(e.Event("select2-focus")), this.container.addClass("select2-container-active")
            })), this.initContainerWidth(), this.opts.element.addClass("select2-offscreen"), this.setPlaceholder()
        }, clear: function (t) {
            var s = this.selection.data("select2-data");
            if (s) {
                var n = e.Event("select2-clearing");
                if (this.opts.element.trigger(n), n.isDefaultPrevented())return;
                var i = this.getPlaceholderOption();
                this.opts.element.val(i ? i.val() : ""), this.selection.find(".select2-chosen").empty(), this.selection.removeData("select2-data"), this.setPlaceholder(), t !== !1 && (this.opts.element.trigger({type: "select2-removed", val: this.id(s), choice: s}), this.triggerChange({removed: s}))
            }
        }, initSelection: function () {
            if (this.isPlaceholderOptionSelected())this.updateSelection(null), this.close(), this.setPlaceholder(); else {
                var e = this;
                this.opts.initSelection.call(null, this.opts.element, function (s) {
                    s !== t && null !== s && (e.updateSelection(s), e.close(), e.setPlaceholder(), e.nextSearchTerm = e.opts.nextSearchTerm(s, e.search.val()))
                })
            }
        }, isPlaceholderOptionSelected: function () {
            var e;
            return this.getPlaceholder() === t ? !1 : (e = this.getPlaceholderOption()) !== t && e.prop("selected") || "" === this.opts.element.val() || this.opts.element.val() === t || null === this.opts.element.val()
        }, prepareOpts: function () {
            var t = this.parent.prepareOpts.apply(this, arguments), s = this;
            return"select" === t.element.get(0).tagName.toLowerCase() ? t.initSelection = function (e, t) {
                var i = e.find("option").filter(function () {
                    return this.selected && !this.disabled
                });
                t(s.optionToData(i))
            } : "data"in t && (t.initSelection = t.initSelection || function (s, i) {
                var a = s.val(), n = null;
                t.query({matcher: function (e, s, i) {
                    var c = o(a, t.id(i));
                    return c && (n = i), c
                }, callback: e.isFunction(i) ? function () {
                    i(n)
                } : e.noop})
            }), t
        }, getPlaceholder: function () {
            return this.select && this.getPlaceholderOption() === t ? t : this.parent.getPlaceholder.apply(this, arguments)
        }, setPlaceholder: function () {
            var e = this.getPlaceholder();
            if (this.isPlaceholderOptionSelected() && e !== t) {
                if (this.select && this.getPlaceholderOption() === t)return;
                this.selection.find(".select2-chosen").html(this.opts.escapeMarkup(e)), this.selection.addClass("select2-default"), this.container.removeClass("select2-allowclear")
            }
        }, postprocessResults: function (e, t, s) {
            var i = 0, n = this;
            if (this.findHighlightableChoices().each2(function (e, t) {
                return o(n.id(t.data("select2-data")), n.opts.element.val()) ? (i = e, !1) : void 0
            }), s !== !1 && (t === !0 && i >= 0 ? this.highlight(i) : this.highlight(0)), t === !0) {
                var a = this.opts.minimumResultsForSearch;
                a >= 0 && this.showSearch(C(e.results) >= a)
            }
        }, showSearch: function (t) {
            this.showSearchInput !== t && (this.showSearchInput = t, this.dropdown.find(".select2-search").toggleClass("select2-search-hidden", !t), this.dropdown.find(".select2-search").toggleClass("select2-offscreen", !t), e(this.dropdown, this.container).toggleClass("select2-with-searchbox", t))
        }, onSelect: function (e, t) {
            if (this.triggerSelect(e)) {
                var i = this.opts.element.val(), s = this.data();
                this.opts.element.val(this.id(e)), this.updateSelection(e), this.opts.element.trigger({type: "select2-selected", val: this.id(e), choice: e}), this.nextSearchTerm = this.opts.nextSearchTerm(e, this.search.val()), this.close(), t && t.noFocus || !this.opts.shouldFocusInput(this) || this.focusser.focus(), o(i, this.id(e)) || this.triggerChange({added: e, removed: s})
            }
        }, updateSelection: function (e) {
            var i, n, s = this.selection.find(".select2-chosen");
            this.selection.data("select2-data", e), s.empty(), null !== e && (i = this.opts.formatSelection(e, s, this.opts.escapeMarkup)), i !== t && s.append(i), n = this.opts.formatSelectionCssClass(e, s), n !== t && s.addClass(n), this.selection.removeClass("select2-default"), this.opts.allowClear && this.getPlaceholder() !== t && this.container.addClass("select2-allowclear")
        }, val: function () {
            var s, i = !1, n = null, e = this, o = this.data();
            if (0 === arguments.length)return this.opts.element.val();
            if (s = arguments[0], arguments.length > 1 && (i = arguments[1]), this.select)this.select.val(s).find("option").filter(function () {
                return this.selected
            }).each2(function (t, s) {
                return n = e.optionToData(s), !1
            }), this.updateSelection(n), this.setPlaceholder(), i && this.triggerChange({added: n, removed: o}); else {
                if (!s && 0 !== s)return this.clear(i), void 0;
                if (this.opts.initSelection === t)throw new Error("cannot call val() if initSelection() is not defined");
                this.opts.element.val(s), this.opts.initSelection(this.opts.element, function (t) {
                    e.opts.element.val(t ? e.id(t) : ""), e.updateSelection(t), e.setPlaceholder(), i && e.triggerChange({added: t, removed: o})
                })
            }
        }, clearSearch: function () {
            this.search.val(""), this.focusser.val("")
        }, data: function (e) {
            var s, i = !1;
            return 0 === arguments.length ? (s = this.selection.data("select2-data"), s == t && (s = null), s) : (arguments.length > 1 && (i = arguments[1]), e ? (s = this.data(), this.opts.element.val(e ? this.id(e) : ""), this.updateSelection(e), i && this.triggerChange({added: e, removed: s})) : this.clear(i), void 0)
        }}), P = m(h, {createContainer: function () {
            var t = e(document.createElement("div")).attr({"class": "select2-container select2-container-multi"}).html(["<ul class='select2-choices'>", "  <li class='select2-search-field'>", "    <label for='' class='select2-offscreen'></label>", "    <input type='text' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' class='select2-input'>", "  </li>", "</ul>", "<div class='select2-drop select2-drop-multi select2-display-none'>", "   <ul class='select2-results'>", "   </ul>", "</div>"].join(""));
            return t
        }, prepareOpts: function () {
            var t = this.parent.prepareOpts.apply(this, arguments), s = this;
            return"select" === t.element.get(0).tagName.toLowerCase() ? t.initSelection = function (e, t) {
                var i = [];
                e.find("option").filter(function () {
                    return this.selected && !this.disabled
                }).each2(function (e, t) {
                    i.push(s.optionToData(t))
                }), t(i)
            } : "data"in t && (t.initSelection = t.initSelection || function (s, i) {
                var a = f(s.val(), t.separator), n = [];
                t.query({matcher: function (s, i, c) {
                    var r = e.grep(a,function (e) {
                        return o(e, t.id(c))
                    }).length;
                    return r && n.push(c), r
                }, callback: e.isFunction(i) ? function () {
                    for (var c = [], s = 0; s < a.length; s++)for (var u = a[s], e = 0; e < n.length; e++) {
                        var r = n[e];
                        if (o(u, t.id(r))) {
                            c.push(r), n.splice(e, 1);
                            break
                        }
                    }
                    ;
                    i(c)
                } : e.noop})
            }), t
        }, selectChoice: function (e) {
            var t = this.container.find(".select2-search-choice-focus");
            t.length && e && e[0] == t[0] || (t.length && this.opts.element.trigger("choice-deselected", t), t.removeClass("select2-search-choice-focus"), e && e.length && (this.close(), e.addClass("select2-search-choice-focus"), this.opts.element.trigger("choice-selected", e)))
        }, destroy: function () {
            e("label[for='" + this.search.attr("id") + "']").attr("for", this.opts.element.attr("id")), this.parent.destroy.apply(this, arguments), v.call(this, "searchContainer", "selection")
        }, initContainer: function () {
            var n, t = ".select2-choices";
            this.searchContainer = this.container.find(".select2-search-field"), this.selection = n = this.container.find(t);
            var o = this;
            this.selection.on("click", ".select2-search-choice:not(.select2-locked)", function () {
                o.search[0].focus(), o.selectChoice(e(this))
            }), this.search.attr("id", "s2id_autogen" + l()), this.search.prev().text(e("label[for='" + this.opts.element.attr("id") + "']").text()).attr("for", this.search.attr("id")), this.search.on("input paste", this.bind(function () {
                this.search.attr("placeholder") && 0 == this.search.val().length || this.isInterfaceEnabled() && (this.opened() || this.open())
            })), this.search.attr("tabindex", this.elementTabIndex), this.keydowns = 0, this.search.on("keydown", this.bind(function (e) {
                if (this.isInterfaceEnabled()) {
                    ++this.keydowns;
                    var o = n.find(".select2-search-choice-focus"), c = o.prev(".select2-search-choice:not(.select2-locked)"), a = o.next(".select2-search-choice:not(.select2-locked)"), r = F(this.search);
                    if (o.length && (e.which == s.LEFT || e.which == s.RIGHT || e.which == s.BACKSPACE || e.which == s.DELETE || e.which == s.ENTER)) {
                        var t = o;
                        return e.which == s.LEFT && c.length ? t = c : e.which == s.RIGHT ? t = a.length ? a : null : e.which === s.BACKSPACE ? this.unselect(o.first()) && (this.search.width(10), t = c.length ? c : a) : e.which == s.DELETE ? this.unselect(o.first()) && (this.search.width(10), t = a.length ? a : null) : e.which == s.ENTER && (t = null), this.selectChoice(t), i(e), t && t.length || this.open(), void 0
                    }
                    ;
                    if ((e.which === s.BACKSPACE && 1 == this.keydowns || e.which == s.LEFT) && 0 == r.offset && !r.length)return this.selectChoice(n.find(".select2-search-choice:not(.select2-locked)").last()), i(e), void 0;
                    if (this.selectChoice(null), this.opened())switch (e.which) {
                        case s.UP:
                        case s.DOWN:
                            return this.moveHighlight(e.which === s.UP ? -1 : 1), i(e), void 0;
                        case s.ENTER:
                            return this.selectHighlighted(), i(e), void 0;
                        case s.TAB:
                            return this.selectHighlighted({noFocus: !0}), this.close(), void 0;
                        case s.ESC:
                            return this.cancel(e), i(e), void 0
                    }
                    ;
                    if (e.which !== s.TAB && !s.isControl(e) && !s.isFunctionKey(e) && e.which !== s.BACKSPACE && e.which !== s.ESC) {
                        if (e.which === s.ENTER) {
                            if (this.opts.openOnEnter === !1)return;
                            if (e.altKey || e.ctrlKey || e.shiftKey || e.metaKey)return
                        }
                        ;
                        this.open(), (e.which === s.PAGE_UP || e.which === s.PAGE_DOWN) && i(e), e.which === s.ENTER && i(e)
                    }
                }
            })), this.search.on("keyup", this.bind(function () {
                this.keydowns = 0, this.resizeSearch()
            })), this.search.on("blur", this.bind(function (t) {
                this.container.removeClass("select2-container-active"), this.search.removeClass("select2-focused"), this.selectChoice(null), this.opened() || this.clearSearch(), t.stopImmediatePropagation(), this.opts.element.trigger(e.Event("select2-blur"))
            })), this.container.on("click", t, this.bind(function (t) {
                this.isInterfaceEnabled() && (e(t.target).closest(".select2-search-choice").length > 0 || (this.selectChoice(null), this.clearPlaceholder(), this.container.hasClass("select2-container-active") || this.opts.element.trigger(e.Event("select2-focus")), this.open(), this.focusSearch(), t.preventDefault()))
            })), this.container.on("focus", t, this.bind(function () {
                this.isInterfaceEnabled() && (this.container.hasClass("select2-container-active") || this.opts.element.trigger(e.Event("select2-focus")), this.container.addClass("select2-container-active"), this.dropdown.addClass("select2-drop-active"), this.clearPlaceholder())
            })), this.initContainerWidth(), this.opts.element.addClass("select2-offscreen"), this.clearSearch()
        }, enableInterface: function () {
            this.parent.enableInterface.apply(this, arguments) && this.search.prop("disabled", !this.isInterfaceEnabled())
        }, initSelection: function () {
            if ("" === this.opts.element.val() && "" === this.opts.element.text() && (this.updateSelection([]), this.close(), this.clearSearch()), this.select || "" !== this.opts.element.val()) {
                var e = this;
                this.opts.initSelection.call(null, this.opts.element, function (s) {
                    s !== t && null !== s && (e.updateSelection(s), e.close(), e.clearSearch())
                })
            }
        }, clearSearch: function () {
            var s = this.getPlaceholder(), e = this.getMaxSearchWidth();
            s !== t && 0 === this.getVal().length && this.search.hasClass("select2-focused") === !1 ? (this.search.val(s).addClass("select2-default"), this.search.width(e > 0 ? e : this.container.css("width"))) : this.search.val("").width(10)
        }, clearPlaceholder: function () {
            this.search.hasClass("select2-default") && this.search.val("").removeClass("select2-default")
        }, opening: function () {
            this.clearPlaceholder(), this.resizeSearch(), this.parent.opening.apply(this, arguments), this.focusSearch(), "" === this.search.val() && this.nextSearchTerm != t && (this.search.val(this.nextSearchTerm), this.search.select()), this.updateResults(!0), this.opts.shouldFocusInput(this) && this.search.focus(), this.opts.element.trigger(e.Event("select2-open"))
        }, close: function () {
            this.opened() && this.parent.close.apply(this, arguments)
        }, focus: function () {
            this.close(), this.search.focus()
        }, isFocused: function () {
            return this.search.hasClass("select2-focused")
        }, updateSelection: function (t) {
            var i = [], n = [], s = this;
            e(t).each(function () {
                a(s.id(this), i) < 0 && (i.push(s.id(this)), n.push(this))
            }), t = n, this.selection.find(".select2-search-choice").remove(), e(t).each(function () {
                s.addSelectedChoice(this)
            }), s.postprocessResults()
        }, tokenize: function () {
            var e = this.search.val();
            e = this.opts.tokenizer.call(this, e, this.data(), this.bind(this.onSelect), this.opts), null != e && e != t && (this.search.val(e), e.length > 0 && this.open())
        }, onSelect: function (e, s) {
            this.triggerSelect(e) && "" !== e.text && (this.addSelectedChoice(e), this.opts.element.trigger({type: "selected", val: this.id(e), choice: e}), this.nextSearchTerm = this.opts.nextSearchTerm(e, this.search.val()), this.clearSearch(), this.updateResults(), (this.select || !this.opts.closeOnSelect) && this.postprocessResults(e, !1, this.opts.closeOnSelect === !0), this.opts.closeOnSelect ? (this.close(), this.search.width(10)) : this.countSelectableResults() > 0 ? (this.search.width(10), this.resizeSearch(), this.getMaximumSelectionSize() > 0 && this.val().length >= this.getMaximumSelectionSize() ? this.updateResults(!0) : this.nextSearchTerm != t && (this.search.val(this.nextSearchTerm), this.updateResults(), this.search.select()), this.positionDropdown()) : (this.close(), this.search.width(10)), this.triggerChange({added: e}), s && s.noFocus || this.focusSearch())
        }, cancel: function () {
            this.close(), this.focusSearch()
        }, addSelectedChoice: function (s) {
            var a, o, r = !s.locked, l = e("<li class='select2-search-choice'>    <div></div>    <a href='#' class='select2-search-choice-close' tabindex='-1'></a></li>"), h = e("<li class='select2-search-choice select2-locked'><div></div></li>"), n = r ? l : h, u = this.id(s), c = this.getVal();
            a = this.opts.formatSelection(s, n.find("div"), this.opts.escapeMarkup), a != t && n.find("div").replaceWith("<div>" + a + "</div>"), o = this.opts.formatSelectionCssClass(s, n.find("div")), o != t && n.addClass(o), r && n.find(".select2-search-choice-close").on("mousedown", i).on("click dblclick", this.bind(function (t) {
                this.isInterfaceEnabled() && (this.unselect(e(t.target)), this.selection.find(".select2-search-choice-focus").removeClass("select2-search-choice-focus"), i(t), this.close(), this.focusSearch())
            })).on("focus", this.bind(function () {
                this.isInterfaceEnabled() && (this.container.addClass("select2-container-active"), this.dropdown.addClass("select2-drop-active"))
            })), n.data("select2-data", s), n.insertBefore(this.searchContainer), c.push(u), this.setVal(c)
        }, unselect: function (t) {
            var s, o, n = this.getVal();
            if (t = t.closest(".select2-search-choice"), 0 === t.length)throw"Invalid argument: " + t + ". Must be .select2-search-choice";
            if (s = t.data("select2-data")) {
                var i = e.Event("select2-removing");
                if (i.val = this.id(s), i.choice = s, this.opts.element.trigger(i), i.isDefaultPrevented())return!1;
                for (; (o = a(this.id(s), n)) >= 0;)n.splice(o, 1), this.setVal(n), this.select && this.postprocessResults();
                return t.remove(), this.opts.element.trigger({type: "select2-removed", val: this.id(s), choice: s}), this.triggerChange({removed: s}), !0
            }
        }, postprocessResults: function (e, t, s) {
            var r = this.getVal(), o = this.results.find(".select2-result"), u = this.results.find(".select2-result-with-children"), i = this;
            o.each2(function (e, t) {
                var s = i.id(t.data("select2-data"));
                a(s, r) >= 0 && (t.addClass("select2-selected"), t.find(".select2-result-selectable").addClass("select2-selected"))
            }), u.each2(function (e, t) {
                t.is(".select2-result-selectable") || 0 !== t.find(".select2-result-selectable:not(.select2-selected)").length || t.addClass("select2-selected")
            }), -1 == this.highlight() && s !== !1 && i.highlight(0), !this.opts.createSearchChoice && !o.filter(".select2-result:not(.select2-selected)").length > 0 && (!e || e && !e.more && 0 === this.results.find(".select2-no-results").length) && c(i.opts.formatNoMatches, "formatNoMatches") && this.results.append("<li class='select2-no-results'>" + n(i.opts.formatNoMatches, i.opts.element, i.search.val()) + "</li>")
        }, getMaxSearchWidth: function () {
            return this.selection.width() - A(this.search)
        }, resizeSearch: function () {
            var s, o, t, n, e, i = A(this.search);
            s = H(this.search) + 10, o = this.search.offset().left, t = this.selection.width(), n = this.selection.offset().left, e = t - (o - n) - i, s > e && (e = t - i), 40 > e && (e = t - i), 0 >= e && (e = s), this.search.width(Math.floor(e))
        }, getVal: function () {
            var e;
            return this.select ? (e = this.select.val(), null === e ? [] : e) : (e = this.opts.element.val(), f(e, this.opts.separator))
        }, setVal: function (t) {
            var s;
            this.select ? this.select.val(t) : (s = [], e(t).each(function () {
                a(this, s) < 0 && s.push(this)
            }), this.opts.element.val(0 === s.length ? "" : s.join(this.opts.separator)))
        }, buildChangeDetails: function (e, t) {
            for (var t = t.slice(0), e = e.slice(0), s = 0; s < t.length; s++)for (var i = 0; i < e.length; i++)o(this.opts.id(t[s]), this.opts.id(e[i])) && (t.splice(s, 1), s > 0 && s--, e.splice(i, 1), i--);
            return{added: t, removed: e}
        }, val: function (s, i) {
            var o, n = this;
            if (0 === arguments.length)return this.getVal();
            if (o = this.data(), o.length || (o = []), !s && 0 !== s)return this.opts.element.val(""), this.updateSelection([]), this.clearSearch(), i && this.triggerChange({added: this.data(), removed: o}), void 0;
            if (this.setVal(s), this.select)this.opts.initSelection(this.select, this.bind(this.updateSelection)), i && this.triggerChange(this.buildChangeDetails(o, this.data())); else {
                if (this.opts.initSelection === t)throw new Error("val() cannot be called if initSelection() is not defined");
                this.opts.initSelection(this.opts.element, function (t) {
                    var s = e.map(t, n.id);
                    n.setVal(s), n.updateSelection(t), n.clearSearch(), i && n.triggerChange(n.buildChangeDetails(o, n.data()))
                })
            }
            ;
            this.clearSearch()
        }, onSortStart: function () {
            if (this.select)throw new Error("Sorting of elements is not supported when attached to <select>. Attach to <input type='hidden'/> instead.");
            this.search.width(0), this.searchContainer.hide()
        }, onSortEnd: function () {
            var t = [], s = this;
            this.searchContainer.show(), this.searchContainer.appendTo(this.searchContainer.parent()), this.resizeSearch(), this.selection.find(".select2-search-choice").each(function () {
                t.push(s.opts.id(e(this).data("select2-data")))
            }), this.setVal(t), this.triggerChange()
        }, data: function (t, s) {
            var n, i, o = this;
            return 0 === arguments.length ? this.selection.children(".select2-search-choice").map(function () {
                return e(this).data("select2-data")
            }).get() : (i = this.data(), t || (t = []), n = e.map(t, function (e) {
                return o.opts.id(e)
            }), this.setVal(n), this.updateSelection(t), this.clearSearch(), s && this.triggerChange(this.buildChangeDetails(i, this.data())), void 0)
        }}), e.fn.select2 = function () {
            var n, i, o, c, r, s = Array.prototype.slice.call(arguments, 0), d = ["val", "destroy", "opened", "open", "close", "focus", "isFocused", "container", "dropdown", "onSortStart", "onSortEnd", "enable", "disable", "readonly", "positionDropdown", "data", "search"], h = ["opened", "isFocused", "container", "dropdown"], l = ["val", "data"], u = {search: "externalSearch"};
            return this.each(function () {
                if (0 === s.length || "object" == typeof s[0])n = 0 === s.length ? {} : e.extend({}, s[0]), n.element = e(this), "select" === n.element.get(0).tagName.toLowerCase() ? r = n.element.prop("multiple") : (r = n.multiple || !1, "tags"in n && (n.multiple = r = !0)), i = r ? new window.Select2["class"].multi : new window.Select2["class"].single, i.init(n); else {
                    if ("string" != typeof s[0])throw"Invalid arguments to select2 plugin: " + s;
                    if (a(s[0], d) < 0)throw"Unknown method: " + s[0];
                    if (c = t, i = e(this).data("select2"), i === t)return;
                    if (o = s[0], "container" === o ? c = i.container : "dropdown" === o ? c = i.dropdown : (u[o] && (o = u[o]), c = i[o].apply(i, s.slice(1))), a(s[0], h) >= 0 || a(s[0], l) >= 0 && 1 == s.length)return!1
                }
            }), c === t ? this : c
        }, e.fn.select2.defaults = {width: "copy", loadMorePadding: 0, closeOnSelect: !0, openOnEnter: !0, containerCss: {}, dropdownCss: {}, containerCssClass: "", dropdownCssClass: "", formatResult: function (e, t, s, i) {
            var n = [];
            return w(e.text, s.term, n, i), n.join("")
        }, formatSelection: function (e, s, i) {
            return e ? i(e.text) : t
        }, sortResults: function (e) {
            return e
        }, formatResultCssClass: function (e) {
            return e.css
        }, formatSelectionCssClass: function () {
            return t
        }, minimumResultsForSearch: 0, minimumInputLength: 0, maximumInputLength: null, maximumSelectionSize: 0, id: function (e) {
            return e == t ? null : e.id
        }, matcher: function (e, t) {
            return u("" + t).toUpperCase().indexOf(u("" + e).toUpperCase()) >= 0
        }, separator: ",", tokenSeparators: [], tokenizer: D, escapeMarkup: S, blurOnChange: !1, selectOnBlur: !1, adaptContainerCssClass: function (e) {
            return e
        }, adaptDropdownCssClass: function () {
            return null
        }, nextSearchTerm: function () {
            return t
        }, searchInputPlaceholder: "", createSearchChoicePosition: "top", shouldFocusInput: function (e) {
            var t = "ontouchstart"in window || navigator.msMaxTouchPoints > 0;
            return t ? e.opts.minimumResultsForSearch < 0 ? !1 : !0 : !0
        }}, e.fn.select2.locales = [], e.fn.select2.locales.en = {formatMatches: function (e) {
            return 1 === e ? "One result is available, press enter to select it." : e + " results are available, use up and down arrow keys to navigate."
        }, formatNoMatches: function () {
            return"No matches found"
        }, formatAjaxError: function () {
            return"Loading failed"
        }, formatInputTooShort: function (e, t) {
            var s = t - e.length;
            return"Please enter " + s + " or more character" + (1 == s ? "" : "s")
        }, formatInputTooLong: function (e, t) {
            var s = e.length - t;
            return"Please delete " + s + " character" + (1 == s ? "" : "s")
        }, formatSelectionTooBig: function (e) {
            return"You can only select " + e + " item" + (1 == e ? "" : "s")
        }, formatLoadMore: function () {
            return"Loading more results\u2026"
        }, formatSearching: function () {
            return"Searching\u2026"
        }}, e.extend(e.fn.select2.defaults, e.fn.select2.locales.en), e.fn.select2.ajaxDefaults = {transport: e.ajax, params: {type: "GET", cache: !1, dataType: "json"}}, window.Select2 = {query: {ajax: x, local: E, tags: y}, util: {debounce: k, markMatch: w, escapeMarkup: S, stripDiacritics: u}, "class": {"abstract": h, single: I, multi: P}}
    }
}(jQuery);
/*! nanoScrollerJS - v0.8.0 - (c) 2014 James Florentino; Licensed MIT */
!function (t, i, c) {
    'use strict';
    var S, s, r, h, f, Y, D, T, y, v, o, m, C, l, e, M, g, n, E, p, A, O, w, b, d, u, x, H, a;
    A = {paneClass: 'nano-pane', sliderClass: 'nano-slider', contentClass: 'nano-content', iOSNativeScrolling: !1, preventPageScrolling: !1, disableResize: !1, alwaysVisible: !1, flashDelay: 1500, sliderMinHeight: 20, sliderMaxHeight: null, documentContext: null, windowContext: null}, M = 'scrollbar', e = 'scroll', T = 'mousedown', y = 'mousemove', o = 'mousewheel', v = 'mouseup', l = 'resize', f = 'drag', n = 'up', C = 'panedown', r = 'DOMMouseScroll', h = 'down', E = 'wheel', Y = 'keydown', D = 'keyup', g = 'touchmove', S = 'Microsoft Internet Explorer' === i.navigator.appName && /msie 7./i.test(i.navigator.appVersion) && i.ActiveXObject, s = null, d = i.requestAnimationFrame, p = i.cancelAnimationFrame, x = c.createElement('div').style, a = function () {
        var i, s, n, t, e, o;
        for (t = ['t', 'webkitT', 'MozT', 'msT', 'OT'], i = e = 0, o = t.length; o > e; i = ++e)if (n = t[i], s = t[i] + 'ransform', s in x)return t[i].substr(0, t[i].length - 1);
        return!1
    }(), H = function (t) {
        return a === !1 ? !1 : '' === a ? t : a + t.charAt(0).toUpperCase() + t.substr(1)
    }, u = H('transform'), w = u !== !1, O = function () {
        var t, i, s;
        return t = c.createElement('div'), i = t.style, i.position = 'absolute', i.width = '100px', i.height = '100px', i.overflow = e, i.top = '-9999px', c.body.appendChild(t), s = t.offsetWidth - t.clientWidth, c.body.removeChild(t), s
    }, b = function () {
        var s, e, t;
        return e = i.navigator.userAgent, (s = /(?=.+Mac OS X)(?=.+Firefox)/.test(e)) ? (t = /Firefox\/\d{2}\./.exec(e), t && (t = t[0].replace(/\D+/g, '')), s && +t > 23) : !1
    }, m = function () {
        function a(e, o) {
            this.el = e, this.options = o, s || (s = O()), this.t$ = t(this.el), this.doc = t(this.options.documentContext || c), this.win = t(this.options.windowContext || i), this.i$ = this.t$.children('.' + o.contentClass), this.i$.attr('tabindex', this.options.tabIndex || 0), this.content = this.i$[0], this.previousPosition = 0, this.options.iOSNativeScrolling && null != this.el.style.WebkitOverflowScrolling ? this.nativeScrolling() : this.generate(), this.createEvents(), this.addEvents(), this.reset()
        };
        return a.prototype.preventScrolling = function (t, i) {
            if (this.isActive)if (t.type === r)(i === h && t.originalEvent.detail > 0 || i === n && t.originalEvent.detail < 0) && t.preventDefault(); else if (t.type === o) {
                if (!t.originalEvent || !t.originalEvent.wheelDelta)return;
                (i === h && t.originalEvent.wheelDelta < 0 || i === n && t.originalEvent.wheelDelta > 0) && t.preventDefault()
            }
        }, a.prototype.nativeScrolling = function () {
            this.i$.css({WebkitOverflowScrolling: 'touch'}), this.iOSNativeScrolling = !0, this.isActive = !0
        }, a.prototype.updateScrollValues = function () {
            var t, i;
            t = this.content, this.maxScrollTop = t.scrollHeight - t.clientHeight, this.prevScrollTop = this.contentScrollTop || 0, this.contentScrollTop = t.scrollTop, i = this.contentScrollTop > this.previousPosition ? 'down' : this.contentScrollTop < this.previousPosition ? 'up' : 'same', this.previousPosition = this.contentScrollTop, 'same' !== i && this.t$.trigger('update', {position: this.contentScrollTop, maximum: this.maxScrollTop, direction: i}), this.iOSNativeScrolling || (this.maxSliderTop = this.paneHeight - this.sliderHeight, this.sliderTop = 0 === this.maxScrollTop ? 0 : this.contentScrollTop * this.maxSliderTop / this.maxScrollTop)
        }, a.prototype.setOnScrollStyles = function () {
            var t;
            w ? (t = {}, t[u] = 'translate(0, ' + this.sliderTop + 'px)') : t = {top: this.sliderTop}, d ? this.scrollRAF || (this.scrollRAF = d(function (i) {
                return function () {
                    i.scrollRAF = null, i.slider.css(t)
                }
            }(this))) : this.slider.css(t)
        }, a.prototype.createEvents = function () {
            this.events = {down: function (t) {
                return function (i) {
                    return t.isBeingDragged = !0, t.offsetY = i.pageY - t.slider.offset().top, t.pane.addClass('active'), t.doc.bind(y, t.events[f]).bind(v, t.events[n]), !1
                }
            }(this), drag: function (t) {
                return function (i) {
                    return t.sliderY = i.pageY - t.t$.offset().top - t.offsetY, t.scroll(), t.contentScrollTop >= t.maxScrollTop && t.prevScrollTop !== t.maxScrollTop ? t.t$.trigger('scrollend') : 0 === t.contentScrollTop && 0 !== t.prevScrollTop && t.t$.trigger('scrolltop'), !1
                }
            }(this), up: function (t) {
                return function () {
                    return t.isBeingDragged = !1, t.pane.removeClass('active'), t.doc.unbind(y, t.events[f]).unbind(v, t.events[n]), !1
                }
            }(this), resize: function (t) {
                return function () {
                    t.reset()
                }
            }(this), panedown: function (t) {
                return function (i) {
                    return t.sliderY = (i.offsetY || i.originalEvent.layerY) - .5 * t.sliderHeight, t.scroll(), t.events.down(i), !1
                }
            }(this), scroll: function (t) {
                return function (i) {
                    t.updateScrollValues(), t.isBeingDragged || (t.iOSNativeScrolling || (t.sliderY = t.sliderTop, t.setOnScrollStyles()), null != i && (t.contentScrollTop >= t.maxScrollTop ? (t.options.preventPageScrolling && t.preventScrolling(i, h), t.prevScrollTop !== t.maxScrollTop && t.t$.trigger('scrollend')) : 0 === t.contentScrollTop && (t.options.preventPageScrolling && t.preventScrolling(i, n), 0 !== t.prevScrollTop && t.t$.trigger('scrolltop'))))
                }
            }(this), wheel: function (t) {
                return function (i) {
                    var e;
                    if (null != i)return e = i.delta || i.wheelDelta || i.originalEvent && i.originalEvent.wheelDelta || -i.detail || i.originalEvent && -i.originalEvent.detail, e && (t.sliderY += -e / 3), t.scroll(), !1
                }
            }(this)}
        }, a.prototype.addEvents = function () {
            var t;
            this.removeEvents(), t = this.events, this.options.disableResize || this.win.bind(l, t[l]), this.iOSNativeScrolling || (this.slider.bind(T, t[h]), this.pane.bind(T, t[C]).bind('' + o + ' ' + r, t[E])), this.i$.bind('' + e + ' ' + o + ' ' + r + ' ' + g, t[e])
        }, a.prototype.removeEvents = function () {
            var t;
            t = this.events, this.win.unbind(l, t[l]), this.iOSNativeScrolling || (this.slider.unbind(), this.pane.unbind()), this.i$.unbind('' + e + ' ' + o + ' ' + r + ' ' + g, t[e])
        }, a.prototype.generate = function () {
            var l, o, r, t, n, e;
            return t = this.options, n = t.paneClass, e = t.sliderClass, l = t.contentClass, this.t$.find('.' + n).length || this.t$.find('.' + e).length || this.t$.append('<div class="' + n + '"><div class="' + e + '" /></div>'), this.pane = this.t$.children('.' + n), this.slider = this.pane.find('.' + e), 0 === s && b() ? (r = i.getComputedStyle(this.content, null).getPropertyValue('padding-right').replace(/\D+/g, ''), o = {right: -14, paddingRight: +r + 14}) : s && (o = {right: -s}, this.t$.addClass('has-scrollbar')), null != o && this.i$.css(o), this
        }, a.prototype.restore = function () {
            this.stopped = !1, this.iOSNativeScrolling || this.pane.show(), this.addEvents()
        }, a.prototype.reset = function () {
            var i, l, h, c, r, d, a, o, u, n, p, t;
            return this.iOSNativeScrolling ? void(this.contentHeight = this.content.scrollHeight) : (this.t$.find('.' + this.options.paneClass).length || this.generate().stop(), this.stopped && this.restore(), i = this.content, c = i.style, r = c.overflowY, S && this.i$.css({height: this.i$.height()}), l = i.scrollHeight + s, n = parseInt(this.t$.css('max-height'), 10), n > 0 && (this.t$.height(''), this.t$.height(i.scrollHeight > n ? n : i.scrollHeight)), a = this.pane.outerHeight(!1), u = parseInt(this.pane.css('top'), 10), d = parseInt(this.pane.css('bottom'), 10), o = a + u + d, t = Math.round(o / l * o), t < this.options.sliderMinHeight ? t = this.options.sliderMinHeight : null != this.options.sliderMaxHeight && t > this.options.sliderMaxHeight && (t = this.options.sliderMaxHeight), r === e && c.overflowX !== e && (t += s), this.maxSliderTop = o - t, this.contentHeight = l, this.paneHeight = a, this.paneOuterHeight = o, this.sliderHeight = t, this.slider.height(t), this.events.scroll(), this.pane.show(), this.isActive = !0, i.scrollHeight === i.clientHeight || this.pane.outerHeight(!0) >= i.scrollHeight && r !== e ? (this.pane.hide(), this.isActive = !1) : this.el.clientHeight === i.scrollHeight && r === e ? this.slider.hide() : this.slider.show(), this.pane.css({opacity: this.options.alwaysVisible ? 1 : '', visibility: this.options.alwaysVisible ? 'visible' : ''}), h = this.i$.css('position'), ('static' === h || 'relative' === h) && (p = parseInt(this.i$.css('right'), 10), p && this.i$.css({right: '', marginRight: p})), this)
        }, a.prototype.scroll = function () {
            return this.isActive ? (this.sliderY = Math.max(0, this.sliderY), this.sliderY = Math.min(this.maxSliderTop, this.sliderY), this.i$.scrollTop((this.paneHeight - this.contentHeight + s) * this.sliderY / this.maxSliderTop * -1), this.iOSNativeScrolling || (this.updateScrollValues(), this.setOnScrollStyles()), this) : void 0
        }, a.prototype.scrollBottom = function (t) {
            return this.isActive ? (this.i$.scrollTop(this.contentHeight - this.i$.height() - t).trigger(o), this.stop().restore(), this) : void 0
        }, a.prototype.scrollTop = function (t) {
            return this.isActive ? (this.i$.scrollTop(+t).trigger(o), this.stop().restore(), this) : void 0
        }, a.prototype.scrollTo = function (t) {
            return this.isActive ? (this.scrollTop(this.t$.find(t).get(0).offsetTop), this) : void 0
        }, a.prototype.stop = function () {
            return p && this.scrollRAF && (p(this.scrollRAF), this.scrollRAF = null), this.stopped = !0, this.removeEvents(), this.iOSNativeScrolling || this.pane.hide(), this
        }, a.prototype.destroy = function () {
            return this.stopped || this.stop(), !this.iOSNativeScrolling && this.pane.length && this.pane.remove(), S && this.i$.height(''), this.i$.removeAttr('tabindex'), this.t$.hasClass('has-scrollbar') && (this.t$.removeClass('has-scrollbar'), this.i$.css({right: ''})), this
        }, a.prototype.flash = function () {
            return!this.iOSNativeScrolling && this.isActive ? (this.reset(), this.pane.addClass('flashed'), setTimeout(function (t) {
                return function () {
                    t.pane.removeClass('flashed')
                }
            }(this), this.options.flashDelay), this) : void 0
        }, a
    }(), t.fn.nanoScroller = function (i) {
        return this.each(function () {
            var s, e;
            if ((e = this.nanoscroller) || (s = t.extend({}, A, i), this.nanoscroller = e = new m(this, s)), i && 'object' == typeof i) {
                if (t.extend(e.options, i), null != i.scrollBottom)return e.scrollBottom(i.scrollBottom);
                if (null != i.scrollTop)return e.scrollTop(i.scrollTop);
                if (i.scrollTo)return e.scrollTo(i.scrollTo);
                if ('bottom' === i.scroll)return e.scrollBottom(0);
                if ('top' === i.scroll)return e.scrollTop(0);
                if (i.scroll && i.scroll instanceof t)return e.scrollTo(i.scroll);
                if (i.stop)return e.stop();
                if (i.destroy)return e.destroy();
                if (i.flash)return e.flash()
            }
            ;
            return e.reset()
        })
    }, t.fn.nanoScroller.Constructor = m
}(jQuery, window, document);
;
if (typeof JSON !== 'object') {
    JSON = {}
}
(function () {
    'use strict';
    function r(e) {
        return e < 10 ? '0' + e : e
    };
    if (typeof Date.prototype.toJSON !== 'function') {
        Date.prototype.toJSON = function (e) {
            return isFinite(this.valueOf()) ? this.getUTCFullYear() + '-' + r(this.getUTCMonth() + 1) + '-' + r(this.getUTCDate()) + 'T' + r(this.getUTCHours()) + ':' + r(this.getUTCMinutes()) + ':' + r(this.getUTCSeconds()) + 'Z' : null
        };
        String.prototype.toJSON = Number.prototype.toJSON = Boolean.prototype.toJSON = function (e) {
            return this.valueOf()
        }
    }
    ;
    var s = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g, i = /[\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g, e, a, u = {'\b': '\\b', '\t': '\\t', '\n': '\\n', '\f': '\\f', '\r': '\\r', '"': '\\"', '\\': '\\\\'}, t;

    function o(e) {
        i.lastIndex = 0;
        return i.test(e) ? '"' + e.replace(i, function (e) {
            var t = u[e];
            return typeof t === 'string' ? t : '\\u' + ('0000' + e.charCodeAt(0).toString(16)).slice(-4)
        }) + '"' : '"' + e + '"'
    };
    function n(r, i) {
        var f, c, l, p, d = e, u, s = i[r];
        if (s && typeof s === 'object' && typeof s.toJSON === 'function') {
            s = s.toJSON(r)
        }
        ;
        if (typeof t === 'function') {
            s = t.call(i, r, s)
        }
        ;
        switch (typeof s) {
            case'string':
                return o(s);
            case'number':
                return isFinite(s) ? String(s) : 'null';
            case'boolean':
            case'null':
                return String(s);
            case'object':
                if (!s) {
                    return'null'
                }
                ;
                e += a;
                u = [];
                if (Object.prototype.toString.apply(s) === '[object Array]') {
                    p = s.length;
                    for (f = 0; f < p; f += 1) {
                        u[f] = n(f, s) || 'null'
                    }
                    ;
                    l = u.length === 0 ? '[]' : e ? '[\n' + e + u.join(',\n' + e) + '\n' + d + ']' : '[' + u.join(',') + ']';
                    e = d;
                    return l
                }
                ;
                if (t && typeof t === 'object') {
                    p = t.length;
                    for (f = 0; f < p; f += 1) {
                        if (typeof t[f] === 'string') {
                            c = t[f];
                            l = n(c, s);
                            if (l) {
                                u.push(o(c) + (e ? ': ' : ':') + l)
                            }
                        }
                    }
                } else {
                    for (c in s) {
                        if (Object.prototype.hasOwnProperty.call(s, c)) {
                            l = n(c, s);
                            if (l) {
                                u.push(o(c) + (e ? ': ' : ':') + l)
                            }
                        }
                    }
                }
                ;
                l = u.length === 0 ? '{}' : e ? '{\n' + e + u.join(',\n' + e) + '\n' + d + '}' : '{' + u.join(',') + '}';
                e = d;
                return l
        }
    };
    if (typeof JSON.stringify !== 'function') {
        JSON.stringify = function (r, o, i) {
            var s;
            e = '';
            a = '';
            if (typeof i === 'number') {
                for (s = 0; s < i; s += 1) {
                    a += ' '
                }
            } else if (typeof i === 'string') {
                a = i
            }
            ;
            t = o;
            if (o && typeof o !== 'function' && (typeof o !== 'object' || typeof o.length !== 'number')) {
                throw new Error('JSON.stringify');
            }
            ;
            return n('', {'': r})
        }
    }
    ;
    if (typeof JSON.parse !== 'function') {
        JSON.parse = function (e, t) {
            var r;

            function a(e, r) {
                var o, i, n = e[r];
                if (n && typeof n === 'object') {
                    for (o in n) {
                        if (Object.prototype.hasOwnProperty.call(n, o)) {
                            i = a(n, o);
                            if (i !== undefined) {
                                n[o] = i
                            } else {
                                delete n[o]
                            }
                        }
                    }
                }
                ;
                return t.call(e, r, n)
            };
            e = String(e);
            s.lastIndex = 0;
            if (s.test(e)) {
                e = e.replace(s, function (e) {
                    return'\\u' + ('0000' + e.charCodeAt(0).toString(16)).slice(-4)
                })
            }
            ;
            if (/^[\],:{}\s]*$/.test(e.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
                r = eval('(' + e + ')');
                return typeof t === 'function' ? a({'': r}, '') : r
            }
            ;
            throw new SyntaxError('JSON.parse');
        }
    }
}());
(function (e, t) {
    'use strict';
    var r = e.History = e.History || {}, a = e.jQuery;
    if (typeof r.Adapter !== 'undefined') {
        throw new Error('History.js Adapter has already been loaded...');
    }
    ;
    r.Adapter = {bind: function (e, t, r) {
        a(e).bind(t, r)
    }, trigger: function (e, t, r) {
        a(e).trigger(t, r)
    }, extractEventData: function (e, r, a) {
        var n = (r && r.originalEvent && r.originalEvent[e]) || (a && a[e]) || t;
        return n
    }, onDomLoad: function (e) {
        a(e)
    }};
    if (typeof r.init !== 'undefined') {
        r.init()
    }
})(window);
(function (t, r) {
    'use strict';
    var a = t.document, o = t.setTimeout || o, i = t.clearTimeout || i, n = t.setInterval || n, e = t.History = t.History || {};
    if (typeof e.initHtml4 !== 'undefined') {
        throw new Error('History.js HTML4 Support has already been loaded...');
    }
    ;
    e.initHtml4 = function () {
        if (typeof e.initHtml4.initialized !== 'undefined') {
            return!1
        } else {
            e.initHtml4.initialized = !0
        }
        ;
        e.enabled = !0;
        e.savedHashes = [];
        e.isLastHash = function (t) {
            var a = e.getHashByIndex(), r;
            r = t === a;
            return r
        };
        e.isHashEqual = function (e, t) {
            e = encodeURIComponent(e).replace(/%25/g, '%');
            t = encodeURIComponent(t).replace(/%25/g, '%');
            return e === t
        };
        e.saveHash = function (t) {
            if (e.isLastHash(t)) {
                return!1
            }
            ;
            e.savedHashes.push(t);
            return!0
        };
        e.getHashByIndex = function (t) {
            var r = null;
            if (typeof t === 'undefined') {
                r = e.savedHashes[e.savedHashes.length - 1]
            } else if (t < 0) {
                r = e.savedHashes[e.savedHashes.length + t]
            } else {
                r = e.savedHashes[t]
            }
            ;
            return r
        };
        e.discardedHashes = {};
        e.discardedStates = {};
        e.discardState = function (t, r, a) {
            var o = e.getHashByState(t), n;
            n = {'discardedState': t, 'backState': a, 'forwardState': r};
            e.discardedStates[o] = n;
            return!0
        };
        e.discardHash = function (t, r, a) {
            var n = {'discardedHash': t, 'backState': a, 'forwardState': r};
            e.discardedHashes[t] = n;
            return!0
        };
        e.discardedState = function (t) {
            var a = e.getHashByState(t), r;
            r = e.discardedStates[a] || !1;
            return r
        };
        e.discardedHash = function (t) {
            var r = e.discardedHashes[t] || !1;
            return r
        };
        e.recycleState = function (t) {
            var r = e.getHashByState(t);
            if (e.discardedState(t)) {
                delete e.discardedStates[r]
            }
            ;
            return!0
        };
        if (e.emulated.hashChange) {
            e.hashChangeInit = function () {
                e.checkerFunction = null;
                var s = '', u, r, i, o, l = Boolean(e.getHash());
                if (e.isInternetExplorer()) {
                    u = 'historyjs-iframe';
                    r = a.createElement('iframe');
                    r.setAttribute('id', u);
                    r.setAttribute('src', '#');
                    r.style.display = 'none';
                    a.body.appendChild(r);
                    r.contentWindow.document.open();
                    r.contentWindow.document.close();
                    i = '';
                    o = !1;
                    e.checkerFunction = function () {
                        if (o) {
                            return!1
                        }
                        ;
                        o = !0;
                        var n = e.getHash(), a = e.getHash(r.contentWindow.document);
                        if (n !== s) {
                            s = n;
                            if (a !== n) {
                                i = a = n;
                                r.contentWindow.document.open();
                                r.contentWindow.document.close();
                                r.contentWindow.document.location.hash = e.escapeHash(n)
                            }
                            ;
                            e.Adapter.trigger(t, 'hashchange')
                        } else if (a !== i) {
                            i = a;
                            if (l && a === '') {
                                e.back()
                            } else {
                                e.setHash(a, !1)
                            }
                        }
                        ;
                        o = !1;
                        return!0
                    }
                } else {
                    e.checkerFunction = function () {
                        var r = e.getHash() || '';
                        if (r !== s) {
                            s = r;
                            e.Adapter.trigger(t, 'hashchange')
                        }
                        ;
                        return!0
                    }
                }
                ;
                e.intervalList.push(n(e.checkerFunction, e.options.hashChangeInterval));
                return!0
            };
            e.Adapter.onDomLoad(e.hashChangeInit)
        }
        ;
        if (e.emulated.pushState) {
            e.onHashChange = function (r) {
                var s = ((r && r.newURL) || e.getLocationHref()), n = e.getHashByUrl(s), a = null, i = null, u = null, o;
                if (e.isLastHash(n)) {
                    e.busy(!1);
                    return!1
                }
                ;
                e.doubleCheckComplete();
                e.saveHash(n);
                if (n && e.isTraditionalAnchor(n)) {
                    e.Adapter.trigger(t, 'anchorchange');
                    e.busy(!1);
                    return!1
                }
                ;
                a = e.extractState(e.getFullUrl(n || e.getLocationHref()), !0);
                if (e.isLastSavedState(a)) {
                    e.busy(!1);
                    return!1
                }
                ;
                i = e.getHashByState(a);
                o = e.discardedState(a);
                if (o) {
                    if (e.getHashByIndex(-2) === e.getHashByState(o.forwardState)) {
                        e.back(!1)
                    } else {
                        e.forward(!1)
                    }
                    ;
                    return!1
                }
                ;
                e.pushState(a.data, a.title, encodeURI(a.url), !1);
                return!0
            };
            e.Adapter.bind(t, 'hashchange', e.onHashChange);
            e.pushState = function (r, a, n, i) {
                n = encodeURI(n).replace(/%25/g, '%');
                if (e.getHashByUrl(n)) {
                    throw new Error('History.js does not support states with fragment-identifiers (hashes/anchors).');
                }
                ;
                if (i !== !1 && e.busy()) {
                    e.pushQueue({scope: e, callback: e.pushState, args: arguments, queue: i});
                    return!1
                }
                ;
                e.busy(!0);
                var o = e.createStateObject(r, a, n), s = e.getHashByState(o), f = e.getState(!1), c = e.getHashByState(f), l = e.getHash(), u = e.expectedStateId == o.id;
                e.storeState(o);
                e.expectedStateId = o.id;
                e.recycleState(o);
                e.setTitle(o);
                if (s === c) {
                    e.busy(!1);
                    return!1
                }
                ;
                e.saveState(o);
                if (!u)e.Adapter.trigger(t, 'statechange');
                if (!e.isHashEqual(s, l) && !e.isHashEqual(s, e.getShortUrl(e.getLocationHref()))) {
                    e.setHash(s, !1)
                }
                ;
                e.busy(!1);
                return!0
            };
            e.replaceState = function (r, a, n, i) {
                n = encodeURI(n).replace(/%25/g, '%');
                if (e.getHashByUrl(n)) {
                    throw new Error('History.js does not support states with fragment-identifiers (hashes/anchors).');
                }
                ;
                if (i !== !1 && e.busy()) {
                    e.pushQueue({scope: e, callback: e.replaceState, args: arguments, queue: i});
                    return!1
                }
                ;
                e.busy(!0);
                var o = e.createStateObject(r, a, n), f = e.getHashByState(o), s = e.getState(!1), l = e.getHashByState(s), u = e.getStateByIndex(-2);
                e.discardState(s, o, u);
                if (f === l) {
                    e.storeState(o);
                    e.expectedStateId = o.id;
                    e.recycleState(o);
                    e.setTitle(o);
                    e.saveState(o);
                    e.Adapter.trigger(t, 'statechange');
                    e.busy(!1)
                } else {
                    e.pushState(o.data, o.title, o.url, !1)
                }
                ;
                return!0
            }
        }
        ;
        if (e.emulated.pushState) {
            if (e.getHash() && !e.emulated.hashChange) {
                e.Adapter.onDomLoad(function () {
                    e.Adapter.trigger(t, 'hashchange')
                })
            }
        }
    };
    if (typeof e.init !== 'undefined') {
        e.init()
    }
})(window);
(function (t, r) {
    'use strict';
    var s = t.console || r, n = t.document, i = t.navigator, o = !1, f = t.setTimeout, c = t.clearTimeout, d = t.setInterval, p = t.clearInterval, a = t.JSON, h = t.alert, e = t.History = t.History || {}, l = t.history;
    try {
        o = t.sessionStorage;
        o.setItem('TEST', '1');
        o.removeItem('TEST')
    } catch (u) {
        o = !1
    }
    ;
    a.stringify = a.stringify || a.encode;
    a.parse = a.parse || a.decode;
    if (typeof e.init !== 'undefined') {
        throw new Error('History.js Core has already been loaded...');
    }
    ;
    e.init = function (t) {
        if (typeof e.Adapter === 'undefined') {
            return!1
        }
        ;
        if (typeof e.initCore !== 'undefined') {
            e.initCore()
        }
        ;
        if (typeof e.initHtml4 !== 'undefined') {
            e.initHtml4()
        }
        ;
        return!0
    };
    e.initCore = function (u) {
        if (typeof e.initCore.initialized !== 'undefined') {
            return!1
        } else {
            e.initCore.initialized = !0
        }
        ;
        e.options = e.options || {};
        e.options.hashChangeInterval = e.options.hashChangeInterval || 100;
        e.options.safariPollInterval = e.options.safariPollInterval || 500;
        e.options.doubleCheckInterval = e.options.doubleCheckInterval || 500;
        e.options.disableSuid = e.options.disableSuid || !1;
        e.options.storeInterval = e.options.storeInterval || 1000;
        e.options.busyDelay = e.options.busyDelay || 250;
        e.options.debug = e.options.debug || !1;
        e.options.initialTitle = e.options.initialTitle || n.title;
        e.options.html4Mode = e.options.html4Mode || !1;
        e.options.delayInit = e.options.delayInit || !1;
        e.intervalList = [];
        e.clearAllIntervals = function () {
            var r, t = e.intervalList;
            if (typeof t !== 'undefined' && t !== null) {
                for (r = 0; r < t.length; r++) {
                    p(t[r])
                }
                ;
                e.intervalList = null
            }
        };
        e.debug = function () {
            if ((e.options.debug || !1)) {
                e.log.apply(e, arguments)
            }
        };
        e.log = function () {
            var l = !(typeof s === 'undefined' || typeof s.log === 'undefined' || typeof s.log.apply === 'undefined'), r = n.getElementById('log'), e, u, f, i, o;
            if (l) {
                i = Array.prototype.slice.call(arguments);
                e = i.shift();
                if (typeof s.debug !== 'undefined') {
                    s.debug.apply(s, [e, i])
                } else {
                    s.log.apply(s, [e, i])
                }
            } else {
                e = ('\n' + arguments[0] + '\n')
            }
            ;
            for (u = 1, f = arguments.length; u < f; ++u) {
                o = arguments[u];
                if (typeof o === 'object' && typeof a !== 'undefined') {
                    try {
                        o = a.stringify(o)
                    } catch (t) {
                    }
                }
                ;
                e += '\n' + o + '\n'
            }
            ;
            if (r) {
                r.value += e + '\n-----\n';
                r.scrollTop = r.scrollHeight - r.clientHeight
            } else if (!l) {
                h(e)
            }
            ;
            return!0
        };
        e.getInternetExplorerMajorVersion = function () {
            var t = e.getInternetExplorerMajorVersion.cached = (typeof e.getInternetExplorerMajorVersion.cached !== 'undefined') ? e.getInternetExplorerMajorVersion.cached : (function () {
                var e = 3, t = n.createElement('div'), r = t.getElementsByTagName('i');
                while ((t.innerHTML = '<!--[if gt IE ' + (++e) + ']><i></i><![endif]-->') && r[0]) {
                }
                ;
                return(e > 4) ? e : !1
            })();
            return t
        };
        e.isInternetExplorer = function () {
            var t = e.isInternetExplorer.cached = (typeof e.isInternetExplorer.cached !== 'undefined') ? e.isInternetExplorer.cached : Boolean(e.getInternetExplorerMajorVersion());
            return t
        };
        if (e.options.html4Mode) {
            e.emulated = {pushState: !0, hashChange: !0}
        } else {
            e.emulated = {pushState: !Boolean(t.history && t.history.pushState && t.history.replaceState && !((/ Mobile\/([1-7][a-z]|(8([abcde]|f(1[0-8]))))/i).test(i.userAgent) || (/AppleWebKit\/5([0-2]|3[0-2])/i).test(i.userAgent))), hashChange: Boolean(!(('onhashchange' in t) || ('onhashchange' in n)) || (e.isInternetExplorer() && e.getInternetExplorerMajorVersion() < 8))}
        }
        ;
        e.enabled = !e.emulated.pushState;
        e.bugs = {setHash: Boolean(!e.emulated.pushState && i.vendor === 'Apple Computer, Inc.' && /AppleWebKit\/5([0-2]|3[0-3])/.test(i.userAgent)), safariPoll: Boolean(!e.emulated.pushState && i.vendor === 'Apple Computer, Inc.' && /AppleWebKit\/5([0-2]|3[0-3])/.test(i.userAgent)), ieDoubleCheck: Boolean(e.isInternetExplorer() && e.getInternetExplorerMajorVersion() < 8), hashEscape: Boolean(e.isInternetExplorer() && e.getInternetExplorerMajorVersion() < 7)};
        e.isEmptyObject = function (e) {
            for (var t in e) {
                if (e.hasOwnProperty(t)) {
                    return!1
                }
            }
            ;
            return!0
        };
        e.cloneObject = function (e) {
            var r, t;
            if (e) {
                r = a.stringify(e);
                t = a.parse(r)
            } else {
                t = {}
            }
            ;
            return t
        };
        e.getRootUrl = function () {
            var e = n.location.protocol + '//' + (n.location.hostname || n.location.host);
            if (n.location.port || !1) {
                e += ':' + n.location.port
            }
            ;
            e += '/';
            return e
        };
        e.getBaseHref = function () {
            var r = n.getElementsByTagName('base'), t = null, e = '';
            if (r.length === 1) {
                t = r[0];
                e = t.href.replace(/[^\/]+$/, '')
            }
            ;
            e = e.replace(/\/+$/, '');
            if (e)e += '/';
            return e
        };
        e.getBaseUrl = function () {
            var t = e.getBaseHref() || e.getBasePageUrl() || e.getRootUrl();
            return t
        };
        e.getPageUrl = function () {
            var a = e.getState(!1, !1), r = (a || {}).url || e.getLocationHref(), t;
            t = r.replace(/\/+$/, '').replace(/[^\/]+$/, function (e, t, r) {
                return(/\./).test(e) ? e : e + '/'
            });
            return t
        };
        e.getBasePageUrl = function () {
            var t = (e.getLocationHref()).replace(/[#\?].*/, '').replace(/[^\/]+$/,function (e, t, r) {
                return(/[^\/]$/).test(e) ? '' : e
            }).replace(/\/+$/, '') + '/';
            return t
        };
        e.getFullUrl = function (t, r) {
            var a = t, n = t.substring(0, 1);
            r = (typeof r === 'undefined') ? !0 : r;
            if (/[a-z]+\:\/\//.test(t)) {
            } else if (n === '/') {
                a = e.getRootUrl() + t.replace(/^\/+/, '')
            } else if (n === '#') {
                a = e.getPageUrl().replace(/#.*/, '') + t
            } else if (n === '?') {
                a = e.getPageUrl().replace(/[\?#].*/, '') + t
            } else {
                if (r) {
                    a = e.getBaseUrl() + t.replace(/^(\.\/)+/, '')
                } else {
                    a = e.getBasePageUrl() + t.replace(/^(\.\/)+/, '')
                }
            }
            ;
            return a.replace(/\#$/, '')
        };
        e.getShortUrl = function (t) {
            var r = t, n = e.getBaseUrl(), a = e.getRootUrl();
            if (e.emulated.pushState) {
            }
            ;
            r = r.replace(a, '/');
            if (e.isTraditionalAnchor(r)) {
                r = './' + r
            }
            ;
            r = r.replace(/^(\.\/)+/g, './').replace(/\#$/, '');
            return r
        };
        e.getLocationHref = function (e) {
            e = e || n;
            if (e.URL === e.location.href)return e.location.href;
            if (e.location.href === decodeURIComponent(e.URL))return e.URL;
            if (e.location.hash && decodeURIComponent(e.location.href.replace(/^[^#]+/, '')) === e.location.hash)return e.location.href;
            if (e.URL.indexOf('#') == -1 && e.location.href.indexOf('#') != -1)return e.location.href;
            return e.URL || e.location.href
        };
        e.store = {};
        e.idToState = e.idToState || {};
        e.stateToId = e.stateToId || {};
        e.urlToId = e.urlToId || {};
        e.storedStates = e.storedStates || [];
        e.savedStates = e.savedStates || [];
        e.normalizeStore = function () {
            e.store.idToState = e.store.idToState || {};
            e.store.urlToId = e.store.urlToId || {};
            e.store.stateToId = e.store.stateToId || {}
        };
        e.getState = function (t, r) {
            if (typeof t === 'undefined') {
                t = !0
            }
            ;
            if (typeof r === 'undefined') {
                r = !0
            }
            ;
            var a = e.getLastSavedState();
            if (!a && r) {
                a = e.createStateObject()
            }
            ;
            if (t) {
                a = e.cloneObject(a);
                a.url = a.cleanUrl || a.url
            }
            ;
            return a
        };
        e.getIdByState = function (t) {
            var r = e.extractId(t.url), a;
            if (!r) {
                a = e.getStateString(t);
                if (typeof e.stateToId[a] !== 'undefined') {
                    r = e.stateToId[a]
                } else if (typeof e.store.stateToId[a] !== 'undefined') {
                    r = e.store.stateToId[a]
                } else {
                    while (!0) {
                        r = (new Date()).getTime() + String(Math.random()).replace(/\D/g, '');
                        if (typeof e.idToState[r] === 'undefined' && typeof e.store.idToState[r] === 'undefined') {
                            break
                        }
                    }
                    ;
                    e.stateToId[a] = r;
                    e.idToState[r] = t
                }
            }
            ;
            return r
        };
        e.normalizeState = function (t) {
            var r, a;
            if (!t || (typeof t !== 'object')) {
                t = {}
            }
            ;
            if (typeof t.normalized !== 'undefined') {
                return t
            }
            ;
            if (!t.data || (typeof t.data !== 'object')) {
                t.data = {}
            }
            ;
            r = {};
            r.normalized = !0;
            r.title = t.title || '';
            r.url = e.getFullUrl(t.url ? t.url : (e.getLocationHref()));
            r.hash = e.getShortUrl(r.url);
            r.data = e.cloneObject(t.data);
            r.id = e.getIdByState(r);
            r.cleanUrl = r.url.replace(/\??\&_suid.*/, '');
            r.url = r.cleanUrl;
            a = !e.isEmptyObject(r.data);
            if ((r.title || a) && e.options.disableSuid !== !0) {
                r.hash = e.getShortUrl(r.url).replace(/\??\&_suid.*/, '');
                if (!/\?/.test(r.hash)) {
                    r.hash += '?'
                }
                ;
                r.hash += '&_suid=' + r.id
            }
            ;
            r.hashedUrl = e.getFullUrl(r.hash);
            if ((e.emulated.pushState || e.bugs.safariPoll) && e.hasUrlDuplicate(r)) {
                r.url = r.hashedUrl
            }
            ;
            return r
        };
        e.createStateObject = function (t, r, a) {
            var n = {'data': t, 'title': r, 'url': a};
            n = e.normalizeState(n);
            return n
        };
        e.getStateById = function (t) {
            t = String(t);
            var a = e.idToState[t] || e.store.idToState[t] || r;
            return a
        };
        e.getStateString = function (t) {
            var n, o, r;
            n = e.normalizeState(t);
            o = {data: n.data, title: t.title, url: t.url};
            r = a.stringify(o);
            return r
        };
        e.getStateId = function (t) {
            var a, r;
            a = e.normalizeState(t);
            r = a.id;
            return r
        };
        e.getHashByState = function (t) {
            var a, r;
            a = e.normalizeState(t);
            r = a.hash;
            return r
        };
        e.extractId = function (e) {
            var a, t, n, r;
            if (e.indexOf('#') != -1) {
                r = e.split('#')[0]
            } else {
                r = e
            }
            ;
            t = /(.*)\&_suid=([0-9]+)$/.exec(r);
            n = t ? (t[1] || e) : e;
            a = t ? String(t[2] || '') : '';
            return a || !1
        };
        e.isTraditionalAnchor = function (e) {
            var t = !(/[\/\?\.]/.test(e));
            return t
        };
        e.extractState = function (t, r) {
            var n = null, a, o;
            r = r || !1;
            a = e.extractId(t);
            if (a) {
                n = e.getStateById(a)
            }
            ;
            if (!n) {
                o = e.getFullUrl(t);
                a = e.getIdByUrl(o) || !1;
                if (a) {
                    n = e.getStateById(a)
                }
                ;
                if (!n && r && !e.isTraditionalAnchor(t)) {
                    n = e.createStateObject(null, null, o)
                }
            }
            ;
            return n
        };
        e.getIdByUrl = function (t) {
            var a = e.urlToId[t] || e.store.urlToId[t] || r;
            return a
        };
        e.getLastSavedState = function () {
            return e.savedStates[e.savedStates.length - 1] || r
        };
        e.getLastStoredState = function () {
            return e.storedStates[e.storedStates.length - 1] || r
        };
        e.hasUrlDuplicate = function (t) {
            var a = !1, r;
            r = e.extractState(t.url);
            a = r && r.id !== t.id;
            return a
        };
        e.storeState = function (t) {
            e.urlToId[t.url] = t.id;
            e.storedStates.push(e.cloneObject(t));
            return t
        };
        e.isLastSavedState = function (t) {
            var o = !1, n, a, r;
            if (e.savedStates.length) {
                n = t.id;
                a = e.getLastSavedState();
                r = a.id;
                o = (n === r)
            }
            ;
            return o
        };
        e.saveState = function (t) {
            if (e.isLastSavedState(t)) {
                return!1
            }
            ;
            e.savedStates.push(e.cloneObject(t));
            return!0
        };
        e.getStateByIndex = function (t) {
            var r = null;
            if (typeof t === 'undefined') {
                r = e.savedStates[e.savedStates.length - 1]
            } else if (t < 0) {
                r = e.savedStates[e.savedStates.length + t]
            } else {
                r = e.savedStates[t]
            }
            ;
            return r
        };
        e.getCurrentIndex = function () {
            var t = null;
            if (e.savedStates.length < 1) {
                t = 0
            } else {
                t = e.savedStates.length - 1
            }
            ;
            return t
        };
        e.getHash = function (t) {
            var a = e.getLocationHref(t), r;
            r = e.getHashByUrl(a);
            return r
        };
        e.unescapeHash = function (t) {
            var r = e.normalizeHash(t);
            r = decodeURIComponent(r);
            return r
        };
        e.normalizeHash = function (e) {
            var t = e.replace(/[^#]*#/, '').replace(/#.*/, '');
            return t
        };
        e.setHash = function (t, r) {
            var a, o;
            if (r !== !1 && e.busy()) {
                e.pushQueue({scope: e, callback: e.setHash, args: arguments, queue: r});
                return!1
            }
            ;
            e.busy(!0);
            a = e.extractState(t, !0);
            if (a && !e.emulated.pushState) {
                e.pushState(a.data, a.title, a.url, !1)
            } else if (e.getHash() !== t) {
                if (e.bugs.setHash) {
                    o = e.getPageUrl();
                    e.pushState(null, null, o + '#' + t, !1)
                } else {
                    n.location.hash = t
                }
            }
            ;
            return e
        };
        e.escapeHash = function (r) {
            var a = e.normalizeHash(r);
            a = t.encodeURIComponent(a);
            if (!e.bugs.hashEscape) {
                a = a.replace(/\%21/g, '!').replace(/\%26/g, '&').replace(/\%3D/g, '=').replace(/\%3F/g, '?')
            }
            ;
            return a
        };
        e.getHashByUrl = function (t) {
            var r = String(t).replace(/([^#]*)#?([^#]*)#?(.*)/, '$2');
            r = e.unescapeHash(r);
            return r
        };
        e.setTitle = function (t) {
            var a = t.title, o;
            if (!a) {
                o = e.getStateByIndex(0);
                if (o && o.url === t.url) {
                    a = o.title || e.options.initialTitle
                }
            }
            ;
            try {
                n.getElementsByTagName('title')[0].innerHTML = a.replace('<', '&lt;').replace('>', '&gt;').replace(' & ', ' &amp; ')
            } catch (r) {
            }
            ;
            n.title = a;
            return e
        };
        e.queues = [];
        e.busy = function (t) {
            if (typeof t !== 'undefined') {
                e.busy.flag = t
            } else if (typeof e.busy.flag === 'undefined') {
                e.busy.flag = !1
            }
            ;
            if (!e.busy.flag) {
                c(e.busy.timeout);
                var r = function () {
                    var t, a, n;
                    if (e.busy.flag)return;
                    for (t = e.queues.length - 1; t >= 0; --t) {
                        a = e.queues[t];
                        if (a.length === 0)continue;
                        n = a.shift();
                        e.fireQueueItem(n);
                        e.busy.timeout = f(r, e.options.busyDelay)
                    }
                };
                e.busy.timeout = f(r, e.options.busyDelay)
            }
            ;
            return e.busy.flag
        };
        e.busy.flag = !1;
        e.fireQueueItem = function (t) {
            return t.callback.apply(t.scope || e, t.args || [])
        };
        e.pushQueue = function (t) {
            e.queues[t.queue || 0] = e.queues[t.queue || 0] || [];
            e.queues[t.queue || 0].push(t);
            return e
        };
        e.queue = function (t, r) {
            if (typeof t === 'function') {
                t = {callback: t}
            }
            ;
            if (typeof r !== 'undefined') {
                t.queue = r
            }
            ;
            if (e.busy()) {
                e.pushQueue(t)
            } else {
                e.fireQueueItem(t)
            }
            ;
            return e
        };
        e.clearQueue = function () {
            e.busy.flag = !1;
            e.queues = [];
            return e
        };
        e.stateChanged = !1;
        e.doubleChecker = !1;
        e.doubleCheckComplete = function () {
            e.stateChanged = !0;
            e.doubleCheckClear();
            return e
        };
        e.doubleCheckClear = function () {
            if (e.doubleChecker) {
                c(e.doubleChecker);
                e.doubleChecker = !1
            }
            ;
            return e
        };
        e.doubleCheck = function (t) {
            e.stateChanged = !1;
            e.doubleCheckClear();
            if (e.bugs.ieDoubleCheck) {
                e.doubleChecker = f(function () {
                    e.doubleCheckClear();
                    if (!e.stateChanged) {
                        t()
                    }
                    ;
                    return!0
                }, e.options.doubleCheckInterval)
            }
            ;
            return e
        };
        e.safariStatePoll = function () {
            var a = e.extractState(e.getLocationHref()), r;
            if (!e.isLastSavedState(a)) {
                r = a
            } else {
                return
            }
            ;
            if (!r) {
                r = e.createStateObject()
            }
            ;
            e.Adapter.trigger(t, 'popstate');
            return e
        };
        e.back = function (t) {
            if (t !== !1 && e.busy()) {
                e.pushQueue({scope: e, callback: e.back, args: arguments, queue: t});
                return!1
            }
            ;
            e.busy(!0);
            e.doubleCheck(function () {
                e.back(!1)
            });
            l.go(-1);
            return!0
        };
        e.forward = function (t) {
            if (t !== !1 && e.busy()) {
                e.pushQueue({scope: e, callback: e.forward, args: arguments, queue: t});
                return!1
            }
            ;
            e.busy(!0);
            e.doubleCheck(function () {
                e.forward(!1)
            });
            l.go(1);
            return!0
        };
        e.go = function (t, r) {
            var a;
            if (t > 0) {
                for (a = 1; a <= t; ++a) {
                    e.forward(r)
                }
            } else if (t < 0) {
                for (a = -1; a >= t; --a) {
                    e.back(r)
                }
            } else {
                throw new Error('History.go: History.go requires a positive or negative integer passed.');
            }
            ;
            return e
        };
        if (e.emulated.pushState) {
            var S = function () {
            };
            e.pushState = e.pushState || S;
            e.replaceState = e.replaceState || S
        } else {
            e.onPopState = function (r, a) {
                var i = !1, n = !1, s, o;
                e.doubleCheckComplete();
                s = e.getHash();
                if (s) {
                    o = e.extractState(s || e.getLocationHref(), !0);
                    if (o) {
                        e.replaceState(o.data, o.title, o.url, !1)
                    } else {
                        e.Adapter.trigger(t, 'anchorchange');
                        e.busy(!1)
                    }
                    ;
                    e.expectedStateId = !1;
                    return!1
                }
                ;
                i = e.Adapter.extractEventData('state', r, a) || !1;
                if (i) {
                    n = e.getStateById(i)
                } else if (e.expectedStateId) {
                    n = e.getStateById(e.expectedStateId)
                } else {
                    n = e.extractState(e.getLocationHref())
                }
                ;
                if (!n) {
                    n = e.createStateObject(null, null, e.getLocationHref())
                }
                ;
                e.expectedStateId = !1;
                if (e.isLastSavedState(n)) {
                    e.busy(!1);
                    return!1
                }
                ;
                e.storeState(n);
                e.saveState(n);
                e.setTitle(n);
                e.Adapter.trigger(t, 'statechange');
                e.busy(!1);
                return!0
            };
            e.Adapter.bind(t, 'popstate', e.onPopState);
            e.pushState = function (r, a, n, i) {
                if (e.getHashByUrl(n) && e.emulated.pushState) {
                    throw new Error('History.js does not support states with fragement-identifiers (hashes/anchors).');
                }
                ;
                if (i !== !1 && e.busy()) {
                    e.pushQueue({scope: e, callback: e.pushState, args: arguments, queue: i});
                    return!1
                }
                ;
                e.busy(!0);
                var o = e.createStateObject(r, a, n);
                if (e.isLastSavedState(o)) {
                    e.busy(!1)
                } else {
                    e.storeState(o);
                    e.expectedStateId = o.id;
                    l.pushState(o.id, o.title, o.url);
                    e.Adapter.trigger(t, 'popstate')
                }
                ;
                return!0
            };
            e.replaceState = function (r, a, n, i) {
                if (e.getHashByUrl(n) && e.emulated.pushState) {
                    throw new Error('History.js does not support states with fragement-identifiers (hashes/anchors).');
                }
                ;
                if (i !== !1 && e.busy()) {
                    e.pushQueue({scope: e, callback: e.replaceState, args: arguments, queue: i});
                    return!1
                }
                ;
                e.busy(!0);
                var o = e.createStateObject(r, a, n);
                if (e.isLastSavedState(o)) {
                    e.busy(!1)
                } else {
                    e.storeState(o);
                    e.expectedStateId = o.id;
                    l.replaceState(o.id, o.title, o.url);
                    e.Adapter.trigger(t, 'popstate')
                }
                ;
                return!0
            }
        }
        ;
        if (o) {
            try {
                e.store = a.parse(o.getItem('History.store')) || {}
            } catch (g) {
                e.store = {}
            }
            ;
            e.normalizeStore()
        } else {
            e.store = {};
            e.normalizeStore()
        }
        ;
        e.Adapter.bind(t, 'unload', e.clearAllIntervals);
        e.saveState(e.storeState(e.extractState(e.getLocationHref(), !0)));
        if (o) {
            e.onUnload = function () {
                var t, r, i;
                try {
                    t = a.parse(o.getItem('History.store')) || {}
                } catch (n) {
                    t = {}
                }
                ;
                t.idToState = t.idToState || {};
                t.urlToId = t.urlToId || {};
                t.stateToId = t.stateToId || {};
                for (r in e.idToState) {
                    if (!e.idToState.hasOwnProperty(r)) {
                        continue
                    }
                    ;
                    t.idToState[r] = e.idToState[r]
                }
                ;
                for (r in e.urlToId) {
                    if (!e.urlToId.hasOwnProperty(r)) {
                        continue
                    }
                    ;
                    t.urlToId[r] = e.urlToId[r]
                }
                ;
                for (r in e.stateToId) {
                    if (!e.stateToId.hasOwnProperty(r)) {
                        continue
                    }
                    ;
                    t.stateToId[r] = e.stateToId[r]
                }
                ;
                e.store = t;
                e.normalizeStore();
                i = a.stringify(t);
                try {
                    o.setItem('History.store', i)
                } catch (n) {
                    if (n.code === DOMException.QUOTA_EXCEEDED_ERR) {
                        if (o.length) {
                            o.removeItem('History.store');
                            o.setItem('History.store', i)
                        } else {
                        }
                    } else {
                        throw n;
                    }
                }
            };
            e.intervalList.push(d(e.onUnload, e.options.storeInterval));
            e.Adapter.bind(t, 'beforeunload', e.onUnload);
            e.Adapter.bind(t, 'unload', e.onUnload)
        }
        ;
        if (!e.emulated.pushState) {
            if (e.bugs.safariPoll) {
                e.intervalList.push(d(e.safariStatePoll, e.options.safariPollInterval))
            }
            ;
            if (i.vendor === 'Apple Computer, Inc.' || (i.appCodeName || '') === 'Mozilla') {
                e.Adapter.bind(t, 'hashchange', function () {
                    e.Adapter.trigger(t, 'popstate')
                });
                if (e.getHash()) {
                    e.Adapter.onDomLoad(function () {
                        e.Adapter.trigger(t, 'hashchange')
                    })
                }
            }
        }
    };
    if (!e.options || !e.options.delayInit) {
        e.init()
    }
})(window);
/*!
 * Bootstrap v3.2.0 (http://getbootstrap.com)
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 */
if ('undefined' == typeof jQuery)throw new Error('Bootstrap\'s JavaScript requires jQuery');
+function (t) {
    'use strict';
    function i() {
        var e = document.createElement('bootstrap'), i = {WebkitTransition: 'webkitTransitionEnd', MozTransition: 'transitionend', OTransition: 'oTransitionEnd otransitionend', transition: 'transitionend'};
        for (var t in i)if (void 0 !== e.style[t])return{end: i[t]};
        return!1
    };
    t.fn.emulateTransitionEnd = function (i) {
        var e = !1, o = this;
        t(this).one('bsTransitionEnd', function () {
            e = !0
        });
        var s = function () {
            e || t(o).trigger(t.support.transition.end)
        };
        return setTimeout(s, i), this
    }, t(function () {
        t.support.transition = i(), t.support.transition && (t.event.special.bsTransitionEnd = {bindType: t.support.transition.end, delegateType: t.support.transition.end, handle: function (i) {
            return t(i.target).is(this) ? i.handleObj.handler.apply(this, arguments) : void 0
        }})
    })
}(jQuery), +function (t) {
    'use strict';
    function o(e) {
        return this.each(function () {
            var s = t(this), o = s.data('bs.alert');
            o || s.data('bs.alert', o = new i(this)), 'string' == typeof e && o[e].call(s)
        })
    };
    var e = '[data-dismiss="alert"]', i = function (i) {
        t(i).on('click', e, this.close)
    };
    i.VERSION = '3.2.0', i.prototype.close = function (i) {
        function n() {
            e.detach().trigger('closed.bs.alert').remove()
        };
        var s = t(this), o = s.attr('data-target');
        o || (o = s.attr('href'), o = o && o.replace(/.*(?=#[^\s]*$)/, ''));
        var e = t(o);
        i && i.preventDefault(), e.length || (e = s.hasClass('alert') ? s : s.parent()), e.trigger(i = t.Event('close.bs.alert')), i.isDefaultPrevented() || (e.removeClass('in'), t.support.transition && e.hasClass('fade') ? e.one('bsTransitionEnd', n).emulateTransitionEnd(150) : n())
    };
    var s = t.fn.alert;
    t.fn.alert = o, t.fn.alert.Constructor = i, t.fn.alert.noConflict = function () {
        return t.fn.alert = s, this
    }, t(document).on('click.bs.alert.data-api', e, i.prototype.close)
}(jQuery), +function (t) {
    'use strict';
    function e(e) {
        return this.each(function () {
            var s = t(this), o = s.data('bs.button'), n = 'object' == typeof e && e;
            o || s.data('bs.button', o = new i(this, n)), 'toggle' == e ? o.toggle() : e && o.setState(e)
        })
    };
    var i = function (e, o) {
        this.t$ = t(e), this.options = t.extend({}, i.DEFAULTS, o), this.isLoading = !1
    };
    i.VERSION = '3.2.0', i.DEFAULTS = {loadingText: 'loading...'}, i.prototype.setState = function (i) {
        var o = 'disabled', e = this.t$, n = e.is('input') ? 'val' : 'html', s = e.data();
        i += 'Text', null == s.resetText && e.data('resetText', e[n]()), e[n](null == s[i] ? this.options[i] : s[i]), setTimeout(t.proxy(function () {
            'loadingText' == i ? (this.isLoading = !0, e.addClass(o).attr(o, o)) : this.isLoading && (this.isLoading = !1, e.removeClass(o).removeAttr(o))
        }, this), 0)
    }, i.prototype.toggle = function () {
        var i = !0, e = this.t$.closest('[data-toggle="buttons"]');
        if (e.length) {
            var t = this.t$.find('input');
            'radio' == t.prop('type') && (t.prop('checked') && this.t$.hasClass('active') ? i = !1 : e.find('.active').removeClass('active')), i && t.prop('checked', !this.t$.hasClass('active')).trigger('change')
        }
        ;
        i && this.t$.toggleClass('active')
    };
    var o = t.fn.button;
    t.fn.button = e, t.fn.button.Constructor = i, t.fn.button.noConflict = function () {
        return t.fn.button = o, this
    }, t(document).on('click.bs.button.data-api', '[data-toggle^="button"]', function (i) {
        var o = t(i.target);
        o.hasClass('btn') || (o = o.closest('.btn')), e.call(o, 'toggle'), i.preventDefault()
    })
}(jQuery), +function (t) {
    'use strict';
    function e(e) {
        return this.each(function () {
            var s = t(this), o = s.data('bs.carousel'), n = t.extend({}, i.DEFAULTS, s.data(), 'object' == typeof e && e), r = 'string' == typeof e ? e : n.slide;
            o || s.data('bs.carousel', o = new i(this, n)), 'number' == typeof e ? o.to(e) : r ? o[r]() : n.interval && o.pause().cycle()
        })
    };
    var i = function (i, e) {
        this.t$ = t(i).on('keydown.bs.carousel', t.proxy(this.keydown, this)), this.a$ = this.t$.find('.carousel-indicators'), this.options = e, this.paused = this.sliding = this.interval = this.c$ = this.n$ = null, 'hover' == this.options.pause && this.t$.on('mouseenter.bs.carousel', t.proxy(this.pause, this)).on('mouseleave.bs.carousel', t.proxy(this.cycle, this))
    };
    i.VERSION = '3.2.0', i.DEFAULTS = {interval: 5e3, pause: 'hover', wrap: !0}, i.prototype.keydown = function (t) {
        switch (t.which) {
            case 37:
                this.prev();
                break;
            case 39:
                this.next();
                break;
            default:
                return
        }
        ;
        t.preventDefault()
    }, i.prototype.cycle = function (i) {
        return i || (this.paused = !1), this.interval && clearInterval(this.interval), this.options.interval && !this.paused && (this.interval = setInterval(t.proxy(this.next, this), this.options.interval)), this
    }, i.prototype.getItemIndex = function (t) {
        return this.n$ = t.parent().children('.item'), this.n$.index(t || this.c$)
    }, i.prototype.to = function (i) {
        var o = this, e = this.getItemIndex(this.c$ = this.t$.find('.item.active'));
        return i > this.n$.length - 1 || 0 > i ? void 0 : this.sliding ? this.t$.one('slid.bs.carousel', function () {
            o.to(i)
        }) : e == i ? this.pause().cycle() : this.slide(i > e ? 'next' : 'prev', t(this.n$[i]))
    }, i.prototype.pause = function (i) {
        return i || (this.paused = !0), this.t$.find('.next, .prev').length && t.support.transition && (this.t$.trigger(t.support.transition.end), this.cycle(!0)), this.interval = clearInterval(this.interval), this
    }, i.prototype.next = function () {
        return this.sliding ? void 0 : this.slide('next')
    }, i.prototype.prev = function () {
        return this.sliding ? void 0 : this.slide('prev')
    }, i.prototype.slide = function (i, e) {
        var s = this.t$.find('.item.active'), o = e || s[i](), a = this.interval, n = 'next' == i ? 'left' : 'right', d = 'next' == i ? 'first' : 'last', r = this;
        if (!o.length) {
            if (!this.options.wrap)return;
            o = this.t$.find('.item')[d]()
        }
        ;
        if (o.hasClass('active'))return this.sliding = !1;
        var c = o[0], l = t.Event('slide.bs.carousel', {relatedTarget: c, direction: n});
        if (this.t$.trigger(l), !l.isDefaultPrevented()) {
            if (this.sliding = !0, a && this.pause(), this.a$.length) {
                this.a$.find('.active').removeClass('active');
                var p = t(this.a$.children()[this.getItemIndex(o)]);
                p && p.addClass('active')
            }
            ;
            var h = t.Event('slid.bs.carousel', {relatedTarget: c, direction: n});
            return t.support.transition && this.t$.hasClass('slide') ? (o.addClass(i), o[0].offsetWidth, s.addClass(n), o.addClass(n), s.one('bsTransitionEnd',function () {
                o.removeClass([i, n].join(' ')).addClass('active'), s.removeClass(['active', n].join(' ')), r.sliding = !1, setTimeout(function () {
                    r.t$.trigger(h)
                }, 0)
            }).emulateTransitionEnd(1e3 * s.css('transition-duration').slice(0, -1))) : (s.removeClass('active'), o.addClass('active'), this.sliding = !1, this.t$.trigger(h)), a && this.cycle(), this
        }
    };
    var o = t.fn.carousel;
    t.fn.carousel = e, t.fn.carousel.Constructor = i, t.fn.carousel.noConflict = function () {
        return t.fn.carousel = o, this
    }, t(document).on('click.bs.carousel.data-api', '[data-slide], [data-slide-to]', function (i) {
        var a, s = t(this), o = t(s.attr('data-target') || (a = s.attr('href')) && a.replace(/.*(?=#[^\s]+$)/, ''));
        if (o.hasClass('carousel')) {
            var r = t.extend({}, o.data(), s.data()), n = s.attr('data-slide-to');
            n && (r.interval = !1), e.call(o, r), n && o.data('bs.carousel').to(n), i.preventDefault()
        }
    }), t(window).on('load', function () {
        t('[data-ride="carousel"]').each(function () {
            var i = t(this);
            e.call(i, i.data())
        })
    })
}(jQuery), +function (t) {
    'use strict';
    function e(e) {
        return this.each(function () {
            var s = t(this), o = s.data('bs.collapse'), n = t.extend({}, i.DEFAULTS, s.data(), 'object' == typeof e && e);
            !o && n.toggle && 'show' == e && (e = !e), o || s.data('bs.collapse', o = new i(this, n)), 'string' == typeof e && o[e]()
        })
    };
    var i = function (e, o) {
        this.t$ = t(e), this.options = t.extend({}, i.DEFAULTS, o), this.transitioning = null, this.options.parent && (this.d$ = t(this.options.parent)), this.options.toggle && this.toggle()
    };
    i.VERSION = '3.2.0', i.DEFAULTS = {toggle: !0}, i.prototype.dimension = function () {
        var t = this.t$.hasClass('width');
        return t ? 'width' : 'height'
    }, i.prototype.show = function () {
        if (!this.transitioning && !this.t$.hasClass('in')) {
            var n = t.Event('show.bs.collapse');
            if (this.t$.trigger(n), !n.isDefaultPrevented()) {
                var i = this.d$ && this.d$.find('> .panel > .in');
                if (i && i.length) {
                    var s = i.data('bs.collapse');
                    if (s && s.transitioning)return;
                    e.call(i, 'hide'), s || i.data('bs.collapse', null)
                }
                ;
                var o = this.dimension();
                this.t$.removeClass('collapse').addClass('collapsing')[o](0), this.transitioning = 1;
                var r = function () {
                    this.t$.removeClass('collapsing').addClass('collapse in')[o](''), this.transitioning = 0, this.t$.trigger('shown.bs.collapse')
                };
                if (!t.support.transition)return r.call(this);
                var a = t.camelCase(['scroll', o].join('-'));
                this.t$.one('bsTransitionEnd', t.proxy(r, this)).emulateTransitionEnd(350)[o](this.t$[0][a])
            }
        }
    }, i.prototype.hide = function () {
        if (!this.transitioning && this.t$.hasClass('in')) {
            var e = t.Event('hide.bs.collapse');
            if (this.t$.trigger(e), !e.isDefaultPrevented()) {
                var i = this.dimension();
                this.t$[i](this.t$[i]())[0].offsetHeight, this.t$.addClass('collapsing').removeClass('collapse').removeClass('in'), this.transitioning = 1;
                var o = function () {
                    this.transitioning = 0, this.t$.trigger('hidden.bs.collapse').removeClass('collapsing').addClass('collapse')
                };
                return t.support.transition ? void this.t$[i](0).one('bsTransitionEnd', t.proxy(o, this)).emulateTransitionEnd(350) : o.call(this)
            }
        }
    }, i.prototype.toggle = function () {
        this[this.t$.hasClass('in') ? 'hide' : 'show']()
    };
    var o = t.fn.collapse;
    t.fn.collapse = e, t.fn.collapse.Constructor = i, t.fn.collapse.noConflict = function () {
        return t.fn.collapse = o, this
    }, t(document).on('click.bs.collapse.data-api', '[data-toggle="collapse"]', function (i) {
        var l, o = t(this), p = o.attr('data-target') || i.preventDefault() || (l = o.attr('href')) && l.replace(/.*(?=#[^\s]+$)/, ''), r = t(p), s = r.data('bs.collapse'), h = s ? 'toggle' : o.data(), n = o.attr('data-parent'), a = n && t(n);
        s && s.transitioning || (a && a.find('[data-toggle="collapse"][data-parent="' + n + '"]').not(o).addClass('collapsed'), o[r.hasClass('in') ? 'addClass' : 'removeClass']('collapsed')), e.call(r, h)
    })
}(jQuery), +function (t) {
    'use strict';
    function s(i) {
        i && 3 === i.which || (t(a).remove(), t(e).each(function () {
            var e = o(t(this)), s = {relatedTarget: this};
            e.hasClass('open') && (e.trigger(i = t.Event('hide.bs.dropdown', s)), i.isDefaultPrevented() || e.removeClass('open').trigger('hidden.bs.dropdown', s))
        }))
    };
    function o(i) {
        var e = i.attr('data-target');
        e || (e = i.attr('href'), e = e && /#[A-Za-z]/.test(e) && e.replace(/.*(?=#[^\s]*$)/, ''));
        var o = e && t(e);
        return o && o.length ? o : i.parent()
    };
    function n(e) {
        return this.each(function () {
            var s = t(this), o = s.data('bs.dropdown');
            o || s.data('bs.dropdown', o = new i(this)), 'string' == typeof e && o[e].call(s)
        })
    };
    var a = '.dropdown-backdrop', e = '[data-toggle="dropdown"]', i = function (i) {
        t(i).on('click.bs.dropdown', this.toggle)
    };
    i.VERSION = '3.2.0', i.prototype.toggle = function (i) {
        var n = t(this);
        if (!n.is('.disabled, :disabled')) {
            var e = o(n), a = e.hasClass('open');
            if (s(), !a) {
                'ontouchstart'in document.documentElement && !e.closest('.navbar-nav').length && t('<div class="dropdown-backdrop"/>').insertAfter(t(this)).on('click', s);
                var r = {relatedTarget: this};
                if (e.trigger(i = t.Event('show.bs.dropdown', r)), i.isDefaultPrevented())return;
                n.trigger('focus'), e.toggleClass('open').trigger('shown.bs.dropdown', r)
            }
            ;
            return!1
        }
    }, i.prototype.keydown = function (i) {
        if (/(38|40|27)/.test(i.keyCode)) {
            var r = t(this);
            if (i.preventDefault(), i.stopPropagation(), !r.is('.disabled, :disabled')) {
                var a = o(r), h = a.hasClass('open');
                if (!h || h && 27 == i.keyCode)return 27 == i.which && a.find(e).trigger('focus'), r.trigger('click');
                var l = ' li:not(.divider):visible a', n = a.find('[role="menu"]' + l + ', [role="listbox"]' + l);
                if (n.length) {
                    var s = n.index(n.filter(':focus'));
                    38 == i.keyCode && s > 0 && s--, 40 == i.keyCode && s < n.length - 1 && s++, ~s || (s = 0), n.eq(s).trigger('focus')
                }
            }
        }
    };
    var r = t.fn.dropdown;
    t.fn.dropdown = n, t.fn.dropdown.Constructor = i, t.fn.dropdown.noConflict = function () {
        return t.fn.dropdown = r, this
    }, t(document).on('click.bs.dropdown.data-api', s).on('click.bs.dropdown.data-api', '.dropdown form',function (t) {
        t.stopPropagation()
    }).on('click.bs.dropdown.data-api', e, i.prototype.toggle).on('keydown.bs.dropdown.data-api', e + ', [role="menu"], [role="listbox"]', i.prototype.keydown)
}(jQuery), +function (t) {
    'use strict';
    function e(e, o) {
        return this.each(function () {
            var n = t(this), s = n.data('bs.modal'), r = t.extend({}, i.DEFAULTS, n.data(), 'object' == typeof e && e);
            s || n.data('bs.modal', s = new i(this, r)), 'string' == typeof e ? s[e](o) : r.show && s.show(o)
        })
    };
    var i = function (i, e) {
        this.options = e, this.i$ = t(document.body), this.t$ = t(i), this.e$ = this.isShown = null, this.scrollbarWidth = 0, this.options.remote && this.t$.find('.modal-content').load(this.options.remote, t.proxy(function () {
            this.t$.trigger('loaded.bs.modal')
        }, this))
    };
    i.VERSION = '3.2.0', i.DEFAULTS = {backdrop: !0, keyboard: !0, show: !0}, i.prototype.toggle = function (t) {
        return this.isShown ? this.hide() : this.show(t)
    }, i.prototype.show = function (i) {
        var e = this, o = t.Event('show.bs.modal', {relatedTarget: i});
        this.t$.trigger(o), this.isShown || o.isDefaultPrevented() || (this.isShown = !0, this.checkScrollbar(), this.i$.addClass('modal-open'), this.setScrollbar(), this.escape(), this.t$.on('click.dismiss.bs.modal', '[data-dismiss="modal"]', t.proxy(this.hide, this)), this.backdrop(function () {
            var o = t.support.transition && e.t$.hasClass('fade');
            e.t$.parent().length || e.t$.appendTo(e.i$), e.t$.show().scrollTop(0), o && e.t$[0].offsetWidth, e.t$.addClass('in').attr('aria-hidden', !1), e.enforceFocus();
            var s = t.Event('shown.bs.modal', {relatedTarget: i});
            o ? e.t$.find('.modal-dialog').one('bsTransitionEnd',function () {
                e.t$.trigger('focus').trigger(s)
            }).emulateTransitionEnd(300) : e.t$.trigger('focus').trigger(s)
        }))
    }, i.prototype.hide = function (i) {
        i && i.preventDefault(), i = t.Event('hide.bs.modal'), this.t$.trigger(i), this.isShown && !i.isDefaultPrevented() && (this.isShown = !1, this.i$.removeClass('modal-open'), this.resetScrollbar(), this.escape(), t(document).off('focusin.bs.modal'), this.t$.removeClass('in').attr('aria-hidden', !0).off('click.dismiss.bs.modal'), t.support.transition && this.t$.hasClass('fade') ? this.t$.one('bsTransitionEnd', t.proxy(this.hideModal, this)).emulateTransitionEnd(300) : this.hideModal())
    }, i.prototype.enforceFocus = function () {
        t(document).off('focusin.bs.modal').on('focusin.bs.modal', t.proxy(function (t) {
            this.t$[0] === t.target || this.t$.has(t.target).length || this.t$.trigger('focus')
        }, this))
    }, i.prototype.escape = function () {
        this.isShown && this.options.keyboard ? this.t$.on('keyup.dismiss.bs.modal', t.proxy(function (t) {
            27 == t.which && this.hide()
        }, this)) : this.isShown || this.t$.off('keyup.dismiss.bs.modal')
    }, i.prototype.hideModal = function () {
        var t = this;
        this.t$.hide(), this.backdrop(function () {
            t.t$.trigger('hidden.bs.modal')
        })
    }, i.prototype.removeBackdrop = function () {
        this.e$ && this.e$.remove(), this.e$ = null
    }, i.prototype.backdrop = function (i) {
        var n = this, e = this.t$.hasClass('fade') ? 'fade' : '';
        if (this.isShown && this.options.backdrop) {
            var o = t.support.transition && e;
            if (this.e$ = t('<div class="modal-backdrop ' + e + '" />').appendTo(this.i$), this.t$.on('click.dismiss.bs.modal', t.proxy(function (t) {
                t.target === t.currentTarget && ('static' == this.options.backdrop ? this.t$[0].focus.call(this.t$[0]) : this.hide.call(this))
            }, this)), o && this.e$[0].offsetWidth, this.e$.addClass('in'), !i)return;
            o ? this.e$.one('bsTransitionEnd', i).emulateTransitionEnd(150) : i()
        } else if (!this.isShown && this.e$) {
            this.e$.removeClass('in');
            var s = function () {
                n.removeBackdrop(), i && i()
            };
            t.support.transition && this.t$.hasClass('fade') ? this.e$.one('bsTransitionEnd', s).emulateTransitionEnd(150) : s()
        } else i && i()
    }, i.prototype.checkScrollbar = function () {
        document.body.clientWidth >= window.innerWidth || (this.scrollbarWidth = this.scrollbarWidth || this.measureScrollbar())
    }, i.prototype.setScrollbar = function () {
        var t = parseInt(this.i$.css('padding-right') || 0, 10);
        this.scrollbarWidth && this.i$.css('padding-right', t + this.scrollbarWidth)
    }, i.prototype.resetScrollbar = function () {
        this.i$.css('padding-right', '')
    }, i.prototype.measureScrollbar = function () {
        var t = document.createElement('div');
        t.className = 'modal-scrollbar-measure', this.i$.append(t);
        var i = t.offsetWidth - t.clientWidth;
        return this.i$[0].removeChild(t), i
    };
    var o = t.fn.modal;
    t.fn.modal = e, t.fn.modal.Constructor = i, t.fn.modal.noConflict = function () {
        return t.fn.modal = o, this
    }, t(document).on('click.bs.modal.data-api', '[data-toggle="modal"]', function (i) {
        var o = t(this), n = o.attr('href'), s = t(o.attr('data-target') || n && n.replace(/.*(?=#[^\s]+$)/, '')), r = s.data('bs.modal') ? 'toggle' : t.extend({remote: !/#/.test(n) && n}, s.data(), o.data());
        o.is('a') && i.preventDefault(), s.one('show.bs.modal', function (t) {
            t.isDefaultPrevented() || s.one('hidden.bs.modal', function () {
                o.is(':visible') && o.trigger('focus')
            })
        }), e.call(s, r, this)
    })
}(jQuery), +function (t) {
    'use strict';
    function e(e) {
        return this.each(function () {
            var s = t(this), o = s.data('bs.tooltip'), n = 'object' == typeof e && e;
            (o || 'destroy' != e) && (o || s.data('bs.tooltip', o = new i(this, n)), 'string' == typeof e && o[e]())
        })
    };
    var i = function (t, i) {
        this.type = this.options = this.enabled = this.timeout = this.hoverState = this.t$ = null, this.init('tooltip', t, i)
    };
    i.VERSION = '3.2.0', i.DEFAULTS = {animation: !0, placement: 'top', selector: !1, template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>', trigger: 'hover focus', title: '', delay: 0, html: !1, container: !1, viewport: {selector: 'body', padding: 0}}, i.prototype.init = function (i, e, o) {
        this.enabled = !0, this.type = i, this.t$ = t(e), this.options = this.getOptions(o), this.h$ = this.options.viewport && t(this.options.viewport.selector || this.options.viewport);
        for (var r = this.options.trigger.split(' '), n = r.length; n--;) {
            var s = r[n];
            if ('click' == s)this.t$.on('click.' + this.type, this.options.selector, t.proxy(this.toggle, this)); else if ('manual' != s) {
                var a = 'hover' == s ? 'mouseenter' : 'focusin', l = 'hover' == s ? 'mouseleave' : 'focusout';
                this.t$.on(a + '.' + this.type, this.options.selector, t.proxy(this.enter, this)), this.t$.on(l + '.' + this.type, this.options.selector, t.proxy(this.leave, this))
            }
        }
        ;
        this.options.selector ? this.p$ = t.extend({}, this.options, {trigger: 'manual', selector: ''}) : this.fixTitle()
    }, i.prototype.getDefaults = function () {
        return i.DEFAULTS
    }, i.prototype.getOptions = function (i) {
        return i = t.extend({}, this.getDefaults(), this.t$.data(), i), i.delay && 'number' == typeof i.delay && (i.delay = {show: i.delay, hide: i.delay}), i
    }, i.prototype.getDelegateOptions = function () {
        var i = {}, e = this.getDefaults();
        return this.p$ && t.each(this.p$, function (t, o) {
            e[t] != o && (i[t] = o)
        }), i
    }, i.prototype.enter = function (i) {
        var e = i instanceof this.constructor ? i : t(i.currentTarget).data('bs.' + this.type);
        return e || (e = new this.constructor(i.currentTarget, this.getDelegateOptions()), t(i.currentTarget).data('bs.' + this.type, e)), clearTimeout(e.timeout), e.hoverState = 'in', e.options.delay && e.options.delay.show ? void(e.timeout = setTimeout(function () {
            'in' == e.hoverState && e.show()
        }, e.options.delay.show)) : e.show()
    }, i.prototype.leave = function (i) {
        var e = i instanceof this.constructor ? i : t(i.currentTarget).data('bs.' + this.type);
        return e || (e = new this.constructor(i.currentTarget, this.getDelegateOptions()), t(i.currentTarget).data('bs.' + this.type, e)), clearTimeout(e.timeout), e.hoverState = 'out', e.options.delay && e.options.delay.hide ? void(e.timeout = setTimeout(function () {
            'out' == e.hoverState && e.hide()
        }, e.options.delay.hide)) : e.hide()
    }, i.prototype.show = function () {
        var d = t.Event('show.bs.' + this.type);
        if (this.hasContent() && this.enabled) {
            this.t$.trigger(d);
            var f = t.contains(document.documentElement, this.t$[0]);
            if (d.isDefaultPrevented() || !f)return;
            var n = this, e = this.tip(), c = this.getUID(this.type);
            this.setContent(), e.attr('id', c), this.t$.attr('aria-describedby', c), this.options.animation && e.addClass('fade');
            var i = 'function' == typeof this.options.placement ? this.options.placement.call(this, e[0], this.t$[0]) : this.options.placement, p = /\s?auto?\s?/i, h = p.test(i);
            h && (i = i.replace(p, '') || 'top'), e.detach().css({top: 0, left: 0, display: 'block'}).addClass(i).data('bs.' + this.type, this), this.options.container ? e.appendTo(this.options.container) : e.insertAfter(this.t$);
            var o = this.getPosition(), r = e[0].offsetWidth, a = e[0].offsetHeight;
            if (h) {
                var u = i, g = this.t$.parent(), s = this.getPosition(g);
                i = 'bottom' == i && o.top + o.height + a - s.scroll > s.height ? 'top' : 'top' == i && o.top - s.scroll - a < 0 ? 'bottom' : 'right' == i && o.right + r > s.width ? 'left' : 'left' == i && o.left - r < s.left ? 'right' : i, e.removeClass(u).addClass(i)
            }
            ;
            var v = this.getCalculatedOffset(i, o, r, a);
            this.applyPlacement(v, i);
            var l = function () {
                n.t$.trigger('shown.bs.' + n.type), n.hoverState = null
            };
            t.support.transition && this.o$.hasClass('fade') ? e.one('bsTransitionEnd', l).emulateTransitionEnd(150) : l()
        }
    }, i.prototype.applyPlacement = function (i, e) {
        var o = this.tip(), f = o[0].offsetWidth, l = o[0].offsetHeight, r = parseInt(o.css('margin-top'), 10), a = parseInt(o.css('margin-left'), 10);
        isNaN(r) && (r = 0), isNaN(a) && (a = 0), i.top = i.top + r, i.left = i.left + a, t.offset.setOffset(o[0], t.extend({using: function (t) {
            o.css({top: Math.round(t.top), left: Math.round(t.left)})
        }}, i), 0), o.addClass('in');
        var h = o[0].offsetWidth, n = o[0].offsetHeight;
        'top' == e && n != l && (i.top = i.top + l - n);
        var s = this.getViewportAdjustedDelta(e, i, h, n);
        s.left ? i.left += s.left : i.top += s.top;
        var p = s.left ? 2 * s.left - f + h : 2 * s.top - l + n, c = s.left ? 'left' : 'top', d = s.left ? 'offsetWidth' : 'offsetHeight';
        o.offset(i), this.replaceArrow(p, o[0][d], c)
    }, i.prototype.replaceArrow = function (t, i, e) {
        this.arrow().css(e, t ? 50 * (1 - t / i) + '%' : '')
    }, i.prototype.setContent = function () {
        var t = this.tip(), i = this.getTitle();
        t.find('.tooltip-inner')[this.options.html ? 'html' : 'text'](i), t.removeClass('fade in top bottom left right')
    }, i.prototype.hide = function () {
        function o() {
            'in' != i.hoverState && e.detach(), i.t$.trigger('hidden.bs.' + i.type)
        };
        var i = this, e = this.tip(), s = t.Event('hide.bs.' + this.type);
        return this.t$.removeAttr('aria-describedby'), this.t$.trigger(s), s.isDefaultPrevented() ? void 0 : (e.removeClass('in'), t.support.transition && this.o$.hasClass('fade') ? e.one('bsTransitionEnd', o).emulateTransitionEnd(150) : o(), this.hoverState = null, this)
    }, i.prototype.fixTitle = function () {
        var t = this.t$;
        (t.attr('title') || 'string' != typeof t.attr('data-original-title')) && t.attr('data-original-title', t.attr('title') || '').attr('title', '')
    }, i.prototype.hasContent = function () {
        return this.getTitle()
    }, i.prototype.getPosition = function (i) {
        i = i || this.t$;
        var o = i[0], e = 'BODY' == o.tagName;
        return t.extend({}, 'function' == typeof o.getBoundingClientRect ? o.getBoundingClientRect() : null, {scroll: e ? document.documentElement.scrollTop || document.body.scrollTop : i.scrollTop(), width: e ? t(window).width() : i.outerWidth(), height: e ? t(window).height() : i.outerHeight()}, e ? {top: 0, left: 0} : i.offset())
    }, i.prototype.getCalculatedOffset = function (t, i, e, o) {
        return'bottom' == t ? {top: i.top + i.height, left: i.left + i.width / 2 - e / 2} : 'top' == t ? {top: i.top - o, left: i.left + i.width / 2 - e / 2} : 'left' == t ? {top: i.top + i.height / 2 - o / 2, left: i.left - e} : {top: i.top + i.height / 2 - o / 2, left: i.left + i.width}
    }, i.prototype.getViewportAdjustedDelta = function (t, i, s, l) {
        var o = {top: 0, left: 0};
        if (!this.h$)return o;
        var n = this.options.viewport && this.options.viewport.padding || 0, e = this.getPosition(this.h$);
        if (/right|left/.test(t)) {
            var p = i.top - n - e.scroll, r = i.top + n - e.scroll + l;
            p < e.top ? o.top = e.top - p : r > e.top + e.height && (o.top = e.top + e.height - r)
        } else {
            var h = i.left - n, a = i.left + n + s;
            h < e.left ? o.left = e.left - h : a > e.width && (o.left = e.left + e.width - a)
        }
        ;
        return o
    }, i.prototype.getTitle = function () {
        var e, i = this.t$, t = this.options;
        return e = i.attr('data-original-title') || ('function' == typeof t.title ? t.title.call(i[0]) : t.title)
    }, i.prototype.getUID = function (t) {
        do t += ~~(1e6 * Math.random()); while (document.getElementById(t));
        return t
    }, i.prototype.tip = function () {
        return this.o$ = this.o$ || t(this.options.template)
    }, i.prototype.arrow = function () {
        return this.r$ = this.r$ || this.tip().find('.tooltip-arrow')
    }, i.prototype.validate = function () {
        this.t$[0].parentNode || (this.hide(), this.t$ = null, this.options = null)
    }, i.prototype.enable = function () {
        this.enabled = !0
    }, i.prototype.disable = function () {
        this.enabled = !1
    }, i.prototype.toggleEnabled = function () {
        this.enabled = !this.enabled
    }, i.prototype.toggle = function (i) {
        var e = this;
        i && (e = t(i.currentTarget).data('bs.' + this.type), e || (e = new this.constructor(i.currentTarget, this.getDelegateOptions()), t(i.currentTarget).data('bs.' + this.type, e))), e.tip().hasClass('in') ? e.leave(e) : e.enter(e)
    }, i.prototype.destroy = function () {
        clearTimeout(this.timeout), this.hide().t$.off('.' + this.type).removeData('bs.' + this.type)
    };
    var o = t.fn.tooltip;
    t.fn.tooltip = e, t.fn.tooltip.Constructor = i, t.fn.tooltip.noConflict = function () {
        return t.fn.tooltip = o, this
    }
}(jQuery), +function (t) {
    'use strict';
    function e(e) {
        return this.each(function () {
            var s = t(this), o = s.data('bs.popover'), n = 'object' == typeof e && e;
            (o || 'destroy' != e) && (o || s.data('bs.popover', o = new i(this, n)), 'string' == typeof e && o[e]())
        })
    };
    var i = function (t, i) {
        this.init('popover', t, i)
    };
    if (!t.fn.tooltip)throw new Error('Popover requires tooltip.js');
    i.VERSION = '3.2.0', i.DEFAULTS = t.extend({}, t.fn.tooltip.Constructor.DEFAULTS, {placement: 'right', trigger: 'click', content: '', template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'}), i.prototype = t.extend({}, t.fn.tooltip.Constructor.prototype), i.prototype.constructor = i, i.prototype.getDefaults = function () {
        return i.DEFAULTS
    }, i.prototype.setContent = function () {
        var t = this.tip(), e = this.getTitle(), i = this.getContent();
        t.find('.popover-title')[this.options.html ? 'html' : 'text'](e), t.find('.popover-content').empty()[this.options.html ? 'string' == typeof i ? 'html' : 'append' : 'text'](i), t.removeClass('fade top bottom left right in'), t.find('.popover-title').html() || t.find('.popover-title').hide()
    }, i.prototype.hasContent = function () {
        return this.getTitle() || this.getContent()
    }, i.prototype.getContent = function () {
        var i = this.t$, t = this.options;
        return i.attr('data-content') || ('function' == typeof t.content ? t.content.call(i[0]) : t.content)
    }, i.prototype.arrow = function () {
        return this.r$ = this.r$ || this.tip().find('.arrow')
    }, i.prototype.tip = function () {
        return this.o$ || (this.o$ = t(this.options.template)), this.o$
    };
    var o = t.fn.popover;
    t.fn.popover = e, t.fn.popover.Constructor = i, t.fn.popover.noConflict = function () {
        return t.fn.popover = o, this
    }
}(jQuery), +function (t) {
    'use strict';
    function i(e, o) {
        var s = t.proxy(this.process, this);
        this.i$ = t('body'), this.s$ = t(t(e).is('body') ? window : e), this.options = t.extend({}, i.DEFAULTS, o), this.selector = (this.options.target || '') + ' .nav li > a', this.offsets = [], this.targets = [], this.activeTarget = null, this.scrollHeight = 0, this.s$.on('scroll.bs.scrollspy', s), this.refresh(), this.process()
    };
    function e(e) {
        return this.each(function () {
            var s = t(this), o = s.data('bs.scrollspy'), n = 'object' == typeof e && e;
            o || s.data('bs.scrollspy', o = new i(this, n)), 'string' == typeof e && o[e]()
        })
    };
    i.VERSION = '3.2.0', i.DEFAULTS = {offset: 10}, i.prototype.getScrollHeight = function () {
        return this.s$[0].scrollHeight || Math.max(this.i$[0].scrollHeight, document.documentElement.scrollHeight)
    }, i.prototype.refresh = function () {
        var e = 'offset', i = 0;
        t.isWindow(this.s$[0]) || (e = 'position', i = this.s$.scrollTop()), this.offsets = [], this.targets = [], this.scrollHeight = this.getScrollHeight();
        var o = this;
        this.i$.find(this.selector).map(function () {
            var n = t(this), s = n.data('target') || n.attr('href'), o = /^#./.test(s) && t(s);
            return o && o.length && o.is(':visible') && [
                [o[e]().top + i, s]
            ] || null
        }).sort(function (t, i) {
            return t[0] - i[0]
        }).each(function () {
            o.offsets.push(this[0]), o.targets.push(this[1])
        })
    }, i.prototype.process = function () {
        var t, s = this.s$.scrollTop() + this.options.offset, n = this.getScrollHeight(), r = this.options.offset + n - this.s$.height(), e = this.offsets, i = this.targets, o = this.activeTarget;
        if (this.scrollHeight != n && this.refresh(), s >= r)return o != (t = i[i.length - 1]) && this.activate(t);
        if (o && s <= e[0])return o != (t = i[0]) && this.activate(t);
        for (t = e.length; t--;)o != i[t] && s >= e[t] && (!e[t + 1] || s <= e[t + 1]) && this.activate(i[t])
    }, i.prototype.activate = function (i) {
        this.activeTarget = i, t(this.selector).parentsUntil(this.options.target, '.active').removeClass('active');
        var o = this.selector + '[data-target="' + i + '"],' + this.selector + '[href="' + i + '"]', e = t(o).parents('li').addClass('active');
        e.parent('.dropdown-menu').length && (e = e.closest('li.dropdown').addClass('active')), e.trigger('activate.bs.scrollspy')
    };
    var o = t.fn.scrollspy;
    t.fn.scrollspy = e, t.fn.scrollspy.Constructor = i, t.fn.scrollspy.noConflict = function () {
        return t.fn.scrollspy = o, this
    }, t(window).on('load.bs.scrollspy.data-api', function () {
        t('[data-spy="scroll"]').each(function () {
            var i = t(this);
            e.call(i, i.data())
        })
    })
}(jQuery), +function (t) {
    'use strict';
    function e(e) {
        return this.each(function () {
            var s = t(this), o = s.data('bs.tab');
            o || s.data('bs.tab', o = new i(this)), 'string' == typeof e && o[e]()
        })
    };
    var i = function (i) {
        this.element = t(i)
    };
    i.VERSION = '3.2.0', i.prototype.show = function () {
        var i = this.element, o = i.closest('ul:not(.dropdown-menu)'), e = i.data('target');
        if (e || (e = i.attr('href'), e = e && e.replace(/.*(?=#[^\s]*$)/, '')), !i.parent('li').hasClass('active')) {
            var n = o.find('.active:last a')[0], s = t.Event('show.bs.tab', {relatedTarget: n});
            if (i.trigger(s), !s.isDefaultPrevented()) {
                var r = t(e);
                this.activate(i.closest('li'), o), this.activate(r, r.parent(), function () {
                    i.trigger({type: 'shown.bs.tab', relatedTarget: n})
                })
            }
        }
    }, i.prototype.activate = function (i, e, o) {
        function n() {
            s.removeClass('active').find('> .dropdown-menu > .active').removeClass('active'), i.addClass('active'), r ? (i[0].offsetWidth, i.addClass('in')) : i.removeClass('fade'), i.parent('.dropdown-menu') && i.closest('li.dropdown').addClass('active'), o && o()
        };
        var s = e.find('> .active'), r = o && t.support.transition && s.hasClass('fade');
        r ? s.one('bsTransitionEnd', n).emulateTransitionEnd(150) : n(), s.removeClass('in')
    };
    var o = t.fn.tab;
    t.fn.tab = e, t.fn.tab.Constructor = i, t.fn.tab.noConflict = function () {
        return t.fn.tab = o, this
    }, t(document).on('click.bs.tab.data-api', '[data-toggle="tab"], [data-toggle="pill"]', function (i) {
        i.preventDefault(), e.call(t(this), 'show')
    })
}(jQuery), +function (t) {
    'use strict';
    function e(e) {
        return this.each(function () {
            var s = t(this), o = s.data('bs.affix'), n = 'object' == typeof e && e;
            o || s.data('bs.affix', o = new i(this, n)), 'string' == typeof e && o[e]()
        })
    };
    var i = function (e, o) {
        this.options = t.extend({}, i.DEFAULTS, o), this.l$ = t(this.options.target).on('scroll.bs.affix.data-api', t.proxy(this.checkPosition, this)).on('click.bs.affix.data-api', t.proxy(this.checkPositionWithEventLoop, this)), this.t$ = t(e), this.affixed = this.unpin = this.pinnedOffset = null, this.checkPosition()
    };
    i.VERSION = '3.2.0', i.RESET = 'affix affix-top affix-bottom', i.DEFAULTS = {offset: 0, target: window}, i.prototype.getPinnedOffset = function () {
        if (this.pinnedOffset)return this.pinnedOffset;
        this.t$.removeClass(i.RESET).addClass('affix');
        var e = this.l$.scrollTop(), t = this.t$.offset();
        return this.pinnedOffset = t.top - e
    }, i.prototype.checkPositionWithEventLoop = function () {
        setTimeout(t.proxy(this.checkPosition, this), 1)
    }, i.prototype.checkPosition = function () {
        if (this.t$.is(':visible')) {
            var h = t(document).height(), a = this.l$.scrollTop(), p = this.t$.offset(), e = this.options.offset, n = e.top, s = e.bottom;
            'object' != typeof e && (s = n = e), 'function' == typeof n && (n = e.top(this.t$)), 'function' == typeof s && (s = e.bottom(this.t$));
            var o = null != this.unpin && a + this.unpin <= p.top ? !1 : null != s && p.top + this.t$.height() >= h - s ? 'bottom' : null != n && n >= a ? 'top' : !1;
            if (this.affixed !== o) {
                null != this.unpin && this.t$.css('top', '');
                var r = 'affix' + (o ? '-' + o : ''), l = t.Event(r + '.bs.affix');
                this.t$.trigger(l), l.isDefaultPrevented() || (this.affixed = o, this.unpin = 'bottom' == o ? this.getPinnedOffset() : null, this.t$.removeClass(i.RESET).addClass(r).trigger(t.Event(r.replace('affix', 'affixed'))), 'bottom' == o && this.t$.offset({top: h - this.t$.height() - s}))
            }
        }
    };
    var o = t.fn.affix;
    t.fn.affix = e, t.fn.affix.Constructor = i, t.fn.affix.noConflict = function () {
        return t.fn.affix = o, this
    }, t(window).on('load', function () {
        t('[data-spy="affix"]').each(function () {
            var o = t(this), i = o.data();
            i.offset = i.offset || {}, i.offsetBottom && (i.offset.bottom = i.offsetBottom), i.offsetTop && (i.offset.top = i.offsetTop), e.call(o, i)
        })
    })
}(jQuery);
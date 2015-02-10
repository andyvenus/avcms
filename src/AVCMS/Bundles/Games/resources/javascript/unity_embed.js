var unityObject = {
    javaInstallDone: function(d, a, b) {
        var c = parseInt(d.substring(d.lastIndexOf("_") + 1), 10);
        if (!isNaN(c)) {
            setTimeout(function() {
                UnityObject2.instances[c].javaInstallDoneCallback(d, a, b)
            }, 10)
        }
    }
};
var UnityObject2 = function(K) {
    var af = [],
        i = window,
        aa = document,
        Y = navigator,
        F = null,
        h = [],
        ai = (document.location.protocol == "https:"),
        z = ai ? "https://ssl-webplayer.unity3d.com/" : "http://webplayer.unity3d.com/",
        L = "_unity_triedjava",
        H = a(L),
        r = "_unity_triedclickonce",
        u = a(r),
        ac = false,
        C = [],
        P = false,
        x = null,
        f = null,
        Q = null,
        l = [],
        V = null,
        q = [],
        X = false,
        W = "installed",
        M = "missing",
        b = "broken",
        w = "unsupported",
        D = "ready",
        A = "start",
        G = "error",
        ab = "first",
        B = "java",
        s = "clickonce",
        N = false,
        T = null,
        y = {
            pluginName: "Unity Player",
            pluginMimeType: "application/vnd.unity",
            baseDownloadUrl: z + "download_webplayer-3.x/",
            fullInstall: false,
            autoInstall: false,
            enableJava: true,
            enableJVMPreloading: false,
            enableClickOnce: true,
            enableUnityAnalytics: false,
            enableGoogleAnalytics: true,
            params: {},
            attributes: {},
            referrer: null,
            debugLevel: 0,
            pluginVersionChecker: {
                container: jQuery("body")[0],
                hide: true,
                id: "version-checker"
            }
        };
    y = jQuery.extend(true, y, K);
    if (y.referrer === "") {
        y.referrer = null
    }
    if (ai) {
        y.enableUnityAnalytics = false
    }

    function a(aj) {
        var ak = new RegExp(escape(aj) + "=([^;]+)");
        if (ak.test(aa.cookie + ";")) {
            ak.exec(aa.cookie + ";");
            return RegExp.$1
        }
        return false
    }

    function e(aj, ak) {
        document.cookie = escape(aj) + "=" + escape(ak) + "; path=/"
    }

    function O(ap) {
        var aq = 0,
            al, ao, am, aj, ak;
        if (ap) {
            var an = ap.toLowerCase().match(/^(\d+)(?:\.(\d+)(?:\.(\d+)([dabfr])?(\d+)?)?)?$/);
            if (an && an[1]) {
                al = an[1];
                ao = an[2] ? an[2] : 0;
                am = an[3] ? an[3] : 0;
                aj = an[4] ? an[4] : "r";
                ak = an[5] ? an[5] : 0;
                aq |= ((al / 10) % 10) << 28;
                aq |= (al % 10) << 24;
                aq |= (ao % 10) << 20;
                aq |= (am % 10) << 16;
                aq |= {
                    d: 2 << 12,
                    a: 4 << 12,
                    b: 6 << 12,
                    f: 8 << 12,
                    r: 8 << 12
                }[aj];
                aq |= ((ak / 100) % 10) << 8;
                aq |= ((ak / 10) % 10) << 4;
                aq |= (ak % 10)
            }
        }
        return aq
    }

    function ah(an, aj) {
        var al = y.pluginVersionChecker.container;
        var ak = aa.createElement("object");
        var am = 0;
        if (al && ak) {
            ak.setAttribute("type", y.pluginMimeType);
            ak.setAttribute("id", y.pluginVersionChecker.id);
            if (y.pluginVersionChecker.hide) {
                ak.style.visibility = "hidden"
            }
            al.appendChild(ak);
            (function() {
                if (typeof ak.GetPluginVersion === "undefined") {
                    setTimeout(arguments.callee, 100)
                } else {
                    var ao = {};
                    if (aj) {
                        for (am = 0; am < aj.length; ++am) {
                            ao[aj[am]] = ak.GetUnityVersion(aj[am])
                        }
                    }
                    ao.plugin = ak.GetPluginVersion();
                    al.removeChild(ak);
                    an(ao)
                }
            })()
        } else {
            an(null)
        }
    }

    function c() {
        var aj = "";
        if (t.x64) {
            aj = y.fullInstall ? "UnityWebPlayerFull64.exe" : "UnityWebPlayer64.exe"
        } else {
            aj = y.fullInstall ? "UnityWebPlayerFull.exe" : "UnityWebPlayer.exe"
        } if (y.referrer !== null) {
            aj += "?referrer=" + y.referrer
        }
        return aj
    }

    function ae() {
        var aj = "UnityPlayer.plugin.zip";
        if (y.referrer != null) {
            aj += "?referrer=" + y.referrer
        }
        return aj
    }

    function m() {
        return y.baseDownloadUrl + (t.win ? c() : ae())
    }

    function E(al, ak, am, aj) {
        if (al === M) {
            N = true
        }
        if (jQuery.inArray(al, q) === -1) {
            if (N) {
                j.send(al, ak, am, aj)
            }
            q.push(al)
        }
        V = al
    }
    var t = function() {
        var al = Y.userAgent,
            an = Y.platform;
        var ap = /chrome/i.test(al);
        var ao = false;
        if (/msie/i.test(al)) {
            ao = parseFloat(al.replace(/^.*msie ([0-9]+(\.[0-9]+)?).*$/i, "$1"))
        } else {
            if (/Trident/i.test(al)) {
                ao = parseFloat(al.replace(/^.*rv:([0-9]+(\.[0-9]+)?).*$/i, "$1"))
            }
        }
        var aq = {
            w3: typeof aa.getElementById != "undefined" && typeof aa.getElementsByTagName != "undefined" && typeof aa.createElement != "undefined",
            win: an ? /win/i.test(an) : /win/i.test(al),
            mac: an ? /mac/i.test(an) : /mac/i.test(al),
            ie: ao,
            ff: /firefox/i.test(al),
            op: /opera/i.test(al),
            ch: ap,
            ch_v: /chrome/i.test(al) ? parseFloat(al.replace(/^.*chrome\/(\d+(\.\d+)?).*$/i, "$1")) : false,
            sf: /safari/i.test(al) && !ap,
            wk: /webkit/i.test(al) ? parseFloat(al.replace(/^.*webkit\/(\d+(\.\d+)?).*$/i, "$1")) : false,
            x64: /win64/i.test(al) && /x64/i.test(al),
            moz: /mozilla/i.test(al) ? parseFloat(al.replace(/^.*mozilla\/([0-9]+(\.[0-9]+)?).*$/i, "$1")) : 0,
            mobile: /ipad/i.test(an) || /iphone/i.test(an) || /ipod/i.test(an) || /android/i.test(al) || /windows phone/i.test(al)
        };
        aq.clientBrand = aq.ch ? "ch" : aq.ff ? "ff" : aq.sf ? "sf" : aq.ie ? "ie" : aq.op ? "op" : "??";
        aq.clientPlatform = aq.win ? "win" : aq.mac ? "mac" : "???";
        var ar = aa.getElementsByTagName("script");
        for (var aj = 0; aj < ar.length; ++aj) {
            var am = ar[aj].src.match(/^(.*)3\.0\/uo\/UnityObject2\.js$/i);
            if (am) {
                y.baseDownloadUrl = am[1];
                break
            }
        }

        function ak(av, au) {
            for (var aw = 0; aw < Math.max(av.length, au.length); ++aw) {
                var at = (aw < av.length) && av[aw] ? new Number(av[aw]) : 0;
                var ax = (aw < au.length) && au[aw] ? new Number(au[aw]) : 0;
                if (at < ax) {
                    return -1
                }
                if (at > ax) {
                    return 1
                }
            }
            return 0
        }
        aq.java = function() {
            if (Y.javaEnabled()) {
                var aw = (aq.win && aq.ff);
                var az = false;
                if (aw || az) {
                    if (typeof Y.mimeTypes != "undefined") {
                        var ay = aw ? [1, 6, 0, 12] : [1, 4, 2, 0];
                        for (var av = 0; av < Y.mimeTypes.length; ++av) {
                            if (Y.mimeTypes[av].enabledPlugin) {
                                var at = Y.mimeTypes[av].type.match(/^application\/x-java-applet;(?:jpi-)?version=(\d+)(?:\.(\d+)(?:\.(\d+)(?:_(\d+))?)?)?$/);
                                if (at != null) {
                                    if (ak(ay, at.slice(1)) <= 0) {
                                        return true
                                    }
                                }
                            }
                        }
                    }
                } else {
                    if (aq.win && aq.ie) {
                        if (typeof ActiveXObject != "undefined") {
                            function au(aA) {
                                try {
                                    return new ActiveXObject("JavaWebStart.isInstalled." + aA + ".0") != null
                                } catch (aB) {
                                    return false
                                }
                            }

                            function ax(aA) {
                                try {
                                    return new ActiveXObject("JavaPlugin.160_" + aA) != null
                                } catch (aB) {
                                    return false
                                }
                            }
                            if (au("1.7.0")) {
                                return true
                            }
                            if (aq.ie >= 8) {
                                if (au("1.6.0")) {
                                    for (var av = 12; av <= 50; ++av) {
                                        if (ax(av)) {
                                            if (aq.ie == 9 && aq.moz == 5 && av < 24) {
                                                continue
                                            } else {
                                                return true
                                            }
                                        }
                                    }
                                    return false
                                }
                            } else {
                                return au("1.6.0") || au("1.5.0") || au("1.4.2")
                            }
                        }
                    }
                }
            }
            return false
        }();
        aq.co = function() {
            if (aq.win && aq.ie) {
                var at = al.match(/(\.NET CLR [0-9.]+)|(\.NET[0-9.]+)/g);
                if (at != null) {
                    var ax = [3, 5, 0];
                    for (var aw = 0; aw < at.length; ++aw) {
                        var au = at[aw].match(/[0-9.]{2,}/g)[0].split(".");
                        if (ak(ax, au) <= 0) {
                            return true
                        }
                    }
                }
            }
            return false
        }();
        return aq
    }();
    var j = function() {
        var aj = function() {
            var ar = new Date();
            var aq = Date.UTC(ar.getUTCFullYear(), ar.getUTCMonth(), ar.getUTCDay(), ar.getUTCHours(), ar.getUTCMinutes(), ar.getUTCSeconds(), ar.getUTCMilliseconds());
            return aq.toString(16) + ap().toString(16)
        }();
        var al = 0;
        var ak = window._gaq = (window._gaq || []);
        an();

        function ap() {
            return Math.floor(Math.random() * 2147483647)
        }

        function an() {
            var aw = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
            var at = aa.getElementsByTagName("script");
            var ax = false;
            for (var av = 0; av < at.length; ++av) {
                if (at[av].src && at[av].src.toLowerCase() == aw.toLowerCase()) {
                    ax = true;
                    break
                }
            }
            if (!ax) {
                var au = aa.createElement("script");
                au.type = "text/javascript";
                au.async = true;
                au.src = aw;
                var ar = document.getElementsByTagName("script")[0];
                ar.parentNode.insertBefore(au, ar)
            }
            var aq = (y.debugLevel === 0) ? "UA-16068464-16" : "UA-16068464-17";
            ak.push(["unity._setDomainName", "none"]);
            ak.push(["unity._setAllowLinker", true]);
            ak.push(["unity._setReferrerOverride", " " + this.location.toString()]);
            ak.push(["unity._setAccount", aq]);
            ak.push(["unity._setCustomVar", 1, "Revision", "bd99309c2ad7", 2])
        }

        function am(av, at, aw, ar) {
            if (!y.enableUnityAnalytics) {
                if (ar) {
                    ar()
                }
                return
            }
            var aq = "http://unityanalyticscapture.appspot.com/event?u=" + encodeURIComponent(aj) + "&s=" + encodeURIComponent(al) + "&e=" + encodeURIComponent(av);
            aq += "&v=" + encodeURIComponent("bd99309c2ad7");
            if (y.referrer !== null) {
                aq += "?r=" + y.referrer
            }
            if (at) {
                aq += "&t=" + encodeURIComponent(at)
            }
            if (aw) {
                aq += "&d=" + encodeURIComponent(aw)
            }
            var au = new Image();
            if (ar) {
                au.onload = au.onerror = ar
            }
            au.src = aq
        }

        function ao(at, aq, au, aB) {
            if (!y.enableGoogleAnalytics) {
                if (aB) {
                    aB()
                }
                return
            }
            var ay = "/webplayer/install/" + at;
            var az = "?";
            if (aq) {
                ay += az + "t=" + encodeURIComponent(aq);
                az = "&"
            }
            if (au) {
                ay += az + "d=" + encodeURIComponent(au);
                az = "&"
            }
            if (aB) {
                ak.push(function() {
                    setTimeout(aB, 1000)
                })
            }
            var aw = y.src;
            if (aw.length > 40) {
                aw = aw.replace("http://", "");
                var ar = aw.split("/");
                var aA = ar.shift();
                var av = ar.pop();
                aw = aA + "/../" + av;
                while (aw.length < 40 && ar.length > 0) {
                    var ax = ar.pop();
                    if (aw.length + ax.length + 5 < 40) {
                        av = ax + "/" + av
                    } else {
                        av = "../" + av
                    }
                    aw = aA + "/../" + av
                }
            }
            ak.push(["unity._setCustomVar", 2, "GameURL", aw, 3]);
            ak.push(["unity._setCustomVar", 1, "UnityObjectVersion", "2", 3]);
            if (aq) {
                ak.push(["unity._setCustomVar", 3, "installMethod", aq, 3])
            }
            ak.push(["unity._trackPageview", ay])
        }
        return {
            send: function(au, at, aw, aq) {
                if (y.enableUnityAnalytics || y.enableGoogleAnalytics) {
                    n("Analytics SEND", au, at, aw, aq)
                }++al;
                var av = 2;
                var ar = function() {
                    if (0 == --av) {
                        x = null;
                        window.location = aq
                    }
                };
                if (aw === null || aw === undefined) {
                    aw = ""
                }
                am(au, at, aw, aq ? ar : null);
                ao(au, at, aw, aq ? ar : null)
            }
        }
    }();

    function J(al, am, an) {
        var aj, aq, ao, ap, ak;
        if (t.win && t.ie) {
            aq = "";
            for (aj in al) {
                aq += " " + aj + '="' + al[aj] + '"'
            }
            ao = "";
            for (aj in am) {
                ao += '<param name="' + aj + '" value="' + am[aj] + '" />'
            }
            an.outerHTML = "<object" + aq + ">" + ao + "</object>"
        } else {
            ap = aa.createElement("object");
            for (aj in al) {
                ap.setAttribute(aj, al[aj])
            }
            for (aj in am) {
                ak = aa.createElement("param");
                ak.name = aj;
                ak.value = am[aj];
                ap.appendChild(ak)
            }
            an.parentNode.replaceChild(ap, an)
        }
    }

    function o(aj) {
        if (typeof aj == "undefined") {
            return false
        }
        if (!aj.complete) {
            return false
        }
        if (typeof aj.naturalWidth != "undefined" && aj.naturalWidth == 0) {
            return false
        }
        return true
    }

    function I(am) {
        var ak = false;
        for (var al = 0; al < l.length; al++) {
            if (!l[al]) {
                continue
            }
            var aj = aa.images[l[al]];
            if (!o(aj)) {
                ak = true
            } else {
                l[al] = null
            }
        }
        if (ak) {
            setTimeout(arguments.callee, 100)
        } else {
            setTimeout(function() {
                d(am)
            }, 100)
        }
    }

    function d(am) {
        var ao = aa.getElementById(am);
        if (!ao) {
            ao = aa.createElement("div");
            var aj = aa.body.lastChild;
            aa.body.insertBefore(ao, aj.nextSibling)
        }
        var an = y.baseDownloadUrl + "3.0/jws/";
        var ak = {
            id: am,
            type: "application/x-java-applet",
            code: "JVMPreloader",
            width: 1,
            height: 1,
            name: "JVM Preloader"
        };
        var al = {
            context: am,
            codebase: an,
            classloader_cache: false,
            scriptable: true,
            mayscript: true
        };
        J(ak, al, ao);
        jQuery("#" + am).show()
    }

    function U(ak) {
        H = true;
        e(L, H);
        var am = aa.getElementById(ak);
        var ao = ak + "_applet_" + F;
        C[ao] = {
            attributes: y.attributes,
            params: y.params,
            callback: y.callback,
            broken: y.broken
        };
        var aq = C[ao];
        var an = {
            id: ao,
            type: "application/x-java-applet",
            archive: y.baseDownloadUrl + "3.0/jws/UnityWebPlayer.jar",
            code: "UnityWebPlayer",
            width: 1,
            height: 1,
            name: "Unity Web Player"
        };
        if (t.win && t.ff) {
            an.style = "visibility: hidden;"
        }
        var ap = {
            context: ao,
            jnlp_href: y.baseDownloadUrl + "3.0/jws/UnityWebPlayer.jnlp",
            classloader_cache: false,
            installer: m(),
            image: z + "installation/unitylogo.png",
            centerimage: true,
            boxborder: false,
            scriptable: true,
            mayscript: true
        };
        for (var aj in aq.params) {
            if (aj == "src") {
                continue
            }
            if (aq.params[aj] != Object.prototype[aj]) {
                ap[aj] = aq.params[aj];
                if (aj.toLowerCase() == "logoimage") {
                    ap.image = aq.params[aj]
                } else {
                    if (aj.toLowerCase() == "backgroundcolor") {
                        ap.boxbgcolor = "#" + aq.params[aj]
                    } else {
                        if (aj.toLowerCase() == "bordercolor") {
                            ap.boxborder = true
                        } else {
                            if (aj.toLowerCase() == "textcolor") {
                                ap.boxfgcolor = "#" + aq.params[aj]
                            }
                        }
                    }
                }
            }
        }
        var al = aa.createElement("div");
        am.appendChild(al);
        J(an, ap, al);
        jQuery("#" + ak).show()
    }

    function Z(aj) {
        setTimeout(function() {
            var ak = aa.getElementById(aj);
            if (ak) {
                ak.parentNode.removeChild(ak)
            }
        }, 0)
    }

    function g(an) {
        var ao = C[an],
            am = aa.getElementById(an),
            al;
        if (!am) {
            return
        }
        am.width = ao.attributes.width || 600;
        am.height = ao.attributes.height || 450;
        var ak = am.parentNode;
        var aj = ak.childNodes;
        for (var ap = 0; ap < aj.length; ap++) {
            al = aj[ap];
            if (al.nodeType == 1 && al != am) {
                ak.removeChild(al)
            }
        }
    }

    function k(al, aj, ak) {
        n("_javaInstallDoneCallback", al, aj, ak);
        if (!aj) {
            E(G, B, ak)
        }
    }

    function ag() {
        af.push(arguments);
        if (y.debugLevel > 0 && window.console && window.console.log) {
            console.log(Array.prototype.slice.call(arguments))
        }
    }

    function n() {
        af.push(arguments);
        if (y.debugLevel > 1 && window.console && window.console.log) {
            console.log(Array.prototype.slice.call(arguments))
        }
    }

    function p(aj) {
        if (/^[-+]?[0-9]+$/.test(aj)) {
            aj += "px"
        }
        return aj
    }

    function v(aw, ak) {
        var au = this;
        var am = M;
        var an;
        Y.plugins.refresh();
        if (t.clientBrand === "??" || t.clientPlatform === "???" || t.mobile) {
            am = w
        } else {
            if (t.op && t.mac) {
                am = w;
                an = "OPERA-MAC"
            } else {
                if (typeof Y.plugins != "undefined" && Y.plugins[y.pluginName] && typeof Y.mimeTypes != "undefined" && Y.mimeTypes[y.pluginMimeType] && Y.mimeTypes[y.pluginMimeType].enabledPlugin) {
                    am = W;
                    if (t.sf && /Mac OS X 10_6/.test(Y.appVersion)) {
                        ah(function(ax) {
                            if (!ax || !ax.plugin) {
                                am = b;
                                an = "OSX10.6-SFx64"
                            }
                            aw(am, Q, an, ax)
                        }, ak);
                        return
                    } else {
                        if (t.mac && t.ch) {
                            ah(function(ax) {
                                if (ax && (O(ax.plugin) <= O("2.6.1f3"))) {
                                    am = b;
                                    an = "OSX-CH-U<=2.6.1f3"
                                }
                                aw(am, Q, an, ax)
                            }, ak);
                            return
                        } else {
                            if (ak) {
                                ah(function(ax) {
                                    aw(am, Q, an, ax)
                                }, ak);
                                return
                            }
                        }
                    }
                } else {
                    if (t.ie) {
                        var al = false;
                        try {
                            if (ActiveXObject.prototype != null) {
                                al = true
                            }
                        } catch (ap) {}
                        if (!al) {
                            am = w;
                            an = "ActiveXFailed"
                        } else {
                            am = M;
                            try {
                                var av = new ActiveXObject("UnityWebPlayer.UnityWebPlayer.1");
                                var aj = av.GetPluginVersion();
                                if (ak) {
                                    var aq = {};
                                    for (var at = 0; at < ak.length; ++at) {
                                        aq[ak[at]] = av.GetUnityVersion(ak[at])
                                    }
                                    aq.plugin = aj
                                }
                                am = W;
                                if (aj == "2.5.0f5") {
                                    var ar = /Windows NT \d+\.\d+/.exec(Y.userAgent);
                                    if (ar && ar.length > 0) {
                                        var ao = parseFloat(ar[0].split(" ")[2]);
                                        if (ao >= 6) {
                                            am = b;
                                            an = "WIN-U2.5.0f5"
                                        }
                                    }
                                }
                            } catch (ap) {}
                        }
                    }
                }
            }
        }
        aw(am, Q, an, aq)
    }

    function R(ak, aj) {
        v(function(am, an, ao, al) {
            ak(am, al)
        }, aj)
    }

    function ad(ak, aj) {
        v(function(am, an, ao, al) {
            E(am, an, ao);
            ak(am, al)
        }, aj)
    }
    var S = {
        getLogHistory: function() {
            return af
        },
        getConfig: function() {
            return y
        },
        getPlatformInfo: function() {
            return t
        },
        initPlugin: function(aj, al) {
            y.targetEl = aj;
            y.src = al;
            n("ua:", t);
            var ak = this;
            ad(function(an, am) {
                ak.handlePluginStatus(an, am)
            })
        },
        detectUnity: function(al, aj) {
            var ak = this;
            R(function(an, am) {
                al.call(ak, an, am)
            }, aj)
        },
        handlePluginStatus: function(al, aj) {
            var ak = y.targetEl;
            var an = jQuery(ak);
            switch (al) {
                case W:
                    this.notifyProgress(an);
                    this.embedPlugin(an, y.callback);
                    break;
                case M:
                    this.notifyProgress(an);
                    var am = this;
                    var ao = (y.debugLevel === 0) ? 1000 : 8000;
                    setTimeout(function() {
                        y.targetEl = ak;
                        am.detectUnity(am.handlePluginStatus)
                    }, ao);
                    break;
                case b:
                    this.notifyProgress(an);
                    break;
                case w:
                    this.notifyProgress(an);
                    break
            }
        },
        getPluginURL: function() {
            var aj = "http://unity3d.com/webplayer/";
            if (t.win) {
                aj = y.baseDownloadUrl + c()
            } else {
                if (Y.platform == "MacIntel") {
                    aj = y.baseDownloadUrl + (y.fullInstall ? "webplayer-i386.dmg" : "webplayer-mini.dmg");
                    if (y.referrer !== null) {
                        aj += "?referrer=" + y.referrer
                    }
                } else {
                    if (Y.platform == "MacPPC") {
                        aj = y.baseDownloadUrl + (y.fullInstall ? "webplayer-ppc.dmg" : "webplayer-mini.dmg");
                        if (y.referrer !== null) {
                            aj += "?referrer=" + y.referrer
                        }
                    }
                }
            }
            return aj
        },
        getClickOnceURL: function() {
            return y.baseDownloadUrl + "3.0/co/UnityWebPlayer.application?installer=" + encodeURIComponent(y.baseDownloadUrl + c())
        },
        embedPlugin: function(am, aw) {
            am = jQuery(am).empty();
            var au = y.src;
            var ak = y.width || "100%";
            var ap = y.height || "100%";
            var av = this;
            if (t.win && t.ie) {
                var al = "";
                for (var aj in y.attributes) {
                    if (y.attributes[aj] != Object.prototype[aj]) {
                        if (aj.toLowerCase() == "styleclass") {
                            al += ' class="' + y.attributes[aj] + '"'
                        } else {
                            if (aj.toLowerCase() != "classid") {
                                al += " " + aj + '="' + y.attributes[aj] + '"'
                            }
                        }
                    }
                }
                var ao = "";
                ao += '<param name="src" value="' + au + '" />';
                ao += '<param name="firstFrameCallback" value="UnityObject2.instances[' + F + '].firstFrameCallback();" />';
                for (var aj in y.params) {
                    if (y.params[aj] != Object.prototype[aj]) {
                        if (aj.toLowerCase() != "classid") {
                            ao += '<param name="' + aj + '" value="' + y.params[aj] + '" />'
                        }
                    }
                }
                var ar = '<object classid="clsid:444785F1-DE89-4295-863A-D46C3A781394" style="display: block; width: ' + p(ak) + "; height: " + p(ap) + ';"' + al + ">" + ao + "</object>";
                var aq = jQuery(ar);
                am.append(aq);
                h.push(am.attr("id"));
                T = aq[0]
            } else {
                var an = jQuery("<embed/>").attr({
                    src: au,
                    type: y.pluginMimeType,
                    width: ak,
                    height: ap,
                    firstFrameCallback: "UnityObject2.instances[" + F + "].firstFrameCallback();"
                }).attr(y.attributes).attr(y.params).css({
                    display: "block",
                    width: p(ak),
                    height: p(ap)
                }).appendTo(am);
                T = an[0]
            } if (!t.sf || !t.mac) {
                setTimeout(function() {
                    T.focus()
                }, 100)
            }
            if (aw) {
                aw()
            }
        },
        getBestInstallMethod: function() {
            var aj = "Manual";
            if (t.x64) {
                return aj
            }
            if (y.enableJava && t.java && H === false) {
                aj = "JavaInstall"
            } else {
                if (y.enableClickOnce && t.co && u === false) {
                    aj = "ClickOnceIE"
                }
            }
            return aj
        },
        installPlugin: function(ak) {
            if (ak == null || ak == undefined) {
                ak = this.getBestInstallMethod()
            }
            var aj = null;
            switch (ak) {
                case "JavaInstall":
                    this.doJavaInstall(y.targetEl.id);
                    break;
                case "ClickOnceIE":
                    u = true;
                    e(r, u);
                    var al = jQuery("<iframe src='" + this.getClickOnceURL() + "' style='display:none;' />");
                    jQuery(y.targetEl).append(al);
                    break;
                default:
                case "Manual":
                    var al = jQuery("<iframe src='" + this.getPluginURL() + "' style='display:none;' />");
                    jQuery(y.targetEl).append(al);
                    break
            }
            Q = ak;
            j.send(A, ak, null, null)
        },
        trigger: function(ak, aj) {
            if (aj) {
                n('trigger("' + ak + '")', aj)
            } else {
                n('trigger("' + ak + '")')
            }
            jQuery(document).trigger(ak, aj)
        },
        notifyProgress: function(aj) {
            if (typeof ac !== "undefined" && typeof ac === "function") {
                var ak = {
                    ua: t,
                    pluginStatus: V,
                    bestMethod: null,
                    lastType: Q,
                    targetEl: y.targetEl,
                    unityObj: this
                };
                if (V === M) {
                    ak.bestMethod = this.getBestInstallMethod()
                }
                if (f !== V) {
                    f = V;
                    ac(ak)
                }
            }
        },
        observeProgress: function(aj) {
            ac = aj
        },
        firstFrameCallback: function() {
            n("*** firstFrameCallback (" + F + ") ***");
            V = ab;
            this.notifyProgress();
            if (N === true) {
                j.send(V, Q)
            }
        },
        setPluginStatus: function(al, ak, am, aj) {
            E(al, ak, am, aj)
        },
        doJavaInstall: function(aj) {
            U(aj)
        },
        jvmPreloaded: function(aj) {
            Z(aj)
        },
        appletStarted: function(aj) {
            g(aj)
        },
        javaInstallDoneCallback: function(al, aj, ak) {
            k(al, aj, ak)
        },
        getUnity: function() {
            return T
        }
    };
    F = UnityObject2.instances.length;
    UnityObject2.instances.push(S);
    return S
};
UnityObject2.instances = [];

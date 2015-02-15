avcms = avcms || {};

$(document).ready(function() {
    avcms.event.addEvent('page-modified', avcms.codemirror.initCodeMirror);
});

avcms.codemirror = {
    initCodeMirror: function() {
        if ($('.codemirror').length == 0) {
            return;
        }

        if (typeof CodeMirror != 'function') {
            $.cachedScript(avcms.config.site_url + 'web/resources/CmsFoundation/javascript/codemirror.js').done(function () {
                avcms.codemirror.applyToTextArea();
            });
        }
        else {
            avcms.codemirror.applyToTextArea();
        }
    },

    applyToTextArea: function() {
        var id = $('.codemirror').filter(':visible').attr('id');

        if (id) {
            var re = /(?:\.([^.]+))?$/;

            var ext = re.exec(id)[1];   // "txt"

            var mode;
            if (ext == 'twig') {
                mode = {name: "jinja2", htmlMode: true};
            }
            else if (ext == 'css') {
                mode = {name: "css"};
            }

            var editor = CodeMirror.fromTextArea(document.getElementById(id), {
                lineNumbers: true,
                lineWrapping: true,
                smartIndent: true,
                mode: mode,
                extraKeys: {
                    "Tab": function(cm){
                        cm.replaceSelection("    " , "end");
                    }
                }
            });
        }
        /*var charWidth = editor.defaultCharWidth(), basePadding = 4;
        editor.on("renderLine", function(cm, line, elt) {
            var off = CodeMirror.countColumn(line.text, null, cm.getOption("tabSize")) * charWidth;
            elt.style.textIndent = "-" + off + "px";
            elt.style.paddingLeft = (basePadding + off) + "px";
        });
        editor.refresh();*/
    }
};

var avcms = avcms || {};

/****************************
 AVCMS ADMIN AJAX NAVIGATION
 ***************************/

$(document).ready(function() {
    var body = $('body');

    History.Adapter.bind(window, 'statechange', avcms.nav.pageChange);

    body.on('click', 'a', avcms.nav.goToPage);
    avcms.nav.onPageModified();
    avcms.nav.setDataAjaxUrl($('.ajax-editor'), 'editor');
});

avcms.nav = {
    // Set the page URL
    goToPage: function (url, set_name) {
        if (url === undefined || typeof url != 'string') {
            url = $(this).attr('href').trim();
        }

        if (url.indexOf('admin') <= 0 || $(this).attr('target') === '_blank' || $(this).hasClass('avcms-no-nav')) {
            return true;
        }

        if (url != '#') {
            History.pushState({state:1}, document.title, url);
            return false;
        }
        else if (set_url !== undefined && typeof set_url == 'string') {
            if (url != '#') {
                History.pushState({state:1}, set_name, set_url);
                return false;
            }
        }

        return true;
    },

    // On URL change, get the right page content via AJAX
    pageChange: function(depth) {
        avcms.event.fireEvent('url-change');

        var full_url = avcms.nav.getCurrentUrl();
        var previous_url = avcms.nav.getPreviousUrl();

        var current_attr = full_url.substring(full_url.indexOf("admin/") + 6).split('/');
        var prev_attr = previous_url.substring(previous_url.indexOf("admin/") + 6).split('/');

        var prev_attr_clean = avcms.general.removeAllAfter('?', prev_attr[0]);

        var func_name, ajax_depth;

        if (($.trim(prev_attr_clean) == $.trim(current_attr[0]) && $('.ajax-editor').length > 0) || (typeof(depth) !== 'undefined' && depth === 'editor')) {
            ajax_depth = 'editor';
            func_name = 'append';
        }
        else {
            ajax_depth = 'main';
            func_name = 'html';
        }

        var content_container = $('.ajax-'+ajax_depth);

        var ajax_required = true;

        if (ajax_depth == 'editor') {
            var existing_editor = $('.ajax-'+ajax_depth+'-inner[data-ajax-url="'+full_url+'"]');

            if (existing_editor.length > 0 && existing_editor.find('.always-refresh').length === 0) {
                console.log(existing_editor.length);
                avcms.nav.hideOrRemovePreviousPage(ajax_depth);
                existing_editor.show();
                avcms.nav.setPageTitle(full_url);
                avcms.browser.setBrowserFocus();
                ajax_required = false;
            }
        }

        if (full_url.indexOf('?') < 0) {
            full_url = full_url + '?';
        }

        if (ajax_required === true) {
            avcms.admin.mainLoaderOn();
            $.get(full_url+'&ajax_depth='+ajax_depth, function(data, textStatus, xhr) {
                if (data.indexOf('<head>') > -1) {
                    var newDoc = document.open("text/html", "replace");
                    newDoc.write(data);
                    newDoc.close();
                    return;
                }

                avcms.nav.hideOrRemovePreviousPage(ajax_depth);
                content_container[func_name](data);

                avcms.nav.setDataAjaxUrl(content_container, ajax_depth);

                avcms.nav.setPageTitle(full_url);

                window.scrollTo(0, 0);
                avcms.nav.onPageModified();
                avcms.admin.mainLoaderOff();
            }).fail(function(xhr) {
                alert('Could not load page. Error: '+xhr.status);
                avcms.admin.mainLoaderOff();
                History.back();
            });
        } else {
            avcms.nav.onPageModified();
        }
    },

    hideOrRemovePreviousPage: function(ajax_depth) {
        var current_page = $('.ajax-'+ajax_depth+'-inner').filter(':visible')

        current_page.hide();

        if (ajax_depth == 'editor' && current_page.find('.always-refresh').length > 0) {
            current_page.remove();
        }
    },

    onPageModified: function() {
        avcms.event.fireEvent('page-modified');
    },

    setPageTitle: function(url) {
        var page_title = $('[data-ajax-url="'+url+'"]').find('.ajax_page_title');

        if (page_title.length) {
            if (page_title.html() != '') {
                document.title = page_title.text();
            }
            else {
                document.title = 'Unnamed Page';
            }
        }
        else if (url.indexOf("?") > -1) {
            avcms.nav.setPageTitle(url.split("?")[0])
        }
    },

    setDataAjaxUrl: function(content_container, ajax_depth) {
        var ajax_inner = content_container.find('.ajax-'+ajax_depth+'-inner').last()
        if (ajax_inner.data('ajax-url') == undefined) {
            ajax_inner.attr('data-ajax-url', avcms.nav.getCurrentUrl());
        }
    },

    getCurrentUrl: function(dont_remove_suid) {
        var State = History.getState(),
            states = History.savedStates,
            prevUrlIndex = states.length - 1;

        var hash = states[prevUrlIndex].hash;

        if (!dont_remove_suid) {
            hash = avcms.general.removeParameter(hash, '_suid');
        }

        if (hash.indexOf('=') <= 0)  {
            hash = hash.replace('?', '');
        }

        return hash;
    },

    getPreviousUrl: function() {
        var State = History.getState(),
            states = History.savedStates,
            prevUrlIndex = states.length - 2;

        if (typeof(states[prevUrlIndex]) !== 'undefined') {
            return states[prevUrlIndex].hash;
        }
        else {
            return '';
        }
    },

    refreshSection: function(section, depth) {
        $(section).filter(':visible').remove();

        avcms.nav.pageChange(depth);
    }
}

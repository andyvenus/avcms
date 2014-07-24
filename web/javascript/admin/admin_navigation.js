var avcms = avcms || {};

/***********************
 ADMIN AJAX NAVIGATION
 **********************/

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
            url = $(this).attr('href');
        }

        if (url.indexOf('admin') <= 0) {
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
    pageChange: function() {
        var full_url = avcms.nav.getCurrentUrl();

        var previous_url = avcms.nav.getPreviousUrl();

        var prev_attr = previous_url.split('admin/').pop().split('/');
        var current_attr = full_url.split('admin/').pop().split('/');

        var prev_attr_clean = avcms.fn.removeAllAfter('?', prev_attr[0]);

        var func_name, ajax_depth;

        //console.log('prev: ' + $.trim(prev_attr_clean) + ' cur: '+ $.trim(current_attr[0]) + 'editor length: ' + $('.ajax-editor').length);

        if ($.trim(prev_attr_clean) == $.trim(current_attr[0]) && $('.ajax-editor').length > 0) {
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
            var existing_editor = $('div[data-ajax-url="'+full_url+'"]');

            if (existing_editor.length > 0) {
                $('.ajax-editor-inner').hide();
                existing_editor.show();
                avcms.nav.setPageTitle(full_url);
                avcms.browser.setBrowserFocus();
                ajax_required = false;
            }
        }

        if (full_url.indexOf('?') < 0) {
            var full_url_wqm = full_url + '?';
        }

        if (ajax_required === true) {
            $.get(full_url_wqm+'&ajax_depth='+ajax_depth, function(data) {
                $('.ajax-'+ajax_depth+'-inner').hide();
                content_container[func_name](data);

                avcms.nav.setDataAjaxUrl(content_container, ajax_depth);

                avcms.nav.setPageTitle(full_url);

                window.scrollTo(0, 0);
                avcms.nav.onPageModified();
            });
        }
    },

    onPageModified: function() {
        $("textarea[name=body]").markdown({autofocus:false,savable:false});
        $("select:not(.no_select2)").select2({
            minimumResultsForSearch: 10
        });

        $(".nano").nanoScroller({ iOSNativeScrolling: false });

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
            hash = avcms.fn.removeParameter(hash, '_suid');
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

        return states[prevUrlIndex].hash;
    }
}
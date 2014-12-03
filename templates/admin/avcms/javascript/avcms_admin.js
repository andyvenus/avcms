var avcms = avcms || {};

/**************
 DISPLAY
 *************/

$(document).ready(function() {
    avcms.adminTemplate.verticalDesign();

    window.onresize = function(event) {
        avcms.adminTemplate.verticalDesign();
    };

    avcms.event.addEvent('page-modified', avcms.adminTemplate.verticalDesign);
    avcms.event.addEvent('submit-form-complete', avcms.adminTemplate.formSubmitScroll);

    $('body').on('click', '#menu_toggle, .admin-menu a', avcms.admin.toggleMenu);
});

avcms.adminTemplate = {
    verticalDesign: function() {
        var header_height = $('.admin-header').innerHeight();
        var window_height = $(window).height();
        var dev_bar_height = $('.dev-bar-inner').innerHeight();
        var target_height = window_height - header_height - dev_bar_height;

        var window_width = $(window).width();

        $('.admin-menu').height(target_height);

        var finder = $('.browser-finder');
        if (finder) {
            var finder_top_height = finder.find('.browser-finder-top').height();
            var finder_results = finder.find('.browser-finder-results');

            if (window_width < 992) {
                finder.height('auto');
                finder_results.height('auto');
            }
            else {
                finder.height(target_height);
                finder_results.height(target_height - finder_top_height - 3);
            }

                $(".nano").nanoScroller({ iOSNativeScrolling: false });

            if (finder_results.find('.nano-content').offsetHeight < finder_results.scrollHeight) {
                console.log('something');
            }
        }

        var editor = $('.editor-content, .simple-content');
        if (editor) {
            if (window_width < 992) {
                editor.outerHeight('auto');
            }
            else {
                var editor_header = $('.editor-header, .simple-header').filter(':visible');
                var editor_target;

                if (editor_header) {
                    editor_target = target_height - editor_header.outerHeight();
                }
                else {
                    editor_target = target_height;
                }

                editor.outerHeight(editor_target);
            }
        }
    },

    formSubmitScroll: function(form) {
        form.parent('.editor-content').scrollTop(0);
        window.scrollTo(0,0);
    }
}
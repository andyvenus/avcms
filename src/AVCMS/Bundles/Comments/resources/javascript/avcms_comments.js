avcms = avcms || {};

$(document).ready(function() {
    $(document).scroll(avcms.comments.scrollCheck);
    $('.load-comments-button').click(avcms.comments.loadComments);
});

avcms.comments = {
    page: 0,
    prev_button_html: null,
    loading: false,
    scroll_load_done: false,

    loadComments: function() {
        if (avcms.comments.loading == true) {
            return;
        }

        avcms.comments.loading = true;
        var comments_area = $('.comments-area[data-ajax-url]');

        var button = $('.load-comments-button');
        avcms.comments.prev_button_html = button.html();
        button.html('<img src="'+avcms.config.site_url+'/web/resources/Comments/images/loading.gif" />');

        avcms.comments.page++;

        $.get(comments_area.data('ajax-url')+'?page='+avcms.comments.page, function(data) {
            button.html(avcms.comments.prev_button_html);

            if (data) {
                var comments_area = $('.comments-area');
                comments_area.html(comments_area.html() + data);
                avcms.comments.loading = false;
            }
            else {
                button.hide();
            }
        })
    },

    scrollCheck: function() {
        if (avcms.comments.scroll_load_done == false && avcms.comments.isScrolledIntoView('.comments-area[data-ajax-url]')) {
            avcms.comments.scroll_load_done = true;
            avcms.comments.loadComments();
        }
    },

    isScrolledIntoView: function(elem)  {
        var docViewTop = $(window).scrollTop();
        var docViewBottom = docViewTop + $(window).height();

        var elemTop = $(elem).offset().top;
        var elemBottom = elemTop + $(elem).height();

        return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    }
}
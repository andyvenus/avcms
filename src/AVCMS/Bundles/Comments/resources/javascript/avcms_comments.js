avcms = avcms || {};

$(document).ready(function() {
    if ($('.avcms-comments-area').length > 0) {
        $(document).scroll(avcms.comments.scrollCheck);
        $('.avcms-load-comments').click(avcms.comments.loadComments);

        var body = $('body');

        body.on('click', '.avcms-load-replies-button', avcms.comments.loadComments);
        body.on('click', '.avcms-reply-button', avcms.comments.showReplyTextarea);

        body.on('submit', '[name=new_comment_form]', avcms.comments.submitComment);
        body.on('click', '.avcms-delete-comment', avcms.comments.deleteComment);
    }
});

avcms.comments = {
    page: [],
    prev_button_html: null,
    loading: false,
    scroll_load_done: false,

    loadComments: function() {
        if (avcms.comments.loading == true) {
            return;
        }

        avcms.comments.loading = true;

        var reply_id = $(this).parents('[data-id]').data('id');
        if (!reply_id) {
            reply_id = 0;
        }

        var comments_area;
        var button;
        if (reply_id == 0) {
            comments_area = $('.avcms-comments-area[data-ajax-url]');
            button = comments_area.parent().find('.avcms-load-comments');
        }
        else {
            comments_area = $('.avcms-comment-replies-container[data-reply-id="'+reply_id+'"]').find('.avcms-comment-replies');
            button = $('.avcms-comment[data-id="'+reply_id+'"]').find('.avcms-load-replies-button');
        }

        avcms.comments.prev_button_html = button.html();
        button.html('<img src="'+avcms.config.site_url+'/web/resources/Comments/images/loading.gif" />');

        if (avcms.comments.page[reply_id] === undefined) {
            avcms.comments.page[reply_id] = 0;
        }

        avcms.comments.page[reply_id]++;

        $.get(comments_area.data('ajax-url')+'?page='+avcms.comments.page[reply_id], function(data) {
            button.html(avcms.comments.prev_button_html);

            if (data) {
                comments_area.html(comments_area.html() + data);

                if ($('#avcms-comments-last-page').length === 1) {
                    button.hide();
                }

                avcms.comments.loading = false;
            }
            else {
                button.hide();
                avcms.comments.loading = false;
            }
        })
    },

    scrollCheck: function() {
        if (avcms.comments.scroll_load_done == false && avcms.comments.isScrolledIntoView('.avcms-comments-area[data-ajax-url]')) {
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
    },

    submitComment: function() {
        var form = $(this);

        var reply_id = form.parents('[data-reply-id]').data('reply-id');

        if (!reply_id) {
            reply_id = 0;
        }

        var data = form.serialize();
        data += '&thread='+reply_id;

        $.post(form.attr('action'), data, function(data) {
            if (data.success == false) {
                alert(data.form.errors[0].message);
                return;
            }

            var comments_area;
            if (reply_id === 0) {
                comments_area = $('.avcms-comments-area');
            }
            else {
                comments_area = $('.avcms-comment-replies-container[data-reply-id="'+reply_id+'"]').find('.avcms-comment-replies');
            }

            comments_area.html(data.html + comments_area.html());
            form[0].reset();

            avcms.event.fireEvent('comment-submit-success', [form, data, reply_id]);
        }, 'json');

        return false;
    },

    deleteComment: function() {
        if (confirm('Are you sure you want to delete this comment?')) {
            var button = $(this);
            var id = button.parents('.avcms-comment').data('id');

            $.ajax({
                type: "POST",
                url: avcms.config.site_url+'moderator/comments/delete',
                data: {'ids': id},
                dataType: 'json',
                success: function(data) {
                    if (data.success == 0) {
                        alert('Error: '+data.error);
                    }
                    else {
                        $('.avcms-comment[data-id='+id+'], .avcms-comment-replies-container[data-reply-id='+id+']').remove();
                    }
                }
            })
        }
    },

    showReplyTextarea: function() {
        var comment = $(this).parents('[data-id]').first();
        var container = $('.avcms-comment-replies-container[data-reply-id="' + comment.data('id') + '"').first().find('.avcms-reply-container').first();

        if (!$(this).data('clicked')) {
            container.html($('.new-comment').first().clone());
            $(this).data('clicked', '1');
        }
        else {
            container.html('');
            $(this).removeData('clicked');
        }
    }
};

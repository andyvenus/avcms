/**
 * Created by Andy on 04/01/15.
 */
avcms = avcms || {};

$(document).ready(function() {
    $('body').on('click', '.like-dislike-buttons button', avcms.likeDislike.registerVote);
});

avcms.likeDislike = {
    registerVote: function() {
        console.log('test');
        var vote_val = $(this).data('vote-value');
        var group = $(this).parent('.like-dislike-buttons');

        if ($(this).hasClass('btn-success') || $(this).hasClass('btn-danger')) {
            vote_val = 'unvote';
        }

        group.find('button').removeClass('btn-success').removeClass('btn-danger').addClass('btn-default');

        if (vote_val == 1) {
            $(this).removeClass('btn-default').addClass('btn-success');
        }
        else if (vote_val == 0) {
            $(this).removeClass('btn-default').addClass('btn-danger');
        }

        var content_id = group.data('content-id');
        var content_type = group.data('content-type');

        $.post(avcms.config.site_url+'vote', 'content_id='+content_id+'&content_type='+content_type+'&rating='+vote_val, function(data) {
            group.find('.like-count').text(data.likes);
            group.find('.dislike-count').text(data.dislikes);
        });
    }
};

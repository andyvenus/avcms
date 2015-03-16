avcms = avcms || {};

$(document).ready(function() {
    var body = $('body');
    body.on('click', '.avcms-friend-request-button', avcms.friends.profileButton);
    body.on('click', '.avcms-friend-request-buttons .avcms-accept', avcms.friends.acceptRequest);
    body.on('click', '.avcms-friend-request-buttons .avcms-decline', avcms.friends.declineRequest);
    body.on('click', '.avcms-delete-friend', avcms.friends.removeFriend);
});

avcms.friends = {
    profileButton: function() {
        var button = $(this);

        var action = button.data('action');

        if (action == 'remove' && confirm(avcms.general.trans('Are you sure you want to remove this friend?')) == false) {
            return;
        }

        button.html(avcms.general.loaderImg);
        button.attr('disabled', 'disabled');

        $.post(avcms.config.site_url + 'friends/' + action, {user: $(this).data('id')}, function(data) {
            button.html(data.message);
        });
    },

    acceptRequest: function() {
        var button = $(this);

        button.parents('.avcms-friend-request-buttons').hide();
        var sender_id = button.parents('.avcms-friend-request').data('user-id');

        $.post(avcms.config.site_url + 'friends/accept-request', {user: sender_id}, function(data) {
            $.notify(data.message, {position: "right top", className: 'info'});
        });
    },

    declineRequest: function() {
        var button = $(this);

        button.parents('.avcms-friend-request').hide();
        var sender_id = button.parents('.avcms-friend-request').data('user-id');

        $.post(avcms.config.site_url + 'friends/decline-request', {user: sender_id});
    },

    removeFriend: function() {
        if (confirm(avcms.general.trans('Are you sure you want to remove this friend?'))) {
            var button = $(this);

            button.parents('.avcms-friend').hide();
            var friend = button.parents('.avcms-friend').data('user-id');

            $.post(avcms.config.site_url + 'friends/remove', {user: friend}, function (data) {
                $.notify(data.message, {position: "right top", className: 'info'});
            });
        }
    }
};

avcms = avcms || {};

$(document).ready(function() {
    $('.avcms-delete-messages').click(avcms.messages.deleteMessages);
    $('.avcms-message :checkbox').click(avcms.messages.toggleSelectedOptions);
    $('.avcms-mark-read, .avcms-mark-unread').click(avcms.messages.markMessagesReadUnread);

    avcms.messages.toggleSelectedOptions();
});

avcms.messages = {
    deleteMessages: function() {
        if (!confirm(avcms.general.trans('Are you sure you want to delete these messages?'))) {
            return;
        }

        var selected_ids = avcms.messages.getSelectedMessages();

        $.each(selected_ids, function(index, id) {
            $('.avcms-message[data-id="'+id+'"]').remove();
        });

        avcms.messages.clearSelectedMessages();

        if ($('.avcms-message').length == 0) {
            $('#pmMessage').show();
        }

        $.post(avcms.config.site_url + 'messages/delete', {ids: selected_ids}, avcms.messages.updateUnreadCount);
    },

    markMessagesReadUnread: function() {
        var selected_ids = avcms.messages.getSelectedMessages();

        var set_status = 0;
        if ($(this).hasClass('avcms-mark-read')) {
            set_status = 1;
        }

        $.each(selected_ids, function(index, id) {
            var read_text = $('.avcms-message[data-id="'+id+'"]').find('.avcms-message-read');

            if (set_status == 1) {
                read_text.hide();
            }
            else {
                read_text.show();
            }
        });

        avcms.messages.clearSelectedMessages();

        $.post(avcms.config.site_url + 'messages/toggle-read', {ids: selected_ids, read: set_status}, avcms.messages.updateUnreadCount);
    },

    updateUnreadCount: function(data) {
        if (typeof data.unread !== 'undefined') {
            $('.avcms-unread-message-count').text(data.unread);
        }
    },

    getSelectedMessages: function() {
        var selected_ids = [];
        $('.avcms-message :checkbox:checked').each(function() {
            selected_ids.push($(this).parents('.avcms-message').data('id'));
        });

        return selected_ids;
    },

    toggleSelectedOptions: function() {
        var options = $('.avcms-inbox-buttons');

        if (avcms.messages.getSelectedMessages().length == 0) {
            options.hide();
        }
        else {
            options.show();
        }
    },

    clearSelectedMessages: function() {
        $('.avcms-message :checkbox:checked').attr('checked', false);
        avcms.messages.toggleSelectedOptions();
    }
};

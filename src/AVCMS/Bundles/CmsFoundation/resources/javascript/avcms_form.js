var avcms = avcms || {};

/***********
 FORMS
 ***********/

avcms.form = {
    submitForm: function() {
        var form = $(this);

        var eventResult = avcms.event.fireEvent('submit-form', [form]);
        if (eventResult === false) {
            avcms.event.fireEvent('submit-form-complete', [form]);
            return false;
        }

        var data = $(this).serialize();

        var submit_url;
        if (form.attr('action')) {
            submit_url = form.attr('action');
        }
        else {
            submit_url = document.URL;
        }

        var submit_button = form.find('button[type=submit]');
        var original_submit_text = submit_button.text();
        submit_button.text(avcms.general.trans('Saving'));
        submit_button.attr('disabled','disabled');

        $.ajax({
            type: "POST",
            url: submit_url,
            dataType: 'json',
            data: data,
            success: function(data) {
                form.find('.has-error').removeClass('has-error');

                var messages = $(form).find('.form-messages');
                messages.html('');

                if (data.form.has_errors === true) {
                    for (var id in data.form.errors) {
                        form.find('[name='+data.form['errors'][id]['param']+']').closest('.form-group').addClass('has-error');

                        messages.append('<div class="alert alert-danger animated bounce">'+data.form['errors'][id]['message']+'</div>');
                    }
                }
                else {
                    if (data.redirect) {
                        avcms.nav.goToPage(data.redirect);
                    }

                    if (data.form.success_message != undefined && data.form.success_message != null) {

                        messages.append('<div class="alert alert-success animated bounce">'+data.form.success_message+'</div>');
                    }

                    avcms.event.fireEvent('submit-form-success', [form, data]);
                }

                submit_button.text(original_submit_text);
                submit_button.removeAttr('disabled');

                avcms.event.fireEvent('submit-form-complete', [form, data]);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                var messages = $(form).find('.form-messages');
                messages.html('<div class="alert alert-danger animated bounce">Save Error: '+errorThrown+'</div>');
            }
        });

        return false;
    },

    resetForm: function() {
        $(this).parent('form')[0].reset();

        var id = $(this).parent('form').attr('data-item-id');
        $('.browser-finder-item[data-id="'+id+'"]').removeClass('finder-item-edited');

        $('.header-label-container:visible').html('&nbsp;');
    }
}

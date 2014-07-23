var avcms = avcms || {};

/***********
 FORMS
 ***********/

$(document).ready(function() {
    var body = $('body');

    body.on('submit', 'form', avcms.form.submitForm);
    body.on('click', '.reset-button', avcms.form.resetForm);
});

avcms.form = {
    submitForm: function() {
        var data = $(this).serialize();
        var form = $(this);

        var submit_url;
        if (form.attr('action')) {
            submit_url = form.attr('action');
        }
        else {
            submit_url = document.URL;
        }

        $.ajax({
            type: "POST",
            url: submit_url,
            dataType: 'json',
            data: data,
            success: function(data) {
                form.find('.has-error').removeClass('has-error');

                var messages = $(form).find('.form-errors');
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

                    //messages.append('<div class="alert alert-success animated bounce">Saved</div>');

                    avcms.event.fireEvent('submit-form-success', [form, data]);
                }

                avcms.event.fireEvent('submit-form-complete', [form, data]);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                var messages = $(form).find('.form-errors');
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
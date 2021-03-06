var template_form_field_changed = false;

$(document).ready(function() {
    avcms.event.addEvent('submit-form-success', function(form) {
        if (template_form_field_changed && form.attr('name') == 'avcms_settings') {
            template_form_field_changed = false;
            regenerateAssets()
        }
    });
    $('body').on('change', 'form[name=avcms_settings] [name=template]', function() {
        template_form_field_changed = true;
    });

    $('body').on('click', '.regenerate-assets', regenerateAssets)
});

function regenerateAssets() {
    $.notify(
        "Regenerating Assets",
        { position:"right bottom",
            className: 'info'
        }
    );

    $.get(avcms.config.site_url+'admin/regenerate-assets', function( data ) {

        if (data.success) {
            $.notify(
                "Assets regenerated",
                {
                    position: "right bottom",
                    className: 'success'
                }
            );
        }
        else {
            $.notify(
                data.error,
                {
                    position: "right bottom",
                    className: 'error'
                }
            );
        }

    }, 'json');
}

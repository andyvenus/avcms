avcms = avcms || {};

$(document).ready(function() {
    $('body').on('change', "[name=admin_module_form] [name=template_type]", avcms.modules.refreshModuleTemplateStyles);
});

avcms.modules = {
    refreshModuleTemplateStyles: function()
    {
        var template_type_field = $("[name=admin_module_form] [name=template_type]");

        if (template_type_field.length < 1) {
            return;
        }

        var template_type = template_type_field.val();

        $.get(avcms.config.site_url+'admin/modules/template-styles/'+template_type, {}, function(data){
            var select_data = [];

            var template_field = $("[name=admin_module_form] [name=template]");
            template_field.empty();

            $.each(data, function(key, val) {
                template_field.append('<option value="'+key+'">'+val+'</option>')
            });

        }, 'json')
    }
};
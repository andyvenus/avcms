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
        var position = $("[name=admin_module_form]").data('position-id');

        $.get(avcms.config.site_url+'admin/modules/template-styles/'+position+'/'+template_type, {}, function(data){
            var template_field = $("[name=admin_module_form] [name=template]");
            template_field.empty();

            $.each(data, function(key, val) {
                template_field.append('<option value="'+key+'">'+val+'</option>')
            });

            template_field.select2("val", "0");

        }, 'json')
    }
};
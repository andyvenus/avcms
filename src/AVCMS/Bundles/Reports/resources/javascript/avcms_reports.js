var avcms = avcms || {};

$(document).ready(function() {
    $('body').on('click', '[data-report-button]', avcms.reports.openReportForm);
});

avcms.reports = {
    openReportForm: function() {
        var button = $(this);

        var content_type = button.data('content-type');
        var content_id = button.data('content-id');

        avcms.general.loadFormModal(avcms.config.site_url+'reports/new', 'content_type='+content_type+'&content_id='+content_id);
    }
};
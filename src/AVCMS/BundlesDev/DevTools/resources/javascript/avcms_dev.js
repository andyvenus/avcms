var avcms = avcms || {};

$(document).ready(function() {
    $('select').select2();

    $('select[name=database_table]').change(avcms.dev.loadDatabaseColumns);

    $('body').on('click', '.status-button', avcms.dev.toggleBundleEnabled);
});

$(document).ready(function() {
    if (location.hash !== '') $('a[href="' + location.hash + '"]').tab('show');
    return $('a[data-toggle="tab"]').on('shown', function(e) {
        return location.hash = $(e.target).attr('href').substr(1);
    });
});

avcms.dev = {
    loadDatabaseColumns: function() {
        $.post( document.URL, {database_table: $(this).val()}, function( data ) {
            $( "#database-columns" ).html( data );
            $('select').select2();
        });
    },

    toggleBundleEnabled: function() {
        var enable_bundle;
        if ($(this).hasClass('list-group-item-success')) {
            enable_bundle = 'disable';
            $(this).html('<span class="glyphicon glyphicon-unchecked"></span> Bundle Inactive');
            $('#status').html('Status: <span class="text-warning">Inactive</span></strong>');
        }
        else {
            enable_bundle = 'enable';
            $(this).html('<span class="glyphicon glyphicon-unchecked"></span> Bundle Active');
            $('#status').html('Status: <span class="text-success">Active</span></strong>');
        }

        $('.content-types a').toggleClass('btn-success').toggleClass('btn-warning');
        $('.list-group-item-success, .list-group-item-warning').toggleClass('list-group-item-success').toggleClass('list-group-item-warning');

        $.get( avcms.config.site_url + 'dev/bundles/'+$(this).data('bundle-name')+'/enable?status='+enable_bundle);

        return false;
    }
}
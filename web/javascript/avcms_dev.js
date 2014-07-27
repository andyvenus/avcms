var avcms = avcms || {};

$(document).ready(function() {
    $('select').select2({placeholder: 'test'});

    $('select[name=database_table]').change(avcms.dev.loadDatabaseColumns)
});

avcms.dev = {
    loadDatabaseColumns: function() {
        $.post( document.URL, {database_table: $(this).val()}, function( data ) {
            $( "#database-columns" ).html( data );
        });
    }
}
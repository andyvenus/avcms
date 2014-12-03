var tagsCache = null;

$(document).ready(function() {
    avcms.event.addEvent('page-modified', function() {
        var tags_field = $("[name='tags']");

        if (tags_field.length > 0) {
            tags_field.select2({
                tags: [],
                tokenSeparators: [","],
                width: '100%'
            });

            if (tagsCache === null) {
                $.post(avcms.config.site_url+'tags/suggestions', null, function(data) {
                    tagsCache = data;

                    tags_field.select2({
                        tags: tagsCache,
                        tokenSeparators: [","],
                        width: '100%'
                    });

                }, 'json');
            }
            else {
                tags_field.select2({
                    tags: tagsCache,
                    tokenSeparators: [","],
                    width: '100%'
                });
            }
        }
    });
});
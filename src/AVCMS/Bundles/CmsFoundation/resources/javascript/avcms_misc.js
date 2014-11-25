$(document).ready(function() {
    // AJAX FORMS

    $('body').on('click', '[data-form-modal-url]', function() {
        var url = $(this).data('form-modal-url');
        avcms.general.loadFormModal(url);
    });
    $('body').on('submit', '[data-ajax-form]', avcms.form.submitForm);
    avcms.event.addEvent('submit-form-success', avcms.general.modalFormSuccess);
});


$.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
    if (options.type.toUpperCase() === "POST" && (options.data == undefined || options.data.indexOf("csrf_token") < 1)) {
        if (options.data != '') {
            options.data = options.data + '&';
        }

        var csrf_token = avcms.general.getCookie('av_csrf_token');

        options.data = options.data + '_csrf_token='+encodeURIComponent(csrf_token);
    }
});

avcms.general = {
    getCookie: function(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    },

    loadFormModal: function(url, data) {
        avcms.general.mainLoaderOn();

        $.post(url, data, function(data) {
            avcms.general.mainLoaderOff();
            $('body').append(data.html);
            var modal = $('#formModal');
            modal.modal();

            modal.on('hidden.bs.modal', function () {
                modal.remove();
            })
        }, 'json').fail(function() {
            alert( "Modal response was not JSON" );
        });
    },

    modalFormSuccess: function(form) {
        if (form.parents('.modal').length == 1) {
            $('#formModal').modal('hide');
        }
    },

    mainLoaderOn: function() {
        $('.loader').fadeIn(500);
    },

    mainLoaderOff: function() {
        $('.loader').fadeOut(500);
    },

    trans: function(translation_string) {
        if (avcms.translations[translation_string] == undefined) {
            return translation_string;
        }
        else {
            return avcms.translations[translation_string];
        }
    },

    getUrlParameter: function (sParam)
    {
        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++)
        {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam)
            {
                return sParameterName[1];
            }
        }

        return null;
    }
};
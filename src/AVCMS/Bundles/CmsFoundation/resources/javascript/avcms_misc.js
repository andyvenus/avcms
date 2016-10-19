$(document).ready(function() {
    // AJAX FORMS

    $('body').on('click', '[data-form-modal-url]', function() {
        var url = $(this).data('form-modal-url');
        avcms.general.loadFormModal(url);
    });

    //$('body').on('mouseover', '.avcms-module-position', avcms.general.showModulePositionButton);
    //$('body').on('mouseout', '.avcms-module-position', avcms.general.hideModulePositionButton);

    $('[data-toggle="tooltip"]').tooltip();

    avcms.event.addEvent('submit-form-success', avcms.general.modalFormSuccess);

    window.onresize = avcms.general.onWindowResize;
});


$.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
    if (typeof(options.data) !== 'object' && options.type.toUpperCase() === "POST" && (options.data == undefined || options.data.indexOf("csrf_token") < 1)) {
        if (options.data != '') {
            options.data = options.data + '&';
        }

        var csrf_token = avcms.general.getCookie('av_csrf_token');

        options.data = options.data + '_csrf_token='+encodeURIComponent(csrf_token);
    }
});

avcms.general = {
    loaderImg: '<img src="'+avcms.config.site_url+'web/resources/CmsFoundation/images/loader.gif" />',

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

    onWindowResize: function() {
        avcms.event.fireEvent('window-resize');
    },

    loadFormModal: function(url, data) {
        avcms.general.mainLoaderOn();

        $.post(url, data, function(data) {
            if (data.success == false) {
                alert(data.error);
                return;
            }

            avcms.general.mainLoaderOff();
            $('body').append(data.html);
            var modal = $('#formModal');
            modal.modal();

            modal.on('hidden.bs.modal', function () {
                modal.remove();
            });

            setTimeout(function() {
                modal.find('input:text').first().focus();
            }, 700);

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
        if (avcms.translations == undefined) {
            return translation_string;
        }

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
    },

    removeParameter: function(url, parameter)
    {
        var urlparts= url.split('?');

        if (urlparts.length>=2)
        {
            var urlBase=urlparts.shift(); //get first part, and remove from array
            var queryString=urlparts.join("?"); //join it back up

            var prefix = encodeURIComponent(parameter)+'=';
            var pars = queryString.split(/[&;]/g);
            for (var i= pars.length; i-->0;)               //reverse iteration as may be destructive
                if (pars[i].lastIndexOf(prefix, 0)!==-1)   //idiom for string.startsWith
                    pars.splice(i, 1);
            url = urlBase+'?'+pars.join('&');
        }
        return url;
    },

    removeAllAfter: function(match, str) {
        if (str.indexOf(match) > 0) {
            return str.substring(0, str.indexOf(match));
        }
        else {
            return str;
        }
    },

    isMobile: function () {
        return ( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent));
    },

    showModulePositionButton: function() {
        $(this).find('.avcms-module-position-button').css('opacity', 1);
    },

    hideModulePositionButton: function() {
        $(this).find('.avcms-module-position-button').css('opacity', 0.5);
    }
};

jQuery.cachedScript = function( url, options ) {

    // Allow user to set any option except for dataType, cache, and url
    options = $.extend( options || {}, {
        dataType: "script",
        cache: true,
        url: url
    });

    // Use $.ajax() since it is more flexible than $.getScript
    // Return the jqXHR object so we can chain callbacks
    return jQuery.ajax( options );
};

$.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
    if (options.type.toUpperCase() === "POST" && (options.data == undefined || options.data.indexOf("csrf_token") < 1)) {
        if (options.data != '') {
            options.data = options.data + '&';
        }

        var csrf_token = avcms.general.getCookie('avcms_csrf_token');

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
    }
};
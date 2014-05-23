var avcms = avcms || {};

/***********
 Events
 ************/

avcms.event = new function () {
    this.events = [];

    this.fireEvent = function (event, args) {
        //console.log('Event ' + event + ' fired')
        if (!args) {
            args = [];
        }

        if (this.events[event] === undefined) {
            this.events[event] = [];
        }

        var arrayLength =  this.events[event].length;
        for (var i = 0; i < arrayLength; i++) {
            this.events[event][i].apply(this, args);
        }
    };

    this.addEvent = function (event, fn) {
        if (this.events[event] === undefined) {
            this.events[event] = [];
        }

        this.events[event].push(fn);
    }
};

/****************
 Misc Functions
 ***************/

avcms.fn = {
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
    }
}

/**************
 DISPLAY
 *************/

$(document).ready(function() {
    avcms.adminTemplate.verticalDesign();

    window.onresize = function(event) {
        avcms.adminTemplate.verticalDesign();
    };
});

avcms.adminTemplate = {
    verticalDesign: function() {
        var header_height = $('.admin-header').height();
        var window_height = $(window).height();
        var target_height = window_height - header_height;

        $('.admin-menu').height(target_height);
        var finder = $('.browser-finder');
        if (finder) {
            finder.height(target_height);
            var finder_top_height = finder.find('.browser-finder-top').height();
            var finder_results = finder.find('.browser-finder-results');
            finder_results.height(target_height - finder_top_height - 3);

            $(".nano").nanoScroller({ iOSNativeScrolling: true });

            if (finder_results.find('.nano-content').offsetHeight < finder_results.scrollHeight) {
                console.log('something');
            }
        }
    }
}
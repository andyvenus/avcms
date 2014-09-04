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
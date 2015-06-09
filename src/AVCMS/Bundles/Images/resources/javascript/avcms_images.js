$(document).ready(function() {
    matchImageHeights();

    avcms.event.addEvent('window-resize', function() {
        $('.avcms-level-thumbnails img').each(function() {
            resizeThumbnail($(this), $(this).innerWidth(), $(this).innerHeight());
        });
    });
});

function matchImageHeights() {
    $('.avcms-level-thumbnails img').each(function() {
        var image = $(this);
        getImageSize($(this), function(width, height) {
            resizeThumbnail(image, width, height);
        });
    });
}

function resizeThumbnail(image, width, height) {
    image.css('max-height', '');
    image.css('width', '100%');

    if (width > height && $(window).innerWidth() > 768) {
        var difference = width - height;

        image.css('margin-top', difference / 2);
        image.css('margin-bottom', difference / 2);
    }
    else {
        image.css('margin-top', 0);
        image.css('margin-bottom', 0);

        image.css('width', 'auto');

        width = image.innerWidth();
        image.css('max-height', width);
    }
}

function getImageSize(img, callback){
    img = $(img);

    var wait = setInterval(function(){
        var w = img.width(),
            h = img.height();

        if(w && h){
            done(w, h);
        }
    }, 0);

    var onLoad;
    img.on('load', onLoad = function(){
        done(img.width(), img.height());
    });


    var isDone = false;
    function done(){
        if(isDone){
            return;
        }
        isDone = true;

        clearInterval(wait);
        img.off('load', onLoad);

        callback.apply(this, arguments);
    }
}

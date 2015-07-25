$(document).ready(function() {
    matchImageHeights();

    avcms.event.addEvent('window-resize', matchImageHeights);
});

function matchImageHeights() {
    var sel = $('.avcms-image-square');

    sel.innerHeight(sel.innerWidth());
}

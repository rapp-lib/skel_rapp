window.showFlash = function(flash){
    for (var i in flash) {
        var callback = toastr[flash[i].options.type];
        if ( ! callback) callback = toastr["info"];
        callback(flash[i].message);
    }
};
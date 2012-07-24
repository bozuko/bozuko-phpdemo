jQuery(function($){
    var t, resize = function(){
        clearTimeout(t);
        var h = document.body.offsetHeight;
        $('.ui-datepicker').each(function(){
            // console.log(document.body.offsetHeight, $(this).offset().top, $(this).width());
            h = Math.max(h, $(this).offset().top + $(this).height());
        });
        window.postMessage({height: h},'*');
        t = setTimeout(resize, 500);
    };
    resize();
    
    $('.toggle-api-log').click(function(){
        $('.api-log').toggle();
        resize();
    });
    
    window.notifyResize = resize;
    
});
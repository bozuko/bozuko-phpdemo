jQuery(function($){
    prettyPrint();
    $('iframe').each(function(i,iframe){
        $(iframe).load(function(){
            iframe.contentWindow.addEventListener('message',function(e){
                if(e.data.height) $(iframe).height(e.data.height + 20);
            }, false);
        });
    });
    $('.refresh-btn').click(function(){
        $(this).prev('iframe')[0].contentWindow.location.reload();
    });
});
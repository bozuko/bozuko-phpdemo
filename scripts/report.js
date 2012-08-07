jQuery(function($){
    $('.report-nav a').click( function(e){
        e.preventDefault();
        var options = {
            server: BOZUKO_SERVER,
            key: BOZUKO_KEY
        };
        
        var filter = $(this).attr('data-filter');
        if( filter ){
            options[filter] = $(this).attr('data-id');
        }
        
        $('.report-options pre').html(
            JSON.stringify( options, null, '  ')
        );
        
        $('.report-title').html( $(this).html() );
        $('.report').bozukoreport(options);
        
        $('.report-nav a').removeClass('active');
        $(this).addClass('active');
    });
    $('.report-nav a.active').click();
});
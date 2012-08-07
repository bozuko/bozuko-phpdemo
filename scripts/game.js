jQuery(function($){
    $('.datetime').datetimepicker({
        beforeShow : function(){
            setTimeout(notifyResize, 10);
        }
    });
    $('#add-prize').click(function(e){
        e.preventDefault();
        // add tab
        var li = $(this).parent()
          , c = li.parent().find('li').length
          , i = c-1
          , id = 'prize_'+i
          , n = $('<li><a href="#'+id+'" data-toggle="tab">Prize '+c+'</li>')
          , tab = $('<div class="tab-pane" id="'+id+'"></div>').appendTo($('#prizes'))
          ;
          
        n.insertBefore(li);
        // use the first prize as a template
        var tpl = $('#prize_0');
        tab.html(tpl.html());
        tab.find('.control-group').each(function(){
            $(this).removeClass('error');
            $(this).find('.help-inline').remove();
        });
        updateTab(tab, i, true);
        
        
        n.find('a').tab('show');
    });
    $('.prize-well').on('click', '.remove-prize', function(e){
        var id = $(this).parents('.tab-pane').attr('id');
        $('a[href="#'+id+'"]').parent().remove();
        $('#'+id).remove();
        $('a[href="#prize_0"]').tab('show');
        
        // reset the other tabs
        $('.prize-well .tab-content .tab-pane').each(function(i){
            var id = $(this).attr('id');
            $('a[href="#'+id+'"]')
                .html('Prize '+(i+1))
                .attr('href','#prize_'+i)
                ;
            $(this).attr('id', 'prize_'+i);
            updateTab( $(this), i );
        });
        
    });
    
    $('[name=consolation_enabled]').change(function(){
        var enabled = $(this).find('option:selected').val();
        $('.consolation-prize')[enabled==='true'?'removeClass':'addClass']('hide');
    });
    
    
    jQuery(function(){
        $('[name=theme]').bozukothemechooser({server:BOZUKO_SERVER, key:BOZUKO_KEY});
    });
    
    function updateTab(tab, i, empty){
        tab.find('.control-group').each(function(){
            var l = $(this).find('label')
              , input = $(this).find('input, textarea, select')
              ;
              
            l.attr('for', l.attr('for').replace(/\[\d+\]/,'['+i+']'));
            input.attr('id', input.attr('id').replace(/\[\d+\]/,'['+i+']'));
            input.attr('name', input.attr('name').replace(/\[\d+\]/,'['+i+']'));
            if( empty ) input.val('');
        });
    }
});
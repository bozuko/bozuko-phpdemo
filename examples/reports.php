<?php
require_once(dirname(__FILE__).'/../init.php');
require_once(dirname(__FILE__).'/inc/helpers.php');

// add css and scripts
global $scripts, $styles, $config;

$scripts[] = $config['server'].'/js/highstock/1.1/highstock.js';
$scripts[] = $config['server'].'/js/widgets/reports.js';

include(dirname(__FILE__).'/inc/header.php');

global $api;
?>
<p>In order to use our charting you must be include the Highstock charting library on your
page.</p>
<p>Then, you need to include the report script. It is located on your Bozuko server at <strong>/js/widgets/reports.js</strong>.</p>

<div class="row-fluid">
    
    <div class="span3">
        <ul class="report-nav">
            <li><a href="#" class="active">All Games</a></li>
        <?php
        /**
         * You should put this all in try / catch blocks...
         */
        
        $pages = $api->call('/pages');
        foreach( $pages->pages as $page ){
            ?>
            <li><a href="#" data-filter="page_id" data-id="<?= $page->id ?>"><?= $page->name ?></a></li>
            <?php
            $games = $api->call($page->links->page_games);
            if( $games->count ){
                ?>
                <ul>
                <?php
                foreach( $games->games as $game ) {
                    ?>
                    <li><a href="#" data-filter="game_id" data-id="<?= $game->id ?>"><?= $game->name ?></a></li>
                    <?php
                }
                ?>
                </ul>
                <?php
            }
        }
        ?>
        </ul>
    </div>
    <div class="span9">
        <h2 class="report-title">All Games</h2>
        <div class="report" style="height: 400px;"></div>
        <div class="report-options">
            <p>The report can be generated with a tag:</p>
            <pre class="tag prettyprint linenums"></pre>
            <p>or javascript:</p>
            <pre class="js prettyprint linenums"></pre>
        </div>
        <p>If you use HTML tags, they must be part of the DOM before the script executes.</p>
    </div>
</div>
<script type="text/javascript">
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
        
        $('.report-options pre.tag').html(htmlEntities(
            ['<div '
            ,'  style="height: 400px"'
            ,'  data-bozuko-widget="reports"'
            ,'  data-server="'+BOZUKO_SERVER+'"'
            ,'  data-key="'+BOZUKO_KEY+'"'
            ,filter ? ('  data-'+filter+'="'+options[filter]+'"') : ''
            ,'></div>'].filter(function(a){return a!=''}).join('\n')
        ));
        
        $('.report-options pre.js').html(htmlEntities(
            ['jQuery(".report").bozukoreport({'
                ,'    server: "'+BOZUKO_SERVER+'"'
                ,'  , key: "'+BOZUKO_KEY+'"'
                ,filter ? ('  , '+filter+': "'+options[filter]+'"') : ''
            ,'});'].filter(function(a){return a!=''}).join('\n')
        ));
        
        $('.report-title').html( $(this).html() );
        $('.report').bozukoreport(options);
        prettyPrint();
        
        $('.report-nav a').removeClass('active');
        $(this).addClass('active');
    });
    $('.report-nav a.active').click();
});

function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
</script>


<?php
include(dirname(__FILE__).'/inc/footer.php');
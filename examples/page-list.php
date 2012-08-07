<?php
require_once(dirname(__FILE__).'/../init.php');
require_once(dirname(__FILE__).'/inc/header.php');
require_once(dirname(__FILE__).'/inc/helpers.php');

global $api;

try{
    $pages = $api->call('/pages');
    
    if(!$pages->count){
        alert_box(
            'There are no pages',
            '<p>Create one with the Create Page example</p>',
            'info',
            false
        );
    }
    else{
        ?>
        <ul>
            <? foreach( $pages->pages as $page ){ ?>
            <li style="margin-bottom: 10px;"><img style="max-height: 30px;" src="<?= $page->image ?>" alt="" /> <?= $page->name ?>
            <? } ?>
        </ul>
        <?php
    }
    
    
}
catch( Bozuko_Api_Exeption $e){
    // handle api error
    alert_box(
        'An API Error occurred',
        '<pre>'.htmlentities( print_r($e, 1)).'</pre>',
        'error',
        true
    );
}
catch( Exception $e ){
    // handle any other errors
    alert_box(
        'An Error occurred',
        '<pre>'.htmlentities( print_r($e, 1)).'</pre>',
        'error',
        true
    );
}

require_once(dirname(__FILE__).'/inc/footer.php');


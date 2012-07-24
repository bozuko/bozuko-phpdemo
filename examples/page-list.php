<?php
require_once(dirname(__FILE__).'/../init.php');
require_once(dirname(__FILE__).'/inc/header.php');
require_once(dirname(__FILE__).'/inc/helpers.php');

global $api;

try{
    $pages = $api->call('/pages');
    
    if(!$pages->count){
        ?>
        <div class="alert alert-info">
        <h4>There are no pages! Create one with the <strong>Create Page</strong> example.
        </div>
        <?
    }
    else{
        ?>
        <ul>
            <? foreach( $pages->pages as $page ){ ?>
            <li><img src="<?= $page->image ?>" alt="" /> <?= $page->name ?>
            <? } ?>
        </ul>
        <?php
    }
    
    
}catch(Bozuko_Api_Exception $e){
    // handle api error
    ?>
    <div class="alert alert-error">
        <h4>An API error occured</h4>
        <pre><?= htmlentities( print_r($e, 1)) ?></pre>
    </div>
    <?
    
}catch(Exception $e){
    // handle any other errors
    ?>
    <div class="alert alert-error">
        <h4>An error occured</h4>
        <pre><?= htmlentities( print_r($e, 1)) ?></pre>
    </div>
    <?
}

require_once(dirname(__FILE__).'/inc/footer.php');


<?php
require_once(dirname(__FILE__).'/../init.php');
require_once(dirname(__FILE__).'/inc/helpers.php');

$styles[] = $config['server'].'/css/widgets/themechooser.css';

require_once(dirname(__FILE__).'/inc/header.php');

global $api;

try{
    $themes = $api->call('/themes');
    
    if(!$themes->count){
        alert_box(
            'There are no themes',
            '<p>Create one with the Create Theme example</p>',
            'info',
            false
        );
    }
    else{
        ?>
        <div class="bozuko-theme-chooser">
            <? foreach( $themes->themes as $theme ){ ?>
            <div class="theme">
                <div class="preview">
                    <img src="<?= $config['server'] ?>/images/assets/icons/unknown.jpg" class="logo" />
                    <img src="<?= $theme->background ?>" class="bg"/>
                </div>
                <div class="name"><?= $theme->name ?></div>
            </div>
            <? } ?>
        </div>
        <div style="clear:both; margin-top: 20px;"></div>
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


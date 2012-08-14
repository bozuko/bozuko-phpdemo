<?php
require_once(dirname(__FILE__).'/../init.php');
require_once(dirname(__FILE__).'/inc/helpers.php');
require_once(dirname(__FILE__).'/inc/header.php');

global $api;

try{
    $errors = array();
    $success = false;
    $page=array();
    $result;
    
    $id = @$_REQUEST['id'];
    
    if( $id ){
        
        $game = $api->call('/game/'.$id);
        ?>
        <h2>Codes for <?= $game->name ?></h2>
        <?
        $result = $api->call( $game->links->game_prize_codes, 'GET', array(
            'limit' => 10000
        ));
        ?>
        <pre><? print_r( $result ) ?></pre>
        <?
    }
    
    // display list of pages
    list_games('codes_button');
    include(dirname(__FILE__).'/inc/footer.php');
    
    
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

function codes_button( $game )
{
    if( @$game->links->game_prize_codes ){
        ?>
    <form action="?" method="POST" style="margin:0">
        <input type="hidden" name="id" value="<?= $game->id ?>" />
        <button type="submit" class="btn btn-mini">View Codes</button>
    </form>
        <?php
    }
    else {
        ?>
        N/A
        <?
    }
}
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
        if( !@$game->links->game_cancel ){
            alert_box('Error', '<p>No Cancel Link</p>', 'error', false);
        }
        
        else {
            $result = $api->call( $game->links->game_cancel, 'POST' );
            if( $result->success ){
                alert_box('Nice!', '<p>Gam has been cancelled</p>', 'success');
            }
            else {
                alert_box('Error', '<p>'.$result->error.'</p>');
            }
        }
    }
    
    // publish...
    // display list of pages
    list_games('cancel_button');
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

function cancel_button( $game )
{
    if( @$game->links->game_cancel ){
        ?>
    <form action="?" method="POST" style="margin:0">
        <input type="hidden" name="id" value="<?= $game->id ?>" />
        <button type="submit" class="btn btn-mini">Cancel</button>
    </form>
        <?php
    }
    else {
        ?>
        N/A
        <?
    }
}
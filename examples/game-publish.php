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
        if( !@$game->links->game_publish ){
            alert_box('Yikes!', '<p>No publish link...</p>', 'error');
        }
        
        else {
            $result = $api->call( $game->links->game_publish, 'POST', array(), array('timeout'=>false) );
            if( $result->success ){
                alert_box('Nice!', '<p>Game was published</p>', 'success', false);
            }
            else {
                alert_box('Yikes!', '<p>Error publishing the game: '.$result->error.'</p>', 'error');
            }
        }
    }
    
    // publish...
    // display list of pages
    list_games('publish_button');
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

function publish_button( $game )
{
    if( @$game->links->game_publish ){
        ?>
    <form action="?" method="POST" style="margin:0">
        <input type="hidden" name="id" value="<?= $game->id ?>" />
        <button type="submit" class="btn btn-mini">Publish</button>
    </form>
        <?php
    }
    else {
        ?>
        N/A
        <?
    }
}
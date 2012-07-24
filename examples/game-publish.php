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
        
        ?>
        <a href="?">Back to list of games</a>
        <?php
        
        $game = $api->call('/game/'.$id);
        if( !@$game->links->game_publish ){
            ?>
    <div class="alert alert-error alert-block">
        <a class="close" data-dismiss="alert" href="#">&times;</a>
        <p>Could not publish - no publish link.</p>
    </div>
            <?php
        }
        
        else {
            $result = $api->call( $game->links->game_publish, 'POST' );
            if( $result->success ){
                ?>
        <div class="alert alert-block alert-success">
            <a class="close" data-dismiss="alert" href="#">&times;</a>
            <h4>Sweet Dice!</h4>
            <p>The game was published.</p>
        </div>
                <?php
            }
            else {
                ?>
        <div class="alert alert-error alert-block">
            <a class="close" data-dismiss="alert" href="#">&times;</a>
            <h4>Error publishing the game</h4>
            <p><?= $result->error ?></p>
        </div>
                <?php
            }
        }
    }
    
    // publish...
    // display list of pages
    list_games('publish_button');
    include(dirname(__FILE__).'/inc/footer.php');
    
    
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
}
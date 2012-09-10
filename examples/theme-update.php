<?php
require_once(dirname(__FILE__).'/../init.php');
require_once(dirname(__FILE__).'/inc/helpers.php');

$styles[] = $config['server'].'/css/widgets/themechooser.css';
$styles[] = '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/base/jquery-ui.css';
$scripts[] = '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js';

global $api;

try{
    // check for delete
    $del = @$_REQUEST['delete'];
    if( $del ){
        $result = $api->call('/theme/'.$del, 'DELETE' );
        
        header('Content-Type: application/json');
        echo json_encode( array(
            'success' => true,
            'result' => print_r( $result, 1 )
        ));
        exit;
    }
    
    $id = @$_REQUEST['id'];
    // if no id... then show all the themes
    if( !$id ){
        // check for saved order...
        if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
            // update each...
            foreach( $_POST['themes'] as $theme ){
                $api->call('/theme', 'POST', $theme );
            }
            header('Content-Type: application/json');
            echo json_encode( array(
                'success' => true
            ));
            exit;
        }
        $themes = $api->call('/themes', 'GET');
        include dirname(__FILE__).'/inc/header.php';
        ?>
        <style type="text/css">
            .theme {
                position: relative;
            }
            .theme .delete {
                display: none;
                position: absolute;
                top: 10px;
                right: 10px;
                color: red;
                padding: 5px;
                background: #fff;
                background: rgba(255,255,255,.8);
                border-radius-bottomleft: 10px;
            }
            .theme:hover .delete {
                display: inline-block;
            }
        </style>
        <form method="POST" value="?" class="sort-form">
        <div class="bozuko-theme-chooser">
            <? foreach( $themes->themes as $i => $theme ){ ?>
            <div class="theme">
                <div>
                    <input type="hidden" name="themes[<?=$i?>][id]" class="id" value="<?= $theme->id ?>" />
                    <input type="hidden" name="themes[<?=$i?>][name]" value="<?= htmlspecialchars($theme->name) ?>" />
                    <input type="hidden" name="themes[<?=$i?>][background]" value="<?= htmlspecialchars($theme->background) ?>" />
                    <input type="hidden" name="themes[<?=$i?>][order]" class="order" value="<?= $theme->order ?>" />
                    <? if( $theme->scope && is_array($theme->scope) ) foreach( $theme->scope as $page ){ ?>
                        <input type="hidden" name="themes[<?=$i?>][scope][]" value="<?= $page ?>" />
                    <? } ?>
                    <div class="preview">
                        <img src="<?= $config['server'] ?>/images/assets/icons/unknown.jpg" class="logo" />
                        <img src="<?= $theme->background ?>" class="bg"/>
                    </div>
                    <div class="name"><a href="?id=<?= $theme->id ?>"><?= $theme->name ?></a></div>
                    <a href="#" class="delete">Delete</a>
                </div>
            </div>
            <? } ?>
        </div>
        </form>
        <div style="clear:both; margin-top: 20px;"></div>
        <script type="text/javascript">
        jQuery('.bozuko-theme-chooser').sortable({
            update: function(){
                jQuery('.bozuko-theme-chooser .theme').each(function(i){
                    $(this).find('.order').val(i);
                });
                var form = jQuery('.sort-form');
                jQuery.post( form.attr('action'), form.serialize() );
            }
        });
        jQuery('.theme .delete').click( function(e){
            e.preventDefault();
            // delete this?
            if( confirm('Are you sure you want to delete this theme?') ){
                jQuery.post('?', {'delete':jQuery(this).parents('.theme').find('.id').val()});
                jQuery(this).parents('.theme').remove();
            }
        });
        </script>
        <?php
        include(dirname(__FILE__).'/inc/footer.php');
        exit;
    }
    include dirname(__FILE__).'/inc/header.php';
    
    $theme = $api->call('/theme/'.$id);
    $pages = $api->call('/pages');
    
    $submitted = false;
    
    $options = array();
    foreach( $pages->pages as $page ){
        $options[$page->id] = $page->name;
    }
    
    if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
        $submitted = true;
        $post = intuit_types( $_POST );
        // print_r( array( $post, $_POST ) );
        $result = $api->call('/theme', 'POST', $post );
        if( !$result->success ){
            $errors = $result->errors;
            $error = $result->error;
        }
        else {
            $theme = $result->theme;
            alert_box(
                'Sweet Dice!',
                '<p>The theme was updated</p>',
                'success',
                false
            );
        }
    }
    
    $fields = array(
        array(
            'name'              => 'id',
            'type'              => 'hidden'
        ),
        array(
            'name'              => 'name',
            'label'             => 'Theme Name',
            'type'              => 'text'
        ),
        array(
            'name'              => 'background',
            'label'             => 'Background',
            'type'              => 'text'
        ),
        array(
            'name'              => 'order',
            'label'             => 'Sort Order',
            'type'              => 'number'
        ),
        array(
            'name'              => 'scope',
            'label'             => 'Scope',
            'type'              => 'select',
            'multiple'          => true,
            'options'           => $options
        )
    );
    
    $values = $theme ? $theme : @$_POST;
    foreach( $fields as $i => $field ){
        $values = (array)$values;
        $n = $field['name'];
        if(($v = @$values[$n]) ) $fields[$i]['value'] = $v;
        // check for errors
        if( $errors && ($e = @$errors->$n) ){
            $fields[$i]['error'] = $e;
        }
    }
    
    ?>
    <a href="?">&crarr; Back to list of all themes</a>
    <form action="?" class="form-horizontal" method="POST">
        <? renderFields( $fields ) ?>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
    <?php
    
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


<?php
require_once(dirname(__FILE__).'/../init.php');
require_once(dirname(__FILE__).'/inc/helpers.php');

$styles[] = $config['server'].'/css/widgets/themechooser.css';

require_once(dirname(__FILE__).'/inc/header.php');

global $api;

try{
    // $themes = $api->call('/themes');
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
        $result = $api->call('/theme', 'PUT', $post );
        if( !$result->success ){
            $errors = $result->errors;
            $error = $result->error;
        }
        else {
            $theme = $result->theme;
            alert_box(
                'Sweet Dice!',
                '<p>The theme was created successfully</p>',
                'success',
                false
            );
            ?>
            <pre><? print_r( $result->theme ) ?></pre>
            <?
            include(dirname(__FILE__).'/inc/footer.php');
            exit;
        }
    }
    
    $fields = array(
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
    <form action="?" class="form-horizontal" method="POST">
        <? renderFields( $fields ) ?>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add Theme</button>
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


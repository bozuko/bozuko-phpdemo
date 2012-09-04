<?php
require_once(dirname(__FILE__).'/../init.php');
require_once(dirname(__FILE__).'/inc/helpers.php');

// add css and scripts
global $scripts, $styles, $config;
$styles[] = 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.19/themes/cupertino/jquery-ui.css';
$styles[] = 'https://raw.github.com/trentrichardson/jQuery-Timepicker-Addon/master/jquery-ui-timepicker-addon.css';

$scripts[] = 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.19/jquery-ui.min.js';
$scripts[] = 'https://raw.github.com/trentrichardson/jQuery-Timepicker-Addon/master/jquery-ui-timepicker-addon.js';
$scripts[] = '../scripts/game.js';
$scripts[] = $config['server'].'/js/widgets/themechooser.js';

require_once(dirname(__FILE__).'/inc/header.php');

global $api;

try{
    $errors = array();
    $success = false;
    $page=array();
    $result;
    
    $pages_result = $api->call('/pages', 'GET', array(
        'limit'     => 50
    ));
    if( !$pages_result->count ){
        alert_box(
            'There are no pages',
            '<p>Create one with the Create Page example</p>',
            'info',
            false
        );
        require_once(dirname(__FILE__).'/inc/footer.php');
        exit;
    }
    
    $pages = $pages_result->pages;
    $page_options = array(''=>'Select a Page');
    foreach($pages as $page){
        $page_options[$page->id] = $page->name;
    }
    
    if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
        $post = intuit_types( $_POST );
        // if consolation prizes is disabled, remove it
        if( !@$post['consolation_enabled'] ){
            $post['consolation_prizes'] = array();
        }
        $result = $api->call('/game', 'PUT', $post );
        if( !$result->success ){
            $errors = $result->errors;
            $error = $result->error;
        }
        else {
            $game = $result->game;
        }
    }
    
    if( $result && $result->success ){
        alert_box(
            'Sweet Dice!',
            '<p>The game was created successfully</p>',
            'success',
            false
        );
        ?>
        <pre><? print_r( $result->game ) ?></pre>
        <?
        include(dirname(__FILE__).'/inc/footer.php');
        exit;
    }
    
    $game_fields = array(
        array(
            'name'              => 'page_id',
            'label'             => 'Page',
            'type'              => 'select',
            'options'           => $page_options
        ),
        array(
            'name'              => 'name',
            'label'             => 'Name'
        ),
        array(
            'name'              => 'type',
            'label'             => 'Type',
            'type'              => 'select',
            'options'           => array('scratch' => 'Scratch Ticket')
        ),
        array(
            'name'              => 'start',
            'label'             => 'Start',
            'type'              => 'date'
        ),
        array(
            'name'              => 'end',
            'label'             => 'End',
            'type'              => 'date'
        ),
        array(
            'name'              => 'theme',
            'label'             => 'Theme'
        ),
        array(
            'name'              => 'game_background',
            'label'             => 'Game Background'
        ),
        array(
            'name'              => 'entry_duration',
            'label'             => 'Entry Duration',
            'type'              => 'select',
            'options'           => array(
                1000*60*60          => '1 Hour',
                1000*60*60*2        => '2 Hours',
                1000*60*60*3        => '3 Hours',
                1000*60*60*4        => '4 Hours',
                1000*60*60*8        => '8 Hours',
                1000*60*60*12       => '12 Hours',
                1000*60*60*24       => '1 Day',
                1000*60*60*24*2     => '2 Days',
                1000*60*60*24*3     => '3 Days',
                1000*60*60*24*4     => '4 Days',
                1000*60*60*24*5     => '5 Days',
            )
        ),
        array(
            'name'              => 'entry_type',
            'label'             => 'Entry Type',
            'type'              => 'select',
            'options'           => array(
                'facebook/like'         => 'Facebook Like',
                'facebook/likecheckin'  => 'Facebook Like + Checkin',
                'facebook/checkin'      => 'Facebook Checkin',
                'bozuko/nothing'        => 'No Requirement'
            )
        ),
        array(
            'name'              => 'rules',
            'label'             => 'Custom Rules',
            'type'              => 'textarea',
            'tall'              => true
        ),
        array(
            'name'              => 'share_url',
            'label'             => 'Facebook Share URL',
            'type'              => 'text'
        ),
        array(
            'name'              => 'share_title',
            'label'             => 'Facebook Share Title',
            'type'              => 'text'
        ),
        array(
            'name'              => 'share_description',
            'label'             => 'Facebook Share Description',
            'type'              => 'textarea'
        ),
        array(
            'name'              => 'engine_type',
            'label'             => 'Contest Engine',
            'type'              => 'select',
            'options'           => array(
                'time'              => 'Time Based',
                'order'             => 'Odds Based'
            )
        ),
        array(
            'name'              => 'win_frequency',
            'label'             => 'Win Frequency',
            'type'              => 'text'
        )
    );
    
    $values = $game ? $game : @$_POST;
    foreach( $game_fields as $i => $field ){
        $values = (array)$values;
        $n = $field['name'];
        if(($v = @$values[$n]) ) $game_fields[$i]['value'] = $v;
        // check for errors
        if( $errors && ($e = @$errors->$n) ){
            $game_fields[$i]['error'] = $e;
        }
    }
    
    $prize_fields = array(
        array(
            'name'              => 'name',
            'label'             => 'Name'
        ),
        array(
            'name'              => 'description',
            'label'             => 'Description',
            'type'              => 'textarea'
        ),
        array(
            'name'              => 'value',
            'label'             => 'Value'
        ),
        array(
            'name'              => 'total',
            'label'             => 'Quantity'
        ),
        array(
            'name'              => 'hide_expiration',
            'label'             => 'Hide Expiration',
            'type'              => 'select',
            'options'           => array(
                'true'              => 'Yes',
                'false'             => 'No'
            )
        ),
        array(
            'name'              => 'expiration',
            'label'             => 'Expiration',
            'type'              => 'text'
        ),
    );
    
    $prize_values = $game ? $game->prizes : @$_POST['prizes'];
    $consolation_prize_values = $game ? $game->consolation_prizes : @$_POST['consolation_prizes'];
    /* ?><pre><? print_r( $prize_values ) ?></pre><?php */
    if( !is_array( $prize_values ) || !count($prize_values) ) {
        $prize_values = array(array());
    }
    $prizes = array();
    foreach($prize_values as $cur => $values){
        $values = (array)$values;
        // copy fields
        $fields = $prize_fields;
        foreach( $fields as $i => $field ){
            $n = $field['name'];
            if(($v = @$values[$n]) ) $fields[$i]['value'] = $v;
            // check for errors
            if( $errors && @$errors->prizes && @$errors->prizes[$cur] && ($e = @$errors->prizes[$cur]->$n) ){
                $fields[$i]['error'] = $e;
            }
            // fix the name
            $fields[$i]['name'] = "prizes[$cur][$n]";
            $fields[$i]['ref'] = $n;
        }
        $prizes[] = $fields;
    }
    
    $consolation_prizes = array();
    $consolation_enabled = true;
    if( @$_POST['consolation_enabled'] === 'false' || !is_array( $consolation_prize_values ) || !count($consolation_prize_values) ) {
        $consolation_enabled = false;
        $consolation_prize_values = array(array());
    }
    if( is_array( $consolation_prize_values ) ) foreach($consolation_prize_values as $cur => $values){
        $values = (array)$values;
        // copy fields
        $fields = $prize_fields;
        foreach( $fields as $i => $field ){
            $n = $field['name'];
            if(($v = @$values[$n]) ) $fields[$i]['value'] = $v;
            // check for errors
            if( $errors && @$errors->consolation_prizes && @$errors->consolation_prizes[$cur] && ($e = @$errors->consolation_prizes[$cur]->$n) ){
                $fields[$i]['error'] = $e;
            }
            // fix the name
            $fields[$i]['name'] = "consolation_prizes[$cur][$n]";
            $fields[$i]['ref'] = $n;
        }
        $consolation_prizes[] = $fields;
    }
    
    ?>
    <form action="?" class="form-horizontal" method="POST">
        <h3>Game Details</h3>
        <div class="well">
            <? renderFields( $game_fields ) ?>
        </div>
        
        <h3>Prizes</h3>
        <div class="well  prize-well">
            <ul class="nav nav-tabs">
            <? foreach($prizes as $i => $prize){ ?>
                <li<? if(!$i){ ?> class="active"<? } ?>>
                    <a href="#prize_<?= $i ?>" data-toggle="tab">Prize <?= $i+1 ?></a>
                </li>
            <? } ?>
                <li>
                    <a href="#" id="add-prize">+</a>
                </li>
            </ul>
            <div class="tab-content" id="prizes">
                <? foreach( $prizes as $i => $prize ){ ?>
                <div class="tab-pane<? if(!$i){ ?> active<? } ?>" id="prize_<?= $i ?>">
                    <? renderFields( $prize ) ?>
                    <div class="form-actions">
                        <button type="button" class="btn btn-danger remove-prize">Remove Prize</button>
                    </div>
                </div>
                <? } ?>
            </div>
            
            <div style="padding-top: 20px;">
            <?php
            renderFields(array(array(
                'type' => 'select',
                'name' => 'consolation_enabled',
                'options' => array(
                    'true'  => 'Enabled',
                    'false' => 'Disabled'
                ),
                'label' => 'Consolation Prize',
                'value' => $consolation_enabled ? 'true' : 'false'
            )));
            ?>
                <div class="consolation-prize<?= $consolation_enabled ? '' : ' hide' ?>">
                    <? renderFields( $consolation_prizes[0] ) ?>
                </div>
            </div>
        </div>
        
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Create Game</button>
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

include(dirname(__FILE__).'/inc/footer.php');

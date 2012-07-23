<?php
require_once(dirname(__FILE__).'/../init.php');

// add css and scripts
global $scripts, $styles;
$styles[] = 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.19/themes/cupertino/jquery-ui.css';
$styles[] = 'https://raw.github.com/trentrichardson/jQuery-Timepicker-Addon/master/jquery-ui-timepicker-addon.css';

$scripts[] = 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.19/jquery-ui.min.js';
$scripts[] = 'https://raw.github.com/trentrichardson/jQuery-Timepicker-Addon/master/jquery-ui-timepicker-addon.js';
$scripts[] = '../scripts/game.js';

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
        ?>
        <div class="alert alert-info">
        <h4>There are no pages! Create one with the <strong>Create Page</strong> example.
        </div>
        <?php
        include(dirname(__FILE__).'/inc/footer.php');
        exit;
    }
    
    $id = @$_REQUEST['id'];
    
    if( !$id ){
        // display list of pages
        list_games('Edit', '?id=%s');
        include(dirname(__FILE__).'/inc/footer.php');
        exit;
    }
    
    if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
        $post = intuit_types( $_POST );
        $result = $api->call('/game', 'POST', $post );
        if( !$result->success ){
            $errors = $result->errors;
            $error = $result->error;
        }
        else {
            $game = $result->game;
        }
    }
    
    else {
        $game = $api->call('/game/'.$id);
    }
    
    if( $result && $result->success ){
        ?>
        <div class="alert alert-block alert-success">
            <h4>Game Created</h4>
            <p>The game was updated successfully.</p>
        </div>
        <?php
    }
    
    $game_fields = array(
        array(
            'name'              => 'id',
            'type'              => 'hidden'
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
            'label'             => 'Theme',
            'type'              => 'select',
            'options'           => array('default' => 'Default')
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
            'name'              => 'entry_plays',
            'label'             => 'Entry Plays',
            'type'              => 'select',
            'options'           => array(
                1                   => '1',
                2                   => '2',
                3                   => '3'
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
            'name'              => 'quantity',
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
    
    ?>
    <a href="?">Back to list of games</a>
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
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Game</button>
        </div>
    </form>
    <?php
    
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

include(dirname(__FILE__).'/inc/footer.php');

function list_games($link_text, $link_href)
{
    global $api;
    try {
        $pages_result = $api->call('/pages', 'GET', array(
            'limit'     => 50
        ));
        if( !$pages_result->count ){
            ?>
            <div class="alert alert-info">
            <h4>There are no pages! Create one with the <strong>Create Page</strong> example.
            </div>
            <?php
            require_once(dirname(__FILE__).'/inc/footer.php');
            exit;
        }
        ?>
        <h4>Games</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Game Name</th>
                    <th>Page</th>
                    <th>Start</th>
                    <th>State</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
        <?php
        foreach( $pages_result->pages as $page){
            $games = $api->call($page->links->page_games);
            if( $games->count ) foreach( $games->games as $game ) {
                ?>
                <tr>
                    <td><?= $game->name ?></td>
                    <td><?= $page->name ?></td>
                    <td><?= $game->start ?></td>
                    <td><?= $game->state ?></td>
                    <td>
                        <a href="<?= sprintf($link_href, $game->id) ?>"><?= $link_text ?></a>
                    </td>
                <?php
            }
        }
        ?>
            </tbody>
        </table>
        <?php
    }
    catch( Bozuko_Api_Exeption $e){
        // handle api error
        ?>
        <div class="alert alert-error">
            <h4>An API error occured</h4>
            <pre><?= htmlentities( print_r($e, 1)) ?></pre>
        </div>
        <?
    }
    catch( Exception $e ){
        // handle any other errors
        ?>
        <div class="alert alert-error">
            <h4>An error occured</h4>
            <pre><?= htmlentities( print_r($e, 1)) ?></pre>
        </div>
        <?
    }
    
}

function intuit_types($ar)
{
    $n = array();
    foreach( $ar as $k => $v){
        // echo( "$k: ".gettype($v)."<br />");
        if( is_array( $v ) ){
            $n[$k] = intuit_types( $v );
        }
        else if( is_string( $v ) ){
            if( preg_match('/^true|false$/', $v ) ){
                $n[$k] = (bool) $v;
            }
            else if( preg_match('/^(\d+|\d?\.\d+)$/', $v) ) {
                $n[$k] = (float) $v;
            }
            else if( !$v ){
                // don't set it
            }
            else {
                $n[$k] = $v;
            }
        }
        else {
            $n[$k] = $v;
        }
    }
    return $n;
}

function renderFields($fields)
{
    foreach( $fields as $i => $field ){
        $classes = array('control-group');
        if( $field['error'] ) $classes[] = 'error';
        ?>
    <div class="<?= implode(' ',$classes) ?>" >
        <label class="control-label" for="<?= $field['name'] ?>"><?= $field['label'] ?></label>
        <?php
        $type = @$field['type'];
        switch( $type ){
            
            case 'select':
                ?>
        <div class="controls">
            <select
                class="span3"
                name="<?= $field['name'] ?>"
                id="<?= $field['name'] ?>"
            >
            <?
            foreach((array) @$field['options'] as $value => $text ){
                $selected = $field['value'] === $value; 
                ?>
                <option
                    <?= $selected ? 'selected' : '' ?>
                    value="<?= htmlspecialchars($value, ENT_QUOTES, 'utf-8') ?>"
                ><?= $text ?></option>
                <?php
            }
            ?>
            </select>
            <? if( ($e=$field['error']) ){ ?>
            <span class="help-inline"><?= $e ?></span>
            <? } ?>
        </div>        
                <?php
                break;
            
            case 'textarea':
                ?>
        <div class="controls">
            <textarea
                class="span6<?= @$field['tall'] ? ' tall' : '' ?>"
                type="text"
                name="<?= $field['name'] ?>"
                id="<?= $field['name'] ?>""
            ><?= @$field['value'] ?></textarea>
            <? if( ($e=$field['error']) ){ ?>
            <span class="help-inline"><?= $e ?></span>
            <? } ?>
        </div>
                <?php
                break;
            
            case 'hidden':
                ?>
            <input
                type="hidden"
                name="<?= $field['name'] ?>"
                id="<?= $field['name'] ?>"
                <? if( ($v=@$field['value']) ) { ?>
                value="<?= htmlspecialchars($v, ENT_QUOTES, 'UTF-8') ?>"
                <? } ?>
            />
                <?php
                break;
            
            case 'text':
            case 'date':
            default:
                $classes = array('span3');
                if( $type == 'date' ) $classes[] = 'datetime';
                ?>
        <div class="controls">
            <input
                class="<?= implode(' ',$classes) ?>"
                type="text"
                name="<?= $field['name'] ?>"
                id="<?= $field['name'] ?>"
                <? if( ($v=@$field['value']) ) { ?>
                value="<?= htmlspecialchars($v, ENT_QUOTES, 'UTF-8') ?>"
                <? } ?>
            />
            <? if( ($e=$field['error']) ){ ?>
            <span class="help-inline"><?= $e ?></span>
            <? } ?>
        </div>
        <?php
                break;
        }
        ?>
    </div>
    <?
    } 
}



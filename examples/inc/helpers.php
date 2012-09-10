<?php

function list_games($link_text, $link_href=null)
{
    global $api;
    try {
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
            include(dirname(__FILE__).'/footer.php');
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
                    <td><a href="<?= $game->url ?>" target="_blank"><?= $game->name ?></a></td>
                    <td><?= $page->name ?></td>
                    <td><?= date('m/d/Y h:i a', strtotime($game->start)) ?></td>
                    <td><?= $game->status ?></td>
                    <td>
                        <?php
                        if( is_callable( $link_text ) && !$link_href ){
                            call_user_func( $link_text, $game );
                        }
                        else {
                        ?>
                        <a class="btn btn-mini" href="<?= sprintf($link_href, $game->id) ?>"><?= $link_text ?></a>
                        <?php
                        }
                        ?>
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
}

function alert_box($title, $text, $type='error', $close=true)
{
    ?>
    <div class="alert alert-<?= $type ?> alert-block">
        <a class="close" data-dismiss="alert" href="#">&times;</a>
        <? if( $title ) { ?><h4><?= $title ?></h4><? } ?>
        <?= $text ?>
    </div>
    <?php
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
            if( preg_match('/^(true|false)$/', $v ) ){
                $n[$k] = $v === 'true';
                
            }
            else if( preg_match('/^\d+$/', $v) ) {
                $n[$k] = (int) $v;
            }
            else if( preg_match('/^\d*\.\d+$/', $v) ) {
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
        if( @$field['type'] == 'hidden' ) $classes[] = 'hide';
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
                name="<?= $field['name'] ?><? if( @$field['multiple'] ){ ?>[]<? } ?>"
                id="<?= $field['name'] ?>"
                <? if( @$field['multiple'] ){ ?>
                multiple="multiple"
                style="height: 100px"
                <? } ?>
            >
            <?
            foreach((array) @$field['options'] as $value => $text ){
                $selected = is_array($field['value']) ?
                    in_array( $value, $field['value'] ) :
                    $value === $field['value']; 
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
            case 'number':
            case 'email':
            default:
                $classes = array('span3');
                if( $type == 'date' ) $classes[] = 'datetime';
                ?>
        <div class="controls">
            <input
                class="<?= implode(' ',$classes) ?>"
                type="<?= $type == 'date' || !$type? 'text' : $type ?>"
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

function parse_date( $str )
{
    list($date, $time) = explode(' ',$str);
    list($m,$d,$y) = explode('/', $date);
    list($h,$i) = explode(':', $time);
    
    $d= new DateTime("$y-$m-$d", new DateTimeZone('America/New_York'));
    $d->setTime($h, $i);
    
    return $d;
    
}

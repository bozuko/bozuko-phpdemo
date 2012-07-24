<?php

function list_games($link_text, $link_href=null)
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

function parse_date( $str )
{
    list($date, $time) = explode(' ',$str);
    list($m,$d,$y) = explode('/', $date);
    list($h,$i) = explode(':', $time);
    
    $d= new DateTime("$y-$m-$d", new DateTimeZone('America/New_York'));
    $d->setTime($h, $i);
    
    return $d;
    
}

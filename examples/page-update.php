<?php
require_once(dirname(__FILE__).'/../init.php');
require_once(dirname(__FILE__).'/inc/header.php');
require_once(dirname(__FILE__).'/inc/helpers.php');

global $api;

try{
    $errors = array();
    $success = false;
    $page=array();
    $result;
    
    $pages_result = $api->call('/pages');
    if( !$pages_result->count ){
        ?>
        <div class="alert alert-info">
        <h4>There are no pages! Create one with the <strong>Create Page</strong> example.
        </div>
        <?php
        require_once(dirname(__FILE__).'/inc/footer.php');
        exit;
    }
    $pages = $pages_result->pages;
    
    // current page?
    if( ($id = $_GET['id']) ){
        $page = $api->call('/page/'.$id);
        
    }
    
    if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
        $result = $api->call('/page/', 'POST', intuit_types($_POST));
        if( !$result->success ){
            $errors = $result->errors;
            $error = $result->error;
        }
        else {
            $page = $result->page;
        }
    }
    
    if( $result && $result->success ){
        ?>
        <div class="alert alert-block alert-success">
            <h4>Page Updated</h4>
            <p>The page was updated successfully.</p>
        </div>
        <?php
    }
    
    $fields = array(
        array(
            'name'              => 'facebook_id',
            'label'             => 'Facebook Page Id'
        ),
        array(
            'name'              => 'name',
            'label'             => 'Name'
        ),
        array(
            'name'              => 'image',
            'label'             => 'Image URL'
        ),
        array(
            'name'              => 'location[street]',
            'label'             => 'Street'
        ),
        array(
            'name'              => 'location[city]',
            'label'             => 'City'
        ),
        array(
            'name'              => 'location[state]',
            'label'             => 'State'
        ),
        array(
            'name'              => 'location[country]',
            'label'             => 'Country'
        ),
        array(
            'name'              => 'location[zip]',
            'label'             => 'Zip'
        ),
        array(
            'name'              => 'location[lat]',
            'label'             => 'Latitude'
        ),
        array(
            'name'              => 'location[lng]',
            'label'             => 'Longitude'
        )
    );
    foreach( $fields as $i => $field ){
        $ar = $_SERVER['REQUEST_METHOD'] == 'POST' ? $_POST : (array) $page;
        $n = $field['name'];
        if( preg_match('/location\[(.*?)\]/', $field['name'], $matches) ){
            $ar = (array) $ar['location'];
            $n = $matches[1];
        }
        
        if( ($v=@$ar[$n]) ) $fields[$i]['value'] = $v;
        if( $result && $result->errors && ($e = $result->errors->$n) ){
            $field[$i]['error'] = $e;
        }
    }
    ?>
    <form action="?" class="form-horizontal" method="POST">
        
        <div class="control-group">
            <label class="control-label" for="id">Page</label>
            <div class="controls">
                <select name="id" id="id" onchange="window.location='?id='+$(this).find('option:selected').val();">
                    <option></option>
                    <? foreach( $pages as $page ): ?>
                    <option
                        value="<?= $page->id ?>"
                        <? if( @$_REQUEST['id'] == $page->id ): ?>selected<? endif; ?>
                    ><?= $page->name ?></option>
                    <? endforeach; ?>
                </select>
            </div>
        </div>
        <?php foreach( $fields as $i => $field ): ?>
        <div class="control-group<? if( $field['error'] ){ ?> error<? } ?>">
            <label class="control-label" for="<?= $field['name'] ?>"><?= $field['label'] ?></label>
            <div class="controls">
                <input
                    class="input-xlarge"
                    type="text"
                    name="<?= $field['name'] ?>"
                    id="<?= $field['name'] ?>"
                    <? if( ($v=@$field['value']) ) { ?>
                    value="<?= htmlspecialchars($v, ENT_QUOTES, 'UTF-8') ?>"
                    <? } ?>
                />
                <? if( ($e=$field['error']) ): ?>
                <span class="help-inline"><?= $e ?></span>
                <? endif; ?>
            </div>
        </div>
        <? endforeach; ?>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Page</button>
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



require_once(dirname(__FILE__).'/inc/footer.php');

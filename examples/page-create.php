<?php
require_once(dirname(__FILE__).'/../init.php');
require_once(dirname(__FILE__).'/inc/header.php');
require_once(dirname(__FILE__).'/inc/helpers.php');

global $api;

try{
    $errors = array();
    $success = false;
    $result;
    if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
        $result = $api->call('/page', 'PUT', intuit_types($_POST) );
        if( !$result->success ){
            $errors = $result->errors;
            $error = $result->error;
        }
        else {
            $page=$result->page;
        }
    }
    
    if( $result && $result->success ){
        ?>
        <div class="alert alert-block alert-success">
            <h4>Page Created</h4>
            <p>The page was created successfully - see the details below.</p>
            <p><a href="?">Create another page</a></p>
        </div>
        <pre><?= htmlentities( print_r( $result->page, 1 ) ) ?></pre>
        <?php
    }
    else {
    
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
        foreach( $fields as &$field ){
            
            $ar = $_POST;
            $n = $field['name'];
            if( preg_match('/location\[(.*?)\]/', $field['name'], $matches) ){
                $ar = $_POST['location'];
                $n = $matches[1];
            }
            
            if( ($v=@$ar[$n]) ) $field['value'] = $v;
            if( $result && $result->errors && ($e = $result->errors->$n) ){
                $field['error'] = $e;
            }
            
        }
        ?>
        <form action="?" class="form-horizontal" method="POST">
            
            <?php foreach( $fields as $field ):
                
                ?>
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
                <button type="submit" class="btn btn-primary">Create Page</button>
            </div>
        </form>
        <?php
    }
    
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

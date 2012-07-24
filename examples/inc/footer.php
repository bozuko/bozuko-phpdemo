        <button type="button" class="btn toggle-api-log">Toggle API Log</button> 
        
        <div class="api-log">
            <pre><?
            global $api;
            foreach($api->getHistory() as $request){
                $e = array(
                    'path' => $request->getPath(),
                    'params' => $request->getParams(),
                    'response_data' => $request->getResponse()->getData()
                );
                print_r($e);
            }
            ?></pre>
        </div>
        
        <!-- Javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <?php
        global $scripts;
        foreach( $scripts as $script ){
            ?>
        <script src="<?= $script ?>"></script>
            <?php
        }
        ?>
        <script src="../scripts/example.js"></script>
    </body>
</html>
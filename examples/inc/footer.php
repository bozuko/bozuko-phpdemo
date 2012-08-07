        <button type="button" class="btn toggle-api-log">Toggle API Log</button> 
        
        <div class="api-log">
            <pre><?
            global $api;
            foreach($api->getHistory() as $request){
                $e = array(
                    'path' => $request->getPath(),
                    'method' => $request->getMethod(),
                    'params' => $request->getParams(),
                    'response_data' => $request->getResponse()->getData()
                );
                print_r($e);
            }
            ?></pre>
        </div>
    </body>
</html>
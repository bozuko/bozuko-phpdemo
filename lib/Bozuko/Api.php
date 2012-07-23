<?php

require_once(dirname(__FILE__).'/Api/Request.php');
require_once(dirname(__FILE__).'/Api/Response.php');
require_once(dirname(__FILE__).'/Api/Exception.php');

class Bozuko_Api {
    
    protected $server;
    protected $token;
    protected $history;
    protected $api_key;
    protected $api_secret;
    protected $throwExceptions = true;
    protected $port;
    protected $last_response;
    
    public function __construct( $server=null )
    {
        $this->setServer( $server );
    }
    
    public function setServer( $server )
    {
        $this->server = $server;
        return $this;
    }
    
    public function setApiKey( $api_key )
    {
        $this->api_key = $api_key;
        return $this;
    }
    
    public function setApiSecret( $api_secret )
    {
        $this->api_secret = $api_secret;
        return $this;
    }
    
    public function setToken( $token )
    {
        $this->token = $token;
        return $this;
    }
    
    public function setThrowExceptions( $throw )
    {
        $this->throwExceptions = $throw;
    }
    
    public function getLastResponse()
    {
        return $this->last_response;
    }
    
    public function call( $path, $method='GET', $params=array() )
    {
        $this->history[] = $request = new Bozuko_Api_Request(array(
            'server'        => $this->server,
            'token'         => $this->token,
            'apiKey'        => $this->api_key,
            'apiSecret'     => $this->api_secret,
            'path'          => $path,
            'method'        => $method,
            'params'        => $params
        ));
        
        $this->last_response = $response = $request->run();
        
        if( $this->throwExceptions && $response->isError() ){
            throw new Bozuko_Api_Exception( $request );
        }
        
        return $response->getData();
    }
    
}
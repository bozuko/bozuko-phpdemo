<?php

class Bozuko_Api_Exception extends Exception
{
    protected $request;
    
    public function __construct( $request )
    {
        $this->request = $request;
        parent::__construct( $this->getBozukoMessage() );
    }
    
    protected function getBozukoMessage()
    {
        return 'Bozuko Request: '.print_r($this->request, 1);
    }
    
    
}

<?php
${basename(__FILE__, '.php')}= function(){

    if($this->get_request_method() == "POST" and isset($this->_request['refresh_token']) and !empty($this->_request['refresh_token'])){
        $refresh_token = $this->_request['refresh_token'];
        try{
        $auth = new oauth($this->_request['refresh_token']);
        $data = [
            "message" => "Refrece successful",
            "tokens" => $auth->refreshAccess(),
        ];
        $data = $this->json($data);
        $this->response($data, 200);
        }
        catch(Exception $e){
         $data = [
            "error" => $e->getMessage()
        ];
        $data = $this->json($data);
        $this->response($data, 406);
        }
    }else{
        $data = [
            "error" => "Bad request, username and password are required"
        ];
        $data = $this->json($data);
        $this->response($data, 400);
    }
};
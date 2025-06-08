<?php
${basename(__FILE__, '.php')} = function(){
    if($this->get_request_method() == "POST" and $this->isAuthenticated()){
        try{
            $data = [
                "username" => $this->getUsername(),
             ];
            $data = $this->json($data);
            $this->response($data, 200);
        } catch(Exception $e){
            $data = [
                "error" => $e->getMessage()
            ];
            $data = $this->json($data);
            $this->response($data, 403);
        }

    } else {
        $data = [
            "error" => "Bad request"
        ];
        $data = $this->json($data);
        $this->response($data, 400);
    }
};







































// ${basename(__FILE__, '.php')} = function(){
//     $token = $this->_request['access_token'] ?? $_GET['access_token'] ?? $_POST['access_token'] ?? null;
// try {
//             $oauth = new OAuth($token);
//             if ($oauth->authenticate()) {
//                 $data = [
//                     "username" => $oauth->getUsername(),
//                 ];
//                 $data = $this->json($data);
//                 $this->response($data, 200);
//                 return;
//             } else {
//                 throw new Exception("Invalid or expired token");
//             }
//     } catch(Exception $e) {
//         $data = [
//             "error" => $e->getMessage()
//         ];
//         $data = $this->json($data);
//         $this->response($data, 403);
//         return;
//     }
// };
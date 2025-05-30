<?php

${basename(__FILe__, '.php')} = function(){

        if ($this->get_request_method() == "POST") {
            $data = ["error" => "method_not_allowed"];
            return $this->response($this->json($data), 405);
        }

        if (isset($this->_request['username']) && isset($this->_request['password']) && isset($this->_request['email'])) {
            $username = $this->_request['username'];
            $password = $this->_request['password'];
            $email = $this->_request['email'];

            $hashed_password = signup::hashPassword($password);
            $token = bin2hex(random_bytes(16));

            $db = $this->dbConnect();
            $query = "INSERT INTO auth (username, password, email, token) VALUES (?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("ssss", $username, $hashed_password, $email, $token);



            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;
                $data = [
                    "message" => "success $username",
                    "user_id" => $user_id,
                    "token" => $token  // Optional: send back the token if needed
                ];
                return $this->response($this->json($data), 201);
            } else {
                $data = ["error" => "internal_server_error"];
                return $this->response($this->json($data), 500);
            }
        } else {
            $data = ["error" => "expectation_failed"];
            return $this->response($this->json($data), 417);
        }
    };
<?php
    class UserController extends BaseController
    {
        /** 
    * "/user/list" Endpoint - Get list of users 
    */
        // public function listAction()
        // {
        //     $strErrorDesc = '';
        //     $requestMethod = $_SERVER["REQUEST_METHOD"];
        //     $arrQueryStringParams = $this->getQueryStringParams();
        //     if (strtoupper($requestMethod) == 'GET') {
        //         try {
        //             $userModel = new UserModel();
        //             $intLimit = 10;
        //             if (isset($arrQueryStringParams['limit']) && !empty($arrQueryStringParams['limit'])) {
        //                 $intLimit = intval($arrQueryStringParams['limit']);
        //             }
        //             $arrUsers = $userModel->getUsers($intLimit);
        //             $responseData = json_encode($arrUsers);
        //         } catch (Error $e) {
        //             $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        //             $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        //         }
        //     } else {
        //         $strErrorDesc = 'Method not supported';
        //         $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        //     }
        //     // send output 
        //     if (!$strErrorDesc) {
        //         $this->sendOutput(
        //             $responseData,
        //             array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        //         );
        //     } else {
        //         $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
        //             array('Content-Type: application/json', $strErrorHeader)
        //         );
        //     }
        // }

        // public function getinfoAction()
        // {
        //     $strErrorDesc = '';
        //     $requestMethod = $_SERVER["REQUEST_METHOD"];
        //     $arrQueryStringParams = $this->getQueryStringParams();
        //     $mail = "";
        //     if (strtoupper($requestMethod) == 'GET') {
        //         try {
        //             $userModel = new UserModel();
        //             if (isset($arrQueryStringParams['mail']) && !empty($arrQueryStringParams['mail']) && filter_var($arrQueryStringParams['mail'], FILTER_VALIDATE_EMAIL)) {
        //                 $mail = strval($arrQueryStringParams['mail']);
        //                 $userModel = new UserModel();
        //                 $userInfo = $userModel->getinfo($mail);
        //                 $responseData = json_encode($userInfo);
        //             }
        //             else{
        //                 $responseData = json_encode("Mail is not found or not valid");
        //             }
        //         } catch (Error $e) {
        //             $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        //             $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        //         }
        //     } else {
        //         $strErrorDesc = 'Method not supported';
        //         $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        //     }
        //     // send output 
        //     if (!$strErrorDesc) {
        //         $this->sendOutput(
        //             $responseData,
        //             array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        //         );
        //     } else {
        //         $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
        //             array('Content-Type: application/json', $strErrorHeader)
        //         );
        //     }
        // }

        public function loginAction()
        {
            $strErrorDesc = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];
            
            if (strtoupper($requestMethod) == 'POST') {
                
                if (isset($_POST['mail']) && isset($_POST['pass'])) {
                    $mail = $_POST['mail'];
                    $pass = $_POST['pass'];
                    
                    try {
                        $userModel = new UserModel();
                        $userInfo = $userModel->getinfo($mail);
                        
                        //var_dump($userInfo);

                        if (!empty($userInfo) && ($pass == $userInfo[0]["pass"])) {
                            
                            $token = $userInfo[0]["token"]; 
                            $uname = $userInfo[0]["uname"]; 
                            $mail = $userInfo[0]["mail"]; 
                            
                            $responseData = json_encode(array('token' => $token, 'uname' => $uname, 'mail' => $mail));
                            $this->sendOutput($responseData, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
                        } else {
                            // Invalid credentials
                            $responseData = json_encode(array('error' => 'Invalid credentials'));
                            $this->sendOutput($responseData, array('Content-Type: application/json', 'HTTP/1.1 401 Unauthorized'));
                        }
                    } catch (Exception $e) {
                        $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                    }
                } else {
                    $strErrorDesc = 'Missing required parameters here';
                    $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 405 Method Not Allowed';
            }
            
            // Send output
            if ($strErrorDesc) {
                $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                    array('Content-Type: application/json', $strErrorHeader)
                );
            }
        }

        public function registerAction()
        {
            $strErrorDesc = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];

            if (strtoupper($requestMethod) == 'POST') {
                if (isset($_POST['mail']) && isset($_POST['pass']) && isset($_POST['uname'])) {
                    $mail = $_POST['mail'];
                    $pass = $_POST['pass'];
                    $uname = $_POST['uname'];
                    $token = sha1(md5($_POST["mail"]));

                    try {
                        $userModel = new UserModel();
                        $existingUser = $userModel->getinfo($mail);

                        if (empty($existingUser)) {
                            // User does not exist, proceed with registration
                            $insertId = $userModel->insertUser($mail, $pass, $uname, $token);

                            if ($insertId) {
                                // User registered successfully
                                //$responseData = json_encode(array('userId' => $insertId));
                                //$responseData = json_encode(array('Status' => "User Created"));
                                $responseData = json_encode(array('token' => $token));
                                $this->sendOutput($responseData,array('Content-Type: application/json', 'HTTP/1.1 201 Created'));
                                //$this->sendOutput(array('Content-Type: application/json', 'HTTP/1.1 201 Created'));
                            } else {
                                // Failed to register user
                                $responseData = json_encode(array('error' => 'Failed to register user'));
                                $this->sendOutput($responseData, array('Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error'));
                            }
                        } else {
                            // User already registered
                            $responseData = json_encode(array('error' => 'User already registered'));
                            $this->sendOutput($responseData, array('Content-Type: application/json', 'HTTP/1.1 409 Conflict'));
                        }
                    } catch (Exception $e) {
                        $strErrorDesc = $e->getMessage() . ' Something went wrong! Please contact support.';
                        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                    }
                } else {
                    $strErrorDesc = 'Missing required parameters';
                    $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 405 Method Not Allowed';
            }

            // Send output
            if ($strErrorDesc) {
                $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
                    array('Content-Type: application/json', $strErrorHeader)
                );
            }
        }
    }
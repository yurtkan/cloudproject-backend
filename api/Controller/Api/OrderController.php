<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Gerekli dosyaları include ediyoruz
require_once "PHPMailer/PHPMailer.php";
require_once "PHPMailer/Exception.php";
require_once "PHPMailer/SMTP.php";

class OrderController extends BaseController
{
    public function listAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'POST') {
            try {
                $orderModel = new OrderModel();
                $orderInfo = $orderModel->getMenu();

                if (!empty($orderInfo)) {
                    $responseData = json_encode($orderInfo);
                    $this->sendOutput($responseData, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
                } else {
                    $responseData = json_encode(array('error' => 'Something went wrong! Please contact support.'));
                    $this->sendOutput($responseData, array('Content-Type: application/json', 'HTTP/1.1 401 Unauthorized'));
                }
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage() . ' Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
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

    public function confirmAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'POST') {
            if (isset($_POST['token']) && isset($_POST['order']) && isset($_POST['city']) && isset($_POST['town']) && isset($_POST['adress'])) {
                $token = $_POST['token'];
                $order = json_decode($_POST['order']);
                $city = $_POST['city'];
                $town = $_POST['town'];
                $adress = $_POST['adress'];

                try {
                    $userModel = new UserModel();
                    $userInfo = $userModel->isAuth($token);
                    $orderModel = new OrderModel();
                    $orderInfo = $orderModel->getMenu();

                    if (!empty($userInfo) && ($token == $userInfo[0]["token"])) {
                        if (!empty($orderInfo)) {
                            $totalPrice = 0;
                            $totalTax = 0;
                            $mailorderpart = '  ';
                            // Loop through the order items and calculate the price
                            //var_dump($order);
                            foreach ($order as $itemId => $quantity) {
                                //var_dump($itemId);
                                foreach ($orderInfo as $meal) {
                                    if ($meal['id'] == $itemId) {
                                        $mealPrice = $meal['price'];
                                        $totalPrice += $mealPrice * $quantity;
                                        $mailorderpart .= '
                                        <tr>
                                            <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">
                                                ' . $meal['foodname'] . '
                                            </td>
                                            <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">
                                                ' . $quantity . ' x $' . $mealPrice . '
                                            </td>
                                        </tr>
                                        ';
                                        break;
                                    }
                                }
                            }

                            if ($totalPrice > 0) {
                                $totalTax = ($totalPrice * 18 / 100);
                                $totalPrice += $totalTax;
                            }

                            $mailhead = '
                                    <html>
                                    <title>Order Confirmation</title>
                                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                                    <meta name="viewport" content="width=device-width, initial-scale=1">
                                    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                                    <style type="text/css">
                                    
                                    body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
                                    table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
                                    img { -ms-interpolation-mode: bicubic; }
                                    
                                    img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
                                    table { border-collapse: collapse !important; }
                                    body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
                                    
                                    
                                    a[x-apple-data-detectors] {
                                        color: inherit !important;
                                        text-decoration: none !important;
                                        font-size: inherit !important;
                                        font-family: inherit !important;
                                        font-weight: inherit !important;
                                        line-height: inherit !important;
                                    }
                                    
                                    @media screen and (max-width: 480px) {
                                        .mobile-hide {
                                            display: none !important;
                                        }
                                        .mobile-center {
                                            text-align: center !important;
                                        }
                                    }
                                    div[style*="margin: 16px 0;"] { margin: 0 !important; }
                                    </style>
                                    <body style="margin: 0 !important; padding: 0 !important; background-color: #eeeeee;" bgcolor="#eeeeee">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
                                            
                                            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                                                <tr>
                                                    <td align="leftr" valign="top" style="font-size:0; padding: 35px;" bgcolor="#F44336">
                                                
                                                    <div style="display:inline-block; max-width:100%; min-width:100px; vertical-align:top; width:100%;">
                                                        <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:300px;">
                                                            <tr>
                                                                <td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 36px; font-weight: 800; line-height: 48px;" class="mobile-center">
                                                                    <h1 style="font-size: 36px; font-weight: 800; margin: 0; color: #ffffff;">Nyan Cat Asian House</h1>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                
                                                    </td>
                                                </tr>
                                ';
                                $mailbodycustomer = '
                                    <tr>
                                    <td align="center" style="padding: 35px 35px 20px 35px; background-color: #ffffff;" bgcolor="#ffffff">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                                        <tr>
                                            <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;">
                                                <img src="https://img.icons8.com/carbon-copy/100/000000/checked-checkbox.png" width="125" height="120" style="display: block; border: 0px;" /><br>
                                                <h2 style="font-size: 30px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;">
                                                    Thank You For Your Order!
                                                </h2>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" style="padding-top: 20px;">
                                                <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                ';
                                $mailbodybusiness = '
                                <tr>
                                <td align="center" style="padding: 35px 35px 20px 35px; background-color: #ffffff;" bgcolor="#ffffff">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                                    <tr>
                                        <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;">
                                            <img src="https://img.icons8.com/carbon-copy/100/000000/checked-checkbox.png" width="125" height="120" style="display: block; border: 0px;" /><br>
                                            <h2 style="font-size: 30px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;">
                                                You Have a New Order!
                                            </h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" style="padding-top: 20px;">
                                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                ';

                                $mailbottom = '
                                                                    <tr>
                                                                        <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 5px 10px;">
                                                                            Sales Tax
                                                                        </td>
                                                                        <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 5px 10px;">
                                                                            $ '.$totalTax.' 
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" style="padding-top: 20px;">
                                                                <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                                                    <tr>
                                                                        <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
                                                                            TOTAL
                                                                        </td>
                                                                        <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
                                                                            $ '.$totalPrice.' 
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="center" height="100%" valign="top" width="100%" style="padding: 0 35px 35px 35px; background-color: #ffffff;" bgcolor="#ffffff">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:660px;">
                                                        <tr>
                                                            <td align="center" valign="top" style="font-size:0;">
                                                                <div style="display:inline-block; max-width:50%; min-width:240px; vertical-align:top; width:100%;">

                                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:300px;">
                                                                        <tr>
                                                                            <td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                                                                <p style="font-weight: 800;">Delivery Address</p>
                                                                                <p>'.$adress.'</p>
                                                                                <p>'.$town.' / '.$city.'</p>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            </td>
                                        </tr>
                                    </table>
                                        
                                    </body>
                                    </html>
                                ';

                            $mailfinalformcustomer = $mailhead . $mailbodycustomer . $mailorderpart . $mailbottom;
                            $mailfinalformbusiness = $mailhead . $mailbodybusiness . $mailorderpart . $mailbottom;
                                    
                            $mailStatusCustomer = $this->sendMail($mailfinalformcustomer, 'Nyan Cat Asian House - Order Confirmation', $userInfo[0]["mail"], $userInfo[0]["uname"], 'restaurant@domain');
                            $mailStatusBusiness = $this->sendMail($mailfinalformbusiness, 'New Order! - Nyan Cat Asian House', 'restaurant@domain', 'Nyan Cat Asian House', $userInfo[0]["mail"]);

                            if ($mailStatusCustomer == true && $mailStatusBusiness == true) {
                                $responseData = json_encode(array('token' => $token, 'Status' => 'Mail Sended'));
                                $this->sendOutput($responseData, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
                            } else {
                                $strErrorDesc = $mailbodycustomer . ' ' . $mailbodybusiness . ' Something went wrong! Please contact support.';
                                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                            }
                        } else {
                            $responseData = json_encode(array('error' => 'Something went wrong! Please contact support.'));
                            $this->sendOutput($responseData, array('Content-Type: application/json', 'HTTP/1.1 500 Unauthorized'));
                        }
                    } else {
                        // Invalid credentials
                        $responseData = json_encode(array('error' => 'Invalid credentials'));
                        $this->sendOutput($responseData, array('Content-Type: application/json', 'HTTP/1.1 401 Unauthorized'));
                    }
                } catch (Exception $e) {
                    $strErrorDesc = $e->getMessage() . ' Something went wrong! Please contact support.';
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

    protected function sendMail($mailbody, $mailsubject, $sendto, $cusname, $replyto)
    {
        try {
            $mail = new PHPMailer(true);

            //SMTP Sunucu Ayarları
            $mail->SMTPDebug = 0; // DEBUG Kapalı: 0, DEBUG Açık: 2
            $mail->isSMTP();
            $mail->Host = 'smtp.yandex.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'MAIL ADRESS';
            $mail->Password = 'SMTP PASSWORD';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->setFrom('MAIL ADRESS', 'MAIL ADRESS NAME');

            //Alici Ayarları
            $mail->addAddress($sendto, $cusname);
            $mail->addReplyTo($replyto);

            // İçerik
            $mail->isHTML(true);
            $mail->CharSet = 'utf-8';
            $mail->Subject = $mailsubject;
            $mail->Body = $mailbody;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return "Error: {$mail->ErrorInfo}";
        }
    }
}

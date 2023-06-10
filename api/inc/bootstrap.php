<?php
    define("PROJECT_ROOT_PATH", __DIR__ . "/../");
    // include main configuration file 
    require_once PROJECT_ROOT_PATH . "/inc/config.php";
    // include the base controller file 
    require_once PROJECT_ROOT_PATH . "/Controller/Api/BaseController.php";
    // include the use model file 
    require_once PROJECT_ROOT_PATH . "/Model/UserModel.php";
    // include the use order file
    require_once PROJECT_ROOT_PATH . "/Model/OrderModel.php";
    // include the use reservation file
    require_once PROJECT_ROOT_PATH . "/Model/ReservationModel.php";
    // // include PHPMailer
    // require_once PROJECT_ROOT_PATH . "/PHPMailer/Exception.php";
    // require_once PROJECT_ROOT_PATH . "/PHPMailer/PHPMailer.php";
    // require_once PROJECT_ROOT_PATH . "/PHPMailer/SMTP.php";
?>
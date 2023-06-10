<?php
    require __DIR__ . "/inc/bootstrap.php";
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );
    if ((isset($uri[2]) && $uri[2] != 'api') || !isset($uri[4])) {
        header("HTTP/1.1 404 Not Found");
        exit();
    }
    if($uri[4]=='user'){
        require PROJECT_ROOT_PATH . "/Controller/Api/UserController.php";
        $objFeedController = new UserController();
        $strMethodName = $uri[5] . 'Action';
        $objFeedController->{$strMethodName}();
    } elseif ($uri[4]=='order'){
        require PROJECT_ROOT_PATH . "/Controller/Api/OrderController.php";
        $objFeedController = new OrderController();
        $strMethodName = $uri[5] . 'Action';
        $objFeedController->{$strMethodName}();
    } elseif ($uri[4]=='reservation'){
        require PROJECT_ROOT_PATH . "/Controller/Api/ReservationController.php";
        $objFeedController = new ReservationController();
        $strMethodName = $uri[5] . 'Action';
        $objFeedController->{$strMethodName}();
    } else {
        header("HTTP/1.1 404 Not Found");
        exit();
    }
?>
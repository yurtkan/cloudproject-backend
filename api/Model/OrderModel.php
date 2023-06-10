<?php
    require_once PROJECT_ROOT_PATH . "/Model/Database.php";
    class OrderModel extends Database
    {
        public function getMenu()
        {
            return $this->select("SELECT * FROM menu ORDER BY id");
        }
    }
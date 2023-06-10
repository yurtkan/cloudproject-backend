<?php
    require_once PROJECT_ROOT_PATH . "/Model/Database.php";
    class ReservationModel extends Database
    {
        public function getTables()
        {
            return $this->select("SELECT * FROM tables ORDER BY id");
        }

        public function setTable($tid, $tstatus, $ttstart, $ttend, $trdate, $customer){
            return $this->update("UPDATE `tables` SET `status` = ?, `timeStart` = ?, `timeEnd` = ?, `resDate` = ?, `customer` = ? WHERE `tables`.`id` = ?;",["ssssss", $tstatus, $ttstart, $ttend, $trdate, $customer, $tid]);
        }

        // public function getinfo($mail)
        // {
        //     return $this->select("SELECT * FROM users WHERE mail = ?", ["s",$mail]);
        // }

        // public function insertUser($mail, $pass, $uname, $token)
        // {
        //     return $this->insert("INSERT INTO users (mail, pass, uname, token) VALUES (?, ?, ?, ?)", ["ssss", $mail, $pass, $uname, $token]);
        // }
        
        // public function isAuth($token)
        // {
        //     return $this->select("SELECT * FROM users WHERE token = ?", ["s",$token]);
        // }
    }
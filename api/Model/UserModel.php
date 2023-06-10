<?php
    require_once PROJECT_ROOT_PATH . "/Model/Database.php";
    class UserModel extends Database
    {
        public function getUsers($limit)
        {
            return $this->select("SELECT * FROM users ORDER BY id ASC LIMIT ?", ["i", $limit]);
        }

        public function getinfo($mail)
        {
            return $this->select("SELECT * FROM users WHERE mail = ?", ["s",$mail]);
        }

        public function insertUser($mail, $pass, $uname, $token)
        {
            return $this->insert("INSERT INTO users (mail, pass, uname, token) VALUES (?, ?, ?, ?)", ["ssss", $mail, $pass, $uname, $token]);
        }
        
        public function isAuth($token)
        {
            return $this->select("SELECT * FROM users WHERE token = ?", ["s",$token]);
        }
    }
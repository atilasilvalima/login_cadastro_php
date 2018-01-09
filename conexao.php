<?php

    /**
     * Class Conexao
     *
     * Cria uma conexao simples com o banco de dados MySQL (POO)
     *
     */
    class Conexao {
        private $hostname;
        private $username;
        private $password;
        private $dbname;
        private $db;

        /**
         * Conexao constructor.
         * @param $hostname
         * @param $username
         * @param $password
         * @param $dbname
         */
        public function __construct(string $hostname, string $username, string $password, string $dbname='') {
            $this->setHostname($hostname);
            $this->setUsername($username);
            $this->setPassword($password);
            $this->setDbname($dbname);
        }

        /**
         * @return mixed
         */
        public function getHostname(): string {
            return $this->hostname;
        }

        /**
         * @param mixed $hostname
         */
        public function setHostname($hostname): void {
            $this->hostname = $hostname;
        }

        /**
         * @return mixed
         */
        public function getUsername(): string {
            return $this->username;
        }

        /**
         * @param mixed $username
         */
        public function setUsername($username): void {
            $this->username = $username;
        }

        /**
         * @return mixed
         */
        public function getPassword(): string {
            return $this->password;
        }

        /**
         * @param string $password
         */
        public function setPassword(string $password): void {
            $this->password = $password;
        }

        /**
         * @return mixed
         */
        public function getDbname(): string {
            return $this->dbname;
        }

        /**
         * @param mixed $dbname
         */
        public function setDbname($dbname): void {
            $this->dbname = $dbname;
        }

        /**
         * @return mixed
         */
        public function getDb(): mysqli {
            return $this->db;
        }

        /**
         * @param mixed $db
         */
        public function setDb(mysqli $db): void {
            $this->db = $db;
        }

        /**
         * @param string $dbname
         */
        public function selecionarDb(string $dbname): void {
            $this->db->select_db($dbname);
        }

        public function conectar(): mysqli {
            $mysqli = new mysqli(
                $this->hostname,
                $this->username,
                $this->password,
                $this->dbname
            );

            if(!$mysqli) {
                die($mysqli->connect_errno . ':' . $mysqli->connect_error);
                return $mysqli;
            } else {
                $this->db = $mysqli;
                return $mysqli;
            }
        }

        public function desconectar(): void {
            $this->db->close();
        }
    }
?>
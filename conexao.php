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
            $this->hostname = $hostname;
            $this->username = $username;
            $this->password = $password;
            $this->dbname = $dbname;
        }

        /**
         * @return string
         */
        public function getHostname(): string {
            return $this->hostname;
        }

        /**
         * @param string $hostname
         */
        public function setHostname(string $hostname): void {
            $this->hostname = $hostname;
        }

        /**
         * @return string
         */
        public function getUsername(): string {
            return $this->username;
        }

        /**
         * @param string $username
         */
        public function setUsername(string $username): void {
            $this->username = $username;
        }

        /**
         * @return string
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
         * @return string
         */
        public function getDbname(): string {
            return $this->dbname;
        }

        /**
         * @param string $dbname
         */
        public function setDbname(string $dbname): void {
            $this->dbname = $dbname;
        }

        /**
         * @return mysqli
         */
        public function getDb(): mysqli {
            return $this->db;
        }

        /**
         * @param mysqli $db
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

        /**
         * @return mysqli
         */
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
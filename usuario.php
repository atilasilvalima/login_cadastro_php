<?php
    /**
     * Class Usuario
     *
     * Cria um usuário simples contendo nome, email, senha e id.
     * As entradas de dados devem ser filtradas fora do objeto, assim permitindo
     * total liberdade do programador em manipular seus dados sem depedencias da classe.
     *
     */
    class Usuario {
        private $id;
        private $nome;
        private $email;
        private $senha;

        /**
         * Usuario constructor.
         * @param $nome
         * @param $email
         * @param $senha
         */
        public function __construct(string $nome = '', string $email = '', string $senha = '', int $id = 0) {
            $this->nome = $nome;
            $this->email = $email;
            $this->senha = $senha;
            $this->id = $id;
        }

        /**
         * @return string
         */
        public function getNome(): string {
            return $this->nome;
        }

        /**
         * @param string $nome
         */
        public function setNome(string $nome): void {
            $this->nome = $nome;
        }

        /**
         * @return string
         */
        public function getEmail(): string {
            return $this->email;
        }

        /**
         * @param string $email
         */
        public function setEmail(string $email): void {
            $this->email = $email;
        }

        /**
         * @return string
         */
        public function getSenha(): string {
            return $this->senha;
        }

        /**
         * @param string $senha
         */
        public function setSenha(string $senha): void {
            $this->senha = $senha;
        }

        /**
         * @param string $senha
         */
        public function setSenhaCriptografada(string $senha): void {
            $this->senha = password_hash($senha, PASSWORD_DEFAULT);
        }

        public function criptografarSenhaAtual(): void {
            $this->senha = password_hash($this->senha, PASSWORD_DEFAULT);
        }

        /**
         * @return int
         */
        public function getId(): int {
            return $this->id;
        }

        /**
         * @param int $id
         */
        public function setId(int $id): void {
            $this->id = $id;
        }

        /**
         * Converte a classe para JSON
         *
         * @return string
         */
        public function jsonSerialize(): string {
            return json_encode(get_object_vars($this));
        }

        /**
         * return 0 -> usuario cadastrado com sucesso 'cd'
         * return 1 -> usuario ja cadastrado '^cd'
         * return -1 -> erro na execucao das querys (pode ser consultada na variavel $db->error) 'er'
         *
         * @param mysqli $db
         * @param bool $criptograr
         * @return int
         */
        public function cadastrar(mysqli $db, bool $criptograrSenha = false): int {
            $sql = $db->prepare("SELECT email FROM usuarios WHERE email = ?");

            if ($sql) {
                $sql->bind_param('s', $this->email);
                $sql->execute();
                $sql->store_result();

                if ($sql->num_rows > 0) {
                    $sql->close();
                    return 1;

                } else {
                    $sql->close();
                    $sql = $db->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?,?,?)");

                    if ($sql) {
                        if ($criptograrSenha) {
                            $this->criptografarSenhaAtual();
                        }
                        $sql->bind_param('sss', $this->nome, $this->email, $this->senha);
                        $sql->execute();
                        $sql->close();
                        return 0;

                    } else {
                        return -1;
                    }
                }

            } else {
                return -1;
            }
        }

        /**
         * return 0 -> usuario logado com sucesso 'lg'
         * return 1 -> usuario (email) não cadastrado '^em'
         * return 2 -> senha incorreta '^pw'
         * return -1 -> erro na execucao da query (pode ser consultada na variavel $db->error) 'er'
         *
         * @param mysqli $db
         * @param string $email
         * @param string $senha
         * @return int
         */
        public function logar(mysqli $db, string $email, string $senha): int {
            $sql = $db->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");

            if ($sql) {
                $sql->bind_param('s', $email);
                $sql->execute();
                $sql->bind_result($id, $nome, $senhaCriptografada);
                $sql->store_result();

                if ($sql->num_rows > 0) {
                    $sql->fetch();

                    if(password_verify($senha, $senhaCriptografada)) {
                        $this->nome = $nome;
                        $this->email = $email;
                        $this->senha = $senhaCriptografada;
                        $this->id = $id;
                        $sql->close();
                        return 0;

                    } else {
                        $sql->close();
                        return 2;
                    }

                } else {
                    $sql->close();
                    return 1;
                }

            } else {
                return -1;
            }
        }
    }
?>
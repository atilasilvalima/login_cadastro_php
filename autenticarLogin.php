<?php
    include ('conexao.php');
    include ('usuario.php');

    /*Certifica-se que o metodo de request eh do tipo post*/
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        /**
         * Averigua os dados de entrada fornecidos pelo usuario
         *
         * @param string $email
         * @param string $senha
         * @return string
         */
        $validarDados = function (string $email, string $senha): string {
            if (empty($email) || empty($senha)) {
                return 'Preencha todos os campos!';
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return 'Email inválido!';
            }

            /*Qualquer caractere de tamanho minimo 8 e maximo 16*/
            if(!preg_match("/^.{8,16}$/", $senha)) {
                return 'Senha muito curta, utilize no mínimo 8 e no máximo 16 caracteres';
            }

            return '';
        };

        $mensagemError = $validarDados($email, $senha);

        /*Caso haja alguma mensagem, entende-se que houve um erro*/
        if(!$mensagemError) {
            $conexao = new Conexao('localhost', 'root', '', 'db_teste');
            $db = $conexao->conectar();
            $db->set_charset('UTF8');

            $sql = $db->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");

            if ($sql) {
                $sql->bind_param('s', $email);
                $sql->execute();
                $sql->bind_result($id, $nome, $senhaCriptografada);
                $sql->store_result();

                if ($sql->num_rows > 0) {
                    $sql->fetch();

                    if(password_verify($senha, $senhaCriptografada)) {
                        $usuario = new Usuario($nome, $email, $senhaCriptografada, $id);
                        $sql->close();

                        /*Inicia uma sessao que contem o Usuario no formato JSON*/
                        session_start();
                        $_SESSION['usuarioLogado'] = $usuario->jsonSerialize();

                        /*Print Usuario JSON*/
                        echo $_SESSION['usuarioLogado'];

                        /*Redireciona para a pagina desejada*/
                        echo "<script> alert('Login efetuado com sucesso!') </script>";
                        echo "<script> location.href='#logado' </script>";

                    } else {
                        echo "<script> alert('Senha incorreta!') </script>";
                        echo "<script> location.href='login.html' </script>";
                    }

                } else {
                    echo "<script> alert('Este email não está cadastrado') </script>";
                    echo "<script> location.href='login.html' </script>";
                }

            } else {
                //TODO enviar email de alerta contendo $db->error
                echo "<script> alert('Ops! Houve um erro, tente novamente mais tarde!') </script>";
                echo "<script> location.href='login.html' </script>";
            }

            $db->close();

        } else {
            echo "<script> alert('$mensagemError') </script>";
            echo "<script> location.href='login.html' </script>";
        }

        unset($conexao);
        unset($usuario);
    }
?>
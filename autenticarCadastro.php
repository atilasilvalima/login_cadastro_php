<?php
    include ('conexao.php');
    include ('usuario.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $confirmaSenha = $_POST['confirmaSenha'] ?? '';

        /**
         * A validacao das entradas fica por conta do programador
         *
         * @param string $nome
         * @param string $email
         * @param string $senha
         * @param string $confirmaSenha
         * @return string
         */
        $validarDados = function (string $nome, string $email, string $senha, string $confirmaSenha): string {
            if (empty($nome) || empty($email) || empty($senha) || empty($confirmaSenha)) {
                return 'Preencha todos os campos!';
            }

            if(!preg_match("/^[A-Za-zÀ-ÿ\s]+$/", $nome)) {
                return 'Não utilize caracteres especiais! (Apenas acentos são permitidos)';
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return 'Email inválido!';
            }

            if(!preg_match("/^.{8,16}$/", $senha)) {
                return 'Senha muito curta, utilize no mínimo 8 e no máximo 16 caracteres';
            }

            if ($confirmaSenha !== $senha) {
                return 'Confirmação de senha inválida!';
            }

            return '';
        };

        $mensagemError = $validarDados($nome, $email, $senha, $confirmaSenha);

        /*Caso haja alguma mensagem, entende-se que houve um erro*/
        if(!$mensagemError) {
            $conexao = new Conexao('localhost', 'root', '', 'db_teste');
            $db = $conexao->conectar();
            $db->set_charset('UTF8');
            $usuario = new Usuario($nome, $email, $senha);

            $resultadoCadastro = $usuario->cadastrar($db, true);

            if($resultadoCadastro == 0) {
                //TODO enviar email de confirmacao de cadastro
                echo "<script> alert('Usuário cadastrado com sucesso!') </script>";
                echo "<script> location.href='login.html' </script>";

            } else if($resultadoCadastro == 1){
                echo "<script> alert('Este usuário já está cadastrado') </script>";
                echo "<script> location.href='cadastro.html' </script>";

            } else {
                //TODO enviar email de alerta contendo $db->error
                echo "<script> alert('Ops! Houve um erro, tente novamente mais tarde!') </script>";
                echo "<script> location.href='cadastro.html' </script>";
            }

            $db->close();

        } else {
            echo "<script> alert('$mensagemError') </script>";
            echo "<script> location.href='cadastro.html' </script>";
        }

        unset($conexao);
        unset($usuario);
    }
?>
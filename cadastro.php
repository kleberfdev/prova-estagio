<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de usuário</title>
</head>
<body>
<h1>Cadastro de usuário</h1>
<form method="post" action="cadastro.php">
    <label for="username">Nome de usuário:</label>
    <input type="text" id="username" name="nome"><br>
    <label for="password">Senha:</label>
    <input type="password" id="password" name="senha"><br>
    <input type="submit" value="Cadastrar">
</form>
<p>Você é cadastrado então faça login. <a href="login.php">Clique aqui</a> para voltar à tela de login.</p>

<?php
// Conectar ao banco de dados MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd";
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar se a conexão foi bem sucedida
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Coletar as informações do formulário de cadastro
if (isset($_POST["nome"]) && isset($_POST["senha"])) {
    $nome = $_POST["nome"];
    $senha = $_POST["senha"];

    // Verificar se já existe um usuário com o mesmo nome e senha na tabela
    $sql = "SELECT * FROM mensageiro WHERE nome = '$nome' AND senha = '$senha'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Já existe um usuário com o mesmo nome e senha, exibir uma mensagem de erro
        echo "Já existe um usuário com o mesmo nome e senha.";
    } else {
        // Inserir o novo usuário na tabela de usuários
        $sql = "INSERT INTO mensageiro (id, nome, senha) VALUES (NULL, '$nome', '$senha')";

        if ($conn->query($sql) === TRUE) {
            // Se o cadastro foi bem sucedido, redirecionar o usuário para a página de login com uma mensagem de sucesso armazenada na sessão
            session_start();
            $_SESSION["success_message"] = "Cadastro realizado com sucesso. Faça o seu login.";
            header("Location: login.php");
        } else {
            echo "Erro ao cadastrar o usuário: " . $conn->error;
        }
    }
}

// Fechar a conexão com o banco de dados
$conn->close();
?>

</body>
</html>

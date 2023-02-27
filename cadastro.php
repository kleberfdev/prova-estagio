<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastre-se</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1><form>Cadastre-se</form></h1>
<form method="post" action="cadastro.php" class="form">
    <label for="username" class="label">Nome de Mensageiro:</label>
    <input type="text" id="username" name="nome" class="input-text"><br>
    <label for="password" class="label">Senha:</label>
    <input type="password" id="password" name="senha" class="input-password"><br>
    <input type="submit" value="Cadastrar" class="input-submit">

<?php
if (isset($_POST["nome"]) && isset($_POST["senha"])) {
    // Se houver uma mensagem de erro armazenada na sessão, exibi-la com a classe 'error'
    session_start();
    if (isset($_SESSION["error_message"])) {
        echo '<div class="error">' . $_SESSION["error_message"] . '</div>';
        unset($_SESSION["error_message"]);
    }
}
?>
<p>Já é cadastrado? Faça login. <a href="login.php" class="link-cadastro">Clique aqui</a></p>
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
        echo "<form><br>Já existe um Mensageiro com o mesmo nome e senha.";
    } else {
        // Inserir o novo usuário na tabela de usuários
        $sql = "INSERT INTO mensageiro (id, nome, senha) VALUES (NULL, '$nome', '$senha')";

        if ($conn->query($sql) === TRUE) {
            // Se o cadastro foi bem sucedido, redirecionar o usuário para a página de login com uma mensagem de sucesso armazenada na sessão
            session_start();
            $_SESSION["success_message"] = "<form>Cadastro realizado com sucesso. Faça o seu login.";
            header("Location: login.php");
        } else {
            echo "Erro ao cadastrar o usuário: " . $conn->error;
        }
    }
}

// Fechar a conexão com o banco de dados
$conn->close();
?>


</form>
</body>
</html>

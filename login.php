<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
// Verificar se o formulário de login foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

// Coletar as informações do formulário de login
$username = $_POST["username"];
$password = $_POST["password"];

// Consultar o banco de dados para verificar se as credenciais de login são válidas
// Consultar o banco de dados para verificar se as credenciais de login são válidas
$sql = "SELECT * FROM mensageiro WHERE nome = '$username' AND senha = '$password'";
$result = $conn->query($sql);

// Verificar se o resultado da consulta retornou um registro
if ($result->num_rows == 1) {
    // Credenciais de login válidas, obter o id do usuário e redirecionar para a página principal
    $row = $result->fetch_assoc();
    session_start();
    $_SESSION['usuario'] = $username;
    $_SESSION['id_usuario'] = $row['id'];
    header("Location: index.php");
    exit();
} else {
    // Credenciais de login inválidas, definir a mensagem de erro
    $error_message = "Nome de usuário ou senha incorretos.";
}


// Fechar a conexão com o banco de dados
$conn->close();
}
?>

<h1><form>Login</form></h1>
<?php
// Verifica se a mensagem de sucesso está definida na sessão
session_start();
if(isset($_SESSION["success_message"])) {
    echo "<p>".$_SESSION["success_message"]."</p>";
    // Remove a mensagem de sucesso da sessão
    unset($_SESSION["success_message"]);
}
?>
<form method="post" action="login.php">
    <label for="username">Mensageiro:</label>
    <input type="text" id="username" name="username"><br>
    <label for="password">Senha:</label>
    <input type="password" id="password" name="password">
    <?php if(isset($error_message)) { ?>
        <p style="color:red;"><?php echo $error_message; ?></p>
    <?php } ?>
    <br>
    <input type="submit" value="Entrar">

<p>Não tem cadastro? <a href="cadastro.php">Cadastre-se</a>.</p>
<?php
if(isset($_GET['cadastro']) && $_GET['cadastro'] == 'sucesso') {
    echo "<p>Cadastro realizado com sucesso. Faça o seu login.</p>";
}

?>
</form>
</body>
</html>

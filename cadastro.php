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
</form>
</body>
</html>

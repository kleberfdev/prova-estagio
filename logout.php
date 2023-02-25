<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<?php
session_start();
session_destroy();
header("Location: login.php"); // redireciona para a página de login
exit;
?>

</body>
</html>
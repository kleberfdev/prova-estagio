<body>
<?php
session_start();
session_destroy();
header("Location: login.php"); // redireciona para a página de login
exit;
?>

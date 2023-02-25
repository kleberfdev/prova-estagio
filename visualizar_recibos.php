<?php
// Iniciar a sessão
session_start();

// Verificar se o usuário não está autenticado
if (!isset($_SESSION['usuario'])) {
    // Redirecionar para a página de login
    header("Location: login.php");
    exit();
}

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

// Processar a requisição de visualização de recibos
if (isset($_POST["data"])) {
    $data = $_POST["data"];
    $sql = "SELECT * FROM movimento_diario WHERE data_movimento = '$data'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Data do Movimento</th><th>Recibo</th><th>Valor</th><th>Status</th><th>Ações</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["data_movimento"] . "</td>";
            echo "<td>" . $row["recibo"] . "</td>";
            echo "<td>" . $row["valor"] . "</td>";
            echo "<td>" . $row["status"] . "</td>";
            echo "<td><a href='detalhes_recibo.php?recibo=" . $row["recibo"] . "'>Detalhes</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Não foram encontrados recibos para a data selecionada.";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<form method="post" action="visualizar_recibos.php">
    <label for="data">Selecione a data:</label>
    <input type="date" id="data" name="data">
    <input type="submit" value="Visualizar">
</form>
<a href="logout.php">Sair</a>
</body>
</html>

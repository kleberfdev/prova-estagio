<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<style>
  h2 {
    color: #800000;
  }
  p {
    color: #000;
    font-size: 18px;
    margin-bottom: 10px;
  }
</style>
</body>
</html>
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

// Verificar se foi passado o parâmetro "contribuinte_id" na URL
if (isset($_GET["contribuinte_id"])) {
    $contribuinte_id = $_GET["contribuinte_id"];

    // Selecionar o contribuinte correspondente na tabela "contribuinte"
    $sql = "SELECT * FROM contribuinte WHERE id = '$contribuinte_id'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = $result->fetch_assoc();

        if ($result->num_rows > 0) {
            // Exibir os dados do contribuinte
            // Atribuir o resultado da consulta à variável $row

            echo "<h2>Dados do Contribuinte</h2>";
            echo "<p>Nome: " . $row['nome'] . "</p>";
            echo "<p>Telefone: " . $row['telefone'] . "</p>";
            echo "<p>Endereço: " . $row['endereco'] . "</p>";
        } else {
            echo "Contribuinte não encontrado.";
        }
    }
} else {
    echo "Parâmetro 'contribuinte_id' não encontrado na URL.";
}
?>

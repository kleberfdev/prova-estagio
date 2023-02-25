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

// Processar o envio do formulário de lançamento de recebimento
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["recibo"]) && isset($_POST["data_movimento"]) && isset($_POST["valor"])) {
    // Obter os dados enviados pelo formulário
    $recibo = $_POST["recibo"];
    $data_movimento = $_POST["data_movimento"];
    $valor = $_POST["valor"];

    // Inserir os dados na tabela "movimento_diario"
    $sql = "INSERT INTO movimento_diario (recibo, data_movimento, valor, status) VALUES ('$recibo', '$data_movimento', $valor, 'pendente')";
    if ($conn->query($sql) === TRUE) {
        echo "Dados do recibo foram inseridos com sucesso!";
    } else {
        echo "Erro ao inserir os dados do recibo: " . $conn->error;
    }
}

// Fechar a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<h2>Cadastro de Recibos</h2>
<form action="cadastrar_recibo.php" method="POST">
    <label for="data_prevista">Data Prevista:</label>
    <input type="date" name="data_prevista" required><br><br>
    <label for="status">Status:</label>
    <select name="status">
        <option value="Aberto">Aberto</option>
        <option value="Pago">Pago</option>
        <option value="Cancelado">Cancelado</option>
    </select><br><br>
    <label for="data_recebimento">Data de Recebimento:</label>
    <input type="date" name="data_recebimento"><br><br>
    <label for="contribuinte_nome">Nome do Contribuinte:</label>
    <input type="text" name="contribuinte_nome" required><br><br>
    <label for="contribuinte_endereco">Endereço do Contribuinte:</label>
    <input type="text" name="contribuinte_endereco"><br><br>
    <label for="contribuinte_telefone">Telefone do Contribuinte:</label>
    <input type="text" name="contribuinte_telefone"><br><br>
    <label for="tipo_pagamento">Tipo de Pagamento:</label>
    <select name="tipo_pagamento">
        <option value="Crédito">Crédito</option>
        <option value="Débito">Débito</option>
        <option value="Pix">Pix</option>
    </select><br><br>
    <button type="submit">Cadastrar Recibo</button>
</form>

<br>
<a href="visualizar_recibos.php">Visualizar recibos gerados</a>
<br>
<a href="logout.php">Sair</a>
</body>
</html>

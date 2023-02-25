<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<?php
// Incluir o arquivo de conexão com o banco de dados
include 'conexao.php';

// Receber o número do recibo através do método GET
if (!isset($_GET['recibo'])) {
    // Redirecionar de volta para a página de visualização de recibos
    header("Location: visualizar_recibos.php");
    exit();
}

$recibo = $_GET['recibo'];

// Verificar se o formulário foi enviado
if (isset($_POST['status'])) {
    $novo_status = $_POST['status'];

    // Atualizar o registro correspondente na tabela "movimento_diario"
    $sql = "UPDATE movimento_diario SET status = '$novo_status' WHERE recibo = '$recibo'";

    if ($conn->query($sql) === TRUE) {
        echo "Status do recibo atualizado com sucesso.\n";
    } else {
        echo "Erro ao atualizar o status do recibo: " . $conn->error;
    }
}

// Buscar as informações do recibo
$sql = "SELECT * FROM movimento_diario WHERE recibo = '$recibo'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Exibir as informações do recibo
    $row = $result->fetch_assoc();
    echo "Recibo número " . $row['recibo'] . "<br>";
    echo "Valor: R$" . $row['valor'] . "<br>";
    echo "Data de movimento: " . $row['data_movimento'] . "<br>";
    echo "Data prevista: " . $row['data_prevista'] . "<br>";
    echo "Data de recebimento: " . $row['data_recebimento'] . "<br>";
    echo "Mensageiro: " . $row['id_mensageiro'] . "<br>";
    echo "Tipo de pagamento: " . $row['id_tipo_pagamento'] . "<br>";
    echo "Status: " . $row['status'] . "<br>";

    // Formulário para atualizar o status do recibo
    echo "<form method='post'>";
    echo "<label for='status'>Destino do recibo:</label>";
    echo "<select name='status' id='status'>";
    echo "<option value='Pendente' " . ($row['status'] == 'Pendente' ? 'selected' : '') . ">Pendente</option>";
    echo "<option value='Recebido' " . ($row['status'] == 'Recebido' ? 'selected' : '') . ">Recebido</option>";
    echo "<option value='Cancelado' " . ($row['status'] == 'Cancelado' ? 'selected' : '') . ">Cancelado</option>";
    echo "</select>";
    echo "<br><br>";
    echo "<input type='submit' value='Atualizar'>";
    echo "</form>";

} else {
    echo "Nenhum recibo encontrado.";
}

// Fechar a conexão com o banco de dados
$conn->close();
?>

</body>
</html>
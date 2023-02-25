<?php
// Iniciar a sessão
session_start();
if (isset($_SESSION["success_message"])) {
    echo $_SESSION["success_message"];
    unset($_SESSION["success_message"]);
}

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

echo "<h2>Incluir Contribuição</h2>";
// Processar o envio do formulário de busca

    // Obter o número do recibo fornecido pelo formulário de busca

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        echo "<h2>Buscar Contribuição</h2>";
        echo "<form method='GET' action=''>";
        echo "<label for='numero-recibo'>Número do Recibo:</label>";
        echo "<input type='text' name='numero-recibo' id='numero-recibo'>";
        echo "<br><br>";
        echo "<input type='submit' value='Buscar'>";
        echo "</form>";
    
        if(isset($_GET['numero-recibo'])) {
            $recibo = $_GET['numero-recibo'];
    
            // Selecionar a contribuição correspondente na tabela "contribuicao"
            $sql = "SELECT c.recibo, c.valor, c.data_prevista, m.nome AS nome_mensageiro, tp.nome AS nome_tipo_pagamento, c.status, co.nome AS nome_contribuinte, c.id_contribuinte
            FROM contribuicao c
            INNER JOIN mensageiro m ON c.id_mensageiro = m.id
            INNER JOIN tipos_pagamento tp ON c.id_tipo_pagamento = tp.id
            INNER JOIN contribuinte co ON c.id_contribuinte = co.id
            WHERE c.recibo = '$recibo'";

    
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $row = $result->fetch_assoc();
            
                if ($result->num_rows > 0) {
                    // Exibir os dados da contribuição
                    // Atribuir o resultado da consulta à variável $row
            
                    echo "<h2>Dados da Contribuição</h2>";
                    echo "<p>Recibo: " . $row['recibo'] . "</p>";
                    echo "<p>Valor: " . $row['valor'] . "</p>";
                    echo "<p>Data Prevista: " . $row['data_prevista'] . "</p>";
                    echo "<p>Mensageiro: " . $row['nome_mensageiro'] . "</p>";
                    echo "<p>Tipo de Pagamento: " . $row['nome_tipo_pagamento'] . "</p>";
                    echo "<p>Status: " . $row['status'] . "</p>";
                    echo "<p>Contribuinte: " . $row['nome_contribuinte'] . "</p>";
                    
                    // Adicionar o formulário de alteração de status e data de recebimento
                    echo "<h3>Alterar Status e Data de Recebimento</h3>";
                    echo "<form method='POST' action=''>";
                    echo "<input type='hidden' name='recibo' value='" . $row["recibo"] . "'>";
                    echo "<label for='status'>Status:</label>";
                    echo "<select name='status' id='status'>";
                    echo "<option value='Pendente'>Pendente</option>";
                    echo "<option value='Recebido'>Recebido</option>";
                    echo "<option value='Cancelado'>Cancelado</option>";
                    echo "</select>";
                    echo "<br><br>";
                    echo "<label for='data-recebimento'>Data de Recebimento:</label>";
                    echo "<input type='date' name='data-recebimento' id='data-recebimento'>";
                    echo "<br><br>";
                    echo "<input type='submit' name='submit' value='Alterar'>";
                    echo "</form>";
                    echo "<a href='visualizar_detalhes.php?contribuinte_id=" . $row['id_contribuinte'] . "' target='_blank'>Visualizar Detalhes</a>";
            
                } else {
                    echo "Contribuição não encontrada.";
                }
            }
            
    }


    }

        
        
    // Processar o envio do formulário de alteração de status e data de recebimento
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"]) && $_POST["submit"] == "Alterar" && isset($_POST["recibo"])) {
        // Obter as informações fornecidas pelo formulário de alteração de status e data de recebimento
        $recibo = $_POST["recibo"];
        $status = $_POST["status"];
        $data_recebimento = $_POST["data-recebimento"];
    
        // Verificar se todos os campos obrigatórios foram preenchidos
        if (empty($status) || empty($data_recebimento)) {
            echo "Por favor, preencha todos os campos.";
        } else {
            // Verificar se o valor do status é válido
            if (!in_array($status, array("Pendente", "Recebido", "Cancelado"))) {
                echo "O valor do status é inválido.";
            } else {
                // Verificar se o valor da data de recebimento está em um formato válido
                $date = DateTime::createFromFormat('Y-m-d', $data_recebimento);
                if (!$date) {
                    echo "O valor da data de recebimento é inválido.";
                } else {
                    // Atualizar a contribuição correspondente na tabela "contribuicao"
                    $sql = "UPDATE contribuicao SET status = '$status' WHERE recibo = '$recibo'";
                    if ($conn->query($sql) === TRUE) {
                        // Exibir a mensagem de sucesso e redirecionar para a página inicial
                        session_start();
                        $_SESSION["success_message"] = "<p>Contribuição atualizada com sucesso.</p>";
                        header("Location: index.php");
                        exit; // Importante sair do script depois de redirecionar
                    } else {
                        echo "Erro ao atualizar a contribuição: " . $conn->error;
                    }
                }
            }
        }
    
    
        
}
// Fechar a conexão com o banco de dados MySQL

$conn->close();
    
?>
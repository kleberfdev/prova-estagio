<!DOCTYPE html>
<html lang="pt-br">
<head>

<title>Recibos</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    
</head>
<body>
<?php
    session_start();
// Verificar se o usuário está autenticado
if (isset($_SESSION['usuario'])) {

// Exibir informações do usuário
    echo "<br><br>";
    echo "<form>";
    echo "<table><th><h5>Mensageiro</th><th>ID</th>";
    echo "<tr><td><h5>" . $_SESSION ['usuario'] . "</th><td>".$_SESSION['id_usuario']."</th> ";
    echo "</table>";
    echo "</form>";
    echo "<br><br>";
}


if (!isset($_POST['data'])) {
  // Se o formulário não for enviado, armazena a última data na sessão
  if (isset($_SESSION['data'])) {
    $lastDate = $_SESSION['data'];
  } else {
    $lastDate = date('Y-m-d');
  }
} else {
  // Se o formulário for enviado, mantém o valor atual do campo de data do formulário
  $lastDate = $_POST['data'];
}
echo "<form>";
// Salva a última data na sessão
$_SESSION['data'] = $lastDate;

?>


<form method="post">
    <label><h2>Recibos</h2></label>
    <label for="data">Selecione a data:</label>
    <input type="date" id="data" name="data" value="<?php echo isset($_SESSION['data']) ? $_SESSION['data'] : date("Y-m-d"); ?>">
    <input type="submit" value="Visualizar">    
<?php
date_default_timezone_set('America/Sao_Paulo');


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

// Coletar a data selecionada pelo mensageiro
if (isset($_POST["data"])) {
    $data = $_POST["data"];
} else {
    // Se a data não foi selecionada, usar a data atual
    $data = date("Y-m-d");
    echo "<form><h3><br>Recibos De Hoje</h3>";
}

// Consultar os recibos correspondentes à data selecionada
$sql = "SELECT recibo, valor, data_prevista, status FROM contribuicao WHERE data_prevista = '$data'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Exibir os recibos em uma tabela
    
    if (isset($_POST["data"])) {
        $data = $_POST["data"];
        $dataf = date("d/m/Y", strtotime($data));
        $datah = date("d/m/Y");
        if ($dataf == $datah){
            echo "<form><h3><br>Recibos De Hoje</h3>";
        }else{
            echo "<form><h3><br>Recibos De $dataf</h3>";
        }
    }
    echo "<table><tr><th>Recibo</th><th>Valor</th><th>Data Prevista</th><th>Status</th>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["recibo"] . "</td><td>" . $row["valor"] . "</td><td>" . date('d/m/Y', strtotime($row["data_prevista"])) . "</td><td>" . $row["status"] ;
    }

    echo "</table>";
  } else {
    echo "<div class='error-message'>Não há recibos para a data selecionada.</div>";
  }
  echo "</form>";
  // Processar o envio do formulário de busca

    // Obter o número do recibo fornecido pelo formulário de busca
        // Iniciar a sessão
        
        echo "<br><br>";
        echo "<form method='GET' action=''>";
        echo "<label><h2>Buscar Contribuição</h2></label>";
        echo "<label for='numero-recibo'>Número do Recibo:</label>";
        echo "<input type='text' name='numero-recibo' id='numero-recibo'>";
        echo "<br><br>";
        echo "<input type='submit' value='Buscar'>";
        
    
        if(isset($_GET['numero-recibo'])) {
            $recibo = $_GET['numero-recibo'];
    
            // Selecionar a contribuição correspondente na tabela "contribuicao"
            $sql = "SELECT c.recibo, c.valor, c.data_prevista, m.nome AS nome_mensageiro, 
               tp.nome AS nome_tipos_pagamento, tp.id AS id_tipos_pagamento, 
               c.status, co.nome AS nome_contribuinte, c.id_contribuinte
            FROM contribuicao c
            INNER JOIN mensageiro m ON c.id_mensageiro = m.id
            INNER JOIN tipos_pagamento tp ON c.id_tipos_pagamento = tp.id
            INNER JOIN contribuinte co ON c.id_contribuinte = co.id
            WHERE c.recibo = '$recibo'";

    
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $row = $result->fetch_assoc();
            
                if ($result->num_rows > 0) {
                    
                    // Exibir os dados da contribuição
                    // Atribuir o resultado da consulta à variável $row
                    echo "<form><h2>Dados da Contribuição</h2>";
                    echo "<p>Recibo: " . $row['recibo'] . "</p>";
                    echo "<p>Valor: " . $row['valor'] . "</p>";
                    echo "<p>Data Prevista: " . $row['data_prevista'] . "</p>";
                    echo "<p>Mensageiro: " . $row['nome_mensageiro'] . "</p>";
                    echo "<p>Tipo de Pagamento: " . $row['nome_tipos_pagamento'] . "</p>";
                    echo "<p>Status: " . $row['status'] . "</p>";
                    echo "<p>Contribuinte: " . $row['nome_contribuinte'] . "</p>";
                    echo "<br><a href='visualizar_detalhes.php?contribuinte_id=" . $row['id_contribuinte'] . "' target='_blank' class='botao'>Visualizar Detalhes</a>";
                    if ($row["status"] == "recebido") {
                        echo "<td><a href='gerar_pdf.php?recibo=".$row["recibo"]."' target='_blank'>Imprimir PDF</a></td>";
                      } else {
                        echo "<td>N/A</td>";
                      }
                      
                    echo "</form>";
                    
                    
                    // Adicionar o formulário de alteração de status e data de recebimento
                    
                    echo "<form method='POST' action=''>";
                    echo "<label><h2>Alterar Status de Contribuição</h2></label>";
                    echo "<input type='hidden' name='recibo' value='" . $row["recibo"] . "'>";
                    echo "<label for='status'>Status:</label>";
                    echo "<select name='status' id='status'>";
                    echo "<option value='Pendente'>Pendente</option>";
                    echo "<option value='Recebido'>Recebido</option>";
                    echo "<option value='Cancelado'>Cancelado</option>";
                    echo "</select>";
                    echo "<br><br>";
                    echo "<input type='submit' name='submit' value='Alterar'>";
                    
                    
            
                } else {
                    echo "<div class='error-message'>Contribuição não encontrada.</div>";
                }
            }
            
    }




        
        
// Processar o envio do formulário de alteração de status e data de recebimento
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"]) && $_POST["submit"] == "Alterar" && isset($_POST["recibo"])) {
    // Obter as informações fornecidas pelo formulário de alteração de status e data de recebimento
    $recibo = $_POST["recibo"];
    $status = $_POST["status"];
    // Verificar o valor do status e definir o valor da data de recebimento de acordo
    if ($status == "Pendente" || $status == "Cancelado") {
        $data_hora_recebimento = null;
    }else{
        $data_hora_recebimento = date('Y-m-d H:i:s');
    }
    $data_movimento = date("Y-m-d");
    $id_mensageiro = $_SESSION['id_usuario'];
    $id_tipos_pagamento = $row['id_tipos_pagamento'];
    $valor = $row['valor'];
    $data_prevista = $row['data_prevista'];



// Atualizar a contribuição correspondente na tabela "contribuicao"
$sql = "UPDATE contribuicao SET status = '$status' WHERE recibo = '$recibo'";
if ($conn->query($sql) === TRUE) {
    // Verificar se o status é "Recebido" antes de inserir o movimento diário
        // Inserir um novo registro na tabela "movimento_diario"
        $sql1 = "INSERT INTO movimento_diario (data_movimento, recibo, id_mensageiro, id_tipos_pagamento, valor, data_prevista, status, data_recebimento) VALUES ('$data_movimento', '$recibo', '$id_mensageiro', '$id_tipos_pagamento', '$valor', '$data_prevista', '$status', '$data_hora_recebimento')";
        if ($conn->query($sql1) === TRUE) {
            // Exibir a mensagem de sucesso e redirecionar para a página inicial
            $_SESSION["success_message"] = "<p>Contribuição atualizada com sucesso.</p>";
            header("Location: index.php");
            exit; // Importante sair do script depois de redirecionar
        } else {
            echo "Erro ao inserir o movimento diário: " . $conn->error;
            exit;
        }

} else {
    echo "Erro ao atualizar a contribuição: " . $conn->error;
    exit;
}

}
if (isset($_SESSION["success_message"])) {
    echo $_SESSION["success_message"];
    unset($_SESSION["success_message"]);
}
echo "</form>";
// Fechar a conexão com o banco de dados MySQL

$conn->close();
 
?>
<form>
<a href="logout.php">Sair</a>
</body>
</html>
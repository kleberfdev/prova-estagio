
<?php

require('fpdf.php');

// Obter o número do recibo a partir dos parâmetros GET
$recibo = $_GET['recibo'];

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

// Selecionar as informações do recibo no banco de dados

$sql = "SELECT md.recibo, md.valor, md.data_prevista, md.data_recebimento, md.status, 
               c.nome AS nome_contribuinte
        FROM movimento_diario md
        INNER JOIN contribuinte c ON md.recibo = c.id
        WHERE md.recibo = $recibo";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Gerar o PDF do recibo
    $row = $result->fetch_assoc();

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(40,10,'Recibo');
    $pdf->Ln();
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(40,10,'Recibo: '.$row["recibo"]);
    $pdf->Ln();
    $pdf->Cell(40,10,'Nome do Contribuinte: '.$row["nome_contribuinte"]);
    $pdf->Ln();
    $pdf->Cell(40,10,'Valor: R$ '.$row["valor"]);
    $pdf->Ln();
    $pdf->Cell(40,10,'Data Prevista: '.date("d/m/Y", strtotime($row["data_prevista"])));
    $pdf->Ln();
    $pdf->Cell(40,10,'Data De Recebimento: '.date("d/m/Y H:i:s", strtotime($row["data_recebimento"])));
    $pdf->Ln();
    $pdf->Cell(40,10,'Status: '.$row["status"]);
    $pdf->Output();
} else {
    echo "Recibo não encontrado.";
}
?>

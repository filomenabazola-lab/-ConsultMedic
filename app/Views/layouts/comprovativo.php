<?php
require('fpdf/fpdf.php'); // Certifique-se de que o caminho está correto

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Recebe os dados
    $nome    = utf8_decode($_POST['nome'] ?? 'Paulo');
    $phone   = $_POST['phone'] ?? '9346556454';
    $data    = $_POST['data'] ?? '12/09/2026 12:30';
    $email   = utf8_decode($_POST['email'] ?? 'teste@gmail.com');
    $depart  = utf8_decode($_POST['departamento'] ?? 'oftamologista');
    $msg     = utf8_decode($_POST['mensagem'] ?? 'Sem mensagem.');

    // 2. Instancia o FPDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Cabeçalho
    $pdf->Cell(0, 10, utf8_decode('Comprovativo de Agendamento'), 0, 1, 'C');
    $pdf->Ln(10); // Quebra de linha

    // Corpo do documento
    $pdf->SetFont('Arial', '', 12);

    // Função auxiliar para criar linhas de dados
    function adicionarLinha($label, $valor, $pdf) {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, $label . ':', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $valor, 0, 1);
    }

    adicionarLinha('Nome', $nome, $pdf);
    adicionarLinha('Telefone', $phone, $pdf);
    adicionarLinha('Email', $email, $pdf);
    adicionarLinha('Data/Hora', $data, $pdf);
    adicionarLinha('Depto. ID', $depart, $pdf);

    // Área da Mensagem (pode ser longa, então usamos MultiCell)
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Mensagem:', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 8, $msg, 1); // O '1' cria uma borda ao redor da mensagem

    // Rodapé simples
    $pdf->Ln(20);
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 10, utf8_decode('Gerado automaticamente em: ') . date('d/m/Y H:i'), 0, 0, 'C');

    // 3. Saída para o navegador
    $pdf->Output('I', 'consulta_' . $nome . '.pdf'); 
    // 'I' abre no navegador, 'D' força o download
}
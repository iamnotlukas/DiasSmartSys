<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: index.php'); // Redireciona para a página de login
    exit();
}

// Inclui a biblioteca TCPDF
require_once('../tcpdf/tcpdf.php');

// Cria um novo objeto TCPDF
$pdf = new TCPDF();

// Define as informações do PDF
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Relatório de Registros');
$dataAtual = date('d/m/Y'); // Obtém a data atual
$pdf->SetHeaderData('', 0, "Relatório de Acesso ao NAAC - $dataAtual", '');

// Define o tamanho da fonte padrão e a escala
$pdf->SetFont('helvetica', '', 9); // Tamanho da fonte menor
$pdf->setPrintHeader(true); // Imprime o cabeçalho
$pdf->setPrintFooter(false); // Não imprime o rodapé

// Adiciona uma página
$pdf->AddPage();

// Escreve o conteúdo
$html = '<h3 style="text-align: center; color: #333;">Dados Registrados</h3>';
$html .= '<h5 style="text-align:center">Desenvolvido por MN-RC DIAS</h5>';
$html .= '<table style="width: 100%; border-collapse: collapse;">'; // Usando border-collapse
$html .= '<tr style="background-color: #007BFF; color: white;">
             <th style="width: 20px; padding: 10px;">ID</th>
             <th style="padding: 10px;">CPF</th>
             <th style="padding: 10px; width:40%">Nome Completo</th>
             <th style="padding: 10px;">Data de Nascimento</th>
             <th style="padding: 10px; width:40px">Motivo</th>
             <th style="padding: 10px;">Observações</th>
             <th style="width: 30px; padding: 10px;">Acesso</th>
             <th style="width: 30px; padding: 10px;">Saída</th>
          </tr>';

// Conexão ao banco de dados
include '../ConexaoBanco/conexao.php'; // Inclua a conexão

// Consulta todos os registros na tabela registros
try {
    $stmt = $conexao->query("SELECT * FROM registros");
    $registros = $stmt->fetchAll();

    foreach ($registros as $registro) {
        $html .= '<tr>
                    <td style="padding: 10px;">' . $registro['id'] . '</td>
                    <td style="padding: 10px;">' . $registro['cpf'] . '</td>
                    <td style="padding: 10px;">' . strtoupper($registro['nome_completo']) . '</td>
                    <td style="padding: 10px;">' . formatarData($registro['data_nascimento']) . '</td>
                    <td style="padding: 10px;">' . $registro['motivo'] . '</td>
                    <td style="padding: 10px;">' . $registro['observacoes'] . '</td>
                    <td style="padding: 10px;">' . formatarHora($registro['hora_registro']) . '</td>
                    <td style="padding: 10px;">' . ($registro['horaSaida'] ? formatarHora($registro['horaSaida']) : '') . '</td>
                  </tr>';
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

$html .= '</table>';

// Escreve o conteúdo HTML no PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Define o nome do arquivo para o download com o formato desejado
$dataAtual = date('d-m-Y'); // Formato: DD-MM-YYYY
$nomeArquivo = 'AcessoNaac-' . $dataAtual . '.pdf'; // Nome do arquivo

// Fecha e gera o PDF
$pdf->Output($nomeArquivo, 'D'); // 'D' para download

// Funções para formatar a data e hora
function formatarData($data) {
    $dateTime = new DateTime($data);
    return $dateTime->format('d/m/Y'); // Formato dd/mm/yyyy
}

function formatarHora($hora) {
    $dateTime = new DateTime($hora);
    return $dateTime->format('H:i'); // Formato hora:minuto
}
?>

<?php
session_start(); // Inicia a sessão
include '../ConexaoBanco/conexao.php'; // Inclua a conexão

// Inicializa variáveis
$registros = [];

// Consulta todos os registros na tabela registros
try {
    $stmt = $conexao->query("SELECT * FROM registros");
    $registros = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

// Verifica se o botão "dar baixa" foi clicado
if (isset($_POST['dar_baixa'])) {
    $id = $_POST['id'];
    $horaSaida = date('Y-m-d H:i:s'); // Pega a hora atual

    try {
        // Atualiza a coluna horaSaida
        $stmt = $conexao->prepare("UPDATE registros SET horaSaida = :horaSaida WHERE id = :id");
        $stmt->execute(['horaSaida' => $horaSaida, 'id' => $id]);

        echo "<script>alert('Baixa registrada com sucesso');</script>";
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}

// Função para formatar a data e hora
function formatarData($data) {
    $dateTime = new DateTime($data);
    return $dateTime->format('d/m/Y'); // Formato dd/mm/yyyy
}

function formatarHora($hora) {
    $dateTime = new DateTime($hora);
    return $dateTime->format('H:i'); // Formato hora:minuto
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuários</title>
    <link rel="stylesheet" href="../cssPaginas/login.css">
</head>
<body>
    <h3>Dados Registrados</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>CPF</th>
            <th>Nome Completo</th>
            <th style="width:40px;">Data de Nascimento</th>
            <th>Observações</th>
            <th>Hora de Registro</th>
            <th>Hora de Saída</th>
            <th style="width:40px;">Ações</th>
        </tr>
        <?php if ($registros): ?>
            <?php foreach ($registros as $registro): ?>
                <tr>
                    <td><?php echo $registro['id']; ?></td>
                    <td><?php echo $registro['cpf']; ?></td>
                    <td><?php echo strtoupper($registro['nome_completo']); ?></td>
                    <td><?php echo formatarData($registro['data_nascimento']); ?></td>
                    <td><?php echo $registro['observacoes']; ?></td>
                    <td><?php echo formatarHora($registro['hora_registro']); ?></td>
                    <td><?php echo $registro['horaSaida'] ? formatarHora($registro['horaSaida']) : ''; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $registro['id']; ?>">
                            <button type="submit" name="dar_baixa">Dar Baixa</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">Nenhum registro encontrado.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>

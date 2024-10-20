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
    
    // Pega a hora atual e subtrai 5 horas
    $horaSaida = date('Y-m-d H:i:s', strtotime('-5 hours')); 

    try {
        // Atualiza a coluna horaSaida
        $stmt = $conexao->prepare("UPDATE registros SET horaSaida = :horaSaida WHERE id = :id");
        $stmt->execute(['horaSaida' => $horaSaida, 'id' => $id]);

        // Define uma variável de sessão para o alerta
        $_SESSION['alert'] = 'Baixa registrada com sucesso';
        
        // Redireciona para a mesma página
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}

// Verifica se existe um alerta na sessão e exibe
if (isset($_SESSION['alert'])) {
    echo "<script>alert('" . $_SESSION['alert'] . "');</script>";
    unset($_SESSION['alert']); // Limpa o alerta após exibir
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
    <style>
        .btn-voltar {
  display: inline-block; /* Para o botão se comportar como um bloco */
  padding: 10px 20px; /* Espaçamento interno */
  margin: 10px auto; /* Margem superior/inferior de 10px e centralização horizontal */
  background-color: #007BFF; /* Cor de fundo azul */
  color: white; /* Cor do texto branco */
  border: none; /* Remove a borda padrão */
  border-radius: 5px; /* Bordas arredondadas */
  font-size: 16px; /* Tamanho da fonte */
  cursor: pointer; /* Muda o cursor para pointer ao passar o mouse */
  text-align: center; /* Centraliza o texto */
  text-decoration: none; /* Remove o sublinhado do texto */
  transition: background-color 0.3s ease; /* Efeito de transição na cor de fundo */
}

.btn-voltar:hover {
  background-color: #0056b3; /* Cor de fundo quando o mouse está sobre o botão */
}

    </style>
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
    <button type="button" class="btn-voltar" onclick="window.location.href='op.php'">Voltar</button>
  </body>
</html>

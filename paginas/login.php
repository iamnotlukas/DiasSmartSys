<?php
session_start();
include '../ConexaoBanco/conexao.php';

// Verifica autenticação
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: index.php');
    exit();
}

// Inicializa variáveis
$registros = [];
$totalAcessos = 0;

// Busca total de acessos
try {
    $stmt = $conexao->query("SELECT COUNT(*) as total FROM registros");
    $result = $stmt->fetch();
    $totalAcessos = $result['total'];
} catch (PDOException $e) {
    $erro = "Erro ao buscar total de acessos: " . $e->getMessage();
}

// Processar o botão "Dar Baixa"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dar_baixa'])) {
    $id = $_POST['id'];
    $horaSaida = date('Y-m-d H:i:s'); // Hora atual no formato do banco de dados

    try {
        $stmt = $conexao->prepare("UPDATE registros SET horaSaida = :horaSaida WHERE id = :id");
        $stmt->bindParam(':horaSaida', $horaSaida);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Atualizar a página para refletir as mudanças
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } catch (PDOException $e) {
        echo "Erro ao registrar hora de saída: " . $e->getMessage();
    }
}

// Buscar registros para exibição
try {
    $stmt = $conexao->query("SELECT * FROM registros ORDER BY hora_registro DESC");
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $erro = "Erro ao buscar registros: " . $e->getMessage();
}

// Funções auxiliares
function formatarData($data) {
    return date('d/m/Y', strtotime($data));
}

function formatarHora($hora) {
    return date('H:i', strtotime($hora));
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de Controle de Visitantes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
  <div class="container">
    <header class="header">
      <br>
      <h1>Sistema de Controle de Visitantes</h1>
      <br>
    </header>

    <nav class="nav-menu">
      <a href="cadastro.php" class="btn btn-primary">Cadastrar Novo Visitante</a>
      <a href="registros.php" class="btn btn-primary">Registrar Visita</a>
      <a href="gerar_pdf.php" class="btn btn-secondary">Gerar Relatório</a>
    </nav>
    <br>
    <div class="card">
      <h2>Total de Acessos: <?php echo $totalAcessos; ?></h2>

      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>CPF</th>
              <th>Nome Completo</th>
              <th>Data de Nascimento</th>
              <th>Motivo</th>
              <th>Observações</th>
              <th>Entrada</th>
              <th>Saída</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($registros as $registro): ?>
            <tr>
              <td><?php echo $registro['cpf']; ?></td>
              <td><?php echo strtoupper($registro['nome_completo']); ?></td>
              <td><?php echo formatarData($registro['data_nascimento']); ?></td>
              <td><?php echo $registro['motivo']; ?></td>
              <td><?php echo $registro['observacoes']; ?></td>
              <td><?php echo formatarHora($registro['hora_registro']); ?></td>
              <td><?php echo $registro['horaSaida'] ? formatarHora($registro['horaSaida']) : '-'; ?></td>
              <td>
                <?php if (!$registro['horaSaida']): ?>
                <form method="POST" style="display: inline;">
                  <input type="hidden" name="id" value="<?php echo $registro['id']; ?>">
                  <button type="submit" name="dar_baixa" class="btn btn-secondary">Dar Baixa</button>
                </form>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <footer class="footer">
      <p>Desenvolvido por MN-RC DIAS 24.0729.23</p>
    </footer>
  </div>
</body>

</html>
<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: index.php'); // Redireciona para a página de login
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
include '../ConexaoBanco/conexao.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_completo = strtoupper($_POST['nome_completo']); // Converte o nome para maiúsculas
    $cpf = $_POST['cpf'];
    $data_nascimento = $_POST['data_nascimento'];
    $observacoes = $_POST['observacoes'];

    // Validações básicas
    if (strlen($cpf) != 11) {
        $erro = 'CPF inválido. Certifique-se de que contém 11 dígitos.';
    } elseif (strlen($observacoes) > 50) {
        $erro = 'Observações não podem exceder 50 caracteres.';
    } else {
        try {
            // Prepara a query para inserir os dados no banco de dados
            $query = "INSERT INTO usuarios (nome_completo, cpf, data_nascimento, observacoes) 
                      VALUES (:nome_completo, :cpf, :data_nascimento, :observacoes)";
            
            $stmt = $conexao->prepare($query);
            
            // Vincula os valores aos parâmetros
            $stmt->bindParam(':nome_completo', $nome_completo);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':data_nascimento', $data_nascimento);
            $stmt->bindParam(':observacoes', $observacoes);
            
            // Executa a query
            $stmt->execute();
            
            // Exibe mensagem de sucesso com JavaScript
            echo "<script>alert('Cadastro realizado com sucesso!');</script>";
        } catch (PDOException $e) {
            // Em caso de erro, exibe mensagem de erro
            $erro = 'Erro ao cadastrar: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastramento de Usuário</title>
    <link rel="stylesheet" href="../cssPaginas/cadastro.css">
    <style>
        button {
            background-color: #007bff; /* Exemplo de cor de fundo */
            color: white; /* Cor do texto */
            border: none; /* Remove bordas */
            padding: 10px 20px; /* Espaçamento interno */
            text-align: center; /* Centraliza o texto */
            text-decoration: none; /* Remove sublinhado */
            display: inline-block; /* Permite definir largura e altura */
            font-size: 16px; /* Tamanho da fonte */
            margin: 10px 2px; /* Margem */
            cursor: pointer; /* Cursor de ponteiro */
            border-radius: 4px; /* Bordas arredondadas */
        }

        button a {
            color: white; /* Mantém a cor do texto branco dentro do link */
            text-decoration: none; /* Remove sublinhado do link */
        }

        button:hover {
            background-color: #0056b3; /* Cor de fundo ao passar o mouse */
        }

        #cad {
  background-color: #4CAF50;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  width: 100%;
}
#cad:hover {
  background-color: #45a049;
}

    </style>
</head>
<body>
    <div class="login-container">
        <img src="../imagens/logoMarinha.png" style="width: 20%; margin-bottom: 10px;">
        <h2>Cadastramento de Usuário</h2>
        <form method="POST">
            <label for="nome_completo">Nome Completo:</label>
            <input type="text" id="nome_completo" name="nome_completo" required>

            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" maxlength="11" required>

            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento" required>

            <label for="observacoes">Observações (máx. 50 caracteres):</label>
            <textarea id="observacoes" name="observacoes" maxlength="50"></textarea>

            <button id="cad" type="submit">Cadastrar</button>
            <button type="button" onclick="window.location.href='op.php'" style="margin: 10px 0; color: white; text-decoration: none;">Voltar</button>
            <h5>Desenvolvido por MN-RC DIAS 24.0729.23</h5>
        </form>
        <?php
        // Exibe a mensagem de erro ou sucesso, se houver
        if (isset($erro)) {
            echo '<p class="error">' . $erro . '</p>';
        }
        ?>
    </div>
</body>
</html>

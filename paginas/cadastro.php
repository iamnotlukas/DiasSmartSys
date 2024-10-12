<?php
session_start(); // Inicia a sessão

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_completo = $_POST['nome_completo'];
    $cpf = $_POST['cpf'];
    $data_nascimento = $_POST['data_nascimento'];
    $observacoes = $_POST['observacoes'];

    // Validações básicas (pode adicionar mais validações se necessário)
    if (strlen($cpf) != 11) {
        $erro = 'CPF inválido. Certifique-se de que contém 11 dígitos.';
    } elseif (strlen($observacoes) > 50) {
        $erro = 'Observações não podem exceder 50 caracteres.';
    } else {
        // Aqui você pode inserir os dados no banco de dados
        // Lógica para inserir os dados no banco

        // Após inserir no banco, pode redirecionar para outra página ou exibir uma mensagem de sucesso
        $sucesso = 'Cadastro realizado com sucesso!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastramento de Usuário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            text-align: left;
        }
        input[type="text"], input[type="date"] {
            width: 93%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        textarea {
            width: 93%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: none;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        .success {
            color: green;
            margin-top: 10px;
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

            <button type="submit">Cadastrar</button>
            <h5>Desenvolvido por MN-RC DIAS 24.0729.23</h5>
        </form>
        <?php
        // Exibe a mensagem de erro ou sucesso, se houver
        if (isset($erro)) {
            echo '<p class="error">' . $erro . '</p>';
        } elseif (isset($sucesso)) {
            echo '<p class="success">' . $sucesso . '</p>';
        }
        ?>
    </div>
</body>
</html>

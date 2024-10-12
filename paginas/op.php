<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: index.php'); // Redireciona para a página de login
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opções do Administrador</title>
    <link rel="stylesheet" href="../cssPaginas/op.css">
</head>
<body>
    <div class="op-container">
        <h2>Opções do Administrador</h2>
        <ul>
            <li><a href="login.php">Ver Registros Atuais</a></li>
            <li><a href="cadastro.php">Cadastrar Usuário</a></li>
            <li><a href="registros.php">Registrar Usuário</a></li>
        </ul>
    </div>
</body>
</html>

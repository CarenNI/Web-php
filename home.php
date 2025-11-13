<?php
// Inicia a sessão
// "home.php" é uma página protegida. Eu verifico se o usuário está logado usando SESSION.
//Se não tiver user_id na sessão, eu redireciono para o login com header().
//Mostro o nome do usuário que está salvo na sessão.
// //E ofereço links para "Gerenciar Tarefas" e "Logout"
session_start();

// Se o usuário NÃO estiver logado, volta para o login
// Se não tiver ID do usuário na sessão, significa que não está logado → manda de volta pro login.
// É uma proteção de páginas internas (somente usuários logados acessam).
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Área Interna</title>
    <!-- CSS externo com tema claro e detalhes de gato -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- header usado só para o detalhe do gatinho no CSS -->
    <header></header>

    <div class="container">
        <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_nome']); ?>!</h1>

        <p>Você está logado no sistema.</p>

        <ul>
            <li><a href="tarefas/tarefas.php">Gerenciar Tarefas</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
        <img src="img/gato2.png" alt="gato">

    </div>

</body>
</html>

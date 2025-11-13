<?php
session_start();
require '../db.php'; // volta uma pasta para pegar o db.php

// A página começa verificando se o usuário está logado (SESSION).
// Se não estiver logado, volta para o login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // login.php está fora da pasta /tarefas
    exit;
}

// Se recebeu um POST (criar tarefa)
$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');

    if ($titulo === '') {
        $mensagem = "O título da tarefa é obrigatório.";
    } else {
        // Quando o formulário é enviado com POST, ele cria uma nova tarefa usando:
        $stmt = $conn->prepare("INSERT INTO tarefas (titulo, descricao) VALUES (?, ?)");
        $stmt->bind_param("ss", $titulo, $descricao);
        $stmt->execute();
        $stmt->close();
        $mensagem = "Tarefa criada com sucesso!";
    }
}

// Busca todas as tarefas com: SELECT * FROM tarefas ORDER BY id DESC
$resultado = $conn->query("SELECT * FROM tarefas ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Tarefas</title>

    <!-- CSS externo com tema claro e detalhes de gato -->
    <link rel="stylesheet" href="../style.css">
</head>
<body>

    <!-- header usado só para o detalhe do gatinho no CSS -->
    <header></header>

    <div class="container">

<h1>Gerenciar Tarefas</h1>

<p>
    <a href="../home.php">Voltar</a> |
    <a href="../logout.php">Sair</a>
</p>

<?php if ($mensagem): ?>
    <p><?php echo htmlspecialchars($mensagem); ?></p>
<?php endif; ?>

<h2>Criar nova tarefa</h2>
<form method="post">
    Título:<br>
    <input type="text" name="titulo"><br><br>

    Descrição:<br>
    <textarea name="descricao"></textarea><br><br>

    <button type="submit">Criar Tarefa</button>
</form>

<hr>

<h2>Tarefas cadastradas</h2>

<table cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Descrição</th>
        <th>Ações</th>
    </tr>

    <?php while ($tarefa = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?php echo $tarefa['id']; ?></td>
            <td><?php echo htmlspecialchars($tarefa['titulo']); ?></td>
            <td><?php echo htmlspecialchars($tarefa['descricao']); ?></td>
            <td>
                <a href="editar_tarefa.php?id=<?php echo $tarefa['id']; ?>">Editar</a> |
                <a href="excluir_tarefa.php?id=<?php echo $tarefa['id']; ?>"
                   onclick="return confirm('Tem certeza que deseja excluir?');">
                   Excluir
                </a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

    </div> <!-- fim container -->

</body>
</html>


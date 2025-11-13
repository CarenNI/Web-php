<?php
session_start();
require '../db.php'; // VOLTA UMA PASTA

// Verifica se está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // VOLTA UMA PASTA
    exit;
}

// Verifica se recebeu o ID pela URL
if (!isset($_GET['id'])) {
    die("ID da tarefa não informado.");
}

$id = (int) $_GET['id']; // converte para inteiro

// Primeiro, busca a tarefa no banco
$stmt = $conn->prepare("SELECT * FROM tarefas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$tarefa = $resultado->fetch_assoc();
$stmt->close();

// Se não encontrou tarefa, para aqui
if (!$tarefa) {
    die("Tarefa não encontrada.");
}

$mensagem = "";

// Se o formulário foi enviado (POST), atualiza a tarefa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');

    if ($titulo === '') {
        $mensagem = "O título é obrigatório.";
    } else {
        $stmt = $conn->prepare("UPDATE tarefas SET titulo = ?, descricao = ? WHERE id = ?");
        $stmt->bind_param("ssi", $titulo, $descricao, $id);
        $stmt->execute();
        $stmt->close();
        $mensagem = "Tarefa atualizada com sucesso!";
        
        // Atualiza os dados na variável $tarefa
        $tarefa['titulo'] = $titulo;
        $tarefa['descricao'] = $descricao;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarefa</title>
    <!-- CSS externo com tema claro e detalhes de gato -->
    <link rel="stylesheet" href="../style.css">
</head>
<body>

    <!-- header usado só para o detalhe do gatinho no CSS -->
    <header></header>

    <div class="container">

<h1>Editar Tarefa</h1>

<p>
    <a href="tarefas.php">Voltar para Tarefas</a> |
    <a href="../home.php">Home</a>
</p>

<?php if ($mensagem): ?>
    <p><?php echo htmlspecialchars($mensagem); ?></p>
<?php endif; ?>

<form method="post">
    Título:<br>
    <input type="text" name="titulo" value="<?php echo htmlspecialchars($tarefa['titulo']); ?>"><br><br>

    Descrição:<br>
    <textarea name="descricao"><?php echo htmlspecialchars($tarefa['descricao']); ?></textarea><br><br>

    <button type="submit">Salvar alterações</button>
</form>

    </div>

</body>
</html>

<?php
require 'db.php'; // importa a conexão com o banco

$mensagem = "";

// method="post" → manda os dados para o PHP usar.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome      = trim($_POST['nome'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $senha     = trim($_POST['senha'] ?? '');
    $confirmar = trim($_POST['confirmar'] ?? '');

    // Validações simples
    if ($nome === '' || $email === '' || $senha === '') {
        $mensagem = "Preencha todos os campos.";
    } elseif ($senha !== $confirmar) {
        $mensagem = "As senhas não são iguais.";
    } else {
        // Criptografa a senha
        // password_hash() → criptografa a senha, então no banco não fica a senha real.
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Prepara comando para inserir
        // prepare() e bind_param() → protegem contra SQL injection 
        $stmt = $conn->prepare("INSERT INTO users (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $senhaHash);

        if ($stmt->execute()) {
            // $mensagem → serve para mostrar erros e avisos na tela.
            $mensagem = "Usuário cadastrado com sucesso! Você já pode fazer login.";
        } else {
            if ($conn->errno == 1062) {
                $mensagem = "Este e-mail já está cadastrado.";
            } else {
                $mensagem = "Erro ao cadastrar: " . $conn->error;
            }
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <!-- CSS externo com tema claro e detalhes de gato -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- header usado só para o detalhe do gatinho no CSS -->
    <header></header>

    <div class="container">
        <h1>Cadastro de Usuário</h1>

        <?php if ($mensagem): ?>
            <p><?php echo htmlspecialchars($mensagem); ?></p>
        <?php endif; ?>

        <form method="post">
            Nome:<br>
            <input type="text" name="nome"><br><br>

            E-mail:<br>
            <input type="email" name="email"><br><br>

            Senha:<br>
            <input type="password" name="senha"><br><br>

            Confirmar Senha:<br>
            <input type="password" name="confirmar"><br><br>

            <button type="submit">Cadastrar</button>
        </form>

        <p><a href="login.php">Já tenho conta (Fazer login)</a></p>
    </div>

</body>
</html>

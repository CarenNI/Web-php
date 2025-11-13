<?php
// session_start() → ativa o uso de sessão no PHP.
session_start();    // inicia a sessão 
require 'db.php';   // usa a conexão com o banco

$mensagem = "";

// O formulário usa method="post", então uso $_POST pra receber os dados.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if ($email === '' || $senha === '') {
        $mensagem = "Preencha e-mail e senha.";
    } else {
        // 1º: busca o usuário pelo e-mail
        $stmt = $conn->prepare("SELECT id, nome, senha FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();
        $stmt->close();

        // Verifica se encontrou usuário e se a senha confere
        // password_verify() compara a senha digitada com a senha criptografada do banco
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // se der certo, guarda dados na sessão
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_nome'] = $usuario['nome'];

            // header("Location: home.php") -> redireciona o usuário para a área interna
            header("Location: home.php");
            exit;
        } else {
            $mensagem = "E-mail ou senha inválidos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <!-- CSS externo com tema claro e detalhes de gato -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- header usado só para o detalhe do gatinho no CSS -->
    <header></header>

    <div class="container">
        <h1>Login</h1>

        <?php if ($mensagem): ?>
            <p><?php echo htmlspecialchars($mensagem); ?></p>
        <?php endif; ?>

        <!-- O formulário usa method="post", então uso $_POST pra receber os dados. -->
        <form method="post">
            E-mail:<br>
            <input type="email" name="email"><br><br>

            Senha:<br>
            <input type="password" name="senha"><br><br>

            <button type="submit">Entrar</button>
        </form>

        <p><a href="register.php">Não tenho cadastro (Criar conta)</a></p>
    </div>

</body>
</html>

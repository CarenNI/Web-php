<?php
// Dados da conexão com o MySQL
$host = "localhost";
$usuario = "root";     // padrão do XAMPP
$senha = "";          // senha vazia mesmo
$banco = "sistema_php"; // nome do banco que criamos

// Criar conexão com o banco
$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica erro
//Se tiver erro, die() encerra o código e mostra a mensagem.
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>

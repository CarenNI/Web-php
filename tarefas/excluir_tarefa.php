<?php
session_start();
require '../db.php'; // volta uma pasta para pegar o db.php

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Verifica se recebeu o ID pela URL (GET)
if (!isset($_GET['id'])) {
    die("ID da tarefa não informado.");
}

$id = (int) $_GET['id']; // converte para inteiro

// Prepara o comando para excluir a tarefa
$stmt = $conn->prepare("DELETE FROM tarefas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// Depois de excluir, volta para a lista de tarefas
header("Location: tarefas.php");
exit;

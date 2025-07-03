<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "minestock";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$nome = $_POST['nome'] ?? null;

if ($nome) {
    $sql = "INSERT INTO categoria (nome) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nome);

    if ($stmt->execute()) {
        echo "<script>alert('Categoria cadastrada com sucesso!'); window.location.href='cadastro.categoria.html';</script>";
    } else {
        echo "Erro ao cadastrar categoria: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Nome da categoria não fornecido.";
}

$conn->close();

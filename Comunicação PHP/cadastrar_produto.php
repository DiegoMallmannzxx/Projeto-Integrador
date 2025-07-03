<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "minestock";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$nome = $_POST['nome'];
$codigo = $_POST['codigo'];
$fornecedor_id = $_POST['fornecedor_id'];
$categoria_id = $_POST['categoria_id'];

$sql = "INSERT INTO produto (nome, codigo, fornecedor_id, categoria_id) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $nome, $codigo, $fornecedor_id, $categoria_id);

if ($stmt->execute()) {
    echo "<script>alert('Produto cadastrado com sucesso!'); window.location.href='cadastro.produtos.html';</script>";
} else {
    echo "Erro ao cadastrar produto: " . $conn->error;
}

$conn->close();
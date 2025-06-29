<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "minestock";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$nome = $_POST['nome'];
$telefone = $_POST['telefone'];

$sql = "INSERT INTO Fornecedor (nome, telefone) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $nome, $telefone);

if ($stmt->execute()) {
    echo "<script>alert('Fornecedor cadastrado com sucesso!'); window.location.href='cadastro.fornecedor.html';</script>";
} else {
    echo "Erro ao cadastrar fornecedor: " . $conn->error;
}

$stmt->close();
$conn->close();
?>

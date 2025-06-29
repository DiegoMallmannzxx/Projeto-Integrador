<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "minestock";

// Conectar ao banco
$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Receber dados do formulário
$nome = $_POST['nome'] ?? '';
$codigo = $_POST['codigo'] ?? '';
$fornecedor_id = $_POST['fornecedor_id'] ?? '';

// Validar campos
if (empty($nome) || empty($codigo) || empty($fornecedor_id)) {
    echo "<script>alert('Preencha todos os campos!'); window.location.href='cadastro.produtos.html';</script>";
    exit;
}

// Inserir no banco
$sql = "INSERT INTO Produto (nome, codigo, fornecedor_id) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $nome, $codigo, $fornecedor_id);

if ($stmt->execute()) {
    echo "<script>alert('Produto cadastrado com sucesso!'); window.location.href='cadastro.produtos.html';</script>";
} else {
    echo "<script>alert('Erro ao cadastrar produto.'); window.location.href='cadastro.produtos.html';</script>";
}

$conn->close();
?>

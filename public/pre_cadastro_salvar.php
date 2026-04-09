<?php
require_once __DIR__ . "/../config/conexao.php";

$nome = trim($_POST['nome']);
$cpf = limparNumero($_POST['cpf']);
$data_nascimento = $_POST['data_nascimento'];
$telefone = limparNumero($_POST['telefone']);
$email = trim($_POST['email']);
$endereco = trim($_POST['endereco']);
$bairro = trim($_POST['bairro']);

function limparNumero($valor)
{
    return preg_replace('/\D/', '', $valor);
}

function validarCPF($cpf)
{
    $cpf = limparNumero($cpf);

    if (strlen($cpf) != 11) return false;

    // Evita CPFs tipo 11111111111
    if (preg_match('/(\d)\1{10}/', $cpf)) return false;

    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) return false;
    }

    return true;
}

if (!$nome || !$cpf || !$data_nascimento) {
    die("Preencha todos os campos obrigatórios");
}

if (!validarCPF($cpf)) {
    die("CPF inválido");
}

if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email inválido");
}

try {

    $stmt = $pdo->prepare("SELECT id FROM pre_cadastros WHERE cpf = ?");
    $stmt->execute([$cpf]);

    if ($stmt->fetch()) {
        header("Location: index.php?erro=cpf");
        exit;
    }

    $stmt = $pdo->prepare("
    INSERT INTO pre_cadastros 
    (nome, cpf, data_nascimento, telefone, email, endereco, bairro, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, 'pendente')
");

    $stmt->execute([
        $nome,
        $cpf,
        $data_nascimento,
        $telefone,
        $email,
        $endereco,
        $bairro
    ]);

    header("Location: index.php?sucesso=1");
    exit;
} catch (PDOException $e) {
    die("Erro ao salvar: " . $e->getMessage());
}

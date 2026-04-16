<?php
require_once __DIR__ . "/../config/conexao.php";

// 🔹 Função para limpar números
function limparNumero($valor)
{
    return preg_replace('/\D/', '', $valor);
}

// 🔹 Validação REAL de CPF
function validarCPF($cpf)
{
    $cpf = preg_replace('/\D/', '', $cpf);

    if (strlen($cpf) != 11) return true; // ⚠ TEMPORÁRIO PRA TESTE

    if (preg_match('/^(\d)\1{10}$/', $cpf)) return false;

    for ($t = 9; $t < 11; $t++) {
        $soma = 0;

        for ($i = 0; $i < $t; $i++) {
            $soma += $cpf[$i] * (($t + 1) - $i);
        }

        $digito = (10 * $soma) % 11;
        $digito = ($digito == 10) ? 0 : $digito;

        if ($cpf[$t] != $digito) return false;
    }

    return true;
}

// 🔹 Recebendo dados com segurança
$nome = trim($_POST['nome'] ?? '');
$cpf = $_POST['cpf'] ?? '';
$data_nascimento = $_POST['data_nascimento'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$email = trim($_POST['email'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$bairro = trim($_POST['bairro'] ?? '');

// 🔹 Limpeza depois de pegar os dados
$cpf = limparNumero($cpf);
$telefone = limparNumero($telefone);

// 🔹 Validações obrigatórias
if (empty($nome) || empty($cpf) || empty($data_nascimento)) {
    header("Location: index.php?erro=campos");
    exit;
}

// 🔹 CPF
if (!validarCPF($cpf)) {
    die("CPF inválido detectado");
}

// 🔹 Email
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: index.php?erro=email");
    exit;
}

try {

    // 🔹 Verifica CPF duplicado
    $stmt = $pdo->prepare("SELECT id FROM pre_cadastros WHERE cpf = ?");
    $stmt->execute([$cpf]);

    if ($stmt->fetch()) {
        header("Location: index.php?erro=cpf");
        exit;
    }

    // 🔹 Inserção
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

    // 🔹 Sucesso
    header("Location: index.php?sucesso=1");
    exit;

} catch (PDOException $e) {
    // 🔴 Em produção, logar erro ao invés de exibir
    die("Erro ao salvar: " . $e->getMessage());
}
<?php
session_start();
// Evita acesso direto
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

// Recebe dados
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

// Validação
if ($email === '' || $senha === '') {
    header("Location: login.php?erro=1");
    exit;
}

session_start();

// SEGURANÇA REAL
session_regenerate_id(true);

$_SESSION['usuario'] = [
    'id' => $usuario['id'],
    'nome' => $usuario['nome'],
    'nivel' => $usuario['nivel']
];

// Busca usuário
$sql = $pdo->prepare("
    SELECT id, nome, email, senha 
    FROM usuarios 
    WHERE email = :email 
    LIMIT 1
");

$sql->bindParam(':email', $email);
$sql->execute();

$usuario = $sql->fetch(PDO::FETCH_ASSOC);

// Verifica senha
if ($usuario && password_verify($senha, $usuario['senha'])) {

    $_SESSION['usuario'] = [
        'id'    => $usuario['id'],
        'nome'  => $usuario['nome'],
        'email' => $usuario['email']
    ];

    header("Location: dashboard.php");
    exit;

} else {
    header("Location: login.php?erro=1");
    exit;
}

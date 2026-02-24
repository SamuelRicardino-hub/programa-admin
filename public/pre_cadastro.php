<?php require_once __DIR__ . "/../layout/header.php"; ?>

<h2>Pré-Cadastro</h2>

<form action="pre_cadastro_salvar.php" method="POST">
    <input type="text" name="nome" placeholder="Nome completo" required>
    <input type="text" name="cpf" placeholder="CPF" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="telefone" placeholder="Telefone">
    <input type="date" name="data_nascimento">
    <input type="text" name="endereco" placeholder="Endereço">
    <button type="submit">Enviar</button>
</form>

<?php require_once __DIR__ . "/../layout/footer.php"; ?>
<?php
// Arquivo: generate_hash.php
// Arquivo para gerar um hash de senha para o usuário administrador.

$plainTextPassword = 'testeadmin';

// Gera o hash da senha usando o algoritmo padrão (atualmente bcrypt)
$hashedPassword = password_hash($plainTextPassword, PASSWORD_DEFAULT);

echo $hashedPassword;


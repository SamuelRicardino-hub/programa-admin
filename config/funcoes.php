<?php

function calcularIdade($dataNascimento) {
    $hoje = new DateTime();
    $nasc = new DateTime($dataNascimento);
    return $hoje->diff($nasc)->y;
}
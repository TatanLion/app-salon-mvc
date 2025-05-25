<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

// Verificar si es último
function esUltimo(string $actual, string $proximo) : bool{
    if($actual !== $proximo){
        return true;
    }
    return false;
}

// Validar si el usuario esta autenticado
function isAuth() : void {
    if(!isset($_SESSION['login'])){
        header('Location: /');
    }
}

// Validar si el usuario es administrador
function isAdmin(){
    if(!isset($_SESSION['admin'])){
        header('Location: /');
    }
}
<h1 class="nombre-pagina">Crear Servicio</h1>
<p class="descripcion-pagina">Llena todos los campos para crear un nuevo servicio</p>

<?php
    include_once __DIR__ . "/../templates/alertas.php";
    include_once __DIR__ . "/../templates/barra.php";
?>

<form action="/servicios/crear" method="POST" class="formulario">
    
    <?php include_once __DIR__ . "/formulario.php" ?>

    <input type="submit" value="Crear Servicio" class="boton">
</form>
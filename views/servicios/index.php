<h1 class="nombre-pagina">Servicios</h1>
<p class="descripcion-pagina">Administración de Servicios</p>

<?php
    include_once __DIR__ . "/../templates/barra.php";
?>

<ul class="servicios">
    <?php foreach($servicios as $servicio): ?>
        <li>
            <p>Nombre: <span><?php echo $servicio->nombre; ?></span></p>
            <p>Precio: <span>$ <?php echo $servicio->precio; ?></span></p>
        </li>
        <div class="acciones">
            <a href="/servicios/actualizar?id=<?php echo s($servicio->id) ?>" class="boton">Actualizar</a>
            <form action="/servicios/eliminar" method="POST">
                <input 
                    type="hidden" 
                    name="id" 
                    value="<?php echo s($servicio->id) ?>"
                ></input>
                <input type="submit" value="Eliminar Servicio" class="boton-eliminar">
            </form>
        </div>
    <?php endforeach; ?>
</ul>
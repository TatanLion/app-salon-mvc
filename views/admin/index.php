<h1 class="nombre-pagina">Panel de Administracion</h1>

<?php include_once __DIR__ . '/../templates/barra.php' ?>


<h2>Buscar Citas</h2>

<div class="busqueda">
    <div class="formulario">
        <div class="campo">
            <label for="fecha"></label>
            <input 
                type="date" 
                name="fecha" 
                id="fecha"
                value="<?php echo $fecha; ?>"
            >
        </div>
    </div>
</div>

<?php if(count($citas) <= 0): ?>
    <h2>No hay citas en esta fecha</h2>
<?php endif; ?>

<div class="citas-admin">
    <ul class="citas">
        <?php 
            // Asignamos el idCita como null la primera vez
            $idCita = null;

            foreach($citas as $key => $cita): 
                // Verificar si el Id se repite
                if($idCita !== $cita->id):
                    // Iniciamos el total en 0 por cada id unico
                    $total = 0;
        ?>
            <li>
                <p>ID: <span><?php echo $cita->id; ?></span></p>
                <p>Hora: <span><?php echo $cita->hora; ?></span></p>
                <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
            </li>

            <h3>Servicios</h3>
            <?php 
                // Aquí se asigna el id luego de se imprime la primera vez
                $idCita = $cita->id;
                endif;
                // Sumamos el total de cada servicio fuera del id unico para que los sume todos y no solo el primero
                $total += $cita->precio;
            ?>
            
            <p class="servicio">
                <?php echo $cita->servicio . " " . $cita->precio ?>
            </p>

        <?php 
            $actual = $cita->id; // Obtenemos el id actual del elemento
            $proximo = $citas[$key + 1]->id ?? 0; // Posicion del id basado en el array del elemento
            if(esUltimo($actual, $proximo)): ?>
                <p class="total">Total: <span><?php echo $total; ?></span></p>
                <form action="/api/eliminar" method="POST">
                    <input type="hidden" name="id" value="<?php echo s($cita->id); ?>">
                    <input type="submit" value="Eliminar Cita" class="boton-eliminar">
                </form>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>

<?php 
    $script = "<script src=\"/build/js/buscador.js\"></script>";
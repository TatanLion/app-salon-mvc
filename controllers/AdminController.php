<?php 

namespace Controllers;

use DateTime;
use Model\AdminCita;
use MVC\Router;

class AdminController {
    public static function index(Router $router){

        session_start();
        isAdmin();

        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $fechas = explode('-', $fecha);

        // Validar que la fecha exista
        if(!checkdate($fechas[1], $fechas[2], $fechas[0])){
            header('Location: /404');
        }

        // Consultar la base de datos
        $query = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $query .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $query .= " FROM citas  ";
        $query .= " LEFT OUTER JOIN usuarios ";
        $query .= " ON citas.usuarioId=usuarios.id  ";
        $query .= " LEFT OUTER JOIN citasServicios ";
        $query .= " ON citasServicios.citaId=citas.id ";
        $query .= " LEFT OUTER JOIN servicios ";
        $query .= " ON servicios.id=citasServicios.servicioId ";
        $query .= " WHERE fecha =  '$fecha' ";

        $citas = AdminCita::SQL($query);
        
        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha
        ]);
    }
}
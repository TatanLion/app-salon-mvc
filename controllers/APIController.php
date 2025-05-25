<?php 

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController {

    public static function index(){

        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar(){

        // Almacena la cita y devuelve el ID
        // Creamos el objeto con lo enviado por la instacia en POST
        $cita = new Cita($_POST);
        // Guardamos la información en la BD con Active Record
        $resultado = $cita->guardar();

        // Es el id de la cita y lo guardamos como referencia
        $id = $resultado['id'];

        // Almacena la cita y el servicio, por cada servicio creado 
        $idServicios = explode(',', $_POST['servicios']);
        foreach($idServicios as $idServicio){
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        // Retornamos la respuesta del servidor
        echo json_encode(['resultado' => $resultado]);
    }
    
    public static function eliminar(){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita->eliminar();
            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }
}
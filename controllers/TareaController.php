<?php
namespace Controllers;
use Model\Proyecto;
use Model\Tarea;

class TareaController{
    public static function index() {

  
        $proyectoId = $_GET['url'];

        if(!$proyectoId) header('Location: /dashboard');

        $proyecto = Proyecto::where('url', $proyectoId);

        session_start();

        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) header('Location: /404');

        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);

        echo json_encode(['tareas' => $tareas]);
    }

    public static function crear(){
       if( $_SERVER['REQUEST_METHOD'] === 'POST'){
            session_start();
            $proyectoId = $_POST['proyectoId'];
            $usuario = $_SESSION['nombre'];
            $proyecto = Proyecto::where( 'url', $proyectoId);
            
            //  debuguear($usuario);
           

            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al agregar tu tarea',

                ];
                echo json_encode($respuesta);
                return;

            }
            $proyectoId = $_POST['proyectoId'];
            $proyecto = Proyecto::where('url', $proyectoId);

            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            $respuesta = [
                'tipo' => 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'Tarea Creada Correctamente',
                'proyectoId' => $proyecto->id,
            ];
            echo json_encode($respuesta);
            
            // debuguear($respuesta);
        }
        
    }


    public static function actualizar(){
        if( $_SERVER['REQUEST_METHOD'] === 'POST'){
            // VAlidar que el proyecto existe
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);
            
            session_start();
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al actualizar la tarea',

                ];
                echo json_encode($respuesta);
                return;

            }

            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();

            if($resultado){
                $respuesta = [
                    'tipo' => 'exito',
                    'mensaje' => 'Tarea Actualizada Correctamente',
                    'text' => 'Así se hace!!',
                    'id' => $tarea->id,
                    'proyectoId' => $proyecto->id,
                    'imageUrl' => "/build/img/check.jpg",
                    'imageWidth' => 250,
                    'imageHeight' => 300,
                    'imageAlt' => 'imagen Tarea Actualizada'
                ];

                echo json_encode(['respuesta' => $respuesta]);
            }
         }
         
     }

     public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Validar que el proyecto exista
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);

            session_start();

            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            } 

            $tarea = new Tarea($_POST);
            $resultado = $tarea->eliminar();


            $resultado = [
                'resultado' => $resultado,
                'mensaje' => 'Tarea Eliminada Correctamente',
                'tipo' => 'exito',
                'imageUrl' => "./build/img/basura_2.jpg",
                'imageWidth' => 250,
                'imageHeight' => 300,
                'imageAlt' => "imagen cubo de basura",
            ];
            
            echo json_encode($resultado);
        }
    }
}

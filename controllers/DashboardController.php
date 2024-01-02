<?php

namespace Controllers;
use Model\Proyecto;
use Model\Usuario;
use MVC\Router;


class DashboardController{
    public static function index(Router $router){
        session_start();
        isAuth();
        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        // debuguear($proyectos);
        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router){

        session_start();
        isAuth();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = new Proyecto($_POST);

            //Validar proyecto
            $alertas = $proyecto->validarProyecto();
            if(empty($alertas)){
                // Generar una URL única
                $proyecto->url = md5(uniqid());
                //Almacenar el propietario del proyecto
                $proyecto->propietarioId = $_SESSION['id'];
                // debuguear($proyecto);
                //Guardar el proyecto
                $proyecto->guardar();

                header('location: /proyecto?url=' . $proyecto->url);

            }

            // debuguear($proyecto);
        }

        $router->render('dashboard/crear_proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas,
        ]);
    }

    public static function proyecto(Router $router){
        session_start();
        isAuth();

        $token = $_GET['url'];

        if(!$token) header('location: /dashboard');

        $proyecto = Proyecto::where('url', $token);
        if($proyecto->propietarioId !== $_SESSION['id']){
            header('location: /dashboard');
        }
        // debuguear($proyecto);
        //Revisar que la persona que visita el proyecto es la que lo creo


        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto,
        ]);
    }

    public static function perfil(Router $router){
        session_start();
        isAuth();
        $alertas = [];

        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);
            
            $alertas = $usuario->validar_perfil();
            
            if(empty($alertas)){
                $existeUsuario = Usuario::where('email', $usuario->email);
                if($existeUsuario && $existeUsuario->id !== $usuario->id){
                    // Mensaje de error
                    Usuario::setAlerta('error', 'Ese email ya esta registrado');
                    $alertas = $usuario->getAlertas();
                }else{
                    // Guardar el registro
                    $usuario->guardar();

                    Usuario::setAlerta('exito', 'Guardado Correctmente');
                    $alertas = $usuario->getAlertas();
                    
                    $_SESSION['nombre'] = $usuario->nombre;
                }
            }
        }
        
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas,
        ]);
    }

    public static function cambiar_password(Router $router){
        session_start();
        isAuth();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevo_password();

            if(empty($alertas)){
                $resultado = $usuario->comprobar_Password();

                if($resultado){
                    //asignar nuevo password
                    $usuario->password = $usuario->password_nuevo;
                    // Eliminar propiedades no necesarias;
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);
                    // Hashear el password;
                    $usuario->hashPasword();

                    $resultado = $usuario->guardar();

                    if($resultado){
                        Usuario::setAlerta('exito', 'El password ha sido cambiado correctamente');
                    $alertas = $usuario->getAlertas();
                    }
                    // debuguear($usuario);
                }else{
                    Usuario::setAlerta('error', 'El password actual no es correcto');
                    $alertas = $usuario->getAlertas();
                }
                // debuguear($resultado);
            }
        }

        // debuguear($proyectos);
        $router->render('dashboard/cambiar_password', [
            'titulo' => 'Cambiar Password',
            'alertas' => $alertas,
            // 'usuario' => $usuario,
            
            
        ]);
    }

    public static function eliminar_proyecto() {
        session_start();
        isAuth();
 
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
 
            if($id) {
                $proyecto = Proyecto::find($id);
                if($proyecto->propietarioId === $_SESSION['id']) {
                    $id = $_POST['id'];
                    $proyecto = Proyecto::find($id);
                    $proyecto->eliminar();
                
                // Redireccionar
                header('Location: /dashboard'); 
                }
            }
        }
    }
}
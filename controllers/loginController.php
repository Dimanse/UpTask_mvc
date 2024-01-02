<?php

namespace Controllers;
use Classes\Email;
use Model\Usuario;
use MVC\Router;

class loginController{
    public static function login(Router $router){
        $alertas= [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);
            
            // debuguear($usuario);
            $alertas = $usuario->validarLogin();
            // debuguear($alertas);

            if(empty($alertas)){
                $usuario = Usuario::where('email', $usuario->email);
                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta('error','El Usuario no Existe o no ha sido confirmado');
                }else{
                    //El usuario existe
                    if(password_verify($_POST['password'], $usuario->password)){
                        session_start();
                        $_SESSION['id'] = $usuario->id;         
                        $_SESSION['nombre'] = $usuario->nombre;         
                        $_SESSION['email'] = $usuario->email;         
                        $_SESSION['login'] = true;         

                        header('location: /dashboard');
                        
                }else{
                    Usuario::setAlerta('error','El Password no es Correcto');
                }
            // debuguear($usuario);
            }
        }
    }

        $alertas = Usuario::getAlertas();
        //render a la vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas,
        ]);
    }


    public static function logout(Router $router){
        session_start();
        $_SESSION = [];
        header('location: /');
    }

    public static function crear(Router $router){
        $alertas = [];
        $usuario = new Usuario;
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario -> validarNuevaCuenta();
            
            if(empty($alertas)){
                $existeUsuario = Usuario::where('email', $usuario->email);

            if($existeUsuario){
                Usuario::setAlerta('error', 'El Usuario ya esta Registrado');
                $alertas = Usuario::getAlertas();
            }else{
                //Hashear el Password
                $usuario->hashPasword();
                //Eliminar password2
                unset($usuario->password2);
                $usuario->crearToken();
                
                //Enviar email
                $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                // debuguear($email);
                $email->enviarConfirmacion();

                //Crear un nuevo Usuario
                $resultado = $usuario -> guardar();
                if($resultado){
                    header('location: /mensaje');
                }
            }
            }
            // debuguear($existeUsuario);
        }


        //render a la vista
        $router->render('auth/crear', [
            'titulo' => 'Crea tu cuenta en UpTask',
            'usuario' => $usuario,
            'alertas' => $alertas,
        ]);
    }


    public static function olvide(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();
            if(empty($alertas)){
                $usuario = Usuario::where('email', $usuario->email);
                if($usuario ?? $usuario->confirmado){
                    $usuario->crearToken();
                    unset($usuario->password2);
                    
                    $usuario->guardar();

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInsrucciones();
                    
                    Usuario::setAlerta('exito','Hemos enviado las instrucciones a tu email');
                }else{
                    Usuario::setAlerta('error','El Usuario no existe o no esta confirmado');
                }
                
                // debuguear($usuario);
            }
        }
        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide', [
            'titulo' => 'Olvide mi pasword',
            'alertas' => $alertas,
        ]);
    }

    public static function reestablecer(Router $router){
        $alertas = [];
        $mostrar = true;

        $token = s($_GET['token']);
        if(!$token) header('location: /');

        $usuario = Usuario::where('token', $token);
        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token No Válido');
            $mostrar = false;
        }
        
    
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Añadir nuevo password
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPassword();
            if(empty($alertas)){
                $usuario->hashPasword();
                unset($usuario->password2);
                $usuario->token = null;
                $resultado = $usuario->guardar();
                
                if($resultado) header('location: /');
            }
            

            
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablece tu Password',
            'alertas' => $alertas,
            'mostrar' => $mostrar,           
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Exitosamente',
        ]);  
    }

    public static function confirmar(Router $router){
        $alertas = [];
        $token = s($_GET['token']);
        if(!$token) header('location: /');
        
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)){
            Usuario::setAlerta('error','Token no Valido');
            
        }else{
            unset($usuario->password2);
            $usuario->token = '';
            $usuario->confirmado = 1;
            $usuario -> guardar();
            Usuario::setAlerta('exito','Cuenta Confirmada Correctamente');
        }

        $alertas = Usuario::getAlertas();
        // debuguear($usuario);
        $router->render('auth/confirmar', [
            'titulo' => 'Confirma tu cuenta en UpTask',
            'alertas' => $alertas,
        ]);  
    }
}
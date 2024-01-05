<?php

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;

    }

    //Validación para cuentas nuevas
    public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error'] [] = 'El Nombre del Usuario es Obligatorio';
        }

        if(!$this->email){
            self::$alertas['error'] [] = 'El Email del Usuario es Obligatorio';
        }

        if(!$this->password){
            self::$alertas['error'] [] = 'El Password no Puede ir Vacio';
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'] [] = 'El Password Debe Tener al Menos 6 Caracteres';
        }
        if( $this->password !== $this->password2){
            self::$alertas['error'] [] = 'Los Passwords son diferentes';
        }
        return self::$alertas;
    }

    public function validar_perfil() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        return self::$alertas;
    }

    public function nuevo_password() : array {
        if(!$this->password_actual) {
            self::$alertas['error'][] = 'El Password Actual no puede ir vacio';
        }
        if(!$this->password_nuevo) {
            self::$alertas['error'][] = 'El Password Nuevo no puede ir vacio';
        }
        if(strlen($this->password_nuevo) < 6) {
            self::$alertas['error'][] = 'El Password debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    }


    //comprobar password actual con el password de la base de datos
    public function comprobar_password() : bool{
        return password_verify($this->password_actual, $this->password);
    }


    //Hashear Password
    public function hashPasword() : void{
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    //Crear un Token
    public function crearToken() : void{
        // $this->token = md5(uniqid());
        // md5 genera 32 caracteres debemos poner en la base de datos en el campo token un varchar(32)
        $this->token = uniqid();
    }

    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'] [] = 'El E-mail es Obligatorio';
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'] [] = 'Eso no parece un email valido';
        }
        return self::$alertas;
    }

    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'] [] = 'El Password no Puede ir Vacio';
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'] [] = 'El Password Debe Tener al Menos 6 Caracteres';
        }
        return self::$alertas;
    }


    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'] [] = 'El E-mail es Obligatorio';
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'] [] = 'Eso no parece un email valido';
        }

        if(!$this->password){
            self::$alertas['error'] [] = 'El Password es Obligatorio';
        }

        return self::$alertas;
    }
}
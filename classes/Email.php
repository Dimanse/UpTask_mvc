<?php

namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;

class Email{

    protected $email;
    protected $nombre;
    protected $token;
    public function __construct($email, $nombre, $token){

        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){

        
        // Crear el objeto de email
    
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASSWORD'];

        $mail->setFrom('domingocurbeira@gmail.com');
        $mail->addAddress('domingocurbeira@gmail.com','UpTask.com');
        $mail->Subject = 'Confirma tu Cuenta';

        // set html
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $mail->Body = "
        <html>
        <style>
        
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap');

        

        h2 {
            font-size: 25px;
            font-weight: 500;
            line-height: 25px;
        }
    
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fffff;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
        }
    
        p {
            line-height: 18px;
        }
    
        a {
            position: relative;
            z-index: 0;
            display: inline-block;
            margin: 20px 0;
        }
    
        a button {
            padding: 0.7em 2em;
            font-size: 16px !important;
            font-weight: 700;
            background-color: #7C3AED;
            color: #ffffff;
            border: none;
            text-transform: uppercase;
            cursor: pointer;
        }
        a button:hover{
            background-color: rgb(67, 56, 202);
        }
        p span {
            font-size: 16px;
            color: #9c9a9a;
        }
        div p{
            border-bottom: 1px solid #000000;
            border-top: none;
            margin-top: 40px;
        }

        .degradacion{
        background: linear-gradient(to right, rgba(124, 58, 237, 1) 0%, rgba(8, 145, 178, .5) 100%);
        color: transparent;
        -webkit-background-clip: text;
        
        font-weight: 700;
        
        }


    </style>
    <body>
        <h1 class='degradacion'>" . $this->nombre ."</h1>
        <h2>¡Gracias por registrarte en <span class='degradacion'>UpTask</span>!</h2>
        <p>Por favor confirma tu correo electrónico para que puedas comenzar a disfrutar de todos los servicios de
        <span class='degradacion'>UpTask</span></p>
        <a href='".$_ENV['APP_URL']."/confirmar?token=" . $this->token . "'><button>Verificar</button></a>
        <p>Si tú no te registraste en <span class='degradacion'>UpTask</span>, por favor ignora este correo electrónico.</p>
        <div><p></p></div>
        <p><span>Este correo electrónico fue enviado desde una dirección solamente de notificaciones que no puede aceptar correo electrónico entrante. Por favor no respondas a este mensaje.</span></p>
    </body>
    </html>";

        $mail->send();
    }

    public function enviarInsrucciones(){
        
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASSWORD'];

        $mail->setFrom('domingocurbeira@gmail.com');
        $mail->addAddress('domingocurbeira@gmail.com','Uptask.com');
        $mail->Subject = 'Reestablece tu Password';

        // set html
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $mail->Body = "
        <html>
        <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap');
        h2 {
            font-size: 25px;
            font-weight: 500;
            line-height: 25px;
        }
    
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fffff;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
        }
    
        p {
            line-height: 18px;
        }
    
        a {
            position: relative;
            z-index: 0;
            display: inline-block;
            margin: 20px 0;
        }
    
        a button {
            padding: 0.7em 2em;
            font-size: 16px !important;
            font-weight: 700;
            background: rgb(219, 39, 119);
            color: #ffffff;
            border: none;
            text-transform: uppercase;
            cursor: pointer;
        }
        a button:hover{
            background-color: rgb(194, 15, 95);
        }
        p span {
            font-size: 12px;
            color: #9c9a9a;
        }
        div p{
            border-bottom: 1px solid #000000;
            border-top: none;
            margin-top: 40px;
        }

        .degradacion{
            background: linear-gradient(to right, rgba(124, 58, 237, 1) 0%, rgba(8, 145, 178, .5) 100%);
            color: transparent;
            -webkit-background-clip: text;
            
            font-weight: 700;
            
        }

        .degradacion__heading{
            background: linear-gradient(to right, rgba(219, 39, 119, 1) 0%, rgba(245, 161, 199, .5) 100%);
            color: transparent;
            -webkit-background-clip: text;
            
            font-weight: 700;
            
        }

    </style>
    <body>
        <h1 class='degradacion__heading'>". $this->nombre . "</h1>
        <h2>¡Gracias por ponerte en contacto con <span class='degradacion'>UpTask</span>,  ". $this->nombre ."!</h2>
        <p>Has solicitado reestablecer tu password, haz click en el boton para hacerlo.</p>
        <a href='".$_ENV['APP_URL']."/reestablecer?token=" . $this->token . "'><button>Reestablecer Password</button></a>
        <p>Si tú no solicitaste esta información, por favor ignora este correo electrónico.</p>
        <div><p></p></div>
        <p><span>Este correo electrónico fue enviado desde una dirección solamente de notificaciones que no puede aceptar correo electrónico entrante. Por favor no respondas a este mensaje.</span></p>
    </body>
    </html>";

        $mail->send();
    }
} 
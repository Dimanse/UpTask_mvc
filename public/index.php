<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\DashboardController;
use Controllers\loginController;
use Controllers\TareaController;
use MVC\Router;
$router = new Router();

//Login
$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);
// $router->get('/', [new loginController(), 'login']);
// $router->post('/', [new loginController(), 'login']);

//Logout
$router->get('/logout', [LoginController::class, 'logout']);
// $router->get('/logout', [new loginController(), 'logout']);

//crear cuenta
$router->get('/crear', [LoginController::class, 'crear']);
$router->post('/crear', [LoginController::class, 'crear']);
// $router->get('/crear', [new loginController(), 'crear']);
// $router->post('/crear', [new loginController(), 'crear']);

//Formulario para olvide Password
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);
// $router->get('/olvide', [new loginController(), 'olvide']);
// $router->post('/olvide', [new loginController(), 'olvide']);


//Colocar nuevo password
$router->get('/reestablecer', [LoginController::class, 'reestablecer']);
$router->post('/reestablecer', [LoginController::class, 'reestablecer']);
// $router->get('/reestablecer', [new loginController(), 'reestablecer']);
// $router->post('/reestablecer', [new loginController(), 'reestablecer']);


//Confirmar Cuenta
$router->get('/mensaje', [LoginController::class, 'mensaje']);
$router->get('/confirmar', [LoginController::class, 'confirmar']);
// $router->get('/mensaje', [new loginController(), 'mensaje']);
// $router->get('/confirmar', [new loginController(), 'confirmar']);

//ZONA PRIVADA
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/crear_proyecto', [DashboardController::class, 'crear_proyecto']);
$router->post('/crear_proyecto', [DashboardController::class, 'crear_proyecto']);
$router->get('/proyecto', [DashboardController::class, 'proyecto']);
$router->post('/proyecto/eliminar', [DashboardController::class, 'eliminar_proyecto']);
$router->get('/perfil', [DashboardController::class, 'perfil']);
$router->post('/perfil', [DashboardController::class, 'perfil']);
$router->get('/cambiar_password', [DashboardController::class, 'cambiar_password']);
$router->post('/cambiar_password', [DashboardController::class, 'cambiar_password']);
// $router->get('/dashboard', [new DashboardController(), 'index']);
// $router->get('/crear_proyecto', [new DashboardController(), 'crear_proyecto']);
// $router->post('/crear_proyecto', [new DashboardController(), 'crear_proyecto']);
// $router->get('/proyecto', [new DashboardController(), 'proyecto']);
// $router->post('/proyecto/eliminar', [new DashboardController(), 'eliminar_proyecto']);
// $router->get('/perfil', [new DashboardController(), 'perfil']);
// $router->post('/perfil', [new DashboardController(), 'perfil']);
// $router->get('/cambiar_password', [new DashboardController(), 'cambiar_password']);
// $router->post('/cambiar_password', [new DashboardController(), 'cambiar_password']);

//API para las tareas
$router->get('/api/tareas', [TareaController::class, 'index']);
$router->post('/api/tarea', [TareaController::class, 'crear']);
$router->post('/api/tarea/actualizar', [TareaController::class, 'actualizar']);
$router->post('/api/tarea/eliminar', [TareaController::class, 'eliminar']);
// $router->get('/api/tareas', [new TareaController(), 'index']);
// $router->post('/api/tarea', [new TareaController(), 'crear']);
// $router->post('/api/tarea/actualizar', [new TareaController(), 'actualizar']);
// $router->post('/api/tarea/eliminar', [new TareaController(), 'eliminar']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
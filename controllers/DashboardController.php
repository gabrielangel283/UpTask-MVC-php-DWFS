<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController
{
    public static function index(Router $router)
    {
        session_start();

        isAuth();

        $id = $_SESSION['id'];

        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router)
    {
        session_start();

        isAuth();

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto($_POST);

            // validacion
            $alertas = $proyecto->validarProyecto();

            if (empty($alertas)) {

                // generar una URL unica
                $proyecto->url = md5(uniqid());

                // almacenar el creador de la url
                $proyecto->propietarioId = $_SESSION['id'];

                // guardar proyecto
                $proyecto->guardar();

                // redireccionar
                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }

        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyectos',
            'alertas' => $alertas
        ]);
    }

    public static function perfil(Router $router)
    {
        session_start();
        isAuth();

        $alertas = [];

        $usuario = Usuario::find($_SESSION['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validar_perfil();

            if (empty($alertas)) {
                // verificar que el email sea unico
                $existeUsuario = Usuario::where('email', $usuario->email);

                if ($existeUsuario && $existeUsuario->id != $usuario->id) {
                    // mostrar un error - el email debe ser unico en la bd
                    $usuario::setAlerta('error', 'El email no valido, ya esta registrada');
                    $alertas = $usuario->getAlertas();
                } else {
                    // guardar el usuario
                    $usuario->guardar();

                    $usuario::setAlerta('exito', 'Guardado correctamente');
                    $alertas = $usuario->getAlertas();

                    $_SESSION['nombre'] = $usuario->nombre;
                    $_SESSION['email'] = $usuario->email;
                }
            }
        }

        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }

    public static function proyecto(Router $router)
    {
        session_start();
        isAuth();

        // revisar que la persona que visita el proyecto sea la que lo creo
        $token = $_GET['id'];

        if (!$token) header('Location: /dashboard');

        $proyecto = Proyecto::where('url', $token);

        if ($proyecto->propietarioId !== $_SESSION['id']) {
            header('Location: /dashboard');
        }

        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function cambiar_password(Router $router)
    {
        session_start();
        isAuth();

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);

            // sincronizar con los datos del usuario
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevo_password();

            if (empty($alertas)) {
                $resultado = $usuario->comprobar_password();

                if ($resultado) {
                    // asignar el nuevo password
                    $usuario->password = $usuario->password_nuevo;

                    // eliminar atributos no necesarios
                    unset($usuario->password_nuevo);
                    unset($usuario->password_actual);

                    // hashear el nuevo password
                    $usuario->hashPassword();
                    $resultado = $usuario->guardar();

                    if ($resultado) {
                        $usuario::setAlerta('exito', 'Paaword actualizado correctamente');
                    }
                } else {
                    $usuario::setAlerta('error', 'Password incorrecto');
                }
            }

            $alertas = $usuario->getAlertas();
        }


        $router->render('dashboard/cambiar-password', [
            'alertas' => $alertas,
            'titulo' => 'Cambiar Password'
        ]);
    }
}

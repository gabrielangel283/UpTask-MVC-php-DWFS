<?php


namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = new Usuario(($_POST));

            $alertas = $usuario->validarLogin();

            if (empty($alertas)) {
                // verificar que el usuario exista
                $usuario = Usuario::where('email', $usuario->email);

                if (!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                } else {
                    // el usuario existe
                    if (password_verify($_POST['password'], $usuario->password)) {
                        // iniciar la sesion del usuario
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // redireccionar a la pagina principal de proyectos
                        header('Location: /dashboard');
                    } else {
                        Usuario::setAlerta('error', 'Password Incorrecto');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesion',
            'alertas' => $alertas
        ]);
    }


    public static function logout()
    {
        session_start();

        $_SESSION = [];
        header('Location: /');
    }


    public static function crear(Router $router)
    {
        $usuario = new Usuario();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarNuevaCuenta();

            if (empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);

                if ($existeUsuario) {
                    Usuario::setAlerta('error', 'El usuario ya etsa registrado');

                    $alertas = Usuario::getAlertas();
                } else { // crear el usuario nuevo
                    // hashear el password
                    $usuario->hashPassword();

                    // eliminar atributo de apoyo
                    unset($usuario->password2);

                    // generar token y no confirmado
                    $usuario->crearToken();
                    $usuario->confirmado = 0;

                    // guardar un nuevo usuario
                    $resultado = $usuario->guardar();

                    // enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear', [
            'titulo' => 'Crea tu cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = new Usuario($_POST);

            $alertas = $usuario->validarEmail();

            if (empty($alertas)) {
                // buscar el usuario con el email
                $usuario = Usuario::where('email', $usuario->email);

                if ($usuario && $usuario->confirmado == "1") {  // si el usuario existe

                    // generar un nuevo toke y quitar el atributo password2
                    $usuario->crearToken();
                    unset($usuario->password2);

                    // actualizar el usuario
                    $usuario->guardar();

                    // enviar el email
                    $mail = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $mail->enviarInstrucciones();

                    // imprimir la alerta
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide', [
            'titulo' => 'Olvide mi password',
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer(Router $router)
    {
        $token = s($_GET['token']);
        $mostrar = true;

        if (!$token) header('Location: /');

        // identificar el usuario que tenga el tokrn
        $usuario = Usuario::where('token', $token);

        if (!$usuario) {
            Usuario::setAlerta('error', 'Token no valido para restablecer un password');
            $mostrar = false;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // añadir el nuevo password
            $usuario->sincronizar($_POST);

            // validar el email
            $alertas = $usuario->validarPassword();

            if (empty($alertas)) {
                // hashear el nuevo password
                $usuario->hashPassword();

                // eliminar el token
                $usuario->token = null;

                // guardar el usuario nuevo
                $resultado = $usuario->guardar();

                if ($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje(Router $router)
    {

        $router->render('auth/mensaje', [
            'titulo' => 'Confirmacion de cuenta'
        ]);
    }

    public static function confirmar(Router $router)
    {
        $token = s($_GET['token']);

        if (!$token) {
            header('Location: /');
        }

        // encontrar al usuario del token
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            // no se encontro un usuario con ese token
            Usuario::setAlerta('error', 'Token no valido');
        } else {
            // confirmar la cuenta: estado de confirmado =1 ; eliminar password2; token = ""
            $usuario->confirmado = 1;
            unset($usuario->password2);
            $usuario->token = "";

            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta confirmada correctamente');
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar', [
            'titulo' => 'Confirmar cuenta UpTask',
            'alertas' => $alertas
        ]);
    }
}

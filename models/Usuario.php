<?php

namespace Model;

class Usuario extends ActiveRecord
{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $token;
    public $confirmado;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? "";
        $this->email = $args['email'] ?? "";
        $this->password = $args['password'] ?? "";
        $this->password2 = $args['password2'] ?? "";
        $this->password_actual = $args['password_actual'] ?? "";
        $this->password_nuevo = $args['password_nuevo'] ?? "";
        $this->token = $args['token'] ?? "";
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    public function validarLogin()
    {
        self::$alertas = [];

        if (!$this->email) {
            self::$alertas['error'][] = "El email del usuario es obligatorio";
        }

        if (!$this->password) {
            self::$alertas['error'][] = "El password del usuario no puede ir vacio";
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = "El email no es valido";
        }

        return self::$alertas;
    }

    public function validarNuevaCuenta()
    {
        self::$alertas = [];

        if (!$this->nombre) {
            self::$alertas['error'][] = "El nombre de usuario es obligatorio";
        }

        if (!$this->email) {
            self::$alertas['error'][] = "El email del usuario es obligatorio";
        }

        if (!$this->password) {
            self::$alertas['error'][] = "El password del usuario no puede ir vacio";
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = "El password debe contener almenos 6 caracteres";
        }

        if ($this->password != $this->password2) {
            self::$alertas['error'][] = "Los passwords son diferentes";
        }

        return self::$alertas;
    }

    public function validarEmail(): array
    {
        $alertas = [];

        if (!$this->email) {
            self::$alertas['error'][] = "Debes ingresar el email de recuperacion";
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = "El email no es valido";
        }

        return self::$alertas;
    }

    public function validarPassword(): array
    {
        $this::$alertas = [];

        if (!$this->password) {
            self::$alertas['error'][] = "El password no debe estar vacio";
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = "El password debe contener almenos 6 caracteres";
        }

        return self::$alertas;
    }

    public function validar_perfil(): array
    {
        $this::$alertas = [];

        if (!$this->nombre) {
            self::$alertas['error'][] = "El nombre es obligatorio";
        }

        if (!$this->email) {
            self::$alertas['error'][] = "El email es obligatorio";
        }

        return self::$alertas;
    }

    public function nuevo_password(): array
    {
        $this::$alertas = [];

        if (!$this->password_actual) {
            self::$alertas['error'][] = "El password actual no puede ir vacio";
        }

        if (!$this->password_nuevo) {
            self::$alertas['error'][] = "Debes ingresar el nuevo password";
        }

        if (strlen($this->password_nuevo) < 6) {
            self::$alertas['error'][] = "El nuevo password debe tener al menos 6 caracteres";
        }

        return self::$alertas;
    }

    public function comprobar_password(): bool
    {
        return password_verify($this->password_actual, $this->password);
    }

    public function hashPassword(): void
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken(): void
    {
        $this->token = md5(uniqid());
    }
}

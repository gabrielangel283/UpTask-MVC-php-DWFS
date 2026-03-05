<div class="contenedor crear">

    <?php include_once __DIR__ . "/../templates/nombre-sitio.php" ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta en Uptask</p>

        <?php include_once __DIR__ . "/../templates/alertas.php" ?>

        <form action="/crear" method="POST" class="formulario">

            <div class="campo">
                <label for="nombre">Nombre</label>
                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    placeholder="Tu Nombre"
                    value="<?php echo s($usuario->nombre) ?? '' ?>">
            </div>

            <div class="campo">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="ejemplo@gmail.com"
                    value="<?php echo s($usuario->email) ?? '' ?>">
            </div>

            <div class="campo">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Tu contraseña" />
            </div>

            <div class="campo">
                <label for="password">Confirma tu Password</label>
                <input
                    type="password"
                    id="password2"
                    name="password2"
                    placeholder="Confirma tu contraseña" />
            </div>

            <input type="submit" class="boton" value="Registrar Cuenta">
        </form>

        <div class="acciones">
            <a href="/">¿Tienes una cuenta? Logeate</a>

            <a href="/olvide">Recupera tu cuenta</a>
        </div>
    </div>
</div>
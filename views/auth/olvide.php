<div class="contenedor olvide">

    <?php include_once __DIR__ . "/../templates/nombre-sitio.php" ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupera tu acceso a UpTask</p>

        <?php include_once __DIR__ . "/../templates/alertas.php" ?>

        <form method="POST" class="formulario">
            <div class="campo">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="ejemplo@gmail.com">
            </div>

            <input type="submit" class="boton" value="Enviar correo">
        </form>

        <div class="acciones">
            <a href="/">¿Tienes una cuenta? Logeate</a>

            <a href="/crear">¿No tienes una cuenta?, Crea una</a>
        </div>
    </div>
</div>
<div class="contenedor reestablecer">

    <?php include_once __DIR__ . "/../templates/nombre-sitio.php" ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Ingresa el nuevo Password</p>

        <?php include_once __DIR__ . "/../templates/alertas.php" ?>


        <?php if ($mostrar) { ?>
            <form method="POST" class="formulario">

                <div class="campo">
                    <label for="password">Nuevo Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Tu contraseña">
                </div>

                <!-- <div class="campo">
                <label for="password">Confirma tu Password</label>
                <input
                    type="password"
                    id="password2"
                    name="password2"
                    placeholder="Confirma tu contraseña">
            </div> -->

                <input type="submit" class="boton" value="Guardar Password">
            </form>

        <?php } ?>

        <div class="acciones">
            <a href="/">¿Tienes una cuenta? Logeate</a>

            <a href="/olvide">Recupera tu cuenta</a>
        </div>
    </div>
</div>
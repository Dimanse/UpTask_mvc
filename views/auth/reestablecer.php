<div class="contenedor reestablecer">
   <?php include_once __DIR__ .'/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu Nuevo Password</p>
        <?php include_once __DIR__ .'/../templates/alertas.php'; ?>

        <?php if($mostrar) { ?>
        <form class="formulario" method="POST" >

            <div class="campo">
                <label for="password">Password</label>
                <input 
                    type="password"
                    id="password"
                    placeholder="Tu Password"
                    name="password"
                    >
            </div>

            <input class="boton" type="submit" value="Guardar Password">
        </form>
            
        <?php } ?>
        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
            <a href="/crear">¿Aún no tienes una cuenta? Crea una</a>
        </div>
    </div><!--contenedor-sm-->

</div>
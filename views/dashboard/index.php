
    <?php include_once __DIR__ . '/header-dashboard.php' ?>

<?php if (count($proyectos) === 0) { ?>
    <p class="no-proyectos">No hay proyectos aún <a href="/crear_proyecto">Comienza creando uno</a></p>
<?php } else { ?>
    <ul class="listado-proyectos">
        <?php foreach ($proyectos as $proyecto) { ?>
            <div class="caja">
                <form action="/proyecto/eliminar" method="post">
                    <input type="hidden" name="id" value="<?php echo $proyecto->id ?>">
                    <input type="button" class="btn btn-eliminar" value="x" />
                </form>
                <a href="/proyecto?url=<?php echo $proyecto->url; ?>">
                    <li class="proyecto">
                        <?php echo $proyecto->proyecto; ?>
                    </li>
                </a>
            </div>
        <?php } ?>
    </ul>
<?php } ?>
 
<?php include_once __DIR__ . '/footer-dashboard.php' ?>
<?php
    $script = "
        <script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script src='build/js/app.js'></script>
        <script src='build/js/proyecto.js'></script>
    ";
?>
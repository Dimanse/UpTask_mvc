(function(){

    obtenerTareas();
    let tareas = [];
    let filtradas = [];
    //Botón para agregar el modal de agregar tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', function(){
        mostrarFormulario();
    });

    // Filtrado por campos
    const filtros = document.querySelectorAll('.filtros input[type="radio"]');
    filtros.forEach(radio => {
        radio.addEventListener('input', filtrarTareas);
        
    });

    function filtrarTareas(e) {
        const filtro = e.target.value;

        if(filtro !== ''){
            filtradas = tareas.filter(tarea => tarea.estado === filtro); 
        }else{
            filtradas = [];
        }
        mostrarTareas();
    }

    async function obtenerTareas(){

        try {
            const id = obtenerProyecto();
            const url = `/api/tareas?url=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();
            tareas = resultado.tareas;
            mostrarTareas();
        } catch (error) {
            console.log(error);
        }

    }

    function mostrarTareas(){
        limpiarTareas();

        totalPendientes();
        totalCompletas();

        const arrayTareas = filtradas.length ? filtradas : tareas;
        if(arrayTareas.length === 0) {
            const contenedorTareas = document.querySelector('#listado-tareas');

            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No Hay Tareas';
            textoNoTareas.classList.add('no-tareas');

            contenedorTareas.appendChild(textoNoTareas);
            return;
        }

        const estados = {
            0: 'pendiente',
            1: 'completada',
        }

        arrayTareas.forEach(tarea => {
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add('tarea');
            
            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;
            nombreTarea.ondblclick = function(){
                mostrarFormulario(editar = true , {...tarea});
            }

            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            const btnEstado = document.createElement('BUTTON');
            btnEstado.classList.add('estado-tarea');
            btnEstado.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstado.dataset.estadoTarea = tarea.estado;
            btnEstado.textContent = estados[tarea.estado];
            btnEstado.ondblclick = function() {
                cambiarEstadoTarea({...tarea});
            }

            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.ondblclick = function(){
                confirmareliminarTarea({...tarea});
            }

            opcionesDiv.appendChild(btnEstado);
            opcionesDiv.appendChild(btnEliminarTarea);

            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            const listadoTareas = document.querySelector('#listado-tareas');
            listadoTareas.appendChild(contenedorTarea);
        });
    
    }

    function totalPendientes(){
        const totalPendientes = tareas.filter(tarea => tarea.estado === '0');
        const pendientesRadio = document.querySelector('#pendientes');
        if(totalPendientes.length === 0){
            pendientesRadio.disabled = true;
        }else{
            pendientesRadio.disabled = false;
        }
    }

    function totalCompletas(){
        const totalCompletas = tareas.filter(tarea => tarea.estado === '1');
        const completasRadio = document.querySelector('#completadas');
        if(totalCompletas.length === 0){
            completasRadio.disabled = true;
        }else{
            completasRadio.disabled = false;
        }
    }
    function mostrarFormulario(editar = false, tarea = {}){
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>${editar ? 'Editar Tarea' : 'Añade una nueva tarea'}</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input
                        type="text"
                        name="tarea"
                        id="tarea"
                        placeholder="${tarea.nombre ? 'Editar la Tarea' : 'Añadir Tarea al Proyecto Actual'}"
                        value="${tarea.nombre ? tarea.nombre : ''}"
                    />
                </div>
                <div class="opciones">
                    <input 
                        type="submit"
                        class="submit-nueva-tarea"
                        value="${tarea.nombre ? 'Guardar Cambios' : 'Añadir Tarea'}"
                        />
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>
        `;

        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);

        modal.addEventListener('click', function(e){
            e.preventDefault();
            if(e.target.classList.contains('cerrar-modal')){
                
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();
                }, 500);
            }
            if(e.target.classList.contains('submit-nueva-tarea')){
                const nombreTarea = document.querySelector('#tarea').value.trim();
                if(nombreTarea === ''){
                    mostrarAlerta('El Nombre de la Tarea es Obligatorio', 'error', document.querySelector('.formulario legend'));
                    return;
                }

                if(editar){
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea);
                }else{
                    agregarTarea(nombreTarea);
                }
                
            }
        });

        document.querySelector('.dashboard').appendChild(modal);
    }



    function mostrarAlerta(mensaje, tipo, referencia){
        const alertaPrevia = document.querySelector('.alerta');
        if(alertaPrevia){
            alertaPrevia.remove();
        }
        
        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        setTimeout(() => {
            alerta.remove();
        }, 5000);
    }

    async function agregarTarea(tarea){
        //Construir la petición
        const datos = new FormData();
        datos.append('nombre', tarea);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = '/api/tarea';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
            // console.log(resultado);

            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario legend'));

            if(resultado.tipo === 'exito') {
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();

                    //Recargar la pagina actual
                    // window.location.reload();

                    //Agregsar el objeton de tareas al global de tareas
                    const tareaObj = {
                        id: String(resultado.id),
                        nombre: tarea,
                        estado: '0',
                        proyectoId: resultado.proyectoId,
                    }

                    tareas = [...tareas, tareaObj];
                    mostrarTareas();
                }, 3000);
            }
            
        } catch (error) {
            console.log(error);
        }
    }

    function cambiarEstadoTarea(tarea){
        const nuevoEstado = tarea.estado === '1' ? '0' : '1';
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);
    }

    async function actualizarTarea(tarea){
        const { id, nombre, estado, proyectoId } = tarea;
        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        // for(let valor of datos.value()){
        //     console.log(valor);
        // }

        try {
            const url = '/api/tarea/actualizar';

            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos,
            });
            const resultado  = await respuesta.json();

            if(resultado.respuesta.tipo === 'exito'){
                // mostrarAlerta(
                //     resultado.respuesta.mensaje, 
                //     resultado.respuesta.tipo, 
                //     document.querySelector('.contenedor-nueva-tarea')
                // );
                Swal.fire({
                    title: resultado.respuesta.text,
                    text: resultado.respuesta.mensaje,
                    imageUrl: resultado.respuesta.imageUrl,
                    imageWidth: resultado.respuesta.imageWidth,
                    imageHeight: resultado.respuesta.imageHeight,
                    imageAlt: resultado.respuesta.imageAlt,
                    customClass: {
                        title: 'title',
                        text: 'text',
                      },
                    
                });

                const modal = document.querySelector('.modal');
                if(modal){
                    modal.remove();
                }

                tareas = tareas.map(tareaMemoria => {
                    if(tareaMemoria.id === id){
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }
                    return tareaMemoria;
                });

                mostrarTareas();
            }
            
        } catch (error) {
            console.log(error)
        }
    }

    function confirmareliminarTarea(tarea){
        Swal.fire({
            title: "¿Quieres eliminar esta tarea?",
            text: 'Dejamelo a mi!!',
            imageUrl: "./build/img/basura_3.jpg",
            imageWidth: 250,
            imageHeight: 300,
            imageAlt: "imagen cubo de basura",
            showCancelButton: true,
            confirmButtonText: "Si",
            cancelButtonText: "No",
            customClass: {
                confirmButton: 'custom-button',
                cancelButton: 'custom-button-cancel',
                title: 'title',
                text: 'text'
              },
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
              eliminarTarea(tarea);
            }
        });
    }

    async function eliminarTarea(tarea) {

        const {estado, id, nombre} = tarea;
        
        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = '/api/tarea/eliminar;'
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            
            const resultado = await respuesta.json();
            console.log(resultado);
            if(resultado.resultado) {
                // mostrarAlerta(
                //     resultado.mensaje, 
                //     resultado.tipo, 
                //     document.querySelector('.contenedor-nueva-tarea')
                // );

                Swal.fire({
                    title: "Listo!!, me deshice de ella",
                    text: resultado.mensaje,
                    imageUrl: resultado.imageUrl,
                    imageWidth: resultado.imageWidth,
                    imageHeight: resultado.imageHeight,
                    imageAlt: resultado.imageAlt,
                    customClass: {
                        confirmButton: 'custom-button-confirm',
                        cancelButton: 'custom-button-cancel',
                        title: 'title',
                        text: 'text',
                      },
                    
                });

                tareas = tareas.filter( tareaMemoria => tareaMemoria.id !== tarea.id);
                mostrarTareas();
            }
            
        } catch (error) {
            
        }
    }

    function obtenerProyecto() {
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.url
    }

    function limpiarTareas(){
        const listadoTareas = document.querySelector('#listado-tareas');
        while(listadoTareas.firstChild){
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
    }
})();
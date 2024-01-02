!function(){!async function(){try{const t="/api/tareas?url="+c(),a=await fetch(t),o=await a.json();e=o.tareas,n()}catch(e){console.log(e)}}();let e=[],t=[];document.querySelector("#agregar-tarea").addEventListener("click",(function(){o()}));function a(a){const o=a.target.value;t=""!==o?e.filter(e=>e.estado===o):[],n()}function n(){!function(){const e=document.querySelector("#listado-tareas");for(;e.firstChild;)e.removeChild(e.firstChild)}(),function(){const t=e.filter(e=>"0"===e.estado),a=document.querySelector("#pendientes");0===t.length?a.disabled=!0:a.disabled=!1}(),function(){const t=e.filter(e=>"1"===e.estado),a=document.querySelector("#completadas");0===t.length?a.disabled=!0:a.disabled=!1}();const a=t.length?t:e;if(0===a.length){const e=document.querySelector("#listado-tareas"),t=document.createElement("LI");return t.textContent="No Hay Tareas",t.classList.add("no-tareas"),void e.appendChild(t)}const i={0:"pendiente",1:"completada"};a.forEach(t=>{const a=document.createElement("LI");a.dataset.tareaId=t.id,a.classList.add("tarea");const s=document.createElement("P");s.textContent=t.nombre,s.ondblclick=function(){o(editar=!0,{...t})};const d=document.createElement("DIV");d.classList.add("opciones");const l=document.createElement("BUTTON");l.classList.add("estado-tarea"),l.classList.add(""+i[t.estado].toLowerCase()),l.dataset.estadoTarea=t.estado,l.textContent=i[t.estado],l.ondblclick=function(){!function(e){const t="1"===e.estado?"0":"1";e.estado=t,r(e)}({...t})};const m=document.createElement("BUTTON");m.classList.add("eliminar-tarea"),m.dataset.idTarea=t.id,m.textContent="Eliminar",m.ondblclick=function(){!function(t){Swal.fire({title:"¿Quieres eliminar esta tarea?",text:"Dejamelo a mi!!",imageUrl:"./build/img/basura_3.jpg",imageWidth:250,imageHeight:300,imageAlt:"imagen cubo de basura",showCancelButton:!0,confirmButtonText:"Si",cancelButtonText:"No",customClass:{confirmButton:"custom-button",cancelButton:"custom-button-cancel",title:"title",text:"text"}}).then(a=>{a.isConfirmed&&async function(t){const{estado:a,id:o,nombre:i}=t,r=new FormData;r.append("id",o),r.append("nombre",i),r.append("estado",a),r.append("proyectoId",c());try{const a=location.origin+"/api/tarea/eliminar",o=await fetch(a,{method:"POST",body:r}),i=await o.json();console.log(i),i.resultado&&(Swal.fire({title:"Listo!!, me deshice de ella",text:i.mensaje,imageUrl:i.imageUrl,imageWidth:i.imageWidth,imageHeight:i.imageHeight,imageAlt:i.imageAlt,customClass:{confirmButton:"custom-button-confirm",cancelButton:"custom-button-cancel",title:"title",text:"text"}}),e=e.filter(e=>e.id!==t.id),n())}catch(e){}}(t)})}({...t})},d.appendChild(l),d.appendChild(m),a.appendChild(s),a.appendChild(d);document.querySelector("#listado-tareas").appendChild(a)})}function o(t=!1,a={}){const o=document.createElement("DIV");o.classList.add("modal"),o.innerHTML=`\n            <form class="formulario nueva-tarea">\n                <legend>${t?"Editar Tarea":"Añade una nueva tarea"}</legend>\n                <div class="campo">\n                    <label>Tarea</label>\n                    <input\n                        type="text"\n                        name="tarea"\n                        id="tarea"\n                        placeholder="${a.nombre?"Editar la Tarea":"Añadir Tarea al Proyecto Actual"}"\n                        value="${a.nombre?a.nombre:""}"\n                    />\n                </div>\n                <div class="opciones">\n                    <input \n                        type="submit"\n                        class="submit-nueva-tarea"\n                        value="${a.nombre?"Guardar Cambios":"Añadir Tarea"}"\n                        />\n                    <button type="button" class="cerrar-modal">Cancelar</button>\n                </div>\n            </form>\n        `,setTimeout(()=>{document.querySelector(".formulario").classList.add("animar")},0),o.addEventListener("click",(function(s){if(s.preventDefault(),s.target.classList.contains("cerrar-modal")){document.querySelector(".formulario").classList.add("cerrar"),setTimeout(()=>{o.remove()},500)}if(s.target.classList.contains("submit-nueva-tarea")){const o=document.querySelector("#tarea").value.trim();if(""===o)return void i("El Nombre de la Tarea es Obligatorio","error",document.querySelector(".formulario legend"));t?(a.nombre=o,r(a)):async function(t){const a=new FormData;a.append("nombre",t),a.append("proyectoId",c());try{const o="/api/tarea",r=await fetch(o,{method:"POST",body:a}),c=await r.json();if(i(c.mensaje,c.tipo,document.querySelector(".formulario legend")),"exito"===c.tipo){const a=document.querySelector(".modal");setTimeout(()=>{a.remove();const o={id:String(c.id),nombre:t,estado:"0",proyectoId:c.proyectoId};e=[...e,o],n()},3e3)}}catch(e){console.log(e)}}(o)}})),document.querySelector(".dashboard").appendChild(o)}function i(e,t,a){const n=document.querySelector(".alerta");n&&n.remove();const o=document.createElement("DIV");o.classList.add("alerta",t),o.textContent=e,a.parentElement.insertBefore(o,a.nextElementSibling),setTimeout(()=>{o.remove()},5e3)}async function r(t){const{id:a,nombre:o,estado:i,proyectoId:r}=t,s=new FormData;s.append("id",a),s.append("nombre",o),s.append("estado",i),s.append("proyectoId",c());try{const t=location.origin+"/api/tarea/actualizar",r=await fetch(t,{method:"POST",body:s}),c=await r.json();if("exito"===c.respuesta.tipo){Swal.fire({title:c.respuesta.text,text:c.respuesta.mensaje,imageUrl:c.respuesta.imageUrl,imageWidth:c.respuesta.imageWidth,imageHeight:c.respuesta.imageHeight,imageAlt:c.respuesta.imageAlt,customClass:{title:"title",text:"text"}});const t=document.querySelector(".modal");t&&t.remove(),e=e.map(e=>(e.id===a&&(e.estado=i,e.nombre=o),e)),n()}}catch(e){console.log(e)}}function c(){const e=new URLSearchParams(window.location.search);return Object.fromEntries(e.entries()).url}document.querySelectorAll('.filtros input[type="radio"]').forEach(e=>{e.addEventListener("input",a)})}();
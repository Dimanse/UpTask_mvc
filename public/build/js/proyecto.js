!function(){if(document.querySelector(".btn-eliminar")){document.querySelectorAll(".btn-eliminar").forEach(e=>{e.addEventListener("click",(function(t){t.preventDefault();const n=e.parentNode;Swal.fire({title:"¿Estas seguro?",text:"¡Me deshare de ella rápidamente!",imageUrl:"/build/img/basura_2.webp",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",cancelButtonHeight:200,confirmButtonText:"¡Sí!",cancelButtonText:"¡No!"}).then(e=>{e.value&&n.submit()})}))})}}();
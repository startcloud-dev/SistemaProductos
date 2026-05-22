

document.addEventListener("DOMContentLoaded",() => {

// Cargar combos al iniciar
fetch("/config/Enrutador.php?action=cargarCombos")
.then(res => res.json())
.then(data => {
            
            const selectBodega = document.getElementById('cboBodega');
            selectBodega.innerHTML = '<option selected value="">Seleccione una bodega...</option>';
            data.bodegas.forEach(b => selectBodega.innerHTML += `<option value="${b.idbodega}">${b.nombre}</option>`);

            const selectSucursal = document.getElementById('cboSucursal');
            selectSucursal.innerHTML = '<option selected value="">Seleccione una sucursal...</option>';

            const selectMoneda = document.getElementById('cboMoneda');
            selectMoneda.innerHTML = '<option selected value="">Seleccione una moneda...</option>';
            data.monedas.forEach(m => selectMoneda.innerHTML += `<option value="${m.idmoneda}">${m.nombre}</option>`);

})

// Evento para cargar sucursales dependientes de la bodega
document.getElementById('cboBodega').addEventListener('change', function() {
    const idBodega = this.value;
    const selectSucursal = document.getElementById('cboSucursal');
    selectSucursal.innerHTML = '<option selected value="">Seleccione una sucursal...</option>';

    if (idBodega !== '') {
        fetch("/config/Enrutador.php?action=cargarSucursales&idBodega=" + idBodega)
        .then(res => res.json())
        .then(data => {
            data.forEach(s => {
                selectSucursal.innerHTML += `<option value="${s.idsucursal}">${s.nombre}</option>`;
            });
        });
    }
});


const formProducto = document.getElementById('formProducto');
formProducto.addEventListener('submit', async (e) => {
    e.preventDefault();

 
    const checkboxes = document.querySelectorAll('.opciones-checkbox input[type="checkbox"]:checked');
    const materiales = Array.from(checkboxes).map(cb => cb.value);

   
    const datos = {
        codigo: document.getElementById('txtCodigo').value,
        nombre: document.getElementById('txtNombre').value,
        idBodega: document.getElementById('cboBodega').value,
        idSucursal: document.getElementById('cboSucursal').value,
        idMoneda: document.getElementById('cboMoneda').value,
        precio: document.getElementById('txtPrecio').value,
        descripcion: document.getElementById('txtDescripcion').value,
        materiales: materiales
    };


    if(datos.codigo===""){
         alert("El código del producto no puede estar en blanco.");
         return;
    }
        console.log(datos.codigo.length);
        if(datos.codigo.length < 5 || datos.codigo.length > 15){
            alert("El código del producto debe tener entre 5 y 15 caracteres.");
            return;
        }
        const regexCodigo = /^[a-zA-Z0-9]+$/.test(datos.codigo);           
        const tieneLetra = /[a-zA-Z]/.test(datos.codigo);      
        const tieneNumero = /[0-9]/.test(datos.codigo);   
        if (!regexCodigo || !tieneLetra || !tieneNumero) {
            alert("El código del producto debe tener letras y números.");
            return;
        }

            const resultado = await fetch("/config/Enrutador.php?action=verificarCodigo", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ codigo: datos.codigo })
             });
            const data = await resultado.json();
        
            if (data.existe) {
                alert("El código del producto ya está registrado.");
                return;
            }

        if (datos.nombre === "") {
            alert("El nombre del producto no puede estar en blanco.");
            return;
        }

        if (datos.nombre.length < 2 || datos.nombre.length > 50) {
            alert("El nombre del producto debe tener entre 2 y 50 caracteres.");
            return;
        }

        if(datos.precio ===""){
            alert("El precio del producto no puede estar en blanco.");
            return;
        }      
        const regexPrecio = /^\d+(\.\d{1,2})?$/.test(datos.precio);

        if (!regexPrecio || parseFloat(datos.precio) <= 0) {
            alert("El precio del producto debe ser un número positivo con hasta dos decimales.");
            return;
        }

        if(datos.materiales.length<2){
             alert("Debe seleccionar al menos dos materiales para el producto.");
            return;
        }

        if(datos.idBodega ===""){
             alert("Debe seleccionar una bodega.");
            return;
        }
        
           if(datos.idMoneda ===""){
             alert("Debe seleccionar una moneda para el producto.");
             return;
            }

               if(datos.idSucursal ===""){
                alert("Debe seleccionar una sucursal para la bodega seleccionada.");
                return;
                }

                if (datos.descripcion === '') {
                alert("La descripción del producto no puede estar en blanco.");
                return;
            }
            if (datos.descripcion.length < 10 || datos.descripcion.length > 1000) {
                alert("La descripción del producto debe tener entre 10 y 1000 caracteres.");
                return;
            }

    fetch("/config/Enrutador.php?action=guardarProducto", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(datos)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            alert(data.message);
            formProducto.reset();
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(err => {
        alert("Error de conexión: " + err.message);
    });
});

});

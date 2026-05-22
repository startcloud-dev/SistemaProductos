

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
            data.sucursales.forEach(s => selectSucursal.innerHTML += `<option value="${s.idsucursal}">${s.nombre}</option>`);

            const selectMoneda = document.getElementById('cboMoneda');
            selectMoneda.innerHTML = '<option selected value="">Seleccione una moneda...</option>';
            data.monedas.forEach(m => selectMoneda.innerHTML += `<option value="${m.idmoneda}">${m.nombre}</option>`);

})


const formProducto = document.getElementById('formProducto');
formProducto.addEventListener('submit', (e) => {
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

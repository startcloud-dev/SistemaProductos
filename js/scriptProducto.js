

document.addEventListener("DOMContentLoaded",() => {

fetch("http://localhost:3000/config/Enrutador.php?action=cargarCombos")
.then(res => res.json())
.then(data => {

            const selectBodega = document.getElementById('cboBodega');
            selectBodega.innerHTML = '<option value="valor">Seleccione una bodega...</option>';
            data.bodegas.forEach(b => selectBodega.innerHTML += `<option value="${b.idbodega}">${b.nombre}</option>`);

})

});

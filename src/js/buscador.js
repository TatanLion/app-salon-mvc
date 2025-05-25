document.addEventListener('DOMContentLoaded', () =>{
    initialApp()
})

function initialApp(){
    buscarPorFecha()
}

function buscarPorFecha(){
    const fechaInput = document.querySelector('#fecha');
    fechaInput.addEventListener('input', (e) => {
        const fechaSeleccionada = e.target.value;
        window.location = `?fecha=${fechaSeleccionada}`
    })
}
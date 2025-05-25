let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
  id: "",
  nombre: "",
  fecha: "",
  hora: "",
  servicios: [],
};

document.addEventListener("DOMContentLoaded", () => {
  iniciarApp();
});

function iniciarApp() {
  mostrarSeccion();
  tabs(); // Cambia la sección cuando se presionen los tabs
  botonesPaginador();
  paginaSiguiente();
  paginaAnterior();

  consultarAPI(); // Consulta la API en el backend de PHP

  idCliente();
  nombreCliente();
  seleccionarFecha();
  seleccionarHora();

  mostrarResumen();
}

function mostrarSeccion() {
  // Ocultar la seccion que tenga la clase mostrar
  const seccionAnterior = document.querySelector(".mostrar");
  if (seccionAnterior) {
    seccionAnterior.classList.remove("mostrar");
  }

  // Seleccionar la sección con el paso...
  const pasoSelector = `#paso-${paso}`;
  const seccion = document.querySelector(pasoSelector);
  seccion.classList.add("mostrar");

  // Quitar clase del tab actual
  const tabAnterior = document.querySelector(".actual");
  if (tabAnterior) {
    tabAnterior.classList.remove("actual");
  }

  // Resalta el tab actual
  const tab = document.querySelector(`[data-paso="${paso}"]`);
  tab.classList.add("actual");
}

function tabs() {
  const botones = document.querySelectorAll(".tabs button");

  botones.forEach((boton) => {
    boton.addEventListener("click", function (e) {
      paso = parseInt(e.target.dataset.paso);
      mostrarSeccion();
      botonesPaginador();
    });
  });
}

function botonesPaginador() {
  const paginaAnterior = document.querySelector("#anterior");
  const paginaSiguiente = document.querySelector("#siguiente");

  if (paso === 1) {
    paginaAnterior.classList.add("ocultar");
    paginaSiguiente.classList.add("remove");
  } else if (paso === 3) {
    paginaAnterior.classList.remove("ocultar");
    paginaSiguiente.classList.add("ocultar");
    mostrarResumen();
  } else {
    paginaAnterior.classList.remove("ocultar");
    paginaSiguiente.classList.remove("ocultar");
  }

  mostrarSeccion();
}

function paginaAnterior() {
  const paginaAnterior = document.querySelector("#anterior");
  paginaAnterior.addEventListener("click", function () {
    if (paso <= pasoInicial) return;
    paso--;
    botonesPaginador();
  });
}

function paginaSiguiente() {
  const paginaSiguiente = document.querySelector("#siguiente");
  paginaSiguiente.addEventListener("click", function () {
    if (paso >= pasoFinal) return;
    paso++;
    botonesPaginador();
  });
}

async function consultarAPI() {
  try {
    const url = "http://localhost:3000/api/servicios";
    const resultado = await fetch(url);
    const servicios = await resultado.json();
    mostrarServicios(servicios);
  } catch (err) {
    console.log(err);
  }
}

function mostrarServicios(servicios) {
  servicios.forEach((servicio) => {
    const { id, nombre, precio } = servicio;

    const nombreServicio = document.createElement("P");
    nombreServicio.classList.add("nombre-servicio");
    nombreServicio.textContent = nombre;

    const precioServicio = document.createElement("P");
    precioServicio.classList.add("precio-servicio");
    precioServicio.textContent = `$${precio}`;

    const servicioDiv = document.createElement("div");
    servicioDiv.classList.add("servicio");
    servicioDiv.dataset.idServicio = id;
    servicioDiv.onclick = () => seleccionarServicio(servicio);

    servicioDiv.append(nombreServicio, precioServicio);

    document.querySelector("#servicios").appendChild(servicioDiv);
  });
}

function seleccionarServicio(servicio) {
  // Extraemos el ID del servicio que recibimos
  const { id } = servicio;
  // Traemos el arreglo de servicios de cita
  const { servicios } = cita;

  const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

  if (servicios.some((agregado) => agregado.id === id)) {
    // Eliminarlo
    cita.servicios = servicios.filter((agregado) => agregado.id !== id);
    divServicio.classList.remove("seleccionado");
  } else {
    cita.servicios = [...servicios, servicio];
    divServicio.classList.add("seleccionado");
  }

  // console.log(cita);
}

function idCliente() {
  cita.id = document.querySelector("#id").value;
}

function nombreCliente() {
  cita.nombre = document.querySelector("#nombre").value;
}

function seleccionarFecha() {
  const inputFecha = document.querySelector("#fecha");

  inputFecha.addEventListener("input", function (e) {
    const dia = new Date(e.target.value).getUTCDay(); // Obtenemos el número del día de la fecha = domingo = 0, lunes = 1

    // console.log(dia);

    if ([6, 0].includes(dia)) {
      e.target.value = "";
      mostrarAlerta("Fines de semana no atendemos", "error", ".formulario");
    } else {
      cita.fecha = e.target.value;
    }
  });
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
  // Previene que se impriman varias alertas
  const alertaPrevia = document.querySelector(".alerta");
  if (alertaPrevia) alertaPrevia.remove();

  // Creamos la alerta
  const alerta = document.createElement("div");
  alerta.textContent = mensaje;
  alerta.classList.add("alerta", tipo);

  // La mostramos en el HTML
  const referencia = document.querySelector(elemento);
  referencia.appendChild(alerta);

  // La removemos luego de 3 segundos
  if (desaparece) setTimeout(() => alerta.remove, 3000);
}

function seleccionarHora() {
  const inputHora = document.querySelector("#hora");
  inputHora.addEventListener("input", (e) => {
    const horaCita = e.target.value;
    const hora = horaCita.split(":")[0];
    const horaActual = new Date().getHours(); // Obtener la hora actual

    if (hora < horaActual || hora < 10 || hora > 18) {
      e.target.value = "";
      mostrarAlerta("Hora no válida", "error", ".formulario");
    } else {
      cita.hora = e.target.value;
    }
  });
}

function mostrarResumen() {
  const resumen = document.querySelector(".contenido-resumen");

  // Limpiar el contenido previo
  while (resumen.firstChild) {
    resumen.removeChild(resumen.firstChild);
  }

  // Error por datos vacios
  if (Object.values(cita).includes("") || cita.servicios.length <= 0) {
    mostrarAlerta(
      "Faltan datos de Servicios, Fecha y Hora",
      "error",
      ".contenido-resumen",
      false
    );
    return;
  }

  // Formatear el resumen
  const { nombre, fecha, hora, servicios } = cita;

  const nombreCliente = document.createElement("P");
  nombreCliente.innerHTML = `<span>Nombre: </span> ${nombre}`;

  // Formatear la fecha en español
  const fechaObj = new Date(fecha);
  const mes = fechaObj.getMonth();
  const dia = fechaObj.getDate(); // Se le suma dos que ya que cada instancion de Date le resta un día
  const year = fechaObj.getFullYear();

  const fechaUTC = new Date(Date.UTC(year, mes, dia));

  const opciones = {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  };
  const fechaFormateada = fechaUTC.toLocaleDateString("es-CO", opciones);

  const fechaCita = document.createElement("P");
  fechaCita.innerHTML = `<span>Fecha: </span> ${fechaFormateada}`;

  const horaCita = document.createElement("P");
  horaCita.innerHTML = `<span>Hora: </span> ${hora}`;

  resumen.append(nombreCliente, fechaCita, horaCita);

  servicios.forEach((servicio) => {
    const { id, precio, nombre } = servicio;

    const contenedorServicio = document.createElement("div");
    contenedorServicio.classList.add("contenedor-servicio");

    const textoServicio = document.createElement("p");
    textoServicio.textContent = nombre;

    const precioServicio = document.createElement("p");
    precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

    contenedorServicio.append(textoServicio, precioServicio);
    resumen.appendChild(contenedorServicio);
  });

  // Botón para crear una nueva cita
  const botonReservar = document.createElement("button");
  botonReservar.classList.add("boton");
  botonReservar.textContent = "Reservar Cita";
  botonReservar.onclick = reservarCita;

  resumen.append(botonReservar);
}

async function reservarCita() {
  const { id, fecha, hora, servicios } = cita;

  const idServicios = servicios.map((servicio) => servicio.id);

  // FormData es la forma de enviar datos por Fetch a un servidor
  const datos = new FormData();
  // Así se agrega información a ese arreglo
  datos.append("fecha", fecha);
  datos.append("hora", hora);
  datos.append("usuarioId", id);
  datos.append("servicios", idServicios);

  try {
    // Peticion hacia la API
    const url = "http://localhost:3000/api/citas";
    const respuesta = await fetch(url, {
      method: "POST", // Metodo para enviar y debe haber un endpoint
      body: datos, // Debemos enviar los datos del FormData
    });
    const resultado = await respuesta.json();
    if (resultado.resultado) {
      Swal.fire({
        icon: "success",
        title: "Cita Creada",
        text: "Tu cita fue creada correctamente",
        button: "OK",
      }).then(() => {
        window.location.reload();
      });
    }
  } catch (err) {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Hubo un error al intentar guardar la cita",
    });
  }

  // console.log(respuesta);

  // Forma de ver el formData
  // console.log([...datos]);
}

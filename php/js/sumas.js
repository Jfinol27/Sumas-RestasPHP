document.addEventListener("DOMContentLoaded", function () {
  const pagina1 = document.getElementById("pagina1");
  const pagina2 = document.getElementById("pagina2");
  const pagina3 = document.getElementById("pagina3");

  function ocultarPaginas() {
    pagina1.style.display = "none";
    pagina2.style.display = "none";
    pagina3.style.display = "none";
  }

  // Botón back page en el footer de la página 3
  const btnBack3 = document.getElementById("btnIrPagina2_desde3_footer");
  if (btnBack3) {
    btnBack3.addEventListener("click", function () {
      ocultarPaginas();
      pagina2.style.display = "block";
    });
  }
  // Botón back page en el footer de la página 2
  const btnBack2 = document.getElementById("btnIrPagina1_desde2_footer");
  if (btnBack2) {
    btnBack2.addEventListener("click", function () {
      ocultarPaginas();
      pagina1.style.display = "block";
    });
  }

  // Navegación con botones de footer
  const btnNext1 = document.getElementById("btnIrPagina2_desde1_footer");
  if (btnNext1) {
    btnNext1.addEventListener("click", function () {
      ocultarPaginas();
      pagina2.style.display = "block";
    });
  }

  const btnNext2 = document.getElementById("btnIrPagina3_desde2_footer");
  if (btnNext2) {
    btnNext2.addEventListener("click", function () {
      ocultarPaginas();
      pagina3.style.display = "block";
    });
  }

  function prepararPagina(pagina) {
  const ejerciciosAll = pagina.querySelectorAll(".suma");
  // solo las sumas no resueltas serán "clicables"
  const ejercicios = pagina.querySelectorAll(".suma:not(.resuelta-suma)");
  const contenedor = pagina;

    const panelRespuesta = document.createElement("div");
    panelRespuesta.id = "panelRespuesta";
    panelRespuesta.style.marginTop = "20px";
    panelRespuesta.style.textAlign = "center";

    const inputRespuesta = document.createElement("input");
    inputRespuesta.id = "inputRespuesta";
    inputRespuesta.type = "text";
    inputRespuesta.style.fontSize = "1.5em";
    inputRespuesta.style.width = "200px";
    inputRespuesta.style.textAlign = "left";
    inputRespuesta.setAttribute("readonly", true);
    panelRespuesta.appendChild(inputRespuesta);

    const botonesNumericos = document.createElement("div");
    botonesNumericos.id = "botonesNumericos";
    botonesNumericos.style.marginTop = "10px";

    for (let i = 1; i <= 9; i++) {
      const btnNum = document.createElement("button");
      btnNum.textContent = i;
      btnNum.style.fontSize = "1.5em";
      btnNum.style.width = "60px";
      btnNum.style.height = "60px";
      btnNum.addEventListener("click", () => {
        if (inputRespuesta.value.length < 10) {
          inputRespuesta.value = i + inputRespuesta.value;
        }
      });
      botonesNumericos.appendChild(btnNum);
    }

    const btnCero = document.createElement("button");
    btnCero.id = "btnCero";
    btnCero.textContent = "0";
    btnCero.style.fontSize = "1.5em";
    btnCero.style.width = "60px";
    btnCero.style.height = "60px";
    btnCero.style.gridColumn = "2 / 3";
    btnCero.addEventListener("click", () => {
      if (inputRespuesta.value.length < 10) {
        inputRespuesta.value = "0" + inputRespuesta.value;
      }
    });
    botonesNumericos.appendChild(btnCero);

    panelRespuesta.appendChild(botonesNumericos);

    const btnBorrar = document.createElement("button");
    btnBorrar.classList.add("actionBtn");
    btnBorrar.innerHTML = `<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="#fff" stroke-width="2" xmlns="http://www.w3.org/2000/svg"><path d="M3 6h18M8 6v12m8-12v12M5 6v12a2 2 0 002 2h10a2 2 0 002-2V6" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>`;
    btnBorrar.addEventListener("click", () => {
      inputRespuesta.value = "";
    });
    panelRespuesta.appendChild(btnBorrar);

    const btnEnviar = document.createElement("button");
    btnEnviar.classList.add("actionBtn");
    btnEnviar.innerHTML = `<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="#fff" stroke-width="2" xmlns="http://www.w3.org/2000/svg"><path d="M11.5003 12H5.41872M5.24634 12.7972L4.24158 15.7986C3.69128 17.4424 3.41613 18.2643 3.61359 18.7704C3.78506 19.21 4.15335 19.5432 4.6078 19.6701C5.13111 19.8161 5.92151 19.4604 7.50231 18.7491L17.6367 14.1886C19.1797 13.4942 19.9512 13.1471 20.1896 12.6648C20.3968 12.2458 20.3968 11.7541 20.1896 11.3351C19.9512 10.8529 19.1797 10.5057 17.6367 9.81135L7.48483 5.24303C5.90879 4.53382 5.12078 4.17921 4.59799 4.32468C4.14397 4.45101 3.77572 4.78336 3.60365 5.22209C3.40551 5.72728 3.67772 6.54741 4.22215 8.18767L5.24829 11.2793C5.34179 11.561 5.38855 11.7019 5.407 11.8459C5.42338 11.9738 5.42321 12.1032 5.40651 12.231C5.38768 12.375 5.34057 12.5157 5.24634 12.7972Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>`;
    let formActivo = null;
    btnEnviar.addEventListener("click", () => {
      if (!formActivo) return;
      if (inputRespuesta.value.trim() === "") {
        mostrarMensajePad(
          "Por favor, ingresa una respuesta usando el pad numérico.",
          false
        );
        return;
      }
      // Validar respuesta antes de enviar
      const num1 = parseInt(
        formActivo.parentElement.querySelector("div").textContent
      );
      const num2 = parseInt(
        formActivo.parentElement.querySelector(".numero-inferior").textContent
      );
      const respuesta = parseInt(inputRespuesta.value);
      if (respuesta !== num1 + num2) {
        mostrarMensajePad("Respuesta incorrecta. Intenta de nuevo.", false);
        return;
      }
      const inputHidden = formActivo.querySelector("input.input-pad-respuesta");
      if (inputHidden) inputHidden.value = inputRespuesta.value;
      formActivo.submit();
    });

    // Función para mostrar mensaje en el pad numérico
    function mostrarMensajePad(mensaje, correcto) {
      let msg = panelRespuesta.querySelector(".mensaje-pad");
      if (!msg) {
        msg = document.createElement("div");
        msg.className = "mensaje-pad";
        msg.style.marginTop = "10px";
        msg.style.fontWeight = "bold";
        panelRespuesta.appendChild(msg);
      }
      msg.textContent = mensaje;
      msg.style.color = correcto ? "green" : "red";
    }
    panelRespuesta.appendChild(btnEnviar);

    const btnVolver = document.createElement("button");
    btnVolver.classList.add("actionBtn");
    btnVolver.innerHTML = `<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="#fff" stroke-width="2" xmlns="http://www.w3.org/2000/svg"><path d="M4 10L3.64645 10.3536L3.29289 10L3.64645 9.64645L4 10ZM20.5 18C20.5 18.2761 20.2761 18.5 20 18.5C19.7239 18.5 19.5 18.2761 19.5 18L20.5 18ZM8.64645 15.3536L3.64645 10.3536L4.35355 9.64645L9.35355 14.6464L8.64645 15.3536ZM3.64645 9.64645L8.64645 4.64645L9.35355 5.35355L4.35355 10.3536L3.64645 9.64645ZM4 9.5L14 9.5L14 10.5L4 10.5L4 9.5ZM20.5 16L20.5 18L19.5 18L19.5 16L20.5 16ZM14 9.5C17.5898 9.5 20.5 12.4101 20.5 16L19.5 16C19.5 12.9624 17.0376 10.5 14 10.5L14 9.5Z"/></svg>`;
    btnVolver.addEventListener("click", () => {
      // Restaurar todas las tarjetas, incluidas las resueltas
      ejerciciosAll.forEach((e) => {
        e.style.display = "block";
        e.style.width = "";
        e.style.height = "";
        e.style.fontSize = "";
        e.classList.remove("centrado-grande");
        e.classList.remove("signo-derecha");
      });
      inputRespuesta.value = "";
      // Mostrar footer de navegación al volver
      if (footerNav) {
        footerNav.style.display = "flex";
      }
      if (panelRespuesta.parentNode) {
        panelRespuesta.parentNode.removeChild(panelRespuesta);
      }
    });
    panelRespuesta.appendChild(btnVolver);

    ejercicios.forEach((ejercicio) => {
      // Si la operación ya está resuelta, no permitir click
      if (ejercicio.querySelector(".resuelta")) {
        ejercicio.style.pointerEvents = "none";
        ejercicio.style.opacity = "0.6";
        return;
      }
      ejercicio.addEventListener("click", function () {
        // ocultar todas las tarjetas excepto la seleccionada (incluye resueltas)
        ejerciciosAll.forEach((e) => {
          if (e !== ejercicio) e.style.display = "none";
        });
        ejercicio.style.width = "40vw";
        ejercicio.style.height = "35vh";
        ejercicio.style.fontSize = "1.2em";
        ejercicio.classList.add("centrado-grande");
        // Ocultar footer de navegación al entrar al ejercicio
        if (footerNav) {
          footerNav.style.display = "none";
        }
        if (!contenedor.contains(panelRespuesta)) {
          contenedor.appendChild(panelRespuesta);
        }
        // Vincular el formulario de la suma seleccionada con el pad numérico
        const form = ejercicio.querySelector("form.form-respuesta");
        if (form) {
          inputRespuesta.value = "";
          const inputHidden = form.querySelector("input.input-pad-respuesta");
          if (inputHidden) inputHidden.value = "";
          formActivo = form;
        }
      });
    });
  }

  prepararPagina(pagina1);
  prepararPagina(pagina2);
  prepararPagina(pagina3);
});

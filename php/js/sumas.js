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
    const ejercicios = pagina.querySelectorAll(".suma");
    const contenedor = pagina;

    // Footer de navegación (SVGs)
    const footerNav = pagina.querySelector(
      'div[style*="display: flex"][style*="justify-content: center"][style*="gap: 32px"]'
    );

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
    btnBorrar.innerHTML = btnBorrar.innerHTML =
      '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 26 26"><path fill="#000000" d="M11.5-.031c-1.958 0-3.531 1.627-3.531 3.594V4H4c-.551 0-1 .449-1 1v1H2v2h2v15c0 1.645 1.355 3 3 3h12c1.645 0 3-1.355 3-3V8h2V6h-1V5c0-.551-.449-1-1-1h-3.969v-.438c0-1.966-1.573-3.593-3.531-3.593h-3zm0 2.062h3c.804 0 1.469.656 1.469 1.531V4H10.03v-.438c0-.875.665-1.53 1.469-1.53zM6 8h5.125c.124.013.247.031.375.031h3c.128 0 .25-.018.375-.031H20v15c0 .563-.437 1-1 1H7c-.563 0-1-.437-1-1V8zm2 2v12h2V10H8zm4 0v12h2V10h-2zm4 0v12h2V10h-2z"/></svg>';
    btnBorrar.classList.add("actionBtn");
    btnBorrar.title = "Borrar";
    btnBorrar.addEventListener("click", () => {
      inputRespuesta.value = "";
    });
    panelRespuesta.appendChild(btnBorrar);

    const btnEnviar = document.createElement("button");
    btnEnviar.innerHTML =
      '<svg width="36" height="36" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="none"><path fill="#78eb7b" d="M1.987 13.704a1.084 1.084 0 0 0 0 1.534l5.203 5.204c.424.423 1.11.423 1.534 0l13.289-13.29a1.084 1.084 0 0 0 0-1.533l-2.06-2.06a1.084 1.084 0 0 0-1.533 0L7.957 14.022L5.58 11.644a1.085 1.085 0 0 0-1.534 0z"/><path fill="#c9f7ca" d="M7.957 17.167L20.76 4.365l-.809-.809a1.085 1.085 0 0 0-1.534 0L7.957 14.022L5.58 11.644a1.084 1.084 0 0 0-1.534 0l-.809.809z"/><path stroke="#191919" stroke-linecap="round" stroke-linejoin="round" d="M1.987 13.704a1.084 1.084 0 0 0 0 1.534l5.203 5.204c.424.423 1.11.423 1.534 0l13.289-13.29a1.084 1.084 0 0 0 0-1.533l-2.06-2.06a1.084 1.084 0 0 0-1.533 0L7.957 14.022L5.58 11.644a1.085 1.085 0 0 0-1.534 0z"/></g></svg>';
    btnEnviar.classList.add("actionBtn");
    btnEnviar.title = "Enviar";
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
    btnVolver.innerHTML =
      '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"><path fill="#000000" d="M4.4 7.4L6.8 4h2.5L7.2 7h6.3a6.5 6.5 0 0 1 0 13H9l1-2h3.5a4.5 4.5 0 1 0 0-9H7.2l2.1 3H6.8L4.4 8.6L4 8z"/></svg>';
    btnVolver.classList.add("actionBtn");
    btnVolver.title = "Volver atrás";
    btnVolver.addEventListener("click", () => {
      ejercicios.forEach((e) => {
        e.style.display = "block";
        e.style.width = "";
        e.style.height = "";
        e.style.fontSize = "";
        e.classList.remove("centrado-grande");
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
        ejercicios.forEach((e) => {
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

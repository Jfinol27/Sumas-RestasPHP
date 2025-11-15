document.addEventListener("DOMContentLoaded", function () {
  const pagina1 = document.getElementById("pagina1");
  const pagina2 = document.getElementById("pagina2");
  const pagina3 = document.getElementById("pagina3");

  function ocultarPaginas() {
    pagina1.style.display = "none";
    pagina2.style.display = "none";
    pagina3.style.display = "none";
  }

  // Navegación con ids únicos para flechas
  document
    .getElementById("btnIrPagina2_desde1")
    ?.addEventListener("click", () => {
      ocultarPaginas();
      pagina2.style.display = "block";
    });

  document
    .getElementById("btnIrPagina1_desde2")
    ?.addEventListener("click", () => {
      ocultarPaginas();
      pagina1.style.display = "block";
    });

  document
    .getElementById("btnIrPagina3_desde2")
    ?.addEventListener("click", () => {
      ocultarPaginas();
      pagina3.style.display = "block";
    });

  document
    .getElementById("btnIrPagina2_desde3")
    ?.addEventListener("click", () => {
      ocultarPaginas();
      pagina2.style.display = "block";
    });

  function prepararPagina(pagina) {
    const ejercicios = pagina.querySelectorAll(".suma");
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
    btnBorrar.textContent = "Borrar";
    btnBorrar.classList.add("actionBtn");
    btnBorrar.addEventListener("click", () => {
      inputRespuesta.value = "";
    });
    panelRespuesta.appendChild(btnBorrar);

    const btnEnviar = document.createElement("button");
    btnEnviar.textContent = "Enviar";
    btnEnviar.classList.add("actionBtn");
    let formActivo = null;
    btnEnviar.addEventListener("click", () => {
      if (!formActivo) return;
      if (inputRespuesta.value.trim() === "") {
        alert("Por favor, ingresa una respuesta usando el pad numérico.");
        return;
      }
      const inputHidden = formActivo.querySelector("input.input-pad-respuesta");
      if (inputHidden) inputHidden.value = inputRespuesta.value;
      formActivo.submit();
    });
    panelRespuesta.appendChild(btnEnviar);

    const btnVolver = document.createElement("button");
    btnVolver.textContent = "Volver atrás";
    btnVolver.classList.add("actionBtn");
    btnVolver.addEventListener("click", () => {
      ejercicios.forEach((e) => {
        e.style.display = "block";
        e.style.width = "";
        e.style.height = "";
        e.style.fontSize = "";
        e.classList.remove("centrado-grande");
      });
      inputRespuesta.value = "";

      // Mostrar flechas de navegación al volver al menú
      const navFlechas = contenedor.querySelector(".nav-flechas");
      if (navFlechas) {
        navFlechas.style.display = "block";
      }

      if (panelRespuesta.parentNode) {
        panelRespuesta.parentNode.removeChild(panelRespuesta);
      }
    });
    panelRespuesta.appendChild(btnVolver);

    ejercicios.forEach((ejercicio) => {
      ejercicio.addEventListener("click", function () {
        ejercicios.forEach((e) => {
          if (e !== ejercicio) e.style.display = "none";
        });
        ejercicio.style.width = "40vw";
        ejercicio.style.height = "35vh";
        ejercicio.style.fontSize = "1.2em";
        ejercicio.classList.add("centrado-grande");

        // Ocultar flechas de navegación al entrar al ejercicio
        const navFlechas = contenedor.querySelector(".nav-flechas");
        if (navFlechas) {
          navFlechas.style.display = "none";
        }

        if (!contenedor.contains(panelRespuesta)) {
          contenedor.appendChild(panelRespuesta);
        }

        // Vincular el formulario de la suma seleccionada con el pad numérico
        const form = ejercicio.querySelector("form.form-respuesta");
        if (form) {
          // Limpiar el inputRespuesta y el input oculto al abrir
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

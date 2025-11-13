<!DOCTYPE html>
<html lang="es">
<head>
    <script src="js/restas.js"></script>
    <meta charset="UTF-8">
    <title>Restas Paginadas</title>
    <link rel="stylesheet" href="../css/restas.css">
</head>
<body>

<!-- Página 1 -->
<div class="contenedor" id="pagina1">
<?php
function generarNumero5Digitos() {
    return rand(10000, 99999);
}
echo '<div class="fila">';
for ($i = 0; $i < 8; $i++) {
    $num1 = generarNumero5Digitos();
    $num2 = generarNumero5Digitos();
    if ($num2 > $num1) {
        list($num1, $num2) = array($num2, $num1);
    }
    echo '<div class="resta">';
    echo '<div>'.$num1.'</div>';
    echo '<div class="linea-resta"></div>';
    echo '<div><span class="signo-menos">-</span><span class="numero-inferior">'.$num2.'</span></div>';
    echo '</div>';
    if (($i + 1) % 4 == 0 && $i != 7) {
        echo '</div><div class="fila">';
    }
}
echo '</div>';
?>
<div class="nav-flechas">
  <button id="btnIrPagina2_desde1" aria-label="Ir a la página 2" style="background:none;border:none;cursor:pointer;">
    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <line x1="5" y1="12" x2="19" y2="12" />
      <polyline points="12 5 19 12 12 19" />
    </svg>
  </button>
</div>
</div>

<!-- Página 2 -->
<div class="contenedor" id="pagina2" style="display:none;">
<?php
echo '<div class="fila">';
for ($i = 0; $i < 8; $i++) {
    $num1 = generarNumero5Digitos();
    $num2 = generarNumero5Digitos();
    if ($num2 > $num1) {
        list($num1, $num2) = array($num2, $num1);
    }
    echo '<div class="resta">';
    echo '<div>'.$num1.'</div>';
    echo '<div class="linea-resta"></div>';
    echo '<div><span class="signo-menos">-</span><span class="numero-inferior">'.$num2.'</span></div>';
    echo '</div>';
    if (($i + 1) % 4 == 0 && $i != 7) {
        echo '</div><div class="fila">';
    }
}
echo '</div>';
?>
<div class="nav-flechas">
  <button id="btnIrPagina1_desde2" aria-label="Ir a página 1" style="background:none;border:none;cursor:pointer;">
    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <line x1="19" y1="12" x2="5" y2="12" />
      <polyline points="12 19 5 12 12 5" />
    </svg>
  </button>
  <button id="btnIrPagina3_desde2" aria-label="Ir a página 3" style="background:none;border:none;cursor:pointer;">
    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <line x1="5" y1="12" x2="19" y2="12" />
      <polyline points="12 5 19 12 12 19" />
    </svg>
  </button>
</div>
</div>

<!-- Página 3 -->
<div class="contenedor" id="pagina3" style="display:none;">
<?php
echo '<div class="fila">';
for ($i = 0; $i < 8; $i++) {
    $num1 = generarNumero5Digitos();
    $num2 = generarNumero5Digitos();
    if ($num2 > $num1) {
        list($num1, $num2) = array($num2, $num1);
    }
    echo '<div class="resta">';
    echo '<div>'.$num1.'</div>';
    echo '<div class="linea-resta"></div>';
    echo '<div><span class="signo-menos">-</span><span class="numero-inferior">'.$num2.'</span></div>';
    echo '</div>';
    if (($i + 1) % 4 == 0 && $i != 7) {
        echo '</div><div class="fila">';
    }
}
echo '</div>';
?>
<div class="nav-flechas">
  <button id="btnIrPagina2_desde3" aria-label="Ir a página 2" style="background:none;border:none;cursor:pointer;">
    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <line x1="19" y1="12" x2="5" y2="12" />
      <polyline points="12 19 5 12 12 5" />
    </svg>
  </button>
</div>
</div>

</body>
</html>

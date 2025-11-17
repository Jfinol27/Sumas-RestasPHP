



<?php

session_start();
require_once __DIR__ . '/db.php';

function generarNumero5Digitos() {
  return rand(10000, 99999);
}

$paginas = 3;
$sumasPorPagina = 8;



// Si se solicita reiniciar, randomizar todos los ejercicios y limpiar estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reiniciar'])) {
  for ($p = 1; $p <= $paginas; $p++) {
    for ($i = 0; $i < $sumasPorPagina; $i++) {
      $_SESSION['sumas'][$p][$i] = [
        'num1' => generarNumero5Digitos(),
        'num2' => generarNumero5Digitos(),
        'resuelta' => false,
        'respuesta' => null
      ];
    }
  }
  // Limpiar historial del usuario si está logueado
  if (isset($_SESSION['usuario'])) {
    $stmt = $pdo->prepare('SELECT ID_Personas FROM login WHERE Usuario = ? LIMIT 1');
    $stmt->execute([$_SESSION['usuario']]);
    $row = $stmt->fetch();
    if ($row && isset($row['ID_Personas'])) {
      $id_persona = $row['ID_Personas'];
      // Borra solo historial de sumas (S_CantidadR IS NULL y P_CantidadR IS NOT NULL)
      $delete = $pdo->prepare('DELETE FROM historial WHERE ID_Personas = ? AND S_CantidadR IS NULL AND P_CantidadR IS NOT NULL');
      $delete->execute([$id_persona]);
    }
  }
  // Redirigir para evitar reenvío de formulario
  header('Location: sumas.php');
  exit();
}



// Variables de mensaje para retroalimentación
$mensaje = '';
$mensajePagina = null;
$mensajeIndice = null;

// Procesar respuesta del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pagina'], $_POST['indice'], $_POST['respuesta'])) {
  $pagina = (int)$_POST['pagina'];
  $indice = (int)$_POST['indice'];
  $respuesta = trim($_POST['respuesta']);
  if (isset($_SESSION['sumas'][$pagina][$indice])) {
    $suma = $_SESSION['sumas'][$pagina][$indice];
    $correcta = $suma['num1'] + $suma['num2'];
    $mensajePagina = $pagina;
    $mensajeIndice = $indice;
    if ($respuesta == $correcta) {
      $_SESSION['sumas'][$pagina][$indice]['resuelta'] = true;
      $_SESSION['sumas'][$pagina][$indice]['respuesta'] = $respuesta;
      $mensaje = '¡Correcto! La suma fue resuelta.';

      // Guardar en historial si está logueado
      if (isset($_SESSION['usuario'])) {
        // Obtener ID_Personas del usuario logueado
        $stmt = $pdo->prepare('SELECT ID_Personas FROM login WHERE Usuario = ? LIMIT 1');
        $stmt->execute([$_SESSION['usuario']]);
        $row = $stmt->fetch();
        if ($row && isset($row['ID_Personas'])) {
          $id_persona = $row['ID_Personas'];
          // Insertar en historial
          $insert = $pdo->prepare('INSERT INTO historial (P_CantidadR, S_CantidadR, ResultadoR, Estado, ID_Personas) VALUES (?, ?, ?, ?, ?)');
          $insert->execute([
            $suma['num1'],
            $suma['num2'],
            $respuesta,
            'Completa', // máximo 9 caracteres
            $id_persona
          ]);
        }
      }
    } else {
      $mensaje = 'Respuesta incorrecta. Intenta de nuevo.';
      // Mantener al usuario en la misma pestaña mostrando el mensaje
      // No hacer redirección ni cambio de página
    }
  }
}

// Inicializar sumas en sesión si no existen
if (!isset($_SESSION['sumas'])) {
  $_SESSION['sumas'] = [];
  for ($p = 1; $p <= $paginas; $p++) {
    $_SESSION['sumas'][$p] = [];
    for ($i = 0; $i < $sumasPorPagina; $i++) {
      $_SESSION['sumas'][$p][$i] = [
        'num1' => generarNumero5Digitos(),
        'num2' => generarNumero5Digitos(),
        'resuelta' => false,
        'respuesta' => null
      ];
    }
  }
}

// Randomizar solo las sumas no resueltas al volver al menú (cuando no es POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  for ($p = 1; $p <= $paginas; $p++) {
    for ($i = 0; $i < $sumasPorPagina; $i++) {
      if (empty($_SESSION['sumas'][$p][$i]['resuelta'])) {
        $_SESSION['sumas'][$p][$i]['num1'] = generarNumero5Digitos();
        $_SESSION['sumas'][$p][$i]['num2'] = generarNumero5Digitos();
      }
    }
  }
}
// Si se solicita reiniciar, randomizar todos los ejercicios y limpiar estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reiniciar'])) {
  for ($p = 1; $p <= $paginas; $p++) {
    for ($i = 0; $i < $sumasPorPagina; $i++) {
      $_SESSION['sumas'][$p][$i] = [
        'num1' => generarNumero5Digitos(),
        'num2' => generarNumero5Digitos(),
        'resuelta' => false,
        'respuesta' => null
      ];
    }
  }
  // Redirigir para evitar reenvío de formulario
  header('Location: sumas.php');
  exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <script src="js/sumas.js"></script>
    <meta charset="UTF-8">
    <title>Sumas Paginadas</title>
    <link rel="stylesheet" href="../css/sumas.css">
</head>

<body>





<!-- Página 1 -->
<div class="contenedor" id="pagina1">
<?php
echo '<div class="fila">';
for ($i = 0; $i < $sumasPorPagina; $i++) {
  $suma = $_SESSION['sumas'][1][$i];
  echo '<div class="suma">';
  echo '<div>'.$suma['num1'].'</div>';
  echo '<div class="linea-suma"></div>';
  echo '<div><span class="signo-mas">+</span><span class="numero-inferior">'.$suma['num2'].'</span></div>';
  // Formulario de respuesta
  if ($suma['resuelta']) {
    echo '<div class="resuelta">Resuelta ✔️<br>Respuesta: '.htmlspecialchars($suma['respuesta']).'</div>';
  } else {
  echo '<form method="post" class="form-respuesta" style="margin-top:10px;">';
  echo '<input type="hidden" name="pagina" value="1">';
  echo '<input type="hidden" name="indice" value="'.$i.'">';
  echo '<input type="hidden" name="respuesta" class="input-pad-respuesta" value="">';
  echo '</form>';
    if ($mensajePagina === 1 && $mensajeIndice === $i && $mensaje) {
      if ($mensaje === '¡Correcto! La suma fue resuelta.') {
        echo '<div style="color:green;font-weight:bold;margin-top:5px;">'.htmlspecialchars($mensaje).'</div>';
      } else {
        echo '<div style="color:red;font-weight:bold;margin-top:5px;">'.htmlspecialchars($mensaje).'</div>';
      }
    }
  }
  echo '</div>';
  if (($i + 1) % 4 == 0 && $i != $sumasPorPagina - 1) {
    echo '</div><div class="fila">';
  }
}
echo '</div>';
?>

<div style="margin: 20px 0 0 0; text-align: center; display: flex; justify-content: center; align-items: center; gap: 32px;">
  <form method="post" style="margin:0;">
    <button type="submit" name="reiniciar" style="background:none; border:none; cursor:pointer;" title="Recargar operaciones">
      <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#0074D9" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 2v6h-6"/>
        <path d="M3 12a9 9 0 0 1 15-6.7l3 3"/>
        <path d="M21 12a9 9 0 1 1-9-9"/>
      </svg>
    </button>
  </form>
  <!-- No poner botón de back page en la página 1 -->
  <a href="menu.php" class="btn-volver-menu" style="display:inline-block; background:none; border:none; cursor:pointer;" title="Volver al menú principal">
    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 20 20"><path fill="#000000" fill-rule="evenodd" d="M1 11C.08 11-.352 9.863.336 9.253l9-8a1 1 0 0 1 1.328 0l9 8C20.352 9.863 19.92 11 19 11h-1v7a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-7H1Zm6 6v-5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v5h3v-7a1 1 0 0 1 .512-.873L10 3.337l-6.512 5.79A1 1 0 0 1 4 10v7h3Zm2 0v-4h2v4H9Z" clip-rule="evenodd"/></svg>
  </a>
  <button id="btnIrPagina2_desde1_footer" aria-label="Ir a la página 2" style="background:none;border:none;cursor:pointer;">
    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
for ($i = 0; $i < $sumasPorPagina; $i++) {
  $suma = $_SESSION['sumas'][2][$i];
  echo '<div class="suma">';
  echo '<div>'.$suma['num1'].'</div>';
  echo '<div class="linea-suma"></div>';
  echo '<div><span class="signo-mas">+</span><span class="numero-inferior">'.$suma['num2'].'</span></div>';
  if ($suma['resuelta']) {
    echo '<div class="resuelta">Resuelta ✔️<br>Respuesta: '.htmlspecialchars($suma['respuesta']).'</div>';
  } else {
  echo '<form method="post" class="form-respuesta" style="margin-top:10px;">';
  echo '<input type="hidden" name="pagina" value="2">';
  echo '<input type="hidden" name="indice" value="'.$i.'">';
  echo '<input type="hidden" name="respuesta" class="input-pad-respuesta" value="">';
  echo '</form>';
    if ($mensajePagina === 2 && $mensajeIndice === $i && $mensaje) {
      if ($mensaje === '¡Correcto! La suma fue resuelta.') {
        echo '<div style="color:green;font-weight:bold;margin-top:5px;">'.htmlspecialchars($mensaje).'</div>';
      } else {
        echo '<div style="color:red;font-weight:bold;margin-top:5px;">'.htmlspecialchars($mensaje).'</div>';
      }
    }
  }
  echo '</div>';
  if (($i + 1) % 4 == 0 && $i != $sumasPorPagina - 1) {
    echo '</div><div class="fila">';
  }
}
echo '</div>';
?>

<div style="margin: 20px 0 0 0; text-align: center; display: flex; justify-content: center; align-items: center; gap: 32px;">
  <form method="post" style="margin:0;">
    <button type="submit" name="reiniciar" style="background:none; border:none; cursor:pointer;" title="Recargar operaciones">
      <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#0074D9" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 2v6h-6"/>
        <path d="M3 12a9 9 0 0 1 15-6.7l3 3"/>
        <path d="M21 12a9 9 0 1 1-9-9"/>
      </svg>
    </button>
  </form>
  <button id="btnIrPagina1_desde2_footer" aria-label="Ir a página 1" style="background:none;border:none;cursor:pointer;">
    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <line x1="19" y1="12" x2="5" y2="12" />
      <polyline points="12 19 5 12 12 5" />
    </svg>
  </button>
  <a href="menu.php" class="btn-volver-menu" style="display:inline-block; background:none; border:none; cursor:pointer;" title="Volver al menú principal">
    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 20 20"><path fill="#000000" fill-rule="evenodd" d="M1 11C.08 11-.352 9.863.336 9.253l9-8a1 1 0 0 1 1.328 0l9 8C20.352 9.863 19.92 11 19 11h-1v7a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-7H1Zm6 6v-5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v5h3v-7a1 1 0 0 1 .512-.873L10 3.337l-6.512 5.79A1 1 0 0 1 4 10v7h3Zm2 0v-4h2v4H9Z" clip-rule="evenodd"/></svg>
  </a>
  <button id="btnIrPagina3_desde2_footer" aria-label="Ir a página 3" style="background:none;border:none;cursor:pointer;">
    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
for ($i = 0; $i < $sumasPorPagina; $i++) {
  $suma = $_SESSION['sumas'][3][$i];
  echo '<div class="suma">';
  echo '<div>'.$suma['num1'].'</div>';
  echo '<div class="linea-suma"></div>';
  echo '<div><span class="signo-mas">+</span><span class="numero-inferior">'.$suma['num2'].'</span></div>';
  if ($suma['resuelta']) {
    echo '<div class="resuelta">Resuelta ✔️<br>Respuesta: '.htmlspecialchars($suma['respuesta']).'</div>';
  } else {
  echo '<form method="post" class="form-respuesta" style="margin-top:10px;">';
  echo '<input type="hidden" name="pagina" value="3">';
  echo '<input type="hidden" name="indice" value="'.$i.'">';
  echo '<input type="hidden" name="respuesta" class="input-pad-respuesta" value="">';
  echo '</form>';
    if ($mensajePagina === 3 && $mensajeIndice === $i && $mensaje) {
      if ($mensaje === '¡Correcto! La suma fue resuelta.') {
        echo '<div style="color:green;font-weight:bold;margin-top:5px;">'.htmlspecialchars($mensaje).'</div>';
      } else {
        echo '<div style="color:red;font-weight:bold;margin-top:5px;">'.htmlspecialchars($mensaje).'</div>';
      }
    }
  }
  echo '</div>';
  if (($i + 1) % 4 == 0 && $i != $sumasPorPagina - 1) {
    echo '</div><div class="fila">';
  }
}
echo '</div>';
?>

<div style="margin: 20px 0 0 0; text-align: center; display: flex; justify-content: center; align-items: center; gap: 32px;">
  <form method="post" style="margin:0;">
    <button type="submit" name="reiniciar" style="background:none; border:none; cursor:pointer;" title="Recargar operaciones">
      <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#0074D9" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 2v6h-6"/>
        <path d="M3 12a9 9 0 0 1 15-6.7l3 3"/>
        <path d="M21 12a9 9 0 1 1-9-9"/>
      </svg>
    </button>
  </form>
  <button id="btnIrPagina2_desde3_footer" aria-label="Ir a página 2" style="background:none;border:none;cursor:pointer;">
    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <line x1="19" y1="12" x2="5" y2="12" />
      <polyline points="12 19 5 12 12 5" />
    </svg>
  </button>
  <a href="menu.php" class="btn-volver-menu" style="display:inline-block; background:none; border:none; cursor:pointer;" title="Volver al menú principal">
    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 20 20"><path fill="#000000" fill-rule="evenodd" d="M1 11C.08 11-.352 9.863.336 9.253l9-8a1 1 0 0 1 1.328 0l9 8C20.352 9.863 19.92 11 19 11h-1v7a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-7H1Zm6 6v-5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v5h3v-7a1 1 0 0 1 .512-.873L10 3.337l-6.512 5.79A1 1 0 0 1 4 10v7h3Zm2 0v-4h2v4H9Z" clip-rule="evenodd"/></svg>
  </a>
</div>
</div>

</body>
</html>

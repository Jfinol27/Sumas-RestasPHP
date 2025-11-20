<?php
session_start();
require_once __DIR__ . '/db.php';
if (!isset($_SESSION['usuario'])) {
	header('Location: login.php');
	exit;
}
function generarNumero5Digitos() {
	return rand(10000, 99999);
}
$paginas = 3;
$restasPorPagina = 8;
// Si se solicita reiniciar, randomizar todos los ejercicios y limpiar estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reiniciar'])) {
	for ($p = 1; $p <= $paginas; $p++) {
		for ($i = 0; $i < $restasPorPagina; $i++) {
			$_SESSION['restas'][$p][$i] = [
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
			// Borra solo historial de restas (S_CantidadR IS NOT NULL y P_CantidadR IS NOT NULL)
			$delete = $pdo->prepare('DELETE FROM historial WHERE ID_Personas = ? AND S_CantidadR IS NOT NULL AND P_CantidadR IS NOT NULL');
			$delete->execute([$id_persona]);
		}
	}
	header('Location: restas.php');
	exit();
}
$mensaje = '';
$mensajePagina = null;
$mensajeIndice = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pagina'], $_POST['indice'], $_POST['respuesta'])) {
	$pagina = (int)$_POST['pagina'];
	$indice = (int)$_POST['indice'];
	$respuesta = trim($_POST['respuesta']);
	if (isset($_SESSION['restas'][$pagina][$indice])) {
		$resta = $_SESSION['restas'][$pagina][$indice];
		$num1 = $resta['num1'];
		$num2 = $resta['num2'];
		if ($num2 > $num1) list($num1, $num2) = array($num2, $num1);
		$correcta = $num1 - $num2;
		$mensajePagina = $pagina;
		$mensajeIndice = $indice;
		if ($respuesta == $correcta) {
			$_SESSION['restas'][$pagina][$indice]['resuelta'] = true;
			$_SESSION['restas'][$pagina][$indice]['respuesta'] = $respuesta;
			$mensaje = '¡Correcto! La resta fue resuelta.';

			// Guardar en historial si está logueado
			if (isset($_SESSION['usuario'])) {
				$stmt = $pdo->prepare('SELECT ID_Personas FROM login WHERE Usuario = ? LIMIT 1');
				$stmt->execute([$_SESSION['usuario']]);
				$row = $stmt->fetch();
				if ($row && isset($row['ID_Personas'])) {
					$id_persona = $row['ID_Personas'];
					$insert = $pdo->prepare('INSERT INTO historial (P_CantidadR, S_CantidadR, ResultadoR, Estado, ID_Personas) VALUES (?, ?, ?, ?, ?)');
					$insert->execute([
						$num1,
						$num2,
						$respuesta,
						'Completa', // máximo 9 caracteres
						$id_persona
					]);
				}
			}
		} else {
			$mensaje = 'Respuesta incorrecta. Intenta de nuevo.';
		}
	}
}
if (!isset($_SESSION['restas'])) {
	$_SESSION['restas'] = [];
	for ($p = 1; $p <= $paginas; $p++) {
		$_SESSION['restas'][$p] = [];
		for ($i = 0; $i < $restasPorPagina; $i++) {
			$_SESSION['restas'][$p][$i] = [
				'num1' => generarNumero5Digitos(),
				'num2' => generarNumero5Digitos(),
				'resuelta' => false,
				'respuesta' => null
			];
		}
	}
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	for ($p = 1; $p <= $paginas; $p++) {
		for ($i = 0; $i < $restasPorPagina; $i++) {
			if (empty($_SESSION['restas'][$p][$i]['resuelta'])) {
				$_SESSION['restas'][$p][$i]['num1'] = generarNumero5Digitos();
				$_SESSION['restas'][$p][$i]['num2'] = generarNumero5Digitos();
			}
		}
	}
}
// Verificar si todas las restas están resueltas
$todasResueltas = true;
for ($p = 1; $p <= $paginas; $p++) {
	for ($i = 0; $i < $restasPorPagina; $i++) {
		if (!$_SESSION['restas'][$p][$i]['resuelta']) {
			$todasResueltas = false;
			break 2;
		}
	}
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
		<script src="js/restas.js"></script>
		<meta charset="UTF-8">
		<title>Restas - Aprende y Diviértete</title>
		<link rel="stylesheet" href="css/restas.css">
</head>

<body>


<!-- Página 1 -->
<div class="contenedor" id="pagina1">
<h2 style="text-align: center; margin-bottom: 20px;">Página 1 de 3</h2>
<?php if (!$todasResueltas): ?>
<div style="position: absolute; top: 10px; right: 10px;">
  <a href="menu.php" class="btn-volver-menu">Volver al menú principal</a>
</div>
<?php endif; ?>
<?php
echo '<div class="fila">';
for ($i = 0; $i < $restasPorPagina; $i++) {
	$resta = $_SESSION['restas'][1][$i];
	$num1 = $resta['num1'];
	$num2 = $resta['num2'];
	if ($num2 > $num1) list($num1, $num2) = array($num2, $num1);
	echo '<div class="resta">';
	echo '<div>'.$num1.'</div>';
	echo '<div class="linea-resta"></div>';
	echo '<div><span class="signo-menos">-</span><span class="numero-inferior">'.$num2.'</span></div>';
	if ($resta['resuelta']) {
		echo '<div class="resuelta">Resuelta ✔️<br>Respuesta: '.htmlspecialchars($resta['respuesta']).'</div>';
	} else {
		echo '<form method="post" class="form-respuesta" style="margin-top:10px;">';
		echo '<input type="hidden" name="pagina" value="1">';
		echo '<input type="hidden" name="indice" value="'.$i.'">';
		echo '<input type="hidden" name="respuesta" class="input-pad-respuesta" value="">';
		echo '</form>';
		if ($mensajePagina === 1 && $mensajeIndice === $i && $mensaje) {
			if ($mensaje === '¡Correcto! La resta fue resuelta.') {
				echo '<div style="color:green;font-weight:bold;margin-top:5px;">'.htmlspecialchars($mensaje).'</div>';
			} else {
				echo '<div style="color:red;font-weight:bold;margin-top:5px;">'.htmlspecialchars($mensaje).'</div>';
			}
		}
	}
	echo '</div>';
	if (($i + 1) % 4 == 0 && $i != $restasPorPagina - 1) {
		echo '</div><div class="fila">';
	}
}
echo '</div>';
?>
<div class="pad-root" style="display:flex; justify-content:center; margin-top:12px;"></div>
<div class="footer-nav" style="margin: 20px 0 0 0; text-align: center; display: flex; justify-content: center; align-items: center; gap: 32px;">
	<form method="post" style="margin:0;">
		<button type="submit" name="reiniciar" style="background:none; border:none; cursor:pointer;" title="Recargar operaciones">
			<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M21 2v6h-6"/>
				<path d="M3 12a9 9 0 0 1 15-6.7l3 3"/>
				<path d="M21 12a9 9 0 1 1-9-9"/>
			</svg>
		</button>
	</form>
	<button id="btnIrPagina2_desde1_footer" aria-label="Ir a la página 2" style="background:none;border:none;cursor:pointer;">
		<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
			<line x1="5" y1="12" x2="19" y2="12" />
			<polyline points="12 5 19 12 12 19" />
		</svg>
	</button>
</div>
</div>

<!-- Página 2 -->
<div class="contenedor" id="pagina2" style="display:none;">
<h2 style="text-align: center; margin-bottom: 20px;">Página 2 de 3</h2>
<div style="position: absolute; top: 10px; right: 10px;">
  <a href="menu.php" class="btn-volver-menu">Volver al menú principal</a>
</div>
<?php
echo '<div class="fila">';
for ($i = 0; $i < $restasPorPagina; $i++) {
	$resta = $_SESSION['restas'][2][$i];
	$num1 = $resta['num1'];
	$num2 = $resta['num2'];
	if ($num2 > $num1) list($num1, $num2) = array($num2, $num1);
	echo '<div class="resta">';
	echo '<div>'.$num1.'</div>';
	echo '<div class="linea-resta"></div>';
	echo '<div><span class="signo-menos">-</span><span class="numero-inferior">'.$num2.'</span></div>';
	if ($resta['resuelta']) {
		echo '<div class="resuelta">Resuelta ✔️<br>Respuesta: '.htmlspecialchars($resta['respuesta']).'</div>';
	} else {
		echo '<form method="post" class="form-respuesta" style="margin-top:10px;">';
		echo '<input type="hidden" name="pagina" value="2">';
		echo '<input type="hidden" name="indice" value="'.$i.'">';
		echo '<input type="hidden" name="respuesta" class="input-pad-respuesta" value="">';
		echo '</form>';
		if ($mensajePagina === 2 && $mensajeIndice === $i && $mensaje) {
			if ($mensaje === '¡Correcto! La resta fue resuelta.') {
				echo '<div style="color:green;font-weight:bold;margin-top:5px;">'.htmlspecialchars($mensaje).'</div>';
			} else {
				echo '<div style="color:red;font-weight:bold;margin-top:5px;">'.htmlspecialchars($mensaje).'</div>';
			}
		}
	}
	echo '</div>';
	if (($i + 1) % 4 == 0 && $i != $restasPorPagina - 1) {
		echo '</div><div class="fila">';
	}
}
echo '</div>';
?>
<div class="pad-root" style="display:flex; justify-content:center; margin-top:12px;"></div>
<div class="footer-nav" style="margin: 20px 0 0 0; text-align: center; display: flex; justify-content: center; align-items: center; gap: 32px;">
	<form method="post" style="margin:0;">
		<button type="submit" name="reiniciar" style="background:none; border:none; cursor:pointer;" title="Recargar operaciones">
			<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M21 2v6h-6"/>
				<path d="M3 12a9 9 0 0 1 15-6.7l3 3"/>
				<path d="M21 12a9 9 0 1 1-9-9"/>
			</svg>
		</button>
	</form>
	<button id="btnIrPagina1_desde2_footer" aria-label="Ir a página 1" style="background:none;border:none;cursor:pointer;">
		<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
			<line x1="19" y1="12" x2="5" y2="12" />
			<polyline points="12 19 5 12 12 5" />
		</svg>
	</button>
	<button id="btnIrPagina3_desde2_footer" aria-label="Ir a página 3" style="background:none;border:none;cursor:pointer;">
		<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
			<line x1="5" y1="12" x2="19" y2="12" />
			<polyline points="12 5 19 12 12 19" />
		</svg>
	</button>
</div>
</div>

<!-- Página 3 -->
<div class="contenedor" id="pagina3" style="display:none;">
<div style="position: absolute; top: 10px; right: 10px;">
  <a href="menu.php" class="btn-volver-menu">Volver al menú principal</a>
</div>
<?php
echo '<div class="fila">';
for ($i = 0; $i < $restasPorPagina; $i++) {
	$resta = $_SESSION['restas'][3][$i];
	$num1 = $resta['num1'];
	$num2 = $resta['num2'];
	if ($num2 > $num1) list($num1, $num2) = array($num2, $num1);
	echo '<div class="resta">';
	echo '<div>'.$num1.'</div>';
	echo '<div class="linea-resta"></div>';
	echo '<div><span class="signo-menos">-</span><span class="numero-inferior">'.$num2.'</span></div>';
	if ($resta['resuelta']) {
		echo '<div class="resuelta">Resuelta ✔️<br>Respuesta: '.htmlspecialchars($resta['respuesta']).'</div>';
	} else {
		echo '<form method="post" class="form-respuesta" style="margin-top:10px;">';
		echo '<input type="hidden" name="pagina" value="3">';
		echo '<input type="hidden" name="indice" value="'.$i.'">';
		echo '<input type="hidden" name="respuesta" class="input-pad-respuesta" value="">';
		echo '</form>';
		if ($mensajePagina === 3 && $mensajeIndice === $i && $mensaje) {
			if ($mensaje === '¡Correcto! La resta fue resuelta.') {
				echo '<div style="color:green;font-weight:bold;margin-top:5px;">'.htmlspecialchars($mensaje).'</div>';
			} else {
				echo '<div style="color:red;font-weight:bold;margin-top:5px;">'.htmlspecialchars($mensaje).'</div>';
			}
		}
	}
	echo '</div>';
	if (($i + 1) % 4 == 0 && $i != $restasPorPagina - 1) {
		echo '</div><div class="fila">';
	}
}
echo '</div>';
?>
<div style="margin: 20px 0 0 0; text-align: center; display: flex; justify-content: center; align-items: center; gap: 32px;">
	<form method="post" style="margin:0;">
		<button type="submit" name="reiniciar" style="background:none; border:none; cursor:pointer;" title="Recargar operaciones">
			<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M21 2v6h-6"/>
				<path d="M3 12a9 9 0 0 1 15-6.7l3 3"/>
				<path d="M21 12a9 9 0 1 1-9-9"/>
			</svg>
		</button>
	</form>
	<button id="btnIrPagina2_desde3_footer" aria-label="Ir a página 2" style="background:none;border:none;cursor:pointer;">
		<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
			<line x1="19" y1="12" x2="5" y2="12" />
			<polyline points="12 19 5 12 12 5" />
		</svg>
	</button>
</div>
</div>

</body>
</html>

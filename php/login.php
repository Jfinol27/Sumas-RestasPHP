<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Autenticación de usuarios - Login
session_start();
	require_once __DIR__ . '/db.php';

$mensaje = '';

// Si ya está logueado, se redirige al menú
if (isset($_SESSION['usuario'])) {
	header('Location: menu.php');
	exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$usuario = trim($_POST['usuario'] ?? '');
	$clave = $_POST['clave'] ?? '';

	if ($usuario === '' || $clave === '') {
		$mensaje = 'Debe completar todos los campos.';
	} else {
		try {
			$stmt = $pdo->prepare('SELECT ID_Login, Usuario, Clave FROM login WHERE Usuario = ? LIMIT 1');
			$stmt->execute([$usuario]);
			$row = $stmt->fetch();
			if ($row && password_verify($clave, $row['Clave'])) {
				// Regenerar id de sesión para prevenir fijación
				session_regenerate_id(true);
				$_SESSION['usuario'] = $row['Usuario'];
				$_SESSION['user_id'] = $row['ID_Login'];

				// Obtener ID_Personas
				$stmt2 = $pdo->prepare('SELECT ID_Personas FROM login WHERE Usuario = ? LIMIT 1');
				$stmt2->execute([$row['Usuario']]);
				$personaRow = $stmt2->fetch();
				$id_persona = $personaRow ? $personaRow['ID_Personas'] : null;


				// Cargar historial de sumas (solo sumas)
				$_SESSION['sumas'] = [];
				$paginas = 3;
				$sumasPorPagina = 8;
				if ($id_persona) {
					$stmt3 = $pdo->prepare('SELECT P_CantidadR, S_CantidadR, ResultadoR FROM historial WHERE ID_Personas = ? AND Estado = ? ORDER BY ID_Historial ASC');
					$stmt3->execute([$id_persona, 'Completa']);
					$historial = $stmt3->fetchAll();
					$sumas = array_filter($historial, function($h) {
						return ($h['P_CantidadR'] + $h['S_CantidadR']) == $h['ResultadoR'];
					});
					$sumas = array_values($sumas);
					$idx = 0;
					for ($p = 1; $p <= $paginas; $p++) {
						$_SESSION['sumas'][$p] = [];
						for ($i = 0; $i < $sumasPorPagina; $i++) {
							if (isset($sumas[$idx])) {
								$h = $sumas[$idx];
								$_SESSION['sumas'][$p][$i] = [
									'num1' => $h['P_CantidadR'],
									'num2' => $h['S_CantidadR'],
									'resuelta' => true,
									'respuesta' => $h['ResultadoR']
								];
							} else {
								$_SESSION['sumas'][$p][$i] = [
									'num1' => rand(10000, 99999),
									'num2' => rand(10000, 99999),
									'resuelta' => false,
									'respuesta' => null
								];
							}
							$idx++;
						}
					}
				}

				// Cargar historial de restas (solo restas)
				$_SESSION['restas'] = [];
				$restasPorPagina = 8;
				if ($id_persona) {
					$stmt4 = $pdo->prepare('SELECT P_CantidadR, S_CantidadR, ResultadoR FROM historial WHERE ID_Personas = ? AND Estado = ? ORDER BY ID_Historial ASC');
					$stmt4->execute([$id_persona, 'Completa']);
					$historialR = $stmt4->fetchAll();
					$restas = array_filter($historialR, function($h) {
						return ($h['P_CantidadR'] - $h['S_CantidadR']) == $h['ResultadoR'] || ($h['S_CantidadR'] - $h['P_CantidadR']) == $h['ResultadoR'];
					});
					$restas = array_values($restas);
					$idx = 0;
					for ($p = 1; $p <= $paginas; $p++) {
						$_SESSION['restas'][$p] = [];
						for ($i = 0; $i < $restasPorPagina; $i++) {
							if (isset($restas[$idx])) {
								$h = $restas[$idx];
								$_SESSION['restas'][$p][$i] = [
									'num1' => $h['P_CantidadR'],
									'num2' => $h['S_CantidadR'],
									'resuelta' => true,
									'respuesta' => $h['ResultadoR']
								];
							} else {
								$_SESSION['restas'][$p][$i] = [
									'num1' => rand(10000, 99999),
									'num2' => rand(10000, 99999),
									'resuelta' => false,
									'respuesta' => null
								];
							}
							$idx++;
						}
					}
				}

				header('Location: menu.php');
				exit();
			} else {
				$mensaje = 'Usuario o contraseña incorrectos.';
			}
		} catch (Exception $e) {
			$mensaje = 'Error al procesar el login.'; // No exponer detalles
		}
	}
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">	
	<title>LOGIN</title>
<link rel="stylesheet" href="/css/login.css">

</head>
<body>
<h1 class="titulo">Aprende y Diviertete</h1>
<div class="login">

	<?php if ($mensaje): ?>
		<div class="error"><?= $mensaje ?></div>
	<?php endif; ?>
	<form method="post" autocomplete="off">
        <h2>Iniciar sesión</h2>
		<input type="text" name="usuario" placeholder="Usuario" required maxlength="50" autofocus>
		<input type="password" name="clave" placeholder="Clave" required minlength="6">
		<button type="submit">Iniciar sesión</button>
	</form>
	<div class="register-link">
		¿Aun no tienes cuenta? <a href="registro.php">Crear cuenta nueva</a>
	</div>
</div>
</body>
</html>
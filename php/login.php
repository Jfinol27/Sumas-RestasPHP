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
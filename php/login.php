<?php
session_start();


$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$usuario = $_POST['usuario'] ?? '';
	$clave = $_POST['clave'] ?? '';
	// Usuario y clave fijos para ejemplo
	if ($usuario === 'admin' && $clave === '1234') {
		$_SESSION['usuario'] = $usuario;
		header('Location: menu.php');
		exit();
	} else {
		$mensaje = 'Usuario o clave incorrectos';
	}
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">	
	<title>LOGIN</title>
<link rel="stylesheet" href="../css/login.css">

</head>
<body>
<h1 class="titulo">Aprende y Diviertete</h1>
<div class="login">

	<?php if ($mensaje): ?>
		<div class="error"><?= $mensaje ?></div>
	<?php endif; ?>
	<form method="post">
        <h2>Iniciar sesión</h2>
		<input type="text" name="usuario" placeholder="Usuario" required autofocus>
		<input type="password" name="clave" placeholder="Clave" required>
		<button type="submit">Iniciar sesión</button>
	</form>
	<div class="register-link">
		¿Aun no tienes cuenta? <a href="registro.php">Crear cuenta nueva</a>
	</div>
</div>
</body>
</html>
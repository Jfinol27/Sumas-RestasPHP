<?php

// Inicia la sesión para poder guardar el usuario logueado
session_start();

// Variable para mostrar mensajes de error o éxito
$mensaje = '';

// Si el formulario fue enviado (método POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene los valores enviados por el usuario
    $usuario = trim($_POST['usuario'] ?? '');
    $clave = $_POST['clave'] ?? '';
    $clave2 = $_POST['clave2'] ?? '';
    // Aquí puedes agregar validaciones básicas si lo deseas
    // Por ahora, no se realiza registro ni guardado
    $mensaje = 'Funcionalidad de registro pendiente de base de datos.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>REGISTRO</title>
    <link rel="stylesheet" href="../css/registro.css">
</head>
<body>
<!-- Contenedor principal del formulario de registro -->
<div class="registro">
    <h2>Crear cuenta</h2>
    <!-- Muestra mensajes de error si existen -->
    <?php if ($mensaje): ?>
        <div class="error"><?= $mensaje ?></div>
    <?php endif; ?>
    <!-- Formulario de registro -->
    <form method="post">
        <input type="text" name="usuario" placeholder="Usuario" required autofocus>
        <input type="password" name="clave" placeholder="Clave" required>
        <input type="password" name="clave2" placeholder="Repetir clave" required>
        <button type="submit">Registrar</button>
    </form>
    <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
</div>
</body>
</html>

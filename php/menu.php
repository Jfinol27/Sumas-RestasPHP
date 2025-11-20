<?php
session_start();
// Proteger acceso: solo usuarios autenticados
if (!isset($_SESSION['usuario'])) {
        header('Location: login.php');
        exit;
}
require_once __DIR__ . '/db.php';
$usuario = htmlspecialchars($_SESSION['usuario']);

// Obtener nombre y apellido desde la base de datos
$stmt = $pdo->prepare('SELECT p.Nombre, p.Apellido FROM personas p INNER JOIN login l ON p.ID_Personas = l.ID_Personas WHERE l.Usuario = ? LIMIT 1');
$stmt->execute([$usuario]);
$persona = $stmt->fetch();
$nombreCompleto = $persona ? htmlspecialchars($persona['Nombre'] . ' ' . $persona['Apellido']) : $usuario;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú Principal</title>
	<link rel="stylesheet" href="../css/menu.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div class="bienvenido-header">
                <div class="welcome-title">¡Hola, <b><?= $nombreCompleto ?></b>!</div>
                <div class="welcome-subtitle">Listo para practicar y divertirte con ejercicios de matemáticas?</div>
            </div>
            <form method="post" action="logout.php" style="display:inline;">
                <button class="logout-btn floating" type="submit">Cerrar sesión</button>
            </form>
        </div>
    </header>
    <div class="container">
    <div class="bienvenido">Bienvenido, <b><?= $nombreCompleto ?></b></div>
        <div class="mensaje">Selecciona la operación que deseas practicar:</div>
        <div class="opciones">
            <form action="sumas.php" method="get" style="margin:0;">
                <button class="opcion-btn" type="submit">Sumas +
                </button>
            </form>
            <form action="restas.php" method="get" style="margin:0;">
                <button class="opcion-btn" type="submit">Restas -</button>
            </form>
        </div>
    </div>
</body>
</html>

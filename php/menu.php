<?php
session_start();
// Proteger acceso: solo usuarios autenticados
if (!isset($_SESSION['usuario'])) {
        header('Location: login.php');
        exit;
}
$usuario = htmlspecialchars($_SESSION['usuario']);
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
            <div class="bienvenido-header">Bienvenido, <b><?= $usuario ?></b></div>
            <form method="post" action="logout.php" style="display:inline;">
                <button class="logout-btn" type="submit">Cerrar sesión</button>
            </form>
        </div>
    </header>
    <div class="container">
        <div class="bienvenido">Bienvenido, <b><?= $usuario ?></b></div>
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

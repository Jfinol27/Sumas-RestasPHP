
<?php
// Registro de nuevos usuarios
session_start();
require_once __DIR__ . '/db.php';


// Si ya está logueado, no necesita registrarse
if (isset($_SESSION['usuario'])) {
    header('Location: menu.php');
    exit();
}

$mensaje = '';
$exito = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $clave = $_POST['clave'] ?? '';
    $clave2 = $_POST['clave2'] ?? '';
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $edad = trim($_POST['edad'] ?? '');
    $seccion = trim($_POST['seccion'] ?? '');

    // Validaciones
    if ($usuario === '' || $clave === '' || $clave2 === '' || $nombre === '' || $apellido === '' || $edad === '' || $seccion === '') {
        $mensaje = 'Todos los campos son obligatorios.';
    } elseif (strlen($usuario) < 3 || strlen($usuario) > 50) {
        $mensaje = 'El usuario debe tener entre 3 y 50 caracteres.';
    } elseif ($clave !== $clave2) {
        $mensaje = 'Las contraseñas no coinciden.';
    } elseif (strlen($clave) < 6) {
        $mensaje = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif (!ctype_digit($edad) || (int)$edad < 4 || (int)$edad > 120) {
        $mensaje = 'Ingresa una edad válida.';
    } elseif (strlen($seccion) > 10) {
        $mensaje = 'La sección debe tener como máximo 10 caracteres.';
    } else {
        try {
            // Verificar si usuario ya existe
            $stmt = $pdo->prepare('SELECT 1 FROM login WHERE Usuario = ? LIMIT 1');
            $stmt->execute([$usuario]);
            if ($stmt->fetch()) {
                $mensaje = 'El nombre de usuario ya está registrado.';
            } else {
                // Usar transacción: primero crear la persona y luego el login para respetar la FK
                $pdo->beginTransaction();
                $pstmt = $pdo->prepare('INSERT INTO personas (Nombre, Apellido, Edad, Seccion) VALUES (?, ?, ?, ?)');
                $pstmt->execute([$nombre, $apellido, (int)$edad, $seccion]);
                $id_persona = $pdo->lastInsertId();

                // Hash de la contraseña
                $hash = password_hash($clave, PASSWORD_DEFAULT);
                $insert = $pdo->prepare('INSERT INTO login (Usuario, Clave, ID_Personas) VALUES (?, ?, ?)');
                $insert->execute([$usuario, $hash, $id_persona]);

                $pdo->commit();
                $exito = true;
                $mensaje = 'Registro exitoso. Ahora puedes iniciar sesión.';
            }
        } catch (Exception $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $mensaje = 'Error al registrar el usuario: ' . $e->getMessage();
        }
    }
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
        <div class="<?= $exito ? 'exito' : 'error' ?>"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>
    <!-- Formulario de registro -->
    <form method="post" autocomplete="off">
        <input type="text" name="usuario" placeholder="Usuario" required minlength="3" maxlength="50">
        <input type="text" name="nombre" placeholder="Nombre" required maxlength="50">
        <input type="text" name="apellido" placeholder="Apellido" required maxlength="50">
        <input type="number" name="edad" placeholder="Edad" required min="4" max="120">
        <input type="text" name="seccion" placeholder="Sección" required maxlength="10">
        <input type="password" name="clave" placeholder="Clave" required minlength="6">
        <input type="password" name="clave2" placeholder="Repetir clave" required minlength="6">
        <button type="submit">Registrar</button>
    </form>
    <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
</div>
</body>
</html>

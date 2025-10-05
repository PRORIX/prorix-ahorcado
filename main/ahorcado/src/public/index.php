<?php
require_once "classes/JuegoAhorcado.php";
require_once "classes/SesionAhorcado.php";
require_once "classes/DibujoAhorcado.php";

SesionAhorcado::iniciar();
$juego = SesionAhorcado::cargar();

if (isset($_POST['letra'])) {
    $letra = strtoupper($_POST['letra']);
    $juego->procesarLetra($letra);
    SesionAhorcado::guardar($juego);
}

$mostrar = $juego->mostrarProgreso();
$mensaje = $juego->comprobarMensaje($mostrar);
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <title>Ahorcado en PHP</title>
</head>
<body>
    <div class="contenedor">
        <h1>Juego del Ahorcado</h1>

        <?php echo DibujoAhorcado::mostrar($juego->getIntentos()); ?>

        <p>Palabra: <?php echo implode(" ", str_split($mostrar)); ?></p>
        <p>Intentos restantes: <?php echo $juego->getIntentos(); ?></p>
        <p>Letras usadas: <?php echo implode(", ", $juego->getLetrasUsadas()); ?></p>

        <?php if ($mensaje == ""): ?>
            <form method="post">
                <label>Introduce una letra:</label>
                <input type="text" name="letra" maxlength="1" required>
                <button type="submit">Adivinar</button>
            </form>
        <?php else: ?>
            <div class="overlay">
                <div class="mensaje-final <?php echo (strpos($mensaje, 'Ganaste') !== false) ? 'ganar' : 'perder'; ?>">
                    <p><?php echo $mensaje; ?></p>
                    <a href="reset.php">Jugar de nuevo</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>

<?php
class DibujoAhorcado {

    public static function mostrar($intentos) {
        $estado = 6 - $intentos;
        $rutaImagen = "images/imagenEstado" . $estado . ".png";
        return "<img src='$rutaImagen' alt='Estado del ahorcado' style='max-width:300px;'>";
    }
}
?>

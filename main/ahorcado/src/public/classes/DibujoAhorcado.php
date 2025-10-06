<?php

/**
 * Clase que dibuja la imagen del ahorcado
 * @author prorix
 * @version 1.0.3
 */
class DibujoAhorcado {

    /**
     * Metodo que genera la imagen en funcion de los intentos restantes
     * @param mixed $intentos intentos restantes
     * @return string imagen (en html) a mostrar
     */
    public static function mostrar($intentos) {
        $estado = 6 - $intentos;
        $rutaImagen = "images/imagenEstado" . $estado . ".png";
        return "<img src='$rutaImagen' alt='Estado del ahorcado' style='max-width:300px;'>";
    }
}
?>

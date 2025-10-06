<?php

/**
 * Clase que controla la sesion
 * @author prorix
 * @version 1.0.3
 */
class SesionAhorcado
{

    /**
     * Metodo start (inicia o reinicia el juego)
     * @return void juego iniciado
     */
    public static function iniciar()
    {
        session_start();

        if (!isset($_SESSION['palabra'])) {
            $juegoTemp = new JuegoAhorcado();
            $_SESSION['palabra'] = $juegoTemp->getPalabra();
            $_SESSION['intentos'] = 6;
            $_SESSION['letras_usadas'] = [];
        }

    }

    /**
     * Metodo que guarda cada movimiento del usuario
     * @param mixed $juego juego cargado
     * @return void resultado
     */
    public static function guardar($juego)
    {
        $_SESSION['palabra'] = $juego->getPalabra();
        $_SESSION['intentos'] = $juego->getIntentos();
        $_SESSION['letras_usadas'] = $juego->getLetrasUsadas();
    }

    /**
     * Metodo que carga lo guardado
     * @return JuegoAhorcado juego
     */
    public static function cargar()
    {
        return new JuegoAhorcado(
            $_SESSION['palabra'],
            $_SESSION['intentos'],
            $_SESSION['letras_usadas']
        );
    }
}
?>
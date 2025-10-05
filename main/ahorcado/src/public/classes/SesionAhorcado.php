<?php
class SesionAhorcado {

    public static function iniciar() {
        session_start();

if (!isset($_SESSION['palabra'])) {
    $juegoTemp = new JuegoAhorcado();
    $_SESSION['palabra'] = $juegoTemp->getPalabra();
    $_SESSION['intentos'] = 6;
    $_SESSION['letras_usadas'] = [];
}

    }

    public static function guardar($juego) {
        $_SESSION['palabra'] = $juego->getPalabra();
        $_SESSION['intentos'] = $juego->getIntentos();
        $_SESSION['letras_usadas'] = $juego->getLetrasUsadas();
    }

    public static function cargar() {
        return new JuegoAhorcado(
            $_SESSION['palabra'],
            $_SESSION['intentos'],
            $_SESSION['letras_usadas']
        );
    }
}
?>

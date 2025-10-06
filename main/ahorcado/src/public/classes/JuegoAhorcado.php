<?php

/**
 * Clase controladora (logica del juego)
 * @autor prorix
 * @version 1.0.3
 */
class JuegoAhorcado {

    private $palabras;
    private $palabra;
    private $intentos;
    private $letras_usadas;

    /**
     * Metodo contructor del juego inicial
     * @param mixed $palabra palabra elegida aleatoriamente
     * @param mixed $intentos intentos del jugador (reiniciados)
     * @param mixed $letras_usadas listado (vacio) donde se guardaran las letras usadas
     */
    public function __construct($palabra = null, $intentos = 6, $letras_usadas = []) {
        $this->palabras = file('words/palabras.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $this->palabra = $palabra ?? $this->palabraAleatoria();
        $this->intentos = $intentos;
        $this->letras_usadas = $letras_usadas;
    }

    /**
     * Metodo que elige una palabra aleatoria del archivo txt
     * @return mixed resultado
     */
    public function palabraAleatoria() {
        return $this->palabras[array_rand($this->palabras)];
    }

    /**
     * Metodo que comprueba cada letra que elige el jugador
     * @param mixed $letra letra elegida por el jugador
     * @return void resultado (positivo o negativo)
     */
    public function procesarLetra($letra) {
        if (!in_array($letra, $this->letras_usadas)) {
            $this->letras_usadas[] = $letra;
            if (strpos($this->palabra, $letra) === false) {
                $this->intentos--;
            }
        }
    }

    /**
     * Metodo que muestra la informacion del progreso de la partida
     * @return string progreso (avance del descubrimiento de la palabra y el listado de palabras usadas)
     */
    public function mostrarProgreso() {
        $mostrar = "";
        foreach (str_split($this->palabra) as $letra) {
            $mostrar .= in_array($letra, $this->letras_usadas) ? $letra : "_";
        }
        return $mostrar;
    }

    // Getters

    public function getIntentos() { 
        return $this->intentos; 
    }
    public function getLetrasUsadas() { 
        return $this->letras_usadas; 
    }
    public function getPalabra() { 
        return $this->palabra; 
    }

    /**
     * Metodo que comprueba si se ha conseguido (o perdido) la partida, y si es asi, muestra un mensaje u otro
     * @param mixed $mostrar informacion de la partida
     * @return string mensaje (victoria o derrota)
     */
    public function comprobarMensaje($mostrar) {
        if ($mostrar === $this->palabra) {
            return "Felicidades ¡Ganaste! La palabra era: " . $this->palabra;
        }
        if ($this->intentos <= 0) {
            return "Lo siento ¡Perdiste! La palabra era: " . $this->palabra;
        }
        return "";
    }
}
?>

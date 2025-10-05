<?php
class JuegoAhorcado {

    private $palabras;
    private $palabra;
    private $intentos;
    private $letras_usadas;

    public function __construct($palabra = null, $intentos = 6, $letras_usadas = []) {
        $this->palabras = file('words/palabras.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $this->palabra = $palabra ?? $this->palabraAleatoria();
        $this->intentos = $intentos;
        $this->letras_usadas = $letras_usadas;
    }

    public function palabraAleatoria() {
        return $this->palabras[array_rand($this->palabras)];
    }

    public function procesarLetra($letra) {
        if (!in_array($letra, $this->letras_usadas)) {
            $this->letras_usadas[] = $letra;
            if (strpos($this->palabra, $letra) === false) {
                $this->intentos--;
            }
        }
    }

    public function mostrarProgreso() {
        $mostrar = "";
        foreach (str_split($this->palabra) as $letra) {
            $mostrar .= in_array($letra, $this->letras_usadas) ? $letra : "_";
        }
        return $mostrar;
    }

    public function getIntentos() { 
        return $this->intentos; 
    }
    public function getLetrasUsadas() { 
        return $this->letras_usadas; 
    }
    public function getPalabra() { 
        return $this->palabra; 
    }

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

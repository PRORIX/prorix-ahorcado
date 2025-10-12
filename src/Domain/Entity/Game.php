<?php
declare(strict_types=1);

namespace App\Domain\Entity;

/**
 * Clase para representar una partida.
 * @author prorix
 * @version 4.2.3
 **/
final class Game
{
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_WON = 'won';
    public const STATUS_LOST = 'lost';

    /**
     * Metodo que construye una nueva partida con su configuracion.
     * @param id identificador unico de la partida.
     * @param word palabra oculta que debe adivinarse.
     * @param guesses letras ya intentadas por el jugador.
     * @param maxAttempts numero maximo de intentos fallidos.
     * @param status estado actual de la partida.
     * @return void
     **/
    public function __construct(
        private string $id,
        private string $word,
        private array $guesses = [],
        private int $maxAttempts = 6,
        private string $status = self::STATUS_IN_PROGRESS
    ) {}

    // Getters
    public function getId(): string { 
        return $this->id; 
    }

    public function getWord(): string { 
        return $this->word; 
    }

    public function getGuesses(): array { 
        return $this->guesses; 
    }

    public function getMaxAttempts(): int { 
        return $this->maxAttempts; 
    }

    public function getStatus(): string { 
        return $this->status; 
    }

    /**
     * Metodo que calcula los intentos restantes disponibles.
     * @return int
     **/
    public function remainingAttempts(): int
    {
        return max(0, $this->maxAttempts - $this->wrongGuessesCount());
    }

    /**
     * Metodo que construye la palabra oculta con guiones bajos.
     * @return string
     **/
    public function maskedWord(): string
    {
        $letters = mb_str_split($this->word);
        $mask = array_map(function ($ch) {
            $g = array_map('mb_strtolower', $this->guesses);
            return in_array(mb_strtolower($ch), $g, true) || !preg_match('/[a-zA-ZñÑáéíóúÁÉÍÓÚ]/u', $ch)
                ? $ch
                : '_';
        }, $letters);
        return implode('', $mask);
    }

    /**
     * Metodo que procesa un intento de letra del jugador.
     * @param letter letra propuesta para adivinar.
     * @return void correcto o incorrecto
     **/
    public function guess(string $letter): void
    {
        if ($this->status !== self::STATUS_IN_PROGRESS) return;

        $letter = mb_strtolower(trim($letter));
        if ($letter === '' || mb_strlen($letter) !== 1) return;

        if (!in_array($letter, $this->guesses, true)) {
            $this->guesses[] = $letter;
        }

        if (strpos($this->maskedWord(), '_') === false) {
            $this->status = self::STATUS_WON;
            return;
        }
        if ($this->remainingAttempts() <= 0) {
            $this->status = self::STATUS_LOST;
        }
    }

    /**
     * Metodo que obtiene las letras incorrectas probadas.
     * @return array array con las letras incorrectas ya usadas.
     **/
    public function wrongGuesses(): array
    {
        $w = mb_str_split(mb_strtolower($this->word));
        return array_values(array_filter($this->guesses, fn($g) => !in_array($g, $w, true)));
    }

    /**
     * Metodo que cuenta las letras fallidas realizadas.
     * @return int letras fallidas
     **/
    public function wrongGuessesCount(): int
    {
        return count($this->wrongGuesses());
    }

    /**
     * Metodo que serializa la partida en un arreglo.
     * @return array partida convertida a un array
     **/
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'word' => $this->word,
            'guesses' => $this->guesses,
            'maxAttempts' => $this->maxAttempts,
            'status' => $this->status,
        ];
    }

    /**
     * Metodo que hidrata una partida desde un arreglo de datos.
     * @param data datos de la partida almacenada.
     * @return self partida
     **/
    public static function fromArray(array $data): self
    {
        return new self(
            (string)($data['id'] ?? ''),
            (string)($data['word'] ?? ''),
            array_values((array)($data['guesses'] ?? [])),
            (int)($data['maxAttempts'] ?? 6),
            (string)($data['status'] ?? self::STATUS_IN_PROGRESS),
        );
    }
}

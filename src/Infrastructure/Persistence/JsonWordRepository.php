<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Repository\WordRepositoryInterface;

/**
 * Clase para obtener palabras desde un archivo JSON.
 * @author prorix
 * @version 4.2.3
 **/
final class JsonWordRepository implements WordRepositoryInterface
{
    /**
     * Metodo que prepara el repositorio con el archivo de palabras.
     * @param file ruta hacia el fichero JSON de palabras.
     * @return void
     **/
    public function __construct(private string $file) {}

    /**
     * Metodo que devuelve una palabra aleatoria.
     * @return string
     **/
    public function randomWord(): string
    {
        $content = file_get_contents($this->file);
        $data = $content ? json_decode($content, true) : ['words' => []];
        $words = $data['words'] ?? [];
        if (!$words) throw new \RuntimeException('No hay palabras en words.json');
        return (string)$words[array_rand($words)];
    }
}

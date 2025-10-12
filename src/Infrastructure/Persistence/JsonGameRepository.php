<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Entity\Game;
use App\Domain\Repository\GameRepositoryInterface;

/**
 * Clase para gestionar las partidas usando almacenamiento JSON.
 * @author prorix
 * @version 4.2.3
 **/
final class JsonGameRepository implements GameRepositoryInterface
{
    /**
     * Metodo que construye el repositorio apuntando a un fichero.
     * @param file ruta del archivo JSON a manipular.
     * @return void
     **/
    public function __construct(private string $file)
    {
        if (!is_file($this->file)) {
            file_put_contents($this->file, json_encode(['games' => []], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * Metodo que guarda o actualiza una partida.
     * @param game partida que se quiere persistir.
     * @return void
     **/
    public function save(Game $game): void
    {
        $data = $this->readAll();
        $data['games'][$game->getId()] = $game->toArray();
        $this->writeAll($data);
    }

    /**
     * Metodo que busca una partida por identificador.
     * @param id identificador objetivo.
     * @return ?Game
     **/
    public function find(string $id): ?Game
    {
        $data = $this->readAll();
        return isset($data['games'][$id]) ? Game::fromArray($data['games'][$id]) : null;
    }

    /**
     * Metodo que elimina una partida almacenada.
     * @param id identificador a quitar del archivo.
     * @return void
     **/
    public function delete(string $id): void
    {
        $data = $this->readAll();
        if (!isset($data['games'][$id])) {
            return;
        }
        unset($data['games'][$id]);
        $this->writeAll($data);
    }

    /**
     * Metodo que lee todo el contenido del archivo JSON.
     * @return array
     **/
    private function readAll(): array
    {
        $fh = fopen($this->file, 'c+');
        if ($fh === false) throw new \RuntimeException('No se pudo abrir el fichero de juegos');
        try {
            flock($fh, LOCK_SH);
            $content = stream_get_contents($fh);
            $json = $content ? json_decode($content, true) : ['games' => []];
            return is_array($json) ? $json : ['games' => []];
        } finally {
            flock($fh, LOCK_UN);
            fclose($fh);
        }
    }

    /**
     * Metodo que escribe el contenido completo al archivo JSON.
     * @param data datos estructurados a persistir.
     * @return void
     **/
    private function writeAll(array $data): void
    {
        $tmp = $this->file . '.tmp';
        $fh = fopen($tmp, 'w');
        if ($fh === false) throw new \RuntimeException('No se pudo escribir el fichero de juegos');
        try {
            flock($fh, LOCK_EX);
            fwrite($fh, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
            fflush($fh);
            flock($fh, LOCK_UN);
        } finally {
            fclose($fh);
        }
        rename($tmp, $this->file);
    }
}

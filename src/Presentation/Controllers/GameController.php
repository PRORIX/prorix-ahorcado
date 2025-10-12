<?php
declare(strict_types=1);

namespace App\Presentation\Controllers;

use App\Application\Services\GameService;
use App\Domain\Entity\Game;
use App\Infrastructure\Persistence\JsonGameRepository;
use App\Infrastructure\Persistence\JsonWordRepository;

/**
 * Clase para gestionar las peticiones del juego del ahorcado.
 * @author prorix
 * @version 4.2.3
 **/
final class GameController
{
    /**
     * Metodo que inicializa el controlador con la configuracion.
     * @param config arreglo de configuracion global.
     * @return void
     **/
    public function __construct(private array $config) {}

    /**
     * Metodo que crea el servicio de partidas con sus repositorios.
     * @return GameService
     **/
    private function service(): GameService
    {
        $games = new JsonGameRepository($this->config['paths']['games']);
        $words = new JsonWordRepository($this->config['paths']['words']);
        return new GameService($games, $words, (int)$this->config['max_attempts']);
    }

    /**
     * Metodo que determina la accion solicitada por el usuario.
     * @return void
     **/
    public function handle(): void
    {
        $action = $_GET['action'] ?? 'home';
        switch ($action) {
            case 'new':   $this->newGame(); break;
            case 'play':  $this->play(); break;
            case 'guess': $this->postGuess(); break;
            case 'resume':$this->resume(); break;
            default:      $this->home();
        }
    }

    /**
     * Metodo que muestra la pagina principal del juego.
     * @return void
     **/
    private function home(): void
    {
        $error = $_SESSION['resume_error'] ?? null;
        unset($_SESSION['resume_error']);
        $this->render('home.php', [
            'currentId' => $_SESSION['game_id'] ?? null,
            'resumeError' => $error,
        ]);
    }

    /**
     * Metodo que inicia una nueva partida y redirige a jugar.
     * @return void
     **/
    private function newGame(): void
    {
        $service = $this->service();
        $game = $service->startGame();
        unset($_SESSION['finished_game']);
        $_SESSION['game_id'] = $game->getId();
        header('Location: ?action=play&id=' . urlencode($game->getId()));
        exit;
    }

    /**
     * Metodo que reanuda la ultima partida o una indicada por ID.
     * @return void
     **/
    private function resume(): void
    {
        $service = $this->service();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestedId = trim((string)($_POST['game_id'] ?? ''));
            if ($requestedId === '') {
                $this->redirectHomeWithError('Debes introducir un ID de partida.');
            }
            $game = $service->loadGame($requestedId);
            if (!$game) {
                $this->redirectHomeWithError('No existe una partida con ese ID.');
            }
            $_SESSION['game_id'] = $game->getId();
            unset($_SESSION['finished_game']);
            header('Location: ?action=play&id=' . urlencode($game->getId()));
            exit;
        }

        $id = $_SESSION['game_id'] ?? null;
        if ($id) {
            header('Location: ?action=play&id=' . urlencode($id));
            exit;
        }

        $this->redirectHomeWithError('No hay partida activa para continuar.');
    }

    /**
     * Metodo que muestra la vista principal de juego.
     * @return void
     **/
    private function play(): void
    {
        $id = (string)($_GET['id'] ?? ($_SESSION['game_id'] ?? ''));
        if ($id === '') { $this->render('error.php', ['message' => 'ID de partida no proporcionado.']); return; }
        $service = $this->service();
        $game = $service->loadGame($id);
        if (!$game) {
            $finished = $_SESSION['finished_game'] ?? null;
            if (is_array($finished) && ($finished['id'] ?? null) === $id) {
                $game = Game::fromArray($finished);
            } else {
                $this->render('error.php', ['message' => 'Partida no encontrada.']);
                return;
            }
        }
        if ($game->getStatus() === Game::STATUS_IN_PROGRESS) {
            $_SESSION['game_id'] = $id;
        } else {
            unset($_SESSION['game_id']);
        }
        $this->render('play.php', ['game' => $game]);
    }

    /**
     * Metodo que procesa el envio del formulario de intento.
     * @return void
     **/
    private function postGuess(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ?action=home'); exit; }
        $id = (string)($_POST['id'] ?? '');
        $letter = (string)($_POST['letter'] ?? '');
        if ($id === '' || $letter === '') { header('Location: ?action=play&id=' . urlencode($id)); exit; }
        $service = $this->service();
        $game = $service->guess($id, $letter);
        if ($game && $game->getStatus() !== Game::STATUS_IN_PROGRESS) {
            $_SESSION['finished_game'] = $game->toArray();
            unset($_SESSION['game_id']);
        } else {
            unset($_SESSION['finished_game']);
        }
        header('Location: ?action=play&id=' . urlencode($id));
        exit;
    }

    /**
     * Metodo que renderiza una vista con el layout comun.
     * @param view nombre del archivo de vista.
     * @param params datos a extraer en la vista.
     * @return void
     **/
    private function render(string $view, array $params = []): void
    {
        extract($params);
        $viewsDir = $this->config['paths']['views'];
        require $viewsDir . '/_layout_top.php';
        require $viewsDir . '/' . $view;
        require $viewsDir . '/_layout_bottom.php';
    }

    /**
     * Metodo que redirige al inicio con un mensaje de error.
     * @param message texto descriptivo para el usuario.
     * @return void
     **/
    private function redirectHomeWithError(string $message): void
    {
        $_SESSION['resume_error'] = $message;
        header('Location: ?action=home');
        exit;
    }
}

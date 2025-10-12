<?php
declare(strict_types=1);

namespace App\Infrastructure\Autoload;

/**
 * Clase para gestionar el cargador automatico de clases del proyecto.
 * @author prorix
 * @version 4.2.3
 **/
final class Autoloader
{
    private array $prefixes = [];

    /**
     * Metodo que registra el autoloader para un prefijo y su base.
     * @param prefix texto del prefijo de namespace.
     * @param baseDir directorio base donde buscar las clases.
     * @return void
     **/
    public static function register(string $prefix = 'App\\', string $baseDir = __DIR__ . '/../../'): void
    {
        $loader = new self();
        $loader->addNamespace($prefix, $baseDir);
        spl_autoload_register([$loader, 'loadClass']);
    }

    /**
     * Metodo que agrega un namespace gestionado por el autoloader.
     * @param prefix texto del prefijo a registrar.
     * @param baseDir ruta base asociada al prefijo.
     * @return void
     **/
    public function addNamespace(string $prefix, string $baseDir): void
    {
        $prefix = trim($prefix, '\\') . '\\';
        $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->prefixes[$prefix] = $baseDir;
    }

    /**
     * Metodo que carga dinamicamente una clase del proyecto.
     * @param class nombre completo de la clase solicitada.
     * @return void
     **/
    public function loadClass(string $class): void
    {
        foreach ($this->prefixes as $prefix => $baseDir) {
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) continue;
            $relativeClass = substr($class, $len);
            $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';
            if (is_file($file)) { require $file; return; }
        }
    }
}

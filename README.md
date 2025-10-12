# Prorix Ahorcado

Aplicación web del clásico juego del ahorcado, enfocada en un código limpio y una arquitectura modular que facilita el mantenimiento y la extensión del proyecto.

## Notas del parche v4.0.0

### Refactor de arquitectura

- Se reorganizó todo el código en las capas `Application`, `Domain`, `Infrastructure` y `Presentation`, alineando el proyecto con principios de diseño limpio.
- Se introdujo un autoloader propio en `src/Infrastructure/Autoload/Autoloader.php`, eliminando `require` dispersos y manteniendo los namespaces bajo control.
- Controladores y vistas fueron movidos a `src/Presentation`, con layouts reutilizables y recursos estáticos bajo `public/` para servirlos desde el servidor web con mayor claridad.
- La configuración sensible quedó centralizada en `config/config.php`, facilitando ajustes de rutas y parámetros sin tocar la lógica del juego.
- Los archivos JSON (`storage/games.json` y `storage/words.json`) se aíslan de la lógica de dominio, permitiendo reemplazar la capa de persistencia sin afectar el resto del sistema.

## Nuevo esquema de directorios

```text
prorix-ahorcado/
|-- config/
|   `-- config.php
|-- images/
|-- public/
|   |-- index.php
|   |-- assets/
|   |   `-- css/
|   |       `-- ahorcado.css
|   `-- images/
|       `-- ahorcado/
|-- src/
|   |-- Application/
|   |   `-- Services/
|   |       `-- GameService.php
|   |-- Domain/
|   |   |-- Entity/
|   |   |   `-- Game.php
|   |   `-- Repository/
|   |       |-- GameRepositoryInterface.php
|   |       `-- WordRepositoryInterface.php
|   |-- Infrastructure/
|   |   |-- Autoload/
|   |   |   `-- Autoloader.php
|   |   `-- Persistence/
|   |       |-- JsonGameRepository.php
|   |       `-- JsonWordRepository.php
|   `-- Presentation/
|       |-- Controllers/
|       |   `-- GameController.php
|       `-- Views/
|           |-- _layout_top.php
|           |-- _layout_bottom.php
|           |-- error.php
|           |-- home.php
|           `-- play.php
|-- storage/
|   |-- games.json
|   `-- words.json
|-- Dockerfile
|-- docker-compose.yml
`-- README.md
```

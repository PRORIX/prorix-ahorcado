<?php
declare(strict_types=1);

return [
    'max_attempts' => 6,
    'paths' => [
        'words' => __DIR__ . '/../storage/words.json',
        'games' => __DIR__ . '/../storage/games.json',
        'views' => __DIR__ . '/../src/Presentation/Views',
    ],
];

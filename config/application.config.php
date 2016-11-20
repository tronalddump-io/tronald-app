<?php
return [
    'modules' => [
        'api' => 'module/api/src/App.php',
        'cli' => 'module/cli/src/App.php',
        'web' => 'module/web/src/App.php'
    ],
    'server_names' => [
        'tronalddump.io'            => 'web',
        'api.tronalddump.local'     => 'api',
        'api.tronalddump.io'        => 'api',
        'www.tronalddump.local'     => 'web',
        'www.tronalddump.io'        => 'web',
        'tronalddump.herokuapp.com' => 'web'
    ]
];
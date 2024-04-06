<?php

return [
  'default' => env('DB_CONNECTION', 'sqlite'),

  'connections' => [
    'sqlite' => [
      'driver' => 'sqlite',
      'database' => env('DB_DATABASE', database_path('database.sqlite')),
      'prefix' => '',
      'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
    ],

    'pgsql' => [
      'driver' => 'pgsql',
      'url' => env('DB_URL', env('DATABASE_URL')),
      'charset' => 'utf8',
      'prefix' => '',
      'search_path' => 'public',
    ],
  ],
  'migrations' => 'migrations',
];

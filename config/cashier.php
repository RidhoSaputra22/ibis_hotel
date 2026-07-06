<?php

return [
    'loader_ttl_minutes' => (int) env('IBIS_LOADER_TTL_MINUTES', 120),
    'loading_delay_ms' => (int) env('IBIS_LOADING_DELAY_MS', 2800),
    'outlets' => [
        ['code' => '10', 'name' => 'Ibis Kitchen'],
        ['code' => '20', 'name' => 'Restaurant Terrace'],
        ['code' => '30', 'name' => 'Room Service'],
        ['code' => '40', 'name' => 'Banquet'],
        ['code' => '50', 'name' => 'Pool Bar'],
    ],
    'cashiers' => [
        ['code' => 'ADHA', 'name' => 'Adha'],
        ['code' => 'FARHAN', 'name' => 'Farhan'],
        ['code' => 'NURUL', 'name' => 'Nurul'],
        ['code' => 'RIZKY', 'name' => 'Rizky'],
    ],
    'shifts' => ['1', '2', '3'],
];

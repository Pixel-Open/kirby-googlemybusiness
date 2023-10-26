<?php

return [
    'description' => 'Initialize the structure for the opening_hours',
    'args' => [],
    'command' => function ($cli) {
        $kirby = $cli->kirby();

        $opening_hours = [
            [
                'weekday' => 'monday',
            ],
            [
                'weekday' => 'tuesday',
            ],
            [
                'weekday' => 'wednesday',
            ],
            [
                'weekday' => 'thursday',
            ],
            [
                'weekday' => 'friday',
            ],
            [
                'weekday' => 'saturday',
            ],
            [
                'weekday' => 'sunday',
            ],
        ];

        $kirby->impersonate('kirby');
        $kirby->site()->update([
            'opening_hours' => $opening_hours,
        ]);

        $cli->success('Structure were correctly initialize');
        return 0;
    },
];

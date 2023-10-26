<?php

return [
    'description' => 'Sort both structure in the panel tab',
    'args' => [],
    'command' => function ($cli) {
        $kirby = $cli->kirby();
        $site_content = $kirby->site()->content();

        $opening_hours = $site_content->get('opening_hours')->toObject()->toArray();
        function sortByDays($a, $b)
        {
            $weekday = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            $dayComp = array_search($a['weekday'], $weekday) - array_search($b['weekday'], $weekday);
            if ($dayComp == 0) {
                return intval($a['open']) - intval($b['open']);
            }
            return $dayComp;
        }
        usort($opening_hours, 'sortByDays');

        $reviews = $site_content->get('reviews_list')->toObject()->toArray();
        function sortByTime($a, $b)
        {
            return $b['time'] - $a['time'];
        }
        usort($reviews, 'sortByTime');

        $kirby->impersonate('kirby');
        $kirby->site()->update([
            'opening_hours' => $opening_hours,
            'reviews_list' => $reviews,
        ]);

        $cli->success('Structure were correctly sort');
        return 0;
    },
];

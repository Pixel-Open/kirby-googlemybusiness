<?php

return [
    'description' => 'Get the most recents reviews from Google My Business',
    'args' => [
        'count' => [
            'description' => 'Set the max number of reviews to keep',
            'required' => false,
            'defaultValue' => 20,
        ],
    ],
    'command' => function ($cli) {
        $kirby = $cli->kirby();
        $site_content = $kirby->site()->content();
        $max_review = $cli->arg('count');

        $response = Remote::get('https://maps.googleapis.com/maps/api/place/details/json?fields=user_ratings_total,rating,reviews&reviews_no_translations=true&reviews_sort=' . $site_content->get('reviews_type') . '&place_id=' . $kirby->option('pixelopen.googlemybusiness.placeId') . '&key=' . $kirby->option('pixelopen.googlemybusiness.apiKey'));
        $content = $response->json();
        if ($content['status'] != 'OK') {
            $cli->error('Can\'t get Google My Business data, please check the place ID and API key');
            return 1;
        }

        $reviews_list = $site_content->get('reviews_list')->toObject()->toArray();
        $time = [];
        foreach ($reviews_list as $review) {
            $time[] = $review['time'];
        }

        $result = $content['result'];
        $new_reviews = [];
        foreach ($result['reviews'] as $review) {
            if (! in_array($review['time'], $time) && ($site_content->get('get_uncomment')->toBool() || $review['text'] != '') && $review['rating'] >= $site_content->get('minimum_rating')->value()) {
                array_push($new_reviews, [
                    'show' => 'true',
                    'rating' => $review['rating'],
                    'author' => $review['author_name'],
                    'comment' => $review['text'],
                    'time' => $review['time'],
                    'date' => date('Y-m-d H:i:s', $review['time']),
                ]);
            }
        }
        $reviews_list = array_merge($reviews_list, $new_reviews);
        function sortByTimeDesc($a, $b)
        {
            return $b['time'] - $a['time'];
        }
        usort($reviews_list, 'sortByTimeDesc');

        $kirby->impersonate('kirby');

        $kirby->site()->update([
            'rating' => $result['rating'],
            'reviews_count' => $result['user_ratings_total'],
            'reviews_list' => array_slice($reviews_list, 0, $max_review),
        ]);

        $cli->success('Reviews were successfully updated');
        if (count($new_reviews) == 0) {
            $cli->out('No reviews were added');
        } else {
            $cli->out(count($new_reviews) . ' reviews were added');
        }
        return 0;
    },
];

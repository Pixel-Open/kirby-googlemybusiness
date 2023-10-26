<?php

return [
    'description' => 'Synchronize the data in the tab settings with the data from Google My Business',
    'args' => [],
    'command' => function ($cli) {
        $kirby = $cli->kirby();
        $update = [];

        $response = Remote::get('https://maps.googleapis.com/maps/api/place/details/json?fields=address_components,geometry/location,formatted_phone_number,opening_hours/periods&place_id=' . $kirby->option('pixelopen.googlemybusiness.placeId') . '&key=' . $kirby->option('pixelopen.googlemybusiness.apiKey'));
        $content = $response->json();
        if ($content['status'] != 'OK') {
            $cli->error('Can\'t get Google My Business data, please check the place ID and API key');
            return 1;
        }

        $result = $content['result'];

        $content = [];
        if (! $kirby->site()->content()->get('overwrite_data')->toBool()) {
            foreach ($kirby->site()->content()->fields() as $key => $field) {
                if ($field->isNotEmpty()) {
                    $content[$key] = $field;
                }
            }
        }

        foreach ($result['address_components'] as $addrComp) {
            foreach ($addrComp['types'] as $type) {
                if (! array_key_exists('address', $content) && $type == 'route' && $addrComp['long_name'] != $kirby->site()->content()->address()) {
                    $update['address'] = $addrComp['long_name'];
                } elseif (! array_key_exists('street_number', $content) && $type == 'street_number' && $addrComp['long_name'] != $kirby->site()->content()->street_number()) {
                    $update['street_number'] = $addrComp['long_name'];
                } elseif (! array_key_exists('country', $content) && $type == 'country' && $addrComp['long_name'] != $kirby->site()->content()->country()) {
                    $update['country'] = $addrComp['long_name'];
                } elseif (! array_key_exists('city', $content) && $type == 'locality' && $addrComp['long_name'] != $kirby->site()->content()->city()) {
                    $update['city'] = $addrComp['long_name'];
                } elseif (! array_key_exists('postal_code', $content) && $type == 'postal_code' && $addrComp['long_name'] != $kirby->site()->content()->postal_code()) {
                    $update['postal_code'] = $addrComp['long_name'];
                }
            }
        }

        if (array_key_exists('geometry', $result)) {
            if (! array_key_exists('latitude', $content) && strval($result['geometry']['location']['lat']) != $kirby->site()->content()->latitude()) {
                $update['latitude'] = $result['geometry']['location']['lat'];
            }
            if (! array_key_exists('longitude', $content) && strval($result['geometry']['location']['lng']) != $kirby->site()->content()->longitude()) {
                $update['longitude'] = $result['geometry']['location']['lng'];
            }
        }

        if (! array_key_exists('phone', $content) && array_key_exists('formatted_phone_number', $result) && $result['formatted_phone_number'] != $kirby->site()->content()->phone()) {
            $update['phone'] = $result['formatted_phone_number'];
        }

        if ($kirby->site()->content()->get('overwrite_hours')->toBool() || $kirby->site()->content()->get('opening_hours')->isEmpty()) {
            $weekday = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            $opening_hours = [];
            foreach ($result['opening_hours']['periods'] as $period) {
                $day = $weekday[$period['open']['day'] - 1];
                $open = $period['open']['time'];
                $open = substr($open, 0, 2) . ":" . substr($open, 2) . ":00";
                $close = $period['close']['time'];
                $close = substr($close, 0, 2) . ":" . substr($close, 2) . ":00";
                array_push($opening_hours, [
                    'weekday' => $day,
                    'open' => $open,
                    'close' => $close,
                ]);
            }
            if ($kirby->site()->content()->opening_hours()->toObject()->toArray() != $opening_hours) {
                $update['opening_hours'] = $opening_hours;
            }
        } else {
            $cli->out('Opening hours were not update because it is disabled in the panel tab');
        }

        $kirby->impersonate('kirby');

        $kirby->site()->update($update);

        if (count($update) == 0) {
            $cli->success('All data is up to date');
        } else {
            $cli->success('Data were successfully updated');
            $cli->out('Data updated:');
            foreach ($update as $key => $value) {
                $cli->out('    ' . $key);
            }
        }
        return 0;
    },
];

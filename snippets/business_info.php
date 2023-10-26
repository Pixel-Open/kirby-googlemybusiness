<?= css('media/plugins/pixelopen/kirby-googlemybusiness/business_info.css') ?>

<?php $content = site()->content();
$full_address = [];
if (($address = $content->address()) != '') {
    if (($street_number = $content->street_number()) != '') {
        $full_address[] = $street_number . ",";
    }
    $full_address[] = $address;
}
if (($postal_code = $content->postal_code()) != '') {
    $full_address[] = $postal_code;
}
if (($city = $content->city()) != '') {
    $full_address[] = $content->country() != '' ? $city . "," : $city;
}
if (($country = $content->country()) != '') {
    $full_address[] = $country;
}
$phone = $content->phone();
$opening_hours = $content->opening_hours()->toObject()->toArray();
$schedule = [];
foreach ($opening_hours as $day_hours) {
    if ($day_hours['open'] && $day_hours['close']) {
        $schedule[strtolower($day_hours['weekday'])][] = [
            'open' => $day_hours['open'],
            'close' => $day_hours['close'],
        ];
    }
} ?>

<a id="openModal"><?= t('pixelopen.googlemybusiness.business_info.show_info') ?></a>
<div id="modal" class="modal">
    <div class="modal-content">
        <?php if (count($full_address)): ?>
            <span style="font-weight: bold">&#x1F4CD;</span>
            <?php if (option('pixelopen.googlemybusiness.placeId') != ''): ?>
            <a class="location" target="_blank" href="https://www.google.com/maps/place/?q=place_id:<?= option('pixelopen.googlemybusiness.placeId') ?>">
                <?= implode(' ', $full_address) ?>
            </a>
            <?php else: ?>
            <span>
                <?= implode(' ', $full_address) ?>
            </span>
            <?php endif ?>
            <br>
        <?php endif ?>
        <?php if ($phone != ''): ?>
            <span>&#x1F4DE;</span> <a class="phone" href="tel:<?= str_replace(' ', '', $phone) ?>"><?= $phone ?></a>
            <br>
        <?php endif ?>

        <br>
        
        <?php if (count($opening_hours) > 0): ?>
            <div class="opening_hours">
                <div class="weekday">
                    <?php foreach ($schedule as $day => $periods): ?>
                        <?= t('pixelopen.googlemybusiness.' . $day) ?>:
                        <br>
                    <?php endforeach ?>
                </div>
                <div class="hours">
                    <?php foreach ($schedule as $day => $periods): ?>
                        <?php foreach ($periods as $period): ?>
                            <?= $period != reset($periods) ? ", " : "" ?><?= substr($period['open'], 0, 5) ?> - <?= substr($period['close'], 0, 5) ?>
                        <?php endforeach ?>
                        <br>
                    <?php endforeach ?>
                </div>
            </div>
        <?php endif ?>
    </div>
</div>

<script>
    const openModalButton = document.getElementById('openModal');
    const closeModalButton = document.getElementById('closeModal');
    const modal = document.getElementById('modal');

    openModalButton.addEventListener('click', () => {
        modal.style.display = 'block';
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            modal.style.display = 'none';
        }
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
</script>
<div class="business_info">
    <?php
        $content = site()->content();
    $address = $content->address();
    $street_number = $content->street_number();
    $postal_code = $content->postal_code();
    $city = $content->city();
    $country = $content->country();
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
    }
    ?>

    <a id="openModal"><?= t('pixelopen.googlemybusiness.business_info.show_info') ?></a>
    <div id="modal" class="modal">
        <div class="modal-content">
            <p><?= $street_number?>, <?= $address ?> <?= $postal_code ?> <?= $city ?>, <?= $country ?></p>
            <p><?= $phone ?></p>
            <br>
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
        </div>
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

<style>
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 100;
    }

    .modal-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        padding: 50px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .opening_hours{
        display: grid;
        grid-template-columns: 1fr 2fr;
    }

    .weekday{
        font-weight: bold;
    }

    .hours{
        text-align: right;
    }

    #openModal{
        border-radius: 0.375rem;
        background-color: rgb(79 70 229);
        padding-left: 0.75rem;
        padding-right: 0.75rem;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        font-size: 0.875rem;
        line-height: 1.25rem;
        font-weight: 600;
        color: white;
    }

    #openModal:hover{
        background-color: rgb(99 102 241);
    }

    #openModal:focus{
        outline-style: solid;
        outline-width: 2px;
        outline-offset: 2px;
        outline-color: #4f46e5;
    }
</style>
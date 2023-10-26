<?= css('media/plugins/pixelopen/kirby-googlemybusiness/reviews.css') ?>

<div class="reviews">
    <?php $reviews = collection('reviews') ?>

    <?php foreach ($reviews as $review): ?>
        <?php if ($review['show'] == 'true' && $review['comment'] != ""): ?>
            <div class="review">
                <div class="rate">
                    <?php for ($i = 0; $i < 5; $i++):
                        if ($i < $review['rating']): ?>
                            <span class="orange">&#x2605;</span>
                        <?php else: ?>
                            <span class="">&#x2605;</span>
                        <?php endif;
                    endfor ?>
                </div>
                <p class="comment"><?= esc($review['comment'], 'html') ?></p>
                <p class="author"><?= strtok(esc($review['author'], 'html'), ' ') ?></p>
                <p class="date"><?= ($date = date_create($review['date'])) ? date_format($date, 'd/m/Y') : '' ?></p>
                <br>
            </div>
        <?php endif ?>
    <?php endforeach ?>
</div>
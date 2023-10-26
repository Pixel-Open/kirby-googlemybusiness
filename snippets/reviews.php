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
                <p class=author><?= strtok(esc($review['author'], 'html'), ' ') ?></p>
                <br>
            </div>
        <?php endif ?>
    <?php endforeach ?>
</div>

<style>
    .reviews{
        display: flex;
        gap: 2rem;
    }

    .review{
        width: 100%;
        padding: 2rem;
        padding-top: 1rem;
        border-radius: 2rem;
        background-color: lightgray;
        position: relative;
    }

    .rate{
        font-size: 20px;
        text-align: center;
        padding-bottom: 0.7rem;
    }

    .orange{
        color: orange;
    }

    .comment{
        text-align: justify;
    }

    .author{
        font-weight: bold;
        position: absolute;
        font-size: 20px;
        bottom: 1rem;
        right: 2rem;
    }
</style>
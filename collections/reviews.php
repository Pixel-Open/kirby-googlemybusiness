<?php

return function () {
    return site()->content()->get('reviews_list')->toObject()->toArray();
};

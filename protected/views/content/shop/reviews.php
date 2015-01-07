<?php $this->widget('application.widgets.ReviewWidget', array(
    'relation' => 'reviewsRel',
    'model' => 'Shop',
    'reviewModel' => 'ShopReview',
    'itemId' => $this->shop->id
));
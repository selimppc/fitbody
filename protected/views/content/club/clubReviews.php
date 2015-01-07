<?php $this->widget('application.widgets.ReviewWidget', array(
    'relation' => 'reviewsRel',
    'model' => 'Club',
    'reviewModel' => 'ClubReview',
    'itemId' => $this->club->id
));
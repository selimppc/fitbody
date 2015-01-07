<?php $this->widget('application.widgets.ReviewWidget', array(
    'relation' => 'reviewsRel',
    'model' => 'Coach',
    'reviewModel' => 'CoachReview',
    'itemId' => $coach->id
));
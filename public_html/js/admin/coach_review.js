$(function() {
    $(".jRatingExercise").jRating({
        length : 5,
        decimalLength: 1,
        bigStarsPath: '/images/jRating/stars.png',
        rateMax: 5,
        sendRequest: false,
        canRateAgain: true,
        nbRates: 100,
        onClick: function(element,rate) {
            $('.jRatingExerciseHidden').attr('value', rate)
        }
    });
});
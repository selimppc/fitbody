var applyCss = function(){
    $(".jRatingExercise").jRating({
        length : 10,
        decimalLength: 1,
        bigStarsPath: '/images/jRating/stars.png',
        rateMax: 10,
        sendRequest: false,
        canRateAgain: true,
        nbRates: 100,
        onClick: function(element,rate) {
            $('.jRatingExerciseHidden').attr('value', rate)
        }
    });
};

var addInstruction = function(){
    var divs = $("#instructionField>div.controls.row");
    var count = divs.length;
    var addButt = $("#add_instruction");

    var createRow = function(count){
        var inner_link = document.createElement('a');
        inner_link.className = 'btn delete_instruction';
        inner_link.style = "margin-left: 3px;";
        inner_link.appendChild(document.createTextNode("Удалить"));

        var template = document.createElement('div');
        template.className = 'controls row';
        template.style = "margin-top:4px;margin-bottom:4px;";

        var input = document.createElement('textarea');
        input.type = "text";
        input.id = "Instruction_"+ count +"_title";
        input.name = "Instruction["+ count +"][title]";
        input.style = "width: 300px";
        template.appendChild(input);
        template.appendChild(inner_link);

        return template;
    };

    var deleteButt = function(){
        $("#instructionField>div.controls.row").each(function(){
            var _this = $(this);

            _this.find('a.delete_instruction').off().click(function(){
                _this.remove();
            });
        });
    };

    deleteButt();

    addButt.click(function(){
        var elem = $("#instructionField>div.controls.row").last();

        if(elem.length){
            elem.after(createRow(count));
        } else {
            $("#instructionField").prepend(createRow(count));
        }
        deleteButt();
        count++;
    });

};

$(document).ready(function(){
    applyCss();
    addInstruction();

    var uploaderMaterialImages = new Uploader({
        formId: '#exerciseImageForm',
        uploadedUrl: '/admin/exercise/exercise/UploadedMultipleImages/'
    });
    var uploaderMaterialVideos = new Uploader({
        formId: '#exerciseVideoForm',
        uploadedUrl: '/admin/exercise/exercise/UploadedMultipleVideos/'
    });
	var uploader = new Uploader({
		formId: '#exerciseForm',
		uploadedUrl: '/admin/exercise/exercise/UploadedMuscleImages/'
	});

	uploader.UploadedSingleImages();

    var exerciseId = $('#exerciseForm').attr('data-id');

    if (exerciseId) {
        uploaderMaterialImages.UploadedMultipleImages(exerciseId);
        uploaderMaterialVideos.UploadedMultipleImages(exerciseId);
    }
});

$('#exerciseTabs a:first').tab('show');
$('a[data-toggle="tab"]').on('shown', function (e) {
    localStorage.setItem('lastTab', $(e.target).attr('href'));
}).on('click',function(){
    $('#Exercise_muscles_chosen').css('width', '220px');

});
var lastTab = localStorage.getItem('lastTab');
if (lastTab) {
    $('#exerciseTabs a[href="' + lastTab + '"]' ).tab('show');
}
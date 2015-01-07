/**
 * Created by shumer on 7/11/14.
 */
$(function(){
    if($("#GoalSize_body_part_id").hasClass('error')){
        $(".jq-selectbox__select", "#goalSizeForm").addClass('error');
    }

    document.getElementById("goalFatForm_save").addEventListener("click", function () {
        document.getElementById("goalFatForm").submit();
        return false;
    });

    document.getElementById("goalSizeForm_save").addEventListener("click", function () {
        document.getElementById("goalSizeForm").submit();
        return false;
    });

    document.getElementById("goalWeightForm_save").addEventListener("click", function () {
        document.getElementById("goalWeightForm").submit();
        return false;
    });
});
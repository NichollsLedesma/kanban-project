$( document ).ready(function() {

    $(document).on("click", ".add-checklist-btn", function(event){
        let newChecklistUrl = $(this).attr('data-checklist-create-url');
        let data = $( this ).parent().serialize();
        $.ajax({
            url: newChecklistUrl,
            data: data,
            cache: false,
            type: 'POST',
            success: function (data) {
                if (data == true) {
                    $.pjax.reload({container: '#board-update-container'});
                }
            }
        });
    });

    $(document).on("click", "input[type=checkbox]", function(event){
        let updateOptionStatusUrl = $(this).attr('data-update-option-status-url');
        $.ajax({
            url: updateOptionStatusUrl,
            cache: false,
            type: 'PUT'
        });

        if ($(this).parent().hasClass('checked')) {
            $(this).parent().removeClass('checked');
        }
        else{
            $(this).parent().addClass('checked');
        }
    });

     $(document).on("click", ".delete-option-btn", function(event){
        let checkboxOptionContainer = $(this).parent();
        let deleteOptionUrl = checkboxOptionContainer.attr('data-delete-option-url');
        alert(deleteOptionUrl);
        $.ajax({
            url: deleteOptionUrl,
            cache: false,
            type: 'DELETE'
        }).done(function() {
            checkboxOptionContainer.remove();
        });
    });

});

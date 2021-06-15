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

});

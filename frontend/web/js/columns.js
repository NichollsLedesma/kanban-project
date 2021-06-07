$( document ).ready(function() {
    $(document).on("click", ".dropbtn", function(){
        $(this).next('.dropdown-content').addClass('show');
    });

    $(document).on("click", ".archive-btn", function(event){
        let archiveColumnUrl = $(this).attr('data-column-archive-url');
        $.ajax({
            url: `${archiveColumnUrl}`,
            cache: false,
            type: 'DELETE',
        });
    });

    $(document).on("blur", "#updatecolumnform-title", function(event){
        $("#w0").submit();
    });

    $(window).click(function(e) {
        if (!e.target.matches('.dropbtn')) {
            $('.dropdown-content').removeClass('show');
        }
    });

     $( ".title-input" ).blur(function() {
        $(this).addClass('d-none');
        $(this).parent().children(".card-title" ).removeClass('d-none');
        $(this).parent().children(".card-title").html($( this).val());
    });
});

function addNewColumn(columnHtml) {
    $('.transparent').before(columnHtml);
}
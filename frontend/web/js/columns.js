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

    $(window).click(function(e) {
        if (!e.target.matches('.dropbtn')) {
            $('.dropdown-content').removeClass('show');
        }
    });
});
$( document ).ready(function() {
    $('.dropbtn').click(function() {
        $(this).next('.dropdown-content').addClass('show');
    });

    $('.archive-btn').click(function() {
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
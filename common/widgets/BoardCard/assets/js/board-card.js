function boardCardLoadContent(elm) {
    $(".modal-body", $(elm).attr('data-target')).load($(elm).attr('href'));
}


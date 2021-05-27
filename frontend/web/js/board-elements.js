
function handleCardElement(columnId, data = '') {
    $.post({
        url: location.hostname + '?columnId=' + columnId + '&type=card',
        data: data,
        cache: false,
        success: function (data) {
            addCardElement(columnId, data);
        }
    });

}

function handleColumnElement(boardId, data = '') {
    $.post({
        url: location.hostname + '?boardId=' + boardId + '&type=column',
        data: data,
        cache: false,
        success: function (data) {
            addColumnElement(boardId, data);
        }
    });
}

function addColumnElement(boardId, data) {
    $('#test').append(data);
    $('#add-list').hide();
}

function addCardElement(columnId, data) {
    removeCardElement(columnId);
    $(data).insertBefore($('.add-card', '[data-column-id="' + columnId + '"]'));
    bindCardElement(columnId);
    $('[data-column-id="' + columnId + '"]').scrollTop($('[data-column-id="' + columnId + '"]').prop("scrollHeight"));
}

function removeCardElement(columnId, reset = false) {
    if ($('form', '[data-column-id="' + columnId + '"]').length) {
        $('form', '[data-column-id="' + columnId + '"]').remove();
        bindCardElement(columnId, false);
    }

    if (reset === true) {
        $('.add-card', '[data-column-id="' + columnId + '"]').show();
}
}

function bindCardElement(columnId, bind = true) {
    if (bind === false) {
        $('form', '[data-column-id="' + columnId + '"]').unbind('beforeSubmit');
        return;
    }
    $('.add-card', '[data-column-id="' + columnId + '"]').hide();
    $('form', '[data-column-id="' + columnId + '"]').on('beforeSubmit', function (e) {
        e.preventDefault();
        handleCardElement(columnId, $(e.currentTarget).serialize());
        return false;
    });
}

function cancelCardElement(columnId) {
    removeCardElement(columnId, true);
}

function cancelColumnElement(e) {
    $(e).parent().remove();
    $('#add-list').show();
}

function isAddingCard(columnId)
{
    return $('form', '[data-column-id="' + columnId + '"]').length;
}

$(document).ready(function () {
    $('.add-card').click(function (e) {
        let columnId = $(this).parent('[data-column-id]').data()['columnId'];
        if (isAddingCard(columnId)) {
            return;
        }
        handleCardElement(columnId);
    });

    $('#add-list').click(function (e) {
        handleColumnElement(1);
    });
});
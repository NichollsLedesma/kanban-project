
function handleCardElement(columnId, data = '') {
    $.post({
        url: location.hostname + '?columnId=' + columnId + '&type=card',
        data: data,
        cache: false,
        success: function (data) {
            if (data === true) {
                cancelCardElement(columnId);
                return;
            }
            addCardElement(columnId, data);
        }
    });

}

function handleColumnElement(data = '') {
    $.post({
        url: location.hostname + '?boardId=' + boardId + '&type=column',
        data: data,
        cache: false,
        success: function (data) {
            if (data === true) {
                cancelColumnElement();
                return;
            }
            addColumnElement(data);
        }
    });
}

function addColumnElement(data) {
    removeColumnElement();
    $(data).insertBefore($('#add-list'));
    bindColumnElement();
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

function removeColumnElement(reset = false) {
    if ($('#creation-column form').length) {
        $('#creation-column form').remove();
        bindColumnElement(false);
    }

    if (reset === true) {
        $('#add-list').show();
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

function bindColumnElement(bind = true) {
    if (bind === false) {
        $('#creation-column form').unbind('beforeSubmit');
        return;
    }
    $('#add-list').hide();
    $('#creation-column form').on('beforeSubmit', function (e) {
        e.preventDefault();
        handleColumnElement($(e.currentTarget).serialize());
        return false;
    });
}

function cancelCardElement(columnId) {
    removeCardElement(columnId, true);
}

function cancelColumnElement() {
    removeColumnElement(true);
}

function isAddingCard(columnId)
{
    return $('form', '[data-column-id="' + columnId + '"]').length;
}

function isAddingColumn()
{
    return $('#creation-column form').length;
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
        if (isAddingColumn()) {
            return;
        }
        handleColumnElement();
    });
});
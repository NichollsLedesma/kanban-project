function addNewColumn(columnHtml) {
    $('.transparent').before(columnHtml);
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

function removeColumnElement(reset = false) {
    if ($('#creation-column form').length) {
        $('#creation-column form').remove();
        bindColumnElement(false);
    }

    if (reset === true) {
        $('#add-list').show();
    }
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

function cancelColumnElement() {
    removeColumnElement(true);
}

function isAddingColumn()
{
    return $('#creation-column form').length;
}

$(document).ready(function () {
    $('#add-list').click(function (e) {
        if (isAddingColumn()) {
            return;
        }
        handleColumnElement();
    });
});
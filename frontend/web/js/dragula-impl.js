
let dragulaComp = dragula();

const addColumnDragula = (column) => {
    if (!dragulaComp.containers.includes(column)) {
        dragulaComp.containers.push(document.getElementById(column));
    }
}

dragulaComp.on('drop', (component) => {
//    console.log(component)
    let card = $(component).attr("id").split('card_')[1];
    if (card === 'new') {
        return;
    }
    let order = $(component, $(component).parent('div .card-body')).index();
    let column = $(component).parent('div .card-body').attr('data-column-id');
    let board = board_id;
    $.post(channelName + '?changeOrder=true', {'column': column, 'card': card, 'order': order, 'board': board});

//        const taskId = Number($(component).attr("id").split('_')[1]);
//        const targetColumnId = $(component.parentElement).attr('data-column-id')
//
//        sendMessage({taskId, targetColumnId})
})

$(document).ready(function () {

    ////////////////////////////////////////////
    const wsbroker = "localhost";  // mqtt websocket enabled broker
    const wsport = 15675; // port for above
    const client = new Paho.MQTT.Client(
            wsbroker,
            wsport,
            "/ws",
            "myclientid_" + parseInt(Math.random() * 100, 10)
            );

    client.onConnectionLost = function (responseObject) {
        connect();
    };

    client.onMessageArrived = function (message) {
        const objData = JSON.parse(message.payloadString);
        if (objData.type === 'New Column') {
            addNewColumn(objData.html);
        }
        ;
        moveCard(objData);
        if (objData.type === 'card' && objData.action === 'new') {
            if ($('div#column-id_' + objData.params.columnId).length > 0) {
                $('div#column-id_' + objData.params.columnId).append(objData.params.html);
            }
        }
        if (objData.type === 'card' && objData.action === 'move') {
            let elm = $('#card_' + objData.params.cardId);
            $('#column-id_' + objData.params.columnId).append(elm);

        }

    };
    connect();

    ////////////
    function connect() {
        client.connect({
            onSuccess: () => {
                client.subscribe(channelName);
            }
        });
    }

    function sendMessage(objData) {
//        message = new Paho.MQTT.Message(JSON.stringify(objData));
//        message.destinationName = channelName;
//        client.send(message);
    }

    function moveCard(data) {
        card = $(`#${data.taskId}`)
        set = $(`div[data-column-id=${data.targetColumnId}]`)
        set.append(card);
    }

    //////////////////////////////////////////
    $('#search').autocomplete({
        type: "POST",
        minLength: 3,
        source: (request, response) => {
            $('.ui-autocomplete').css('z-index', 9999);
            $.get(window.location.pathname + "/get/" + request.term, (options) => {
                response(options);
            });
        },
        select: (event, ui) => {
            const {id} = ui.item;
            getInfoAndOpenModal(id);
        }
    });

//    $(".task").on("click", (e) => {
//        const id = $(e.currentTarget).attr("id").split('_')[1];
//        getInfoAndOpenModal(id);
//    });

    function getInfoAndOpenModal(id) {
        $.get("get-one/" + id,
                (task) => {
            console.log(task)
            const modal = $('#detailModal');
            modal.modal('show')
            modal.find(".modal-title").html(task.title);
            modal.find(".content").html(task.description)
        });
    }

    const boardNameComp = $("#boardname");
    boardNameComp.val(boardName);
    boardNameComp.addClass("disabled-style")
    boardNameComp.on("click", (e) => {
        boardNameComp.removeClass("disabled-style");
        boardNameComp.select();
    });
    boardNameComp.on("blur", (e) => {
        boardNameComp.addClass("disabled-style");
        if (boardNameComp.val() !== boardName) {
            const url = `${window.location.pathname.replace("kanban", "board/update")}`
            boardName = boardNameComp.val();
            $.ajax({
                method: "PUT",
                url,
                data: {title: boardNameComp.val()},
            });
        }
    });

    $("#remove-board").on("click", (e) => {
        if (confirm("Are you sure you want to delete this board?")) {
            const url = `${window.location.pathname.replace("kanban", "board/delete")}`
            $.ajax({
                method: "DELETE",
                url
            });
        }
    });

});

$(document).ready(function () {
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
        };
        moveCard(objData);
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
        message = new Paho.MQTT.Message(JSON.stringify(objData));
        message.destinationName = channelName;
        client.send(message);
    }

    function moveCard(data) {
        card = $(`#${data.taskId}`)
        set = $(`div[data-column-id=${data.targetColumnId}]`)
        set.append(card);
    }

    const dragulaComp = dragula(
        columns.map(column => document.getElementById(column))
    );

    dragulaComp.on('drop', (component) => {
        const taskId = Number($(component).attr("id").split('_')[1]);
        const targetColumnId = $(component.parentElement).attr('data-column-id')

        sendMessage({ taskId, targetColumnId })
    })
    
    

    $('#search').autocomplete({
        type: "POST",
        minLength: 3,
        source: (request, response) => {
            $('.ui-autocomplete').css('z-index', 9999);
            $.get("get/" + request.term, (options) => {
                response(options);
            });
        },
        select: (event, ui) => {
            const { id } = ui.item;
            getInfoAndOpenModal(id);
        }
    });

    $(".task").on("click", (e) => {
        const id = Number($(e.currentTarget).attr("id").split('_')[1]);
        getInfoAndOpenModal(id);
    });

    function getInfoAndOpenModal(id) {
        $.get("get-one/" + id,
            (task) => {
                const modal = $('#detailModal');
                modal.modal('show')
                modal.find(".modal-title").html(task.name);
                modal.find(".content").html(task.description)
            });
    }
});

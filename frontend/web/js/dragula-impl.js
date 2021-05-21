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
        console.log("CONNECTION LOST - " + responseObject.errorMessage);
    };

    client.onMessageArrived = function (message) {
        const objData = JSON.parse(message.payloadString);
        moveCard(objData);
    };

    client.connect({
        onSuccess: () => {
            client.subscribe(channelName);
        }
    });

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
        const taskId = $(component).attr("id");
        const targetColumnId = $(component.parentElement).attr('data-column-id')

        sendMessage({ taskId, targetColumnId })
    })

    $('#search').autocomplete({
        type: "POST",
        minLength: 3,
        source: (request, response) => {
            $.get("kanban/get", {
                query: request.term
            }, (data) => {
                response(JSON.parse(data));
            });
        },
        select: (event, ui) => {
            console.log(ui)
        }
    });

});

$(document).ready(function () {
    const options = {
        timeout: 3,
        keepAliveInterval: 30,
        onSuccess: function () {
            console.log("CONNECTION SUCCESS");
            client.subscribe(channelName);
            message = new Paho.MQTT.Message("Hello");
            message.destinationName = "World";
            client.send(message);
        },
        onFailure: function (message) {
            console.log("CONNECTION FAILURE - " + message.errorMessage);
        }
    };
    const wsbroker = location.hostname;  // mqtt websocket enabled broker
    const wsport = 15675; // port for above
    const client = new Paho.MQTT.Client(
        wsbroker,
        wsport,
        "/ws",
        "myclientid_" + parseInt(Math.random() * 100, 10)
    );

    client.onConnectionLost = onConnectionLost;
    client.onMessageArrived = onMessageArrived;
    // connect the client
    client.connect(options);

    const dragulaComp = dragula(
        columns.map(column => document.getElementById(column))
    );

    dragulaComp.on('drop', (component) => {
        const id = $(component).attr("id");
        // const targetParentId = $(component.parentElement).attr('id')
        const targetParentId = $(component.parentElement).attr('data-column-id')

        moveCard(id, targetParentId)
    })

    const moveCard = (taskId, targetColumnId) => {
        console.log(taskId, targetColumnId)
        message = new Paho.MQTT.Message("Hello");
        message.destinationName = "Nicholls";
        console.log('message sended',message)
        client.send(message);
       
        // $.ajax({
        //     type: "POST",
        //     url: 'kanban/move',
        //     data: { taskId, targetColumnId },
        //     success: (data) => {
        //         console.log(data);
        //     },
        // });
    }




    function onConnectionLost(responseObject) {
        console.log( responseObject.errorMessage);
        console.log("CONNECTION LOST - " + responseObject.errorMessage);
    };
    
    function onMessageArrived(message) {
        console.log(message)
        console.log("RECEIVE ON " + message.destinationName + " PAYLOAD " + message.payloadString);
        // print_first(message.payloadString);
    };

    // called when the client connects
    function onConnect() {
        // Once a connection has been made, make a subscription and send a message.
        console.log("onConnect");
        // client.subscribe("World");
        message = new Paho.MQTT.Message("Hello");
        message.destinationName = "World";
        client.send(message);
    }

});
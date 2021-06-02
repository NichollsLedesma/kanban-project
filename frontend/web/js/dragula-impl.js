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
        if (objData.type === 'Column ReOrder') {
            $.pjax.reload({container: '#board-container'});
        };
        moveCard(objData);
        if (objData.type === 'card' && objData.action === 'new') {
            if ($('div#column-id_' + objData.params.columnId).length > 0) {
                $('div#column-id_' + objData.params.columnId).append(objData.params.html);
            }
        }
    };
    connect();
    let currentColumnOrder = getCurrentColumnOrder();

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

    function dragulaLoad(){
        d = dragula(
            columns.map(column => document.getElementById(column))
            );
        d.containers.push(document.getElementById('board-body'));
        d.on('drop', (component) => {
            if ($(component).hasClass('task')) {
                const taskId = Number($(component).attr("id").split('_')[1]);
                const targetColumnId = $(component.parentElement).attr('data-column-id')
                sendMessage({taskId, targetColumnId})
            }
            if ($(component).hasClass('card-row')) {
                let updatedColumnOrder = getCurrentColumnOrder();
                $.post({
                    url: 'http://y2aa-frontend.test:81/kanban/update-order',
                    data: {order:updatedColumnOrder},
                    cache: false,
                });
            }
        });

        return d;
    }

    function getCurrentColumnOrder() {
        let columns = [];
        $( "div[id*='column-id']" ).each(function() {
            columns.push($(this).attr('data-column-id'));
        });
        return Array.from(new Set(columns));
    }

    let dragulaComp = dragulaLoad();

    $(document).on('pjax:end', function() {
        dragulaComp = dragulaLoad();
        currentColumnOrder = getCurrentColumnOrder();
    });

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
            const {id} = ui.item;
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

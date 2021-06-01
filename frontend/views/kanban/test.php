<?php

use yii\web\View;

/* @var $this View */
$this->registerAssetBundle(frontend\assets\paho\PahoAsset::class,View::POS_BEGIN);
//$this->registerJs(<<<JS
//        client = new Paho.MQTT.Client(location.hostname, Number(location.port), "clientId");
//
//// set callback handlers
//client.onConnectionLost = onConnectionLost;
//client.onMessageArrived = onMessageArrived;
//
//// connect the client
//client.connect({onSuccess:onConnect});
//
//
//// called when the client connects
//function onConnect() {
//  // Once a connection has been made, make a subscription and send a message.
//  console.log("onConnect");
//  client.subscribe("World");
//  message = new Paho.MQTT.Message("Hello");
//  message.destinationName = "World";
//  client.send(message);
//}
//
//    // called when the client loses its connection
//    function onConnectionLost(responseObject) {
//    if (responseObject.errorCode !== 0) {
//    console.log("onConnectionLost:"+responseObject.errorMessage);
//    }
//    }
//
//    // called when a message arrives
//    function onMessageArrived(message) {
//    console.log("onMessageArrived:"+message.payloadString);
//    }
//
//        
//   JS);
$sessId = Yii::$app->session->id;
?>
aca
<script type="text/javascript">

    client = new Paho.MQTT.Client("127.0.0.1", Number(15675),'/ws', "clientId_<?= $sessId ?>");

// set callback handlers
    client.onConnectionLost = onConnectionLost;
    client.onMessageArrived = onMessageArrived;

// connect the client
    client.connect({onSuccess: onConnect});


// called when the client connects
    function onConnect() {
        // Once a connection has been made, make a subscription and send a message.
        console.log("onConnect");
        client.subscribe("board_udpate");
        message = new Paho.MQTT.Message("<?= $_SERVER['HTTP_USER_AGENT'] ?>");
        message.destinationName = "board/create";
        client.send(message);
    }

    // called when the client loses its connection
    function onConnectionLost(responseObject) {
        if (responseObject.errorCode !== 0) {
            console.log("onConnectionLost:" + responseObject.errorMessage);
        }
    }

    // called when a message arrives
    function onMessageArrived(message) {
        console.log("onMessageArrived:" + message.payloadString);
    }

</script>
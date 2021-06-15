let dragulaComp = dragula([], {
  accepts: function (el, target, source, sibling) {
    if ($(el).hasClass("column")) return true;
    return (
      $(target).hasClass("column-container-to-card") && $(el).hasClass("task")
    );
  },
});

const addColumnDragula = (column) => {
  if (!dragulaComp.containers.includes(column)) {
    dragulaComp.containers.push(document.getElementById(column));
  }
};

dragulaComp.containers.push(document.getElementById("board-container"));

dragulaComp.on("drop", (component) => {
  if ($(component).hasClass("task")) {
    let card = $(component).attr("id").split("card_")[1];
    if (card === "new") {
      return;
    }
    let order = $(component, $(component).parent("div .card-body")).index();
    let column = $(component).parent("div .card-body").attr("data-column-id");
    let board = board_id;
    $.post(channelName + "?changeOrder=true", {
      column: column,
      card: card,
      order: order,
      board: board,
    });
  }

  if ($(component).hasClass("card-row")) {
    let updatedColumnOrder = getCurrentColumnOrder();
    $.post({
      url: updateColumnOrderUrl,
      data: { columns: updatedColumnOrder },
      cache: false,
    });
  }

  //        const taskId = Number($(component).attr("id").split('_')[1]);
  //        const targetColumnId = $(component.parentElement).attr('data-column-id')
  //
  //        sendMessage({taskId, targetColumnId})
});

function getCurrentColumnOrder() {
  let columns = [];
  $("div[id*='column-id']").each(function () {
    columns.push($(this).attr("data-column-id"));
  });
  return Array.from(new Set(columns));
}

$(document).ready(function () {
  ////////////////////////////////////////////
  const wsbroker = "localhost"; // mqtt websocket enabled broker
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
    if (objData.type === "New Column") {
      addNewColumn(objData.html);
    }
    if (objData.type === "Column ReOrder") {
      $.pjax.reload({ container: "#board-container" });
    }
    if (objData.type === "Column Updated") {
      if (location.href.includes("?")) {
        history.pushState({}, null, location.href.split("?")[0]);
      }
      $.pjax.reload({ container: "#board-container" });
    }
    if (objData.type === "Column Removed") {
      $.pjax.reload({ container: "#board-container" });
    }

    //        moveCard(objData);
    if (objData.type === "card" && objData.action === "new") {
      if ($("div#column-id_" + objData.params.columnId).length > 0) {
        $("div#column-id_" + objData.params.columnId).append(
          objData.params.html
        );
      }
    }
    if (objData.type === "card" && objData.action === "move") {
      let elm = $("#card_" + objData.params.cardId);
      let refElm = $("#column-id_" + objData.params.columnId)
        .children('div[id^="card_"]')
        .not(elm)
        .get(objData.params.order);
      if (refElm === undefined) {
        if (objData.params.order == 0) {
          $("#column-id_" + objData.params.columnId).prepend(elm);
        } else {
          $("#column-id_" + objData.params.columnId).append(elm);
        }
      } else {
        $(refElm).before(elm);
      }
    }
    if (objData.type === "card" && objData.action === "update") {
      let elm = $("#card_" + objData.params.cardId);
      $(elm).css({ "border-top-color": "#" + objData.params.color });
      $("h5.card-title", elm).html(objData.params.title);
      $(".card-body p", elm).html(objData.params.description);
    }
    if (objData.type === "card" && objData.action === "remove") {
      let elm = $("#card_" + objData.params.cardId);
      $(elm).remove();
    }
  };
  connect();
  let currentColumnOrder = getCurrentColumnOrder();

  ////////////
  function connect() {
    client.connect({
      onSuccess: () => {
        client.subscribe(channelName);
      },
    });
  }

  function sendMessage(objData) {
    //        message = new Paho.MQTT.Message(JSON.stringify(objData));
    //        message.destinationName = channelName;
    //        client.send(message);
  }

  function moveCard(data) {
    card = $(`#${data.taskId}`);
    set = $(`div[data-column-id=${data.targetColumnId}]`);
    set.append(card);
  }

  $(document).on("pjax:end", function () {
    currentColumnOrder = getCurrentColumnOrder();
  });

  //////////////////////////////////////////
  $("#search").autocomplete({
    type: "POST",
    minLength: 3,
    source: (request, response) => {
      $(".ui-autocomplete").css("z-index", 9999);
      $.get(window.location.pathname + "/get/" + request.term, (options) => {
        response(options);
      });
    },
    select: (event, ui) => {
      const { id } = ui.item;
      $("#card_"+id+" a" ).trigger('click');
//      getInfoAndOpenModal(id);
    },
  });

  //    $(".task").on("click", (e) => {
  //        const id = $(e.currentTarget).attr("id").split('_')[1];
  //        getInfoAndOpenModal(id);
  //    });

  function getInfoAndOpenModal(id) {
    $.get("get-one/" + id, (task) => {
      const modal = $("#detailModal");
      modal.modal("show");
      modal.find(".modal-title").html(task.title);
      modal.find(".content").html(task.description);
    });
  }

  const boardNameComp = $("#boardname");
  boardNameComp.val(boardName);
  boardNameComp.addClass("disabled-style");
  boardNameComp.on("click", (e) => {
    boardNameComp.removeClass("disabled-style");
    boardNameComp.select();
  });
  boardNameComp.on("blur", (e) => {
    boardNameComp.addClass("disabled-style");
    if (boardNameComp.val() !== boardName) {
      const url = `${window.location.pathname.replace(
        "kanban",
        "board/update"
      )}`;
      $.ajax({
        method: "PUT",
        url,
        data: { title: boardNameComp.val() },
      })
        .then((data) => {
          boardName = boardNameComp.val();
        })
        .catch((err) => {
          boardNameComp.val(boardName);
        });
    }
  });

  $("#remove-board").on("click", (e) => {
    if (confirm("Are you sure you want to delete this board?")) {
      const url = `${window.location.pathname.replace(
        "kanban",
        "board/delete"
      )}`;
      $.ajax({
        method: "DELETE",
        url,
      });

      window.location.href = "/kanban/index";      
    }
  });

  $("#user-leave-board").on("click", (e) => {
    if (confirm("Are you sure you want to leave this board?")) {
      const url = `${window.location.pathname.replace(
        "kanban",
        "board/leave"
      )}`;
      $.ajax({
        method: "POST",
        url,
      });
    }
  });

  $(".remove-user-board").on("click", (e) => {
    e.preventDefault();
    const url = e.target.href;
    const item = e.target.id.replace('remove-user-board-', 'list-item-');
    if (confirm("Are you sure you want to remove this user?")) {
      $.ajax({
        method: "DELETE",
        url,
      }).done(function () {
        $("#boardMenu").modal("hide");
        $("#" + item).remove();
      });
    }
  });

  $("#board-manage-users").click(function (e) {
    e.preventDefault();
    $("#boardMenu").modal("show");
  });
});

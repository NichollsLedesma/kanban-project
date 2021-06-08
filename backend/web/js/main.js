$(document).ready(function () {
    $("#select_entity").on("change", (e) => {
        const optionSelected = Number($(this).find('option:selected').val());
        const theUserEntity = usersEntity.filter(user => {
            return user.entity_id === optionSelected;
        })

        $("#select_owner").html('<option value="">Owner</option>>');

        theUserEntity.forEach(user => {
            $("#select_owner").append(`<option value="${user.user_id}">${user.user.username}</option>`);
        });
    })
})
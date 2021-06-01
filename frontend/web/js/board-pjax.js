/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    $(document).on('pjax:end', function() {
        const dragulaComp = dragula(
            columns.map(column => document.getElementById(column))
            );
    	dragulaComp.containers.push(document.getElementById('board-body'));
    });
});



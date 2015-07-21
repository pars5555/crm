/* ~~~~ Datapicker ~~~~ START*/

//datapicker init
$("#accordion .f_from").datepicker({
    showOtherMonths: true,
    selectOtherMonths: true,
    nextText: '<i class="btn-next fa fa-angle-right"></i>',
    prevText: '<i class="btn-prev fa fa-angle-left"></i>',
    gotoCurrent: true,
    //defaultDate: "+1w",
    numberOfMonths: 1,
    setDate: new Date(),
    onSelect: function (date) {
    },
    onClose: function (selectedDate) {
        $("#accordion .f_from").datepicker("option", "minDate", selectedDate);
    }
});
$("#accordion .f_to").datepicker({
    showOtherMonths: true,
    selectOtherMonths: true,
    nextText: '<i class="btn-next fa fa-angle-right"></i>',
    prevText: '<i class="btn-prev fa fa-angle-left"></i>',
    gotoCurrent: true,
    //defaultDate: "+1w",
    numberOfMonths: 1,
    setDate: new Date(),
    onSelect: function (date) {
    },
    onClose: function (selectedDate) {
        $("#accordion .f_to").datepicker("option", "maxDate", selectedDate);
    }
});

//datapicker open animation
$("#anim").change(function () {
    $(".f_from").datepicker("option", "showAnim", $(this).val());
    $(".f_to").datepicker("option", "showAnim", $(this).val());
});

//sets datapicker to corrent date
$("#accordion  .f_from, #accordion  .f_to").datepicker('setDate', new Date());


//opens datapicker on datapicker icon click
$("#accordion .f_to + i").click(function () {
    $(this).datepicker("show");
});

//opens datapicker on datapicker icon click
$("#accordion  .f_from + i").click(function () {
    $(this).datepicker("show");
});

/* ~~~~ Card ~~~~ START*/
$("#accordion  .f_card .details ul").sortable({
    connectWith: ".f_connectedSortable",
    revert: true,
    placeholder: "sortable-placeholder",
    cancel: ".f_close",
    handle: '.f_move',
    start : function(event, ui) {
        ui.helper.parent().width($(this).width());
    }
}).disableSelection();
console.log(1);



$("#accordion").on('click' , '.f_close' , function () {
    $(this).parent().slideUp();
    setTimeout(function () {
        $(this).parent().remove();
    }, 300);
});


$('#accordion  .f_disableBtn').on('click', function () {
    $(this).closest('.card').addClass('cardDisabled');
    $(this).closest('.card').find('ul').removeClass('f_connectedSortable ui-sortable');
});

$('#accordion  .f_enableBtn').on('click', function () {
    $(this).closest('.card').removeClass('cardDisabled');
    $(this).closest('.card').find('ul').addClass('f_connectedSortable ui-sortable');
});


$("#accordion").accordion({
    heightStyle: "content",
    collapsible: true
});



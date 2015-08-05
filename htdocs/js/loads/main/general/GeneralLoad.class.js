NGS.createLoad("crm.loads.main.general.general", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#GeneralForm').find('select').change(function () {
            $('#GeneralForm').trigger('submit');
        });
    }
});

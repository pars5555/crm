NGS.createLoad("crm.loads.main.recipient.open", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
      $('#deleteRecipientButton').click(function () {
            if (confirm("Are you sure you want to delete recipient? It will delete all transactions related to this recipient."))
            {
                $(this).closest('form').trigger('submit');
            }
        });
    }
});

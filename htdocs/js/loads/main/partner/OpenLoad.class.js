NGS.createLoad("crm.loads.main.partner.open", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
      $('#deletePartnerButton').click(function () {
            if (confirm("Are you sure you want to delete partner? It will delete all transactions related to this partner."))
            {
                $(this).closest('form').trigger('submit');
            }
        });
    }
});

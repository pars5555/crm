NGS.createLoad("crm.loads.main.partners", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#createPartnerButton').click(function () {
            if ($('.createPartner').hasClass('hide'))
            {
                $('.createPartner').removeClass('hide');
            }
        });
        $('#cancelPartnerButton').click(function () {
            if (!$('.createPartner').hasClass('hide'))
            {
                $('.createPartner').addClass('hide');
            }
        });
    }
});

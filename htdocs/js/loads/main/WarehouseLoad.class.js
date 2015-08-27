NGS.createLoad("crm.loads.main.warehouse", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('.tooltipster').each(function () {
            var content = $(this).find('p').html();
            $(this).tooltipster({
                content: $(content),
                animation: 'fade',
                trigger: 'hover',
                contentAsHTML:true,
                interactive:true,
                theme: 'tooltipster-shadow'
            });
        });
    }
});

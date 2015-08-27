NGS.createLoad("crm.loads.main.product.list", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#productFilters').find('input, select, checkbox').change(function () {
            $('#productFilters').trigger('submit');
        });

        $('.tooltipster').each(function () {
            var content = $(this).find('p').html();
            $(this).tooltipster({
                content: $(content),
                animation: 'fade',
                trigger: 'hover',
                contentAsHTML: true,
                interactive: true,
                theme: 'tooltipster-shadow'
            });
        });
    }
});

NGS.createLoad("crm.loads.main.product.list", {
    search_timout_handler: null,
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#productFilters').find('input[name="st"]').keyup(function () {
            if (this.search_timout_handler > 0) {
                window.clearTimeout(this.search_timout_handler);
            }
            this.search_timout_handler = window.setTimeout(function () {
                $('#productFilters').trigger('submit');
            }, 1000);
        }.bind(this));
        $('#productFilters').find('select, checkbox').change(function () {
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
                minWidth: 300,
                theme: 'tooltipster-shadow'
            });
        });
        $('.f_hidden_checkbox').change(function () {
            var product_id = $(this).data('product_id');
            var hidden = $(this).is(':checked') ? 1 : 0;

            NGS.action('crm.actions.main.product.set_product_hidden', {product_id: product_id, hidden: hidden});
        });
    }
});

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
        this.initExport();
        this.initQtyChecked();
    },
    initQtyChecked: function(){
        $('.f_qty_checked_checkbox').change(function () {
            var product_id = $(this).data('product_id');
            var qty_checked = $(this).is(':checked') ? 1 : 0;

            NGS.action('crm.actions.main.product.set_product_qty_checked', {product_id: product_id, qty_checked: qty_checked});
        });
    },
    initExport: function(){
        $('#export_csv').click(function(){
            var actionUrl = '/dyn/main_warehouse/do_export_csv?';
            $(this).attr('href', actionUrl);
        });
        
    }
});

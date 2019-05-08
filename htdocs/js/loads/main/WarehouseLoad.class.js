NGS.createLoad("crm.loads.main.warehouse", {
    getContainer: function () {
        return "indexRightContent";
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
                contentAsHTML: true,
                interactive: true,
                minWidth: 300,
                theme: 'tooltipster-shadow'
            });
        });
        this.initExport();
        this.initReserve();
        this.initSort();
        this.initQtyChecked();
        $('.f_include_in_price_xlsx_checkbox').change(function () {
            var id = $(this).data('product_id');
            var checked = $(this).is(':checked') ? 1 : 0;
            NGS.action('crm.actions.main.UpdateField', {'id': id, 'object_type': 'product', 'field_name': 'include_in_price_xlsx', "field_value": checked});
        });
        
        $("#partner_select").chosen({
            search_contains: true
        });
    },
    initSort:function(){
        $('#warehouseFilters').find('select, checkbox').change(function () {
            $('#warehouseFilters').trigger('submit');
        });
    },
    initReserve: function () {
        $('.f_reserve').click(function () {
            $('#add_reservation_modalBox').addClass('is_active');
            var productName = $(this).data('product_name');
            $('#add_reservation_modalBox').find('.f_product_name').text(productName);
            $('#reserve_product_id').val($(this).data('product_id'));
        });
        $('#add_reservation_modalBox #reserve_confirm').click(function () {
            var reserve_product_id = $('#reserve_product_id').val();
            var reserve_qty = $('#reserve_qty').val();
            var reserve_hours = $('#reserve_hours').val();
            var reserve_phone_number = $('#reserve_phone_number').val();
            var reserve_note = $('#reserve_note').val();
            var params = {product_id: reserve_product_id, qty: reserve_qty, hours: reserve_hours, phone_number: reserve_phone_number, note: reserve_note};
            NGS.action('crm.actions.main.product.reserve_product', params);
        });
    },
    initQtyChecked: function () {
        $('.f_qty_checked_checkbox').change(function () {
            var product_id = $(this).data('product_id');
            var qty_checked = $(this).is(':checked') ? 1 : 0;

            NGS.action('crm.actions.main.product.set_product_qty_checked', {product_id: product_id, qty_checked: qty_checked});
        });
    },
    initExport: function () {
        $('#export_csv').click(function () {
            var actionUrl = '/dyn/main_warehouse/do_export_csv?';
            $(this).attr('href', actionUrl);
        });

    }
});

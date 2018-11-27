NGS.createLoad("crm.loads.main.purse.prepare_hidden", {
    getContainer: function () {
        return "hide_modalBox";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        this.autocompleteSelect();
        $('#hide_modalBox').addClass('is_active');
        $('#create_purchase_btn').click(function () {
            $('#create_purchase_btn').addClass('hidden');
            this.createPurchase();
        }.bind(this));

    },
    autocompleteSelect: function () {
        $(".f_purchase_item").chosen({
            search_contains: true
        });
    },
    createPurchase: function () {
        var products = [];
        $('.f_purchase_container').each(function () {
            var productId = $(this).find('.f_purchase_item').val();
            var price = $(this).find('.f_purchase_item_price').val();
            var weight = $(this).find('.f_purchase_item_weight').val();
            var tax = $(this).find('.f_purchase_item_tax').val();
            var quantity = $(this).find('.f_purchase_item_quantity').val();
            var name = $(this).find('.f_purchase_item_name').val();
            products.push({product_id: productId, name: name,price: price, weight: weight, quantity: quantity, tax:tax});
        });
        var id = $('#purse_order_id').val();
        NGS.action('crm.actions.main.purse.set_hidden_and_create_purchase', {id: id, products: JSON.stringify(products)});
    }

});

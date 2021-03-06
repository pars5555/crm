NGS.createLoad("crm.loads.main.purchase.list", {
    getContainer: function () {
        return "initialLoad";
    },
    onError: function (params) {

    },
    afterLoad: function () {
        $('#purchaseOrderFilters').find('input, select, checkbox').change(function () {
            $('#purchaseOrderFilters').trigger('submit');
        });
        $('.deletePurchaseOrder').click(function () {
            if (confirm("Are you sure you want to delete this Purchase Order?!"))
            {
                return true;
            }
            return false;
        });
      this.initExportCsv();
        this.initFromAndToDateParams();
    },
    initExportCsv:function(){
        $('#export_csv').click(function(){
             $('#export_modalBox').addClass('is_active');
        });
        $('#export_modalBox .f_export').click(function(){
            var startDate = $('#exportStartDate').val();
            var endDate = $('#exportEndDate').val();
            var partner_id = $('#export_modalBox .f_partner_id').val();
            var params = {startDate :startDate ,endDate:endDate,partner_id:partner_id};
            var urlParams = $.param(params);
            var actionUrl = '/dyn/main_purchase/do_export_csv?';
            $(this).attr('href', actionUrl + urlParams);
       
            
            
        });
    },
     initFromAndToDateParams: function () {

        $('#startDateWidget').datetimepicker({
            format: 'Y-m-d',
            inline: true,
            timepicker: false,
            step: 1,
            lang:'hy',
            onSelectDate: function (ct, $i) {
                $('#exportStartDate').val(ct.dateFormat('Y-m-d'));
            }
        });
        $('#endDateWidget').datetimepicker({
            format: 'Y-m-d',
            inline: true,
            timepicker: false,
            step: 1,
            lang:'hy',
            onSelectDate: function (ct, $i) {
                $('#exportEndDate').val(ct.dateFormat('Y-m-d'));
            }
        });
    }
});

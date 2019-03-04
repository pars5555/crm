NGS.createLoad("crm.loads.main.checkout.list", {
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
        $('#productFilters').find('select').change(function () {
            $('#productFilters').trigger('submit');
        });
        $('#productFilters').find('input[name="pr"]').change(function () {
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
        this.initUpload();
        this.initHide();
        this.initDelete();
        this.initProblematic();
        this.initSolveProblematic();
        this.initTrackingsSearch();
        this.initExportTrackingsSearch();
        this.initRefreshTracking();
        this.initRefreshRecipient();
        this.initRefreshCarrierDeliveryDate();
        this.initExport();
        this.initConfirmOrder();
    },
    initConfirmOrder: function () {
        $('.f_confirm_order').click(function () {
            $('#confirming_order_id').val($(this).data('id'));
            $('#confirm_order').addClass('is_active');
        });
        $('#confirm_checkout_order_btn').click(function () {
            var id = $('#confirming_order_id').val();
            NGS.action('crm.actions.main.checkout.confirm', {'id': id}, function (data) {
                console.log(data);
                if (data.success === true) {
                    window.open('https://www.amazon.com/dp/' + data.asin);
                    $('#confirm_order').removeClass('is_active');
                }else{
                    alert(data.message);
                }
            });
        });

    },
    initExport: function () {
        $('#export_csv').click(function () {
            var actionUrl = '/dyn/main_checkout/do_export_csv' + window.location.search;
            $(this).attr('href', actionUrl);
        });

    },
    initExportTrackingsSearch: function () {
        $('#export_search').click(function () {
            var trackingNumbers = $('#tracking_numbers_input').val();
            var params = {trackingNumbers: trackingNumbers};
            var urlParams = $.param(params);
            var actionUrl = '/dyn/main_checkout/do_export_search_csv?';
            $(this).attr('href', actionUrl + urlParams);
        });
    },
    initTrackingsSearch: function () {
        $('#find_trackings_button').click(function () {
            $('#trackings_modalBox').addClass('is_active');
        });
    },
    initAddExternalOrder: function () {
        $('#add_external_order_button').click(function () {
            $('#add_external_order_modalBox').addClass('is_active');
        });
    },

    initHide: function () {
        $('.f_hide').click(function () {
            var id = $(this).data('id');
            NGS.load('crm.loads.main.purse.prepare_hidden', {id: id});
        });
    },
    initProblematic: function () {
        $('.f_problematic').click(function () {
            var id = $(this).data('id');
            NGS.action('crm.actions.main.purse.set_problematic', {id: id});

        });
    },
    initDelete: function () {
        $('.f_delete').click(function () {
            var id = $(this).data('id');
            $(this).closest('tr').remove();
            NGS.action('crm.actions.main.purse.delete', {id: id});
        });
    },
    initSolveProblematic: function () {
        $('.f_problem_solved').click(function () {
            var id = $(this).data('id');
            NGS.action('crm.actions.main.purse.set_problem_solved', {id: id});

        });
    },
    initRefreshTracking: function () {
        $('.f_refresh_tracking').click(function () {
            var id = $(this).data('id');
            NGS.action('crm.actions.main.purse.refresh_tracking', {id: id});
            $(this).closest('.f_tracking').html('');
        });
    },
    initRefreshRecipient: function () {
        $('.f_refresh_recipient').click(function () {
            var id = $(this).data('id');
            NGS.action('crm.actions.main.purse.refresh_recipient', {id: id}, function () {
                window.location.reload();
            });
        });
    },
    initRefreshCarrierDeliveryDate: function () {
        $('.f_refresh_carrier_delivery_details').click(function () {
            var id = $(this).data('id');
            NGS.action('crm.actions.main.purse.refresh_carrier_delivery_details', {id: id});
            $(this).remove();
            $('#carrier_delivery_details_' + id).html('');
            $('#carrier_tracking_status_' + id).html('');

        });
    },
    initUpload: function () {
        jQuery('#upload_button').click(function () {
            jQuery("#file_input").trigger('click');
            return false;
        });

        jQuery("#file_input").change(function () {
            jQuery('#upload_form').submit();
        });
    }
});
function uploadedFileResponse(changedOrNewOrdersText) {
    if (changedOrNewOrdersText.length > 0) {
        window.location.href = "/checkout/list?changed_orders=" + changedOrNewOrdersText;
    } else
    {
        window.location.href = "/checkout/list";

    }

}
;

jQuery(document).ready(function ($) {
    error_flag = false;
    var payment_gw_checkbox = $('input:checkbox[id="#wp_travel_engine_setting_paypal_payment"]');
    var payment_gw_id = $('.wp_travel_engine_settings_paypal_id');

    //add hide class
    $(window).on('load', function () {
        if ($('.obp-getting-started-content').hasClass('current')) {
            $('.obp-process-content-inner').siblings('.obp-footer').addClass('obp-hide');
        } else {
            $('.obp-process-content-inner').siblings('.obp-footer').removeClass('obp-hide');
        }

        if ($('.obp-ready-content').hasClass('current')) {
            $('.obp-footer .wpte-left-block .obp-prev-step, .obp-footer .wpte-right-block').addClass('obp-hide');
            $('.obp-footer .wpte-left-block .obp-btn-link').removeClass('obp-hide');
        } else {
            $('.obp-footer .wpte-left-block .obp-prev-step, .obp-footer .wpte-right-block').removeClass('obp-hide');
            $('.obp-footer .wpte-left-block .obp-btn-link').addClass('obp-hide');
        }
    });

    //animate progress bar
    $(window).on('load resize', function () {

        $(document).on("click", ".obp-next-step", function () {
            next_step_tab_action();
        });

        $(document).on("click", ".obp-prev-step", function () {
            prev_step_tab_action();
        });
    });

    //add remove current class
    $(document).on("click", ".obp-next-step", function () {
        next_step_action();
    });

    function next_step_tab_action() {
        var obpTabCount = $('.obp-process-inner .obp-process').length;
        var obpWinWidth = $('.obp-process-inner').outerWidth();
        var obpPercentageWidth = parseInt(obpWinWidth) / parseInt(obpTabCount);
        $('.obp-process-outer .obp-progress-bar').css('width', '+=' + obpPercentageWidth);
        return false;
    }

    function prev_step_tab_action() {
        var obpTabCount = $('.obp-process-inner .obp-process').length;
        var obpWinWidth = $('.obp-process-inner').outerWidth();
        var obpPercentageWidth = parseInt(obpWinWidth) / parseInt(obpTabCount);
        $('.obp-process-outer .obp-progress-bar').css('width', '-=' + obpPercentageWidth);
        return false;
    }

    $(document).on("click", ".obp-prev-step", function () {

        $('.obp-process-inner').find('.current').prev('.obp-process').removeClass('completed').addClass('current');
        $('.obp-process-inner').find('.current').next('.obp-process').removeClass('current');

        $('.obp-process-content-inner').find('.current').prev('.obp-process-content').removeClass('completed').addClass('current');
        $('.obp-process-content-inner').find('.current').next('.obp-process-content').removeClass('current');

        if ($('.obp-getting-started-content').hasClass('current')) {
            $('.obp-process-content-inner').siblings('.obp-footer').addClass('obp-hide');
        } else {
            $('.obp-process-content-inner').siblings('.obp-footer').removeClass('obp-hide');
        }

        if ($('.obp-ready-content').hasClass('current')) {
            $('.obp-footer .wpte-left-block .obp-prev-step, .obp-footer .wpte-right-block').addClass('obp-hide');
            $('.obp-footer .wpte-left-block .obp-btn-link').removeClass('obp-hide');
        } else {
            $('.obp-footer .wpte-left-block .obp-prev-step, .obp-footer .wpte-right-block').removeClass('obp-hide');
            $('.obp-footer .wpte-left-block .obp-btn-link').addClass('obp-hide');
        }
        var id = $('.obp-process-content.current').attr('id');
        goToByScroll(id);
    });

    function next_step_action() {
        $('.obp-process-inner').find('.current').removeClass('current').addClass('completed');
        $('.obp-process-inner').find('.completed').next('.obp-process').addClass('current');
        $('.obp-process-inner .obp-process.completed').removeClass('current');

        $('.obp-process-content-inner').find('.current').removeClass('current').addClass('completed');
        $('.obp-process-content-inner').find('.completed').next('.obp-process-content').addClass('current');
        $('.obp-process-content-inner .obp-process-content.completed').removeClass('current');

        if ($('.obp-getting-started-content').hasClass('current')) {
            $('.obp-process-content-inner').siblings('.obp-footer').addClass('obp-hide');
        } else {
            $('.obp-process-content-inner').siblings('.obp-footer').removeClass('obp-hide');
        }

        if ($('.obp-ready-content').hasClass('current')) {
            $('.obp-footer .wpte-left-block .obp-prev-step, .obp-footer .wpte-right-block').addClass('obp-hide');
            $('.obp-footer .wpte-left-block .obp-btn-link').removeClass('obp-hide');
        } else {
            $('.obp-footer .wpte-left-block .obp-prev-step, .obp-footer .wpte-right-block').removeClass('obp-hide');
            $('.obp-footer .wpte-left-block .obp-btn-link').addClass('obp-hide');
        }

        var id = $('.obp-process-content.current').attr('id');
        goToByScroll(id);
    }

    /**
    * Save and continue link action.
    */
    $(document).on('click', '.obp-btn-submit-continue', function (e) {
        e.preventDefault();
        // if(payment_gw_checkbox.prop("checked") == true && payment_gw_id.val() == ''){
        //     error_flag = true;
        // }else{
        //     error_flag = false;
        // }

        // if(error_flag == true){
        //     return;
        // }

        // Get Data.
        var parent = '.obp-process-content.current';
        var $this = $(this);
        var form_data = {};
        $(parent + ' input, ' + parent + ' select, ' + parent + ' textarea').each(function (index) {
            filterby = $(this).attr('name');
            filterby_val = $(this).val();
            if ('undefined' == typeof (form_data[filterby])) {
                form_data[filterby] = [];
            }
            if ($(this).attr('type') == 'checkbox') {
                if ($(this).is(':checked')) {
                    form_data[filterby] = filterby_val;
                } else {
                    form_data[filterby] = '';
                }
            } else {
                form_data[filterby] = filterby_val;
            }
        });

        form_data['next_tab'] = $(this).data('next-tab');
        form_data['action'] = 'wpte_onboard_save_function';
        form_data['_nonce'] = WPTEOB_OBJ.ajax_nonce;
        $.ajax({
            url: WPTEOB_OBJ.ajax_url,
            ajax_nonce: WPTEOB_OBJ.ajax_nonce,
            data: form_data,
            type: "post",
            dataType: "json",
            beforeSend: function (xhr) {
                $('#onboard-loader').show();
            },
            success: function (data) {
                if (data.success) {
                    next_step_tab_action();
                    next_step_action();
                    $('.obp-message-block').html(data.data.message).slideDown();
                    setTimeout(() => {
                        $('.obp-message-block').html('').slideUp();
                    }, 3000);

                    if (data.data.additional_message === 'yes' && data.data.currency_code !== '') {
                        var currency_code = data.data.currency_code;
                        ajax_dynamic_recommendation(currency_code);
                    }
                    $('#onboard-loader').hide('slow');
                }
            }
        });
    });

    /**
    * Scroll To Top Function
    */
    function goToByScroll(id) {
        var offset = '300';
        $('html,body').animate({
            scrollTop: $('#' + id).offset().top - offset
        }, 'slow');
    }

    $(document).on("click", ".wte-onboard-default-payment", function () {
        if ($(this.dataset.target)) {
            $(this.dataset.target).slideToggle();
        }
        $(this).toggleClass('active');
    });

    /**
    * Select 2 for all select
    */
    $('.onboard-select2-select').select2({
        allowClear: true,
        closeOnSelect: false
    });

    /**
    * Dynamic function for recommendation call on ajax
    */
    currency_code = $('.onboard-select2-select-currency option:selected').val();
    ajax_dynamic_recommendation(currency_code);

    /**
    * Twitter Share
    */
    $.getScript("https://platform.twitter.com/widgets.js", function () {
        twttr.ready(function () {
            function handleTweetEvent(event) {
                if (event) {
                }
            }
            twttr.events.bind('tweet', handleTweetEvent);
            twttr.events.bind(
                'click',
                function (ev) {
                }
            );
        })
    });
}); //document close
function ajax_dynamic_recommendation(currency_code) {
    jQuery(document).ready(function ($) {
        $.ajax({
            url: WPTEOB_OBJ.ajax_url,
            type: "POST",
            data: {
                action: 'wte_onboard_dynamic_recommendation',
                currency_code: currency_code
            },
            beforeSend: function (xhr) {
            },
            success: function (rec_data) {
                $(document).find('#wpte-onboard-recommendations').html(rec_data);
            }
        });
    });
}

$(document).ready(function () {

    //esto permite validar que solo sean escritos en un campo valores numericos
        jQuery('.form-control.input-number').keypress(function (tecla) {
            if (tecla.charCode < 48 || tecla.charCode > 57) return false;
        });
    //
    var navListItems = $('div.setup-panel div button'),
        allWells = $('.setup-content'),
        allNextBtn = $('.nextBtn');

    allWells.hide();

    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
            $item = $(this);

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-primary').addClass('btn-default');
            $item.addClass('btn-primary');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

    allNextBtn.click(function () {
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div button[href="#' + curStepBtn + '"]').parent().next().children("button"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            isValid = true;
        $('body,html').animate({ scrollTop: 400 }, 500);

        $(".form-group").removeClass("has-error");
        for (var i = 0; i < curInputs.length; i++) {
            if (!curInputs[i].validity.valid) {
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }

        if (isValid)
            nextStepWizard.removeAttr('disabled').trigger('click');
    });

    $('div.setup-panel div button.btn-primary').trigger('click');
});
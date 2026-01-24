// Mascaras
window.loadMasks = function loadMasks() {
    let inputPrice = $('.price')
    let inputTime = $('.time')
    let inputPercent = $('.percent')
    let inputNumber = $('.number')
    let inputNumber1 = $('.number-1')
    let inputNumber2 = $('.number-2')
    let inputNumber3 = $('.number-3')
    let inputNumber4 = $('.number-4')
    let inputPhone = $('.phone')
    let inputMonths = $('.months')
    let inputPaymentDay = $('.payment-day')
    let inputDocumentMask = $(".document-mask")
    inputPrice.maskMoney('destroy').empty();
    inputPercent.maskMoney('destroy').empty();
    inputPercent.unmask()
    inputTime.unmask()
    inputNumber.unmask()
    inputNumber1.unmask()
    inputNumber2.unmask()
    inputNumber3.unmask()
    inputNumber4.unmask()
    inputMonths.unmask()
    inputPaymentDay.unmask()
    inputPhone.unmask()
    inputDocumentMask.unmask()
    inputTime.mask('00:00');
    inputPercent.mask('##0,00%', {reverse: true});
    inputNumber.mask('99999');
    inputNumber1.mask('9');
    inputNumber2.mask('99');
    inputNumber3.mask('999');
    inputNumber4.mask('9999');
    inputPhone.mask("(00) 00000-0000");
    inputPrice.maskMoney({
        decimal: ',',
        thousands: '',
        prefix: 'R$ '
    });
    inputDocumentMask.on("keydown", function () {
        if ($(".document-mask").length === 0) return;
        $(this).unmask();

        var tamanho = $(this).val().length;

        if (tamanho < 11) {
            $(this).mask("999.999.999-99");
        } else {
            $(this).mask("99.999.999/9999-99");
        }
    });
    inputPaymentDay.mask('00');
    inputPaymentDay.on('keyup', function () {
        let $input = $(this);
        let val = parseInt($input.val(), 10);

        if (isNaN(val)) {
            $input.val('');
            return;
        }

        // Corrige automaticamente valores fora do range
        if (val < 1) val = 1;
        if (val > 28) val = 28;

        // Preenche sempre com 2 dígitos (01, 02, 03...)
        $input.val(val);
    });

    inputPaymentDay.on('blur', function () {
        let $input = $(this);
        let val = parseInt($input.val(), 10);

        if (isNaN(val)) {
            $input.val('');
            return;
        }

        // Corrige automaticamente valores fora do range
        if (val >= 1 && val <= 9) {
            val = 0 + val.toString();
        }
        $input.val(val);
    });

    //Months
    inputMonths.mask('00');
    inputMonths.on('keyup', function () {
        let $input = $(this);
        let val = parseInt($input.val(), 10);

        if (isNaN(val)) {
            $input.val('');
            return;
        }

        // Corrige automaticamente valores fora do range
        if (val < 1) val = 1;
        if (val > 12) val = 12;

        // Preenche sempre com 2 dígitos (01, 02, 03...)
        $input.val(val);
    });

    inputMonths.on('blur', function () {
        let $input = $(this);
        let val = parseInt($input.val(), 10);

        if (isNaN(val)) {
            $input.val('');
            return;
        }

        // Corrige automaticamente valores fora do range
        if (val >= 1 && val <= 9) {
            val = 0 + val.toString();
        }
        $input.val(val);
    });

}

loadMasks()
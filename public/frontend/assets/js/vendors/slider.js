// Requires jQuery

// Initialize slider:
$(document).ready(function () {
    $(".noUi-handle").on("click", function () {
        $(this).width(50);
    });
    var rangeSlider = document.getElementById("slider-range");
    var rangeSlider2 = $("#slider-range");
    if (rangeSlider2.length > 0) {
        var moneyFormat = wNumb({
            decimals: 0,
            thousand: ".",
            prefix: "Rp"
        });
        noUiSlider.create(rangeSlider, {
            start: [1000, 100000],
            step: 1,
            range: {
                min: [1000],
                max: [100000]
            },
            format: moneyFormat,
            connect: true
        });

        // Set visual min and max values and also update value hidden form inputs
        rangeSlider.noUiSlider.on("update", function (values, handle) {
            $(".min-value-money").html(values[0]);
            $(".max-value-money").html(values[1]);
            $(".min-value").val(moneyFormat.from(values[0]));
            $(".max-value").val(moneyFormat.from(values[1]));
            const min = document.querySelector('.min-value')
            const max = document.querySelector('.max-value')
            min.value = moneyFormat.from(values[0])
            max.value = moneyFormat.from(values[1])

            console.log(max.value)
            
            min.dispatchEvent(new Event('input'))
            max.dispatchEvent(new Event('input'))
        });
    }
});

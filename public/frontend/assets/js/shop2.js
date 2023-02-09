function decrQty(e) {
    var parent = e.parentElement;
    var input = parent.querySelector("input");
    var val = parseInt(input.value);
    if (val > 1) {
        val = val - 1;
    }
    
    const inputDataset = input.dataset;
    const inputID = inputDataset.id;
    const inputX = inputDataset.x;
    const xTarget = e.dataset.target;

    input.value = val;

    const qtyForms = document.querySelectorAll(".the-form-of-qty");
    const len = qtyForms.length;
    for (let i = 0; i < len; i++) {
        const inp = qtyForms[i].querySelector("[data-id]");
        const inpID = inp.dataset.id;
        if (inpID != inputID) continue;

        let xval = inp.dataset.x;
        if (xval == xTarget) {
            inp.value = input.value;
            inp.dispatchEvent(new Event("input"));
        }
    }

    if (xTarget == inputX) input.dispatchEvent(new Event("input"));
}

function incrQty(e) {
    var parent = e.parentElement;
    var input = parent.querySelector("input");
    var val = parseInt(input.value);
    if (val == 0) {
        val = 1;
    }
    
    if (val >= 1) {
        val++
    }

    const inputDataset = input.dataset;
    const inputID = inputDataset.id;
    const inputX = inputDataset.x;
    const xTarget = e.dataset.target;

    input.value = val;

    const qtyForms = document.querySelectorAll(".the-form-of-qty");
    const len = qtyForms.length;
    for (let i = 0; i < len; i++) {
        const inp = qtyForms[i].querySelector("[data-id]");
        const inpID = inp.dataset.id;
        if (inpID != inputID) continue;

        let xval = inp.dataset.x;
        if (xval == xTarget) {
            inp.value = input.value;
            inp.dispatchEvent(new Event("input"));
        }
    }

    if (xTarget == inputX) input.dispatchEvent(new Event("input"));
}

function filterSlide(e) {
    alert('ok')
}
function isImmediatelyPurchaseChecked(checked){
    if(checked.checked){
        document.getElementById('period').disabled = true;
        document.getElementById('price').disabled = true;
    } else {
        document.getElementById('period').disabled = false;
        document.getElementById('price').disabled = false;
    }
}

function isImmediatelyPurchaseCheckedVal(val){
    const checked = document.getElementById('immediate_check');
    checked.checked = true;
    isImmediatelyPurchaseChecked(checked);
}

function priceChanged(min_price){
    const price = document.getElementById('price');
    const period = document.getElementById('period');
    if(parseInt(price.value) > parseInt(min_price)){
        price.value = min_price;
    }
    if(parseInt(price.value) == parseInt(min_price)){
        price.value = "";
        period.value = "";
        isImmediatelyPurchaseCheckedVal()
    }
}
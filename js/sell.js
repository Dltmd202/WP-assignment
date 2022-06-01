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

function priceChanged(max_price){
    const price = document.getElementById('price');
    const period = document.getElementById('period');
    if(parseInt(price.value) < parseInt(max_price)){
        price.value = max_price;
    }
    if(parseInt(price.value) == parseInt(max_price)){
        price.value = "";
        period.value = "";
        isImmediatelyPurchaseCheckedVal()
    }
}

function sellCheck(param){
    var form = document.sell;

    if(document.getElementById('immediate_check').checked){
        form.action = "./sell_action.php" + param;
        form.submit();
        return;
    }
    if(!form.price.value){
        alert("가격을 입력해 주세요");
        form.price.focus();
        return;
    }
    if(!form.period.value){
        alert("입찰기한을 입력해 주세요");
        form.password.focus();
        return;
    }
    form.action = "./sell_action.php" + param;
    form.submit();
}

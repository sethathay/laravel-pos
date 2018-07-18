$(function() {
    //Your custom javascript/jquery goes here
    function calculateTotalPrice(){
        var qtyVal = $("#qty").val();
        var qtyPrice = $("#price_riel").val();
        //Remove comma from value in price
        qtyPrice = qtyPrice.replace(/,/g, "");
        var result = parseFloat(qtyVal) * parseFloat(qtyPrice);
        if($.isNumeric(result)) {
            //Formate number by adding comma			
            result = result.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");				
            $("#total_riel").val(result);
        }
    }
    //Event on change of QTY
    $("#qty").change(function(){
        calculateTotalPrice();
    });
    //Event on change of Price in riel
    $("#price_riel").change(function(){
        calculateTotalPrice();
    });
});
<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 7/31/2022
 * Time: 9:06 AM
 */ ?>

{{--
<script>
    function calculate() {
        var amount = document.getElementById('amount').value;
        var interest_rate = {!! getUsdInterestRate() !!};
        var paybackPeriod = document.getElementById('tenure').value;
        var gross_amount = amount/0.95;

        var payment = -1*pmt(interest_rate/100,paybackPeriod,amount,0,0);

        var insurance = 0.01*amount;
        var ags_commission = 0.02*amount;
        var app_fee = 0.01*amount;
        var est_fee = 0.01*amount;
        var net_after_charge = amount - est_fee -ags_commission - app_fee - insurance;

        localStorage.setItem("MonthlyRepayment", parseFloat(payment).toFixed(2));
        localStorage.setItem("Interest", interest_rate);
        localStorage.setItem("netAfterCharge", net_after_charge);
        localStorage.setItem("gross_amount", gross_amount);
        localStorage.setItem("insurance", insurance);
        localStorage.setItem("est_fee", est_fee);
        localStorage.setItem("ags_commission", ags_commission);
        localStorage.setItem("app_fee", app_fee);

        display(payment,interest_rate,net_after_charge,gross_amount,insurance,est_fee,ags_commission,app_fee);
    }
    function pmt(rate_per_period, number_of_payments, present_value, future_value, type){
        future_value = typeof future_value !== 'undefined' ? future_value : 0;
        type = typeof type !== 'undefined' ? type : 0;

        if(rate_per_period != 0.0){
            // Interest rate exists
            var q = Math.pow(1 + rate_per_period, number_of_payments);
            return -(rate_per_period * (future_value + (q * present_value))) / ((-1 + q) * (1 + rate_per_period * (type)));

        } else if(number_of_payments != 0.0){
            // No interest rate, but number of payments exists
            return -(future_value + present_value) / number_of_payments;
        }

        return 0;
    }
    function display(payment,interest_rate,net_after_charge,gross_amount,insurance,est_fee,ags_commission,app_fee){

        document.getElementById("monthly").value=parseFloat(payment).toFixed(2);
        document.getElementById("interestRate").value=interest_rate;
        document.getElementById("net_after_charge").value=parseFloat(net_after_charge).toFixed(2);
        document.getElementById("gross_amount").value=parseFloat(gross_amount).toFixed(2);
        document.getElementById("insurance").value=parseFloat(insurance).toFixed(2);
        document.getElementById("est_fee").value=parseFloat(est_fee).toFixed(2);
        document.getElementById("ags_commission").value=parseFloat(ags_commission).toFixed(2);
        document.getElementById("app_fee").value=parseFloat(app_fee).toFixed(2);
    }

    function checkCreditLimit(){
        var amount = document.getElementById("amount").value;
        if (parseFloat(amount)> parseFloat(localStorage.getItem('creditLimit'))) {
            document.getElementById("btn").disabled = true;
            document.getElementById('amount').style.borderColor = "red";
        }
        else{
            document.getElementById("btn").disabled = false;
            document.getElementById('amount').style.borderColor = "green";
        }
    }

</script>
--}}


<script>
    function calculate() {
        var amount = document.getElementById('amount').value;
        var interest_rate = {!! getUsdInterestRate() !!};
        var paybackPeriod = document.getElementById('tenure').value;

        var payment = -1*pmt(interest_rate/100,paybackPeriod,amount,0,0);

        var insurance = 0.01*amount;
        var ags_commission = 0.02*amount;
        var app_fee = 0.01*amount;
        var est_fee = 0.01*amount;
        var net_after_charge = amount - est_fee -ags_commission - app_fee - insurance;

        localStorage.setItem("MonthlyRepayment", parseFloat(payment).toFixed(2));
        localStorage.setItem("Interest", interest_rate);
        localStorage.setItem("netAfterCharge", net_after_charge);
        localStorage.setItem("insurance", insurance);
        localStorage.setItem("est_fee", est_fee);
        localStorage.setItem("ags_commission", ags_commission);
        localStorage.setItem("app_fee", app_fee);

        display(payment,interest_rate,net_after_charge,insurance,est_fee,ags_commission,app_fee);
    }
    function pmt(rate_per_period, number_of_payments, present_value, future_value, type){
        future_value = typeof future_value !== 'undefined' ? future_value : 0;
        type = typeof type !== 'undefined' ? type : 0;

        if(rate_per_period != 0.0){
            // Interest rate exists
            var q = Math.pow(1 + rate_per_period, number_of_payments);
            return -(rate_per_period * (future_value + (q * present_value))) / ((-1 + q) * (1 + rate_per_period * (type)));

        } else if(number_of_payments != 0.0){
            // No interest rate, but number of payments exists
            return -(future_value + present_value) / number_of_payments;
        }

        return 0;
    }
    function display(payment,interest_rate,net_after_charge,insurance,est_fee,ags_commission,app_fee){

        document.getElementById("monthly").value=parseFloat(payment).toFixed(2);
        document.getElementById("interestRate").value=interest_rate;
        document.getElementById("net_after_charge").value=parseFloat(net_after_charge).toFixed(2);
        document.getElementById("insurance").value=parseFloat(insurance).toFixed(2);
        document.getElementById("est_fee").value=parseFloat(est_fee).toFixed(2);
        document.getElementById("ags_commission").value=parseFloat(ags_commission).toFixed(2);
        document.getElementById("app_fee").value=parseFloat(app_fee).toFixed(2);
    }

    function checkCreditLimit(){
        var amount = document.getElementById("amount").value;
        if (parseFloat(amount)> parseFloat(localStorage.getItem('creditLimit'))) {
            document.getElementById("btn").disabled = true;
            document.getElementById('amount').style.borderColor = "red";
        }
        else{
            document.getElementById("btn").disabled = false;
            document.getElementById('amount').style.borderColor = "green";
        }
    }

</script>

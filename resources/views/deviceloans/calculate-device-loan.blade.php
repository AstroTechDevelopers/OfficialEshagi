<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 4/30/2022
 * Time: 10:25 AM
 */ ?>
<script>
    function calculate() {
        var bankCharge = 1;
        var amount = document.getElementById('amount').value;
        var deposit = document.getElementById('deposit_prct').value;
        var loanAmount = amount - ((deposit/100) * amount) ;
        console.log(loanAmount);
        var interest_rate = {!! getDeviceInterestRate() !!};

        if (bankCharge=="") {
            alert("Please select Microfinance");
            return;
        }
        var paybackPeriod = document.getElementById('paybackPeriod').value;
        var interest = (loanAmount * (interest_rate * .01))/paybackPeriod;
        var payment =  -1*pmt(interest_rate/100,paybackPeriod,loanAmount,0,0);
        //payment = payment.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        var total_charges = 0.16*loanAmount;
        var tax = 0.02*loanAmount;
        var insurance = 0.025*loanAmount;
        var arrangement = 0.05*loanAmount;
        var amountDibursed = 0.84*loanAmount;
        var applifee = 0.065*loanAmount;
        localStorage.setItem("MonthlyRepayment", parseFloat(payment).toFixed(2));
        localStorage.setItem("Interest", interest_rate);
        localStorage.setItem("Months", paybackPeriod);
        localStorage.setItem("amountDibursed", amountDibursed);
        localStorage.setItem("total_charges", parseFloat(total_charges).toFixed(2));
        localStorage.setItem("amount", loanAmount);
        localStorage.setItem("tax", tax);
        localStorage.setItem("insurance", insurance);
        localStorage.setItem("arrangement", arrangement);
        localStorage.setItem("appFee", applifee);

        display(amountDibursed,loanAmount,interest_rate,paybackPeriod,payment,total_charges,applifee);

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
    function display(amountDibursed,loanAmount,interest_rate,paybackPeriod,payment,total_charges,applifee){

        document.getElementById("monthly").value=parseFloat(payment).toFixed(2);
        document.getElementById("interestRate").value=interest_rate;
        document.getElementById("tenure").value=paybackPeriod;
        document.getElementById("disbursed").value=parseFloat(amountDibursed).toFixed(2);
        document.getElementById("charges").value=total_charges;
        document.getElementById("appFee").value=parseFloat(applifee).toFixed(2);
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

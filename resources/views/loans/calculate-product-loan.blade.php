<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 11/22/2021
 * Time: 9:45 PM
 */ ?>

<script>
    function calculate() {
		var bankCharge = 1;
        var amount = document.getElementById('amount').value;
        var interest_rate = {!! getInterestRate() !!};
		var management_rate = {!! getManagementRate() !!}
		var management_fee = amount * management_rate;
		var arrangement = 0.10*amount;
		var total_charges = arrangement;
		
        if (bankCharge=="") {
            alert("Please select Microfinance");
            return;
        }
        var paybackPeriod = document.getElementById('paybackPeriod').value;
        var interest = (amount * (interest_rate * .01))/paybackPeriod;
		var amountDibursed = parseInt(amount) + parseInt(arrangement);
        var payment =  -1*pmt((interest_rate/12)/100,paybackPeriod,amount,0,0) + management_fee;
		display(amountDibursed,interest_rate,management_rate,paybackPeriod,payment,arrangement,management_fee);
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

	function display(amountDibursed,interest_rate,management_rate,paybackPeriod,payment,arrangement,management_fee){	

        document.getElementById("monthly").value=parseFloat(payment).toFixed(2);
        document.getElementById("interestRate").value=interest_rate;
		//document.getElementById("managementRate").value=management_rate;
        document.getElementById("tenure").value=paybackPeriod;
        document.getElementById("disbursed").value=parseFloat(amountDibursed).toFixed(2);        
        document.getElementById("charges").value=arrangement;
		//document.getElementById("managementFee").value=management_fee;
    }

    function checkCreditLimit(){
        var amount = document.getElementById("amount").value;

		if (parseFloat(amount)> parseFloat({!! $user->cred_limit !!})) {
            document.getElementById("btn").disabled = true;
            document.getElementById('amount').style.borderColor = "red";
        }
        else{
            document.getElementById("btn").disabled = false;
            document.getElementById('amount').style.borderColor = "green";
        }
    }

</script>

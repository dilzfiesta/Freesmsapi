function encodeTxnRequest() {
	
	if($('#paymentemail').val() == '') {
		alert('Email address is required');
		$('#paymentemail').focus();
		return false;
	}
	
	$.post('/users/getPaymentDetails', {'paymentemail':$('#paymentemail').val()}, function(data) {
		if(data == '0') {
			alert('Server Unavailable, Please try again after some time');
		} else if(data == '1') {
			alert('Email address is required');
			$('#paymentemail').focus();
		} else {
			$('#requestparameter').val(data);
			document.ecom.submit();
		}
	});

}

function paymentType(self) {
	
	if(self.value == 0) {
		$("#paynow").hide();
		$("#directdeposit").hide();
		$("#demanddraft").hide();
	} else if(self.value == 1) {
		$("#paynow").show();
		$("#directdeposit").hide();
		$("#demanddraft").hide();
	} else if(self.value == 2) {
		$("#paynow").hide();
		$("#directdeposit").show();
		$("#demanddraft").hide();
	} else if(self.value == 3) {
		$("#paynow").hide();
		$("#directdeposit").hide();
		$("#demanddraft").show();
	}
	
}
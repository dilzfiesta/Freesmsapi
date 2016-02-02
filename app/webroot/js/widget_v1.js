var FREESMSAPI_RESPONSE_SEPERATOR = "::";
function freesmsapi_widget_send() {
	if(document.getElementById('freesmsapi_widget_verify').style.display == 'none') {
		var error = "";
		var from = trim(document.getElementById("freesmsapi_widget_d_fm").value);
		var to = trim(document.getElementById("freesmsapi_widget_d_in").value);
		var message = trim(document.getElementById("freesmsapi_widget_d_t").value);
		var token = trim(document.getElementById("csrftoken").value);
		
		if(from == "") error += "Your Mobile Number is required\r\n";
		if(to == "") error += "Recipient Mobile Number is required\r\n";
		if(message == "") error += "Message is required";
		
		if(error != "") {
			alert(error);
			return false;
		} else {
			document.getElementById('freesmsapi_widget_d_is').value = 'Sending';
			document.getElementById('freesmsapi_widget_d_is').disabled = true;
			var url_action = document.getElementById("freesmsapi_widget_fmain").action;
			var params = "from="+from+"&to="+to+"&message="+escape(message)+"&token="+token;
			var url = url_action+"?"+params;
			freesmsapi_widget_call(url);
		}
	} else {
		var error = "";
		if(trim(document.getElementById("freesmsapi_widget_d_vr").value) == "") error += "Verification code is required\r\n";
		if(error != "") {
			alert(error);
			return false;
		} else {
			document.getElementById('freesmsapi_widget_d_isv').value = 'Sending';
			document.getElementById('freesmsapi_widget_d_isv').disabled = true;
			var token = trim(document.getElementById("csrftoken").value);
			var vcode = trim(document.getElementById("freesmsapi_widget_d_vr").value);
			var url_action = document.getElementById("freesmsapi_widget_fmain").action;
			var params = "vcode="+vcode+"&token="+token;
			var url = url_action+"?"+params;
			freesmsapi_widget_call(url);
		}
	}
}
function freesmsapi_widget_parseresponse(response) {
	if(response.match(FREESMSAPI_RESPONSE_SEPERATOR)) {
		document.getElementById('freesmsapi_widget_d_is').value = 'Send SMS';
		document.getElementById('freesmsapi_widget_d_is').disabled = false;
		document.getElementById('freesmsapi_widget_d_isv').value = 'Send SMS';
		document.getElementById('freesmsapi_widget_d_isv').disabled = false;
		var data = response.split(FREESMSAPI_RESPONSE_SEPERATOR);
		data[0] = trim(data[0]);
		data[1] = trim(data[1]);
		if(data[0] == "0") { // on error
			alert(data[1]);
		} else if(data[0] == "1") { // on verification code send
			document.getElementById("freesmsapi_widget_main").style.display = "none";
			document.getElementById("freesmsapi_widget_verify").style.display = "";
			alert(data[1]);
		} else if(data[0] == "2") { // on message send
			freesmsapi_widget_clear();
			document.getElementById("freesmsapi_widget_main").style.display = "";
			document.getElementById("freesmsapi_widget_verify").style.display = "none";
			alert(data[1]);
		}
	} else {
		alert("Invalid Response");
	}
}
function freesmsapi_widget_clear() {
	document.getElementById('freesmsapi_widet_tc').innerHTML = 140;
	document.getElementById('freesmsapi_widget_fmain').reset();
	document.getElementById('freesmsapi_widget_d_vr').value = "";
}
function freesmsapi_widget_numKey(eventObj) {
    var keycode;
    if(eventObj.keyCode) keycode = eventObj.keyCode;	// For IE
    else if(eventObj.Which) keycode = eventObj.Which;  // For FireFox
    else keycode = eventObj.charCode; // Other Browser
    if (keycode!=8 && keycode!=9) {  //if the key is the backspace key or tab key  && keycode!=44
		if (keycode<48||keycode>57) return false; // disable key press
		else return true; // enable key press
    }        
}
function freesmsapi_widget_textCounter(field) {
	var maxlimit = 140;
	var counter = document.getElementById('freesmsapi_widet_tc');
	if (field.value.length > maxlimit) field.value = field.value.substring(0, maxlimit);
	else counter.innerHTML = Number(maxlimit - field.value.length);
}
function freesmsapi_widget_back() {
	freesmsapi_widget_clear();
	document.getElementById('freesmsapi_widget_main').style.display = '';
	document.getElementById('freesmsapi_widget_verify').style.display = 'none';
	
}
function freesmsapi_widget_call(url) {
	YUI().use("io-base", function(Y) {
	    var uri = url;
	    function complete(id, o, args) {
	        var id = id; // Transaction ID.
	        var data = o.responseText; // Response data.
	        freesmsapi_widget_parseresponse(data);
	    };
	    Y.on('io:complete', complete, Y);
	    var request = Y.io(uri);
	});
}
function trim(stringToTrim) {
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}
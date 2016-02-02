function freesmsapi_widget_send() {
	var error = "";
	if(document.getElementById("freesmsapi_widget_d_in").value.trim() == "") error += "Mobile Number required\r\n";
	if(document.getElementById("freesmsapi_widget_d_t").value.trim() == "") error += "Message required";
	if(error.trim() != "") {
		alert(error);
		return false;
	} else {
		document.getElementById("freesmsapi_widget_f").submit();
	}
}
function freesmsapi_widget_clear() {
	document.getElementById('freesmsapi_widget_f').reset();
}
function freesmsapi_widget_numKey(eventObj) {
    var keycode;
    if(eventObj.keyCode) keycode = eventObj.keyCode;	// For IE
    else if(eventObj.Which) keycode = eventObj.Which;  // For FireFox
    else keycode = eventObj.charCode; // Other Browser
    if (keycode!=8 && keycode!=9 && keycode!=44) {  //if the key is the backspace key or tab key
		if (keycode<48||keycode>57) return false; // disable key press
		else return true; // enable key press
    }        
}
function freesmsapi_widget_textCounter(field) {
	var maxlimit = 160;
	if (field.value.length > maxlimit) field.value = field.value.substring(0, maxlimit);
}
String.prototpe.trim = function () {
    return this.replace(/^\s*/, "").replace(/\s*$/, "");
};
function micox(form, url_action) {
	var iframe = document.createElement("iframe");  //creating the iframe
	iframe.setAttribute("id","micox-temp");
	iframe.setAttribute("name","micox-temp");
	iframe.setAttribute("width","0");
	iframe.setAttribute("height","0");
	iframe.setAttribute("border","0");
	iframe.setAttribute("style","width: 0; height: 0; border: none;");
	form.parentNode.parentNode.appendChild(iframe);  //add to document
	window.frames["micox-temp"].name="micox-temp"; //ie sucks
	form.setAttribute("target","micox-temp");  //properties of form
	form.setAttribute("action",url_action);
	form.setAttribute("method","post");
	form.submit();
}
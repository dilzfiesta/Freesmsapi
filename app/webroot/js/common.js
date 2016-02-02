String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}

/*	BULK SMS EDIT	*/
function B_S_E(id) {
	$('#tr_e_'+id).show();
	$('#tr_'+id).hide();
}

/*	BULK SMS EDIT CANCEL	*/
function B_S_E_C(id) {
	$('#tr_e_'+id).hide();
	$('#tr_'+id).show();
}

/*	BULK SMS EDIT SAVE	*/
function B_S_E_S(id, self) {
	
	var fn = $('#fn_'+id).val();
	var ln = $('#ln_'+id).val();
	var m = $('#m_'+id).val();
	self.disabled = true;
	
	$.post("/bulksms/addressbook/edit", 
			{ id: id, firstname: fn, lastname: ln, mobile: m }, 
			function(s) {
				if(s) {
					alert(s);
					self.disabled = false;
				} else {
					$('#s_fn_'+id).html(fn);
					$('#s_ln_'+id).html(ln);
					$('#s_m_'+id).html(m);
					self.disabled = false;
					B_S_E_C(id);
				}
			}, "json"
	);
	
}

/*	BULK SMS CHANGE GROUP	*/
function B_S_S_G(group_id) {
	
	/*if(group_id == '0') return false;
	else*/ window.location.replace('/bulksms/addressbook/view/'+$('#bulk_group_id').val());
	
}

/*	BULK SMS MOVE GROUP	*/
function B_S_M_G(group_id) {
	
	if(group_id == '0') return false;
	
	var c_s = new Array();
	$('input:checkbox').each(function(index, elem) {
		if(elem.checked) c_s.push(elem.value); 
	});
	
	if(c_s.length > 0) {
		
		if(!confirm('Are you sure?')) return false;
		
		c_s = array1dToJson(c_s);
		$.post('/bulksms/addressbook/changegroup', { id: c_s, group_id: group_id },
				function(s) {
					alert(s);
					window.location.reload(true);
					//for(var i in c_s) { $('#tr_'+c_s[i]).hide(); }
					//$('#s_group_id').val(0);
				}
		);
	} else {
		alert('No Contact(s) Selected');
		$('#s_group_id').val(0);
	}
	
}

/*	BULK SMS COPY GROUP	*/
function B_S_C_G(group_id) {
	
	if(group_id == '0') return false;
	
	var c_s = new Array();
	$('input:checkbox').each(function(index, elem) {
		if(elem.checked) c_s.push(elem.value); 
	});
	
	if(c_s.length > 0) {
		
		if(!confirm('Are you sure?')) return false;
		
		c_s = array1dToJson(c_s);
		$.post('/bulksms/addressbook/copygroup', { id: c_s, group_id: group_id },
				function(s) {
					alert(s);
					window.location.reload(true);
					//for(var i in c_s) { $('#tr_'+c_s[i]).hide(); }
					//$('#s_group_id').val(0);
				}
		);
	} else {
		alert('No Contact(s) Selected');
		$('#c_group_id').val(0);
	}
	
}

/*	BULK SMS SHOW REPORT DETAILED	*/
function B_S_S_D(group_id) {

	window.location.replace('/bulksms/showdetailedreport/'+group_id);
	
}

/*	BULK SMS SELECT CHECKBOX	*/
function B_S_S_CK(self) {
	
	if(self.type == 'checkbox') { //addressbook
		if(self.checked) state = true;
		else state = false;
	}
	$(':checkbox').attr('checked', state);
	
}

/*	BULK SMS ASK TO FETCH CONTACTS	*/
function B_S_A_F_C(self) {

	if(self.value == '0') $('#group_fetch_list').hide();
	else $('#group_fetch_list').show();
	$('#group_mobile_list').hide().html('');
	
}

/*	BULK SMS CLOSE CONTACT LIST	*/
function B_S_C_C_L() {
	
	$('#group_mobile_list').hide().html('');
	$('#bulk_group_id').val('0');
	
}

function B_S_G_C() {
	
	$('#group_fetch_list').hide();
	
	var loader = '<div style="float:left"><img src="/img/loading.gif" width="40px"/></div>'+
					'<div id="s_text" style="margin-top:12px;padding-left:45px;font-size:18px;">Please wait while loading contact(s)</div>';
	
	$('#group_mobile_list').show().html(loader);
	$.post('/bulksms/getcontacts', { group_id: $('#bulk_group_id').val() },
			function(s) {
				var s = eval( '(' + s + ')' );
				var data = '<table class="normal_table"><tr>';
				//data += '<td colspan="2">Select:&nbsp;<a href="javascript:void(0)" onclick="B_S_S_CK(true)">all</a>&nbsp;<a href="javascript:void(0)" onclick="B_S_S_CK(false)">none</a></td>';
				data += '<td colspan="2"><strong>ALL CONTACTS</strong></td>';
				data += '<td align="right"><img src="/img/cancel.png" alt="Close contact list" width="20px" style="cursor:pointer" onclick="B_S_C_C_L()"/></td>';
				data += '</tr>';
				var count = 0;
				for(var i in s) {
					if(count != '0' && count%3 =='0') data += '</tr><tr>';
					data += '<td>';
					data += '<span><input type="checkbox" checked="checked" name="data[mobile_list][]" value="'+i+'" /></span>';
					data += '&nbsp;<span>'+s[i]['name']+'</span>';
					data += '&nbsp;<span><'+s[i]['mobile']+'></span>';
					data += '</td>';
					count++;
				}
				data += '</tr></table>';
				$('#group_mobile_list').html(data);
			}
	);
	
}

/*	BULK SMS DELETE CONTACTS	*/
function B_S_D_CN() {
	
	var c_s = new Array();
	$('input:checkbox').each(function(index, elem) {
		if(elem.checked) c_s.push(elem.value); 
	});
	
	if(c_s.length > 0) {

		if(!confirm('Are you sure?')) return false;

		c_s = array1dToJson(c_s);
		$.post('/bulksms/addressbook/deletecontact', { id: c_s },
				function(s) {
					alert(s);
					window.location.reload(true);
				}
		);
	} else {
		alert('No Contact(s) Selected');
		$('#c_group_id').val(0);
	}
	
}

function array1dToJson(a, p) {
  var i, s = '{';
  for (i = 0; i < a.length; ++i) {
    if (typeof a[i] == 'string') {
      s += '"' + i + '"' + ':"' + a[i] + '"';
    }
    else { // assume number type
      s += '"' + i + '"' + ':' + a[i];
    }
    if (i < a.length - 1) {
      s += ',';
    }
  }
  s += '}';
  if (p) {
    return '{"' + p + '":' + s + '}';
  }
  return s;
}

function numKey(eventObj) {
    var keycode;
    if(eventObj.keyCode) keycode = eventObj.keyCode; //For IE
    else if(eventObj.Which) keycode = eventObj.Which;  // For FireFox
    else keycode = eventObj.charCode; // Other Browser
 
    if (keycode!=8 && keycode!=9) { //if the key is the backspace key or tab key
        if (keycode<48||keycode>57) //if not a number
            return false; // disable key press
        else
            return true; // enable key press
     }        
}
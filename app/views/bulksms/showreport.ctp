<link rel="stylesheet" type="text/css" media="all" href="/css/calendar.css" title="Aqua" />

<!-- import the calendar script -->
<script type="text/javascript" src="/js/calendar.js"></script>

<!-- import the language module -->
<script type="text/javascript" src="/js/calendar-en.js"></script>

<!-- other languages might be available in the lang directory; please check
your distribution archive. -->

<!-- helper script that uses the calendar -->
<script type="text/javascript">

var oldLink = null;
// code to change the active stylesheet
function setActiveStyleSheet(link, title) {
  var i, a, main;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title")) {
      a.disabled = true;
      if(a.getAttribute("title") == title) a.disabled = false;
    }
  }
  if (oldLink) oldLink.style.fontWeight = 'normal';
  oldLink = link;
  link.style.fontWeight = 'bold';
  return false;
}

// This function gets called when the end-user clicks on some date.
function selected(cal, date) {
  cal.sel.value = date; // just update the date in the input field.
  if (cal.dateClicked)
    // if we add this call we close the calendar on single-click.
    // just to exemplify both cases, we are using this only for the 1st
    // and the 3rd field, while 2nd and 4th will still require double-click.
    cal.callCloseHandler();
}

// And this gets called when the end-user clicks on the _selected_ date,
// or clicks on the "Close" button.  It just hides the calendar without
// destroying it.
function closeHandler(cal) {
  cal.hide();                        // hide the calendar
//  cal.destroy();
  _dynarch_popupCalendar = null;
}

// This function shows the calendar under the element having the given id.
// It takes care of catching "mousedown" signals on document and hiding the
// calendar if the click was outside.
function showCalendar(id, format, showsTime, showsOtherMonths) {
  var el = document.getElementById(id);
  if (_dynarch_popupCalendar != null) {
    // we already have some calendar created
    _dynarch_popupCalendar.hide();                 // so we hide it first.
  } else {
    // first-time call, create the calendar.
    var cal = new Calendar(1, null, selected, closeHandler);
    // uncomment the following line to hide the week numbers
    // cal.weekNumbers = false;
    if (typeof showsTime == "string") {
      cal.showsTime = true;
      cal.time24 = (showsTime == "24");
    }
    if (showsOtherMonths) {
      cal.showsOtherMonths = true;
    }
    _dynarch_popupCalendar = cal;                  // remember it in the global var
    cal.setRange(1900, 2070);        // min/max year allowed.
    cal.create();
  }
  _dynarch_popupCalendar.setDateFormat(format);    // set the specified date format
  _dynarch_popupCalendar.parseDate(el.value);      // try to parse the text in field
  _dynarch_popupCalendar.sel = el;                 // inform it what input field we use

  // the reference element that we pass to showAtElement is the button that
  // triggers the calendar.  In this example we align the calendar bottom-right
  // to the button.
  
  _dynarch_popupCalendar.showAtElement(el.nextSibling, "Br");        // show the calendar

  // fix width
 // var s = document.getElementsbyClassName('calendar');alert(s.length);
 // for(var i in s) s[i].style.width="200px";

  return false;
}

var MINUTE = 60 * 1000;
var HOUR = 60 * MINUTE;
var DAY = 24 * HOUR;
var WEEK = 7 * DAY;

// If this handler returns true then the "date" given as
// parameter will be disabled.  In this example we enable
// only days within a range of 10 days from the current
// date.
// You can use the functions date.getFullYear() -- returns the year
// as 4 digit number, date.getMonth() -- returns the month as 0..11,
// and date.getDate() -- returns the date of the month as 1..31, to
// make heavy calculations here.  However, beware that this function
// should be very fast, as it is called for each day in a month when
// the calendar is (re)constructed.
function isDisabled(date) {
  var today = new Date();
  return (Math.abs(date.getTime() - today.getTime()) / DAY) > 10;
}

function flatSelected(cal, date) {
  var el = document.getElementById("preview");
  el.innerHTML = date;
}

function showFlatCalendar() {
  var parent = document.getElementById("display");

  // construct a calendar giving only the "selected" handler.
  var cal = new Calendar(0, null, flatSelected);

  // hide week numbers
  cal.weekNumbers = false;

  // We want some dates to be disabled; see function isDisabled above
  cal.setDisabledHandler(isDisabled);
  cal.setDateFormat("%A, %B %e");

  // this call must be the last as it might use data initialized above; if
  // we specify a parent, as opposite to the "showCalendar" function above,
  // then we create a flat calendar -- not popup.  Hidden, though, but...
  cal.create(parent);

  // ... we can show it here.
  cal.show();
}

function checkIt(evt) {
    evt = (evt) ? evt : window.event
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
    else return true;
}
</script>


<div class="gradient" align="center"><h1><span></span>SMS Delivery Reports</h1></div>
    
<?php echo $this->renderElement('success_error', array('error'=>isset($sc_error)?$sc_error:'', 'success'=>isset($sc_success)?$sc_success:''));  ?>

<div class="pad5">

	<div class="header_hw"><div class="header_wrapper header_03">SMS Search</div></div>
	<!--<div class="header_05" style="background-color:#D3D3D3;padding:5px;">SMS Search</div>-->

	<div align="center">
    	<form method='post'>
	        <div class="pad5 f14">
	        	<div style="padding-top:10px">
	        	<table class="normal_table">
	        		<tr>
		        		<td class="f12">From : </td>
		        		<td><input type="text" name="data[date1]" class="inputbox" id="sel1" size="20" value="<?=(isset($date1)?$date1:'')?>"><input class="reset" type="reset" value=" ... " onclick="return showCalendar('sel1', '%d-%m-%Y', null, true);">&nbsp;<span class="f12"><em>dd-mm-yyyy</em></span></td>
	        		</tr>
		        	<tr>
		        		<td class="f12">To : </td>
		        		<td><input type="text" name="data[date2]" class="inputbox" id="sel2" size="20" value="<?=(isset($date2)?$date2:'')?>"><input class="reset" type="reset" value=" ... " onclick="return showCalendar('sel2', '%d-%m-%Y', null, true);">&nbsp;<span class="f12"><em>dd-mm-yyyy</em></span></td>
		        	</tr>
		        	<tr>
		        		<td class="f12">Groups : </td>
		        		<td>
		        			<span><select name="data[group_id]" class="dropdown"><?=$groupname?></select></span>
		        		</td>
		        	</tr>
		        	<tr>
		        		<td class="f12">Tags : </td>
		        		<td>
		        			<span><select name="data[tag_id]" class="dropdown"><?=$tagname?></select></span>
		        		</td>
		        	</tr>
		        </table>
		        </div>
		        <div style="padding-top:10px; margin-left:-60px"><input type='submit' value='find' class="rc_btn_01"/></div>
	        </div>
	    </form>
	</div>

	<div class="header_07" style="margin-bottom:-35px;padding-top:20px">NOTE: Click on the message to review the delivery details.</div>
	<table cellpadding="4" id="hor-zebra" style="width:100%">
		<thead>
			<tr>
    			<th scope="col"></th>
    			<th scope="col">Message</th>
    			<th scope="col">Group</th>
    			<!--<th scope="col">SenderID</th>
    			<th scope="col">Tag</th>
    			<th scope="col">Mobile</th>-->
    			<th scope="col">SMS Count</th>
    			<th scope="col">Send on</th>
			</tr>
		</thead>
		<tbody>
		
		<?php if(!empty($data)) { ?>
		
		<?php for($i=0; $i<count($data); $i++) { ?>
		
			<tr valign="top" <?=(($i%2)==0)?'class="odd"':''?> onclick="window.open('/bulksms/showdetailedreport/<?=$data[$i]['BulkSmsLog']['id']?>');" style="cursor:pointer">
				<td><?=$i+1?></td>
				<td><?=wordwrap($data[$i]['BulkSmsLog']['message'], 30, "\n", true)?></td>
				<td><?=isset($groups[$data[$i]['BulkSmsLog']['bulk_group_id']]) ? $groups[$data[$i]['BulkSmsLog']['bulk_group_id']] : '-'?></td>
				<!--<td>< ?=isset($senderid[$data[$i]['BulkSmsLog']['bulk_senderid_id']]) ? $senderid[$data[$i]['BulkSmsLog']['bulk_senderid_id']] : SMS_SENDER_ID?></td>
				<td>< ?=$tag_list[$data[$i]['BulkSmsLog']['bulk_tag_id']]?></td>
				<td width="20px">< ?=!empty($data[$i]['BulkSmsLog']['numbers']) ? wordwrap($data[$i]['BulkSmsLog']['numbers'], 11, "\n", true) : '-'?></td>-->
				<td width="70px"><?=$data[$i]['BulkSmsLog']['sms_count']?></td>
				<td width="120"><?=date('j M Y g:i a', strtotime($data[$i]['BulkSmsLog']['created']))?></td>
			</tr>
			
		<?php } ?>
		
		<?php } else { ?>

    		<tr valign="top">
				<td colspan="6" align="center">NO ENTRIES FOUND</td>
			</tr>
    	
    	<?php } ?>
			
		</tbody>
	</table>
	
</div>
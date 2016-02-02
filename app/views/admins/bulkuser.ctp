<html>

<?php echo $this->renderElement('title'); ?>

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

<body>

<?php echo $this->renderElement('adminheader'); ?>

<div id="templatemo_content_wrapper">

    <div id="templatemo_content">

		<div class="header_02">Bulk User</div>
        <div>    
        	<table style="width:100%;text-align:left">
        
        		<thead>
					<th><strong>ID</strong></th>
					<th><strong>FIRSTNAME</strong></th>
					<th><strong>LASTNAME</strong></th>
					<th><strong>EMAIL</strong></th>
					<th><strong>MOBILE</strong></th>
					<th><strong>CREATED</strong></th>
					<th><strong>LAST LOGIN</strong></th>
					<th><strong>DETAILS</strong></th>
				</thead>
        
			<?php $count=1; foreach($bulk_user as $key => $value) { ?>
				
				<tr>
					<td><?=$count++?></td>
					<td><?=$value['BulkUserpersonalinfo']['firstname']?></td>
					<td><?=$value['BulkUserpersonalinfo']['lastname']?></td>
					<td><?=$value['BulkUserpersonalinfo']['email']?></td>
					<td><?=$value['BulkUserpersonalinfo']['mobile']?></td>
					<td><?=date('j M Y g:i a', strtotime($value['BulkUser']['created']))?></td>
					<td><?=date('j M Y g:i a', strtotime($value['BulkUser']['updated']))?></td>
					<td><a href="/admins/bulksmsdetails/<?=$value['BulkUser']['id']?>">view</a></td>
				</tr>
				
			<?php } ?>
			
			</table>
			    
        </div> 

		<div><br/><br/></div>
        
        <div class="header_02">Add New</div>
        <div>
			<form method="post" action="/admins/buybulksms" onsubmit="return confirm('Are you sure?')">
				<div class="pad5">
					<span><input type="text" class="inputbox" name="data[quantity]" /></span>
					<span>Quantity</span>
				</div>
				<div class="pad5">
					<span><input type="text" class="inputbox" name="data[costpersms]" /></span>
					<span>Cost Per SMS</span>
				</div>
				<div class="pad5">
					<span><input type="text" class="inputbox" name="data[amount]" /></span>
					<span>Amount</span>
				</div>
				<div class="pad5">
					<span><input type="text" id="sel2" class="inputbox" name="data[validtill]" /><input type="reset" value=" ... " onclick="return showCalendar('sel2', '%d-%m-%Y', null, true);">&nbsp;<em>dd-mm-yyyy</em></span>
					<span>Valid Till</span>
				</div>
				<div class="pad5">
					<span><input type="text" class="inputbox" name="data[email]" /></span>
					<span>Email</span>
				</div>
				<div class="pad5">
					<select class="dropdown" name="data[sms_vendor_id]">
					<? foreach($sms_vendor as $val) { ?>
						<option value="<?=trim($val['SmsVendor']['id'])?>"><?=trim($val['SmsVendor']['name'])?></option>
					<? } ?>
					</select>
					<span>SMS Provider</span>
				</div>
				<div class="pad5" style="margin-left:-5px">
					<span><input type="submit" class="rc_btn_01" value="create" /></span>
				</div>
			</form>
        </div>
    
    </div>  <!-- end of content -->

</div> <!-- end of content wrapper -->

<div><br/><br/></div>

<?php echo $this->renderElement('footer'); ?>

</div>
</body>
</html>
<?php exit; ?>
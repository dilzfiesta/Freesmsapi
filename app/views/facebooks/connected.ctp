<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<body>

  <div id="user">
	<span>
		<fb:profile-pic uid=loggedinuser facebook-logo=true></fb:profile-pic>
		Welcome, <fb:name uid=loggedinuser useyou=false></fb:name>. You are signed in with your Facebook account.
	</span>
	<span><a href='#' onclick='logout()'>logout</a></span>
  </div>

<input type="hidden" name="facebook-request" value="true" />
<script type="text/javascript">
function logout() {
	FB.Connect.logoutAndRedirect('/facebooks/connect');
}
</script>
<script type="text/javascript" src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php"></script>
<script type="text/javascript">
   FB.init("a615b75f05c1f13a2a6e50f302edebe8","receiver", {"ifUserNotConnected" : "connect"});
</script>
</body>
</html>
<?php exit; ?>

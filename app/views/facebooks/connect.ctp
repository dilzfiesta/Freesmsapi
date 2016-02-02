<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<body>
<div id="comments_post">

  <h3>Login:</h3>
  <div id="user">
    <fb:login-button></fb:login-button>
  </div>

</div>
<input type="hidden" name="facebook-request" value="true" />
<script type="text/javascript" src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php"></script>
<script type="text/javascript">
   FB.init("a615b75f05c1f13a2a6e50f302edebe8","receiver", {"ifUserConnected" : "connected"});
   FB.XFBML.Host.parseDomTree();
</script>
</body>
</html>
<?php exit; ?>

<script type="text/javascript" src="/js/sh/shCore.js"></script> 
<script type="text/javascript" src="/js/sh/shBrushPlain.js"></script>
<script type="text/javascript" src="/js/sh/shBrushPhp.js"></script>
<script type="text/javascript" src="/js/sh/shBrushPerl.js"></script>
<script type="text/javascript" src="/js/sh/shBrushJava.js"></script>
<script type="text/javascript" src="/js/sh/shBrushRuby.js"></script>
<script type="text/javascript" src="/js/sh/shBrushPython.js"></script>
<script type="text/javascript" src="/js/sh/shBrushVb.js"></script>
<script type="text/javascript" src="/js/sh/shBrushCSharp.js"></script>
<script type="text/javascript" src="/js/sh/shBrushCpp.js"></script>
<link type="text/css" rel="stylesheet" href="/css/sh/shCoreDefault.css"/> 
<script type="text/javascript">SyntaxHighlighter.all();</script>

<?php 
	if(SHOW_SENDER_ID) {
		$URL = "$server</strong>/messages/send?skey=$secret_key&message=YOUR_MESSAGE&senderid=YOUR_SENDERID&recipient=MOBILE_NUMBER";
	} else {
		$URL = "$server</strong>/messages/send?skey=$secret_key&message=YOUR_MESSAGE&recipient=MOBILE_NUMBER";
	}
?>

<div class="gradient"><h1><span></span>API Help Desk</h1></div>
    
    <div class="header_hw"><div class="header_wrapper header_03">Steps to get started</div></div>
    <!--<div class="header_05" style="background-color:#D3D3D3;padding:5px;margin-bottom:10px;">Steps to get started</div>-->
	
	<div class="pad10">
		<div class="pad5"><span class="f12"><strong>URL</strong> - <label class="f13"><?=$server?>/messages/send</label></span></div>
		<div class="pad5"><span class="f12"><strong>METHOD</strong> - POST or GET</span></div>
		<div class="pad5"><span class="f12"><strong>PARAMETERS</strong> - </span></div>
		<div class="pad5"><span class="f12">* skey - your secret key <strong><?=$secret_key?></strong></span></div>
		<div class="pad5"><span class="f12">* message - proper url encoded message. (If unsure please refer to <a href="http://www.w3schools.com/tags/ref_urlencode.asp" target="_blank">click here</a>)</span></div>
		<div class="pad5"><span class="f12">* recipient - whom to send, Can be comma seperated multiple values</span></div>
		<?php if(SHOW_SENDER_ID) { ?><div class="pad5"><span class="f12">* senderid - if nothing is set then <strong><?=SMS_SENDER_ID?></strong> is used (<a href="/users/myaccount"><em>create sender ID</em></a>)</span></div><? } ?>
		<div class="pad5"><span class="f12">* response - json or xml (<em>default is xml</em>)</span></div>
	</div>
	<div><br/></div>
    <div class="pad5 f12">
    	<div><span class="header_03">URL Example</span></div>
    	<div><br/></div>
    	<div><pre class="brush: plain;">

    	<?=$URL?>

    	</pre>
    </div>
    <div><br/></div>
    <div><br/></div>
    
    <div class="header_hw"><div class="header_wrapper header_03">Usage Examples</div></div>
    <!--<div class="header_05" style="background-color:#D3D3D3;padding:5px;margin-bottom:10px">Usage Examples</div>-->

    <div><br/></div>
    
    
    <div class="pad5 f12">
    	<div class="header_03">PHP</div>
    	<div><pre class="brush: php;">
    	<?php if(SHOW_SENDER_ID) { ?>
    		echo file_get_contents("<?=$server?>/messages/send?skey=<?=$secret_key?>&message=".urlencode('YOUR MESSAGE')."&senderid=YOUR_SENDERID&recipient=MOBILE_NUMBERS");
    	<?php } else { ?>
	    	echo file_get_contents("<?=$server?>/messages/send?skey=<?=$secret_key?>&message=".urlencode('YOUR MESSAGE')."&recipient=MOBILE_NUMBERS");
    	<?php } ?>
    	</pre></div>
    </div>
    
    
    <div><br/></div>
    <div><br/></div>
    
    
    <div class="pad5 f12">
    	<div class="header_03">JAVA</div>
    	<div><pre class="brush: java;">import java.net.*;
import java.io.*;

public class JavaGetUrl {
	public static void main(String[] args) throws Exception {
		URL myurl = new URL("<?=$URL?>");
		BufferedReader in = new BufferedReader(
		new InputStreamReader(
		myurl.openStream()));

		String inputLine;

		while ((inputLine = in.readLine()) != null)
		System.out.println(inputLine);

		in.close();
	}
}
	</pre></div>
    </div>
    
    
    <div><br/></div>
    <div><br/></div>
    
    
    <div class="pad5 f12">
    	<div class="header_03">PERL</div>
    	<div><pre class="brush: perl;">use strict;

use LWP::UserAgent;

my $ua = new LWP::UserAgent;
$ua->timeout(120);
my $url='<?=$URL?>';
my $request = new HTTP::Request('GET', $url);
my $response = $ua->request($request);
my $content = $response->content();
print $content;
	</pre></div>
    </div>
    
    
    <div><br/></div>
    <div><br/></div>
    
    
    <div class="pad5 f12">
    	<div class="header_03">PYTHON</div>
    	<div><pre class="brush: python;">from urllib import urlopen
print urlopen('<?=$URL?>').read()
	</pre></div>
    </div>
    
    
    <div><br/></div>
    <div><br/></div>
    
    
    <div class="pad5 f12">
    	<div class="header_03">RUBY</div>
    	<div><pre class="brush: ruby;">require 'net/http'
require 'uri'
Net::HTTP.get_print URI.parse('<?=$URL?>')
	</pre></div>
    </div>
    
    <div><br/></div>
    <div><br/></div>
    
    <div class="pad5 f12">
    	<div class="header_03">C Language</div>
    	<div class="header_03" style="font-weight:normal">* Dependencies - curl and libcurl4-dev or libcurl3-dev</div>
    	<div class="header_03" style="font-weight:normal">* Compile using -lcurl, eg -  'gcc file.c -lcurl -o output' on *nix</div>
    	<div class="header_03" style="font-weight:normal">* To run use './output'</div>
    	<div><pre class="brush: cpp;">#include &lt;curl/curl.h&gt;

void main() {

        long http_code = 0;
        CURL *curl;
        CURLcode res;

        //Initializing the CURL module
        curl = curl_easy_init();

        if(curl) {

                curl_easy_setopt(curl, CURLOPT_URL, "<?=$URL?>");
                res = curl_easy_perform(curl);

                if(CURLE_OK == res) {
                    curl_easy_getinfo(curl, CURLINFO_RESPONSE_CODE, &http_code);

                    if(http_code == 200) {
                        puts("Received 200 status code");
                    } else {
                        puts("Did not received 200 status code");
                    }
                }
        } else {
            puts("Could not initialize curl");
        }
}
	</pre></div>
    </div>

    <div><br/></div>
    <div><br/></div>
    
    <div class="pad5 f12">
    	<div class="header_03">VB.NET</div>
    	<div><pre class="brush: vb;">Imports System.IO
Imports System.Net
Dim connectionString As String = "<?=$URL?>"
Try
    Dim SourceStream As System.IO.Stream
    Dim myRequest As System.Net.HttpWebRequest = WebRequest.Create(connectionString)
    myRequest.Credentials = CredentialCache.DefaultCredentials
    Dim webResponse As WebResponse = myRequest.GetResponse
    SourceStream = webResponse.GetResponseStream()
    Dim reader As StreamReader = New StreamReader(webResponse.GetResponseStream())
    Dim str As String = reader.ReadLine()
    MessageBox.Show(str)

Catch ex As Exception
    MessageBox.Show(ex.Message)
End Try
	</pre></div>
    </div>
    
    <div><br/></div>
    <div><br/></div>
	
	<div class="pad5 f12">
    	<div class="header_03">C#</div>
    	<div><pre class="brush: csharp;">using System.IO;
using System.Net;
string connectionString = "<?=$URL?>";
try
{
        System.IO.Stream SourceStream = null;
        System.Net.HttpWebRequest myRequest = (HttpWebRequest)WebRequest.Create(connectionString);
        myRequest.Credentials = CredentialCache.DefaultCredentials;
        HttpWebResponse webResponse = (HttpWebResponse)myRequest.GetResponse();
        SourceStream = webResponse.GetResponseStream();
        StreamReader reader = new StreamReader(webResponse.GetResponseStream());
        string str = reader.ReadLine();
        MessageBox.Show(str);
}
catch (Exception ex)
{
       MessageBox.Show(ex.Message);
}
	</pre></div>
    </div>

    <div><br/></div>
    <div><br/></div>  

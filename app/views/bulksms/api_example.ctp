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
	if(SHOW_SENDER_ID)
		$URL = "$server</strong>/bulksms/send?skey=$secret_key&message=YOUR_MESSAGE&senderid=YOUR_SENDERID&mobile=MOBILE_NUMBERS";
	else
		$URL = "$server</strong>/bulksms/send?skey=$secret_key&message=YOUR_MESSAGE&mobile=MOBILE_NUMBERS";
?>

<div class="gradient"><h1><span></span>API Example</h1></div>
    
    <div class="header_hw"><div class="header_wrapper header_03">Usage Examples</div></div>

    <div><br/></div>
    
    
    <div class="pad5 f12">
    	<div class="header_03">PHP</div>
    	<div><pre class="brush: php;">
    	<? if(SHOW_SENDER_ID) { ?>
    	echo file_get_contents("<?=$server?>/bulksms/send?skey=<?=$secret_key?>&message=".urlencode('YOUR MESSAGE')."&senderid=YOUR_SENDERID&mobile=MOBILE_NUMBERS");
    	<? } else { ?>
    	echo file_get_contents("<?=$server?>/bulksms/send?skey=<?=$secret_key?>&message=".urlencode('YOUR MESSAGE')."&mobile=MOBILE_NUMBERS"); 
    	<? } ?>
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
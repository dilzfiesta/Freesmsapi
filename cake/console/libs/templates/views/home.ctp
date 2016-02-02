<?php
$output = "<h2>Sweet, \"" . Inflector::humanize($app) . "\" got Baked by CakePHP!</h2>\n";
$output .="
<?php
if (Configure::read() > 0):
	Debugger::checkSessionKey();
endif;
?>
<p>
<?php
	if (is_writable(TMP)):
		echo '<span class=\"notice success\">';
			__('Your tmp directory is writable.');
		echo '</span>';
	else:
		echo '<span class=\"notice\">';
			__('Your tmp directory is NOT writable.');
		echo '</span>';
	endif;
?>
</p>
<p>
<?php
	\$settings = Cache::settings();
	if (!empty(\$settings)):
		echo '<span class=\"notice success\">';
				echo sprintf(__('The %s is being used for caching. To change the config edit APP/config/core.php ', true), '<em>'. \$settings['engine'] . 'Engine</em>');
		echo '</span>';
	else:
		echo '<span class=\"notice\">';
				__('Your cache is NOT working. Please check the settings in APP/config/core.php');
		echo '</span>';
	endif;
?>
</p>
<p>
<?php
	\$filePresent = null;
	if (file_exists(CONFIGS . 'database.php')):
		echo '<span class=\"notice success\">';
			__('Your database configuration file is present.');
			\$filePresent = true;
		echo '</span>';
	else:
		echo '<span class=\"notice\">';
			__('Your database configuration file is NOT present.');
			echo '<div class="pad15"></div>';
			__('Rename config/database.php.default to config/database.php');
		echo '</span>';
	endif;
?>
</p>
<?php
if (!empty(\$filePresent)):
 	uses('model' . DS . 'connection_manager');
	\$db = ConnectionManager::getInstance();
 	\$connected = \$db->getDataSource('default');
?>
<p>
<?php
	if (\$connected->isConnected()):
		echo '<span class=\"notice success\">';
 			__('Cake is able to connect to the database.');
		echo '</span>';
	else:
		echo '<span class=\"notice\">';
			__('Cake is NOT able to connect to the database.');
		echo '</span>';
	endif;
?>
</p>\n";
$output .= "<?php endif;?>\n";
$output .= "<h3><?php __('Editing this Page') ?></h3>\n";
$output .= "<p>\n";
$output .= "<?php\n";
$output .= "\techo sprintf(__('To change the content of this page, edit: %s\n";
$output .= "\t\tTo change its layout, edit: %s\n";
$output .= "\t\tYou can also add some CSS styles for your pages at: %s', true),\n";
$output .= "\t\tAPP . 'views' . DS . 'pages' . DS . 'home.ctp.<div class="pad15"></div>',  APP . 'views' . DS . 'layouts' . DS . 'default.ctp.<div class="pad15"></div>', APP . 'webroot' . DS . 'css');\n";
$output .= "?>\n";
$output .= "</p>\n";
?>

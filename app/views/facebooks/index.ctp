<html>
<table>
<?php

$i = 0;
foreach ($person as $friend)
{
  echo '<tr class="vevent';
    if ($i == 0) {
      echo ' odd';
      $i = 1;
    } else {
      $i = 0;
    }
  echo '">';
  echo '<td><img src="' . $friend['image'] . '" /></td>';
  echo '<td>' . $friend['email'] . ' </td>';
  echo '<th scope="row" class="summary vcard"><span class="fn">' . $friend['name'] . '</span></th>';
  echo '<td><abbr class="dtstart" title="' . $friend['year'] . '-' 
     . date('m', strtotime($friend['month'])) . '-' . substr($friend['day'],0,-2) . '">' 
     . $friend['day'] . ' ' . $friend['month'] . ' ' . $friend['year'] . '</td>';
  echo '</tr>';
}

?>
</table>
</html>
<? exit; ?>

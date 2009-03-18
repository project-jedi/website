<?php

include '../mainfile.php';

echo '<html><head><title></title></head><body>';

if (isset($HTTP_POST_VARS['submit'])) {

	if (!$xoopsDB->queryFromFile('./xoops2_0_to_2_0_1.sql')) {
		echo 'File xoops2_0_to_2_0_1.sql not found!';
	}
	echo $xoopsLogger->dumpQueries();
	echo 'Upgraded to XOOPS 2.0.1. Do not forget do delete this file from your server!';

} else {
	echo 'Press the button below to upgrade to XOOPS 2.0.1<br />
<form action="xoops2_0_to_2_0_1.php" method="post">
<input type="submit" name="submit" value="'._SUBMIT.'" />
</form>';
}

echo '</body></html>';
?>
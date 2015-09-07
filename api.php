<?php

$render = isset($_GET['render']) ? $_GET['render'] : 'default';

switch ( $render ) {
	case 'json':
		echo 'this is json';
		break;
    default:
		echo "this not really defined";
}
exit;
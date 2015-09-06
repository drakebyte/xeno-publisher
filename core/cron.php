<?php
//	CORE: Cron handler
//	-	define cron array
//	-	add/modify cronjobs to queue (a cron job has a cron alias, a query, an interval)
//	-	remove cronjob
//	-	run all cronjobs

$xeno_cron_list = array();
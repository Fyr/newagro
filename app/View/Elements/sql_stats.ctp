<?php

if (!class_exists('ConnectionManager') || Configure::read('debug') < 2) {
	return false;
}
$noLogs = !isset($sqlLogs);
if ($noLogs):
	$sources = ConnectionManager::sourceList();

	$sqlLogs = array();
	foreach ($sources as $source):
		$db = ConnectionManager::getDataSource($source);
		if (!method_exists($db, 'getLog')):
			continue;
		endif;
		$sqlLogs[$source] = $db->getLog();
	endforeach;
endif;

if ($noLogs || isset($_forced_from_dbo_)) {
	$total = array('count' => 0, 'time' => 0);
	foreach ($sqlLogs as $source => $logInfo) {
		fdebug("{$source},{$logInfo['count']},{$logInfo['time']},{$_SERVER['REQUEST_URI']}\r\n", 'sql_stats.log');
		$total['count']+= $logInfo['count'];
		$total['time']+= $logInfo['time'];
	}
	fdebug("total,{$total['count']},{$total['time']},{$_SERVER['REQUEST_URI']}\r\n", 'sql_stats.log');
}

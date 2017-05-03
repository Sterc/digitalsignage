<?php

	$time = time();
	$date = strtotime('2017-04-20 08:30:00');
	
	echo 'Tijd: '.$time.'<br />';
	echo 'Datum: '.$date.'<br />';
	
	echo date('d-m-Y H:i:s', $time).'<br />';
	echo date('d-m-Y H:i:s', $date).'<br />';
	 
?>
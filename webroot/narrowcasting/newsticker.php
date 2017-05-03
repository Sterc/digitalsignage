<?php
	
	header('Content-Type: application/json');
	
	$feeds = array(
		'nunl'		=> array(
			'name'		=> 'NU.nl',
			'feed'		=> 'http://www.nu.nl/rss'
		)	
	);
	
	$data	= array();
	$output = array();
	
	$limit = isset($_GET['limit']) ? $_GET['limit'] : 5;
	
	foreach ($feeds as $key => $value) {
		$data[$key] = array();
		
		if (isset($value['feed'])) {
			if (false !== ($results = simplexml_load_string(file_get_contents($value['feed'])))) {
				if (isset($results->channel->item)) {
					foreach ($results->channel->item as $current => $item) {
						$data[$key][] = array(
							'source'	=> $key,
							'name'		=> $value['name'],
							'title'		=> (string) $item->title
						);
					}
				}
			}
		}
	}
	
	for ($i = 0; $i < $limit; $i++) {
		foreach ($data as $results) {
			if (isset($results[$i])) {
				$output[] = $results[$i];
			}
		}
	}
	
	echo json_encode(array(
		'items'	=> $output
	));
	
?>
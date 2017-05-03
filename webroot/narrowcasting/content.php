<?php
	
	header('Content-Type: application/json');

	$feeds = array(
		array(
			'type'			=> 'default',
			'source'		=> 'vvopende',
			'name'			=> 'V.V. Opende',
			'feed'			=> 'http://www.vvopende.com/nieuws-rss.xml',
			'description'	=> 'Lees verder op onze website: www.vvopende.com'
		),
		array(
			'type'			=> 'default',
			'source'		=> 'nunl',
			'name'			=> 'NU.nl',
			'feed'			=> 'http://www.nu.nl/rss/voetbal',
			'description'	=> 'Bron: www.nu.nl'
		)	
	);
	
	$data = array();
	
	foreach ($feeds as $key => $value) {
		$data[$key] = array();
		
		if (isset($value['feed'])) {
			if (false !== ($results = simplexml_load_string(file_get_contents($value['feed'])))) {
				if (isset($results->channel->item)) {
					foreach ($results->channel->item as $current => $item) {
						$current = array(
							'slide'			=> strtolower($value['type']),
							'source'		=> strtolower($value['source']),
							'name'			=> $value['name'],
							'title'			=> (string) $item->title,
							'content'		=> (string) $item->description,
							'description'	=> $value['description']
						);
						
						
						if (isset($item->enclosure->attributes()->url)) {
							$current['enclosure'] = array(
								'url'		=> (string) $item->enclosure->attributes()->url,
								'length'	=> '0',
								'type'		=> 'image/jpeg'
							);
						}
						
						$data['feed'.$key][] = $current;
					}
				}
			}
		}
	}
	
	if (false !== ($results = simplexml_load_string(file_get_contents('http://narrowcasting.vvopende.com/sponsoren-rss.xml')))) {
		if (isset($results->channel->item)) {
			foreach ($results->channel->item as $current => $item) {
				if (isset($item->enclosure->attributes()->url)) {
					$data['sponsors'][] = array(
						'name'		=> (string) $item->title,
						'enclosure'	=> array(
							'url'		=> (string) $item->enclosure->attributes()->url,
							'length'	=> '0',
							'type'		=> 'image/jpeg'
						)
					);
				}	
			}
		}
	}

	/*$output 	= array(
		array(
			'type'		=> 'wiwah-media',
			'time'		=> 10,
			'title'		=> 'Dit narrowcasting systeem wordt je aangeboden door:',
			'content'	=> '<p><strong>We creëren goed vormgegeven oplossingen*<br />die jouw boodschap naar een hoger niveau tilt. </strong></p><p>* En met ‘goed vormgegeven oplossingen’ bedoelen we niet alleen het uiterlijk maar ook dat het goed werkt. ;-)</p>'
		),
		array(
			'type'		=> 'table',
			'time'		=> 10,
			'title'		=> 'Programma: Thuis',
			'feed'		=> array(
				'url'		=> _URL_.'programma.php?programm=home',
				'type'		=> 'JSON'
			)
		),
		array(
			'type'		=> 'table',
			'time'		=> 10,
			'title'		=> 'Programma: Uit',
			'feed'		=> array(
				'url'		=> _URL_.'programma.php?programm=away',
				'type'		=> 'JSON'
			)
		),
		array(
			'type'		=> 'teletekst',
			'time'		=> 10,
			'title'		=> 'Teletekst',
			'iframe'	=> _URL_.'teletekst.php?p=818'
		),
		array(
			'type'		=> 'social-media',
			'time'		=> 10,
			'title'		=> 'Social Media',
			'feed'		=> array(
				'url'		=> _URL_.'socialmedia.php',
				'type'		=> 'JSON'
			)
		),
		array(
			'type'		=> 'buien-radar',
			'time'		=> 10,
			'title'		=> 'Het weer',
			'forecast'	=> array(
				'url'		=> 'http://api.buienradar.nl/data/forecast/1.1/daily/2749302',
				'type'		=> 'JSON'
			),
			'precipitation'	=> array(
				'url'		=> 'http://graphdata.buienradar.nl/forecast/json/?lat=53.173&lon=6.199&btc=201609191445',
				'type'		=> 'JSON'
			),
			'radar'		=> 'http://api.buienradar.nl/image/1.0/radarmapnl'
		),
		array(
			'type'		=> 'media',
			'time'		=> 21,
			'title'		=> 'IJtsma Livingworld',
			'video'		=> 'https://www.youtube.com/embed/Vyehn5vXQ6g'
		)
	);*/
	
	$output		= array(
		array(
			'time'			=> 10,
			'slide'			=> 'buienradar',
			'title'			=> 'Het weer',
			'lat'			=> '53.173',
			'lng'			=> '6.199'
		),
		array(
			'time'			=> 10,
			'slide'			=> 'social-media',
			'title'			=> 'Social media',
			'feed'			=> '//staging1.vvopende.com/social-media-rss.xml',
			'feedType'		=> 'XML'
		)
	);
	
	$limit 		= isset($_GET['limit']) ? $_GET['limit'] : 5;
	$sponsors	= ceil(count($data['sponsors']) / ($limit * 2));
	
	for ($i = 0; $i < $limit; $i++) {
		foreach ($data as $key => $results) {
			if (isset($results[$i])) {
				if (0 === strpos($key, 'feed')) {
					$item = array(
						'time'			=> 10,
						'slide'			=> $results[$i]['slide'],
						'source'		=> $results[$i]['source'],
						'name'			=> $results[$i]['name'],
						'title'			=> $results[$i]['title'],
						'content'		=> $results[$i]['content'],
						'description'	=> $results[$i]['description']
					);
						
					if (isset($results[$i]['enclosure'])) {
						$item['image'] = str_replace(array('https:', 'http:'), array('', ''), $results[$i]['enclosure']['url']);
					}
					
					$output[] = $item;
					
					for ($ii = 0; $ii < $sponsors; $ii++) {
						if (null !== ($sponsor = array_shift($data['sponsors']))) {
							$output[] = array(
								'time'		=> 5,
								'slide'		=> 'sponsor',
								'title'		=> 'Sponsor: '.$sponsor['name'],
								'logo'		=> $sponsor['enclosure']['url']
							);
						}
					}
				}
			}
		}
	}
	
	echo json_encode(array(
		'slides' => $output
	));
	
?>
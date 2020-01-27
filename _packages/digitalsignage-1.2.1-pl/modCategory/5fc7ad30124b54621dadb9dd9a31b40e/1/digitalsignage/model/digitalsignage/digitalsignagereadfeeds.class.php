<?php

    class DigitalSignageReadFeeds
    {
        /**
         * @access public.
         * @var modX.
         */
        public $modx;

        /**
         * @access public.
         * @param modX $modx.
         */
        public function __construct(modX &$modx)
        {
            $this->modx =& $modx;
        }

        /**
         * @access public.
         * @return Mixed.
         */
        public function readDigitalSignageFeeds($scriptProperties = [])
        {
            $limit          = $this->modx->getOption('limit', $scriptProperties, 5);
            $feedUrls       = explode(',', $this->modx->getOption('url', $scriptProperties, ''));
            $feedUrls2ld    = explode(',', $this->modx->getOption('2ld', $scriptProperties, 'uk'));
            $imageRequired  = (bool) $this->modx->getOption('makeImagesRequired', $scriptProperties, true);
            $toJson         = $this->modx->getOption('type', $scriptProperties, 'json');

            $data   = [];
            $output = [];

            if (isset($_GET['url'])) {
                $feedUrls = array_merge(explode(',', $_GET['url']), $feedUrls);
            }

            foreach (array_filter($feedUrls) as $key => $feedUrl) {
                $feedUrlParts = parse_url($feedUrl);

                if (isset($feedUrlParts['host'])) {
                    $host = array_reverse(explode('.', $feedUrlParts['host']));

                    if (in_array($host[0], $feedUrls2ld)) {
                        $key = $host[2];
                    } else {
                        $key = $host[1];
                    }
                }

                $feedData = $this->readDigitalSignageFeedCurl($feedUrl);

                if ($feedData) {
                    if ($feedData['type'] === 'xml') {
                        $response = simplexml_load_string($feedData['response'], null, LIBXML_NOCDATA);

                        if ($response) {
                            if (isset($response->channel->item)) {
                                foreach ($response->channel->item as $value) {
                                    $image = '';

                                    if (isset($value->enclosure['url'])) {
                                        $image = (string) $value->enclosure['url'];
                                    }

                                    if ((bool) $imageRequired && empty($image)) {
                                        continue;
                                    }

                                    $data[$key][] = [
                                        'title'     => (string) (isset($value->title) ? $value->title : ''),
                                        'image'     => $image,
                                        'content'   => (string) (isset($value->description) ? $value->description : ''),
                                        'pubdate'   => (string) (isset($value->pubDate) ? $value->pubDate : '')
                                    ];
                                }
                            }
                        }
                    } else if ($feedData['type'] === 'json') {
                        $response = json_decode($feedData['response']);

                        if ($response) {
                            if (isset($response->items)) {
                                foreach ((array) $response->items as $value) {
                                    $image = '';
                                    $content = '';

                                    if (isset($value->image)) {
                                        $image = (string) $value->image;
                                    } else if (isset($value->enclosure)) {
                                        $image = (string) $value->enclosure;
                                    }

                                    if (isset($value->description)) {
                                        $content = (string) $value->description;
                                    } else if (isset($value->content)) {
                                        $content = (string) $value->content;
                                    }

                                    if ((bool) $imageRequired && empty($image)) {
                                        continue;
                                    }

                                    $data[$key][] = [
                                        'title'     => (string) (isset($value->title) ? $value->title : ''),
                                        'image'     => $image,
                                        'content'   => $content,
                                        'pubdate'   => (string) (isset($value->pubDate) ? $value->pubDate : '')
                                    ];
                                }
                            }
                        }
                    }
                }
            }

            for ($i = 0; $i <= $limit; $i++) {
                foreach ($data as $key => $value) {
                    if (isset($value[$i])) {
                        $output[] = array_merge($value[$i], [
                            'source' => $key
                        ]);
                    }
                }
            }

            if ($this->modx->getOption('type', $scriptProperties, 'json') === 'array') {
                return $output;
            }

            return json_encode([
                'items' => $output
            ]);
        }

        /**
         * @access public.
         * @param String $url.
         * @return Boolean|Array.
         */
        public function readDigitalSignageFeedCurl($url)
        {
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL             => $url,
                CURLOPT_RETURNTRANSFER  => true,
                CURLOPT_CONNECTTIMEOUT  => 10,
                CURLOPT_TIMEOUT         => 10,
                CURLOPT_FOLLOWLOCATION  => true
            ]);

            $response   = curl_exec($curl);
            $info       = curl_getinfo($curl);

            curl_close($curl);

            if (isset($info['http_code']) || $info['http_code'] === '200') {
                if (isset($info['content_type'])) {
                    if (false !== strpos($info['content_type'],'xml')) {
                        return [
                            'type'      => 'xml',
                            'response'  => $response
                        ];
                    }

                    if (false !== strpos($info['content_type'],'json')) {
                        return [
                            'type'      => 'json',
                            'response'  => $response
                        ];
                    }
                }
            }

            return false;
        }
    }

?>
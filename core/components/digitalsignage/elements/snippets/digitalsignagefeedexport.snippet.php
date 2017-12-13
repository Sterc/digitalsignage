<?php

    if ($modx->loadClass('DigitalSignage', $modx->getOption('digitalsignage.core_path', null, $modx->getOption('core_path').'components/digitalsignage/').'model/digitalsignage/', true, true)) {
        $digitalsignage = new DigitalSignage($modx);

        if ($digitalsignage instanceOf DigitalSignage) {
            $data = array();
            $output = array();

            $limit = $modx->getOption('limit', $scriptProperties, 5);
            $urls = explode(',', $modx->getOption('url', $scriptProperties, ''));
            $imageRequired = $modx->getOption('imageRequired', $scriptProperties, '1');

            $parameters = $modx->request->getParameters();

            if (isset($parameters['url'])) {
                $urls = array_merge(explode(',', $parameters['url']), $urls);
            }

            foreach (array_filter($urls) as $value) {
                $url = parse_url($value);

                if (isset($url['host'])) {
                    $host = array_reverse(explode('.', $url['host']));

                    if ('uk' == $host[0]) {
                        $key = $host[2];
                    } else {
                        $key = $host[1];
                    }
                }

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL             => $value,
                    CURLOPT_RETURNTRANSFER  => true,
                    CURLOPT_CONNECTTIMEOUT  => 10,
                    CURLOPT_TIMEOUT         => 10,
                    CURLOPT_FOLLOWLOCATION  => true
                ));

                $response   = curl_exec($curl);
                $info       = curl_getinfo($curl);

                if (isset($info['http_code']) || '200' == $info['http_code']) {
                    if (isset($info['content_type'])) {
                        if (strstr($info['content_type'],'xml')) {
                            if (false !== ($response = simplexml_load_string($response, null, LIBXML_NOCDATA))) {
                                if (isset($response->channel->item)) {
                                    foreach ($response->channel->item as $value) {
                                        $image = '';

                                        if (isset($value->enclosure['url'])) {
                                            $image = (string) $value->enclosure['url'];
                                        }

                                        if ((bool) $imageRequired && empty($image)) {
                                            continue;
                                        }

                                        $data[$key][] = array(
                                            'title'         => (string) (isset($value->title) ? $value->title : ''),
                                            'image'         => $image,
                                            'content'       => (string) (isset($value->description) ? $value->description : ''),
                                            'pubdate'       => (string) (isset($value->pubDate) ? $value->pubDate : '')
                                        );
                                    }
                                }
                            }
                        } else {
                            if (false !== ($response = json_decode($response))) {
                                foreach ($response->items as $value) {
                                    $image = '';
                                    $content = '';

                                    if (isset($value->image)) {
                                        $image = (string) $value->image;
                                    } else if (isset($value->enclosure)) {
                                        $content = (string) $value->enclosure;
                                    }

                                    if (isset($value->description)) {
                                        $description = (string) $value->description;
                                    } else if (isset($value->content)) {
                                        $content = (string) $value->content;
                                    }

                                    if ((bool) $imageRequired && empty($image)) {
                                        continue;
                                    }

                                    $data[$key][] = array(
                                        'title'         => (string) (isset($value->title) ? $value->title : ''),
                                        'image'         => $image,
                                        'content'       => $content,
                                        'pubdate'       => (string) (isset($value->pubDate) ? $value->pubDate : '')
                                    );
                                }
                            }
                        }
                    }
                }

                curl_close($curl);
            }

            for ($i = 0; $i <= 5; $i++) {
                foreach ($data as $key => $value) {
                    if (isset($value[$i])) {
                        $output[] = array_merge($value[$i], array(
                            'source' => $key
                        ));
                    }
                }
            }

            return json_encode(array(
                'items' => $output
            ));
        }
    }

    return json_encode(array(
       'items' => array()
    ));

?>
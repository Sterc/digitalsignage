<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class DigitalSignageBroadcastsFeeds extends xPDOSimpleObject
{
    /**
     * @access public.
     * @return Array.
     */
    public function getSlides()
    {
        $slides = [];

        if (preg_match('/^(http|https)/si', $this->get('url'))) {
            $data = file_get_contents($this->get('url'));

            if ($data) {
                $data = simplexml_load_string($data);

                if ($data) {
                    if (isset($data->channel->item)) {
                        foreach ($data->channel->item as $value) {
                            $slides[] = $this->toSlide($this->toSlideArray($value));
                        }
                    }
                }
            }
        }

        return $slides;
    }

    /**
     * @access public.
     * @param Array $data.
     * @return Array.
     */
    public function toSlide(array $data = [])
    {
        if (!isset($data['content'])) {
            if (isset($data['description'])) {
                $data['content'] = $data['description'];
            }
        }

        if (isset($data['image'])) {
            $data['image'] = urldecode($data['image']);
        }

        if (isset($data['enclosure'])) {
            if (isset($data['enclosure']['@attributes']['url'])) {
                $data['image'] = urldecode($data['enclosure']['@attributes']['url']);
            }

            unset($data['enclosure']);
        }

        return $data;
    }

    /**
     * @access public.
     * @param SimpleXMLElement $data.
     * @return Array.
     */
    public function toSlideArray($data)
    {
        $output = [];

        foreach ((array) $data as $key => $value) {
            if (!in_array($key, ['id', 'link'], true)) {
                if (is_object($value) || is_array($value)) {
                    if (!empty($value)) {
                        $output[$key] = $this->toSlideArray($value);
                    } else {
                        $output[$key] = (string) $value;
                    }
                } else {
                    $output[$key] = (string) $value;
                }
            }
        }

        return $output;
    }
}

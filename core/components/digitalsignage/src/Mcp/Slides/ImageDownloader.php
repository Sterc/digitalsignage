<?php
namespace DigitalSignage\Mcp\Slides;

class ImageDownloader
{
    public const MAX_BYTES = 10 * 1024 * 1024;
    public const MAX_REDIRECTS = 5;
    public const TIMEOUT = 15;

    protected const TYPES = [
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG => 'png',
        IMAGETYPE_GIF => 'gif',
        IMAGETYPE_WEBP => 'webp',
    ];

    /**
     * @return array{content: string, extension: string}
     *
     * @throws SlideRejected
     */
    public function download(string $url): array
    {
        $body = $this->fetch($url);
        $info = @getimagesizefromstring($body);

        if ($info === false || !isset(self::TYPES[$info[2]])) {
            throw new SlideRejected(
                'The file at image_url is not a JPEG, PNG, GIF or WebP image.',
                'Tell the user this link does not point to a usable image (JPEG, PNG, GIF or WebP) and ask for another link, or create the slide without an image.'
            );
        }

        return ['content' => $body, 'extension' => self::TYPES[$info[2]]];
    }

    protected function fetch(string $url): string
    {
        for ($hop = 0; $hop <= self::MAX_REDIRECTS; $hop++) {
            $parts = parse_url($url);
            $scheme = strtolower($parts['scheme'] ?? '');
            $host = $parts['host'] ?? '';

            if (!in_array($scheme, ['http', 'https'], true) || $host === '') {
                throw $this->unreachable('image_url must be a public http or https link.');
            }

            $port = $parts['port'] ?? ($scheme === 'https' ? 443 : 80);
            $ip = $this->publicIp($host);

            $body = '';
            $headers = [];
            $curl = curl_init($url);
            curl_setopt_array($curl, [
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_TIMEOUT => self::TIMEOUT,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
                CURLOPT_RESOLVE => ["{$host}:{$port}:{$ip}"],
                CURLOPT_USERAGENT => 'DigitalSignage-MCP/1.0',
                CURLOPT_HTTPHEADER => ['Accept: image/*'],
                CURLOPT_HEADERFUNCTION => function ($curl, $line) use (&$headers) {
                    if (str_contains($line, ':')) {
                        [$name, $value] = explode(':', $line, 2);
                        $headers[strtolower(trim($name))] = trim($value);
                    }

                    return strlen($line);
                },
                CURLOPT_WRITEFUNCTION => function ($curl, $chunk) use (&$body) {
                    $body .= $chunk;

                    return strlen($body) > self::MAX_BYTES ? 0 : strlen($chunk);
                },
            ]);

            $ok = curl_exec($curl);
            $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
            $error = curl_error($curl);
            curl_close($curl);

            if (strlen($body) > self::MAX_BYTES) {
                throw new SlideRejected(
                    'The image at image_url is larger than 10 MB.',
                    'Tell the user the image is too large (maximum 10 MB) and ask for a smaller one.'
                );
            }

            if ($status >= 300 && $status < 400 && !empty($headers['location'])) {
                $url = $this->absolute($url, $headers['location']);
                continue;
            }

            if ($ok === false || $status !== 200) {
                throw $this->unreachable($ok === false ? "The image could not be downloaded ({$error})." : "The image could not be downloaded (HTTP {$status}).");
            }

            if (!str_starts_with(strtolower($headers['content-type'] ?? ''), 'image/')) {
                throw new SlideRejected(
                    'The link at image_url does not serve an image (content type ' . ($headers['content-type'] ?? 'unknown') . ').',
                    'Tell the user this link is not a direct link to an image file (it may be a web page) and ask for a direct image URL.'
                );
            }

            return $body;
        }

        throw $this->unreachable('The image URL redirects too many times.');
    }

    protected function publicIp(string $host): string
    {
        $ip = filter_var($host, FILTER_VALIDATE_IP) ? $host : gethostbyname($host);

        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            throw $this->unreachable('image_url must point to a public host.');
        }

        return $ip;
    }

    protected function absolute(string $base, string $location): string
    {
        if (preg_match('#^https?://#i', $location)) {
            return $location;
        }

        $parts = parse_url($base);
        $root = $parts['scheme'] . '://' . $parts['host'] . (isset($parts['port']) ? ':' . $parts['port'] : '');

        if (str_starts_with($location, '/')) {
            return $root . $location;
        }

        return $root . rtrim(dirname($parts['path'] ?? '/'), '/') . '/' . $location;
    }

    protected function unreachable(string $message): SlideRejected
    {
        return new SlideRejected(
            $message,
            'Tell the user the image could not be fetched from that link, and ask for a public direct image URL. Do not retry the same link.'
        );
    }
}

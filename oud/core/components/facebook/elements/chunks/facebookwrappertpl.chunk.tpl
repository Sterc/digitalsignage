<?xml version="1.0" encoding="utf-8"?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
    <channel>
        <title>[[*longtitle:default=`[[*pagetitle]]`]]</title>
        <description>[[*description:default=`[[*longtitle:default=`[[*pagetitle]]`]]`]]</description>
        <pubDate>[[*publishedon:dateFormat=`%a, %d %b %Y, %H:%M:%S GMT`]]</pubDate>
        <atom:link href="[[~[[*id]]? &scheme=`full`]]" rel="self"></atom:link>
        [[+output]]
    </channel>
</rss>
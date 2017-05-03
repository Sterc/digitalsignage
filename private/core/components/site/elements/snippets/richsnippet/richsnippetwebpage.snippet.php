<?php
switch ($modx->resource->getTVValue('richSnippetWebPage')) {
    case '2':
        $webPage = 'AboutPage';
        break;
    case '3':
        $webPage = 'CheckoutPage';
        break;
    case '4':
        $webPage = 'CollectionPage';
        break;
    case '5':
        $webPage = 'ContactPage';
        break;
    case '6':
        $webPage = 'ItemPage';
        break;
    case '7':
        $webPage = 'ProfilePage';
        break;
    case '8':
        $webPage = 'QAPage';
        break;
    case '9':
        $webPage = 'SearchResultsPage';
        break;
    default:
        $webPage = 'WebPage';
}

return 'itemscope itemtype="http://schema.org/' . $webPage . '"';

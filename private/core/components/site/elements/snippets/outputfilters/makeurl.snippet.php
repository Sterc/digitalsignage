<?php
if (is_numeric($input)) {
    return $modx->makeUrl($input);
}

if (!preg_match('~^(?:f|ht)tps?://~i', $input)) {
    return 'http://' . $input;
}

return $input;

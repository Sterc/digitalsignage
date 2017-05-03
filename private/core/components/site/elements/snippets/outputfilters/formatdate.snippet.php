<?php
if (!is_numeric($input)) {
    $input = strtotime($input);
}

$options = !empty($options) ? $options : '%e %B %Y';

return strftime($options, $input);

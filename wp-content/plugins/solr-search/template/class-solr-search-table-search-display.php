<?php

$a = shortcode_atts( array(
    'foo' => 'something',
    'bar' => 'something else',
), $atts );

return "foo = {$a['foo']}";
<?php

/**
 * RAM usage
 */

$separator = '--';
$output = '';
$cmd = 'free -h';

$string = shell_exec( $cmd );
$string = substr( $string, strpos( $string, PHP_EOL ) );
preg_match_all( "/([0-9\,\.KMG]+)/", $string, $matches );

$output .= 'Mem.total: ' . $matches[0][0];
$output .= $separator . 'Mem.free: ' . $matches[0][2];

return $output;
<?php

/**
 * Uptime
 */

$separator = '--';
$output = '';
$cmd = 'uptime';

$string = trim( shell_exec( $cmd ) );
$string = str_replace( '  ', ' ', $string );

$output .= substr( $string, 0, strpos( $string, ',' ) );
$string = trim( substr( $string, strpos( $string, ',' ) + 1 ) );

$output .= '--' . substr( $string, 0, strpos( $string, ',' ) );

return $output;
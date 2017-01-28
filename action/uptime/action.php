<?php

/**
 * Uptime
 */

$separator = '--';
$output = '';
$cmd = 'uptime';

$string = trim( shell_exec( $cmd ) );
$string = preg_replace( '/\s{2,}/', ' ', $string );

$output .= $controller->getSubstring( $string, ',' );
$output = str_replace( 'up', '/', $output );

$string = $controller->getSubstring( $string, ',', false );

$string = preg_replace( '/[^\d\s\,\.]/', '', $string );
$string = trim( substr( $string, 0, strrpos( $string, ', ' ) ) );
$string = preg_replace( '/\s{2,}/', ' ', $string );

$output .= '--' . $controller->getSubstring( $string, ',' );
$output .= ' / ' . $controller->getSubstring( $string, ',', false );

return $output;

<?php

/**
 * HDD usage
 */

$separator = '--';
$output = '';

$disk_free_space = disk_free_space('/');
$disk_total_space = disk_total_space('/');

$percent = 100 * ( $disk_free_space / $disk_total_space );
$percent = round( $percent );

$output .= 'Total ' . $controller->sizeFormat( $disk_total_space );
$output .= $separator . 'Free ' . $controller->sizeFormat( $disk_free_space ) . ' ' . $percent . '%';

return $output;

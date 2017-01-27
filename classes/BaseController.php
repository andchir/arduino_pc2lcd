<?php

/**
 * BaseController
 * @version 1.0.0
 * @author Andchir<andchir@gmail.com>
 */

namespace ArPC2LCD\Controllers;

class BaseController
{

    protected $config = array();

    public function __construct($config = [])
    {

        $this->config = array_merge([
            'tty_address' => '/dev/ttyACM0',
            'lcd_chars' => 16,
            'lcd_rows' => 2,
            'str_separator' => '--',
            'base_path' => dirname(__DIR__),
            'max_log_size' => 5 * 1024,
            'logging' => false
        ], $config);

    }

    /**
     * Print on LCD
     */
    public function printOnLCD()
    {
        $fp = fopen($this->config['tty_address'], 'w+');
        if( $fp === false){
            return;
        }
        while (1) {
            $output = include $this->config['base_path'] . '/action/ram/action.php';
            fwrite($fp, $this->lcdStringNormalize( $output ));
            sleep(2);
        }
        fclose($fp);
    }

    /**
     * @param $string
     * @return string
     */
    public function lcdStringNormalize( $string )
    {
        $out = [];
        $results = explode( $this->config['str_separator'], $string );
        foreach ( $results as $row ){
            $out[] = str_pad( substr($row, 0, $this->config['lcd_chars']), $this->config['lcd_chars'] );
        }
        return implode( $this->config['str_separator'], $out );
    }

}
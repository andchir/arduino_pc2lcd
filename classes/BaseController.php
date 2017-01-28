<?php

/**
 * BaseController
 * @version 1.0.1
 * @author Andchir<andchir@gmail.com>
 */

namespace ArPC2LCD\Controllers;

class BaseController
{

    protected $config = array();

    public function __construct($config = [])
    {

        $this->config = array_merge([
            'base_path' => dirname(__DIR__),
            'tty_address' => '/dev/ttyACM0',
            'lcd_chars' => 16,
            'lcd_rows' => 2,
            'str_separator' => '--',
            'switch_delay' => 10,
            'max_log_size' => 5 * 1024,
            'logging' => false
        ], $config);

    }

    /**
     * Run application
     * @param $arg
     * @return bool|void
     */
    public function run( $arg )
    {

        $data = file_get_contents( $this->config['base_path'] . '/action/data.json' );
        $data = json_decode( $data, true );
        $actionIndex = 0;

        $data['actions'] = array_values(array_filter($data['actions'], function($inp){
            return $inp['active'];
        }));

        //Update config
        $this->config = array_merge( $this->config, $this->arrayAvoidKeys( $data, array('actions', '') ) );

        $cmdAction = count( $arg ) >= 3
            ? trim( $arg[1] )
            : '';

        switch ( $cmdAction ){
            case 'print'://Print action output

                $output = $this->getActionOutput( $arg[2] );
                echo $output;
                exit;

                break;
            case 'print_lcd'://Print action output on LCD

                return $this->printOnceOnLCD( $arg[2] );

                break;
            case 'index'://Set action index

                $actionIndex = is_numeric( $arg[2] )
                    ? intval( $arg[2] )
                    : 0;

                break;
        }

        $this->printOnLCD( $data, $actionIndex );
    }

    /**
     * Get action output content
     * @param string $actionName
     * @return string
     */
    public function getActionOutput( $actionName )
    {
        $controller = &$this;
        return file_exists( $this->config['base_path'] . "/action/{$actionName}/action.php" )
            ? include $this->config['base_path'] . "/action/{$actionName}/action.php"
            : '';
    }

    /**
     * Print on LCD
     * @param array $data
     * @param int $actionStartIndex
     */
    public function printOnLCD( $data, $actionStartIndex = 0 )
    {
        $fp = fopen($this->config['tty_address'], 'w+');
        if( $fp === false ){
            return;
        }
        $startTime = time();
        $switchTime = $this->config['switch_delay'];
        while (1) {
            $currentTime = time() - $startTime;
            if( $currentTime >= $switchTime ){
                $actionStartIndex++;
                if( !isset( $data['actions'][ $actionStartIndex ] ) ){
                    $actionStartIndex = 0;
                }
                $switchTime += $this->config['switch_delay'];
            }
            $output = $this->getActionOutput( $data['actions'][ $actionStartIndex ]['name'] );
            fwrite( $fp, $this->lcdStringNormalize( $output ) );
            sleep( $data['actions'][ $actionStartIndex ]['refresh_time'] );
        }
        fclose($fp);
    }

    /**
     * Print once on LCD
     * @param $actionName
     * @return bool
     */
    public function printOnceOnLCD( $actionName )
    {
        $output = $this->getActionOutput( $actionName );
        if( !$output ){
            return false;
        }
        $fp = fopen($this->config['tty_address'], 'w+');
        if( $fp === false ){
            return false;
        }
        fwrite($fp, $this->lcdStringNormalize( $output ));
        fclose($fp);
        return true;
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

    /**
     * @param $bytes
     * @param string $unit
     * @param int $decimals
     * @return string
     */
    public function sizeFormat($bytes, $unit = "", $decimals = 2) {
        $units = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4, 'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);
        $value = 0;
        if ($bytes > 0) {
            if (!array_key_exists($unit, $units)) {
                $pow = floor(log($bytes)/log(1024));
                $unit = array_search($pow, $units);
            }
            $value = ($bytes/pow(1024,floor($units[$unit])));
        }
        if (!is_numeric($decimals) || $decimals < 0) {
            $decimals = 2;
        }
        return sprintf('%.' . $decimals . 'f '.$unit, $value);
    }

    /**
     * Get substring
     * @param $string
     * @param $separator
     * @param bool $before
     * @return mixed
     */
    public function getSubstring( $string, $separator, $before = true ){
        if( $before ){
            return trim( substr( $string, 0, strpos( $string, $separator ) ) );
        }
        else {
            return trim( substr( $string, strpos( $string, $separator ) + 1 ) );
        }
    }

    /**
     * @param $arr
     * @param $badKeys
     * @return mixed
     */
    public function arrayAvoidKeys( $arr, $badKeys )
    {
        return array_diff_key($arr, array_flip($badKeys));
    }

}
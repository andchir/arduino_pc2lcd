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
            'max_log_size' => 5 * 1024,
            'logging' => false
        ], $config);

    }

    /**
     * Run application
     * @param $arg
     */
    public function run( $arg )
    {

        $data = file_get_contents( $this->config['base_path'] . '/action/data.json' );
        $data = json_decode( $data, true );
        $actionNamesArr = array();
        $actionIndex = 0;
        foreach ( $data['actions'] as $index => $row ){
            array_push( $actionNamesArr, $row['name'] );
        }

        $cmdAction = count( $arg ) >= 3
            ? trim( $arg[1] )
            : '';

        switch ( $cmdAction ){
            case 'run'://Print action output

                if( in_array( $arg[2], $actionNamesArr ) ){
                    $output = $this->getActionOutput( $arg[2] );
                    echo $output;
                    exit;
                }

                break;
            case 'index'://Set action index

                $actionIndex = is_numeric( $arg[2] )
                    ? intval( $arg[2] )
                    : 0;

                break;
        }

        if( count( $arg ) >= 3 && $arg[1] == 'run' ){

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
        if( $fp === false){
            return;
        }
        while (1) {
            $output = $this->getActionOutput( $data['actions'][$actionStartIndex]['name'] );
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

    public function getDiskUsage()
    {

        $disk_free_space = disk_free_space('/');
        $disk_total_space = disk_total_space('/');

        $percent = 100 - (100 * ( $disk_free_space / $disk_total_space ));
        $percent = round( $percent );


    }

}
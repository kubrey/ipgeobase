<?php

namespace Ipgeobase;

/**
 * Description of IpGeobase
 *
 * @author kubrey <kubrey@gmail.com>
 */
class IpGeobase {

    private $fhandleCIDR, $fhandleCities, $fSizeCIDR, $fsizeCities;
    private $path;
    private $citiesDb;
    private $cidrDb;

    /**
     * 
     */
    public function __construct() {
        
    }

    /**
     * Открывает файлы с базами адресов
     * @throws \Exception
     */
    private function start() {
        $this->path = dirname(__FILE__) . "/../etc/";     
        if (empty($this->cidrDb)) {
            $this->cidrDb = $this->path . '/cidr_optim.txt';
        }
        if (empty($this->citiesDb)) {
            $this->citiesDb = $this->path . '/cities.txt';
        }
        if (empty($this->fhandleCIDR) || empty($this->fhandleCities)) {
            $this->fhandleCIDR = fopen($this->cidrDb, 'r');
            $this->fhandleCities = fopen($this->citiesDb, 'r');
            if (!$this->fhandleCIDR) {
                throw new \Exception('Failed to open Cidr');
            }
            if (!$this->fhandleCities) {
                throw new \Exception('Failed to open cities file');
            }
            $this->fSizeCIDR = filesize($this->cidrDb);
            $this->fsizeCities = filesize($this->citiesDb);
        }
    }

    /**
     * <pre>
     * Устанавливает путь к файлу, содержащему города
     * По умолчанию свежие файлы с базой хранятся в папке etc. Устанавливайте пути к своим базам, только при необходимости
     * </pre>
     * @param string $path Полный путь к файлу
     * @return \Ipgeobase\IpGeobase
     * @throws \Exception
     */
    public function setCitiesDb($path) {
        if (!is_file($path)) {
            throw new \Exception('Path to cities db is not valid');
        }
        $this->citiesDb = $path;
        return $this;
    }

    /**
     * <pre>
     * Устанавливает путь к файлу, содержащему страны
     * По умолчанию свежие файлы с базой хранятся в папке etc. Устанавливайте пути к своим базам, только при необходимости
     * </pre>
     * @param string $path Полный путь к файлу
     * @return \Ipgeobase\IpGeobase
     * @throws \Exception
     */
    public function setCidrDb($path) {
        if (!is_file($path)) {
            throw new \Exception('Path to cities db is not valid');
        }
        $this->cidrDb = $path;
        return $this;
    }

    /**
     * @param type $ip
     * @return boolean|\stdClass
     * @throws \Exception
     */
    public function lookup($ip) {
        if (empty($ip)) {
            throw new Exception('Ip is not set');
        }
        $this->start();

        $ip = sprintf('%u', ip2long($ip));

        rewind($this->fhandleCIDR);
        $rad = floor($this->fSizeCIDR / 2);
        $pos = $rad;
        while (fseek($this->fhandleCIDR, $pos, SEEK_SET) != -1) {
            if ($rad) {
                $str = fgets($this->fhandleCIDR);
            } else {
                rewind($this->fhandleCIDR);
            }

            $str = fgets($this->fhandleCIDR);

            if (!$str) {
                return false;
            }

            $arRecord = explode("\t", trim($str));

            $rad = floor($rad / 2);
            if (!$rad && ($ip < $arRecord[0] || $ip > $arRecord[1])) {
                return false;
            }

            if ($ip < $arRecord[0]) {
                $pos -= $rad;
            } elseif ($ip > $arRecord[1]) {
                $pos += $rad;
            } else {
                $result = array('range' => $arRecord[2], 'cc' => $arRecord[3]);

                if ($arRecord[4] != '-' && $cityResult = $this->getCityByIdx($arRecord[4])) {
                    $result += $cityResult;
                }
                $obj = json_decode(json_encode($result), false);
                return $obj;
            }
        }
        return false;
    }

    /**
     * Получение информации о городе по индексу
     * @param string $idx индекс города
     * @return array|boolean false, если не найдено
     */
    private function getCityByIdx($idx) {
        rewind($this->fhandleCities);
        while (!feof($this->fhandleCities)) {
            $str = fgets($this->fhandleCities);
            $arRecord = explode("\t", trim($str));
            if ($arRecord[0] == $idx) {
                return array('city' => $arRecord[1],
                    'region' => $arRecord[2],
                    'district' => $arRecord[3],
                    'lat' => $arRecord[4],
                    'lng' => $arRecord[5]);
            }
        }
        return false;
    }

}

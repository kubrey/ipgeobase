# ipgeobase.ru PHP API #

### Реализация поиска geo-данных IP по локальной базе ipgeobase.ru ###

### Описание ###
Обладает высокой точностью при определении городов России и Украины

### Установка через Composer ###

#### Определение зависимостей ####

 [Composer](http://getcomposer.org/).
Для установки добавьте `kubrey/ipgeobase` в Ваш `composer.json`. Если этого файла нет, то создайте его в корне сайта

```json
{
    "require": {
        "kubrey/ipgeobase": "0.2.*"
    }
}
```

#### Установка Composer ####

Выполнить в корне проекта: 

```
curl -s http://getcomposer.org/installer | php
```

#### Установка зависимостей ####

Выполнить в корне проекта: 

```
php composer.phar install
```

#### Автолоадер ####

Выполнить автозагрузку всех пакетов composer можно подключив скрипт:
```
require 'vendor/autoload.php';
```

### Применение ###

```

require 'vendor/autoload.php';

use Ipgeobase\IpGeobase;

$geo = new IpGeobase();
try {
    $geoInfo = $geo->lookup('194.85.91.253');
    var_dump($geoInfo);
//    object(stdClass)#2 (7) {
//  ["range"]=>
//  string(27) "194.85.88.0 - 194.85.95.255"
//  ["cc"]=>
//  string(2) "RU"
//  ["city"]=>
//  string(12) "Москва"
//  ["region"]=>
//  string(12) "Москва"
//  ["district"]=>
//  string(56) "Центральный федеральный округ"
//  ["lat"]=>
//  string(9) "55.755787"
//  ["lng"]=>
//  string(9) "37.617634"
//}
} catch (\Exception $e) {
    echo $e->getMessage();
}
```





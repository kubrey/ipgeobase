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





# yii2-russian-calendar
Yii2 комопонент производственного календаря РФ [gozoro/russian-calendar](https://github.com/gozoro/russian-calendar) на основе xmlcalendar.ru для PHP.
Yii2 component to use russian working calendar [gozoro/russian-calendar](https://github.com/gozoro/russian-calendar) based on the xmlcalendar.ru for PHP.







Установка
------------
```code
	composer require gozoro/yii2-russian-calendar
```




Конфигурация
-----
```php

'components' => [

	...

	'calendar' => [
		'class' => 'gozoro\russian_calendar\components\RussianCalendar'
	],

	...

],

```


Использование
-----
```php
$calendar = Yii::$app->calendar;

$date = '2019-01-02';
print "Дата: ".$date."\n";
print "ЭТО РАБОЧИЙ ДЕНЬ? ".($calendar->checkWorkingDay($date)?"ДА":"НЕТ")."\n"; // НЕТ

print "ЭТО ПОЛНЫЙ РАБОЧИЙ ДЕНЬ? ".($calendar->checkFullWorkingDay($date)?"ДА":"НЕТ")."\n"; // НЕТ

print "ЭТО КОРОТКИЙ РАБОЧИЙ ДЕНЬ? ".($calendar->checkShortWorkingDay($date)?"ДА":"НЕТ")."\n"; // НЕТ

print "ЭТО ВЫХОДНОЙ ДЕНЬ? ".($calendar->checkWeekend($date)?"ДА":"НЕТ")."\n"; // ДА

print "ЭТО ПРАЗДНИЧНЫЙ ДЕНЬ? ".($calendar->checkHoliday($date)?"ДА":"НЕТ")."\n"; // ДА

print "НАЗВАНИЕ ПРАЗДНИКА: ".$calendar->getHolidayName($date)."\n"; // Новогодние каникулы (в ред. Федерального закона от 23.04.2012 № 35-ФЗ)

print "СЛЕДУЮЩИЙ РАБОЧИЙ ДЕНЬ: ".$calendar->getNextWorkingDay($date)."\n"; // 2019-01-09
```




**Выходные дни**

По умолчанию выходными считаются суббота и воскресенье.
Это можно изменить указав выходные дни при вызове методов.
В этом случае выходными днями будут считаться только указанные дни недели.
```php
$my_weekends = [0]; // выходной только воскресенье, суббота рабочий день
$calendar->checkWorkingDay($date, $my_weekends);
```




**Продолжительность выходных и праздников**

Дополнительно можно получить список последовательных дат выходного или праздничного периода.


Получение списка дат выходного периода
```php
$weekends = [0,6];

// полный список
print_r($calendar->getWeekendDateArray($date, $weekends, true);

//Array
//(
//    [0] => 2018-12-30
//    [1] => 2018-12-31
//    [2] => 2019-01-01
//    [3] => 2019-01-02
//    [4] => 2019-01-03
//    [5] => 2019-01-04
//    [6] => 2019-01-05
//    [7] => 2019-01-06
//    [8] => 2019-01-07
//    [9] => 2019-01-08
//)

// только даты больше чем $date и даты в формате d.m.Y
print_r($calendar->getWeekendDateArray($date, $weekends, false, 'd.m.Y');

//Array
//(
//    [0] => 03.01.2019
//    [1] => 04.01.2019
//    [2] => 05.01.2019
//    [3] => 06.01.2019
//    [4] => 07.01.2019
//    [5] => 08.01.2019
//)
```


Получение списка дат праздничного периода
```php
// полный список
print_r($calendar->getHolidayDateArray($date, true);

// Array
//(
//    [0] => 2019-01-01
//    [1] => 2019-01-02
//    [2] => 2019-01-03
//    [3] => 2019-01-04
//    [4] => 2019-01-05
//    [5] => 2019-01-06
//    [6] => 2019-01-07
//    [7] => 2019-01-08
//)

// только даты больше чем $date и даты в формате d.m.Y
$holidayArray = $calendar->getHolidayDateArray($date, false, 'd.m.Y';
print_r($holidayArray);

//Array
//(
//    [0] => 03.01.2019
//    [1] => 04.01.2019
//    [2] => 05.01.2019
//    [3] => 06.01.2019
//    [4] => 07.01.2019
//    [5] => 08.01.2019
//)


// Сколько дней осталось отдыхать?
print count($holidayArray); // 6
```




**Кэширование**

Компонент по умолчанию кэширует xml-файлы в директории @app/runtime/russian-calendar,
чтобы при каждом вызове не делать запросы к xmlcalendar.ru.

В конфигурации можно явно задать параметры кэширования:
```php

'components' => [

	...

	'calendar' => [
		'class' => 'gozoro\russian_calendar\components\RussianCalendar',
		'cacheFolder' => '/home/user/mycache/russian-calendar', // директория для кэша, если не существует, то будет создана автоматически
		'cacheDuration' => 3600, // время жизни кэша в секундах

		'fileMode' => 0664, // права на файлы кэша
		'dirMode' => 0775,  // права на директорию кэша

		'cacheEnable' => true, // включить/отключить использование кэша, по умолчанию true
	],

	...

],

```




**Локализация**

По умолчанию компонент использует язык проекта:
```php
Yii::$app->language;
```


В конфигурации можно указать локаль явно:
```php

'components' => [

	...

	'calendar' => [
		'class' => 'gozoro\russian_calendar\components\RussianCalendar',
		'locale' => 'ru',
	],

	...

],

```
Компонент поддерживает только две локали: **ru** и **en**.

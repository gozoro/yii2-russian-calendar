<?php

namespace gozoro\russian_calendar\components;

use Yii;

/**
 * Russian working calendar component.
 * @author gozoro <gozoro@yandex.ru>
 */
class RussianCalendar extends \yii\base\Component
{
	/**
	 * Локаль календаря
	 * @var string
	 */
	public $locale;


	/**
	 * Путь к директория для кэша
	 * @var string
	 */
	public $cacheFolder = '@app/runtime/russian-calendar';

	/**
	 * Время хранения кэша в секундах
	 * @var int
	 */
	public $cacheDuration = 86400;

	/**
	 * Включает/отключает кэширование xml-файлов.
	 * По умолчанию true.
	 * @var bool
	 */
	public $cacheEnable = true;

	/**
	 * Права на файл кэша. По умолчанию не задны - устанавливаются операционной системой.
	 * @var int
	 */
	public $fileMode;

	/**
	 * Права на директорию кэша. По молчанию 0775.
	 * @var int
	 */
	public $dirMode = 0775;


	private $_calendar;

	public function init()
	{
		if(is_null($this->locale))
		{
			$this->locale = Yii::$app->language;
		}




		$locale        = $this->locale;
		$cacheFolder   = Yii::getAlias($this->cacheFolder);
		$cacheDuration = (int)$this->cacheDuration;

		if(!$this->cacheEnable)
		{
			$cacheFolder   = null;
			$cacheDuration = 0;
		}


		$this->_calendar = new \gozoro\russian_calendar\models\RussianCalendarModel($locale, $cacheFolder, $cacheDuration);
		$this->_calendar->fileMode = $this->fileMode;
		$this->_calendar->dirMode  = $this->dirMode;
	}




	/**
	 * Возвращает TRUE, если $date праздник. Праздник это всегда выходной день.
	 * @param int|string|DateTime $date проверяемая дата
	 * @return bool
	 */
	public function checkHoliday($date)
	{
		return $this->_calendar->checkHoliday($date);
	}

	/**
	 * Возвращает название праздника по дате, или пустую строку, если праздника нет.
	 * @param int|string|DateTime $date проверяемая дата
	 * @return string
	 */
	public function getHolidayName($date)
	{
		return $this->_calendar->getHolidayName($date);
	}

	/**
	 * Возращает TRUE, если $date выходной день (т.е. суббота, воскресенье или праздник,
	 * выпавший на будний день или субботу или воскресенье).
	 * Выходной день может не являться праздником.
	 * @param int|string|DateTime $date проверяемая дата
	 * @param array $weekends массив с днями недели, которые являются выходными (по умолчанию [0,6] - воскресенье, суббота)
	 * @return bool
	 */
	public function checkWeekend($date, $weekends = array(0,6))
	{
		return $this->_calendar->checkWeekend($date, $weekends);
	}

	/**
	 * Возращает TRUE, если $date предпраздничный (короткий) РАБОЧИЙ день.
	 * @param int|string|DateTime $date проверяемая дата
	 * @return bool
	 */
	public function checkShortWorkingDay($date)
	{
		return $this->_calendar->checkShortWorkingDay($date);
	}

	/**
	 * Возращает TRUE, если $date ПОЛНЫЙ РАБОЧИЙ день.
	 * Если день рабочий, но короткий, метод вернет FALSE.
	 *
	 * Для простой проверки рабочего дня (рабочий/не рабочий) используйте метод checkWorkingDay($date).
	 *
	 * @param int|string|DateTime $date проверяемая дата
	 * @param array $weekends массив с номерами дней недели, которые являются выходными (по умолчанию [0,6] - воскресенье, суббота)
	 * @return bool
	 */
	public function checkFullWorkingDay($date, $weekends = array(0,6))
	{
		return $this->_calendar->checkFullWorkingDay($date, $weekends);
	}


	/**
	 * Возвращает TRUE, если $date РАБОЧИЙ день (ПОЛНЫЙ или КОРОТКИЙ).
	 *
	 * @param int|string|DateTime $date проверяемая дата
	 * @param array $weekends массив с номерами дней недели, которые являются выходными (по умолчанию [0,6] - воскресенье, суббота)
	 * @return bool
	 */
	public function checkWorkingDay($date, $weekends = array(0,6))
	{
		return $this->_calendar->checkWorkingDay($date, $weekends);
	}

	/**
	 * Возвращает дату следующего рабочего дня.
	 * @param int|string|DateTime $date дата относительно которой проверяем следующий рабочий день
	 * @param array $weekends массив с номерами дней недели, которые являются выходными (по умолчанию [0,6] - воскресенье, суббота).
	 * @param string $format формат даты возвращаемого значения
	 * @return string Дата в формате YYYY-MM-DD
	 */
	public function getNextWorkingDay($date, $weekends = array(0,6), $format='Y-m-d')
	{
		return $this->_calendar->getNextWorkingDay($date, $weekends, $format);
	}

	/**
	 * Возвращает массив дат последовательных выходных дней, в который входит $date.
	 * Если $date не выходной день, то метод вернет пустой массив.
	 *
	 * @param int|string|DateTime $date дата относительно которой проверяем следующий рабочий день
	 * @param array $weekends массив с номерами дней недели, которые являются выходными (по умолчанию [0,6] - воскресенье, суббота).
	 * @param bool $fullArray если TRUE, то массив будет включать все даты, в том числ предшествующие и равную $date,
	 *						  иначе только даты после $date.
	 * @param string $format формат дат в возвращаемом массиве
	 * @return array of string
	 */
	public function getWeekendDateArray($date, $weekends = array(0,6), $fullArray = true, $format = 'Y-m-d')
	{
		return $this->_calendar->getWeekendDateArray($date, $weekends, $fullArray, $format);
	}

	/**
	 * Возвращает массив дат последовательных праздников, в который входит $date.
	 * Если $date не праздник, то метод вернет пустой массив (даже если $date выходной день).
	 *
	 * @param int|string|DateTime $date дата относительно которой проверяем следующий рабочий день
	 * @param bool $fullArray если TRUE, то массив будет включать все даты, в том числ предшествующие и равную $date,
	 *						  иначе только даты после $date.
	 * @param string $format формат дат в возвращаемом массиве
	 * @return array of string
	 */
	public function getHolidayDateArray($date, $fullArray = true, $format = 'Y-m-d')
	{
		return $this->_calendar->getHolidayDateArray($date, $fullArray, $format);
	}
}


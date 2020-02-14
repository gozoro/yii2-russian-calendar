<?php

namespace gozoro\russian_calendar\models;




class RussianCalendarModel extends \gozoro\russian_calendar\RussianCalendar
{
	/**
	 * Выбрасывает исключение
	 * @param string $message
	 * @throws RussianCalendarException
	 */
	public function throwException($message)
	{
		throw new RussianCalendarException($message);
	}
}

class RussianCalendarException extends \yii\base\Exception
{

}
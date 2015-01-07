<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 16.07.14
 * Time: 12:12
 * Comment: Yep, it's magic
 */
class DateHelper {

    public static function getDativeMonth($num) {
        $months = array(1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля', 5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа', 9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря');
        return $months[$num];
    }

    public static function convertNewsDate($date){
        return date('d', strtotime($date)) . ' ' . (DateHelper::getDativeMonth(date('n', strtotime($date)))) . ' ' . ((date('Y', strtotime($date)) < date('Y')) ? date('Y', strtotime($date)) : '');
    }
    public static function convertDate($date) {
        $date = new DateTime($date);
        $dateNow = new DateTime();

        $timestamp = strtotime($date->format('Y-m-d'));
        $timestampNow = strtotime($dateNow->format('Y-m-d'));

        if ($timestampNow === $timestamp) {
            return 'Сегодня в ' . $date->format('H:i');
        }

        if ($timestampNow > $timestamp && ((int) ($timestampNow - $timestamp) === 24 * 60 * 60)) {
            return 'Вчера в ' . $date->format('H:i');
        }

        return $date->format('d.m.Y');

    }

}
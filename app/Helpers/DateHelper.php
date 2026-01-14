<?php

namespace App\Helpers;

use Morilog\Jalali\Jalalian;

class DateHelper
{
    public static function convertToGregorian($jalaliDate)
    {
        try {
            if (str_contains($jalaliDate, '-')) {
                list($year, $month, $day) = explode('-', $jalaliDate);
            } elseif (str_contains($jalaliDate, '/')) {
                list($year, $month, $day) = explode('/', $jalaliDate);
            } else {
                return null;
            }
            
            $jalalian = Jalalian::fromFormat('Y-m-d', sprintf('%04d-%02d-%02d', $year, $month, $day));
            $gregorian = $jalalian->toCarbon();
            
            return $gregorian->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
    
    public static function convertToJalali($gregorianDate)
    {
        try {
            $jalalian = Jalalian::fromFormat('Y-m-d', $gregorianDate);
            return $jalalian->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
    
    public static function getCurrentJalaliDate()
    {
        return Jalalian::now()->format('Y-m-d');
    }
    
    public static function getCurrentGregorianDate()
    {
        return now()->format('Y-m-d');
    }
}
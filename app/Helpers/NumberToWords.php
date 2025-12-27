<?php

namespace App\Helpers;

class NumberToWords
{
    private static $ones = [
        '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine',
        'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen',
        'seventeen', 'eighteen', 'nineteen'
    ];

    private static $tens = [
        '', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'
    ];

    /**
     * Convert number to words (Indian numbering system)
     *
     * @param int|float $number
     * @return string
     */
    public static function convert($number)
    {
        if ($number == 0) {
            return 'zero';
        }

        $number = (int) $number;

        if ($number < 0) {
            return 'minus ' . self::convert(abs($number));
        }

        $words = '';

        // Crores
        if ($number >= 10000000) {
            $crores = (int) ($number / 10000000);
            $words .= self::convert($crores) . ' crore ';
            $number %= 10000000;
        }

        // Lakhs
        if ($number >= 100000) {
            $lakhs = (int) ($number / 100000);
            $words .= self::convert($lakhs) . ' lakh ';
            $number %= 100000;
        }

        // Thousands
        if ($number >= 1000) {
            $thousands = (int) ($number / 1000);
            $words .= self::convert($thousands) . ' thousand ';
            $number %= 1000;
        }

        // Hundreds
        if ($number >= 100) {
            $hundreds = (int) ($number / 100);
            $words .= self::$ones[$hundreds] . ' hundred ';
            $number %= 100;
        }

        // Tens and ones
        if ($number > 0) {
            if ($number < 20) {
                $words .= self::$ones[$number];
            } else {
                $words .= self::$tens[(int) ($number / 10)];
                if ($number % 10 > 0) {
                    $words .= ' ' . self::$ones[$number % 10];
                }
            }
        }

        return trim($words);
    }

    /**
     * Convert number to words with currency (Indian Rupees)
     *
     * @param int|float $number
     * @return string
     */
    public static function convertToRupees($number)
    {
        $number = number_format($number, 2, '.', '');
        list($rupees, $paise) = explode('.', $number);

        $words = 'rupees ' . self::convert($rupees);

        if ((int) $paise > 0) {
            $words .= ' and ' . self::convert($paise) . ' paise';
        }

        return $words . ' only';
    }
}

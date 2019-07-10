<?php

namespace Dakalab\CardParser;

use Dakalab\Birthday\Birthday;
use Dakalab\DivisionCode\DivisionCode;

class IDCard extends Card
{
    const MODULUS = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];

    const MAPPING = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];

    /**
     * Parse the id card
     */
    protected function parse(): void
    {
        $version = $this->parseVersion();

        if ($version == 0) { // invalid
            $this->info['error'] = 'Wrong format';

            return;
        }

        if ($version == 2 && !$this->validateLastChar()) {
            $this->info['error'] = 'Validation of last character fail';

            return;
        }

        if ($version == 1) {
            $date = '19' . substr($this->number, 6, 6);
            $gender = $this->number[14] % 2 ? 'M' : 'F';
        } else {
            $date = substr($this->number, 6, 8);
            $gender = $this->number[16] % 2 ? 'M' : 'F';
        }
        $birthday = new Birthday($date, $this->lang);

        $code = substr($this->number, 0, 6);
        $divisionCode = new DivisionCode;

        $this->info = [
            'valid'         => true,
            'gender'        => $gender, // M -> male，F -> female
            'birthday'      => (string) $birthday,
            'province'      => $divisionCode->getProvince($code),
            'city'          => $divisionCode->getCity($code),
            'county'        => $divisionCode->getCounty($code),
            'address'       => $divisionCode->getAddress($code),
            'age'           => $birthday->getAge(),
            'constellation' => $birthday->getConstellation(),
            'version'       => $version,
        ];
    }

    /**
     * Parse the version of ID card, return 0 if it's invalid
     *
     * @return int
     */
    public function parseVersion(): int
    {
        if (preg_match('/^\d{15}$/', $this->number)) {
            return 1;
        } elseif (preg_match('/^\d{17}[0-9X]{1}$/', $this->number)) {
            return 2;
        }

        return 0;
    }

    /**
     * Validate the last character
     *
     * @return bool
     */
    protected function validateLastChar(): bool
    {
        return self::generateLastChar($this->number) == $this->number[17];
    }

    /**
     * Generate the last character
     *
     * @param  string   $str
     * @return string
     */
    protected static function generateLastChar($str): string
    {
        $sum = 0;
        for ($i = 0; $i < 17; $i++) {
            $sum += $str[$i] * self::MODULUS[$i];
        }

        return self::MAPPING[$sum % 11];
    }

    /**
     * Generate a random valid ID number
     *
     * @return string
     */
    public static function generate(): string
    {
        $divisionCode = new DivisionCode;
        $count = $divisionCode->count();
        $offset = mt_rand(0, $count - 1);
        $code = key($divisionCode->getSlice($offset, 1));

        $year = mt_rand(date('Y') - 100, date('Y'));
        $month = mt_rand(1, 12);
        if ($month == 2) {
            $day = mt_rand(1, 28);
        } elseif (in_array($month, [4, 6, 9, 11])) {
            $day = mt_rand(1, 30);
        } else {
            $day = mt_rand(1, 31);
        }

        $seq = sprintf('%03d', mt_rand(0, 999));

        $str = $code . $year . sprintf('%02d%02d', $month, $day) . $seq;

        return $str . self::generateLastChar($str);
    }
}

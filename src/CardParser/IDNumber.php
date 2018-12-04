<?php

namespace Dakalab\CardParser;

use Dakalab\Birthday\Birthday;
use Dakalab\DivisionCode\DivisionCode;

class IDNumber
{
    public $number = ''; // ID number

    public $info = ['valid' => false]; // the parsed result

    const MODULUS = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];

    const MAPPING = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];

    /**
     * Constructor
     *
     * @param string $number ID number
     */
    public function __construct($number)
    {
        $this->number = strtoupper($number);
        try {
            $this->parse();
        } catch (\Exception $e) {
            $this->info['error'] = $e->getMessage();
        }
    }

    /**
     * Parse the id number
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
        $birthday = new Birthday($date, 'zh');

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
     * Parse the version of ID number, return 0 if it's invalid
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
        $sum = 0;
        for ($i = 0; $i < 17; $i++) {
            $sum += $this->number[$i] * self::MODULUS[$i];
        }

        return self::MAPPING[$sum % 11] == $this->number[17];
    }

    /**
     * Magic method to get specified field content
     * e.g. $idNumber->age
     * http://php.net/manual/en/language.oop5.overloading.php#object.get
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return isset($this->info[$key]) ? $this->info[$key] : null;
    }

    /**
     * Magic method to get specified field content
     * e.g. $idNumber->age()
     * http://php.net/manual/en/language.oop5.overloading.php#object.call
     *
     * @param  string  $key
     * @return mixed
     */
    public function __call($method, $args)
    {
        if ($method == 'info') {
            return $this->info;
        }

        return isset($this->info[$method]) ? $this->info[$method] : null;
    }
}

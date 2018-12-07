<?php

namespace Dakalab\CardParser;

abstract class Card
{
    /**
     * Card number
     *
     * @var string
     */
    public $number = '';

    /**
     * Language
     *
     * @var string
     */
    public $lang = 'zh';

    /**
     * Card info
     *
     * @var array
     */
    public $info = ['valid' => false];

    /**
     * Constructor
     *
     * @param string $number card number
     */
    public function __construct($number, $lang = 'zh')
    {
        $this->number = strtoupper($number);
        $this->lang = $lang;
        try {
            $this->parse();
        } catch (\Exception $e) {
            $this->info['error'] = $e->getMessage();
        }
    }

    /**
     * Parse the card number
     */
    abstract protected function parse(): void;

    /**
     * Magic method to get specified field content
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
     * http://php.net/manual/en/language.oop5.overloading.php#object.call
     *
     * @param  string  $method
     * @param  mixed   $args
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

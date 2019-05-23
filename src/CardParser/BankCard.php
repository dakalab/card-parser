<?php

namespace Dakalab\CardParser;

class BankCard extends Card
{
    const SUPPORTED_LANGS = ['en', 'zh'];

    /**
     * Known bank list
     *
     * @var array
     */
    protected $banks;

    /**
     * Bank card types
     *
     * @var array
     */
    protected $types;

    /**
     * Parse the bank card
     */
    protected function parse(): void
    {
        $this->loadTranslations($this->lang);
        $this->callAlipayAPI();
    }

    /**
     * Load translations
     *
     * @param string $lang
     */
    protected function loadTranslations($lang = 'zh'): void
    {
        if (!in_array($lang, self::SUPPORTED_LANGS)) {
            $lang = 'zh';
        }
        $dir = __DIR__ . DIRECTORY_SEPARATOR . 'BankCard' . DIRECTORY_SEPARATOR . $lang;
        $banks = file_get_contents($dir . DIRECTORY_SEPARATOR . 'banks.json');
        $this->banks = json_decode($banks, true);
        $types = file_get_contents($dir . DIRECTORY_SEPARATOR . 'types.json');
        $this->types = json_decode($types, true);
    }

    /**
     * Get bank name by bank code
     *
     * @param  string   $bankCode
     * @return string
     */
    public function getBankName($bankCode): string
    {
        return $this->banks[$bankCode] ?? '';
    }

    /**
     * Get the name of card type
     *
     * @param  string   $cardType
     * @return string
     */
    public function getCardTypeName($cardType): string
    {
        return $this->types[$cardType] ?? '';
    }

    /**
     * Get bank icon via alipay
     *
     * @param  string   $bankCode
     * @return string
     */
    public static function getBankIcon($bankCode): string
    {
        return "https://apimg.alipay.com/combo.png?d=cashier&t={$bankCode}";
    }

    /**
     * Get bank card info via alipay api
     */
    protected function callAlipayAPI(): void
    {
        $api = 'https://ccdcapi.alipay.com/validateAndCacheCardInfo.json?_input_charset=utf-8&cardNo=%s&cardBinCheck=true';

        $json = file_get_contents(sprintf($api, $this->number));
        $result = json_decode($json);

        if (!empty($result) && $result->validated) {
            $this->info = [
                'valid'        => true,
                'bankCode'     => $result->bank,
                'bankName'     => $this->getBankName($result->bank),
                'cardType'     => $result->cardType,
                'cardTypeName' => $this->getCardTypeName($result->cardType),
                'icon'         => self::getBankIcon($result->bank),
            ];
        }
    }
}

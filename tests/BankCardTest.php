<?php

namespace Dakalab\CardParser\Tests;

use Dakalab\CardParser\BankCard;

/**
 * Test class for Dakalab\CardParser\BankCard
 *
 * @coversDefaultClass \Dakalab\CardParser\BankCard
 * @runTestsInSeparateProcesses
 */
class BankCardTest extends TestCase
{
    /**
     * @dataProvider bankCardProvider
     */
    public function testBankCard($number, $lang, $expected): void
    {
        $bankCard = new BankCard($number, $lang);
        $this->assertEquals($bankCard->info, $bankCard->info());
        foreach ($expected as $k => $v) {
            $this->assertEquals($v, $bankCard->$k);
            $this->assertEquals($v, $bankCard->$k());
        }
    }

    public function bankCardProvider(): array
    {
        return [
            [
                '6225700000000000',
                'zh',
                [
                    'valid'        => true,
                    'bankCode'     => 'CEB',
                    'bankName'     => '中国光大银行',
                    'cardType'     => 'CC',
                    'cardTypeName' => '信用卡',
                    'icon'         => 'https://apimg.alipay.com/combo.png?d=cashier&t=CEB',
                ],
            ],
            [
                '6222980000000000',
                'en',
                [
                    'valid'        => true,
                    'bankCode'     => 'SPABANK',
                    'bankName'     => 'Ping An Bank',
                    'cardType'     => 'DC',
                    'cardTypeName' => 'Debit Card',
                    'icon'         => 'https://apimg.alipay.com/combo.png?d=cashier&t=SPABANK',
                ],
            ],
            [
                '6214180400000000000',
                'de',
                [
                    'valid'        => true,
                    'bankCode'     => 'NBBANK',
                    'bankName'     => '宁波银行',
                    'cardType'     => 'DC',
                    'cardTypeName' => '储蓄卡',
                    'icon'         => 'https://apimg.alipay.com/combo.png?d=cashier&t=NBBANK',
                ],
            ],
            [
                'fake-bank-account',
                'zh',
                [
                    'valid' => false,
                ],
            ],
        ];
    }
}

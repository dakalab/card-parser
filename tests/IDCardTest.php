<?php

namespace Dakalab\CardParser\Tests;

use Dakalab\CardParser\IDCard;

/**
 * Test class for Dakalab\CardParser\IDCard
 *
 * @coversDefaultClass \Dakalab\CardParser\IDCard
 * @runTestsInSeparateProcesses
 */
class IDCardTest extends TestCase
{
    /**
     * @dataProvider idCardProvider
     */
    public function testIDCard($input, $expected): void
    {
        $idCard = new IDCard($input);
        $this->assertEquals($idCard->info, $idCard->info());
        foreach ($expected as $k => $v) {
            $this->assertEquals($v, $idCard->$k);
            $this->assertEquals($v, $idCard->$k());
        }
    }

    public function idCardProvider(): array
    {
        return [
            [
                '445281200010010010',
                [
                    'valid'         => true,
                    'gender'        => 'M',
                    'birthday'      => '2000-10-01',
                    'province'      => '广东省',
                    'city'          => '揭阳市',
                    'county'        => '普宁市',
                    'address'       => '广东省揭阳市普宁市',
                    'constellation' => '天秤座',
                    'version'       => 2,
                ],
            ],
            [
                'abc',
                [
                    'valid' => false,
                    'error' => 'Wrong format',
                ],
            ],
            [
                '445281200010010011',
                [
                    'valid' => false,
                    'error' => 'Validation of last character fail',
                ],
            ],
            [
                '445281100010010012',
                [
                    'valid' => false,
                    'error' => 'The age is out of range [0, 200)',
                ],
            ],
            [
                '331303870808141',
                [
                    'valid'         => true,
                    'gender'        => 'M',
                    'birthday'      => '1987-08-08',
                    'province'      => '浙江省',
                    'city'          => '',
                    'county'        => '',
                    'address'       => '浙江省',
                    'constellation' => '狮子座',
                    'version'       => 1,
                ],
            ],
        ];
    }

    public function testGenerate(): void
    {
        // loop 100 times in order to raise code coverage
        for ($i = 0; $i < 100; $i++) {
            $number = IDCard::generate();
            $idCard = new IDCard($number);
            $this->assertTrue($idCard->valid);
        }
    }
}

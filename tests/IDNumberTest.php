<?php

namespace Dakalab\CardParser\Tests;

use Dakalab\CardParser\IDNumber;

/**
 * Test class for Dakalab\CardParser\IDNumber
 *
 * @coversDefaultClass \Dakalab\CardParser\IDNumber
 * @runTestsInSeparateProcesses
 */
class IDNumberTest extends TestCase
{
    /**
     * @dataProvider idNumberProvider
     */
    public function testIDNumber($input, $expected): void
    {
        $idNumber = new IDNumber($input);
        $this->assertEquals($idNumber->info, $idNumber->info());
        foreach ($expected as $k => $v) {
            $this->assertEquals($v, $idNumber->$k);
            $this->assertEquals($v, $idNumber->$k());
        }
    }

    public function idNumberProvider(): array
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
}

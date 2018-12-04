# Card Parser

Card parser for ID number and Bank account of China

[![Build Status](https://travis-ci.org/dakalab/card-parser.svg?branch=master)](https://travis-ci.org/dakalab/card-parser)
[![codecov](https://codecov.io/gh/dakalab/card-parser/branch/master/graph/badge.svg)](https://codecov.io/gh/dakalab/card-parser)
[![Total Downloads](https://poser.pugx.org/dakalab/card-parser/downloads)](https://packagist.org/packages/dakalab/card-parser)
[![License](https://poser.pugx.org/dakalab/card-parser/license.svg)](https://packagist.org/packages/dakalab/card-parser)

## Install

```
composer require dakalab/card-parser
```

*require php >= 7*

## ID Number

### Usage

```
use Dakalab\CardParser\IDNumber;

$no = 'the-id-number';

$idNumber = new IDNumber($no);
$info = $idNumber->info; // equal to `$info = $idNumber->info();`
print_r($info);
$age = $idNumber->age; // equal to `$age = $idNumber->age();`
echo $age . PHP_EOL;
```

### Result

The result info will be in below formats:

1) for valid ID number:

```
Array
(
    [valid] => true
    [gender] => M|F
    [birthday] => yyyy-mm-dd
    [province] => string
    [city] => string
    [county] => string
    [address] => string
    [age] => integer
    [constellation] => string
    [version] => 1|2
)
```

2) for invalid ID number:

```
Array
(
    [valid] => false
    [error] => "error message"
)
```

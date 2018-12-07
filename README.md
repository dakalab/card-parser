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

## ID Card

### Usage

```
use Dakalab\CardParser\IDCard;

$no = 'the-id-number';
$lang = 'zh'; // optional, default is zh

$idCard = new IDCard($no);
$info = $idCard->info; // equal to `$info = $idCard->info();`
print_r($info);
$age = $idCard->age; // equal to `$age = $idCard->age();`
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

### Generate random ID number

```
echo IDCard::generate();
```

## Bank Card

### Usage

```
use Dakalab\CardParser\BankCard;

$no = 'the-bank-account';
$lang = 'zh'; // optional, default is zh

$bankCard = new BankCard($no, $lang);
print_r($bankCard->info);
```

### Result

The result info will be in below formats:

1) for valid bank account:

```
Array
(
    [valid] => true
    [bankCode] => string
    [bankName] => string
    [cardType] => string
    [cardTypeName] => string
    [icon] => string
)
```

2) for invalid bank account:

```
Array
(
    [valid] => false
)
```

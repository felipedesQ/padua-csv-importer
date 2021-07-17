<?php

namespace Padua\CsvImporter\Service;

class CheckCharacterService
{
    const VALID_CHARACTERS = ['2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C','D', 'E', 'F', 'G', 'H', 'J', 'K','L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T','U', 'V', 'W', 'X', 'Y', 'Z'];
    const FACTOR = 2;

    public function __construct()
    {
    }

    public function generateCheckCharacter(string $checkCodeDigit) :string
    {

        $factor = self::FACTOR;
        $sum = 0;
        $n = count(self::VALID_CHARACTERS);

        // Starting from the right and working leftwards is easier since
        // the initial "factor" will always be "2"
        for ($iCount = strlen($checkCodeDigit)-1; $iCount >= 0; $iCount--){

            $codePoint = array_search($checkCodeDigit[$iCount], self::VALID_CHARACTERS);
            $addend = $factor * $codePoint;

            // Alternate the "factor" that each "codePoint" is multiplied by
            $factor = ($factor == 2) ? 1 : 2;

            // Sum the digits of the "addend" as expressed in base "n"
            $addend = (int)($addend / $n) + (int)($addend % $n);

            $sum += $addend;
        }

        // Calculate the number that must be added to the "sum"
        // to make it divisible by "n"
        $remainder = $sum % $n;
        $checkCodePoint = ($n - $remainder) % $n;

        return self::VALID_CHARACTERS[$checkCodePoint];
    }
}

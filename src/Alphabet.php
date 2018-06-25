<?php

namespace FuzzyMatching;

interface Alphabet
{
    public function getLetterFreq();

    public function letters();

    public function randLetter();
}

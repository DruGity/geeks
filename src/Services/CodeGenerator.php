<?php

namespace App\Services;

use App\Entity\Code;
use App\Repository\CodeRepository;

class CodeGenerator
{
    const LETTERS = ['A', 'B', 'C', 'D', 'E', 'F'];
    const NUMBERS = [2, 3, 4, 6, 7, 8, 9];

    /** @var CodeRepository  */
    private $codeRepository;

    public function __construct(CodeRepository $codeRepository)
    {
        $this->codeRepository = $codeRepository;
    }

    public function generate()
    {
        $randNumbers = [];
        $randLetters = [];
        for ($i=0; $i<6; $i++) {
            $key = array_rand($this::LETTERS, 1);
            $randLetters[] = $this::LETTERS[$key];
        }
        for ($i=0; $i<4; $i++) {
            $key = array_rand($this::NUMBERS, 1);
            $randNumbers[] = $this::NUMBERS[$key];
        }

        $codeArray = array_merge($randLetters, $randNumbers);
        shuffle($codeArray);
        $code = implode('', $codeArray);

        $code = $this->checkIfNotExists($code) ? $code : $this->generate();

        return $code;
    }

    private function checkIfNotExists(string $code)
    {
        /** @var Code $code */
        $code = $this->codeRepository->findOneBy(['code' => $code]);
        if($code) {
            return false;
        } else {
            return true;
        }
    }

}
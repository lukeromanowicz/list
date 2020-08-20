<?php

namespace App\Service\Words;

class SentenceAnalyser
{
    /**
     * @return WordDTO[]
     */
    public function getWordsFromSentence(string $sentence): array
    {
        $matches = [];
        if (!preg_match_all("/[\w']{3,}/", $sentence, $matches)) {
            return [];
        }

        $sortedWordsMap = [];
        foreach ($matches[0] as $word) {
            if (array_key_exists($word, $sortedWordsMap)) {
                $sortedWordsMap[$word] += 1;
            } else {
                $sortedWordsMap[$word] = 1;
            }
        }

        $wordDTOs = [];
        foreach ($sortedWordsMap as $word => $count) {
            $wordDTO = new WordDTO();
            $wordDTO->word = $word;
            $wordDTO->count = $count;
            array_push($wordDTOs, $wordDTO);
        }


        return $wordDTOs;
    }
}

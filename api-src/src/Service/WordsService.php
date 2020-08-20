<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Repository\WordRepository;
use App\Entity\User;
use App\Service\Words\SentenceAnalyser;
use App\Service\Words\UserDTO;

class WordsService
{
    private UserRepository $userRepository;
    private WordRepository $wordRepository;
    private SentenceAnalyser $sentenceAnalyser;

    public function __construct(
        UserRepository $userRepository,
        WordRepository $wordRepository,
        SentenceAnalyser $sentenceAnalyser
    ) {
        $this->userRepository = $userRepository;
        $this->wordRepository = $wordRepository;
        $this->sentenceAnalyser = $sentenceAnalyser;
    }

    public function clearWordsByUser(UserDTO $userDTO): void
    {
        $this->userRepository->deleteByIp($userDTO->ip);
    }

    /**
     * @return UserDTO[]
     */
    public function getWordsByUser(UserDTO $userDTO): array
    {
        return $this->wordRepository->findByUserIp($userDTO->ip) ?: [];
    }

    public function addWords(string $sentence, UserDTO $userDTO): void
    {
        $words = $this->sentenceAnalyser->getWordsFromSentence($sentence);

        if (count($words) === 0) {
            return;
        }

        $this->userRepository->createFromDTO($userDTO);

        /** @var User $user */
        $user = $this->userRepository->findOneBy(['ip' => $userDTO->ip]);
        $this->wordRepository->updateWithDTOs($words, $user);
    }
}

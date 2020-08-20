<?php

namespace App\Controller;

use App\Controller\Words\AddWordsPayload;
use App\Service\Words\UserDTO;
use Doctrine\ORM\EntityNotFoundException;
use Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\WordsService;
use App\Service\Words\WordDTO;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WordsController extends AbstractController
{
    private WordsService $wordsService;

    /**
     * @param ValidatorInterface $validator
     * @param WordsService $wordsService
     */
    public function __construct(
        ValidatorInterface $validator,
        WordsService $wordsService
    ) {
        parent::__construct($validator);
        $this->wordsService = $wordsService;
    }

    /**
     * @Route("/words", methods={"GET"})
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getWords(): JsonResponse
    {
        $user = new UserDTO();
        $user->ip = $this->container->get('request_stack')->getCurrentRequest()->getClientIp();

        $words = $this->wordsService->getWordsByUser($user);
        $rawResponse = $this->encodeResponse($this->normalize($words), 'array<' . WordDTO::class . '>');

        return new JsonResponse($rawResponse, 200, [], true);
    }

    /**
     * @Route("/words", methods={"DELETE"})
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function clearWords(): Response
    {
        $user = new UserDTO();
        $user->ip = $this->container->get('request_stack')->getCurrentRequest()->getClientIp();

        try {
            $this->wordsService->clearWordsByUser($user);
            return new Response(); // 200 ok
        } catch (EntityNotFoundException $e) {
            return new Response($e->getMessage(), 404); // TODO: refactor into unified JSON based error message structure
        }
    }

    /**
     * @Route("/words", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function addWords(Request $request): Response
    {
        /** @var AddWordsPayload $payload */
        $payload = $this->decodePayload($request,AddWordsPayload::class);

        $user = new UserDTO();
        $user->ip = $this->container->get('request_stack')->getCurrentRequest()->getClientIp();

        $this->wordsService->addWords($payload->sentence, $user);

        return new Response(); // 200 ok
    }
}

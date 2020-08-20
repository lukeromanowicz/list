<?php

namespace App\Controller;

use Exception;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerBuilder;

abstract class AbstractController extends SymfonyAbstractController
{
    const API_DATA_FORMAT = 'json';

    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(
        ValidatorInterface $validator
    ) {
        $this->serializer = SerializerBuilder::create()
            ->setPropertyNamingStrategy(
                new SerializedNameAnnotationStrategy(
                    new IdenticalPropertyNamingStrategy()
                )
            )
            ->build();
        $this->validator = $validator;
    }

    protected function decodePayload(Request $requestData, string $class) {
        $object = $this->deserialize($requestData->getContent(), $class);
        $this->validate($object);

        return $object;
    }

    protected function encodeResponse($dataToDenormalize, string $type) {
        if (is_null($dataToDenormalize)) return null;
        $object = $this->serializer->fromArray($dataToDenormalize, $type);

        if ($_ENV['APP_ENV'] === 'dev') {
            $violations = $this->validator->validate($object);

            if (count($violations) > 0) {
                throw new Exception($violations);
            }
        }

        return $this->serialize($object);
    }

    /**
     * @param mixed $data
     */
    protected function normalize($data): array {
        $result = $this->get('serializer')->normalize($data, null);
        return $result;
    }

    private function validate($objectInstance): bool {
        $violations = $this->validator->validate($objectInstance);

        if (count($violations) > 0) {
            throw new Exception($violations);
        }

        return true;
    }

    private function serialize($data): string {
        return $this->serializer->serialize($data, self::API_DATA_FORMAT);
    }

    private function deserialize(string $serializedData, string $className) {
        return $this->serializer->deserialize($serializedData, $className, self::API_DATA_FORMAT);
    }
}

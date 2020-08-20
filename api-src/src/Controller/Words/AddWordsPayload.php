<?php

namespace App\Controller\Words;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Type;

class AddWordsPayload
{
    /**
     * @Assert\NotBlank()
     *
     * @Type("string")
     */
    public string $sentence;
}

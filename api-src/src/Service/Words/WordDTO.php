<?php

namespace App\Service\Words;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Type;

class WordDTO
{
    /**
     * @Assert\NotBlank()
     *
     * @Type("string")
     */
    public string $word;

    /**
     * @Assert\NotBlank()
     *
     * @Type("int")
     */
    public int $count;
}

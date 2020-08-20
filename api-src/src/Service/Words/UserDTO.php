<?php

namespace App\Service\Words;

use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{
    /**
     * @Assert\NotBlank()
     */
    public string $ip;
}

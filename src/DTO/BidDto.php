<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly class BidDto
{

    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public string $amount,

        #[Assert\NotBlank]
        public string $bidder,
    )
    {
    }
}
<?php

namespace App\Validation;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class DTOValidator
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    public function validate(object $dto): void
    {
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            throw new \InvalidArgumentException(json_encode(['errors' => $errorMessages]));
        }
    }
}

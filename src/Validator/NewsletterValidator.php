<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\MailChimpPlugin\Validator;

use BitBag\MailChimpPlugin\Validator\Constraints\UniqueNewsletterEmail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Email;

final class NewsletterValidator
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $email
     *
     * @return array
     */
    public function validate(string $email): array
    {
        $violations = $this->validator->validate($email, [
            new Email(['message' => 'bitbag.mailchimp_plugin.invalid_email']),
            new NotBlank(['message' => 'bitbag.mailchimp_plugin.email_not_blank']),
            new UniqueNewsletterEmail(),
        ]);

        $errors = [];

        if (count($violations) === 0) {
            return $errors;
        }

        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $errors[] = $violation->getMessage();
        }

        return $errors;
    }
}
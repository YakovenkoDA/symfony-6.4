<?php

namespace App\Controller\Api;

use App\Base\DTO\DTOInterface;
use App\Base\DTO\Transformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseController extends AbstractController
{
    protected \App\Service\UserService $service;
    protected DTOInterface $dto;
    public static function getSubscribedServices(): array
    {
        $subscribedServices = parent::getSubscribedServices();
        $subscribedServices['property_accessor'] = PropertyAccessorInterface::class;
        $subscribedServices['dto.transformer'] = Transformer::class;

        return $subscribedServices;
    }

    public function validateDTO(ValidatorInterface $validator, $groups = [])
    {
        $errors = $validator->validate($this->dto, null, $groups);
        if (count($errors) > 0) {
            throw new BadRequestHttpException((string)$errors);
        }
    }
}
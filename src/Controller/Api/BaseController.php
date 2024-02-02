<?php

namespace App\Controller\Api;

use App\Base\DTO\Transformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class BaseController extends AbstractController
{
    protected \App\Service\UserService $service;

    public static function getSubscribedServices(): array
    {
        $subscribedServices = parent::getSubscribedServices();
        $subscribedServices['property_accessor'] = PropertyAccessorInterface::class;
        $subscribedServices['dto.transformer'] = Transformer::class;

        return $subscribedServices;
    }

    public function handleValidationErrors(ConstraintViolationListInterface $errors)
    {
        if (count($errors) > 0) {
            throw new BadRequestHttpException((string)$errors);
        }
    }
}
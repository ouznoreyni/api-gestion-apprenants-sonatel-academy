<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Tags;
use Doctrine\ORM\EntityManagerInterface;

final class TagsDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->_entityManager = $entityManager;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Tags;
    }

    public function persist($data, array $context = [])
    {
        // call your persistence layer to save $data
        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {
        // call your persistence layer to delete $data
        $data->setArchiver(true);
        $this->_entityManager->flush();
        return $data;
    }
}

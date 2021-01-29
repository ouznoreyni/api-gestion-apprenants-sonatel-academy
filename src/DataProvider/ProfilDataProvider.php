<?php
// api/src/DataProvider/BlogPostItemDataProvider.php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Profil;
use App\Repository\ProfilRepository;

final class ProfilDataProvider implements ContextAwareCollectionDataProviderInterface, ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $profilRepository;

    public function __construct(ProfilRepository $profilRepository)
    {
        $this->profilRepository = $profilRepository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Profil::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        // Retrieve the blog post item from somewhere then return it or null if not found
        return $this->profilRepository->findOneBy(['archiver' => false, 'id' => $id]);
    }

    public function getCollection(string $resourceClass, ?string $operationName = null, array $context = [])
    {

        return $this->profilRepository->findBy(['archiver' => false]);
    }
}

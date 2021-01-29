<?php
// api/src/DataProvider/BlogPostItemDataProvider.php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\ProfilSortie;
use App\Repository\ProfilSortieRepository;

final class ProfilSortieDataProvider implements ContextAwareCollectionDataProviderInterface, ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $profilSortie;

    public function __construct(ProfilSortieRepository $profilSortie)
    {
        $this->profilSortie = $profilSortie;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return ProfilSortie::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        // Retrieve the blog post item from somewhere then return it or null if not found
        return $this->profilSortie->findOneBy(['archiver' => false, 'id' => $id]);
    }

    public function getCollection(string $resourceClass, ?string $operationName = null, array $context = [])
    {

        return $this->profilSortie->findBy(['archiver' => false]);
    }
}

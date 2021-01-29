<?php
// api/src/DataProvider/BlogPostItemDataProvider.php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\GroupeCompetences;
use App\Repository\CompetencesRepository;
use App\Repository\GroupeCompetencesRepository;

final class GrpeCompetencesDataProvider implements ContextAwareCollectionDataProviderInterface, ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $_competencesRepository;
    private $_grpeCompetencesRepository;

    public function __construct(CompetencesRepository $competencesRepository, GroupeCompetencesRepository $groupeCompetencesRepository)
    {
        $this->_competencesRepository = $competencesRepository;
        $this->_grpeCompetencesRepository = $groupeCompetencesRepository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return GroupeCompetences::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        // Retrieve the blog post item from somewhere then return it or null if not found
        return $this->_grpeCompetencesRepository->findBy(['archiver' => false, 'id' => $id]);
    }

    public function getCollection(string $resourceClass, ?string $operationName = null, array $context = [])
    {

        return $this->_grpeCompetencesRepository->findBy(['archiver' => false]);
    }
}

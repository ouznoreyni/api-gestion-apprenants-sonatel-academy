<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\User;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *      routePrefix="/admin/users/",
 *      normalizationContext={"groups"={"apprenant:read"}},
 *      denormalizationContext={"groups"={"apprenant:write"}},
 *     attributes={
 *        "pagination_items_per_page"=10,
 *     },
 *     itemOperations={"GET", "PUT", "DELETE"}
 * )
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 */
class Apprenant extends User
{

}

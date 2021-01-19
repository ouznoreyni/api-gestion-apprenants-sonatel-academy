<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\User;
use App\Repository\FormateurRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     routePrefix="/admin/users",
 *      normalizationContext={"groups"={"formateur:read"}},
 *      denormalizationContext={"groups"={"formateur:write"}},
 *     attributes={
 *         "pagination_items_per_page"=10
 *     },
 *     itemOperations={"GET", "PUT", "DELETE"}
 * )
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 */
class Formateur extends User
{

}

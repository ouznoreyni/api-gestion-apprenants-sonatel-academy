<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\User;
use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * *@ApiResource(
 *      routePrefix="/admin/users",
 *      normalizationContext={"groups"={"admin:read"}},
 *      denormalizationContext={"groups"={"admin:write"}},
 *      attributes={
 *        "pagination_items_per_page"=10
 *       },
 *     itemOperations={
 *      "GET",
 *      "PUT"={"deserialize"=false},
 *      "DELETE"
 *      }
 * )
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 */
class Admin extends User
{

}

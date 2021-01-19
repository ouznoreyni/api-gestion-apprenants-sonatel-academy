<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\User;
use App\Repository\CommunityManagerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *      routePrefix="/admin/users",
 *      normalizationContext={"groups"={"cm:read"}},
 *      denormalizationContext={"groups"={"cm:write"}},
 *      attributes={
 *         "pagination_items_per_page"=10
 *      },
 *      collectionOperations={
 *        "GET"={"path"="/cm"},
 *        "POST"={"path"="/cm"}
 *      },
 *     itemOperations={
 *         "GET"={"path"="/cm/{id}"},
 *         "PUT"={"path"="/cm/{id}"},
 *         "DELETE"={"path"="/cm/{id}"}
 *       }
 * )
 * @ORM\Entity(repositoryClass=CommunityManagerRepository::class)
 */
class CommunityManager extends User
{

}

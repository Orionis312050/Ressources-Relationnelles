<?php
// src/Security/Role/RoleConverter.php
namespace App\Security\Role;

use Symfony\Component\Security\Core\Role\RoleInterface;
use App\Repository\RoleRepository;
use App\Entity\Role;

class RoleConverter
{
    public function roleToInt(array $role, RoleRepository $roleRepo): Role
    {
        switch ($role[0]) {
            case 'ROLE_USER':
                $role = $roleRepo->find(2);
                return $role;
            case 'ROLE_ADMIN':
                $role = $roleRepo->find(1);
                return $role;
            case 'ROLE_SUPERADMIN':
                $role = $roleRepo->find(4);
                return $role;
            case 'ROLE_MODERATOR':
                $role = $roleRepo->find(3);
                return $role;
            default:
                $role = $roleRepo->find(2);
                return $role;
        }
        // $roleName = str_replace('ROLE_', '', $role->getRole());
        // $user = $this->roleRepo->findOneBy(['name' => $roleName]);
        // if (!$user) {
        //     return 0;
        // }
        // return $user->getId();
    }

    public function intToRole(int $roleId): ?array
    {
        switch ($roleId) {
            case 2:
                return ['ROLE_USER'];
            case 1:
                return ['ROLE_ADMIN'];
            case 4:
                return ['ROLE_SUPERADMIN'];
            case 3:
                return ['ROLE_MODERATOR'];
            default:
                return ['ROLE_USER']; // Rôle inconnu
        }

        // $selectRole = $this->roleRepo->findOneBy(['id' => $roleId]);
        // if (!$selectRole) {
        //     return null;
        // }
        // return [new Role('ROLE_' . $selectRole->getRole())];
    }
}

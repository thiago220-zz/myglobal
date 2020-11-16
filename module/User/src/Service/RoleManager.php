<?php
namespace User\Service;

use User\Entity\Role;
use User\Entity\Permission;

/**
 * This service is responsible for adding/editing roles.
 */
class RoleManager
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;  
    
    /**
     * RBAC manager.
     * @var User\Service\RbacManager
     */
    private $rbacManager;
    
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $rbacManager) 
    {
        $this->entityManager = $entityManager;
        $this->rbacManager = $rbacManager;
    }
    
    /**
     * Adds a new role.
     * @param array $data
     */
    public function addRole($data)
    {
        $existingRole = $this->entityManager->getRepository(Role::class)
                ->findOneByName($data['name']);
        if ($existingRole!=null) {
            throw new \Exception('El rol con ese nombre ya existe');
        }
        
        $role = new Role;
        $role->setName($data['name']);
        $role->setDescription($data['description']);
        $role->setDateCreated(date('Y-m-d H:i:s'));

        // add parent roles to inherit
        $inheritedRoles = $data['inherit_roles'];
        if (!empty($inheritedRoles)) {
            foreach ($inheritedRoles as $roleId) {
                $parentRole = $this->entityManager->getRepository(Role::class)
                    ->findOneById($roleId);

                if ($parentRole == null) {
                    throw new \Exception('Función a heredar no encontrada');
                }

                if (!$role->getParentRoles()->contains($parentRole)) {
                    $role->addParent($parentRole);
                }
            }
        }
        
        $this->entityManager->persist($role);
        
        // Apply changes to database.
        $this->entityManager->flush();
        
        // Reload RBAC container.
        $this->rbacManager->init(true);
    }
    
    /**
     * Updates an existing role.
     * @param Role $role
     * @param array $data
     */
    public function updateRole($role, $data)
    {
        $existingRole = $this->entityManager->getRepository(Role::class)
                ->findOneByName($data['name']);
        if ($existingRole!=null && $existingRole!=$role) {
            throw new \Exception('Ya existe otro rol con tal nombre');
        }
        
        $role->setName($data['name']);
        $role->setDescription($data['description']);

        // clear parent roles so we don't populate database twice
        $role->clearParentRoles();

        // add the new parent roles to inherit
        $inheritedRoles = $data['inherit_roles'];
        if (!empty($inheritedRoles)) {
            foreach ($inheritedRoles as $roleId) {
                $parentRole = $this->entityManager->getRepository(Role::class)
                    ->findOneById($roleId);

                if ($parentRole == null) {
                    throw new \Exception('Función a heredar no encontrada');
                }

                if (!$role->getParentRoles()->contains($parentRole)) {
                    $role->addParent($parentRole);
                }
            }
        }

        $this->entityManager->flush();
        
        // Reload RBAC container.
        $this->rbacManager->init(true);
    }
    
    /**
     * Deletes the given role.
     */
    public function deleteRole($role)
    {
        $this->entityManager->remove($role);
        $this->entityManager->flush();
        
        // Reload RBAC container.
        $this->rbacManager->init(true);
    }
    
    /**
     * This method creates the default set of roles if no roles exist at all.
     */
    public function createDefaultRolesIfNotExist()
    {
        $role = $this->entityManager->getRepository(Role::class)
                ->findOneBy([]);
        if ($role!=null)
            return; // Some roles already exist; do nothing.
        
        $defaultRoles = [
            'Administrator' => [
                'description' => 'Persona que gestiona usuarios, roles, etc.',
                'parent' => null,
                'permissions' => [
                    'user.manage',
                    'role.manage',
                    'permission.manage',
                    'profile.any.view',
                ],
            ],
            'Guest' => [
                'description' => 'Una persona que puede iniciar sesión y ver su propio perfil.',
                'parent' => null,
                'permissions' => [
                    'profile.own.view',
                ],
            ],
        ];
        
        foreach ($defaultRoles as $name=>$info) {
            
            // Create new role
            $role = new Role();
            $role->setName($name);
            $role->setDescription($info['description']);
            $role->setDateCreated(date('Y-m-d H:i:s'));
            
            // Assign parent role
            if ($info['parent']!=null) {
                $parentRole = $this->entityManager->getRepository(Role::class)
                        ->findOneByName($info['parent']);
                if ($parentRole==null) {
                    throw new \Exception('Rol de padre ' . $info['parent'] . ' no existe');
                }
                
                $role->setParentRole($parentRole);
            }
            
            $this->entityManager->persist($role);
            
            // Assign permissions to role
            $permissions = $this->entityManager->getRepository(Permission::class)
                    ->findByName($info['permissions']);
            foreach ($permissions as $permission) {
                $role->getPermissions()->add($permission);
            }
        }
        
        // Apply changes to database.
        $this->entityManager->flush();
        
        // Reload RBAC container.
        $this->rbacManager->init(true);
    }
    
    /**
     * Retrieves all permissions from the given role and its child roles.
     * @param Role $role
     */
    public function getEffectivePermissions($role)
    {
        $effectivePermissions = [];

        foreach ($role->getParentRoles() as $parentRole)
        {
            $parentPermissions = $this->getEffectivePermissions($parentRole);
            foreach ($parentPermissions as $name=>$inherited) {
                $effectivePermissions[$name] = 'inherited';
            }
        }
        
        foreach ($role->getPermissions() as $permission)
        {
            if (!isset($effectivePermissions[$permission->getName()])) {
                $effectivePermissions[$permission->getName()] = 'own';
            }
        }
        
        return $effectivePermissions;
    }
    
    /**
     * Updates permissions of a role. 
     */
    public function updateRolePermissions($role, $data)
    {
        // Remove old permissions.
        $role->getPermissions()->clear();
        
        // Assign new permissions to role
        foreach ($data['permissions'] as $name=>$isChecked) {
            if (!$isChecked)
                continue;
            
            $permission = $this->entityManager->getRepository(Permission::class)
                ->findOneByName($name);
            if ($permission == null) {
                throw new \Exception('El permiso con tal nombre no existe');
            }
            
            $role->getPermissions()->add($permission);            
        }
        
        // Apply changes to database.
        $this->entityManager->flush();
        
        // Reload RBAC container.
        $this->rbacManager->init(true);
    }
}


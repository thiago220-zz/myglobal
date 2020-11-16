<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\Cliente;

// This is the custom repository class for Post entity.
class ClienteRepository extends EntityRepository {

    public function findAllClientes() {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('c')
                ->from(Cliente::class, 'c');

        return $queryBuilder->getQuery();
    }
    
    public function findAgenteById($id) {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('c')
                ->from(Cliente::class, 'c')
                ->where("c.user_id = {$id}");

        return $queryBuilder->getQuery();
    }

}

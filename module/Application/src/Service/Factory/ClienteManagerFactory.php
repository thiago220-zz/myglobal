<?php
namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Service\ClienteManager;

class ClienteManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, 
                    $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');        
        return new ClienteManager($entityManager);
    }
}

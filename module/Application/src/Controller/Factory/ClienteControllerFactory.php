<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Service\MailSender;
use Application\Service\ClienteManager;
use Application\Controller\ClienteController;

class ClienteControllerFactory
{
    public function __invoke(ContainerInterface $container, 
                             $requestedName, array $options = null)
    {
        $mailSender = $container->get(MailSender::class);
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $clienteManager = $container->get(ClienteManager::class);        
        return new ClienteController($mailSender,$entityManager, $clienteManager);
    }
}


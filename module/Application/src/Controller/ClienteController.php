<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Form\ContactoForm;
use Application\Entity\Cliente;
use Application\Form\ClienteForm;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Laminas\Paginator\Paginator;

class ClienteController extends AbstractActionController {

    private $mailSender;

    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Post manager.
     * @var Application\Service\ClienteManager 
     */
    private $clienteManager;

    public function __construct($mailSender, $entityManager, $clienteManager) {
        $this->mailSender = $mailSender;
        $this->entityManager = $entityManager;
        $this->clienteManager = $clienteManager;
    }

    public function indexAction() {
        $all = $this->entityManager->getRepository(Cliente::class)->findAll();
        $a = $this->entityManager->getRepository(Cliente::class)->findBy(['status' => 'aceptado']);
        $b = $this->entityManager->getRepository(Cliente::class)->findBy(['status' => 'activo']);
        $c = $this->entityManager->getRepository(Cliente::class)->findBy(['status' => 'espera']);
        $d = $this->entityManager->getRepository(Cliente::class)->findBy(['status' => 'rechazado']);

        return new ViewModel([
            'all' => count($all),
            'a' => count($a),
            'b' => count($b),
            'c' => count($c),
            'd' => count($d),
        ]);
    }

    public function contratosAction() {
        return new ViewModel();
    }

    public function nuevoAction() {
        $form = new ClienteForm();
        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            $data = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
            );
            $form->setData($data);
            if ($form->isValid()) {

                $data = $form->getData();
                $data['user_id'] = $this->identity()->getId();
                $id = $this->clienteManager->addNewCliente($data);
                $subject = "Nuevo cliente registrado por {$this->identity()->getEmail()}.";
                $body = "Equipo de operaciones, verifique el sistema para obtener acceso al nuevo cliente registrado. <a href='http://portal.myglobalux.com/cliente/detalles/{$id}'>Accede aquí a todos los detalles del contrato.</a>";
                $this->mailSender->sendMail($this->identity()->getEmail(), "operaciones@myglobalux.com", $subject, $body);
                return $this->redirect()->toRoute('cliente', ['action' => 'todos']);
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editarAction() {
        $clienteId = $this->params()->fromRoute('id', -1);
        $contratos = null;
        $form = new ClienteForm();

        $cliente = $this->entityManager->getRepository(Cliente::class)
                ->findOneById($clienteId);
        if ($cliente == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }


        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            $data = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
            );
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();
                $this->clienteManager->updateCliente($cliente, $data);
                return $this->redirect()->toRoute('cliente', ['action' => 'todos']);
            }
        } else {
            $data = [
                'nombre' => $cliente->getNombre(),
                'apellidos' => $cliente->getApellidos(),
                'status' => $cliente->getStatus(),
                'nif' => $cliente->getNIF(),
                'dni' => $cliente->getDNI(),
                'direccion' => $cliente->getDireccion(),
                'fecha_de_antiguedad' => $cliente->getFechaDeAntiguedad(),
                'telefono' => $cliente->getTelefono(),
                'n_cc' => $cliente->getNCC(),
                'tipo' => $cliente->getTipo(),
                'tipo_cliente' => $cliente->getTipoCliente(),
                'user_id' => $cliente->getUserId()
            ];
            
            $contratos = [$cliente->getContrato1(),$cliente->getContrato2(),$cliente->getContrato3()];

            $form->setData($data);
        }

        return new ViewModel([
            'form' => $form,
            'cliente' => $cliente,
            'contratos' => $contratos
        ]);
    }

    public function detallesAction() {
        $clienteId = $this->params()->fromRoute('id', -1);
        $cliente = $this->entityManager->getRepository(Cliente::class)
                ->findOneById($clienteId);
        return new ViewModel([
            'cliente' => $cliente,
            'clienteManager' => $this->clienteManager
        ]);
    }

    public function eliminarAction() {
        $clienteId = $this->params()->fromRoute('id', -1);
        $cliente = $this->entityManager->getRepository(Cliente::class)
                ->findOneById($clienteId);
        if ($cliente == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->clienteManager->removeCliente($cliente);
        return $this->redirect()->toRoute('cliente', ['action' => 'todos']);
    }

    public function contactoAction() {
        $form = new ContactoForm();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();
                $subject = $data['asunto'];
                $body = $data['cuerpo'];

                if (!$this->mailSender->sendMail($this->identity()->getEmail(), "operaciones@myglobalux.com", 
                                $subject, $body)) {
                    return $this->redirect()->toRoute('cliente',
                                    ['action' => 'error']);
                }

                // Redirect to "Thank You" page
                return $this->redirect()->toRoute('cliente',
                                ['action' => 'confirmacion']);
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function confirmacionAction() {
        return new ViewModel();
    }

    public function errorAction() {
        return new ViewModel();
    }

    public function todosAction() {
        if ($this->access('user.manage')) {
            $query = $this->entityManager->getRepository(Cliente::class)->findAllClientes();
        }else
        {
            $query = $this->entityManager->getRepository(Cliente::class)->findAgenteById($this->identity()->getId());            
        } 
        $page = $this->params()->fromQuery('page', 1);
        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(5);        
        $paginator->setCurrentPageNumber($page);
        return new ViewModel([
            'clientes' => $paginator,
            'clienteManager' => $this->clienteManager
        ]);
    }

}

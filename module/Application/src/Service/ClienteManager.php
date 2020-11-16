<?php

namespace Application\Service;

use Application\Entity\Cliente;
use Laminas\Filter\StaticFilter;

class ClienteManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    public function addNewCliente($data) {
        $cliente = new Cliente();
        $cliente->setNombre($data['nombre']);
        $cliente->setApellidos($data['apellidos']);
        $cliente->setNIF($data['nif']);
        $cliente->setDNI($data['dni']);
        $cliente->setDireccion($data['direccion']);
        $cliente->setFechaDeAntiguedad($data['fecha_de_antiguedad']);
        $cliente->setTelefono($data['telefono']);
        $cliente->setNCC($data['n_cc']);
        $cliente->setContrato1($data['contrato_1']['name']);
        $cliente->setContrato2($data['contrato_2']['name']);
        $cliente->setContrato3($data['contrato_3']['name']);
        $cliente->setTipo($data['tipo']);
        $cliente->setTipoCliente($data['tipo_cliente']);
        $cliente->setStatus($data['status']);
        $cliente->setUserId($data['user_id']);
        
        $this->entityManager->persist($cliente);
        $this->entityManager->flush();
        
        return $cliente->getId();
    }
    
    public function updateCliente($cliente, $data) 
    {
        $cliente->setNombre($data['nombre']);
        $cliente->setApellidos($data['apellidos']);
        $cliente->setNIF($data['nif']);
        $cliente->setDNI($data['dni']);
        $cliente->setDireccion($data['direccion']);
        $cliente->setFechaDeAntiguedad($data['fecha_de_antiguedad']);
        $cliente->setTelefono($data['telefono']);
        $cliente->setNCC($data['n_cc']);
        if($data['contrato_1']!= null){
            $cliente->setContrato1($data['contrato_1']['name']);
        }
        if($data['contrato_2']!= null){
            $cliente->setContrato2($data['contrato_2']['name']);
        }
        if($data['contrato_3']!= null){
            $cliente->setContrato3($data['contrato_3']['name']);
        }
        $cliente->setTipo($data['tipo']);
        $cliente->setTipoCliente($data['tipo_cliente']);
        $cliente->setStatus($data['status']);
        $cliente->setUserId($data['user_id']);
        
        $this->entityManager->flush();
    }

    public function removeCliente($cliente) {
        $this->entityManager->remove($cliente);
        $this->entityManager->flush();
    }

    /**
     * Returns status as a string.
     */
    public function getClienteStatusAsString($cliente) {
        switch ($cliente->getStatus()) {
            case Cliente::STATUS_ACEPTADO: return '<span class="text-primary">Aceptado</span>';
            case Cliente::STATUS_ACTIVO: return '<span class="text-success">Activo</span>';
            case Cliente::STATUS_ESPERA: return '<span class="text-warning">Espera</span>';
            case Cliente::STATUS_RECHAZADO: return '<span class="text-danger">Rechazado</span>';
        }
        return 'Desconocido';
    }

    /**
     * Returns type as a string.
     */
    public function getTypeAsString($cliente) {
        switch ($cliente->getTipo()) {
            case Cliente::TIPO_LUXE: return 'Contrato Luxe';
            case Cliente::TIPO_PAMM: return 'Contrato PAMM';
        }
        return 'Desconocido';
    }
    
    /**
     * Returns type as a string.
     */
    public function getClienteTypeAsString($cliente) {
        switch ($cliente->getTipoCliente()) {
            case Cliente::TIPO_C_AUTONOMO: return "Autonomo";
            case Cliente::TIPO_C_EMPRESA: return "Emrpesa";
        }
        return 'Desconocido';
    }
}

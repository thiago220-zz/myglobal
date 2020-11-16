<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\Application\Repository\ClienteRepository")
 * @ORM\Table(name="cliente")
 */
class Cliente
{    
  const STATUS_ACTIVO       = 'activo'; 
  const STATUS_ACEPTADO     = 'aceptado';
  const STATUS_ESPERA       = 'espera';
  const STATUS_RECHAZADO    = 'rechazado';
  
  const TIPO_LUXE = 'luxe';
  const TIPO_PAMM = 'pamm';
  
  const TIPO_C_AUTONOMO = "autonomo";
  const TIPO_C_EMPRESA = "empresa";  
  
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(name="id")   
   */
  protected $id;

  /** 
   * @ORM\Column(name="nombre")  
   */
  protected $nombre;

  /** 
   * @ORM\Column(name="apellidos")  
   */
  protected $apellidos;

  /** 
   * @ORM\Column(name="nif")  
   */
  protected $nif;
  
  /** 
   * @ORM\Column(name="dni")  
   */
  protected $dni;
  
  /** 
   * @ORM\Column(name="direccion")  
   */
  protected $direccion;

  /**
   * @ORM\Column(name="fecha_de_antiguedad")  
   */
  protected $fechaDeAntiguedad;   
  
  /**
   * @ORM\Column(name="telefono")  
   */
  protected $telefono;
  
  /**
   * @ORM\Column(name="n_cc")  
   */
  protected $nCC;
  
  /**
   * @ORM\Column(name="contrato_1")  
   */
  protected $contrato1;
  
  /**
   * @ORM\Column(name="contrato_2")  
   */
  protected $contrato2;
  
  /**
   * @ORM\Column(name="contrato_3")  
   */
  protected $contrato3;
  
  /**
   * @ORM\Column(name="tipo")  
   */
  protected $tipo;
  
  /**
   * @ORM\Column(name="tipo_cliente")  
   */
  protected $tipo_cliente;
  
  /**
   * @ORM\Column(name="status")  
   */
  protected $status;
  
  /**
   * @ORM\Column(name="user_id")  
   */
  protected $user_id;
  
  public function getId() 
  {
    return $this->id;
  }
  
  public function setId($id) 
  {
    $this->id = $id;
  }
  
  public function getUserId() 
  {
    return $this->user_id;
  }
  
  public function setUserId($id) 
  {
    $this->user_id = $id;
  }

  public function getNombre() 
  {
    return $this->nombre;
  }
  
  public function setNombre($nombre) 
  {
    $this->nombre = $nombre;
  }
  
  public function getApellidos() 
  {
    return $this->apellidos;
  }
  
  public function setApellidos($apellidos) 
  {
    $this->apellidos = $apellidos;
  }
  public function getDireccion() 
  {
    return $this->direccion;
  }
  
  public function setDireccion($direccion) 
  {
    $this->direccion = $direccion;
  }
    
  public function getNIF() 
  {
    return $this->nif;
  }
  
  public function setNIF($nif) 
  {
    $this->nif = $nif;
  }
  
  public function getDNI() 
  {
    return $this->dni;
  }
  
  public function setDNI($dni) 
  {
    $this->dni = $dni;
  }
  
  public function getFechaDeAntiguedad() 
  {
    return $this->fechaDeAntiguedad;
  }
  
  public function setFechaDeAntiguedad($fechaDeAntiguedad) 
  {
    $this->fechaDeAntiguedad = $fechaDeAntiguedad;
  }
  
  public function getTelefono() 
  {
    return $this->telefono;
  }
  
  public function setTelefono($telefono) 
  {
    $this->telefono = $telefono;
  }
  
  public function getNCC() 
  {
    return $this->nCC;
  }
  
  public function setNCC($ncc) 
  {
    $this->nCC = $ncc;
  }
  
  public function getContrato1() 
  {
    return $this->contrato1;
  }
  
  public function setContrato1($contrato1) 
  {
    $this->contrato1 = $contrato1;
  }
  
  public function getContrato2() 
  {
    return $this->contrato2;
  }
  
  public function setContrato2($contrato2) 
  {
    $this->contrato2 = $contrato2;
  }
  
  public function getContrato3() 
  {
    return $this->contrato3;
  }
  
  public function setContrato3($contrato3) 
  {
    $this->contrato3 = $contrato3;
  }
  
  public function getTipo() 
  {
    return $this->tipo;
  }
  
  public function setTipo($tipo) 
  {
    $this->tipo = $tipo;
  }
  
  public function getTipoCliente() 
  {
    return $this->tipo_cliente;
  }
  
  public function setTipoCliente($tipo_cliente) 
  {
    $this->tipo_cliente = $tipo_cliente;
  }
  
  
  public function getStatus() 
  {
    return $this->status;
  }
  
  public function setStatus($status) 
  {
    $this->status = $status;
  }
}
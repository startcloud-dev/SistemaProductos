<?php
class Producto{


    private $codigo;
    private $nombre;
    private $idBodega;
    private $idSucursal;
    private $idMoneda;
    private $precio;
    private $descripcion;
    private $materiales;

    
   public function __construct() {
        $this->materiales = []; 
    }



    public function getCodigo() {
        return $this->codigo;
    }
    public function setCodigo($codigo) {
        $this->codigo = trim($codigo);
    }

    public function getNombre() {
        return $this->nombre;
    }
    public function setNombre($nombre) {
        $this->nombre = trim($nombre);
    }

    public function getIdBodega() {
        return $this->idBodega;
    }
    public function setIdBodega($idBodega) {
        $this->idBodega = !empty($idBodega) ? (int)$idBodega : null;
    }

    public function getIdSucursal() {
        return $this->idSucursal;
    }
    public function setIdSucursal($idSucursal) {
        $this->idSucursal = !empty($idSucursal) ? (int)$idSucursal : null;
    }

    public function getIdMoneda() {
        return $this->idMoneda;
    }
    public function setIdMoneda($idMoneda) {
        $this->idMoneda = !empty($idMoneda) ? (int)$idMoneda : null;
    }

    public function getPrecio() {
        return $this->precio;
    }
    public function setPrecio($precio) {
        $this->precio = !empty($precio) ? (float)$precio : 0.0;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }
    public function setDescripcion($descripcion) {
        $this->descripcion = trim($descripcion);
    }

    public function getMateriales() {
        return $this->materiales;
    }
    public function setMateriales($materiales) {
        $this->materiales = is_array($materiales) ? $materiales : [];
    }

}
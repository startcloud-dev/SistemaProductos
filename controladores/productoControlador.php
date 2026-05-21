<?php
require_once '../modelos/Producto.php';
require_once '../config/Conexion.php';
class ProductoControlador{

    private $db;

  
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function cargarCombos()
    {
       try{
    
            $data = [
            'bodegas' => $this->db->query("SELECT idBodega, nombre FROM bodega ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC),
            'sucursales' => $this->db->query("SELECT idSucursal, nombre FROM sucursal ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC),
            'monedas' => $this->db->query("SELECT idMoneda, nombre FROM moneda ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC),
            'materiales' => $this->db->query("SELECT idMoneda, nombre FROM moneda ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC)
            ];
            echo json_encode($data);

       }catch(PDOException $e){
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Error de lectura SQL: " . $e->getMessage()]);
       }
    }


    
}
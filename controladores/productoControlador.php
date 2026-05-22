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
            'monedas' => $this->db->query("SELECT idMoneda, nombre FROM moneda ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC),
            'materiales' => $this->db->query("SELECT idMaterial, nombre FROM material ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC)
            ];
            echo json_encode($data);

       }catch(PDOException $e){
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Error de lectura SQL: " . $e->getMessage()]);
       }
    }

    public function cargarSucursales($idBodega)
    {
        try {
            if (empty($idBodega)) {
                echo json_encode([]);
                return;
            }

            $stmt = $this->db->prepare("SELECT idSucursal, nombre FROM sucursal WHERE idBodega = :idBodega ORDER BY nombre");
            $stmt->bindValue(':idBodega', $idBodega, PDO::PARAM_INT);
            $stmt->execute();
            $sucursales = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($sucursales);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Error al cargar sucursales: " . $e->getMessage()]);
        }
    }

    public function verificarCodigo() {
        try{
            $input = json_decode(file_get_contents("php://input"), true);
            $codigo = $input['codigo'] ?? '';
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM productos WHERE codigo = :codigo");
            $stmt->bindValue(':codigo', $codigo);
            $stmt->execute();
            $existe = $stmt->fetchColumn() > 0;
            echo json_encode(["existe" => $existe]);
        }catch(PDOException $e){
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Error de lectura SQL: " . $e->getMessage()]);
        }
    }

    public function guardarProducto()
    {
        try {
           
            $input = json_decode(file_get_contents("php://input"), true);

            if (!$input) {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Datos no válidos"]);
                return;
            }

      
            $producto = new Producto();
            $producto->setCodigo($input['codigo'] ?? '');
            $producto->setNombre($input['nombre'] ?? '');
            $producto->setIdBodega($input['idBodega'] ?? null);
            $producto->setIdSucursal($input['idSucursal'] ?? null);
            $producto->setIdMoneda($input['idMoneda'] ?? null);
            $producto->setPrecio($input['precio'] ?? 0);
            $producto->setDescripcion($input['descripcion'] ?? '');
            $producto->setMateriales($input['materiales'] ?? []);

           
            if (empty($producto->getCodigo()) || empty($producto->getNombre())) {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Código y nombre son obligatorios"]);
                return;
            }

          
            $this->db->beginTransaction();

          
            $sql = "INSERT INTO productos (codigo, nombre, idBodega, idSucursal, idMoneda, precio, descripcion)
                    VALUES (:codigo, :nombre, :idBodega, :idSucursal, :idMoneda, :precio, :descripcion)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':codigo', $producto->getCodigo());
            $stmt->bindValue(':nombre', $producto->getNombre());
            $stmt->bindValue(':idBodega', $producto->getIdBodega(), PDO::PARAM_INT);
            $stmt->bindValue(':idSucursal', $producto->getIdSucursal(), PDO::PARAM_INT);
            $stmt->bindValue(':idMoneda', $producto->getIdMoneda(), PDO::PARAM_INT);
            $stmt->bindValue(':precio', $producto->getPrecio());
            $stmt->bindValue(':descripcion', $producto->getDescripcion());
            $stmt->execute();

         
            if (!empty($producto->getMateriales())) {
                $sqlMaterial = "INSERT INTO productosmateriales (codigoProducto, idMaterial)
                                VALUES (:codigoProducto, :idMaterial)";
                $stmtMaterial = $this->db->prepare($sqlMaterial);

                foreach ($producto->getMateriales() as $nombreMaterial) {
                  
                    $stmtBuscar = $this->db->prepare("SELECT idMaterial FROM material WHERE LOWER(nombre) = LOWER(:nombre)");
                    $stmtBuscar->bindValue(':nombre', $nombreMaterial);
                    $stmtBuscar->execute();
                    $material = $stmtBuscar->fetch(PDO::FETCH_ASSOC);

                    if ($material) {
                        $stmtMaterial->bindValue(':codigoProducto', $producto->getCodigo());
                        $stmtMaterial->bindValue(':idMaterial', $material['idmaterial'], PDO::PARAM_INT);
                        $stmtMaterial->execute();
                    }
                }
            }

         
            $this->db->commit();

            http_response_code(201);
            echo json_encode(["status" => "success", "message" => "Producto guardado correctamente"]);

        } catch (PDOException $e) {
            $this->db->rollBack();
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Error al guardar: " . $e->getMessage()]);
        }
    }
}
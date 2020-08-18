<?php

/**
 * Clase MariaDB
 * 
 * Clase de conexión y manipulación de datos entre
 * motor MySQL/MariaDB y PHP
 * 
 */
class MariaDB
{
    //$pdo = atributo para instanciar a la clase PDO
    public $pdo;
    //$ultimoError = nos servirá para guardar e informar de los posibles errores 
    //en la conexión a la BD.
    public $ultimoError;
    //$conectado = atributo para validar si hay o no conexion a la base de datos
    public $conectado;

    /**
     * Constructor de la clase
     * 
     * @param string $servidor    IP o URL del servidor MySQL/MariaDB
     * @param string $puerto      Número de puerto de conexion (comúnmente 3306)
     * @param string $bd          Nombre de la base de datos a conectar
     * @param string $usuario     Nombre del usuario con privilegios a la base de dato
     * @param string $clave       Clave de acceso del usuario a la base de datos
     */
    public function __construct(
        string $servidor, 
        string $puerto, 
        string $bd,
        string $usuario,
        string $clave){
            $dsn = "mysql:host=$servidor;port=$puerto;dbname=$bd;charset=utf8";
            $opciones = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8",
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION
            );
            try {
                $this->pdo = new PDO($dsn, $usuario, $clave, $opciones);
                $this->conectado = true;
            } catch(\PDOException $x_X){
                $this->conectado = false;
                $this->ultimoError = $x_X->getMessage();
                exit;
            }
    }

    /**
     * Ejecuta una sentencia SQL
     * 
     * @param string $sql    Sentencia SQL a ejecutar.
     */
    public function ejecutar(string $sql){
        $resultados = array();
        $modo = explode(" ", $sql);
        if($this->conectado){
           $estamento = $this->pdo->prepare($sql);
           try{
                $estamento->execute();
           } catch(\PDOException $x_X){
                $this->ultimoError = $x_X->getMessage();
                return $this->ultimoError;
                exit;
           }
           switch($modo[0]) {
               case "SELECT":
                    $resultado = $estamento->fetchAll(PDO::FETCH_ASSOC);
               break;
               case "INSERT" :
                    $resultado = self::ejecutar("SELECT LAST_INSERT_ID() AS ID");
                break;
            }
           return $resultado;
        }
    }
}
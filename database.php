<?php

class Database extends PDO {

    public function __construct () {
        
        parent::__construct (DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
        parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Implements SELECT in database
     *
     * @param string $sql
     * @param array $array
     * @param string $fetchMode
     * @return void
     */
    public function select ($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC) {
        
        $stmt = $this -> prepare ($sql);

        foreach ($array as $key => $value)
            $stmt -> bindValue ("$key", $value);

        $stmt -> execute ();

        return $stmt -> fetchAll ($fetchMode);
    }

    /**
     * Implements INSERT in database
     *
     * @param string $table
     * @param array $data
     * @return void
     */
    public function insert ($table, $data) {
        
        ksort($data);
        
        $fieldNames = implode('`, `', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));
        
        $stmt = $this->prepare("INSERT INTO $table (`$fieldNames`) VALUES ($fieldValues)");
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        $stmt->execute();
    }

}
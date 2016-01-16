<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UpdateDB
 *
 * @author IT03
 */
class UpdateDB {

    private $query = array();

    public function UpdateTerstruktur($table, $set, $where) {
        //INISIALISASI SET VALUE
        $setValue = "";
        //INISIALISASI WHERE VALUE
        $whereValue = "";
        //HASIL QUERY
        $sql = "";
        foreach ($set as $key => $value) {
            $setValue .= $key . "='" . $value . "',";
        }
        $setValue = substr($setValue, 0, (strlen($setValue) - 1));

        foreach ($where as $key => $value) {
            $whereValue .= $key . "='" . $value . "',";
        }
        $whereValue = substr($whereValue, 0, (strlen($whereValue) - 1));

        if ($where == "") {
            $sql = "UPDATE $table MASTER_DRAWING SET $setValue";
        } else {
            $sql = "UPDATE $table MASTER_DRAWING SET $setValue WHERE $whereValue";
        }
        return $sql;//array_push($this->query, $sql);
    }
    
    public function UpdateSembarang(){
        
    }

    public function commitQuery() {
        foreach ($this->query as $value) {
            echo "$value";
        }
    }

}

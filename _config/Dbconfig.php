<?php

class Dbconfig {

    private $resposne = array();
    private $connWenmart;
    private $connLogistic;
    private $resultQuery;

    public function __construct() {
        $this->connWenmart = null;
        $this->connLogistic = null;
        $this->resultQuery = '';
    }

    public function WenmartConn() {
        $this->connWenmart = oci_connect("WELTESADMIN", "weltespass", "192.168.100.71/WENMART");
        return $this->connWenmart;
    }

    public function LogisticConn() {
        $this->connLogistic = oci_connect("WELTESADMIN", "weltespass", "192.168.100.68/WENLOGINV");
        return $this->connLogistic;
    }

    public function SelectFrom($sql, $conn) {
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row = mysql_fetch_array($query)) {
            array_push($this->resposne, $row);
        }
        return $this->resposne;
    }

    public function UpdateFrom($sql, $conn) {
        $parse = oci_parse($conn, $sql);
        $execute = oci_execute($parse);
        if ($execute) {
            $this->resultQuery = "SUKSES";
        } else {
            $this->resultQuery = "GAGAL" . oci_error();
        }
        return $this->resultQuery;
    }

    public function DeleteFrom() {
        $parse = oci_parse($conn, $sql);
        $execute = oci_execute($parse);
        if ($execute) {
            $this->resultQuery = "SUKSES";
        } else {
            $this->resultQuery = "GAGAL" . oci_error();
        }
        return $this->resultQuery;
    }

    public function InsertInto($conn, $sql) {
        $execute = oci_execute($parse);
        if ($execute) {
            $this->resultQuery .= "SUKSES";
        } else {
            $this->resultQuery .= "GAGAL" . oci_error();
        }
        return $this->resultQuery;
    }

}

<?php

function SingleQryFld($sql, $conn) {
    // require_once '../dbinfo.inc.php';
    // GENERATE THE APPLICATION PAGE
    // $conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
    // echo "$sql<br>";
    $sqlSqry = oci_parse($conn, $sql);
    oci_execute($sqlSqry);
    $rowSqry = oci_fetch_array($sqlSqry);
    // echo $rowSqry[0];
    return $rowSqry[0];
}

function HakAksesUser($username, $nm_field, $conn) {
    $sql = "SELECT $nm_field FROM WELTES_SEC_ADMIN.WELTES_AUTHENTICATION AUT LEFT OUTER JOIN WELTES_SEC_ADMIN.WELTES_AUTH_LEVEL LEV ON LEV.APP_USR_CODE=AUT.APP_USR_CODE WHERE APP_USERNAME = :UN_BV ";
    $sqlSqry = oci_parse($conn, $sql);
    oci_bind_by_name($sqlSqry, ":UN_BV", $username);
    oci_execute($sqlSqry);
    $rowSqry = oci_fetch_array($sqlSqry);
    // echo $rowSqry[0];
    return $rowSqry[0];
}

function nolnoldidepan($value, $places) {
    if (is_numeric($value)) {
        $leading = "";
        for ($x = 1; $x <= $places; $x++) {
            $ceiling = pow(10, $x);
            // echo "$ceiling >> $value --- ";
            if ($value < $ceiling) {
                $zeros = $places - $x;
                // echo "$zeros = $places - $x";
                for ($y = 1; $y <= $zeros; $y++) {
                    $leading .= "0";
                }
                // echo " [$leading] ";
                $x = $places + 1;
            }
        }
        $output = $leading . $value;
        // echo " (($output))";
    } else {
        $output = $value;
    }
    return $output;
}

function PO_NO_generate($type_po, $datePO, $grandtot, $conn) {
    $newDatePO = new dateTime($datePO);
    $kdAwal = $newDatePO->format('md-');
    $kdAwal_thn = $newDatePO->format('y');
    $grandtot = floatval($grandtot);
    // }
    if ($type_po == 'NONVAT') {
        $emptyPOSQL = "SELECT MIN(PO_NO) FROM MST_PO WHERE PO_NO LIKE '$kdAwal%' AND PO_STAT = 'INACT' AND PO_TYPE='$type_po' "
                . "AND TO_NUMBER(SUBSTR(PO_NO,6,2)) > 50 AND TO_NUMBER(SUBSTR(PO_NO,6,2)) <= 70 AND SUBSTR(PO_NO,(length(PO_NO)-1),2) = '" . $kdAwal_thn . "' ";

        $cekPOIdSql = "SELECT MAX(PO_NO) FROM MST_PO WHERE PO_NO LIKE '$kdAwal%' AND PO_STAT = 'ACT' AND PO_TYPE='$type_po' "
                . "AND TO_NUMBER(SUBSTR(PO_NO,6,2)) > 50 AND TO_NUMBER(SUBSTR(PO_NO,6,2)) <= 70 AND SUBSTR(PO_NO,(length(PO_NO)-1),2) = '" . $kdAwal_thn . "' ";
        if ($grandtot > 3000000) {
            $emptyPOSQL = "SELECT MIN(PO_NO) FROM MST_PO WHERE PO_NO LIKE '$kdAwal%' AND PO_STAT = 'INACT' AND PO_TYPE='$type_po' "
                    . "AND TO_NUMBER(SUBSTR(PO_NO,6,2)) > 70 AND SUBSTR(PO_NO,(length(PO_NO)-1),2) = '" . $kdAwal_thn . "' ";

            $cekPOIdSql = "SELECT MAX(PO_NO) FROM MST_PO WHERE PO_NO LIKE '$kdAwal%' AND PO_STAT = 'ACT' AND PO_TYPE='$type_po' "
                    . "AND TO_NUMBER(SUBSTR(PO_NO,6,2)) > 70 AND SUBSTR(PO_NO,(length(PO_NO)-1),2) = '" . $kdAwal_thn . "' ";
        }
    } else {
        $emptyPOSQL = "SELECT MIN(PO_NO) FROM MST_PO WHERE PO_NO LIKE '$kdAwal%' AND PO_STAT = 'INACT' AND PO_TYPE='$type_po' "
                . "AND TO_NUMBER(SUBSTR(PO_NO,6,2)) <= 50 AND SUBSTR(PO_NO,(length(PO_NO)-1),2) = '" . $kdAwal_thn . "' ";

        $cekPOIdSql = "SELECT MAX(PO_NO) FROM MST_PO WHERE PO_NO LIKE '$kdAwal%' AND PO_STAT = 'ACT' AND PO_TYPE='$type_po' "
                . "AND TO_NUMBER(SUBSTR(PO_NO,6,2)) <= 50 AND SUBSTR(PO_NO,(length(PO_NO)-1),2) = '" . $kdAwal_thn . "' ";
    }

    $emptyPO = SingleQryFld($emptyPOSQL, $conn);
    if ($emptyPO <> "") {
        return $emptyPO;
    } else {
//     echo "$cekPOIdSql<br>";
        $cekPOIdParse = oci_parse($conn, $cekPOIdSql);
        //oci_bind_by_name($cekPOIdParse, ":PONOMAX", $poNo);
        oci_execute($cekPOIdParse);
        $cekPOId = oci_fetch_array($cekPOIdParse)[0];
        // echo "$cekPOId - $type_po";
        if ($cekPOId == "" and $type_po == "VAT") {
            $num = 1;
            $num = str_pad($num, 2, "0", STR_PAD_LEFT);
        } elseif ($cekPOId == "" and $type_po == "NONVAT") {
            if ($grandtot > 3000000) {
                $num = 71;
            } else {
                $num = 51;
            }
        } else {
            $num = substr($cekPOId, 5);
            $num = substr($num, 0, -7);
            $num++;
            $num = str_pad($num, 2, "0", STR_PAD_LEFT);
        }
        //echo "$num";

        return $kdAwal . $num . '/WEN/' . $kdAwal_thn;
    }
}

function MTO_ID_generate($conn) {
    $MTO_id = "MTO/" . date("y") . "/";

    $sql = "SELECT NVL(MAX(MTO_ID),0) MTO_ID FROM MST_MTO WHERE MTO_ID LIKE '$MTO_id%'";
//        echo $sql;
    $parse = oci_parse($conn, $sql);
    oci_execute($parse);
    $value = oci_fetch_array($parse)['MTO_ID'];
    $value = intval(substr($value, strlen($value) - 4, 4)) + 1;
    $values = str_pad(strval($value), 4, "0", STR_PAD_LEFT);
    $MTO_id = $MTO_id . "$values";

    return $MTO_id;
}

function PR_ID_generate($conn) {
    $PR_id = "PR/" . date("y") . "/";

    $sql = "SELECT NVL(MAX(PR_ID),0) PR_ID FROM MST_PR WHERE PR_ID LIKE '$PR_id%'";
//        echo $sql;
    $parse = oci_parse($conn, $sql);
    oci_execute($parse);
    $value = oci_fetch_array($parse)['PR_ID'];
    $value = intval(substr($value, strlen($value) - 4, 4)) + 1;
    $values = str_pad(strval($value), 4, "0", STR_PAD_LEFT);
    $PR_id = $PR_id . "$values";

    return $PR_id;
}

function terbilang($angka) {
    $angka = (float) $angka;
    $bilangan = array(
        '',
        'satu',
        'dua',
        'tiga',
        'empat',
        'lima',
        'enam',
        'tujuh',
        'delapan',
        'sembilan',
        'sepuluh',
        'sebelas'
    );

    if ($angka < 12) {
        return $bilangan[$angka];
    } else if ($angka < 20) {
        return $bilangan[$angka - 10] . ' belas';
    } else if ($angka < 100) {
        $hasil_bagi = (int) ($angka / 10);
        $hasil_mod = $angka % 10;
        return trim(sprintf('%s puluh %s', $bilangan[$hasil_bagi], $bilangan[$hasil_mod]));
    } else if ($angka < 200) {
        return sprintf('seratus %s', terbilang($angka - 100));
    } else if ($angka < 1000) {
        $hasil_bagi = (int) ($angka / 100);
        $hasil_mod = $angka % 100;
        return trim(sprintf('%s ratus %s', $bilangan[$hasil_bagi], terbilang($hasil_mod)));
    } else if ($angka < 2000) {
        return trim(sprintf('seribu %s', terbilang($angka - 1000)));
    } else if ($angka < 1000000) {
        $hasil_bagi = (int) ($angka / 1000); // karena hasilnya bisa ratusan jadi langsung digunakan rekursif
        $hasil_mod = $angka % 1000;
        return sprintf('%s ribu %s', terbilang($hasil_bagi), terbilang($hasil_mod));
    } else if ($angka < 1000000000) {

        // hasil bagi bisa satuan, belasan, ratusan jadi langsung kita gunakan rekursif
        $hasil_bagi = (int) ($angka / 1000000);
        $hasil_mod = $angka % 1000000;
        return trim(sprintf('%s juta %s', terbilang($hasil_bagi), terbilang($hasil_mod)));
    } else if ($angka < 1000000000000) {
        // bilangan 'milyaran'
        $hasil_bagi = (int) ($angka / 1000000000);
        $hasil_mod = fmod($angka, 1000000000);
        return trim(sprintf('%s milyar %s', terbilang($hasil_bagi), terbilang($hasil_mod)));
    } else if ($angka < 1000000000000000) {
        // bilangan 'triliun'                           
        $hasil_bagi = $angka / 1000000000000;
        $hasil_mod = fmod($angka, 1000000000000);
        return trim(sprintf('%s triliun %s', terbilang($hasil_bagi), terbilang($hasil_mod)));
    } else {
        return 'Wow...';
    }
}

?>
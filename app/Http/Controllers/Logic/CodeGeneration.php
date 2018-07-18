<?php namespace App\Http\Controllers\Logic;

use DB;

class CodeGeneration {

    public static function newCode($table, $field, $length, $prefix){
        $obj = new CodeGeneration();
        return $obj->create($table, $field, $length, $prefix);
    }

    public function create($table, $field, $length, $prefix){
        $sqlArr = array(
            'table' => $table,
            'fields' => array('id', $field)
        );
        $code = $this->getLastCode($sqlArr, false);
        $currentYear = date('y');
        if(strlen($code) > $length){
            list($year, $id) = preg_split('/'.$prefix.'/' , $code);
            $numId = (int) $id;
            $numYear = (int) $year;
            if ($numId == $this->get9NumberByLen($len)) {
                $numId = 0;
                $currentYear = $numYear + 1;
            }
            return $currentYear . $prefix . $this->getIdString($numId + 1, $len);
        }else{
            return $currentYear . $prefix . $this->getIDString($code, $length);
        }
    }

    private function get9NumberByLen($len){
        $numStr = '';
        for ($i = 1; $i <= $len; $i++) {
            $numStr = $numStr . '9';
        }
        return (int) $numStr;
    }

    private function getIDString($numID, $len){
        $str = '';
        for($i=0; $i<=$len-strlen($numID);$i++){
            $str = $str . '0';
        }
        return $str . $numID;
    }

    private function getLastCode($sqlArr, $sort = false){

        $tableName = $sqlArr['table'];
        $fieldID = $sqlArr['fields'][0];
        $fieldCode = $sqlArr['fields'][1];
        $sortField = $fieldID;
        if($sort) $sortField = $fieldCode;
        $sqlString = 'SELECT ' . $fieldID . ',' . $fieldCode . ' FROM ' . $tableName . ' order by ' . $sortField . ' DESC LIMIT 0 ,1';
        $results = DB::select( DB::raw($sqlString) );
        $code = 1;
        if (count($results) > 0) {
            $code = $results[0]->$fieldCode;
        }
        return $code;
    }

}

?>
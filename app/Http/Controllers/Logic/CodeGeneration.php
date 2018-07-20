<?php namespace App\Http\Controllers\Logic;

use DB;

class CodeGeneration {

    private $datetime;

    public function __construct(IDateTime $dt = null){
        $this->datetime = $dt ?: new ClsDateTime();
    }

    public static function newCode($table, $field, $length, $prefix){
        $obj = new CodeGeneration();
        return $obj->create($table, $field, $length, $prefix);
    }

    public function create($table, $field, $length, $prefix){
        $tableAndField = array(
            'table' => $table,
            'fields' => array('id', $field)
        );
        //Get last code from table inside of database
        $lastCode = $this->getLastCode($tableAndField, $length, $prefix, false);
        $currentYear = $this->datetime->getCurrentYear();
        list($year, $number) = preg_split('/'. $prefix .'/' , $lastCode);
        //Year of last code equal current year
        if((int) $year == $currentYear){
            return $this->formatOrderNumber($currentYear, $prefix, (int) $number + 1, $length, '0');
        }else{
            return $this->formatOrderNumber(($year + 1), $prefix, 1, $length, '0');
        }
    }
    //Format order number based on length and char format
    //Example: number = 1, length = 6 => Result: 000001
    private function formatOrderNumber($year, $prefix, $number, $length, $charFormat){
        return $year . $prefix . str_pad($number, $length, $charFormat, STR_PAD_LEFT);
    }
    //Function to get last code from table in database
    private function getLastCode($tableAndField, $length, $prefix, $sort = false){
        $tableName = $tableAndField['table'];
        $fieldID = $tableAndField['fields'][0];
        $fieldCode = $tableAndField['fields'][1];
        $sortField = $fieldID;
        if($sort) $sortField = $fieldCode;
        $sqlString = 'SELECT ' . $fieldID . ',' . $fieldCode . ' FROM ' . $tableName . ' order by ' . $sortField . ' DESC LIMIT 0 ,1';
        $results = DB::select(DB::raw($sqlString));
        if (count($results) > 0) {
            return $results[0]->$fieldCode;
        }
        return $this->formatOrderNumber($this->datetime->getCurrentYear(), $prefix, 0, $length, '0');
    }

}

?>
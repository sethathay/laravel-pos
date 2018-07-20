<?php namespace App\Http\Controllers\Logic;

class ClsDateTime implements IDateTime{

    public function getCurrentYear(){
        return date('y');
    }

    public function getCurrentDateTime(){
        return date();
    }

}
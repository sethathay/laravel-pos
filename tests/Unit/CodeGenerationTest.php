<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\Logic\CodeGeneration;
use App\Http\Controllers\Logic\ClsDateTime;
use DateTime;

class CodeGenerationTest extends TestCase
{
    /**
     * A basic test of getLastCode function.
     * Condition current year equals year in last code selected
     * from database
     * @return lastCode
     */
    public function testFuncCreate_isTheCurrentYear()
    {
        $obj = new CodeGeneration();
        $expectedResult = "18SO000003";
        $actualResult = $obj->create("tb_sale_orders","code",6,"SO"); 
        $this->assertSame($expectedResult, $actualResult);
    }

    //Condition current year is different from last code
    public function testFuncCreate_isNotCurrentYear(){
        //Create a stub of ClsDateTime class
        $fakeDateTime = $this->createMock(ClsDateTime::class);
        $fakeData = new DateTime('2019-01-01');
        //Configure the stub
        $fakeDateTime->method('getCurrentYear')->willReturn($fakeData->format('y'));
        $obj = new CodeGeneration($fakeDateTime);
        $expectedResult = "19SO000001";
        $actualResult = $obj->create("tb_sale_orders","code",6,"SO");
        $this->assertSame($expectedResult, $actualResult);
    }
}

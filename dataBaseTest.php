<?php
require_once "tdd.php";

use PHPUnit\Framework\TestCase;

class dataBaseTest extends TestCase
{

    private $database;

    /**
     * @before
     */
    public function 前処理()
    {
        $this->database = new database();
    }

    /**
     * @test
     * @testWith ["MS SQL Server", 919000]
     *           ["Oracle Database", 2100000]
     */
    public function データベースの販売価格を返す($product_name, $product_price)
    {
        echo $product_name . ":" . $product_price;
        $this->assertEquals($product_price, $this->database->get_price($product_name));
    }

    /**
     * @test
     * @testdox データベースの収益認識日は契約日、60日後、120日後である
     * @testWith ["MS SQL Server", [0, 60, 120]]
     *           ["Oracle Database", [0, 60, 120]]
     */
    public function データベースの収益認識予定日の一覧を返す($product_name, $revenue_recognition_days_list)
    {
        $this->assertEquals($revenue_recognition_days_list, $this->database->get_revenue_recognition_days_list($product_name));
    }

    /**
     * @test
     * @testdox データベースは契約日に売上の1/3、60日後に1/3を120日後に1/3を収益認識する
     *          割り切れないので初日に端数を寄せる
     * @testWith ["MS SQL Server", 0, 306334]
     *           ["MS SQL Server", 59, 0]
     *           ["MS SQL Server", 60, 306333]
     *           ["MS SQL Server", 119, 0]
     *           ["MS SQL Server", 120, 306333]
     *           ["MS SQL Server", 121, 0]
     *           ["Oracle Database", 0, 700000]
     *           ["Oracle Database", 59, 0]
     *           ["Oracle Database", 60, 700000]
     *           ["Oracle Database", 119, 0]
     *           ["Oracle Database", 120, 700000]
     *           ["Oracle Database", 121, 0]
     */
    public function データベースの契約日から指定された日付分の後日の収益認識額を返す($product_name, $target_days, $revenue_recognition_price)
    {
        $this->assertEquals($revenue_recognition_price, $this->database->get_revenue_recognition_price($product_name, $target_days));
    }

    /**
     * @test
     * @testWith ["MS SQL Server"]
     * @testWith ["Oracle Database"]
     */
    public function totalの収益認識額と販売額が同一であることを確認する($product_name)
    {
        $product_price = $this->database->get_price($product_name);
        $revenue_recognition_days_list = $this->database->get_revenue_recognition_days_list($product_name);

        $total_revenue_recognition_price = 0;
        foreach ($revenue_recognition_days_list as $target_days) {
            $total_revenue_recognition_price += $this->database->get_revenue_recognition_price($product_name, $target_days);
        }

        $this->assertEquals($product_price, $total_revenue_recognition_price);
    }
}

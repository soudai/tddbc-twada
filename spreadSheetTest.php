<?php
require_once "tdd.php";

use PHPUnit\Framework\TestCase;

class spreadSheetTest extends TestCase
{

    private $spreadSheet;

    /**
     * @before
     */
    public function 前処理()
    {
        $this->spreadSheet = new spreadSheet();
    }

    /**
     * @test
     * @testWith ["MS Excel", 27800]
     * @testWith ["三四郎", 5000]
     *
     */
    public function スプレッドシートの販売価格を返す($product_name, $product_price)
    {
        echo $product_name . ":" . $product_price;
        $this->assertEquals($product_price, $this->spreadSheet->get_price($product_name));
    }

    /**
     * @test
     * @testdox スプレッドシートの収益認識日は契約日から30日後である
     * @testWith ["MS Excel", [0,30]]
     *           ["MS Excel", [0,30]]
     */
    public function スプレッドシートの収益認識予定日の一覧を返す($product_name, $revenue_recognition_days_list)
    {
        $this->assertEquals($revenue_recognition_days_list, $this->spreadSheet->get_revenue_recognition_days_list($product_name));
    }

    /**
     * @test
     * @testdox スプレッドシートは契約日に売上の2/3、30日後に1/3を収益認識する
     *          割り切れないので初日に端数を寄せる
     * @testWith ["MS Excel", 0, 9268]
     *           ["MS Excel", 29, 0]
     *           ["MS Excel", 30, 18532]
     *           ["MS Excel", 31, 0]
     *           ["三四郎", 0, 1668]
     *           ["三四郎", 29, 0]
     *           ["三四郎", 30, 3332]
     *           ["三四郎", 31, 0]
     */
    public function スプレッドシートの契約日から指定された日付分の後日の収益認識額を返す($product_name, $target_days, $revenue_recognition_price)
    {
        $this->assertEquals($revenue_recognition_price, $this->spreadSheet->get_revenue_recognition_price($product_name, $target_days));
    }

    /**
     * @test
     * @testWith ["MS Excel"]
     * @testWith ["三四郎"]
     */
    public function totalの収益認識額と販売額が同一であることを確認する($product_name)
    {
        $product_price = $this->spreadSheet->get_price($product_name);
        $revenue_recognition_days_list = $this->spreadSheet->get_revenue_recognition_days_list($product_name);

        $total_revenue_recognition_price = 0;
        foreach ($revenue_recognition_days_list as $target_days) {
            $total_revenue_recognition_price += $this->spreadSheet->get_revenue_recognition_price($product_name, $target_days);
        }

        $this->assertEquals($product_price, $total_revenue_recognition_price);
    }
}

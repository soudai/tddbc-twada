<?php
require_once "tdd.php";

use PHPUnit\Framework\TestCase;

class wordProcessorTest extends TestCase
{

    private $wordProcessor;

    /**
     * @before
     */
    public function 前処理()
    {
        $this->wordProcessor = new wordProcessor();
    }

    /**
     * @test
     * @testWith ["MS Word", 18800]
     *           ["一太郎", 20000]
     */
    public function ワードプロセッサの販売価格を返す($product_name, $product_price)
    {
        $this->assertEquals($product_price, $this->wordProcessor->get_price($product_name));
    }

    /**
     * @test
     * @testdox ワードプロセッサは契約日と収益認識日は同日である
     * @testWith ["MS Word", "2021-01-25"]
     *           ["一太郎", "2021-01-27"]
     */

    /**
     * @test
     * @testdox ワードプロセッサの収益認識日は契約日から30日後である
     * @testWith ["MS Word", [0]]
     * @testWith ["一太郎", [0]]
     */
    public function ワードプロセッサの収益認識予定日の一覧を返す($product_name, $revenue_recognition_days_list)
    {
        $this->assertEquals($revenue_recognition_days_list, $this->wordProcessor->get_revenue_recognition_days_list($product_name));
    }

    /**
     * @test
     * @testdox ワードプロセッサは契約日に売上の全てを収益認識する
     * @testWith ["MS Word", 0, 18800]
     *           ["MS Word", 29, 0]
     *           ["一太郎", 0, 20000]
     *           ["一太郎", 29, 0]
     */
    public function ワードプロセッサの契約日から指定された日付分の後日の収益認識額を返す($product_name, $target_days, $revenue_recognition_price)
    {
        $this->assertEquals($revenue_recognition_price, $this->wordProcessor->get_revenue_recognition_price($product_name, $target_days));
    }

    /**
     * @test
     * @testWith ["MS Word"]
     * @testWith ["一太郎"]
     */
    public function totalの収益認識額と販売額が同一であることを確認する($product_name)
    {
        $product_price = $this->wordProcessor->get_price($product_name);
        $revenue_recognition_days_list = $this->wordProcessor->get_revenue_recognition_days_list($product_name);

        $total_revenue_recognition_price = 0;
        foreach ($revenue_recognition_days_list as $target_days) {
            $total_revenue_recognition_price += $this->wordProcessor->get_revenue_recognition_price($product_name, $target_days);
        }

        $this->assertEquals($product_price, $total_revenue_recognition_price);
    }
}

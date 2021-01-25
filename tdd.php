<?php

class product
{
    protected $product;

    public function get_price($product_name)
    {
        return $this->product[$product_name]['price'];
    }

    public function get_revenue_recognition_days_list($product_name)
    {
        $revenue_recognition_days_list = array_keys($this->product[$product_name]['revenue_recognition_price']);

        return $revenue_recognition_days_list;
    }

    public function get_revenue_recognition_price($product_name, $target_days)
    {
        if (empty($this->product[$product_name]['revenue_recognition_price'][$target_days])) {
            return 0;
        }
        return $this->product[$product_name]['revenue_recognition_price'][$target_days];
    }
}

class wordProcessor extends product
{
    public $product =
        [
            'MS Word' =>
                [
                    'price' => 18800,
                    'revenue_recognition_price' => [
                        0 => 18800,
                    ]
                ],
            '一太郎' =>
                [
                    'price' => 20000,
                    'revenue_recognition_price' => [
                        0 => 20000,
                    ]
                ]
        ];
}

class spreadSheet extends product
{
    public $product =
        [
            'MS Excel' =>
                [
                    'price' => 27800,
                    'revenue_recognition_price' => [
                        0 => 9268,
                        30 => 18532,
                    ]
                ],
            '三四郎' =>
                [
                    'price' => 5000,
                    'revenue_recognition_price' => [
                        0 => 1668,
                        30 => 3332,
                    ]
                ],
        ];
}

class dataBase extends product
{
    public $product =
        [
            'MS SQL Server' =>
                [
                    'price' => 919000,
                    'revenue_recognition_price' => [
                        0 => 306334,
                        60 => 306333,
                        120 => 306333,
                    ]
                ],
            'Oracle Database' =>
                [
                    'price' =>  2100000,
                    'revenue_recognition_price' => [
                        0 => 700000,
                        60 => 700000,
                        120 => 700000,
                    ]
                ],
        ];
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                [
                    'product_name' => 'Product 1',
                    'price' => 50000,
                    'quantity' => 2,
                ],
                [
                    'product_name' => 'Product 2',
                    'price' => 100000,
                    'quantity' => 1,
                ],
                [
                    'product_name' => 'Product 3',
                    'price' => 150000,
                    'quantity' => 3,
                ],
            ],
            [
                [
                    'product_name' => 'Product 4',
                    'price' => 200000,
                    'quantity' => 2,
                ],
                [
                    'product_name' => 'Product 5',
                    'price' => 250000,
                    'quantity' => 1,
                ],
                [
                    'product_name' => 'Product 6',
                    'price' => 300000,
                    'quantity' => 3,
                ],
            ],
            [
                [
                    'product_name' => 'Product 7',
                    'price' => 350000,
                    'quantity' => 2,
                ],
                [
                    'product_name' => 'Product 8',
                    'price' => 400000,
                    'quantity' => 1,
                ],
                [
                    'product_name' => 'Product 9',
                    'price' => 450000,
                    'quantity' => 3,
                ],
            ]
        ];

        foreach ($items as $key => $item) {
            $orderId = $this->generateOrderId();
            $totalPrice = 0;

            foreach ($item as $product) {
                $totalPrice += $product['price'] * $product['quantity'];
            }

            $order = [
                'order_id' => $orderId,
                'total_price' => $totalPrice,
            ];

            $order = \App\Models\Order::create($order);

            foreach ($item as $product) {
                $order->items()->create($product);
            }
        }
    }

    protected function generateOrderId(): string
    {
        return 'ORD' . mt_rand(1000, 9999) . time();
    }
}

<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerOrder;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\UserData;
use Illuminate\Database\Seeder;
use Throwable;

class UserDatasSeeder extends Seeder
{

    public function run()
    {
        // $idUsers = Customer::pluck('id')->toArray();
        // $idUserOrders = OrderDetail::pluck('customer_order_id')->unique()->toArray();
        // $idUserOrders = OrderDetail::join('customer_orders', 'customer_orders.id', '=', 'order_details.customer_order_id')
        //                 ->where('customer_orders.order_status', 1)
        //                 ->pluck('customer_order_id')->unique()->toArray();

        $idUserOrders = CustomerOrder::where('customer_orders.order_status', 1)
        ->pluck('customer_id')->unique()->toArray();

        // $common_orders = array_intersect($idUsers, $idUserOrders);  
        $idProducts = Product::pluck('id')->unique()->toArray();

        foreach($idUserOrders as $index => $idUserOrder) {
            $idCustomerOrder = CustomerOrder::where('customer_id',$idUserOrder)->pluck('id')->unique()->toArray();
            $boughtIds = OrderDetail::whereIn('customer_order_id', $idCustomerOrder)->pluck('product_id')->unique()->toArray();
            
            $remainingProducts = array_values(array_diff($idProducts, $boughtIds));
            if (count($remainingProducts) >= 2) {
                $n = rand(2, count($remainingProducts));
                if($n>5) $n = 5; 
                $randomKeys = array_rand($remainingProducts, $n);
                $randomRecentAdd = array();
                foreach ($randomKeys as $key) {
                    $randomRecentAdd[] = $remainingProducts[$key];
                }
            }
            else $randomRecentAdd = [] ;

            if (count($remainingProducts) >= 2) {
                $remainingProducts = array_values(array_diff($remainingProducts, $randomRecentAdd));
            }

            if (count($remainingProducts) >= 2) {
                $n = rand(2, count($remainingProducts));
                if($n>5) $n = 5; 
                $randomKeys = array_rand($remainingProducts, $n);
                $randomRecentCare = array();
                foreach ($randomKeys as $key) {
                    $randomRecentCare[] = $remainingProducts[$key];
                }
            }
            else $randomRecentCare = [] ;

            sort($randomRecentCare);
            sort($randomRecentAdd);
            sort($boughtIds);

            $data = [
                'id_user' => $idUserOrder,
                'recent_care' => json_encode($randomRecentCare),
                'recent_add' => json_encode([]), # vì đây là dữ liệu giỏ hàng hiện tại , nên không seed 
                'recent_buy' => json_encode($boughtIds)
            ];
            UserData::create($data);
        }
    }
}

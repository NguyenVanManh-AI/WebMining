<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderDetail;
use App\Models\Image; // xóa product đi thì xóa ảnh của nó đi luôn (không giữ lại nặng máy)
use App\Models\ImportDetail; // xóa product đi thì cũng set cho product_id của import_detail về null
use App\Models\UserData;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UserDataController extends Controller
{
    public function recommendProduct(Request $request){
        $id_user = $request->id_user;
        $response = Http::get('http://localhost:8000/rs', ['id_user' => $id_user]);
        $data = null;
        if ($response->successful()) {
            $data = $response->json();
            $products = $data['recommend_products'];
            $products = Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->whereIn('products.id', $products)
            ->select('products.*', 'categories.name as name_category')
            ->get();
            foreach($products as $product) {
                $images = Image::where('product_id', $product->id)->get();
                $product->images = $images;
            }
        }
        else $products = [];
        return response()->json([
            'message' => 'Get a list of successful product suggestions !',
            'products' => $products,
        ], 201);
    }
    public function userTracking(Request $request){
        $action = $request->action;
        $id_user = $request->id_user;
        $productIds = $request->product_ids;

        // không có thì tạo mới 
        $userData = UserData::firstOrCreate(['id_user' => $id_user], [
            'recent_care' => json_encode([]),
            'recent_add' => json_encode([]),
            'recent_buy' => json_encode([]),
        ]);

        $recentCare = json_decode($userData->recent_care);
        $recentAdd = json_decode($userData->recent_add);
        $recentBuy = json_decode($userData->recent_buy);

        if ($action == 'click') {
            $recentCare = array_merge($productIds, $recentCare);
        }

        if ($action == 'add') {
            $recentCare = array_merge($recentAdd, $recentCare); # thêm hết vào care sau đó loại sau 
            $recentAdd = array_merge($productIds, []); # giỏ hàng hiện tại 
            // add hay remove đều chỉ cần truyền list product hiện tại là đủ 
        }

        if ($action == 'buy') {
            // CustomerOrderController gọi đến userTracking
            $recentBuy = array_merge($productIds, $recentBuy);
        }

        // final 
        $recentCare = array_unique($recentCare);
        $recentAdd = array_unique($recentAdd);
        $recentBuy = array_unique($recentBuy);

        // loại bỏ các care đã có trong add và buy 
        $recentCare = array_diff($recentCare, array_merge($recentAdd, $recentBuy));
        // loại bỏ các add đã có trong buy 
        $recentAdd = array_diff($recentAdd, $recentBuy);

        // chỉ lưu value (dạng array) , nếu không nó sẽ lưu dạng object , mặt dù các biến của ta đang dạng mảng
        $userData->recent_care = json_encode(array_values($recentCare));
        $userData->recent_add = json_encode(array_values($recentAdd));
        $userData->recent_buy = json_encode(array_values($recentBuy));
        $userData->save();
        
        return response()->json([
            'message' => 'Update data tracking successful !',
            'data' => $userData,
        ], 201);
    }

    public function cancelBuy($id_cancel) {
        $customer_order = CustomerOrder::find($id_cancel);
        $idCustomerOrder = CustomerOrder::where('customer_id',$customer_order->customer_id)
            ->where('order_status',1)->pluck('id')->unique()->toArray();
        $idProductSuccess = OrderDetail::whereIn('customer_order_id', $idCustomerOrder)->pluck('product_id')->unique()->toArray();
        
        $idProductCancels = []; 
        $idProductCancels = OrderDetail::where('customer_order_id',$id_cancel)->pluck('product_id')->unique()->toArray();

        $removeIds = array_diff($idProductCancels, $idProductSuccess);

        // không có thì tạo mới 
        $userData = UserData::firstOrCreate(['id_user' => $customer_order->customer_id], [
            'recent_care' => json_encode([]),
            'recent_add' => json_encode([]),
            'recent_buy' => json_encode([]),
        ]);

        $recentCare = json_decode($userData->recent_care);
        $recentAdd = json_decode($userData->recent_add);
        $recentBuy = json_decode($userData->recent_buy);

        $recentCare = array_merge($removeIds, $recentCare);
        $recentBuy = array_diff($recentBuy, $removeIds);

        // final 
        $recentCare = array_unique($recentCare);
        $recentAdd = array_unique($recentAdd);
        $recentBuy = array_unique($recentBuy);
        // loại bỏ các care đã có trong add và buy 
        $recentCare = array_diff($recentCare, array_merge($recentAdd, $recentBuy));
        // loại bỏ các add đã có trong buy 
        $recentAdd = array_diff($recentAdd, $recentBuy);
        $userData->recent_care = json_encode(array_values($recentCare));
        $userData->recent_add = json_encode(array_values($recentAdd));
        $userData->recent_buy = json_encode(array_values($recentBuy));
        $userData->save();
    }

}
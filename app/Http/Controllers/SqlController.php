<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use function GuzzleHttp\Promise\all;

class SqlController extends Controller
{
    public function run(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sql' => 'required',
            'type' => ['required', Rule::in('select', 'update', 'insert')],
        ]);

        if ($validator->fails())
            return response()->json($validator->errors(), 400);
        $type = $request->type;
        $data = DB::$type($request->sql);
        return response()->json($data);
    }

    public function getModelData()
    {
        return [
            "Address" => ["table_name" => 'addresses', "fields" => ["national_id", "postal_code", "province", "city", "rest"]],
            "BankingTransaction" => ["table_name" => "banking_transactions", "fields" => ["transaction_id", "bank_name", "shaparak_code"]],
            "Bookmark" => ["table_name" => "bookmarks", "fields" => ["national_id", "product_id"]],
            "Cart" => ["table_name" => "carts", "fields" => ["cart_id", "national_id"]],
            "CartProduct" => ["table_name" => "cart_products", "fields" => ["cart_id", "product_id", "quantity"]],
            "Category" => ["table_name" => "categories", "fields" => ["category_id", "name", "parent_category_id"]],
            "Comment" => ["table_name" => "comments", "fields" => ["national_id", "product_id", "has_bought", "score", "text", "date", "status"]],
            "ConsumableProduct" => ["table_name" => "consumable_products", "fields" => ["product_id", "product_date", "expiration_date"]],
            "Customer" => ["table_name" => "customers", "fields" => ["national_id", "loyalty_score"]],
            "InPlaceTransaction" => ["table_name" => "in_place_transactions", "fields" => ["transaction_id", "method"]],
            "Order" => ["table_name" => "orders", "fields" => ["order_id", "cart_id", "status", "date", "payment_method", "transaction_id", "postman_national_id", "address_id"]],
            "Person" => ["table_name" => "people", "fields" => ["national_id", "email", "first_name", "last_name", "telephonr", "mobile"]],
            "Postman" => ["table_name" => "postmen", "fields" => ["national_id", "salary"]],
            "Product" => ["table_name" => "products", "fields" => ["product_id", "name", "description", "quantity", "sale_price", "purchase_price", "discount", "img_url", "category_id"]],
            "Property" => ["table_name" => "properties", "fields" => ["product_id", "key", "value"]],
            "Supplier" => ["table_name" => "suppliers", "fields" => ["national_id", "company_name", "average_score"]],
            "Transaction" => ["table_name" => "transactions", "fields" => ["transaction_id", "national_id", "type"]],
        ];
    }

    public function buildQuery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query_name' => ['required', Rule::in(['select', 'insert', 'update', 'delete'])],
            'query_params' => 'array',
            'model' => ['required', Rule::in([
                "Address", "BankingTransaction", "Bookmark",
                "Cart", "CartProduct", "Category", "Comment", "ConsumableProduct", "Customer",
                "InPlaceTransaction", "Order", "Person", "Postman", "Product", "Property", "Supplier", "Transaction"
            ])],
            "methods" => 'array'
        ]);
        if ($validator->fails())
            return response()->json($validator->errors(), 400);
        DB::enableQueryLog();
        $model_name = $request->model;
        $query_name = $request->query_name;
        $model = "App\Models\\$model_name";
        $query = $model::query();
        $methods = $request->methods;
        foreach ($methods as $name => $param) {
            switch ($name) {
                case 'distinct':
                    $query->$name();
                    break;
                case 'join':
                case 'leftJoin':
                case 'rightJoin':
                    $query->$name($param[0], $param[1], $param[2], $param[3]);
                    break;
                case 'where':
                case 'orWhere':
                case 'having':
                    $query->$name($param[0], $param[1], $param[2]);
                    break;
                case 'orderBy':
                    $query->$name($param[0], $param[1]);
                    break;
                case 'groupBy':
                    $query->$name($param[0]);
            }
        }
        if ($query_name == 'select') {
            $args = $request->query_params;
            $data = $query->addSelect($args)->get();
        } else if ($query_name == 'update') {
            $data = $query->update($request->query_params);
        } else
            $data = $query->delete();
        return response()->json(['data' => $data, 'query' => DB::getQueryLog()]);
    }
}

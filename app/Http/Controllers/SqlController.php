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
            "Address" => ["national_id", "postal_code", "province", "city", "rest"],
            "BankingTransaction" => ["transaction_id", "bank_name", "shaparak_code"],
            "Bookmark" => ["national_id", "product_id"],
            "Cart" => ["cart_id", "national_id"],
            "CartProduct" => ["cart_id", "product_id", "quantity"],
            "Category" => ["category_id", "name", "parent_category_id"],
            "Comment" => ["national_id", "product_id", "has_bought", "score", "text", "date", "status"],
            "ConsumableProduct" => ["product_id", "product_date", "expiration_date"],
            "Customer" => ["national_id", "loyalty_score"],
            "InPlaceTransaction" => ["transaction_id", "method"],
            "Order" => ["order_id", "cart_id", "status", "date", "payment_method", "transaction_id", "postman_national_id", "address_id"],
            "Person" => ["national_id", "email", "first_name", "last_name", "telephonr", "mobile"],
            "Postman" => ["national_id", "salary"],
            "Product" => ["product_id", "name", "description", "quantity", "sale_price", "purchase_price", "discount", "img_url", "category_id"],
            "Property" => ["product_id", "key", "value"],
            "Supplier" => ["national_id", "company_name", "average_score"],
            "Transaction" => ["transaction_id", "national_id", "type"],
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
        if ($query_name == 'select'){
            $args = $request->query_params;
            $data = $query->addSelect($args)->get();
        } else if ($query_name == 'update') {
            $data = $query->update($request->query_params);
        } else
            $data = $query->delete();
        return response()->json(['data' => $data, 'query' => DB::getQueryLog()]);
    }
}

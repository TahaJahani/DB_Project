<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTriggersAndViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        
		DB::statement("
CREATE VIEW in_progress_orders
AS
  SELECT *
  FROM   orders
  WHERE  status = 'pending'
	  ");
	  //------------------------------------
		DB::statement("
CREATE VIEW shipping_orders
AS
  SELECT *
  FROM   orders
  WHERE  status = 'shipping'
	  ");
	  //------------------------------------
		DB::statement("
CREATE VIEW pending_comments
AS
  SELECT *
  FROM   comments
  WHERE  status = 'pending'
	  ");
	  //------------------------------------
	  DB::statement("
CREATE OR REPLACE FUNCTION customer_orders(
  the_national_id varchar
) 
	RETURNS TABLE (
    order_id bigint,
    cart_id bigint,
    status varchar,
    date timestamp,
    payment_method varchar,
    transaction_id bigint,
    postman_national_id varchar,
    address_id bigint
	) 
	LANGUAGE plpgsql
AS $$
BEGIN
	RETURN QUERY 
		SELECT 
			*
		FROM
			 orders AS O1
		WHERE
			the_national_id = (SELECT national_id FROM carts AS C1 WHERE O1.cart_id = C1.cart_id);
END;$$;
	  ");
	  
	  
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	DB::statement("DROP VIEW IF EXISTS in_progress_orders");
    }
}

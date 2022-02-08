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
    //------------------------------------
    // VIEWS
    //------------------------------------
    DB::statement(" CREATE OR REPLACE VIEW in_progress_orders AS SELECT * FROM orders WHERE  status = 'pending'");
    //------------------------------------
    DB::statement(" CREATE OR REPLACE VIEW shipping_orders AS SELECT * FROM orders WHERE  status = 'shipping'");
    //------------------------------------
    DB::statement(" CREATE OR REPLACE VIEW pending_comments AS SELECT * FROM comments WHERE  status = 'pending'");
    //------------------------------------
    DB::statement(" CREATE OR REPLACE VIEW category_with_sales
                        AS SELECT category_id, name as category_name, parent_category_id, SUM(quantity * sale_price) as total_sales
                        FROM cart_products NATURAL JOIN categories NATURAL JOIN products
                        WHERE cart_id IN (SELECT cart_id from orders NATURAL JOIN carts WHERE status='finished')
                        GROUP BY (category_name, category_id)
                        ORDER BY total_sales DESC;
                      ");
    //------------------------------------
    DB::statement(" CREATE OR REPLACE FUNCTION personal_info(the_national_id varchar) 
                        RETURNS TABLE (
                          national_id varchar,
                          email varchar,
                          first_name varchar,
                          last_name varchar,
                          telephone varchar,
                          mobile varchar
                        ) LANGUAGE plpgsql AS $$
                          BEGIN
                          RETURN QUERY 
                            SELECT * FROM people as P1 WHERE
                              the_national_id = P1.national_id;
                          END;$$;
                        ");
    //------------------------------------
    DB::statement(" CREATE OR REPLACE FUNCTION customer_orders(the_national_id varchar) 
	                      RETURNS TABLE (
                          order_id bigint,
                          cart_id bigint,
                          status varchar,
                          date timestamp,
                          payment_method varchar,
                          transaction_id bigint,
                          postman_national_id varchar,
                          address_id bigint
                        ) LANGUAGE plpgsql AS $$
                          BEGIN
                          RETURN QUERY 
                            SELECT * FROM orders AS O1 WHERE
                              the_national_id = (SELECT national_id FROM carts AS C1 WHERE O1.cart_id = C1.cart_id);
                          END;$$;
                        ");
    //------------------------------------
    DB::statement(" CREATE OR REPLACE FUNCTION customer_bookmarks(the_national_id varchar) 
                        RETURNS TABLE (
                          product_id bigint,
                          name varchar,
                          purchase_price bigint,
                          discount numeric,
                          img_url varchar,
                          category_id bigint
                        ) LANGUAGE plpgsql AS $$
                          BEGIN
                          RETURN QUERY 
                            SELECT P1.product_id, P1.name, P1.purchase_price, P1.discount, P1.img_url, P1.category_id
                    FROM (products AS P1 NATURAL JOIN bookmarks AS B1)
                    WHERE the_national_id = national_id;
                          END;$$;
                        ");
                        
      //------------------------------------
      // TRIGGERS
      //------------------------------------
      // quantity_insert_check: check and update quantity of product when add it to a cart
      DB::statement(" CREATE OR REPLACE FUNCTION quantity_insert_check_function()
                        RETURNS TRIGGER
                        LANGUAGE plpgsql AS $$
                        BEGIN
                          IF NEW.quantity > (select quantity from products as P1 WHERE P1.product_id = NEW.product_id) THEN
                            RAISE EXCEPTION 'Not enought product #% available', NEW.product_id;
                            RETURN NULL;
                          ELSE
                            UPDATE products as P2
                              SET quantity = P2.quantity - NEW.quantity
                              WHERE P2.product_id = NEW.product_id;
                            RETURN NEW;
                          END IF;
                        END;$$;");
                      
      DB::statement(" CREATE TRIGGER quantity_check_insert
                        BEFORE INSERT ON cart_products
                        FOR EACH ROW
                        EXECUTE PROCEDURE quantity_insert_check_function();
                        ");
      //------------------------------------
      // quantity_update_check: check and update quantity of product when update its quantity in a cart
      DB::statement(" CREATE OR REPLACE FUNCTION quantity_update_check_function()
                        RETURNS TRIGGER
                        LANGUAGE plpgsql AS $$
                        BEGIN
                          IF NEW.quantity - OLD.quantity > (select quantity from products as P1 WHERE P1.product_id = NEW.product_id) THEN
                            RAISE EXCEPTION 'Not enought product #% available', NEW.product_id;
                            RETURN NULL;
                          ELSE
                            UPDATE products as P2
                              SET quantity = P2.quantity - (NEW.quantity - OLD.quantity)
                              WHERE P2.product_id = NEW.product_id;
                            RETURN NEW;
                          END IF;
                        END;$$;");
                      
      DB::statement(" CREATE TRIGGER quantity_update_check
                        BEFORE UPDATE OF quantity ON cart_products
                        FOR EACH ROW
                        EXECUTE PROCEDURE quantity_update_check_function();
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
    DB::statement("DROP VIEW IF EXISTS shipping_orders");
    DB::statement("DROP VIEW IF EXISTS pending_comments");
    DB::statement("DROP VIEW IF EXISTS category_sales");
  }
}

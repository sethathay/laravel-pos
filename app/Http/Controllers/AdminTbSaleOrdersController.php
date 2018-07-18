<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use Carbon\Carbon;
	use App\Http\Controllers\Logic\CodeGeneration;

	class AdminTbSaleOrdersController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = false;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "tb_sale_orders";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Sale No","name"=>"code"];
			$this->col[] = ["label"=>"Customer","name"=>"customer_id","join"=>"tb_customers,name"];
			$this->col[] = ["label"=>"Order Date","name"=>"order_date"];
			$this->col[] = ["label"=>"Due Date","name"=>"due_date"];
			$this->col[] = ["label"=>"Amount","name"=>"amount"];
			$this->col[] = ["label"=>"Discount","name"=>"discount"];
			$this->col[] = ["label"=>"Write Off","name"=>"write_off"];
			$this->col[] = ["label"=>"Total Amount","name"=>"total_amount"];
			$this->col[] = ["label"=>"Pre Amount","name"=>"pre_amount"];
			$this->col[] = ["label"=>"Credit Amount","name"=>"credit_amount"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Remark","name"=>"remark"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Sale No','name'=>'code','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10','readonly'=>true, 'value'=>CodeGeneration::newCode("tb_sale_orders","code",6,"SO")];
			$this->form[] = ['label'=>'Customer','name'=>'customer_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'tb_customers,name'];
			$this->form[] = ['label'=>'Order Date','name'=>'order_date','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10', 'value' => now()];
			$this->form[] = ['label'=>'Due Date','name'=>'due_date','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10', 'value' => Carbon::now()->addDays(7)];
			
			$columns = [];
			$columns[] = ['label'=>'Product', 'name'=>'product_id', 'type'=>'datamodal', 'datamodal_table'=>'tb_products','datamodal_columns'=>'name,price,photo','datamodal_select_to'=>'price:item_price,photo:photo','datamodal_columns_alias'=>'Name,Price,Photo','required'=>true];
			$columns[] = ['label'=>'Price', 'name'=>'item_price','type'=>'number','readonly'=>true,'required'=>true];
			$columns[] = ['label'=>'Qty','name'=>'item_qty','type'=>'number','required'=>true];
			$columns[] = ['label'=>'Amount','name'=>'item_amount','type'=>'number','required'=>true,'readonly'=>true,'formula'=>'[item_price] * [item_qty]'];
			$columns[] = ['label'=>'Discount(%)','name'=>'item_discount','type'=>'number','validation'=>'integer|min:1|max:100'];
			$columns[] = ['label'=>'Write Off','name'=>'item_write_off','type'=>'number','validation'=>'integer|min:0'];
			$columns[] = ['label'=>'Total Amount','name'=>'item_total_amount','type'=>'number','readonly'=>true, 'required'=>true, 'formula'=>'[item_amount]-([item_amount]*([item_discount]/100))-[item_write_off]'];
			$columns[] = ['label'=>'Remark','name'=>'remark','type'=>'textarea'];
			$this->form[] = ['label'=>'Sale Order Items','name'=>'tb_sale_order_items','type'=>'child-sale-order','columns'=> $columns,'table'=>'tb_sale_order_items','foreign_key'=>'sale_order_id'];

			$this->form[] = ['label'=>'Amount','name'=>'amount','type'=>'money','validation'=>'required|integer|min:0','width'=>'col-sm-10','readonly'=>'true','value'=>0];
			$this->form[] = ['label'=>'Discount(%)','name'=>'discount','type'=>'number','validation'=>'integer|min:0','width'=>'col-sm-10','value'=>0];
			$this->form[] = ['label'=>'Write Off','name'=>'write_off','type'=>'money','validation'=>'integer|min:0','width'=>'col-sm-10','value'=>0];
			$this->form[] = ['label'=>'Total Amount','name'=>'total_amount','type'=>'money','validation'=>'required|integer|min:0','width'=>'col-sm-10','value'=>0,'readonly'=>'true','formula'=>'[amount]-([amount]*([discount]/100))-[write_off]'];
			$this->form[] = ['label'=>'Pre Amount','name'=>'pre_amount','type'=>'money','validation'=>'integer|min:0','width'=>'col-sm-10','value'=>0];
			$this->form[] = ['label'=>'Credit Amount','name'=>'credit_amount','type'=>'money','validation'=>'required|integer|min:0','width'=>'col-sm-10','value'=>0,'readonly'=>'true','formula'=>'[total_amount]-[pre_amount]'];
			$this->form[] = ['label'=>'Remark','name'=>'remark','type'=>'textarea','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Code','name'=>'code','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Customer Id','name'=>'customer_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'tb_customers,name'];
			//$this->form[] = ['label'=>'Order Date','name'=>'order_date','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Due Date','name'=>'due_date','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
			//
			//$columns = [];
			//$columns[] = ['label'=>'Product', 'name'=>'product_id', 'type'=>'datamodal', 'datamodal_table'=>'tb_products','datamodal_columns'=>'name,price,photo','datamodal_select_to'=>'price:item_price','datamodal_columns_alias'=>'Name,Price,Photo','required'=>true];
			//$columns[] = ['label'=>'Price', 'name'=>'item_price','type'=>'number','readonly'=>true,'required'=>true];
			//$columns[] = ['label'=>'Qty','name'=>'item_qty','type'=>'number','required'=>true];
			//$columns[] = ['label'=>'Amount','name'=>'item_amount','type'=>'number','required'=>true,'readonly'=>true,'formula'=>'[item_price] * [item_qty]'];
			//$this->form[] = ['label'=>'Sale Order Items','name'=>'tb_sale_order_items','type'=>'child','columns'=> $columns,'table'=>'tb_sale_order_items', 'foreign_key'=>'sale_order_id'];
			//
			//$this->form[] = ['label'=>'Amount','name'=>'amount','type'=>'money','validation'=>'required|integer|min:0','width'=>'col-sm-3','readonly'=>'true'];
			//$this->form[] = ['label'=>'Discount','name'=>'discount','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-3'];
			//$this->form[] = ['label'=>'Write Off','name'=>'write_off','type'=>'money','validation'=>'required|integer|min:0','width'=>'col-sm-3'];
			//$this->form[] = ['label'=>'Total Amount','name'=>'total_amount','type'=>'money','validation'=>'required|integer|min:0','width'=>'col-sm-3','readonly'=>'true'];
			//$this->form[] = ['label'=>'Pre Amount','name'=>'pre_amount','type'=>'money','validation'=>'required|integer|min:0','width'=>'col-sm-3'];
			//$this->form[] = ['label'=>'Credit Amount','name'=>'credit_amount','type'=>'money','validation'=>'required|integer|min:0','width'=>'col-sm-3','readonly'=>'true'];
			//$this->form[] = ['label'=>'Remark','name'=>'remark','type'=>'textarea','width'=>'col-sm-3'];
			# OLD END FORM

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
	        $this->sub_module = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
	        $this->addaction = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
	        $this->button_selected = array();

	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
	        $this->alert        = array();
	                

	        
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	        $this->index_button = array();



	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
	        $this->table_row_color = array();     	          

	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
			$this->script_js = "
				$(function(){
					setInterval(function(){
						var total = 0;
						$('#table-saleorderitems tbody .item_total_amount').each(function(){
							var amount = parseInt($(this).text());
							total += amount;
						});
						$('#amount').val(total);
						var discount = parseInt($('#discount').val());
						var writeOff = parseInt($('#write_off').val());
						$('#total_amount').val(total-(total*(discount/100))-writeOff);
						var totalAmount = parseInt($('#total_amount').val());
						var preAmount = parseInt($('#pre_amount').val());
						$('#credit_amount').val(totalAmount-preAmount);
					},500);
				});
			";


            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
			$this->style_css = NULL;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
	        
	        
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
	        //Your code here
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	if($column_index=='10'){
	    		if($column_value=='01'){
	    			$column_value = "<label style='padding:5px;font-size:12px' class='label label-danger'>Unpaid</label>";
	    		}else{
	    			$column_value = "<label style='padding:5px;font-size:12px' class='label label-success'>Paid</label>";
	    		}
	    	}
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {
			//unset set field sale order items
			unset($postdata["tb_sale_order_items"]);
			//mapping custom type components child-sale-order to child
			$this->data_inputan[4]['type'] = 'child';
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }



	    //By the way, you can still create your own method in here... :) 


	}
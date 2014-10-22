<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/**
 * @author Kariuki
 */
class orders extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this -> load -> helper(array('form', 'url'));
		$this -> load -> library(array('hcmp_functions', 'form_validation'));
	}

	public function order_listing($level, $facility_code = null) {
		/*
		 $data['order_counts']=Counties::get_county_order_details("","", $facility_c);
		 $data['delivered']=Counties::get_county_received("","", $facility_c);
		 $data['pending']=Counties::get_pending_county("","", $facility_c);
		 $data['approved']=Counties::get_approved_county("","", $facility_c);
		 $data['rejected']=Counties::get_rejected_county("","", $facility_c);
		 $data['content_view'] = "facility/facility_issues/facility_issues_service_points_v";
		 $data['title'] = "Order Listing";
		 $data['banner_text'] = "Order Listing";	*/
	}

	public function index() {
		$test= $this -> hcmp_functions -> create_order_delivery_color_coded_table(1);
		echo $test['table'];
	}
	
	public function test_read_write_excel(){

	$inputFileName = 'print_docs/excel/excel_template/KEMSA Customer Order Form.xlsx'; 

    $file_name=time().'.xlsx';
	$excel2 = PHPExcel_IOFactory::createReader('Excel5');
    $excel2=$objPHPExcel= $excel2->load($inputFileName); // Empty Sheet
    
    $sheet = $objPHPExcel->getSheet(0); 
    $highestRow = $sheet->getHighestRow(); 
	
    $highestColumn = $sheet->getHighestColumn();
	
    $excel2->setActiveSheetIndex(0);
	
    $excel2->getActiveSheet()
    ->setCellValue('H4', '4')
    ->setCellValue('H5', '5')
    ->setCellValue('H6', '6')       
    ->setCellValue('H7', '7')
	->setCellValue('H8', '7');

   //  Loop through each row of the worksheet in turn
for ($row = 1; $row <= $highestRow; $row++){ 
    //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);							  
   if(isset($rowData[0][2]) && $rowData[0][2]!='Product Code'){
   	$excel2->getActiveSheet()->setCellValue("H$row", '7');
   }

}

   $objWriter = PHPExcel_IOFactory::createWriter($excel2, 'Excel5');
   $objWriter->save("print_docs/excel/excel_files/".$file_name);

	}
	public function getDistrict()
	{
		//for ajax
		$county = $_POST['county'];
		$districts = Districts::getDistrict($county);
		$list = "";
		
		foreach ($districts as $districts) 
		{
			$list.=$districts->id;
			$list.="*";
			$list.=$districts->district;
			$list.="_";
		}
		
		echo $list;
	}
	public function getFacilities()
	{
		//for ajax
		$district=$_POST['district'];
		$facilities=Facilities::getFacilities($district);
		$list="";
		
		foreach ($facilities as $facilities) 
		{
			$list.=$facilities->facility_code;
			$list.="*";
			$list.=$facilities->facility_name;
			$list.="_";
		}
		
		echo $list;
	}
	//Facility Transaction Data when MEDS option is selected
	public function facility_order_meds() {
		$facility_code = $this -> session -> userdata('facility_id');
		
		$facility_data = Facilities::get_facility_name_($facility_code) -> toArray();
		
		$items = Facility_Transaction_Table::get_commodities_for_ordering($facility_code);
		
		//echo "<pre>";print_r($items);echo "</pre>";exit;
		$meds_categories = meds_categories::get_all();
		//$meds_commodities = meds_commodities::get_all();
		//echo "<pre>";print_r($meds_categories);exit;
		
		if(isset($_FILES['file']) && $_FILES['file']['size'] > 0)
        {
        	$ext = pathinfo($_FILES["file"]['name'], PATHINFO_EXTENSION);
            //echo $ext; 
	        if($ext=='xls')
	        {
	        	$excel2 = PHPExcel_IOFactory::createReader('Excel5');  
				 
	        }else if($ext=='xlsx'){
	        	$excel2 = PHPExcel_IOFactory::createReader('Excel2007');    
	        }else{
	        	die('Invalid file format given'.$_FILES['file']);   
	        }
		
		$excel2=$objPHPExcel= $excel2->load($_FILES["file"]["tmp_name"]); // Empty Sheet
    
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
    
        $highestColumn = $sheet->getHighestColumn();
        $temp=array();
        $facility_code= $sheet->getCell('H4')->getValue();
   		
        //  Loop through each row of the worksheet in turn
        $array_code=array();
		$array_commodity=array();
		$array_category=array();
		$array_pack=array();
		$array_price=array();
		$array_order_qty=array();
		$array_order_val=array();
		$array_index=array();
		//$array_code=array();
        for ($row = 17; $row <= $highestRow; $row++){ 
        //  Read a row of data into an array
        $rowData = $objPHPExcel->getActiveSheet()->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
     	//var_dump($rowData); 
		//count($rowData);
		$array_index[]=$rowData[0][1];
		$array_code[]=$rowData[0][2];
		$array_commodity[]=$rowData[0][3];
		$array_category[]=$rowData[0][4];
		$array_price[]=$rowData[0][6];
		$array_order_qty[]=(int)$rowData[0][7];
		$array_order_val[]=$rowData[0][8];
		$array_pack[]=$rowData[0][5];
			
		//if(isset($rowData[0][2]) && $rowData[0][2]!='Product Code'){
			//echo '<pre>';print_r($rowData[0][7]); echo '</pre>';
        //foreach($items as $key=> $data){
        	//echo '<pre>';print_r($array_index); echo '</pre>';
            //}   
            //}
            }
		
		foreach ($array_index as $id => $key) {
        		//foreach($items as $key=> $data){
        array_push($temp,array('sub_category_name'=>$array_category[$key],
        'commodity_name'=>$array_commodity[$key],
        'unit_size'=>$array_pack[$key],
        'unit_cost'=>$array_price[$key],
        'commodity_code'=>$array_code[$key],
        'commodity_id'=>$data['commodity_id'],
        'quantity_ordered'=>($array_order_qty[$key]=='')? 0:(int)$array_order_qty[$key],
        'total_commodity_units'=>$array_price[$key]*(int)$array_order_qty[$key],
        'opening_balance'=>0,
        'total_receipts'=>0,
        'total_issues'=>0,
        'comment'=>'',
        'closing_stock_'=>0,
        'closing_stock'=>0,
        'days_out_of_stock'=>0,
        'date_added'=>'',
        'losses'=>0,
        'status'=>0,
        'adjustmentpve'=>0,
        'adjustmentnve'=>0,
        'historical'=>0));
         //unset($items[$key]);
        // }
              
}
		
foreach ($temp as $key => $value) {
	//echo '<pre>';print_r($value['commodity_code']); echo '</pre>';
	if ($value['commodity_code']==""||$value['quantity_ordered']=="") {
		unset($temp[$key]);
	}
}
		//$c = array_combine($array_code, $array_commodity);
		$array_id=array();
		foreach ($temp as $key ) {
			
			$kemsa=$key['commodity_code'];
			
			$get_id=Commodities::get_id($kemsa);
			
			foreach ($get_id as $key2) {
				$array_id[]=$key2['id'];
				//echo '<pre>';print_r($key2['id']); echo '</pre>';
			}
			
			//echo '<pre>';print_r($get_id[]); echo '</pre>';
		}
		//$result = array_combine($array_id, $temp);
		//echo '<pre>';print_r($array_id); echo '</pre>';
		//echo '<pre>';print_r($temp); echo '</pre>';exit;
		//echo $key['sub_category_name'];

		$array_combined=array();
		$id_count=count($temp);
		
		foreach ($temp as $key => $value) {
			
				//echo $keys['sub_category_name'];
				array_push($array_combined,array(
			'sub_category_name'=>$value['sub_category_name'],
        'commodity_name'=>$value['commodity_name'],
        'unit_size'=>$value['unit_size'],
        'unit_cost'=>$value['unit_cost'],
        'commodity_code'=>$value['commodity_code'],
        'commodity_id'=>$key,
        'quantity_ordered'=>$value['quantity_ordered'],
        'total_commodity_units'=>$value['total_commodity_units'],
        'opening_balance'=>0,
        'total_receipts'=>0,
        'total_issues'=>0,
        'comment'=>'',
        'closing_stock_'=>0,
        'closing_stock'=>0,
        'days_out_of_stock'=>0,
        'date_added'=>'',
        'losses'=>0,
        'status'=>0,
        'adjustmentpve'=>0,
        'adjustmentnve'=>0,
        'historical'=>0));
		}
		//echo '<pre>';print_r($array_combined); echo '</pre>';
		//echo '<pre>';print_r($array_id); echo '</pre>';
		//exit;
		
              
	//var_dump();
	//exit;
        //unset($objPHPExcel);
       $data['order_details'] = $data['facility_order'] = $array_combined;  
        }else{
        $data['order_details'] = $data['facility_order'] = $items;   
        }
		
		$data['categories'] = $meds_categories;
        $data['order_details'] = $data['facility_order'] = $items;   
		$facility_code = $this -> session -> userdata('facility_id');
		// echo $facility_code;exit;
        $data['facility_commodity_list'] = Commodities::get_commodities_not_in_facility($facility_code);
		$data['title'] = "Facility New Order MEDS";
		$data['content_view'] = "facility/facility_orders/facility_order_meds";
		$data['banner_text'] = "Facility New Order MEDS";
		$this -> load -> view("shared_files/template/template", $data);
		
		//var_dump($temp);exit;
        /*$facility_code = $this -> session -> userdata('facility_id');
		$facility_data = Facilities::get_facility_name_($facility_code) -> toArray();
        $data['content_view'] = "facility/facility_orders/facility_order_from_kemsa_v";
        $data['title'] = "Facility New Order";
        $data['banner_text'] = "Facility New Order";
        $data['drawing_rights'] = $facility_data[0]['drawing_rights'];
        
		$this -> load -> view('shared_files/template/template', $data);*/
		//$data['facility_stock_data'] = facility_transaction_table::get_all($facility_code);
        //$data['last_issued_data']=facility_issues::get_last_time_facility_issued($facility_code);
		
	}
	//AJAX Request for getting the sub categories of a particular category
	public function get_sub_categories() {
		$category = $_POST['category'];
		$sub_categories = meds_sub_category::get_all_in_category($category);
		//echo "<pre>";print_r($sub_categories);exit;
		//$facilities = Facilities::getFacilities($district);
		$list = "";
		foreach ($sub_categories as $sub_categories) {
			$list .= $sub_categories -> id;
			$list .= "*";
			$list .= $sub_categories -> sub_category_name;
			$list .= "_";
		}
		echo $list;
	}
	//AJAX Request for getting the commoditeis in a particular sub category
	public function get_commodities_meds() {
		$sub_category = $_POST['sub_category'];
		$commodities = meds_commodities::get_all_in_category($sub_category);
		//$commodities = meds_sub_category::get_all_in_category($category);
		//echo "<pre>";print_r($sub_categories);exit;
		//$facilities = Facilities::getFacilities($district);
		$list = "";
		foreach ($commodities as $commodities) {
			$list .= $commodities -> commodity_code;
			$list .= "^";
			$list .= $commodities -> unit_pack;
			$list .= "^";
			$list .= $commodities -> commodity_name;
			$list .= "_";
			
		}
		echo $list;
	}
	public function facility_order() 
	{
		//$this -> load -> library('PHPExcel');
		//ini_set("max_execution_time", "1000000");
		$facility_code = $this -> session -> userdata('facility_id');

        $items=Facility_Transaction_Table::get_commodities_for_ordering($facility_code);
        if(isset($_FILES['file']) && $_FILES['file']['size'] > 0)
        {
        	$ext = pathinfo($_FILES["file"]['name'], PATHINFO_EXTENSION);
            //echo $ext; 
	        if($ext=='xls')
	        {
	        	$excel2 = PHPExcel_IOFactory::createReader('Excel5');    
	        }else if($ext=='xlsx'){
	        	$excel2 = PHPExcel_IOFactory::createReader('Excel2007');    
	        }else{
	        	die('Invalid file format given'.$_FILES['file']);   
	        }
		
		$excel2=$objPHPExcel= $excel2->load($_FILES["file"]["tmp_name"]); // Empty Sheet
    
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
    
        $highestColumn = $sheet->getHighestColumn();
        $temp=array();
        $facility_code= $sheet->getCell('H4')->getValue();
   
   
        //  Loop through each row of the worksheet in turn
        $array_code=array();
		$array_commodity=array();
		$array_category=array();
		$array_pack=array();
		$array_price=array();
		$array_order_qty=array();
		$array_order_val=array();
		$array_index=array();
		//$array_code=array();
        for ($row = 17; $row <= $highestRow; $row++){ 
        //  Read a row of data into an array
        $rowData = $objPHPExcel->getActiveSheet()->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
     	//var_dump($rowData); 
		//count($rowData);
		$array_index[]=$rowData[0][1];
		$array_code[]=$rowData[0][2];
		$array_commodity[]=$rowData[0][3];
		$array_category[]=$rowData[0][4];
		$array_price[]=$rowData[0][6];
		$array_order_qty[]=(int)$rowData[0][7];
		$array_order_val[]=$rowData[0][8];
		$array_pack[]=$rowData[0][5];
			
		//if(isset($rowData[0][2]) && $rowData[0][2]!='Product Code'){
			//echo '<pre>';print_r($rowData[0][7]); echo '</pre>';
        //foreach($items as $key=> $data){
        	//echo '<pre>';print_r($rowData); echo '</pre>';
			
       
            //}   
            //}

		
		
		
            }
		
		foreach ($array_index as $id => $key) {
				  
        	
        		//foreach($items as $key=> $data){
        array_push($temp,array('sub_category_name'=>$array_category[$key],
        'commodity_name'=>$array_commodity[$key],
        'unit_size'=>$array_pack[$key],
        'unit_cost'=>$array_price[$key],
        'commodity_code'=>$array_code[$key],
        'commodity_id'=>$data['commodity_id'],
        'quantity_ordered'=>($array_order_qty[$key]=='')? 0:(int)$array_order_qty[$key],
        'total_commodity_units'=>$array_price[$key]*(int)$array_order_qty[$key],
        'opening_balance'=>0,
        'total_receipts'=>0,
        'total_issues'=>0,
        'comment'=>'',
        'closing_stock_'=>0,
        'closing_stock'=>0,
        'days_out_of_stock'=>0,
        'date_added'=>'',
        'losses'=>0,
        'status'=>0,
        'adjustmentpve'=>0,
        'adjustmentnve'=>0,
        'historical'=>0));
         //unset($items[$key]);
        // }
				
			
			
              
}
foreach ($temp as $key => $value) {
	//echo '<pre>';print_r($value['commodity_code']); echo '</pre>';
	if ($value['commodity_code']==""||$value['quantity_ordered']=="") {
		unset($temp[$key]);
	}
}
		//$c = array_combine($array_code, $array_commodity);
		$array_id=array();
		foreach ($temp as $key ) {
			
			$kemsa=$key['commodity_code'];
			
			$get_id=Commodities::get_id($kemsa);
			
			foreach ($get_id as $key2) {
				$array_id[]=$key2['id'];
				//echo '<pre>';print_r($key2['id']); echo '</pre>';
			}
			
			//echo '<pre>';print_r($get_id[]); echo '</pre>';
		}
		//$result = array_combine($array_id, $temp);
		//echo '<pre>';print_r($array_id); echo '</pre>';
		//echo '<pre>';print_r($temp); echo '</pre>';exit;
		
		
			
			//echo $key['sub_category_name'];
			
		
		$array_combined=array();
		$id_count=count($temp);
		
		foreach ($temp as $key => $value) {
			
				//echo $keys['sub_category_name'];
				array_push($array_combined,array(
			'sub_category_name'=>$value['sub_category_name'],
        'commodity_name'=>$value['commodity_name'],
        'unit_size'=>$value['unit_size'],
        'unit_cost'=>$value['unit_cost'],
        'commodity_code'=>$value['commodity_code'],
        'commodity_id'=>$key,
        'quantity_ordered'=>$value['quantity_ordered'],
        'total_commodity_units'=>$value['total_commodity_units'],
        'opening_balance'=>0,
        'total_receipts'=>0,
        'total_issues'=>0,
        'comment'=>'',
        'closing_stock_'=>0,
        'closing_stock'=>0,
        'days_out_of_stock'=>0,
        'date_added'=>'',
        'losses'=>0,
        'status'=>0,
        'adjustmentpve'=>0,
        'adjustmentnve'=>0,
        'historical'=>0));
		}
		//echo '<pre>';print_r($array_combined); echo '</pre>';
		//echo '<pre>';print_r($array_id); echo '</pre>';
		//exit;
		
              
	//var_dump();
	//exit;
        //unset($objPHPExcel);
       $data['order_details'] = $data['facility_order'] = $array_combined;  
        }else{
        $data['order_details'] = $data['facility_order'] = $items;   
        }
		
		//var_dump($temp);exit;
        $facility_code = $this -> session -> userdata('facility_id');
		$facility_data = Facilities::get_facility_name_($facility_code) -> toArray();
        $data['content_view'] = "facility/facility_orders/facility_order_from_kemsa_v";
        $data['title'] = "Facility New Order";
        $data['banner_text'] = "Facility New Order";
        $data['drawing_rights'] = $facility_data[0]['drawing_rights'];
        $data['facility_commodity_list'] = Commodities::get_commodities_not_in_facility($facility_code);

		$this -> load -> view('shared_files/template/template', $data);
	}
	public function facility_order_($facility_code=null) {
		// hack to ensure that when you are ordering for a facility that is not using hcmp they have all the items
		$checker= $this -> session -> userdata('facility_id') ? null : 1; 
		if(isset($_FILES['file']) && $_FILES['file']['size'] > 0){ 
			$more_data=$this -> hcmp_functions -> kemsa_excel_order_uploader($_FILES["file"]["tmp_name"]);
			$data['order_details'] = $data['facility_order'] =$more_data['row_data'];
			
			$facility_data = Facilities::get_facility_name($more_data['facility_code'])->toArray();
			if(count($facility_data)==0){
			$this -> session -> set_flashdata('system_error_message', "Kindly upload a file with correct facility MFL code ");
			redirect("reports/order_listing/subcounty");	
			}
			if($facility_data[0]['using_hcmp']==1){
			$this -> session -> set_flashdata('system_error_message', "You cannot order for a". 
			" facility that is already using HCMP, they need to place their order using their accounts");
			redirect("reports/order_listing/subcounty");		
			}
		}else{
		$data['order_details'] = $data['facility_order'] = Facility_Transaction_Table::get_commodities_for_ordering($facility_code,$checker);
		$facility_data = Facilities::get_facility_name($facility_code)->toArray();	
		}
		$data['content_view'] = "facility/facility_orders/facility_order_from_kemsa_v";
		$data['title'] = "Facility New Order";
		$data['system_error_message']="You are ordering for ".$facility_data[0]['facility_name'] ;
		$data['facility_code'] = $facility_code;
		$data['banner_text'] = "Facility New Order";
		$data['drawing_rights'] = $facility_data[0]['drawing_rights'];		
		$data['facility_commodity_list'] = Commodities::get_all_from_supllier(1);
		$this -> load -> view('shared_files/template/template', $data);
	}

	public function update_facility_order($order_id, $rejected = null, $option = null) {
		$order_data = facility_orders::get_order_($order_id) -> toArray();
		$data['content_view'] = "facility/facility_orders/update_facility_order_from_kemsa_v";
		$data['title'] = "Facility Update Order";
		$data['banner_text'] = "Facility Update Order";
		$data['rejected'] = ($rejected == 'rejected') ? 1 : 0;
		$data['option_'] = ($option == 'readonly') ? 'readonly_' : 0;
		$data['order_details'] = $order_data;
		$data['facility_order'] = facility_order_details::get_order_details($order_id);
		$data['facility_commodity_list'] = Commodities::get_all_from_supllier(1);
		$this -> load -> view('shared_files/template/template' , $data);
			}

			public function facility_new_order() {
			//security check
			if ($this -> input -> post('commodity_id')) :
			$this -> load -> database();
			$data_array = array();
			$commodity_id = $this -> input -> post('commodity_id');
			//order details table details
			$quantity_ordered_pack = $this -> input -> post('quantity');
			$quantity_ordered_units = $this -> input -> post('actual_quantity');
			$price = $this -> input -> post('price');
			$o_balance = $this -> input -> post('open');
			$t_receipts = $this -> input -> post('receipts');
			$t_issues = $this -> input -> post('issues');
			$adjustpve = $this -> input -> post('adjustmentpve');
			$adjustnve = $this -> input -> post('adjustmentnve');
			$losses = $this -> input -> post('losses');
			$days = $this -> input -> post('days');
			$c_stock = $this -> input -> post('closing');
			$comment = $this -> input -> post('comment');
			$s_quantity = $this -> input -> post('suggested');
			$amc = $this -> input -> post('amc');
			$workload = $this -> input -> post('workload');
			//order table details
			$bed_capacity = $this -> input -> post('bed_capacity');
			$drawing_rights = $this -> input -> post('drawing_rights');
			$order_total = $this -> input -> post('total_order_value');
			$order_no = $this -> input -> post('order_no');
			$facility_code = $this -> input -> post('facility_code');
			$user_id = $this -> session -> userdata('user_id');
			$order_date = date('y-m-d');
			$number_of_id = count($commodity_id);

			for ($i = 0; $i < $number_of_id; $i++) {
			if ($i == 0) {
			$order_details = array("workload" => $workload, 'bed_capacity' => $bed_capacity, 'order_total' => $order_total, 'order_no' => $order_no, 'order_date' => $order_date, 'facility_code' => $facility_code, 'ordered_by' => $user_id, 'drawing_rights' => $drawing_rights);
			$this -> db -> insert('facility_orders', $order_details);
			$new_order_no = $this -> db -> insert_id();
			}
			$temp_array = array("commodity_id" => (int)$commodity_id[$i], 'quantity_ordered_pack' => (int)$quantity_ordered_pack[$i], 'quantity_ordered_unit' => (int)$quantity_ordered_units[$i], 'quantity_recieved' => 0, 'price' => $price[$i], 'o_balance' => $o_balance[$i], 't_receipts' => $t_receipts[$i], 't_issues' => $t_issues[$i], 'adjustpve' => $adjustpve[$i], 'adjustnve' => $adjustnve[$i], 'losses' => $losses[$i], 'days' => $days[$i], 'c_stock' => $c_stock[$i], 'comment' => $comment[$i], 's_quantity' => $s_quantity[$i], 'amc' => $amc[0], 'order_number_id' => $new_order_no);
			//create the array to push to the db
			array_push($data_array, $temp_array);
			
			}// insert the data here
			//var_dump($data_array);exit;
			$this -> db -> insert_batch('facility_order_details', $data_array);
			if ($this -> session -> userdata('user_indicator') == 'district') :
			$order_listing = 'subcounty';
			elseif ($this -> session -> userdata('user_indicator') == 'county') :
			$order_listing = 'county';
			else :
			$myobj = Doctrine::getTable('Facilities') -> findOneByfacility_code($facility_code);
			$facility_name = $myobj -> facility_name;
			// get the order form details here
			//create the pdf here
			$pdf_body = $this -> create_order_pdf_template($new_order_no);
			$file_name = $facility_name . '_facility_order_no_' . $new_order_no . "_date_created_" . date('d-m-y');
			$pdf_data = array("pdf_title" => "Order Report For $facility_name", 'pdf_html_body' => $pdf_body, 'pdf_view_option' => 'save_file', 'file_name' => $file_name);
			$this -> hcmp_functions -> create_pdf($pdf_data);// create pdf
			$this -> hcmp_functions -> clone_excel_order_template($new_order_no,'save_file',$file_name);//create excel
			$order_listing = 'facility';
			$message_1='
			<br>
			Please find the Order Made by '.$facility_name.' below for approval.
			<br>
			You may log in to the HCMP system to approve it.<a href="http://health-cmp.or.ke/" target="_blank">Click here</a>
			<br>
			<br>
			<br>
			';
			$subject='Pending Approval Order Report For '.$facility_name;

			$attach_file1='./pdf/'.$file_name.'.pdf';
			$attach_file2="./print_docs/excel/excel_files/".$file_name.'.xls';

			$message=$message_1.$pdf_body;

			$response= $this->hcmp_functions->send_order_submission_email($message,$subject,$attach_file1."(more)".$attach_file2,null);
            
			if($response){
			delete_files($attach_file1);
            unlink($attach_file2);
			}
			else{

			}

			endif;
			$user = $this -> session -> userdata('user_id');
			$user_action = "order";

		 	Log::log_user_action($user, $user_action);

			$this -> hcmp_functions -> send_order_sms();

			$this -> session -> set_flashdata('system_success_message', "Facility Order No $new_order_no has Been Saved");
			redirect("reports/order_listing/$order_listing");

			endif;

			}

			public function update_facility_new_order() {
		//security check
		if ($this -> input -> post('commodity_id')) :
			$this -> load -> database();
			$data_array = array();
			$rejected = $this -> input -> post('rejected');
			$rejected_admin = $this -> input -> post('rejected_admin');
			$approved_admin = $this -> input -> post('approved_admin');
			$commodity_id = $this -> input -> post('commodity_id');
			//order details table details
			$quantity_ordered_pack = $this -> input -> post('quantity');
			$order_id = $this -> input -> post('order_number');
			$facility_order_details_id = $this -> input -> post('facility_order_details_id');
			$quantity_ordered_unit = $this -> input -> post('actual_quantity');
			(int)$price = $this -> input -> post('unit_cost');
			$o_balance = $this -> input -> post('open');
			$t_receipts = $this -> input -> post('receipts');
			$t_issues = $this -> input -> post('issues');
			$adjustpve = $this -> input -> post('adjustmentpve');
			$adjustnve = $this -> input -> post('adjustmentnve');
			$losses = $this -> input -> post('losses');
			$days = $this -> input -> post('days');
			$c_stock = $this -> input -> post('closing');
			$comment = $this -> input -> post('comment');
			$s_quantity = $this -> input -> post('suggested');
			(int)$amc = $this -> input -> post('amc');
			$workload = $this -> input -> post('workload');
			//order table details
			$bed_capacity = $this -> input -> post('bed_capacity');
			$drawing_rights = $this -> input -> post('drawing_rights');
			$order_total = $this -> input -> post('total_order_value');
			$order_no = $this -> input -> post('order_no');
			//$facility_code=$this -> session -> userdata('facility_id');
			//$user_id=$this->session->userdata('user_id');
			$order_date = date('y-m-d');
			$number_of_id = count($commodity_id);
            $subject=$file_name=$title=$info=$attach_file=null;
			for ($i = 0; $i < $number_of_id; $i++) {

			$orders = Doctrine_Manager::getInstance() -> getCurrentConnection() -> execute("INSERT INTO facility_order_details (  `id`,
			`order_number_id`,
			`commodity_id`,
			`quantity_ordered_pack`,
			`quantity_ordered_unit`,
			`price`,
			`o_balance`,
			`t_receipts`,
			`t_issues`,
			`adjustpve`,
			`losses`,
			`days`,
			`comment`,
			`c_stock`,
			`amc`,
			`adjustnve`)
			VALUES ($facility_order_details_id[$i],
			$order_id,
			$commodity_id[$i],
			$quantity_ordered_pack[$i],
			$quantity_ordered_unit[$i],
			$price[$i],
			$o_balance[$i],
			$t_receipts[$i],
			$t_issues[$i],
			$adjustpve[$i],
			$losses[$i],
			$days[$i],
			'$comment[$i]',
			$c_stock[$i],
			$amc[$i],
			$adjustnve[$i]
			)
			ON DUPLICATE KEY UPDATE
			`commodity_id`=$commodity_id[$i],
			`quantity_ordered_pack`=$quantity_ordered_pack[$i],
			`quantity_ordered_unit`=$quantity_ordered_unit[$i],
			`price`=$price[$i],
			`o_balance`=$o_balance[$i],
			`t_receipts`=$t_receipts[$i],
			`t_issues`=$t_issues[$i],
			`adjustpve`=$adjustpve[$i],
			`adjustnve`=$adjustnve[$i],
			`losses`=$losses[$i],
			`days`=$days[$i],
			`c_stock`=$c_stock[$i],
			`comment`='$comment[$i]',
			`amc`=$amc[$i],
			`order_number_id`=$order_id;");

			}//insert the data here
			
			$orders = Doctrine_Manager::getInstance() -> getCurrentConnection() -> 
			execute("UPDATE `facility_orders` SET `order_total` = $order_total,`order_total` = $order_total,`order_no` = $order_no
						,`workload` = $workload ,`bed_capacity` = $bed_capacity WHERE `facility_orders`.`id` = $order_id;");

			$myobj = Doctrine::getTable('facility_orders') -> find($order_id);
			$myobj -> workload = $workload;
			$myobj -> bed_capacity = $bed_capacity;
			$myobj -> order_no = $order_no;
			$myobj -> order_total = $order_total;						
			$facility_code=$myobj ->facility_code;
			

			$myobj1 = Doctrine::getTable('Facilities') -> findOneByfacility_code($facility_code);
			$facility_name = $myobj1 -> facility_name;
			$pdf_body = $this -> create_order_pdf_template($order_id);
			
			$file_name = $facility_name . '_facility_order_no_' . $order_id. "_date_created_" . date('d-m-y');
			
			$pdf_data = array("pdf_title" => "Order Report For $facility_name", 
			'pdf_html_body' => $pdf_body, 'pdf_view_option' => 'save_file', 'file_name' => $file_name);
			
			$this -> hcmp_functions -> create_pdf($pdf_data);// create pdf
			$this -> hcmp_functions -> clone_excel_order_template($order_id,'save_file',$file_name);//create excel
			
			$attach_file1='./pdf/'.$file_name.'.pdf';
			$attach_file2="./print_docs/excel/excel_files/".$file_name.'.xls';
			//echo $attach_file;
			
			//exit;
			
			if ($rejected == 1) {
				$myobj -> status = 1;
				$status = "Updated";
				$subject='Updated Order Report Pending Approval For '.$facility_name;				

				$attach_file='./pdf/'.$file_name.'.pdf';
			}
			if ($rejected_admin == 1) {
				$myobj -> status = 3;
				$status = "Rejected";
				$subject='Rejected Order Report For '.$facility_name;
				$info='<br> Note the order for  '.$facility_name.' Has been rejected by '.$approve_name1.' '.$approve_name2.'.
				<br>
		  		Find the attached order, correct it
				<br>';
		
			}
			if ($approved_admin == 1) {
				$myobj -> status = 2;
				$myobj -> approval_date = date('y-m-d');
				$myobj -> approved_by = $this -> session -> userdata('user_id');
				$status = "Approved";
				$subject='Approved Order Report For '.$facility_name;
			
			}
			
           $myobj -> save();
			if ($this -> session -> userdata('user_indicator') == 'district') :
				$order_listing = 'subcounty';
			elseif ($this -> session -> userdata('user_indicator') == 'county') :
				$order_listing = 'county';
			else :
				$order_listing = 'facility';
			endif;
			
          $message="<br>Please find the $status Order for  ".$facility_name.'
		  <br>'.$info.$pdf_body ;

		$response= $this->hcmp_functions->send_order_approval_email($message,$subject,$attach_file1."(more)".$attach_file2,$facility_code,$status);
		if($response){
			delete_files($attach_file1);
            delete_files($attach_file2);
			}
			else{

			}  
			//Test for sms
			//$this -> hcmp_functions -> order_update_sms($this -> session -> userdata('facility_id'),$status);
		$this -> session -> set_flashdata('system_success_message', "Facility Order No $order_id has Been $status");
		redirect("reports/order_listing/$order_listing");

		endif;

	}
     public function auto_save_order_detail(){
         //security check     
    if($this->input->is_ajax_request()):
            $commodity_id = $this -> input -> post('commodity_id');
            $order_details_id = $this -> input -> post('order_details_id');
            $batch_no = $this -> input -> post('batch_no');
            $manu = $this -> input -> post('manu');
            $clone_datepicker = $this -> input -> post('clone_datepicker');
            $quantity = $this -> input -> post('quantity');
            $actual_quantity = $this -> input -> post('actual_quantity');
            //build the query to run
            $orders = Doctrine_Manager::getInstance() -> 
            getCurrentConnection() -> execute("update facility_order_details 
             set
            `batch_no`='$batch_no',
            `quantity_recieved_pack`=$quantity,
            `quantity_recieved_unit`=$actual_quantity,
             `maun`='$manu',
            `expiry_date`='$clone_datepicker'
             where
            `id`=$order_details_id ");
            echo 'success';
    endif;
     }
      	/*
	 |--------------------------------------------------------------------------
	 | End of update_facility_new_order
	 |--------------------------------------------------------------------------
	 Next section update_order_delivery get the view that loads up the order delivery details
	 */
	public function update_order_delivery($order_id) {
		$facility_code = $this -> session -> userdata('facility_id');
		$data['content_view'] = "facility/facility_orders/update_order_delivery_from_kemsa_v";
		$data['title'] = "Facility Update Order Delivery";
		$data['facility_commodity_list'] = Commodities::get_all_from_supllier(1);
		$data['order_details']=facility_order_details::get_order_details($order_id);
		$data['general_order_details']=facility_orders::get_order_($order_id);
		$data['banner_text'] = "Facility Update Order Delivery";
		$this -> load -> view('shared_files/template/template', $data);

	}
	public function delete_facility_order($for, $order_id) {
		$reset_facility_order_table = Doctrine_Manager::getInstance() -> getCurrentConnection();
		$reset_facility_order_table -> execute("DELETE FROM `facility_order_details` WHERE order_number_id=$order_id; ");

		$reset_facility_order_table = Doctrine_Manager::getInstance() -> getCurrentConnection();
		$reset_facility_order_table -> execute("DELETE FROM `facility_orders` WHERE  id=$order_id; ");

		$this -> session -> set_flashdata('system_success_message', "Order Number $order_id has been deleted");
		redirect("reports/order_listing/$for");
	}

	public function get_facility_sorf($order_id = null, $facility_code = null) {
		if (isset($order_id) && isset($facility_code)) :
			$myobj = Doctrine::getTable('Facilities') -> findOneByfacility_code($facility_code);
			$facility_name = $myobj -> facility_name;
			// get the order form details here
			//create the pdf here
			$pdf_body = $this -> create_order_pdf_template($order_id);
			$file_name = $facility_name . '_facility_order_no_' . $new_order_no . "_date_created_" . date('d-m-y');
			$pdf_data = array("pdf_title" => "Order Report For $facility_name", 'pdf_html_body' => $pdf_body, 'pdf_view_option' => 'download', 'file_name' => $file_name);

			$this -> hcmp_functions -> create_pdf($pdf_data);
			endif;
			redirect();
	}
	public function create_order_pdf_template($order_no) {
		$from_order_table = facility_orders::get_order_($order_no);
		//get the order data here
		$from_order_details_table = Doctrine_Manager::getInstance() -> getCurrentConnection() -> fetchAll("SELECT 

    a.sub_category_name,
    b.commodity_name,
    b.commodity_code,
    b.unit_size,
    b.unit_cost,
    c.quantity_ordered_pack,
    c.quantity_ordered_unit,
    c.price,
    c.quantity_recieved,
    c.o_balance,
    c.t_receipts,
    c.t_issues,
    c.adjustnve,
    c.adjustpve,
    c.losses,
    c.days,
    c.comment,
    c.c_stock,
    c.s_quantity
    
FROM
    commodity_sub_category a,
    commodities b,
    facility_order_details c
WHERE
    c.order_number_id = $order_no
        AND b.id = c.commodity_id
        AND a.id = b.commodity_sub_category_id
group by b.id
ORDER BY a.id ASC , b.commodity_name ASC  ");
		// get the order details here
		$from_order_details_table_count = count($from_order_details_table);
		foreach ($from_order_table as $order) {
			$o_no = $order -> order_no;
			$o_bed_capacity = $order -> bed_capacity;
			$o_workload = $order -> workload;
			$o_date = $order -> order_date;
			$a_date = $order -> approval_date;
			$a_date = strtotime($a_date) ? date('d M, Y', strtotime($a_date)) : "N/A";
			//checkdate ( (int) date('m', $unixtime) , (int) date('d', $unixtime) , date('y', $unixtime ) ) ? date('d M, Y',$unixtime) : "N/A";
			$o_total = $order -> order_total;
			$d_rights = $order -> drawing_rights;
			$bal = $d_rights - $o_total;
			$creator = $order -> ordered_by;
			$approver = $order -> approved_by;
			$mfl = $order -> facility_code;

			$myobj = Doctrine::getTable('Facilities') -> findOneByfacility_code($mfl);
			$sub_county_id = $myobj -> district;
			// get the order form details here

			$myobj1 = Doctrine::getTable('Districts') -> find($sub_county_id);
			$sub_county_name = $myobj1 -> district;
			$county = $myobj1 -> county;

			$myobj2 = Doctrine::getTable('Counties') -> find($county);
			$county_name = $myobj2 -> county;

			$myobj_order = Doctrine::getTable('users') -> find($creator);
			$creator_email = $myobj_order -> email;
			$creator_name1 = $myobj_order -> fname;
			$creator_name2 = $myobj_order -> lname;
			$creator_telephone = $myobj_order -> telephone;

			$myobj_order_ = Doctrine::getTable('users') -> find($approver);
			$approver_email = $myobj_order_ -> email;
			$approver_name1 = $myobj_order_ -> fname;
			$approver_name2 = $myobj_order_ -> lname;
			$approver_telephone = $myobj_order_ -> telephone;

		}
		//create the table for displaying the order details
		$html_body = "<table class='data-table' width=100%>
<tr>
<td>MFL No: $mfl</td> 
<td>Health Facility Name:<br/> $facility_name</td>
<td>Total OPD Visits & Revisits: $o_workload </td>
<td>Level:</td>
<td>Dispensary</td>
<td>Health Centre</td>
</tr>
<tr>
<td>County: $county_name</td> 
<td> District: $sub_county_name</td>
<td>In-patient Bed Days :$o_bed_capacity </td>
<td>Order Date:<br/> " . date('d M, Y', strtotime($o_date)) . " </td>
<td>Order no. $o_no</td>
<td >Reporting Period <br/>
Start Date:  <br/>  End Date: " . date('d M, Y', strtotime($o_date)) . "
</td>
</tr>
</table>";
		$html_body .= "
<table class='data-table'>
<thead><tr><th><b>KEMSA Code</b></th><th><b>Description</b></th><th><b>Order Unit Size</b>
</th><th><b>Order Unit Cost</b></th><th ><b>Opening Balance</b></th>
<th ><b>Total Receipts</b></th><th><b>Total issues</b></th><th><b>Adjustments(-ve)</b></th><th><b>Adjustments(+ve)</b></th>
<th><b>Losses</b></th><th><b>Closing Stock</b></th><th><b>No days out of stock</b></th>
<th><b>Order Quantity (Packs)</b></th><th><b>Order Quantity (Units)</b></th><th>
<b>Order cost(Ksh)</b></th><th><b>Comment</b></th></tr> </thead><tbody>";

		$html_body .= '<ol type="a">';
		for ($i = 0; $i < $from_order_details_table_count; $i++) {
			if ($i == 0) {
				$html_body .= '<tr style="background-color:#C6DEFF;"> <td colspan="16" >
				 <li> ' . $from_order_details_table[$i]['sub_category_name'] . ' </li> </td></tr>';
			} else if ($from_order_details_table[$i]['sub_category_name'] != $from_order_details_table[$i - 1]['sub_category_name']) {
				$html_body .= '<tr style="background-color:#C6DEFF;"> <td  colspan="15"> 
       	 	<li> ' . $from_order_details_table[$i]['sub_category_name'] . ' </li> </td></tr>';
			}
			$adjpve = $from_order_details_table[$i]['adjustpve'];
			$adjnve = $from_order_details_table[$i]['adjustnve'];
			$c_stock = $from_order_details_table[$i]['c_stock'];
			$o_t = $from_order_details_table[$i]['quantity_ordered_pack'];
			$o_q = $from_order_details_table[$i]['price'];
			$o_bal = $from_order_details_table[$i]['o_balance'];
			$t_re = $from_order_details_table[$i]['t_receipts'];
			$t_issues = $from_order_details_table[$i]['t_issues'];
			$losses = $from_order_details_table[$i]['losses'];
			$adj = $adjpve + $adjnve;
			if ($o_bal == 0 && $t_re == 0 && $t_issues > 0) {
				$adj = $t_issues;
			}
			$c_stock = $o_bal + $t_re + $adj - $losses - $t_issues;

			if ($c_stock < 0) {
				$adj = $c_stock * -1;
			}
			$c_stock = $o_bal + $t_re + $adj - $losses - $t_issues;
			$html_body .= "<tr>";
			$html_body .= "<td>" . $from_order_details_table[$i]['commodity_code'] . "</td>";
			$html_body .= "<td>" . $from_order_details_table[$i]['commodity_name'] . "</td>";
			$html_body .= "<td>" . $from_order_details_table[$i]['unit_size'] . "</td>";
			$ot = number_format($o_t * $o_q, 2, '.', ',');
			$order_total = $order_total + ($o_t * $o_q);
			$html_body .= "<td>$o_q</td>";
			$html_body .= "<td>" . $o_bal . "</td>";
			$html_body .= "<td>" . $t_re . "</td>";
			$html_body .= "<td>" . $t_issues . "</td>";
			$html_body .= "<td>" . $adjnve . "</td>";
			$html_body .= "<td>" . $adjpve . "</td>";
			$html_body .= "<td>" . $losses . "</td>";
			$html_body .= "<td>" . $c_stock . "</td>";
			$html_body .= "<td>" . $from_order_details_table[$i]['days'] . "</td>";
			$html_body .= "<td>$o_t</td>";
			$html_body .= "<td>" . $from_order_details_table[$i]['quantity_ordered_unit'] . "</td>";
			$html_body .= "<td>$ot</td>";
			$html_body .= "<td>" . $from_order_details_table[$i]['comment'] . "</td></tr>";
		}

		$bal = $d_rights - $order_total;
		$html_body .= '</tbody></table></ol>';
		$html_body1 = '<table class="data-table" width="100%" style="background-color: 	#FFF380;">
		  <tr style="background-color: 	#FFFFFF;" > <td colspan="4" ><div style="width:100%">
		  <div style="float:right; width: 40%;"> Total Order Value:</div>
		  <div style="float:right; width: 40%;" >KSH ' . number_format($order_total, 2, '.', ',') . '</div> </div></td></tr>
		  <tr style="background-color: 	#FFFFFF;"  > 
		  <td colspan="4" ><div>
		  <div style="float: left" > Drawing Rights Available Balance:</div>
		  <div style="float: right" >KSH		' . number_format($bal, 2, '.', ',') . '</div> </td></tr>
		  <tr><td>FACILITY TEL NO:</td><td colspan="3">FACILITY EMAIL:</td>
		  </tr>
		  <tr><td >Prepared by (Name/Designation) ' . $creator_name1 . ' ' . $creator_name2 . '
		  <br/>
		  <br/>Email: ' . $creator_email . '</td><td>Tel: ' . $creator_telephone . '</td><td>Date: ' . date('d M, Y', strtotime($o_date)) . '</td><td>Signature</td>
		  </tr>
		  <tr><td>Checked by (Name/DPF/DPHN)  ' . $approver_name1 . ' ' . $approver_name2 . '
		  <br/>
		  <br/>Email: ' . $approver_email . '</td><td>Tel: ' . $approver_telephone . '</td><td>Date: ' . $a_date . '</td><td>Signature</td>
		  </tr>
		  <tr><td>Authorised by (Name/DMoH) 
		  <br/>
		  <br/>Email:</td><td>Tel: </td><td>Date:</td><td>Signature</td>		   
		  </tr>
		  </table>';

		return $html_body . $html_body1;

	}

}

<?php
/**
 * @author Kariuki
 */
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class issues extends MY_Controller {
	function __construct() {
		parent::__construct();

		$this -> load -> helper(array('form', 'url'));
		$this -> load -> library(array('hcmp_functions', 'form_validation'));
		$this -> load -> database();
	}

	/*
	 |--------------------------------------------------------------------------
	 | facility issuing to service points
	 |--------------------------------------------------------------------------
	 |1. load the view/ determine if its a redistribution/ internal issue
	 |2. check if the facility has commodity data
	 |4. save the data in the facility stock, facility transaction , issues table
	 */
	public function index($checker = NULL) {
		$facility_code = $this -> session -> userdata('facility_id');
		switch ($checker) :
			case 'internal' :
				$data['content_view'] = "facility/facility_issues/facility_issues_service_points_v";
				$data['title'] = "Issues to service points";
				$data['banner_text'] = "Issues to service points";
				break;
			case 'external' :
				$data['content_view'] = "facility/facility_issues/facility_redistribute_items_v";
				$data['subcounties'] = districts::getAll();
				$data['banner_text'] = "Redistribute Commodities";
				$data['title'] = "Redistribute Commodities";
				break;
			case 'district_store':	
					$district_id = $this -> session -> userdata('district_id');	
					$dist = districts::get_district_name_($district_id);	
					$data['district_id'] = $this -> session -> userdata('district_id');
					$data['district_data'] = districts::get_district_name_($district_id);
					$data['content_view'] = "facility/facility_issues/facility_redistribute_items_v";
					$data['donate_destination'] = "district";
					$data['subcounties']=districts::getAll();
					$data['banner_text'] = "Redistribute Commodities";
					$data['title'] ="Redistribute Commodities";						
				break;	
			default :
		endswitch;
		$data['service_point'] = service_points::get_all_active($facility_code);
		$data['commodities'] = facility_stocks::get_distinct_stocks_for_this_facility($facility_code, 1);
		$data_ = facility_stocks::get_distinct_stocks_for_this_facility($facility_code, "batch_data");
		foreach ($data_ as $key => $data_1) {
			$data_[$key]['commodity_name'] = preg_replace('/[^A-Za-z0-9\-]/', ' ', $data_1['commodity_name']);
		}
		$data['facility_stock_data'] = json_encode($data_);

		$this -> load -> view("shared_files/template/template", $data);
	}

	public function store_home(){
		$district_id = $this -> session -> userdata('district_id');	
		$data['expiry_data'] = Facility_stocks::drug_store_commodity_expiries($district_id);
		// echo "<pre>";print_r($data['expiry_data']);echo "</pre>";exit;

		$view = 'shared_files/template/template';
		    $data['content_view'] = "subcounty/subcounty_drug_store_home";	
			$data['district_dashboard_notifications']=$this->get_district_dashboard_notifications_graph_data();
			$data['title'] = "Drug Store Home";
		$data['banner_text'] = "Drug Store";
		$this -> load -> view($view, $data);
	}

	public function district_store(){
		$district_id = $this -> session -> userdata('district_id');	
						$dist = districts::get_district_name_($district_id);	
						$data['district_id'] = $this -> session -> userdata('district_id');
						$data['district_data'] = districts::get_district_name_($district_id);
						$data['content_view'] = "subcounty/subcounty_drug_store";
						$data['donate_destination'] = "facility";
						$data['subcounties']=districts::getAll();
						$data['banner_text'] = "Redistribute Commodities";
						$data['title'] ="Redistribute Commodities";		
						//$data['service_point']=service_points::get_all_active($facility_code);		
		$data['commodities'] = facility_stocks::get_distinct_stocks_for_this_district_store($district_id,1);
	    $data['facility_stock_data']=json_encode(facility_stocks::get_distinct_stocks_for_this_district_store($district_id,"batch_data"));	
     	$this -> load -> view("shared_files/template/template", $data);

	}

	public function district_store_issue()
	{
		//security check
		if($this->input->post('mfl')):
		$facility_code=$this -> session -> userdata('facility_id');
		$district_id = $this -> session -> userdata('district_id');	
		$service_point=array_values($this->input->post('mfl'));
		$commodity_id=array_values($this->input->post('desc'));
		$commodity_balance_before=array_values($this->input->post('commodity_balance'));
		$facility_stock_id=array_values($this->input->post('facility_stock_id'));
		$batch_no=array_values($this->input->post('batch_no'));
		$expiry_date=array_values($this->input->post('expiry_date'));
		$commodity_unit_of_issue=array_values($this->input->post('commodity_unit_of_issue'));
		$quantity_issued=array_values($this->input->post('quantity_issued'));
		$clone_datepicker_normal_limit_today=array_values($this->input->post('clone_datepicker_normal_limit_today'));
		$manufacture=array_values($this->input->post('manufacture'));
		$total_units=array_values($this->input->post('total_units'));
		$total_items=count($facility_stock_id);
		$data_array_issues_table=array();
		$data_array_redistribution_table=array();

		$commodity_ids=facility_stocks::get_district_store_commodities($district_id);
		
		$commodity_total = facility_stocks::get_district_store_total_commodities($district_id);

		$comm_count = count($commodity_total);
		// echo "<pre>";print_r($commodity_total);echo "</pre>";exit;

		// insertion of data to totals table
		$totals_data =array();
		$totals_data_ =array();
		
		
		// update the issues table 
		        for($i=0;$i<$total_items;$i++)://compute the actual stock

		$updated_ckecker = 1;
		foreach ($commodity_total as $comm) {
			if (in_array(($district_id), $comm)){
				if (in_array(($commodity_id[$i]), $comm)){
				$b = Doctrine_Manager::getInstance()->getCurrentConnection();
				$b->execute("UPDATE `drug_store_totals` SET `total_amount` = `total_amount`+$quantity_issued[$i] where commodity_id ='$commodity_id[$i]' and district_id = '$district_id'");
				$updated_ckecker = 2;
			    }
		    }//end of dist if
		}

		switch ($updated_ckecker) {
			case 1:
				$totals_data =array();
				$totals_data_ =array();

				$totals_data_ = array(
						 'district_id'=>$district_id,
					     'commodity_id' => $commodity_id[$i],
					     'total_amount' => $quantity_issued[$i]
					     );
				array_push($totals_data,$totals_data_);
			$this->db->insert_batch('drug_store_totals', $totals_data);
				break;
			case 2:
				break;
			default:
				break;
		}
		
	        $total_items_issues=($commodity_unit_of_issue[$i]=='Pack_Size')? 
	        $quantity_issued[$i]*$total_units[$i] : $quantity_issued[$i]; 
			
	     //prepare the issues data
	     $facility_name=isset($service_point[$i]) ? Facilities::get_facility_name2($service_point[$i]) : null;
		 $facility_name=isset($facility_name)? $facility_name['facility_name']: 'N/A';
		                 $mydata = array('facility_code' => $facility_code,	 
		                 's11_No'=>'(-ve Adj) Stock Deduction', 'batch_no' => $batch_no[$i] ,'commodity_id' => $commodity_id[$i],
					     'expiry_date' => date('y-m-d',strtotime($expiry_date[$i])),'qty_issued'=> $total_items_issues ,
					     'issued_to'=>"inter-facility donation:".$facility_name,'balance_as_of'=>$commodity_balance_before[$i], 
					     'date_issued'=>date('y-m-d',strtotime($clone_datepicker_normal_limit_today[$i])),'issued_by'=>$this -> session -> userdata('user_id'));
						 
						  $mydata_2 = array('manufacturer'=>$manufacture[$i],
						  'district_id' => $district_id,
						  'source_facility_code' => $facility_code,	 
		                 'batch_no' => $batch_no[$i] ,'commodity_id' => $commodity_id[$i],
					     'expiry_date' => date('y-m-d',strtotime($expiry_date[$i])),'quantity_sent'=> $total_items_issues ,
					     'receive_facility_code'=>$service_point[$i],'facility_stock_ref_id'=>$facility_stock_id[$i], 
					     'date_sent'=>date('y-m-d'),'sender_id'=>$this -> session -> userdata('user_id'));				
				// update the issues table 
				array_push($data_array_issues_table,$mydata); 
				array_push($data_array_redistribution_table,$mydata_2); 
	            // reduce the stock levels 
				$a = Doctrine_Manager::getInstance()->getCurrentConnection();
				$a->execute("UPDATE `facility_stocks` SET `current_balance` = `current_balance`-$total_items_issues where id='$facility_stock_id[$i]'");
	            //update the transaction table here 
				$inserttransaction = Doctrine_Manager::getInstance()->getCurrentConnection();
				$inserttransaction->execute("UPDATE `facility_transaction_table` SET `total_issues` = `total_issues`+$total_items_issues,
				`closing_stock`=`closing_stock`-$total_items_issues
	            WHERE `commodity_id`= '$commodity_id[$i]' and status='1' and facility_code='$facility_code';");	

		endfor;
				$user = $this -> session -> userdata('user_id');
				 $user_action = "redistribute";
				 Log::log_user_action($user, $user_action);
		         $this->db->insert_batch('facility_issues', $data_array_issues_table); 
				 $this->db->insert_batch('redistribution_data', $data_array_redistribution_table); 
		         $this->session->set_flashdata('system_success_message', "You have issued $total_items item(s)");
				 redirect();
		endif;
		redirect();	
	}//confirm distribution to district store

	public function district_store_external_issue()
		{
			//security check
			if($this->input->post('mfl')):

			$district_id = $this -> session -> userdata('district_id');
			$facility_code=$this -> session -> userdata('facility_id');
			$service_point=array_values($this->input->post('mfl'));
			$commodity_id=array_values($this->input->post('desc'));
			$commodity_balance_before=array_values($this->input->post('commodity_balance'));
			$facility_stock_id=array_values($this->input->post('facility_stock_id'));
			$batch_no=array_values($this->input->post('batch_no'));
			$expiry_date=array_values($this->input->post('expiry_date'));
			$commodity_unit_of_issue=array_values($this->input->post('commodity_unit_of_issue'));
			$quantity_issued=array_values($this->input->post('quantity_issued'));
			$clone_datepicker_normal_limit_today=array_values($this->input->post('clone_datepicker_normal_limit_today'));
			$manufacture=array_values($this->input->post('manufacture'));

			$total_units=array_values($this->input->post('total_units'));
			$total_items=count($facility_stock_id);
			$data_array_issues_table_store=array();
			$data_array_redistribution_table_store=array();
			        for($i=0;$i<$total_items;$i++)://compute the actual stock
			        
	        $total_items_issues=($commodity_unit_of_issue[$i]=='Pack_Size')? 
	        $quantity_issued[$i]*$total_units[$i] : $quantity_issued[$i]; 
			
		     //prepare the issues data
		     $facility_name=isset($service_point[$i]) ? Facilities::get_facility_name2($service_point[$i]) : null;
			 $facility_name=isset($facility_name)? $facility_name['facility_name']: 'N/A';
		                 $mydata = array(
		                 'facility_code' => $facility_code,	
		                 'district_id' => $district_id,
		                 's11_No'=>'(-ve Adj) Stock Deduction', 'batch_no' => $batch_no[$i] ,'commodity_id' => $commodity_id[$i],
					     'expiry_date' => date('y-m-d',strtotime($expiry_date[$i])),'qty_issued'=> $total_items_issues ,
					     'issued_to'=>"inter-facility donation:".$facility_name,'balance_as_of'=>$commodity_balance_before[$i], 
					     'date_issued'=>date('y-m-d',strtotime($clone_datepicker_normal_limit_today[$i])),'issued_by'=>$this -> session -> userdata('user_id'));
						 
						  $mydata_2 = array(
						  'manufacturer'=>$manufacture[$i],
						  'district_id' => $district_id,	
						  'source_facility_code' => $facility_code,	 
		                  'batch_no' => $batch_no[$i] ,'commodity_id' => $commodity_id[$i],
					      'expiry_date' => date('y-m-d',strtotime($expiry_date[$i])),'quantity_sent'=> $total_items_issues ,
					      'receive_facility_code'=>$service_point[$i],'facility_stock_ref_id'=>$facility_stock_id[$i], 
					      'date_sent'=>date('y-m-d'),'sender_id'=>$this -> session -> userdata('user_id'));				
				// update the issues table 
			array_push($data_array_issues_table_store,$mydata); 
			array_push($data_array_redistribution_table_store,$mydata_2); 
            // reduce the stock levels 
			$a = Doctrine_Manager::getInstance()->getCurrentConnection();
			$a->execute("UPDATE `drug_store_totals` SET `total_balance` = `total_balance`-$total_items_issues where commodity_id='$commodity_id[$i]' and district_id = $district_id");
            //update the transaction table here 
			$inserttransaction = Doctrine_Manager::getInstance()->getCurrentConnection();
			$inserttransaction->execute("UPDATE `drug_store_transaction_table` SET `total_issues` = `total_issues`+$total_items_issues,
			`closing_stock`=`closing_stock`-$total_items_issues
            WHERE `commodity_id`= '$commodity_id[$i]' and status='1' and district_id='$district_id';");		
		endfor;
		$user = $this -> session -> userdata('user_id');
		 $user_action = "redistribute";
		 Log::log_user_action($user, $user_action);
         $this->db->insert_batch('drug_store_issues', $data_array_issues_table_store); 
		 $this->db->insert_batch('redistribution_data', $data_array_redistribution_table_store); 
         $this->session->set_flashdata('system_success_message', "You have issued $total_items item(s)");
		 redirect();
		endif;
		redirect();		
	}//district store external issue

	public function confirm_store_external_issue($editable_=null){
		//seth
		$district_id = $this -> session -> userdata('district_id');
		$data['title'] ="Confirm Redistribution";	
		$data['banner_text'] = "Confirm Redistribution";
		$data['redistribution_data']=redistribution_data::get_all_active_drug_store($district_id,$editable_);
		$data['editable']=$editable_;
		$data['content_view'] = "subcounty/drug_store/drug_store_redistribute_items_confirmation_v";
		$this -> load -> view("shared_files/template/template", $data);		
	}

	// facility internal issue
	public function internal_issue() {
		//security check
		if ($this -> input -> post('service_point')) :
			$facility_code = $this -> session -> userdata('facility_id');
			$service_points = array_values($this -> input -> post('service_point'));

			$commodity_id = array_values($this -> input -> post('desc'));
			$commodity_balance_before = array_values($this -> input -> post('commodity_balance'));
			$facility_stock_id = array_values($this -> input -> post('facility_stock_id'));
			$batch_no = array_values($this -> input -> post('batch_no'));
			$expiry_date = array_values($this -> input -> post('expiry_date'));
			$commodity_unit_of_issue = array_values($this -> input -> post('commodity_unit_of_issue'));
			$quantity_issued = array_values($this -> input -> post('quantity_issued'));
			$clone_datepicker_normal_limit_today = array_values($this -> input -> post('clone_datepicker_normal_limit_today'));
			$total_units = array_values($this -> input -> post('total_units'));
			$total_items = count($facility_stock_id);
			print_r($total_units);
			for ($i = 0; $i < $total_items; $i++) ://compute the actual stock
				$total_items_issues = ($commodity_unit_of_issue[$i] == 'Pack_Size') ? $quantity_issued[$i] * $total_units[$i] : $quantity_issued[$i];
				//prepare the issues data

				$mydata = array('facility_code' => $facility_code, 's11_No' => 'internal issue', 'batch_no' => $batch_no[$i], 'commodity_id' => $commodity_id[$i], 'expiry_date' => date('y-m-d', strtotime($expiry_date[$i])), 'qty_issued' => $total_items_issues, 'issued_to' => $service_points[$i], 'balance_as_of' => $commodity_balance_before[$i], 'date_issued' => date('y-m-d', strtotime($clone_datepicker_normal_limit_today[$i])), 'issued_by' => $this -> session -> userdata('user_id'));

				// update the issues table
				facility_issues::update_issues_table($mydata);
				// reduce the stock levels
				$a = Doctrine_Manager::getInstance() -> getCurrentConnection();
				$a -> execute("UPDATE `facility_stocks` SET `current_balance` = `current_balance`-$total_items_issues where id='$facility_stock_id[$i]'");
				//update the transaction table here

				$inserttransaction = Doctrine_Manager::getInstance() -> getCurrentConnection();
				$inserttransaction -> execute(" UPDATE `facility_transaction_table` SET `total_issues` = `total_issues`+$total_items_issues,
			`closing_stock`=`closing_stock`-$total_items_issues
            WHERE `commodity_id`= '$commodity_id[$i]' and status='1' and facility_code='$facility_code';");

			endfor;

			//$user = $this -> session -> userdata('user_id');
			//$user_action = "issue";
			//Log::log_user_action($user, $user_action);
			$this -> session -> set_flashdata('system_success_message', "You have issued $total_items item(s)");
			redirect(home);
		endif;
		redirect(home);
	}

	public function external_issue() {
		//security check
		if ($this -> input -> post('mfl')) :
			$facility_code = $this -> session -> userdata('facility_id');
			$service_point = array_values($this -> input -> post('mfl'));
			$commodity_id = array_values($this -> input -> post('desc'));
			$commodity_balance_before = array_values($this -> input -> post('commodity_balance'));
			$facility_stock_id = array_values($this -> input -> post('facility_stock_id'));
			$batch_no = array_values($this -> input -> post('batch_no'));
			$expiry_date = array_values($this -> input -> post('expiry_date'));
			$commodity_unit_of_issue = array_values($this -> input -> post('commodity_unit_of_issue'));
			$quantity_issued = array_values($this -> input -> post('quantity_issued'));
			$clone_datepicker_normal_limit_today = array_values($this -> input -> post('clone_datepicker_normal_limit_today'));
			$manufacture = array_values($this -> input -> post('manufacture'));

			$total_units = array_values($this -> input -> post('total_units'));
			$total_items = count($facility_stock_id);
			//var_dump($total_units);exit;
			$data_array_issues_table = array();
			$data_array_redistribution_table = array();
			for ($i = 0; $i < $total_items; $i++) ://compute the actual stock

				$total_items_issues = ($commodity_unit_of_issue[$i] == 'Pack_Size') ? $quantity_issued[$i] * $total_units[$i] : $quantity_issued[$i];

				//prepare the issues data
				$facility_name = isset($service_point[$i]) ? Facilities::get_facility_name2($service_point[$i]) : null;
				$facility_name = isset($facility_name) ? $facility_name['facility_name'] : 'N/A';
				$mydata = array('facility_code' => $facility_code, 's11_No' => '(-ve Adj) Stock Deduction', 'batch_no' => $batch_no[$i], 'commodity_id' => $commodity_id[$i], 'expiry_date' => date('y-m-d', strtotime($expiry_date[$i])), 'qty_issued' => $total_items_issues, 'issued_to' => "inter-facility donation:" . $facility_name, 'balance_as_of' => $commodity_balance_before[$i], 'date_issued' => date('y-m-d', strtotime($clone_datepicker_normal_limit_today[$i])), 'issued_by' => $this -> session -> userdata('user_id'));

				$mydata_2 = array('manufacturer' => $manufacture[$i], 'source_facility_code' => $facility_code, 'batch_no' => $batch_no[$i], 'commodity_id' => $commodity_id[$i], 'expiry_date' => date('y-m-d', strtotime($expiry_date[$i])), 'quantity_sent' => $total_items_issues, 'receive_facility_code' => $service_point[$i], 'facility_stock_ref_id' => $facility_stock_id[$i], 'date_sent' => date('y-m-d'), 'sender_id' => $this -> session -> userdata('user_id'));
				// update the issues table
				array_push($data_array_issues_table, $mydata);
				array_push($data_array_redistribution_table, $mydata_2);
				// reduce the stock levels
				//var_dump($mydata);exit;
				$a = Doctrine_Manager::getInstance() -> getCurrentConnection();
				$a -> execute("UPDATE `facility_stocks` SET `current_balance` = `current_balance`-$total_items_issues where id='$facility_stock_id[$i]'");
				//update the transaction table here
				$inserttransaction = Doctrine_Manager::getInstance() -> getCurrentConnection();
				$inserttransaction -> execute("UPDATE `facility_transaction_table` SET `total_issues` = `total_issues`+$total_items_issues,
			`closing_stock`=`closing_stock`-$total_items_issues
            WHERE `commodity_id`= '$commodity_id[$i]' and status='1' and facility_code='$facility_code';");
			endfor;
			$user = $this -> session -> userdata('user_id');
			$user_action = "redistribute";
			Log::log_user_action($user, $user_action);
			$this -> db -> insert_batch('facility_issues', $data_array_issues_table);
			$this -> db -> insert_batch('redistribution_data', $data_array_redistribution_table);
			$this -> session -> set_flashdata('system_success_message', "You have issued $total_items item(s)");
			redirect(home);
		endif;
		redirect(home);
	}//confirm the external issue

	public function confirm_external_issue($editable = null) {
		$facility_code = $this -> session -> userdata('facility_id');
		$data['title'] = "Confirm Redistribution";
		$data['banner_text'] = "Confirm Redistribution";
		$data['redistribution_data'] = redistribution_data::get_all_active($facility_code, $editable);
		$data['editable'] = $editable;
		$data['content_view'] = "facility/facility_issues/facility_redistribute_items_confirmation_v";
		$this -> load -> view("shared_files/template/template", $data);
	}

	public function add_service_points() {
		$facility_code = $this -> session -> userdata('facility_id');
		$data['title'] = "Facility Service Points";
		$data['banner_text'] = "Facility Service Points";
		$data['service_point'] = service_points::get_all_active($facility_code, 'all');
		$data['content_view'] = "facility/facility_issues/add_service_points_v";
		$this -> load -> view("shared_files/template/template", $data);
	}// save service points

	public function save_service_points() {
		//security check
		if ($this -> input -> post('service_point')) :
			$service_point_name = $this -> input -> post('service_point');
			$service_for_all_facilities = 0;
			$date_of_entry = date('y-m-d H:i:s');
			// check if the user is super admin so that to set the item to all people
			if ($this -> session -> userdata('user_indicator') == 'super_admin') {$service_for_all_facilities = 1;
			}
			foreach ($service_point_name as $service_point_name) :
				$myarray = array('facility_code' => $this -> session -> userdata('facility_id'), 'service_point_name' => $service_point_name, 'for_all_facilities' => $service_for_all_facilities, 'date_added' => $date_of_entry, 'added_by' => $this -> session -> userdata('user_id'));
				//update the service points
				service_points::update_service_points($myarray);
			endforeach;
			$this -> session -> set_flashdata('system_success_message', "service points Have Been Updated");
			redirect('issues/add_service_points');
		endif;
		redirect(home);
	}

	//update the service point incase smthin changes
	public function update_service_point() {
		//security check
		if ($this -> input -> post('id')) :
			$service_point_id = $this -> input -> post('id');
			$service_point_name = $this -> input -> post('name');
			$service_status = $this -> input -> post('status');
			$service_for_all_facilities = $this -> input -> post('all_facilities');
			service_points::edit_service_point($service_point_id, $service_point_name, $service_for_all_facilities, $this -> session -> userdata('user_id'), $service_status);
			$this -> session -> set_flashdata('system_success_message', "service points Have Been Updated");
			redirect('issues/add_service_points');
		endif;
		redirect(home);
	}

}

<?php
/**
 * @author Kariuki
 */
class facility_orders extends Doctrine_Record {	
	public function setTableDefinition()
	{
		        $this->hasColumn('id', 'int');
				$this->hasColumn('order_date', 'date');
				$this->hasColumn('approval_date', 'date');
				$this->hasColumn('dispatch_date', 'date');
				$this->hasColumn('deliver_date', 'date');
				$this->hasColumn('dispatch_update_date', 'date');
				$this->hasColumn('facility_code', 'int');
				$this->hasColumn('order_no', 'int');
				$this->hasColumn('workload', 'int');
				$this->hasColumn('bed_capacity', 'int');
				$this->hasColumn('kemsa_order_id', 'int');
				$this->hasColumn('order_total', 'varchar',100);
				$this->hasColumn('deliver_total', 'varchar',100);
				$this->hasColumn('reciever_id', 'int');
				$this->hasColumn('drawing_rights', 'varchar',100);
				$this->hasColumn('reciever_id', 'int');
				$this->hasColumn('ordered_by', 'int');
				$this->hasColumn('approved_by', 'int');
				$this->hasColumn('dispatch_by', 'varchar',100);
				$this->hasColumn('warehouse', 'varchar',100);
				$this->hasColumn('source', 'int');
				$this->hasColumn('status', 'int');	
	}

	public function setUp() 
	{
		$this -> setTableName('facility_orders');
		$this->hasMany('facility_order_details as order_detail', array('local' => 'id', 'foreign' => 'order_number_id'));	
		$this->hasMany('facilities as facility_detail', array('local' => 'facility_code', 'foreign' => 'facility_code'));
		$this->hasMany('users as ordered_detail', array('local' => 'approved_by', 'foreign' => 'id'));
		$this->hasMany('users as dispatch_detail', array('local' => 'approved_by', 'foreign' => 'id'));	
		
	}
    public static function get_facility_order_summary_count($facility_code=null,$district_id=null,$county_id=null)
    {
    	$where_clause = isset($facility_code)? "f.facility_code=$facility_code ": (isset($district_id)? "d.id=$district_id ": "d.county=$county_id ") ;
		$orders = Doctrine_Manager::getInstance()->getCurrentConnection()
		->fetchAll("SELECT  f_o_s.`status_desc` as status, 
					count(f_o.`id`) as total 
					from facilities f, districts d,facility_order_status f_o_s, facility_orders f_o 
					where f.facility_code=f_o.facility_code 
					and f.district=d.id 
					and f_o.`status`= f_o_s.id 
					and $where_clause 
					GROUP BY f_o_s.id");
		
		return $orders ;	    	
    }
	////dumbing data into the issues table
	public static function update_orders_table($data_array){
		$o = new facility_orders();
	    $o->fromArray($data_array);
		$o->save();
		return TRUE;
	}
	public static function get_order_($order_id){
		$query = Doctrine_Query::create() -> select("*") -> from("facility_orders")->where("id=$order_id" );
		$order = $query -> execute();
		return $order;
		}
	public static function get_order_details($facility_code=null,$district_id=null,$county_id=null,$order_status)
	{
		if(isset($district_id) && $district_id>0):
		$and_data =" AND d.id ='$district_id' " ;
		elseif(isset($county_id) && $county_id>0):
		$and_data =" AND d.county ='$county_id' " ;
		else: $and_data =" AND f.facility_code ='$facility_code' " ;
		endif;
		
		$standard_query="
		SELECT o.id, d.district, f.facility_name, f.facility_code, o.order_date, date_format( o.order_date, '%b %Y' ) AS mwaka, o.order_total
		FROM districts d, facilities f, facility_orders o
		WHERE f.district = d.id
		AND o.facility_code = f.facility_code
		$and_data" ;
		
		if($order_status=="pending"):// pending approval
		$query=$standard_query."AND o.status =1";
		elseif($order_status=="approved"):
		$query=$standard_query."AND o.status =2";
		elseif($order_status=="rejected"):
		$query=$standard_query."AND o.status =3";
		else: $query=$standard_query."AND o.status =4";
		endif;
	
		$query_results=Doctrine_Manager::getInstance()->getCurrentConnection()->fetchAll($query);
	
		return $query_results;

	}

 
 public static function get_facility_order_details($order_id){
 	    $query_results=Doctrine_Manager::getInstance()->getCurrentConnection()->fetchAll("select f.facility_name, 
 	    f.facility_code, c.county, f_o.order_date from counties c, districts d, facilities f, 
 	    facility_orders f_o where f_o.facility_code=f.facility_code and f.district=d.id 
 	    and d.county=c.id and f_o.id=$order_id");

		return $query_results;
 }
 
  public static function get_facility_order_details($order_id){
 	    $query_results=Doctrine_Manager::getInstance()->getCurrentConnection()->fetchAll("select f.facility_name, 
 	    f.facility_code, c.county, f_o.order_date from counties c, districts d, facilities f, 
 	    facility_orders f_o where f_o.facility_code=f.facility_code and f.district=d.id 
 	    and d.county=c.id and f_o.id=$order_id");

		return $query_results;
 }
  
 public static function get_cost_of_orders($facility_code=null,$district_id=null,$county_id=null,$year=null,$month=null){
 	    $year_=date("Y");
		$month_=date("n");
		$and_data =(isset($district_id)&& ($district_id>0)) ?"AND d.id = '$district_id'" : null;
	 	$and_data .=(isset($facility_code)&& ($facility_code>0)) ?" AND f.facility_code = '$facility_code'" : null;
     	$and_data .=(isset($county_id)&&$county_id>0) ?" AND d.county='$county_id'" : '';
		$and_data .=($year=="NULL") ? " and YEAR(f_o.order_date) = $year_ ": " and YEAR(f_o.order_date) = $year ";
	
		$and_data .($month=='NULL') ? null: "and month(f_o.order_date) = $month_";
		$group_by=($month=='NULL') ? 'GROUP BY MONTH(f_o.order_date) asc': 'GROUP BY f_o.order_date asc';
		//echo ; exit;
		$query_results = Doctrine_Manager::getInstance()->getCurrentConnection()
 	    ->fetchAll("
 	  	select 
    	DATE_FORMAT(f_o.order_date ,'%b %d %y') as month,
    	sum(f_o.order_total) as total_orders
		from
    	facilities f,
    	districts d,
    	facility_orders f_o
		where
    	f_o.facility_code = f.facility_code
        and d.id=f.district
        $and_data
        $group_by
		");

		return $query_results;
 }

 public static function get_facility_orders($facility_code, $year)
 {
 	  $query_results = Doctrine_Manager::getInstance()->getCurrentConnection()
 	    ->fetchAll("
 	    select MONTHNAME( f_o.order_date) as month, f_o.order_total as total_orders 
		from facilities f, facility_orders f_o 
		where f_o.facility_code=f.facility_code 
		and f_o.facility_code = $facility_code
		GROUP BY MONTH( f_o.order_date ) asc");
		
		
		return $query_results;
 }
 public static function get_filtered_facility_orders($facility_code, $year, $month, $option)
 {
 	//echo $option;die;
 	switch ($option) :
         case 'ksh':
           $computation ="(fod.quantity_ordered_unit*d.unit_cost) as total";
             break;
         case 'units':
           $computation ="(fod.quantity_ordered_unit) AS total" ;
             break;
             case 'packs':
           $computation ="(fod.quantity_ordered_pack) AS total" ;
             break;
         default:
      $computation ="((fod.quantity_ordered_unit)*d.unit_cost) AS total";
          break;
     endswitch;		
 ($month == 0) ? $and_data = null: $and_data = "AND DATE_FORMAT( fo.order_date,'%m') = $month ";	
 ($year == 0) ? $and_data .= null: $and_data .= "AND YEAR(fo.order_date) = $year ";
$query_results = Doctrine_Manager::getInstance()->getCurrentConnection()
 	    ->fetchAll("
 	    SELECT d.commodity_name as name, $computation
		 FROM facilities f, commodities d, facility_orders fo, facility_order_details fod
		 WHERE fo.facility_code = f.facility_code
		AND fod.order_number_id = fo.id
		 AND d.id = fod.commodity_id
		 AND fo.facility_code = '$facility_code'
		$and_data
		GROUP BY name
				 ");

		return $query_results;
 }


 public static function get_filtered_cost_of_orders($facility_code, $month = null, $year = null)
 {
 	if(isset($month) && isset($year))
 	{
 		$inserttransaction = Doctrine_Manager::getInstance()->getCurrentConnection()
		->fetchAll("SELECT MONTHNAME( fo.order_date )as month, cms.commodity_name as commodity, CEIL((fo.order_total)*cms.unit_cost) as total_cost
			FROM facility_orders fo, commodities cms, facilities f 
			WHERE fo.facility_code = f.facility_code
			AND fo.status =  '4'
			AND fo.facility_code = $facility_code
			AND YEAR( fo.order_date ) = $year
			AND MONTH( fo.order_date ) = $month
			GROUP BY commodity asc");	
 	}
			
		return $inserttransaction ;
	
 }
 

 /*public static function get_cost_of_orders($facility_code,$year = null, $district_id = null, $county_id = null)
 {
 	$year = (isset($year)) ? $year: date("Y");
 	if(isset($district_id) && $district_id>0):
		$and_data =" AND d.id ='$district_id' " ;
		elseif(isset($county_id) && $county_id>0):
		$and_data =" AND d.county ='$county_id' " ;
		else: $and_data =" AND f.facility_code ='$facility_code' " ;
		endif;
 	$inserttransaction = Doctrine_Manager::getInstance()->getCurrentConnection()
		->fetchAll("SELECT o.order_date as date_order, date_format( o.order_date, '%b %Y' ) AS mwaka, o.order_total
			FROM districts d, facilities f, facility_orders o
			WHERE f.district = d.id
			AND o.facility_code = f.facility_code
			AND o.status != 3
			AND YEAR( o.order_date ) = $year
			$and_data
			order BY date_order asc");	
			
	return $inserttransaction ;
 }*/
}
	
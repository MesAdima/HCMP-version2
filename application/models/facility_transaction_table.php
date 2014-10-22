<?php
/**
 * @author Kariuki
 */
class facility_transaction_table extends Doctrine_Record {
		
	public function setTableDefinition()
	{
				$this->hasColumn('id', 'int');
				$this->hasColumn('facility_code', 'int');
				$this->hasColumn('commodity_id', 'int');
				$this->hasColumn('opening_balance', 'int');
				$this->hasColumn('total_receipts', 'int');
				$this->hasColumn('total_issues', 'int');
				$this->hasColumn('closing_stock', 'int');
				$this->hasColumn('days_out_of_stock', 'int');
				$this->hasColumn('date_added', 'date');
				$this->hasColumn('date_modified', 'date');
				$this->hasColumn('adjustmentpve', 'int');
				$this->hasColumn('adjustmentnve', 'int');
				$this->hasColumn('losses', 'int');
				$this->hasColumn('quantity_ordered', 'int');
				$this->hasColumn('comment', 'varchar', 100);
				$this->hasColumn('status', 'int');	
			
	}

	public function setUp() {
		$this -> setTableName('facility_transaction_table');	
		$this -> hasMany('commodities as commodity_detail', array('local' => 'commodity_id', 'foreign' => 'id'));
		
	}//get the data from the table
	 public static function get_all($facility_code){
        $query = Doctrine_Query::create() -> select("*") -> from("facility_transaction_table")->where("facility_code=$facility_code and status=1");
        $commodities = $query -> execute();
        return $commodities;
    }//save bulk data here 
	public static function update_facility_table($data_array){
		$o = new facility_transaction_table();
	    $o->fromArray($data_array);
		$o->save();		
		return TRUE;
	}//get if a facility has a given item
	public static function get_if_commodity_is_in_table($facility_code,$commodity_id){
		
	$query = Doctrine_Query::create() -> select("*") -> from("facility_transaction_table")
	->where("facility_code='$facility_code' and commodity_id='$commodity_id' and status='1'");
	$commodities = $query -> execute();
	$commodities = $commodities -> count();
	return $commodities;		}

    public static function get_commodities_for_ordering($facility_code,$for_a_facility=null){
    	//echo "Its ait till here";exit;
    	if(isset($for_a_facility)): // hack to ensure that when you are ordering for a facility that is not using hcmp they have all the items
    $items=Commodities::get_all_from_supllier(1);
	$temp=array();
	foreach ($items as $data){
	array_push($temp,array('sub_category_name'=>$data['sub_category_name'],'commodity_name'=>$data['commodity_name']
	,'unit_size'=>$data['unit_size'],'unit_cost'=>$data['unit_cost'],'commodity_code'=>$data['commodity_code'],'commodity_id'=>$data['commodity_id'],
	'total_commodity_units'=>$data['total_commodity_units'],'opening_balance'=>0,
	'total_receipts'=>0,
	'total_issues'=>0,
	'quantity_ordered'=>0,
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
	return $temp;
endif;
	 	$inserttransaction = Doctrine_Manager::getInstance()->getCurrentConnection()
->fetchAll("select `c`.`facility_code` AS `facility_code`,
`a`.`sub_category_name` AS `sub_category_name`,
`b`.`commodity_name` AS `commodity_name`,
`b`.`unit_size` AS `unit_size`,
`b`.`unit_cost` AS `unit_cost`,
`b`.`commodity_code` AS `commodity_code`,
`b`.`id` AS `commodity_id`,
`b`.total_commodity_units,
`c`.`opening_balance` AS `opening_balance`,
`c`.`total_receipts` AS `total_receipts`,
`c`.`total_issues` AS `total_issues`,
`c`.`quantity_ordered` AS `quantity_ordered`,
`c`.`comment` AS `comment`,
ceiling((`c`.`closing_stock` / `b`.`total_commodity_units`)) AS `closing_stock_`,
`c`.`closing_stock` AS `closing_stock`,
`c`.`days_out_of_stock` AS `days_out_of_stock`,
`c`.`date_added` AS `date_added`,
`c`.`losses` AS `losses`,
`c`.`status` AS `status`,
`c`.`adjustmentpve` AS `adjustmentpve`,
`c`.`adjustmentnve` AS `adjustmentnve`,
ifnull(ceiling(sum((`h`.`total_units` / `b`.`total_commodity_units`))),0) AS `historical` 
from  `commodities` `b`,`commodity_sub_category` `a` ,`facility_transaction_table` `c`
left join `facility_monthly_stock` `h` on (h.`facility_code`=$facility_code
and  `h`.`commodity_id` = `c`.`commodity_id`)
where (`b`.`id` = `c`.`commodity_id`
and `c`.`status` = '1' 
and `a`.`id` = `b`.`commodity_sub_category_id` 
and c.`facility_code`=$facility_code)
group by `c`.`facility_code`,`c`.`commodity_id` 
order by `a`.`sub_category_name` desc");
        return $inserttransaction ;
		
	 }
	
	
}
?>
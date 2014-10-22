<style type="text/css">
.row div p{
	padding:10px;
}
</style>
<link href="<?php echo base_url().'assets/bower_components/intro.js/introjs.css'?>" type="text/css" rel="stylesheet"/>
<div class="container-fluid" style="">

	<div class="row">
		<div class="col-md-4" id=""><p class="bg-info"><b>AMC - Average Monthly Consumption</b></p>
			
		</div>
		<div class="col-md-4 order-for-excel" id="" ><p class="bg" float = "right"><button type="button" class="remove btn btn-success btn-large"><span class="glyphicon glyphicon-save"></span>Upload order via Excel</button></p>
			
		</div>
	</div>
	</div>
		<!--<<div class="col-md-5" id=""><p class="bg-info"><span class="badge ">2</span> Select Batch No,input date issued,select issue type and quantity issued</p></div>
		<div class="col-md-3" id=""><p class="bg-info"><span class="badge ">3</span>To add more issues press add row
			<span class="glyphicon glyphicon-question-sign toolt" data-toggle="tooltip" data-placement="left" title="click for help" href="javascript:void(0);" onclick="startIntro();" style="margin-left:20%;"></span></p>
		
	</div>
	</div>
	<div class="row">
		<div class="col-md-6"><p class="text-danger">*Available Batch Stock is for a specific 
	batch, Total Balance is the total for the commodity</p></div>
		
	</div>-->
	<hr />
	<div class="container">
<div class="table-responsive" style="min-height:300px; overflow-y: auto;">
 <?php $att=array("name"=>'myform','id'=>'myform'); echo form_open('issues/internal_issue',$att); ?>
<table  class="table table-hover table-bordered table-update" id="facility_issues_table" >
<thead style="background-color: white">
	<th>Commodity Type</th>
	<th>Category</th>
	<th>Item Description</th>
	<th>Unit Size</th>
	<th>Stock Code</th>
	<th>Order Note</th>
	
	<th>Unit Cost (Ksh)</th>
	
	<th>Order Quantity</th>
	<th>Total Cost <br>(Ksh)</th>
	<th colspan="2">Action</th>
	<!--<th>Suggested <br>Order Quantity</th>
		<th>AMC (Packs)</th><th>Opening Balance</th>
	<th>Total Receipts</th>
    <th>Total issues</th>
    <th>Adjustments(-ve)</th>
    <th>Adjustments(+ve)</th>
    <th>Losses</th>
    <th>No days out of stock</th>
    <th>Closing Stock</th>
    
    <th>Actual Units</th>
    <th>Order Cost</th>	
    <th>Comment (if any)</th>
    <td><input class="form-control input-small amc" readonly="readonly" type="text" name="amc[]"  /></td>
    <td><input class="form-control input-small suggested" readonly="readonly" type="text" name="suggested[]" /></td>
		
    -->		    
					
</thead>
<tbody>
	<tr>
		<td>
			<select type="hidden" name="commodity_type" class="form-control input-small commodity_type" >
			<option value="0" >Select Type</option>
			<?php 
			
			foreach ($categories as $categories) :
				$category_id=$categories['id'];
				$category_name=$categories['category_name'];
				echo "<option  value='$category_id'>$category_name</option>";
								
			endforeach;
			//exit;
			?> 
			</select>
		</td>
		<td>
			<select name="mfl[0]" class="form-control input-small sub_category" style="width:110px !important;">
           		<option value="0">Select Category</option>
		   	</select>
		</td>
		<td>
			<select name="mfl[0]" class="form-control input-small commodity" style="width:110px !important;">
           		<option value="0">Select Commodity</option>
		   	</select>
			
		</td>
		<!--<td>
			<select type="hidden" name="categories" class="form-control input-small categories" >
			<option value="0" >Select Category</option>
			<?php 
			
			foreach ($categories as $categories) :
				$category_id=$categories['id'];
				$category_name=$categories['category_name'];
				echo "<option  value='$category_id'>$category_name</option>";
								
			endforeach;
			//exit;
			?> 
			</select>
		</td>-->
		<!--<td>
			<select type="hidden" name="commodity_name" class="form-control input-small commodity_name" >
			<option value="0" >Select Commodity</option>
			<?php 
			foreach ($facility_order as $facility_order) :						
						$commodity_name=$facility_order['commodity_name'];
						$category=$facility_order['sub_category_name'];
						
						$unit_size=$facility_order['unit_size'];
						$unit_cost=$facility_order['unit_cost'];
						$commodity_code=$facility_order['commodity_code'];
						$category=$facility_order['sub_category_name'];
						$category=$facility_order['sub_category_name'];
						$category=$facility_order['sub_category_name'];
						$category=$facility_order['sub_category_name'];
						$category=$facility_order['sub_category_name'];
						$category=$facility_order['sub_category_name'];
						
									
					echo "<option  value='$commodity_name'>$commodity_name</option>";
					echo "<option special_data='$commodity_id^$unit^$source_name^$total_commodity_units^$commodity_balance' value='$commodity_id'>$commodity_name</option>";		
			endforeach;
			?> 
			</select>
			<select class="form-control input-small service desc" name="desc[0]" >
    <option special_data="0" value="0" selected="selected" style="width:auto !important;">Select Commodity</option>
		<?php 
foreach ($facility_order as $facility_order) :						
			$commodity_name=$commodities['commodity_name'];
			$commodity_id=$commodities['commodity_id'];
			$unit=$commodities['unit_size'];
			$source_name=$commodities['source_name'];
			$total_commodity_units=$commodities['total_commodity_units'];
			$commodity_balance=$commodities['commodity_balance'];		
		echo "<option special_data='$commodity_id^$unit^$source_name^$total_commodity_units^$commodity_balance' value='$commodity_id'>$commodity_name</option>";		
endforeach;
		?> 		
	</select>
		</td>-->
		
		<td><input  type="text" class="form-control input-small unit_size" readonly="readonly" /></td>
		<td><input  type="text" class="form-control input-small commodity_code" readonly="readonly" /></td>
		<td><input  type="text" class="form-control input-small order_note" readonly="readonly" /></td>
		
		<td><input  type="text" class="form-control input-small unit_cost" readonly="readonly"  /></td>										
		<td><input class="form-control input-small quantity" type="text" name="quantity[]"/></td>
		<td><input type="text" class="form-control input-small cost" name="cost[]" readonly="yes"   /></td>	
		<td style="width:50px !important;" id="step8" ><button type="button" class="remove btn btn-success btn-xs"><span class="glyphicon glyphicon-plus-sign"></span>Add Row</button>
		</td><td><button type="button" class="remove btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus"></span>Delete Row</button></td>
			
														
											
	</tr>
</tbody>
</table>
</div>
</div>
<script>
$(document).ready(function() {
 	var $table = $('table');
 	//float the headers
  	$table.floatThead({
  		scrollingTop: 100,
	 	zIndex: 1001,
	 	scrollContainer: function($table){ return $table.closest('.table-responsive'); }
	});	
	
	//step one load all the facility data here
	var facility_stock_data = <?php echo $facility_order;?>;
	
	///when changing the commodity select box
	$(".commodity").on('change',function(){
		var row_id=$(this).closest("tr").index();	
  		var locator=$('option:selected', this);
		var data =$('option:selected', this).attr('special_data'); 
       	var data_array=data.split("^");	
       //	console.log(data_array);
       	
       	locator.closest("tr").find(".unit_size").val(data_array[1]);
     	locator.closest("tr").find(".supplier_name").val(data_array[2]);
     	locator.closest("tr").find(".commodity_id").val(data_array[0]);
     	locator.closest("tr").find(".available_stock").val("");
     	locator.closest("tr").find(".total_units").val(data_array[3]);
     	locator.closest("tr").find(".expiry_date").val("");
     	locator.closest("tr").find(".quantity_issued").val("0");
     	locator.closest("tr").find(".clone_datepicker_normal_limit_today").val("");	
	
	});
	
	//when a commodity type is selected
	$('.commodity_type').on("change", function() {
		var locator = $('option:selected', this);
		json_obj = {"url":"<?php echo site_url("orders/get_sub_categories");?>",}
		var baseUrl = json_obj.url;
		var id = $(this).val();
	    var dropdown;
		$.ajax({
		  type: "POST",
		  url: baseUrl,
		  data: "category="+id,
		  success: function(msg)
		  {
		  	var values = msg.split("_");
  			var txtbox;
	  		for (var i=0; i < values.length-1; i++) 
	  		{
	  			var id_value = values[i].split("*");				  					  			
	  			dropdown+="<option value="+id_value[0]+">";
				dropdown+=id_value[1];						
				dropdown+="</option>";		  			
	  		}	
		  },
			  error: function(XMLHttpRequest, textStatus, errorThrown) {
			       if(textStatus == 'timeout') {}
			   }
			}).done(function( msg ) {			
				locator.closest("tr").find(".sub_category").html(dropdown);
			});				
		});
		//When a sub_category is selected the commodities listed change
		$('.sub_category').on("change", function() {
		var locator = $('option:selected', this);
		json_obj = {"url":"<?php echo site_url("orders/get_commodities_meds");?>",}
		var baseUrl = json_obj.url;
		var id = $(this).val();
	    var dropdown;
		$.ajax({
		  type: "POST",
		  url: baseUrl,
		  data: "sub_category="+id,
		  success: function(msg)
		  {
		  	//alert(msg);return
		  	var values = msg.split("_");
		  	var x = values[0];
		  	alert(x);return
  			var txtbox;
	  		for (var i=0; i < values.length-1; i++) 
	  		{
	  			alert(values[i]);return
	  			var id_value = values[i].split("^");	
	  			console.log(id_value);			  					  			
	  			dropdown+="<option value="+id_value[1]+">";
				dropdown+=id_value[2];						
				dropdown+="</option>";	
					  			
	  		}	
		  },
			  error: function(XMLHttpRequest, textStatus, errorThrown) {
			       if(textStatus == 'timeout') {}
			   }
			}).done(function( msg ) {			
				locator.closest("tr").find(".commodity").html(dropdown);
			});				
		});
		
		$(".order-for-excel").on('click', function(e) {
                  e.preventDefault(); 
    var body_content='<?php  $att=array("name"=>'myform','id'=>'myform');
    echo form_open_multipart('orders/facility_order_meds',$att)?>'+
'<input type="file" name="file" id="file" required="required" class="form-control"><br>'+
'<button class="upload">Upload</button>'+
'</form>';
   //hcmp custom message dialog
    dialog_box(body_content,'');        
    });		
});	
</script>
<script src="<?php echo base_url().'assets/bower_components/intro.js/intro.js'?>" type="text/javascript"></script>
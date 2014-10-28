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
		<div class="col-md-4" id="" ><p class="bg" float = "right"><button type="button" class="remove btn btn-success btn-large"><span class="glyphicon glyphicon-save"></span>Upload order via Excel</button></p>
			
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
 <?php $att=array("name"=>'myform','id'=>'myform'); echo form_open('orders/facility_meds_order',$att); ?>
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
			<select type="hidden" name="commodity_type[]" class="form-control input-small commodity_type" >
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
			<select name="mfl[]" class="form-control input-small sub_category" style="width:110px !important;">
           		<option value="0">Select Category</option>
		   	</select>
		</td>
		<td>
			<select name="commodity_code[]" class="form-control input-small commodity" style="width:110px !important;">
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
		<td><input class="form-control input-small quantity" id="quantity" type="text" name="quantity[]"/></td>
		<td><input type="text" class="form-control input-small cost" name="cost[]" readonly="yes"   /></td>	
		<td style="width:50px !important;" id="step8" ><button type="button" class="add btn btn-success btn-xs"><span class="glyphicon glyphicon-plus-sign"></span>Add Row</button>
		</td><td><button type="button" class="remove btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus"></span>Delete Row</button></td>
			
														
											
	</tr>
</tbody>
</table>

<?php echo form_close(); ?>
</div>
</div>
<hr />
<div class="container-fluid">
<div style="float: right;">
<button type="button" class="add btn btn-primary"><span class="glyphicon glyphicon-plus"></span>Add Item</button>
<button type="button" class="btn btn-success test"><span class="glyphicon glyphicon-open"></span>Save</button></div>
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
	
	$(document).on('keyup','.quantity', function (){
		//alert()
	var selector_object=$(this);
	var user_input=$(this).val();
	var total_units=$(this).closest("tr").find(".total_commodity_units").val();
	var unit_cost=$(this).closest("tr").find(".unit_cost").val();
	// check the user input value here
	var alert_message='';
			if (selector_object.val() <0) { alert_message+="<li>Order value must be above 0</li>";}
		    if (selector_object.val().indexOf('.') > -1) {alert_message+="<li>Decimals are not allowed.</li>";}		
			if (isNaN(selector_object.val())){alert_message+="<li>Enter only numbers</li>";}				
			if(isNaN(alert_message)){
	//reset the text field and the message dialog box 
    selector_object.val(""); var notification='<ol>'+alert_message+'</ol>&nbsp;&nbsp;&nbsp;&nbsp;';
    //hcmp custom message dialog
    dialog_box(notification,'<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>');
    //This event is fired immediately when the hide instance method has been called.
    $('#communication_dialog').on('hide.bs.modal', function (e) { selector_object.focus();	})
    calculate_totals();
    $(this).closest("tr").find(".actual_quantity").val("");
	$(this).closest("tr").find(".cost").val("");
    return;   } 
    if(user_input==''){
    	user_input=0;
    }
	
	// set the order total here
	calculate_totals();	
	});// process all the order into a summary table for the user to confirm before placing the order bed_capacity workload

	///when changing the commodity select box
	$(".commodity").on('change',function(){
  		var locator=$('option:selected', this);
		var row_id=$(this).closest("tr").index();	
		var data =$('option:selected', this).attr('special_data'); 
		var stock_id =$('option:selected', this).val(); 
		var unit_price =$('option:selected', this).attr('data-unit-price'); 
       	var data_array=data.split("^");	
       	// alert(data_array[2]);return;
       	console.log(data);
       	locator.closest("tr").find(".unit_size").val(data_array[0]);
     	locator.closest("tr").find(".order_note").val(data_array[1]);
     	locator.closest("tr").find(".unit_cost").val(unit_price);
     	locator.closest("tr").find(".available_stock").val("");
     	locator.closest("tr").find(".commodity_code").val(stock_id);
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
		//adding a row
		$(".add").click(function() {
        var selector_object = $('#facility_issues_table tr:last');
        // alert("works");return;
        var form_data = check_if_the_form_has_been_filled_correctly(selector_object);
        if(isNaN(form_data[0])){
        var notification='<ol>'+form_data[0]+'</ol>&nbsp;&nbsp;&nbsp;&nbsp;';
           //hcmp custom message dialog
        dialog_box(notification,'<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>');
        return;   }// set the balance here
			//set the quantities to readonly  $("#dropdown").prop("disabled", true);
			selector_object.closest("tr").find(".quantity_issued").attr('readonly','readonly');
			selector_object.closest("tr").find(".batch_no").attr("disabled", true);
			selector_object.closest("tr").find(".commodity_unit_of_issue").attr("disabled", true);
			selector_object.closest("tr").find(".desc").attr("disabled", true);				
			//reset the values of current element 
		  clone_the_last_row_of_the_table();
		});	/////batch no change event

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
		  	// alert(msg);return;
		  	var values = msg.split("_");
		  	var x = values[0];
  			var txtbox;
	  		for (var i=0; i < values.length-1; i++) 
	  		{
	  			// dropdown ="";
	  			var id_value = values[i].split("^");		  					  			
	  			// alert(id_value[3]);return
	  			// var special_data_values = id_value[1] + "^" +id_value[2]+"^"+id_value[3];
	  			// alert(special_data_values);return;
	  			dropdown+="<option value="+id_value[0]+" special_data="+id_value[1]+"^"+id_value[2]+"^ data-unit-price = "+id_value[3]+">";
				dropdown+=id_value[4];						
				dropdown+="</option>";

				// alert(dropdown);return;
	  		}	
		  },
			  error: function(XMLHttpRequest, textStatus, errorThrown) {
			       if(textStatus == 'timeout') {}
			   }
			}).done(function( msg ) {			
				locator.closest("tr").find(".commodity").html(dropdown);
			});				
		});//end of ajax
	
		$('.test').on('click','', function (){
	var table_data='<div class="row" style="padding-left:2em"><div class="col-md-6"><h4>Order Summary</h4></div></div>'+
    '<table class="table table-hover table-bordered table-update">'+
					"<thead><tr>"+
					"<th>Description</th>"+
					"<th>Commodity Code</th>"+
					"<th>Order Quantity</th>"+
					"<th>Unit Cost Ksh</th>"+
					"<th>Total Ksh</th></tr></thead><tbody>";	 	    			
        $("input[name^=cost]").each(function(i) { 
         	//$(document).each('','input[name^=cost]', function (i){
         var C_name=$(this).closest("tr").find(".commodity option:selected").text()
         //alert(C_name);
         //return;
        table_data +="<tr>" +
							"<td>" +C_name+ "</td>" +
							"<td>" +$(this).closest("tr").find(".commodity_code").val()+ "</td>" +
							"<td>" +$(this).closest("tr").find(".quantity").val()+ "</td>" +	
							"<td>" +number_format($(this).closest("tr").find(".unit_cost").val(), 2, '.', ',')+ "</td>" +	
							"<td>" +number_format($(this).closest("tr").find(".cost").val(), 2, '.', ',')+ "</td>" +													
						"</tr>" 
                    });
         table_data +="</tbody></table>";
    //hcmp custom message dialog
    dialog_box(table_data,'<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>'
    +'<button type="button" class="btn btn-primary" id="save_dem_order" data-dismiss="modal">Save</button>');
	});

	$('#main-content').on('click','#save_dem_order',function() {
     var order_total=$('#total_order_value').val();
     var workload=$('#workload').val();
     var bed_capacity=$('#bed_capacity').val();
     var alert_message='';
     if (order_total==0) {alert_message+="<li>Sorry, you can't submit an Order Value of Zero</li>";}
     if (workload=='') {alert_message+="<li>Indicate Total OPD Visits & Revisits</li>";}
     if (bed_capacity=='') {alert_message+="<li>Indicate In-patient Bed Days</li>";}
     if(isNaN(alert_message)){
     //This event is fired immediately when the hide instance method has been called.
    $('#quantity').delay(500).queue(function (nxt){
    // Load up a new modal...
     dialog_box('Fix these items before saving your Order <ol>'+alert_message+'</ol>&nbsp;&nbsp;&nbsp;&nbsp;',
     '<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>');
    	nxt();
    });
     }else{//seth
    $('#quantity').delay(500).queue(function (nxt){
				// alert("I work");return;
    // Load up a new modal...
    var img='<img src="<?php echo base_url('assets/img/wait.gif') ?>"/>';
     dialog_box(img+'<h5 style="display: inline-block; font-weight:500;font-size: 18px;padding-left: 2%;"> Please wait as the order is being processed</h5>',
     '');
    	nxt();
    	$("#myform").submit();
    });
     	
     }
     });

		$('.remove').on('click',function(){
			var data_ =$('option:selected', $(this).closest("tr").find('.commodity')).val(); 
			// alert(data_);return;
	       	var data_array=data_.split("^");
			var row_id=$(this).closest("tr").index();
			var count_rows=0;
			var bal=parseInt(calculate_actual_stock(data_array[3],$(this).closest("tr").find(".commodity_unit_of_issue").val(),
    parseInt($(this).closest("tr").find(".quantity_issued").val()),'return',''))			
            var commodity_stock_id=parseInt($(this).closest("tr").find(".facility_stock_id").val());   
            var total=0;
            var commodity_id=$(this).closest("tr").find(".commodity_id").val();  

			$("input[name^=commodity_id]").each(function(index, value) {				
			var new_id=$(this).closest("tr").index();
			var total_current_issues=$(this).closest("tr").find(".quantity_issued").val();
			var current_facility_stock_id=$(this).closest("tr").find(".facility_stock_id").val();
                  if(new_id>row_id && parseInt($(this).val())==commodity_id){ // check for total issues for this item              	 
                  var value=parseInt($(this).closest("tr").find(".commodity_balance").val())+bal;  ///available_stock
                   $(this).closest("tr").find(".commodity_balance").val(value);
                   $(this).closest("tr").find(".balance").val(value);                  
                  }                
                  if(new_id>row_id && current_facility_stock_id==commodity_stock_id){// check for total issues for batch this item
                   var value=parseInt($("input[name='available_stock["+new_id+"]']").val())+bal;  ///available_stock
                   $("input[name='available_stock["+new_id+"]']").val(value);
                  }
                 count_rows++;
		        });
	       //finally remove the row 
	       if(count_rows==1){
	       	 clone_the_last_row_of_the_table();
	       	 $(this).parent().parent().remove(); 
	       }else{
	       	$(this).parent().parent().remove(); 
	       }	         
      });

		function clone_the_last_row_of_the_table(){
            var last_row = $('#facility_issues_table tr:last');
            var cloned_object = last_row.clone(true);
            var table_row = cloned_object.attr("row_id");
            var next_table_row = parseInt(table_row) + 1;           
		    cloned_object.attr("row_id", next_table_row);
			cloned_object.find(".service_point").attr('name','service_point['+next_table_row+']');
			cloned_object.find(".facility").attr('name','mfl['+next_table_row+']'); 
			cloned_object.find(".commodity_id").attr('name','commodity_id['+next_table_row+']'); 
			cloned_object.find(".commodity_id").attr('id',next_table_row); 
			cloned_object.find(".quantity_issued").attr('name','quantity_issued['+next_table_row+']'); 	
			cloned_object.find(".clone_datepicker_normal_limit_today").attr('name','clone_datepicker_normal_limit_today['+next_table_row+']'); 
			cloned_object.find(".available_stock").attr('name','available_stock['+next_table_row+']'); 
			cloned_object.find(".facility_stock_id").attr('name','facility_stock_id['+next_table_row+']'); 
			cloned_object.find(".batch_no").attr('name','batch_no['+next_table_row+']');
			cloned_object.find(".commodity_unit_of_issue").attr('name','commodity_unit_of_issue['+next_table_row+']');
			cloned_object.find(".expiry_date").attr('name','expiry_date['+next_table_row+']');
			cloned_object.find(".desc").attr('name','desc['+next_table_row+']');
			cloned_object.find(".commodity_balance").attr('name','commodity_balance['+next_table_row+']');	
			cloned_object.find(".manufacture").attr('name','manufacture['+next_table_row+']');					
            cloned_object.find("input").attr('value',"");     
            cloned_object.find(".quantity_issued").attr('value',"0");   
            cloned_object.find(".quantity_issued").removeAttr('readonly');  
            cloned_object.find(".batch_no").removeAttr('disabled');
            cloned_object.find(".commodity_unit_of_issue").removeAttr('disabled'); 
            cloned_object.find(".desc").removeAttr('disabled');   
            cloned_object.find(".commodity_balance").attr('value',"0");            
            cloned_object.find(".batch_no").html("");  
            // remove the error class
            cloned_object.find("label.error").remove();           
			cloned_object.insertAfter('#facility_issues_table tr:last').find('input').val('');;	
			refresh_clone_datepicker_normal_limit_today();	
        }

		function check_if_the_form_has_been_filled_correctly(selector_object){
		var alert_message='';
		var service_point = selector_object.closest("tr").find(".service_point").val();
		var commodity_id = selector_object.closest("tr").find(".desc").val();
		var issue_date = selector_object.closest("tr").find(".clone_datepicker_normal_limit_today").val();
		//var issue_quantity = selector_object.closest("tr").find(".quantity_issued").val();

		var service_point=selector_object.closest("tr").find(".service_point").val();
		var commodity_id=selector_object.closest("tr").find(".desc").val();
		var issue_date=selector_object.closest("tr").find(".clone_datepicker_normal_limit_today").val();
		//var issue_quantity=selector_object.closest("tr").find(".quantity_issued").val();

		var facility=selector_object.closest("tr").find(".facility").val();
		//set the message here
		if (facility==0) {alert_message+="<li>Select a Facility First</li>";}
		if (service_point==0) {alert_message+="<li>Select a Service Point</li>";}
	    if (commodity_id==0) {alert_message+="<li>Select a commodity</li>";}
	    if (issue_date==0 || issue_date=='') {alert_message+="<li>Indicate the date of the issue</li>";}	
	   // if (issue_quantity==0) {alert_message+="<li>Indicate how much you want to issue</li>";}	    

	    return[alert_message,service_point,commodity_id,issue_date];	

		}//extract facility_data  from the json object 	

		function calculate_totals(){
	var order_total=0;
	var balance=0
	 $("input[name^=quantity]").each(function() {

	 	if($(this).val()=='')
	 	{ var total=0} 
	 	else{ var total=$(this).val()
	 		}//calculate the balances here
	 	var unit_cost=$(this).closest("tr").find(".unit_cost").val();
	 	var actual_units=parseInt($(this).closest("tr").find(".total_commodity_units").val())*total;
	 	var total_cost=parseInt(total)*parseInt(unit_cost.replace(",", ""));    	
        order_total=(total_cost)+parseInt(order_total); 
	    $(this).closest("tr").find(".actual_quantity").val(actual_units);
	    $(this).closest("tr").find(".cost").val(total_cost);   
     });//check if order total is a NAN
    //calculate the balances here
     //set the balances here
     $("#total_order_balance_value").val(balance)
     $("#total_order_value").val(order_total);
		
	}

});	
</script>
<script src="<?php echo base_url().'assets/bower_components/intro.js/intro.js'?>" type="text/javascript"></script>
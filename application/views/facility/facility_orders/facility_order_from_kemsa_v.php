<?php //echo "This is the one";exit; ?>
<style>
 	.input-small{
 		width: 55px !important;
 	}
 	.row div p{
	padding:10px;
}
 </style>
 <div class="container-fluid">
 <div style="width: 100%; margin: auto;">

<div class="row">
		<div class="col-md-8"><p class="bg-info">
			<span  class='' style="display: inline">
				Enter Order Quantity and Comment,Order Quantity (Quarterly) = ((Average Monthly Consumption * <span id="month" style="display: inline"> 3</span>) - Closing Stock) + AMC</span>
		</p></div>
		
	</div>


<?php  $att=array("name"=>'myform','id'=>'myform'); echo form_open('orders/facility_new_order',$att); //?>
<div class="row" style="padding-left: 1%;margin-bottom: 5px;">
	<div class="col-md-2">
		
	<b>*Order Frequency</b><input  type="text" class="form-control input-large commodity_code" readonly="readonly" value="Quarterly" /> 
	</div>
	<div class="col-md-1">
     <b>*Order No:</b> <input type="text" class="form-control input_text" name="order_no" id="order_no" id="order_no" required="required"/>
	</div>
<div class="col-md-2">
	 <b>*In-patient Bed Days:</b><input type="text" class="form-control  input_text" name="bed_capacity" id="bed_capacity" required="required"/>
</div>	
<div class="col-md-2">
	 <b>*Total OPD Visits & Revisits:</b><input type="text" class="form-control input_text" name="workload" id="workload" required="required"/>
</div>
<div class="col-md-2">
<b>Total Order Value(KSH)</b>
<input type="text" class="form-control" name="total_order_value" id="total_order_value" readonly="readonly" value="0"/>	
<input type="hidden" id="actual_drawing_rights" name="drawing_rights" value="<?php echo $drawing_rights; ?>" />				
</div>
<!--<div class="col-md-3">
<b>Drawing Rights Available Balance(KSH) :</b>
<input type="text" class="form-control" name="total_order_balance_value" 
id="total_order_balance_value" readonly="readonly" value="<?php echo $drawing_rights; ?>"/>	
<input name="facility_code" type="hidden" value="<?php echo isset($facility_code)? $facility_code :$this -> session -> userdata('facility_id'); ?>" />					
</div>-->
</div>
<table width="100%" border="0" class="row-fluid table table-hover table-bordered table-update"  id="example">
<thead>
<tr style="background-color: white">
						<th>Category</th>
						<th>Description</th>
						<th>Commodity&nbsp;Code</th>
						<th>Order Unit Size</th>
						<th>Order Unit Cost (Ksh)</th>
						<th>Opening Balance</th>
						<th>Total Receipts</th>
					    <th>Total issues</th>
					    <th>Adjustments(-ve)</th>
					    <th>Adjustments(+ve)</th>
					    <th>Losses</th>
					    <th>No days out of stock</th>
					    <th>Closing Stock</th>
					    <th>AMC (Packs)</th>
					    <th>Suggested Order Quantity</th>
					    <th>Order Quantity</th>
					    <th>Actual Units</th>
					    <th>Order Cost</th>	
					    <th>Comment (if any)</th>				    
	</tr>
</thead>
<tbody>
								<?php
								 $count=0; $thr=true; 
							$j=count($facility_order);
								for($i=0;$i<$j;$i++){ ?>
						<tr>
							<td><?php echo $facility_order[$i]['sub_category_name'];?></td>
	
							<?php 
							      $price=$facility_order[$i]['unit_cost'];
								  $price=str_replace(",", '',$price);
									 
					?>
					<input class="commodity_code" type="hidden" name="commodity_code[<?php echo $i; ?>] " value="<?php echo $facility_order[$i]['commodity_code'];?>" />
	<input class="total_commodity_units" type="hidden" name="total_commodity_units[<?php echo $i; ?>] " value="<?php echo $facility_order[$i]['total_commodity_units'];?>" />
	<input class="commodity_id" type="hidden" name="commodity_id[<?php echo $i; ?>] " value="<?php echo $facility_order[$i]['commodity_id'];?>" />
	<input class="commodity_name" type="hidden" name="commodity_name[<?php echo $i; ?>] " value="<?php echo $facility_order[$i]['commodity_name'];?>" />
	<input class="price" type="hidden" name="price[<?php echo $i; ?>] " value="<?php echo $price;?>" />
	<input class="unit_size" type="hidden" name="unit_size[<?php echo $i; ?>] " value="<?php echo $facility_order[$i]['unit_size'];?>" />
	<input class="unit_cost" type="hidden" name="unit_cost[<?php echo $i; ?>] " value="<?php echo $facility_order[$i]['unit_cost'];?>" />
							<td><?php echo $facility_order[$i]['commodity_name']?></td>
							<td><?php echo $facility_order[$i]['commodity_code'];?></td>
							<td><?php echo $facility_order[$i]['unit_size']?> </td>
							<td><?php echo $facility_order[$i]['unit_cost']; ?> </td>
							<td><input class="form-control input-small" readonly="readonly" type="text" name="open[<?php echo $i ;?>]" value="<?php echo $facility_order[$i]['opening_balance'];?>" /></td>
							<td><input class="form-control input-small" readonly="readonly" type="text" name="receipts[<?php echo $i ;?>]" value="<?php echo $facility_order[$i]['total_receipts'];?>" /></td>
							<td><input class="form-control input-small" readonly="readonly" type="text" name="issues[<?php echo $i ;?>]"  value="<?php echo $facility_order[$i]['total_issues'];?>" /></td>
				<td><input  class="form-control input-small" readonly="readonly" type="text" name="adjustmentnve[<?php echo $i ;?>]"  value="<?php echo $facility_order[$i]['adjustmentnve']?>" /></td>
				<td><input class="form-control input-small" readonly="readonly" type="text" name="adjustmentpve[<?php echo $i ;?>]"  value="<?php echo $facility_order[$i]['adjustmentpve']?>" /></td>
							<td><input class="form-control input-small" readonly="readonly" type="text" name="losses[<?php echo $i ;?>]"  value="<?php echo $facility_order[$i]['losses'] ?>" /></td>
							<td><input class="form-control input-small" readonly="readonly" type="text"  name="days[<?php echo $i ;?>]"  value="<?php echo $facility_order[$i]['days_out_of_stock'];?>" /></td>
							<td><input class="form-control input-small closing" readonly="readonly" type="text" name="closing[<?php echo $i ;?>]"
								 value="<?php echo ($facility_order[$i]['closing_stock']<0)? 0:$facility_order[$i]['closing_stock'] ;?>" /></td>
							<td><input class="form-control input-small amc" readonly="readonly" type="text" name="amc[<?php echo $i ;?>]" value="<?php echo $facility_order[$i]['historical'];?>" /></td>
							<td><input class="form-control input-small suggested" readonly="readonly" type="text" name="suggested[<?php echo $i ;?>]"  value="0"/></td>
							<td><input class="form-control input-small quantity" type="text" name="quantity[<?php echo $i ;?>]" value="<?php $qty=$facility_order[$i]['quantity_ordered'];
							if($qty>0){echo $qty;} else echo 0;?>"/></td>
							<td><input class="form-control input-small actual_quantity" readonly="readonly" type="text" name="actual_quantity[<?php echo $i ;?>]" value="0"/></td>
							<td><?php echo '<input type="text" class="form-control input-small cost" name="cost['.$i.']" value="0" readonly="yes"   />';?></td>
							<td><input class="form-control input-small" type="text" name="comment[<?php echo $i ;?>]" value="N/A" /></td>
			       			</tr>						
						<?php } $i=($i==0)? 0: $i=$i-1;
						 echo form_close()."<script>var count=".$i."</script>"	?>
</tbody>
</table>

<hr />
<div class="container-fluid">
<div style="float: right;">
<button type="button" class="add btn btn-primary"><span class="glyphicon glyphicon-plus"></span>Add Item</button>
<button type="button" class="btn btn-success test"><span class="glyphicon glyphicon-open"></span>Save</button></div>
</div>
</form>  
</div>
</div>
<script>
$(document).ready(function() {
	var new_count =count+1;
	var drawing_rights_balance=$('#actual_drawing_rights').val();
	//auto compute the values
	calculate_totals();
	calculate_suggested_value(3);
	//datatables settings 
	$('#example').dataTable( {
       "sPaginationType": "bootstrap",
       "sScrollY": "310px",
	     "sScrollX": "100%",
        "oLanguage": {
                        "sLengthMenu": "_MENU_ Records per page",
                        "sInfo": "Showing _START_ to _END_ of _TOTAL_ records"},
        "bPaginate": false} );
	$('#example_filter label input').addClass('form-control');
	$('#example_length label select').addClass('form-control');	
	// add commodity data
    $(".add").button().click( function (){
	var body_content='<table class="table-update"><thead><tr><th>Description</th><th>Commodity Code</th><th>Order Unit Size</th><th>Order Unit Cost (Ksh)</th>'+				   	    
					'</tr></thead><tbody><tr><td>'+
                    '<select name="desc" class="form-control desc"><option value="0">--Select Commodity Name--</option>'+
                    '<?php	foreach($facility_commodity_list as $commodity):
						     $commodity_id=$commodity['commodity_id'];
							 $commodity_code=$commodity['commodity_code'];							
							 $sub_category_name=preg_replace('/[^A-Za-z0-9\-]/', ' ',$commodity['sub_category_name']);
							 $unit_size=preg_replace('/[^A-Za-z0-9\-]/', ' ',$commodity['unit_size']);
							 $unit_cost=$commodity['unit_cost'];
							 $total_commodity_units=$commodity['total_commodity_units'];
							 $commodity_name= preg_replace('/[^A-Za-z0-9\-]/', ' ', $commodity['commodity_name']);?>'+					
						'<option <?php echo 'special_data="'.$commodity_id.'^'.$unit_cost.'^'.$unit_size.
	'^'.$sub_category_name.'^'.$commodity_code.'^'.$total_commodity_units.'" value="'.$commodity_id.'">'.$commodity_name ;?></option><?php endforeach;?>'+
	'</select></td><td><input readonly="readonly" class="form-control" type="text" name="commodity_code"    /></td>'+
						'<td><input class="form-control" readonly="readonly" type="text" name="unit_size"  /></td>'+
						'<td><input class="form-control" readonly="readonly" type="text" name="unit_cost"   />'+
						'<input type="hidden" name="cat_name"/><input type="hidden" name="commodity_id"  />' +
						'<input type="hidden" name="commodity_name_"  /><input type="hidden" name="total_commodity_units_" value=""/></td></tr></tbody></table>';
        //hcmp custom message dialog
    dialog_box(body_content,'<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>'+
        '<button type="button" class="btn btn-primary add_item"><span class="glyphicon glyphicon-plus"></span>Add</button>');
    });
    // add item modal box
    $(document.body).on("change", ".desc", function (){
    var data = $('option:selected', this).attr('special_data');  
				var code_array = data.split("^");
				var commodity_id = code_array[0];
				$('input:text[name=commodity_code]').val(code_array[4]);
				$('input:text[name=commodity_id_]').val(commodity_id);
				$('input:text[name=unit_size]').val(code_array[2]);
				$('input:text[name=unit_cost]').val(code_array[1]);
				$('input:hidden[name=cat_name]').val(code_array[3]);
				$('input:hidden[name=commodity_name_]').val($(".desc option:selected").text());
				$('input:hidden[name=total_commodity_units_]').val(code_array[5]);});
	// add the item to the order list			
	$(document.body).on("click", ".add_item", function (){
	 var check_if_the_user_has_selected_a_commodity=$('#desc').val();
//alert(new_count)
	 if(check_if_the_user_has_selected_a_commodity==0){
	 	alert("Please select a commodity first");
	 	return;
	 }

	// add the items here to the order form
	  $("#example" ).dataTable().fnAddData( [ 
  	 '<input type="hidden" class="commodity_name" id="commodity_name['+new_count+']" name="commodity_name['+new_count+']" value="'+$(".desc option:selected").text()+'" />'+
          '<input type="hidden" class="commodity_code" id="commodity_code['+new_count+']" name="commodity_code['+new_count+']" value="'+$('input:text[name=commodity_code]').val()+'" />'+
         '<input type="hidden" class="commodity_id" id="commodity_id['+new_count+']" name="commodity_id['+new_count+']" value="'+$(".desc option:selected").val()+'" />'+
         '<input type="hidden" class="total_commodity_units" id="total_commodity_units['+new_count+']" name="total_commodity_units['+new_count+']" value="'+$('input:hidden[name=total_commodity_units_]').val()+'" />'+ 
         '<input type="hidden" class="unit_cost" id="price['+new_count+']" name="price['+new_count+']" value="'+$('input:text[name=unit_cost]').val()+'" />'+
         '<input type="hidden" id="unit_size['+new_count+']" name="unit_size['+new_count+']" value="'+$('input:text[name=unit_size]').val()+'" />'+
							"" + $('input:hidden[name=cat_name]').val() + "" ,  
							"" + $('input:hidden[name=commodity_name_]').val() + "" , 
							"" + $('input:text[name=commodity_code]').val() + "" ,
							"" + $('input:text[name=unit_size]').val() + "" ,
							"" + $('input:text[name=unit_cost]').val() + "" ,
						  '' +'<input class="form-control input-small" type="text" name="open['+new_count+']" id="open['+new_count+']"   value="0"/>',
							'<input class="form-control input-small" type="text" name="issues['+new_count+']" id="issues['+new_count+']"   value="0" />',
							'<input class="form-control input-small" type="text" name="receipts['+new_count+']" id="receipts['+new_count+']"  value="0" />' ,
							'<input class="form-control input-small" type="text" name="adjustmentnve['+new_count+']" id="adjustmentnve['+new_count+']"  value="0"   />' ,
                            '<input class="form-control input-small" type="text" name="adjustmentpve['+new_count+']" id="adjustmentpve['+new_count+']"  value="0"   />' ,
							'<input class="form-control input-small" type="text" name="losses['+new_count+']" id="losses['+new_count+']" value="0"   />' ,
							'<input class="form-control input-small" type="text" name="closing['+new_count+']" id="closing['+new_count+']" value="0"   />',
							'<input class="form-control input-small" type="text" name="days['+new_count+']" id="days['+new_count+']" value="0"   />',
							'<input class="form-control input-small" type="text" name="amc['+new_count+']" id="amc['+new_count+']" value="0"   />',
							'<input class="form-control input-small" type="text" value="0" name="suggested['+new_count+']" 	id="suggested['+new_count+']" readonly="yes"  />',
							'<input class="form-control input-small quantity" type="text" name="quantity['+new_count+']" id="quantity['+new_count+']" value="0" />',
							'<input class="form-control input-small actual_quantity" type="text" name="actual_quantity['+new_count+']" id="actual_quantity['+new_count+']" value="0" readonly="yes" />',
							'<input class="form-control input-small cost" type="text" name="cost['+new_count+']" id="cost['+new_count+']" value="0" readonly="yes" />',
							'<input type="text" class="form-control input-small" name="comment['+new_count+']" id="comment['+new_count+']" value="N/A"/>'
		]); 
		new_count=new_count+1;
		$('#communication_dialog').modal('hide');	
	});
	//compute the order totals here
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
	$('.test').on('click','', function (){
	var table_data='<div class="row" style="padding-left:2em"><div class="col-md-6"><h4>Order Summary</h4></div></div>'+
    '<div class="row" style="padding-left:2em"><div class="col-md-6">Order Form No</div><div class="col-md-6">'+($("#order_no").val())+'</div></div>'+
    '<div class="row" style="padding-left:2em"><div class="col-md-6">Order Frequency</div><div class="col-md-6">'+($("#order_period option:selected").text())+'</div></div>'+
    '<div class="row" style="padding-left:2em"><div class="col-md-6">In-Patient Bed Days</div><div class="col-md-6">'+($("#bed_capacity").val())+'</div></div>'+
    '<div class="row" style="padding-left:2em"><div class="col-md-6">Total OPD Visits & Revisits:</div><div class="col-md-6">'+($("#workload").val())+'</div></div>'+
    '<div class="row" style="padding-left:2em"><div class="col-md-6">Initial Drawing Rights (Ksh)</div><div class="col-md-6">'+number_format(drawing_rights_balance, 2, '.', ',')+'</div></div>'+
    '<div class="row" style="padding-left:2em"><div class="col-md-6">Total Order Value (Ksh)</div><div class="col-md-6">'+number_format($("#total_order_value").val(), 2, '.', ',')+'</div></div>'+
    '<div class="row" style="padding-left:2em"><div class="col-md-6">Drawing Rights Balance(Ksh)</div><div class="col-md-6">'+number_format($("#total_order_balance_value").val(), 2, '.', ',')+'</div></div>'+
    '<table class="table table-hover table-bordered table-update">'+
					"<thead><tr>"+
					"<th>Description</th>"+
					"<th>Commodity Code</th>"+
					"<th>Order Quantity</th>"+
					"<th>Unit Cost Ksh</th>"+
					"<th>Total Ksh</th></tr></thead><tbody>";	 	    			
        $("input[name^=cost]").each(function(i) { 
         	//$(document).each('','input[name^=cost]', function (i){
         var C_name=$(this).closest("tr").find(".commodity_name").val()
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
      /************save the data here*******************/

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
    $('#workload').delay(500).queue(function (nxt){
    // Load up a new modal...
     dialog_box('Fix these items before saving your Order <ol>'+alert_message+'</ol>&nbsp;&nbsp;&nbsp;&nbsp;',
     '<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>');
    	nxt();
    });
     }else{
    $('#workload').delay(500).queue(function (nxt){
    // Load up a new modal...
    var img='<img src="<?php echo base_url('assets/img/wait.gif') ?>"/>';
     dialog_box(img+'<h5 style="display: inline-block; font-weight:500;font-size: 18px;padding-left: 2%;"> Please wait as the order is being processed</h5>',
     '');
    	nxt();
    	$("#myform").submit();
    });
     	
     }
     });
     //change ordeing cycle
     $('#main-content').on('change','#order_period',function() {
     var month=$(this).val(); 
     $("#month").html(month);
     calculate_suggested_value(month);
     	
     });
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
      balance=parseInt(drawing_rights_balance)-order_total;
     //set the balances here
     $("#total_order_balance_value").val(balance)
     $("#total_order_value").val(order_total);
		
	}
	
	function calculate_suggested_value(month){
		$("input[name^=suggested]").each(function() {
        var amc=parseInt($(this).closest("tr").find(".amc").val());
	 	var closing_stock=parseInt($(this).closest("tr").find(".closing").val());
        var suggested=0;       
        if(closing_stock<0) {closing_stock=0;}
        suggested=((amc*month)-closing_stock)+amc;
        if(suggested<0) {suggested=0;}
        $(this).val(suggested)
     });//check if order total is a NAN
	}		
    
});

</script>
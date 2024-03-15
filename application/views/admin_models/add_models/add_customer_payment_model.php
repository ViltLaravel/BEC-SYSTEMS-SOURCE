<!-- Select2 -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/select2.min.css">
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title" style="font-family: 'Poppins'"><i class="fas fa-money" aria-hidden="true"></i>
		Customer payment collections
	 </h4>
</div>
  <div class="modal-body">
	<div class="row">
      <div class="box box-danger">
        <div class="box-body">
          <div class="col-md-12">
				<?php
					$attributes = array('id'=>'customer_payment','method'=>'post','class'=>'form-horizontal');
				?>
				<?php echo form_open('customers/add_customer_payments',$attributes); ?>  
					<div class="form-group">
			       		<?php echo form_label('Customer Name'); ?>
			        	<select name="customer_id" class="form-control select2 input-lg">
			            <?php 
			           
			            if($customer_list  != NULL)
			            {
				            foreach ($customer_list as $single_customer) 
				            {
				            ?>
				                <option value="<?php echo $single_customer->id; ?>">
				                    <?php echo 'Name: '.$single_customer->customer_name.' - '.$single_customer->cus_company.' - '.$single_customer->cus_contact_2.' - '.$single_customer->cus_type.' - '.$single_customer->cus_region;

                                         ?>     
				                </option>
				            <?php 
				             }
			            }
			            ?>
			        	</select>
			        </div>	      	          
			      <div class="form-group">    
					<?php
							echo form_label('Amount:');
							$data = array('class'=>'form-control input-lg','type'=>'text','name'=>'amount','placeholder'=>'e.g 3200','reqiured'=>'');
							echo form_input($data);							
					?>           
			      </div>	
			      <div class="form-group">
					<label>Payment Method: </label>	
					<select class="form-control input-lg " name="payment_id" id="payment_id"  style="width: 100%;">
						<option value="Cash" > Cash </option>
						<option value="Cheque" > Cheque </option>
					</select>
				 </div>
				 <div class="bank-section-details">
					<div class="form-group ">
						<label>Deposit account: </label>				
						<select class="form-control select2" name="bank_id" id="bank_id"  style="width: 100%;">
							<option value="0"> Bank account</option>
							<?php
							//category_names from mp_category table;
							if($bank_list != NULL)
							{		
								foreach ($bank_list as $single_bank)
								{
							?>
								<option value="<?php echo $single_bank->id; ?>" ><?php echo $single_bank->bankname.' | TITLE '.$single_bank->title.' | Account '.$single_bank->accountno.' | Branch '.$single_bank->branch.' | Code '.$single_bank->branchcode; ?> 
								</option>
							<?php
									}
								}
								else
								{
									echo "No Record Found";
								}
							?>	
						</select>
						<h5>Available balance PKR <b id="available_balance"> 0 </b></h5>
					</div>
					<div class="form-group " id="bank-cheque-no">
						<?php echo form_label('Cheque No:'); ?>
						<?php				
							$data = array('class'=>'form-control input-lg','type'=>'text','name'=>'ref_no','reqiured'=>'');
							echo form_input($data);				
						?>
						<?php				
							$data = array('type'=>'hidden','id'=>'save_available_balance','name'=>'save_available_balance','value'=>'0','reqiured'=>'');
							echo form_input($data);				
						?>
					</div>
				</div>  	  
			      <div class="form-group">  
						<?php
							echo form_label('Description :');
							$data = array('class'=>'form-control input-lg','type'=>'text','name'=>'description','placeholder'=>'Any description','reqiured'=>'');
							echo form_input($data);	
						 ?>	
				  </div>
				  <div class="form-group">  				
					<?php

						$data = array('class'=>'btn btn-info btn-outline-primary','type' => 'submit','name'=>'btn_submit_category','value'=>'true', 'content' => '<i class="fa fa-floppy-o" aria-hidden="true"></i> Save Payment');

						
						echo form_button($data);	
					 ?>    
			      </div>
				<?php echo form_close(); ?>			    		
			    </div>	
		      </div>				  
	    </div>
	</div>
</div>
<script src="<?php echo base_url(); ?>assets/plugins/select2/select2.full.min.js"></script>
<script type="text/javascript">
	  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();
  });

   $('#payment_id').change(function(){
	var method = $('#payment_id').val();
	if(method == 'Cheque')
	{
		$('.bank-section-details').css('display','block');
	}
	else
	{
		$('.bank-section-details').css('display','none');
	}
});

$('#bank_id').change(function(){
	var bank_id = $('#bank_id').val();


	if(bank_id != 0)
	{
		// SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
            url: '<?php echo base_url('bank/check_available_balance/'); ?>'+bank_id,
            success: function(response)
            {
                $('#available_balance').html(response);
                $('#save_available_balance').val(response);
            }
        });

		$('#bank-cheque-no').css('display','block');
	}
});
</script>
 <!-- Form Validation -->
<script src="<?php echo base_url(); ?>assets/dist/js/custom.js"></script>
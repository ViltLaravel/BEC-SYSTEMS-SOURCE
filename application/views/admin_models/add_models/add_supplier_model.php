<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title" style="font-family: 'Poppins'"><i class="fas fa-plus-square" aria-hidden="true"></i> 
    	Add new supplier
    </h4>
</div>  
<style>
.modal-dialog{width:90% !important}
.modal-body{height:100% !important;}
</style>
<div class="modal-body">
	<div class="row">
        <div class="">
            <div class="box-body">
              <div class="col-md-12">
				<?php
					$attributes = array('id'=>'supplier_form','method'=>'post','class'=>'');
				?>
				<?php echo form_open_multipart('supplier/add_supplier',$attributes); ?>
	              	<div class="row box box-default">
	              		<div class="row margin">
						  
						  	<div class="col-md-12">	
	              			<div class="col-md-6">
								<h4><label class="box-label" style="font-family: 'Poppins'; color: red"><b>Supplier Basic Info</b></label></h4>
							</div>
							<div class="col-md-6">
							 <div class="col-md-8 pull-right row">	
							 <div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label" style="font-size:20px;">Type: </label>
								<div class="col-sm-9">
									<select name="customer_type" class="form-control input-lg">
										<option value="Visitor"> Visitor</option>
										<option value="Regular"> Regular</option>
										<option value="Dealer" > Dealer </option>
										<option value="Company" > Company  </option>
									</select>
								</div>
								</div>
							</div>
							</div>
						</div>

			              	<div class="col-md-4">	
				              <div class="form-group">	
								 <?php echo form_label('Supplier Name:'); ?>
								  <?php
									$data = array('class'=>'form-control input-lg','type'=>'text','name'=>'customer_name','placeholder'=>'Enter your Supplier Name','reqiured'=>'');
									echo form_input($data);
								?>
				              </div>
				            </div>
							 <div class="col-md-4">	
							   <div class="form-group">
							     <?php echo form_label('Supplier Mobile :'); ?>
				                   <?php
										$data = array('class'=>'form-control input-lg','type'=>'number','name'=>'customer_contact_two','placeholder'=>'e.g 00659855487','reqiured'=>'');
										echo form_input($data);
									?>
				              </div>
			              </div>
				            <div class="col-md-4">	
							   <div class="form-group">
								<?php echo form_label('Supplier Email:'); ?>
				                <?php
								$data = array('class'=>'form-control input-lg','type'=>'email','name'=>'customer_email','placeholder'=>'e.g Pencil@gmail.com','reqiured'=>'');
										echo form_input($data);
								
									?>
				              </div>
			              	</div>


							<div class="col-md-4">	
							  <div class="form-group">
							  <?php echo form_label('Supplier Phone:'); ?>
								<?php
										$data = array('class'=>'form-control input-lg','type'=>'number','name'=>'customer_contatc1','placeholder'=>'e.g 00659855487','reqiured'=>'');
										echo form_input($data);
							   ?>
				              </div>
			              </div>
			              	<div class="col-md-4 " >	
							   <div class="form-group">
							     <?php echo form_label('National ID No:'); ?>
								  <?php
										$data = array('class'=>'form-control input-lg','type'=>'text','name'=>'customer_cnic','placeholder'=>'e.g 5248222154','reqiured'=>'');
										echo form_input($data);
									?>
				              </div>
			              </div>
						  	<div class="col-md-4">	
							   <div class="form-group">
							   <?php echo form_label('Supplier Address:'); ?>
				               <?php
										$data = array('class'=>'form-control input-lg','type'=>'text','name'=>'customer_address','placeholder'=>'e.g 11th Commercial Street DHA ,Karachi','reqiured'=>'');
										echo form_input($data);
							  ?>
				              </div>
			              </div>


			             
			              <div class="col-md-4 ">	
							   <div class="form-group">
							     <?php echo form_label('Company:'); ?>
								  <?php
										$data = array('class'=>'form-control input-lg','type'=>'text','name'=>'customer_company','placeholder'=>'e.g Pepsico','reqiured'=>'');
										echo form_input($data);
									?>
				              </div>
			              </div>
			              <div class="col-md-4">	
							  <div class="form-group">
							     <?php echo form_label('Region:'); ?>
								  <?php
										$data = array('class'=>'form-control input-lg','type'=>'text','name'=>'customer_region','placeholder'=>'e.g Istanbol','reqiured'=>'');
										echo form_input($data);
									?>
				              </div>
			              </div>

			              <div class="col-md-4">	
							  <div class="form-group">
							     <?php echo form_label('Town:'); ?>
								  <?php
										$data = array('class'=>'form-control input-lg','type'=>'text','name'=>'customer_town','placeholder'=>'e.g ','reqiured'=>'');
										echo form_input($data);
									?>
				              </div>
			              </div>
			            <div class="col-md-12 field-agjust">	
							   <div class="form-group">
							   <?php echo form_label('Any Description:'); ?>
				               <?php
									$data = array('class'=>'form-control input-lg','type'=>'text','name'=>'customer_description','placeholder'=>'e.g extra information about customer ','reqiured'=>'');
									echo form_input($data);
								?>
				              </div>
			              </div>
			              <div class="col-md-12 field-agjust">	
						  <div class="col-md-5">	
							  <div class="form-group">
				                <label>Upload Customer Picture (Optional)</label>
									 <div class="input-group">
				                
				          		<input type="file" name="customer_picture" data-validate="required" class="form-control input-lg" data-message-required="Value Required" >
				                </div>
				              </div>
							  </div>
							   <div class="col-md-5">	<br/>
								<div class="form-group">  				
									<?php
										$data = array('class'=>'btn btn-info btn-outline-primary ','type' => 'submit','name'=>'btn_submit_customer','value'=>'true', 'content' => '<i class="fa fa-floppy-o" aria-hidden="true"></i> Save Supplier');
										echo form_button($data);
									?>   
								</div>
							</div>

			              </div>

	             

				  </div>
			        </div>
						<?php echo form_close(); ?>
        		</div>
			</div>
 		</div>
	</div>
</div>
 <!-- Form Validation -->
<script src="<?php echo base_url(); ?>assets/dist/js/custom.js"></script>
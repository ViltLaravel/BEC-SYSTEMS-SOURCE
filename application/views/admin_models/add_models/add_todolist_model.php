<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h4 class="modal-title" style="font-family: 'Poppins'"><i class="fas fa-plus-square" aria-hidden="true"></i> Add new request</h4>
</div>
  <div class="modal-body">
	   <div class="row">
      <div class="box box-danger">
        <div class="box-body">
        <div class="col-md-12">
		 <?php
				$attributes = array('id'=>'Todolist_form','method'=>'post','class'=>'form-horizontal');
		  ?>
		<?php echo form_open('todolist/add_todo',$attributes); ?>		  
          <div class="form-group">    
			<?php
					echo form_label('Request items:');
					$data = array('class'=>'form-control input-lg','type'=>'text','name'=>'todolist_name','placeholder'=>'Enter your Todolist ','reqiured'=>'');
					echo form_input($data);						
			?>           
          </div>  
          <div class="form-group">    
			<?php
					echo form_label('Date to remind:');
					$data = array('class'=>'form-control input-lg','type'=>'date','name'=>'Todolist_Date','placeholder'=>'Enter your Todolist Date','reqiured'=>'');
					echo form_input($data);							
			?>           
          </div>
		  <div class="form-group">  				
			<?php
				$data = array('style' => 'border-radius: 8px','class'=>'btn btn-info btn-outline-primary','type' => 'submit','name'=>'btn_submit_todo','value'=>'true', 'content' => '<i class="fa fa-floppy-o" aria-hidden="true"></i> Save Request');
				echo form_button($data);
			 ?>   
          </div>
		<?php echo form_close(); ?>
    </div>
  </div>
</div>
</div>
 <!-- Form Validation -->
<script src="<?php echo base_url(); ?>assets/dist/js/custom.js"></script>
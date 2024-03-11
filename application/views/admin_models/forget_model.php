<div id="ForgetModel" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="reset-password">
	<div class="modal-content text-center" style="border-radius: 20px; width: 300px; background: rgb(255, 247, 241); box-shadow: 0 0 20px rgb(0, 0, 0, 0.6);">
	      <div class="modal-header" style="background-color: rgb(135, 169, 34); border-bottom-left-radius: 60px; border-bottom-right-radius: 60px; border-top-left-radius: 20px; border-top-right-radius: 20px;">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>		
	        <h4  class="modal-title" style="font-family: 'Poppins'; font-size: 14px; color: white">Kindly provide your email address</h4>
	      </div>  
	      <div class="modal-body"> 
	            <?php $attributes = array('id'=>'Forget_form_Model','method'=>'post'); ?>
					<?php echo form_open('Forget_password/forget_password_administrator',$attributes); ?>
							<div class="form-group">
								<?php $data = array('style' => 'font-family: Poppins; font-size: 10px; border-radius: 12px;','class'=>'form-control reset','type'=>'email','id'=>'user_email','name'=>'user_email','value'=>'','placeholder'=>'Email Address','data-validate'=>'required','data-message-required'=>'Value Required'); echo form_input($data); ?>
							</div>
							<div class="form-group">	  
								<?php $data = array('style' => 'background-color: rgb(135, 169, 34); color: white; font-family: Poppins, sans-serif; border-radius: 10px; padding: 4px 22px;', 'class'=>'btn','name'=>'btn_submit_login','value'=>'Send to Email'); echo form_submit($data); ?>     
							</div> 
						<br />		  
					<?php echo form_close(); ?>		
			</div>
			<div class="modal-footer" style="background-color: rgb(135, 169, 34); border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;"></div>   
    </div>
	</div>
  </div>
</div>


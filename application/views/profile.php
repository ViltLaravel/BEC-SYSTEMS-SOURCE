<div class="content-wrapper">
    <section class="content-header">
         <h4  class="purchase-heading text-center" style="font-family: 'Poppins'">
            <i class="far fa-user" aria-hidden="true"></i>  
            User Profile
            <small>Profile of a current logged in User</small>
        </h4>
    </section>
        <?php
         if($User_profile != NULL) 
         {
        ?>
        <section class="content">
            <div class="row">
                <div class="col-md-2">
                </div>
                <div class="col-md-8">
                    <div class="box">
                        <div class="box-body box-profile">
                            <span onclick="show_modal_page('<?php echo base_url();?>profile/popup/picture_model')">
                            <?php echo img(array('width'=>'40','height'=>'40','class'=>'profile-user-img img-responsive img-circle','alt'=>'<?php echo $User_profile[0]->user_name; ?>','src'=>'uploads/users/'.$User_profile[0]->cus_picture)); ?>

                                <h3 class="profile-user_name text-center" style="font-family: 'Poppins'"><?php echo $User_profile[0]->user_name; ?></h3>
                            </span>    
                        </div>
                        <div class="box-body box-profile ">
							<!---#passwordModel -->
                            <p class="text-center passwordset " onclick="show_modal_page('<?php echo base_url();?>profile/popup/password_model')" >    
							   <i class="fas fa-unlock" aria-hidden="true" /></i><u> Change Password </u> </p>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title" style="font-family: 'Poppins'">About Me</h3>
                        </div>
                        <div class="box-body">
                            <strong><i class="fas fa-envelope margin-r-5"></i> Email</strong>
                            <p class="text-muted">
                            <i class="far fa-at margin-r-5"></i><?php echo $User_profile[0]->user_email; ?>
                            </p>
                            <hr>
                            <strong><i class="fas fa-map-marker-alt margin-r-5"></i> Address</strong>
                            <p class="text-muted">
                                <?php echo $User_profile[0]->user_address; ?>
                            </p>
                            <hr>
                            <strong><i class="fas fa-address-book margin-r-5"></i> Contact 1</strong>
                            <p class="text-muted">
                                <?php echo $User_profile[0]->user_contact_1; ?>
                            </p>
                            <hr>
                            <strong><i class="fas fa-address-book margin-r-5"></i> Contact 2 </strong>
                            <p class="text-muted">
                                <?php echo $User_profile[0]->user_contact_2; ?>
                            </p>
                            <hr>
                            <strong><i class="fas fa-briefcase margin-r-5"></i> Joined Date</strong>

                            <p class="text-muted">
                                <?php echo $User_profile[0]->user_date; ?>
                            </p>
                            <hr>
                            <strong><i class="fas fa-user-tag margin-r-5"></i> Role </strong>

                            <p class="text-muted">
                                <?php echo $User_profile[0]->user_description; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
        </section>
        <?php
            }
        ?>
</div>
<!-- Bootstrap model  -->
<?php $this->load->view('bootstrap_model.php'); ?>
<!-- Bootstrap model  ends--> 
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Edge-Inventory </title>
     <meta http-equiv="Content-Security-Policy" content="frame-ancestors 'none'">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url();?>assets/img/favicon.png">
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.css">
</head>
<body style="background-image:url('<?php echo base_url(); ?>uploads/systemimgs/bg.jpg'); background-size:cover; position: relative;">
   <br/><br/><br/><br/><br><br><br/>
    <div style="background: rgb(255, 247, 241); border-radius:20px; border: none; box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); display: flex; flex-direction: row; justify-content: center; margin: auto; width: 600px;">
        <div class="login-box-body" style="margin: 0">
            <h3 class="text-center" style="font-family: 'Poppins', sans-serif; font-size: 30px">
                <a href="<?php echo base_url('login'); ?>"> <b style="color: black;">Sign In</b></a>
            </h3>
            <p class="login-box-msg" style="color: black;font-size: 11px; font-family: 'Poppins', sans-serif;">Please provide your email and password</p>
                <?php $attributes = array('id'=>'Customer_form','method'=>'post','class'=>'form-horizontal');?>
                <?php echo form_open('login/authentication',$attributes); ?>
                    <div class="form-group has-feedback">
                        <?php
                            $data = array('style' => 'border-radius: 10px; font-size: 12px; font-family: Poppins; margin-left: 4px;', 'class'=>'form-control input-md rounded','id'=>'user_email','type'=>'email','name'=>'user_email','value'=>'','placeholder'=>'Email Address','reqiured'=>'','AUTOCOMPLETE'=>'ON');
                            echo form_input($data);
                        ?>  
                    </div>
                    <div class="form-group has-feedback">
                        <?php
                        $data = array('style' => 'border-radius: 10px; font-size: 12px; font-family: Poppins; margin-left: 4px;', 'class'=>'form-control input-md rounded','id'=>'user_password','type'=>'password','name'=>'user_password','value'=>'','placeholder'=>'Password','reqiured'=>'','AUTOCOMPLETE'=>'ON');
                        echo form_input($data);
                        ?>
                    </div>
                    <div class="row text-center">
                            <?php
                                $data = array('class'=>'btn','name'=>'btn_submit_signin','value'=>$page_title_model_button_Signin, 'style' => 'background-color: rgb(135, 169, 34); color: white; font-family: Poppins, sans-serif; border-radius: 10px; padding: 4px 22px;');

                                echo form_submit($data);
                            ?>
                    </div>
                <?php echo form_close(); ?>
            <?php $this->load->view('admin_models/forget_model'); ?>
            <br />    
            <div style="display: flex; justify-content: center; font-family: 'Poppins', sans-serif;">
                <a style="color: black; font-size: 12px;" href="javascript:void(0)" data-toggle="modal" data-target="#ForgetModel" class="pull-left link-set">Forget Password?</a>
            </div> 
        </div>
        <div style="background-color: rgb(135, 169, 34); width: 300px; margin-left: 10px; margin-right: 0; border-top-left-radius: 80px; border-bottom-left-radius: 80px; border-bottom-right-radius: 20px; border-top-right-radius: 20px; color: white;">
            <div style="margin: 0 4px 0 4px;" class="text-center">
            <img style="width: 170px; margin-top: 70px;" src="<?php echo base_url(); ?>uploads/systemimgs/logo/BEC.png" alt="">
                <h1 style="font-family: 'Poppins', sans-serif; font-size: 30px; font-weight: bold">Welcome Back!</h1>
                <span style="font-family: 'Poppins', sans-serif; font-size: 12px" class="text-center">Please provide your email and password</span>
            </div>
        </div>
    </div>

    <!-- jQuery 2.2.3 -->
    <script src="<?php echo base_url(); ?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
    <!-- Validate form js -->
    <script src="<?php echo base_url(); ?>assets/jquery/jquery.validate.js"></script>
    <script src="<?php echo base_url(); ?>assets/dist/js/custom.js"></script>
    <!-- Bootstrap Gowl -->
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-growl/jquery.bootstrap-growl.js"></script>
    <!-- page script -->
    <script>
        function alertFunc(status, message) {

            $.bootstrapGrowl(message, {
                ele: 'body', // which element to append to
                type: status, // (NULL, 'info', 'error', 'success')
                offset: {
                    from: 'top',
                    amount: 20
                }, // 'top', or 'bottom'
                align: 'right', // ('left', 'right', or 'center')
                width: 250, // (integer, or 'auto')
                delay: 4000,
                allow_dismiss: true,
                stackup_spacing: 10 // spacing between consecutively stacked growls.
            });

        };
    </script>
    <?php
     if($this->session->flashdata('status') == "")
     {
     }
     else
     {
        $message = $this->session->flashdata('status');
        echo "<script>alertFunc('".$message['alert']."','".$message['msg']."')</script>";
     }
    ?>
</body>
</html>
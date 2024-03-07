<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Edge-Inventory </title>
     <meta http-equiv="Content-Security-Policy" content="frame-ancestors 'none'">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url();?>assets/img/favicon.png">
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

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap');
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body{
            background-color: #c9d6ff;
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 100vh;
        }

        .container{
            background-color: #fff;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
            position: relative;
            overflow: hidden;
            width: 768px;
            max-width: 100%;
            min-height: 480px;
        }

        .container p{
            font-size: 14px;
            line-height: 20px;
            letter-spacing: 0.3px;
            margin: 20px 0;
        }

        .container span{
            font-size: 12px;
        }

        .container a{
            color: #333;
            font-size: 13px;
            text-decoration: none;
            margin: 15px 0 10px;
        }

        .container button{
            background-color: #87A922;
            color: #fff;
            font-size: 12px;
            padding: 10px 45px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 10px;
            cursor: pointer;
        }

        .container button.hidden{
            background-color: transparent;
            border-color: #fff;
        }

        .container form{
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            height: 100%;
        }

        .container input{
            background-color: #eee;
            border: none;
            margin: 8px 0;
            padding: 10px 15px;
            font-size: 13px;
            border-radius: 8px;
            width: 100%;
            outline: none;
        }

        .form-container{
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        .sign-in{
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .container.active .sign-in{
            transform: translateX(100%);
        }

        .sign-up{
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
        }

        .container.active .sign-up{
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: move 0.6s;
        }

        @keyframes move{
            0%, 49.99%{
                opacity: 0;
                z-index: 1;
            }
            50%, 100%{
                opacity: 1;
                z-index: 5;
            }
        }

        .social-icons{
            margin: 20px 0;
        }

        .social-icons a{
            border: 1px solid #ccc;
            border-radius: 20%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin: 0 3px;
            width: 40px;
            height: 40px;
        }

        .toggle-container{
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: all 0.6s ease-in-out;
            border-radius: 150px 0 0 100px;
            z-index: 1000;
        }

        .container.active .toggle-container{
            transform: translateX(-100%);
            border-radius: 0 150px 100px 0;
        }

        .toggle{
            background-color: #87A922;
            height: 100%;
            background: linear-gradient(to right, #87A922, #87A922);
            color: #fff;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: all 0.6s ease-in-out;
        }

        .container.active .toggle{
            transform: translateX(50%);
        }

        .toggle-panel{
            position: absolute;
            width: 50%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 30px;
            text-align: center;
            top: 0;
            transform: translateX(0);
            transition: all 0.6s ease-in-out;
        }

        .toggle-left{
            transform: translateX(-200%);
        }

        .container.active .toggle-left{
            transform: translateX(0);
        }

        .toggle-right{
            right: 0;
            transform: translateX(0);
        }

        .container.active .toggle-right{
            transform: translateX(200%);
        }

        .container .text-danger {
            color: red;
            font-size: 10px;
            margin-top: 5px;
        }
    </style>
    
</head>
<body>
    <div class="container">
        <div class="form-container sign-in">
            <form action="<?php echo base_url('login/authentication'); ?>" method="post" id="Customer_form" class="form-horizontal">
                <h1>Sign In</h1>
                <div class="social-icons">
                    <a href="#" class="icon fa fa-facebook"><i ></i></a>
                    <a href="#" class="icon fa fa-google-plus"><i ></i></a>
                    <a href="#" class="icon fa fa-linkedin"><i ></i></a>
                    <a href="#" class="icon fa fa-github"><i ></i></a>
                </div>
                <span>or use your email password</span>
                <div class="form-group has-feedback">
                    <?php
                        $data = array('class' => 'form-control input-md', 'id' => 'user_email', 'type' => 'email', 'name' => 'user_email', 'value' => '', 'placeholder' => 'Email Address', 'required' => '', 'autocomplete' => 'on');
                        echo form_input($data);
                    ?>
                    <small class="text-danger" style="color: red; font-size: 10px;"><?php echo form_error('user_email'); ?></small>
                </div>
                <div class="form-group has-feedback">
                    <?php
                        $data = array('class' => 'form-control input-md', 'id' => 'user_password', 'type' => 'password', 'name' => 'user_password', 'value' => '', 'placeholder' => 'Password', 'required' => '', 'autocomplete' => 'on');
                        echo form_input($data);
                    ?>
                    <small class="text-danger" style="color: red; font-size: 10px;"><?php echo form_error('user_password'); ?></small>
                </div>
                <a href="javascript:void(0)" data-toggle="modal" data-target="#ForgetModel" class="pull-left link-set">Forget Password</a>
                <button type="submit">Sign In</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-right">
                    <h1>Welcome Back!</h1>
                    <p>Please provide your credentials to sign in.</p>
                </div>
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
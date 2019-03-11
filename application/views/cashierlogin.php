<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cashier Login</title>
    <link rel="shortcut icon" href="<?php echo base_url().'assets/img/eload.ico'?>" type="image/icon">
    <link href="<?php echo base_url().'assets/bootstrap/css/bootstrap.min.css'?>" rel="stylesheet" type="text/css" />
    <style type="text/css">
        *{
            padding: 0px;
            margin: 0px;
        }
        body,html,label{
            font-family: tahoma;
            font-size: 12px;
            margin: 0px;
            padding: 0px;
        }
        div.container86{
            width: 800px;
            height: 600px;
            background-color: red;
            background: url(../assets/images/cashierB.jpg) no-repeat center center fixed; 
            background-color: gray;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            margin: auto;
            max-height: 600px;
            min-height: 600px !important;
            overflow: hidden;
        }
        div.login-contain{
            background-color: red;
            padding: auto;
            margin: auto;
            width: 300px;
            margin-top: 100px;
            background-color: #fff;
        }
        div.lbl-container{
            text-align: center;
            background-color: #0A61B0;
            color: #fff;
            font-weight: bold;
            border-top: 1px solid #0FAFF3;
            border-bottom: 1px solid #0FAFF3;
        }

        label.login_type{
            padding: 6px 6px;
            font-size: 14px;
        }

        span.input-group-addon,input[type='text'],button[type='submit'],input[type='password']{
            border-radius: 0px;
        }
        input[type='text'],input[type='password']{
            font-weight: bold;
        }

        form#managerLogin{
            display: none;
        }

        div.alert-danger{
            border-radius: 0px;
            padding: 8px;
        }
    </style>
</head>

<body>
    <div class="container86">
        <div class="login-contain ">
            <div class="lbl-container">
                <label class="login_type">E-load Cashier Login</label>
            </div>
            <div class="form-login">
                <form action="<?php echo base_url().'User/loginUser'; ?>" method="post" accept-charset="utf-8" class="separate-sections" id="cashierLogin">          
                    <input type="hidden" name="baseurl" value="<?php echo base_url(); ?>">
                    <div class="input-group"> 
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-user"></i>
                      </span> 
                      <input name="username" value="" id="username" class="form-control" placeholder="Username" type="text" autocomplete="off" required=""> 
                    </div>
                    <div class="input-group"> 
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-tags"></i>
                      </span>
                      <input name="idnumber" value="" maxlength="13" id="idnumber" class="form-control" placeholder="Employee ID Number" type="text" autocomplete="off" required=""> 
                    </div>
                    <div class="input-group"> 
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-lock"></i>
                      </span> 
                      <input name="password" value="" id="password" class="form-control" placeholder="Password" type="password" required=""> 
                    </div>
                        <div class="row">            
                    <div class="col-md-12">
                      <button type="submit" class="btn btn-success btn-block">Login <i class="glyphicon glyphicon-log-in"></i> </button>
                    </div>
                    </div>
                </form>
                <div class="response">
                </div>
            </div>
        </div>
    </div>
</body>
<script src="<?php echo base_url().'assets/plugins/jQuery/jquery-2.2.3.min.js'?>"></script>
<script src="<?php echo base_url().'assets/js/cashier.js'?>"></script>
</html>
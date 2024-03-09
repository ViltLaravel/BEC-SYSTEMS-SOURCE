<section class="content" style="font-family: 'Poppins', sans-serif;">
    <div class="row">
        <div class="col-lg-4 col-xs-6">
            <div class="small-box" style="background: rgb(255, 247, 241); box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); border-top-left-radius: 20px; border-top-right-radius: 20px; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                <div class="inner">
                    <h3><label class="label " style="font-family: 'Poppins', sans-serif; color: #424769"><?php echo number_format($cash_in_hand,2,'.','');?></label></h3>

                    <h4 class="paragraph" style="font-family: 'Poppins', sans-serif; color: #424769">Cash in hand <?php echo $currency; ?></h4>
                </div>
                <div class="icon" style="padding-top: 5px">
                    <i class="fa fa-money " style="color: #424769" aria-hidden="true"></i>
                </div>
                <a href="<?php echo base_url('statements/leadgerAccounst');?>" class="small-box-footer" style="background-color: rgb(135, 169, 34); border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">More info <i class="fa fa-check"></i></a>
            </div>
        </div>

        <div class="col-lg-4 col-xs-6">
            <div class="small-box" style="background: rgb(255, 247, 241); box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); border-top-left-radius: 20px; border-top-right-radius: 20px; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                <div class="inner">
                    <?php 
                    if($payables < 0)
                    {
                        $payables = '('.-(number_format($payables,2,'.','')).')';
                    }

                     ?>
                    <h3><label class="label" style="font-family: 'Poppins', sans-serif; color: #424769"><?php echo $payables; ?></label></h3>

                    <h4 class="paragraph" style="font-family: 'Poppins', sans-serif; color: #424769">Accounts Payables <?php echo $currency; ?></h4>
                </div>
                <div class="icon" style="padding-top: 10px">
                    <i class="fa fa-id-card-o" style="color: #424769" aria-hidden="true"></i>
                </div>
                 <a href="<?php echo base_url('statements/leadgerAccounst');?>" class="small-box-footer" style="background-color: rgb(135, 169, 34); border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">More info <i class="fa fa-check"></i></a>
            </div>
        </div>

        <div class="col-lg-4 col-xs-6">
            <div class="small-box" style="background: rgb(255, 247, 241); box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); border-top-left-radius: 20px; border-top-right-radius: 20px; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                <div class="inner">
                    <h3><label class="label" style="font-family: 'Poppins', sans-serif; color: #424769"><?php echo $out_of_stock; ?></label></h3>
                    <h4 class="paragraph" style="font-family: 'Poppins', sans-serif; color: #424769">Shortage items</h4>

                </div>
                <div class="icon" style="padding-top: 10px">
                    <i class="far fa-bell-slash" style="color: #424769"></i>
                </div>
                <a href="<?php echo base_url('stockAlertReport');?>" class="small-box-footer" style="background-color: rgb(135, 169, 34); border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">More info <i class="fa fa-check"></i></a>
            </div>
        </div>

        <div class="col-lg-4 col-xs-6">
            <div class="small-box" style="background: rgb(255, 247, 241); box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); border-top-left-radius: 20px; border-top-right-radius: 20px; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                <div class="inner">
                    <h3><label class="label" style="font-family: 'Poppins', sans-serif; color: #424769"><?php echo number_format($account_recieveble,2,'.','');?></label></h3>

                    <h4 class="paragraph" style="font-family: 'Poppins', sans-serif; color: #424769">Accounts receivable <?php echo $currency; ?></h4>
                </div>
                <div class="icon" style="padding-top: 10px">
                    <i class="fa fa-folder-open-o" style=" color: #424769"></i>
                </div>
                <a href="<?php echo base_url('statements/leadgerAccounst');?>" class="small-box-footer" style="background-color: rgb(135, 169, 34); border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">More info <i class="fa fa-check"></i></a>
            </div>
        </div>

        <div class="col-lg-4 col-xs-6">
            <div class="small-box" style="background: rgb(255, 247, 241); box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); border-top-left-radius: 20px; border-top-right-radius: 20px; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                <div class="inner">
                    <h3><label class="label" style="font-family: 'Poppins', sans-serif; color: #424769"><?php echo $customers_count; ?></label></h3>
                    <h4 class="paragraph" style="font-family: 'Poppins', sans-serif; color: #424769">Branches</h4>

                </div>
                <div class="icon" style="padding-top: 10px">
                    <i class="fas fa-store " aria-hidden="true" style=" color: #424769"></i>
                </div>
               <a href="<?php echo base_url('customers');?>" class="small-box-footer" style="background-color: rgb(135, 169, 34); border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">More info <i class="fa fa-check"></i></a>
            </div>
        </div>     

        <div class="col-lg-4 col-xs-6">
            <div class="small-box" style="background: rgb(255, 247, 241); box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); border-top-left-radius: 20px; border-top-right-radius: 20px; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                <div class="inner">
                    <h3><label class="label" style="font-family: 'Poppins', sans-serif; color: #424769"><?php echo $suppliers_count; ?></label></h3>
                    <h4 class="paragraph" style="font-family: 'Poppins', sans-serif; color: #424769">Suppliers</h4>

                </div>
                <div class="icon" style="padding-top: 10px">
                    <i class="fas fa-shipping-fast " aria-hidden="true" style=" color: #424769"></i>
                </div>
               <a href="<?php echo base_url('supplier');?>" class="small-box-footer" style="background-color: rgb(135, 169, 34); border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">More info <i class="fa fa-check"></i></a>
            </div>
        </div>

        <div class="col-lg-4 col-xs-6">
            <div class="small-box" style="background: rgb(255, 247, 241); box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); border-top-left-radius: 20px; border-top-right-radius: 20px; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                <div class="inner">
                    <h3><label class="label" style="font-family: 'Poppins', sans-serif; color: #424769"><?php  echo number_format($expense_amount,2,'.',''); ?></label></h3>
                    <h4 class="paragraph" style="font-family: 'Poppins', sans-serif; color: #424769">Expense This Month <?php echo $currency; ?></h4>

                </div>
                <div class="icon" style="padding-top: 10px">
                   <i class="fas fa-wallet" aria-hidden="true" style=" color: #424769"></i>
                </div>
               <a href="<?php echo base_url('expense');?>" class="small-box-footer" style="background-color: rgb(135, 169, 34); border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">More info <i class="fa fa-check"></i></a>
            </div>
        </div>

        <div class="col-lg-4 col-xs-6">
            <div class="small-box" style="background: rgb(255, 247, 241); box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); border-top-left-radius: 20px; border-top-right-radius: 20px; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                <div class="inner">
                    <h3><label class="label" style="font-family: 'Poppins', sans-serif; color: #424769"><?php  echo number_format($purchase_amount,2,'.',''); ?></label></h3>
                    <h4 class="paragraph" style="font-family: 'Poppins', sans-serif; color: #424769">Purchases This Month <?php echo $currency; ?></h4>
                </div>
                <div class="icon" style="padding-top: 10px">
                    <i class="fas fa-luggage-cart" style=" color: #424769"></i>
                </div>
               <a href="<?php echo base_url('purchase');?>" class="small-box-footer" style="background-color: rgb(135, 169, 34); border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">More info <i class="fa fa-check"></i></a>
            </div>
        </div>

        <div class="col-lg-4 col-xs-6">
            <div class="small-box" style="background: rgb(255, 247, 241); box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); border-top-left-radius: 20px; border-top-right-radius: 20px; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                <div class="inner">
                    <h3><label class="label" style="font-family: 'Poppins', sans-serif; color: #424769"><?php echo $product_Count;?></label></h3>

                    <h4 class="paragraph" style="font-family: 'Poppins', sans-serif; color: #424769">Products in Stock</h4>
                </div>
                <div class="icon" style="padding-top: 10px">
                    <i class="fas fa-hourglass-start" aria-hidden="true" style=" color: #424769"></i>
                </div>
                <a href="<?php echo base_url('product/productStock');?>" class="small-box-footer" style="background-color: rgb(135, 169, 34); border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">More info <i class="fa fa-check"></i></a>
            </div>
        </div>
    </div>

    <div class="row tiles-bg-settings">
        
        <div class="col-lg-4 col-xs-6">
            <div class="small-box" style="background: rgb(255, 247, 241); box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); border-top-left-radius: 20px; border-top-right-radius: 20px; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                <div class="inner">
                    <h3><label class="label" style="font-family: 'Poppins', sans-serif; color: #424769"><?php echo $Sales_today_count; ?></label></h3>
                    <h4 class="paragraph" style="font-family: 'Poppins', sans-serif; color: #424769">Sales Today</h4>
                </div>
                <div class="icon" style="padding-top: 10px">
                    <i class="fas fa-chart-line" style=" color: #424769"></i>
                </div>
                <a href="<?php echo base_url('salesreport');?>" class="small-box-footer" style="background-color: rgb(135, 169, 34); border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;"><span class="dashboard_text" > Sl <?php echo $sales_today_amount[0]; ?> | Ex <?php echo $sales_today_amount[1]; ?> | Pr <?php echo $sales_today_amount[0]-$sales_today_amount[1]; ?></a>
            </div>
        </div>

        <div class="col-lg-4 col-xs-6">
            <div class="small-box" style="background: rgb(255, 247, 241); box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); border-top-left-radius: 20px; border-top-right-radius: 20px; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                <div class="inner">
                    <h3><label class="label" style="font-family: 'Poppins', sans-serif; color: #424769"><?php echo $Sales_month_count; ?></label></h3>

                    <h4 class="paragraph" style="font-family: 'Poppins', sans-serif; color: #424769">Sales This Month</h4>
                </div>
                <div class="icon" style="padding-top: 10px">
                    <i class="fas fa-chart-area" style=" color: #424769"></i>
                </div>
                <a href="<?php echo base_url('salesreport');?>" class="small-box-footer" style="background-color: rgb(135, 169, 34); border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;"> <span class="dashboard_text" >  Sl <?php echo $sales_month_amount[0]; ?> | <span class="expense_das">Ex <?php echo $sales_month_amount[1]; ?></span>  | Pr <?php echo $sales_month_amount[0]-$sales_month_amount[1]; ?>  </span></a>
            </div>
        </div>

        <div class="col-lg-4 col-xs-6">
            <div class="small-box" style="background: rgb(255, 247, 241); box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); border-top-left-radius: 20px; border-top-right-radius: 20px; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                <div class="inner">
                    <h3><label class="label" style="font-family: 'Poppins', sans-serif; color: #424769"><?php  echo number_format($total_retial_cost,2,'.',''); ?></label></h3>

                    <h4 class="paragraph" style="font-family: 'Poppins', sans-serif; color: #424769">Worth of items in stock <?php echo $currency; ?></h4>
                </div>
                <div class="icon" style="padding-top: 10px">
                    <i class="fas fa-money-check-alt" aria-hidden="true" style=" color: #424769"></i>
                </div>
                <a href="<?php echo base_url('product');?> " class="small-box-footer" style="background-color: rgb(135, 169, 34); border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">More info <i class="fa fa-check"></i></a>
            </div>
        </div>
    </div>
    
    </div>    
</section>
    <div class="row">
        <section class="col-lg-7 connectedSortable">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title" style="font-family: 'Poppins', sans-serif;"><i class="fas fa-money" aria-hidden="true"></i> Total Revenue & Expense This Year <?php echo $currency; ?></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="areaChart" style="height:250px"></canvas>
                    </div>
                </div>
            </div>
        </section>
        <section class="col-lg-5 connectedSortable">
            <div class="box box-primary ">
                <div class="box-header with-border">
                    <h3 class="box-title" style="font-family: 'Poppins', sans-serif;">  <i class="ion ion-stats-bars "></i> Sales Profit This Year <?php echo $currency; ?> </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="lineChart" style="height:249px"></canvas>
                    </div>
                </div>
            </div>
            
        </section>


</div>
<style>
.small-box > .inner {
    padding: 20px;
}
@media (min-width: 992px){
.col-md-10 {
    width: 85.333333%;
}
}
</style>
<?php 
    $this->load->view('script/dashboard_script.php');
 ?>
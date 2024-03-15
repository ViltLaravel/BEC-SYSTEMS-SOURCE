<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="pull pull-right">
                <button onclick="printDiv('print-section')" class="btn btn-default btn-outline-primary   pull-right " style="border-radius: 10px"><i class="fas fa-print  pull-center"></i> Print Report</button>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="box" id="print-section">
        <div class="box-body box-bg ">
            <div class="make-container-center">
            <?php
                $attributes = array('id'=>'leadgerAccounst','method'=>'post','class'=>'');
            ?>
            <?php echo form_open_multipart('statements/trail_balance',$attributes); ?>
            <div class="row no-print">
                <div class="col-md-3">
                    <div class="form-group">
                        <?php echo form_label('Select year'); ?>
                        <select class="form-control input-lg" name="year">
                            <option  value="2024"> 2024</option>
                            <option  value="2025"> 2025</option>
                            <option  value="2026"> 2026</option>
                            <option  value="2027"> 2027</option>
                            <option  value="2028"> 2028</option>
                            <option  value="2029"> 2029</option>
                            <option  value="2030"> 2030</option>
                            <option  value="2031"> 2031</option>
                            <option  value="2032"> 2032</option>
                            <option  value="2033"> 2033</option>
                        </select>
                    </div>
                </div>                    
                <div class="col-md-3">
                    <div class="form-group" style="margin-top:21px;">
                        <?php
                            $data = array('style' => 'border-radius: 10px','class'=>'btn btn-info btn-flat margin btn-lg pull-right ','type' => 'submit','name'=>'btn_submit_customer','value'=>'true', 'content' => '<i class="fa fa-floppy-o" aria-hidden="true"></i> 
                                Create Statement');
                            echo form_button($data);
                         ?>  
                    </div>
                </div>      
            <?php form_close(); ?>
        </div>
        <div class="row">
            <div class="col-md-3"></div>
                <div class="col-md-6">
                   <h2 style="text-align:center; font-family: 'Poppins'">Trial Balance </h2>
                   <h3 style="text-align:center; font-family: 'Poppins'">
                        <?php echo $this->db->get_where('mp_langingpage', array('id' => 1))->result_array()[0]['companyname'] ;
                        ?>
                    </h3>
                   <h4 style="text-align:center; font-family: 'Poppins'">As of :  <b style="color: red"><?php echo $year; ?></b> <b>
                   </h4>
                   <h4 style="text-align:center; font-family: 'Poppins'">Created : <b style="color: red"><?php echo Date('Y-m-d'); ?></b> <b>
                   </h4>
                </div>
                <div class="col-md-3"></div>  
        </div>
        <div class="row">
            <div class="col-md-12">
                 <table class="table table-striped table-hover">
                     <thead>
                         <tr class="balancesheet-header">
                             <th class="col-md-10">ACCOUNT TITLE</th>
                             <th class="col-md-1">DEBIT</th>
                             <th class="col-md-1">CREDIT</th>
                         </tr>
                     </thead>
                     <tbody>
                        <?php echo $trail_records; ?>
                     </tbody>
                 </table>
            </div>
           
        </div>
            </div>
        </div>
    </div>
</section>
<!-- Bootstrap model  -->
<?php $this->load->view('bootstrap_model.php'); ?>
<!-- Bootstrap model  ends--> 
<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="pull pull-right">
                <button onclick="printDiv('print-section')" class="btn btn-default btn-md btn-flat   pull-right " style="border-radius: 10px"><i class="fas fa-print  pull-center"></i> Print Report</button>
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
            <?php echo form_open_multipart('statements/leadgerAccounst',$attributes); ?>
            <div class="row no-print">
                <div class="col-md-3">
                    <div class="form-group">
                        <?php echo form_label('From'); ?>
                        <?php
                            $data = array('class'=>'form-control input-lg','type'=>'date','name'=>'from','reqiured'=>'');
                            echo form_input($data);
                        ?>
                    </div>
                </div>                    
                <div class="col-md-3">
                    <div class="form-group">
                        <?php echo form_label('To'); ?>
                        <?php
                            $data = array('class'=>'form-control input-lg','type'=>'date','name'=>'to','reqiured'=>'');
                            echo form_input($data);
                        ?>
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
                    <h2 style="text-align:center; font-family: 'Poppins'">General Ledger </h2>
                    <h3 style="text-align:center; font-family: 'Poppins'">
                        <?php echo $this->db->get_where('mp_langingpage', array('id' => 1))->result_array()[0]['companyname'] ;
                        ?>
                    </h3>
                   <h4 style="text-align:center; font-family: 'Poppins'"><b> From </b> <b style="color: red"><?php echo $from; ?></b> <b> To </b> <b style="color: red"><?php echo $to; ?></b></h4>
                   <h4 style="text-align:center; font-family: 'Poppins'"><b> Created  </b> <?php echo Date('Y-m-d'); ?> </h4>
                </div>
            <div class="col-md-3"></div>  
        </div>
        <div class="row">
            <?php echo $ledger_records; ?>
        </div>
    </section>
<!-- Bootstrap model  -->
<?php $this->load->view('bootstrap_model.php'); ?>
<!-- Bootstrap model  ends--> 
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box" id="print-section">
                <div class="box-header">
                    <h3 class="box-title" style="font-family: 'Poppins'">
                        <i class="fas fa-arrow-circle-right" aria-hidden="true"></i> 
                             Take Backup :
                    </h3>
                </div>
                <div class="box-body">
                     <div class="row">
                         <div class="col-md-12">
                             <?php
                                $attributes = array('id'=>'Service_form','method'=>'post','class'=>'form-horizontal');
                            ?>
                            <?php echo form_open_multipart('backup/take_backup',$attributes); ?>

                                <div class="form-group text-center">                  
                                        <?php
                                            $data = array('class'=>'btn btn-danger btn-outline-primary ','type' => 'submit','name'=>'btn_submit_Service','value'=>'true', 'content' => '<i class="fas fa-download" aria-hidden="true"></i> Click here to take Back up');
                                            echo form_button($data);
                                         ?>    
                                  </div>
                              <?php echo form_close(); ?>
                         </div>
                     </div>   
                </div>
            </div>
        </div>
    </div>
</section>
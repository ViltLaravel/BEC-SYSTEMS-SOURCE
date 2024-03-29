<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="pull pull-right">
                <button type="button" onclick="show_modal_page('<?php echo base_url();?>todolist/popup/add_todolist_model')" class="btn btn-info btn-outline-primary " style="border-radius: 8px"><i class="fas fa-plus-square" aria-hidden="true"></i>
                    <?php echo $page_add_button_name; ?>
                </button>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title" style="font-family: 'Poppins'"><i class="fas fa-arrow-circle-right" aria-hidden="true"></i> <?php echo $table_name; ?></h3>
                </div>
                <div class="box-body">
                    <div class="col-md-12 table-responsive">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <?php
                    					foreach ($table_heading_names_of_coloums as $table_head)
                                        {
                        			?>
                                        <th>
                                            <?php echo $table_head; ?>
                                        </th>
                                    <?php
                    				    }
                    				?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                    				if($todo_records != NULL)
                                    {
                    					foreach($todo_records as $obj_todo_records)
                                        {
                    				?>
                                    <tr>
                                        <td>
                                            <?php echo $obj_todo_records->id; ?>
                                        </td>
                                        <td>
                                            <?php echo $obj_todo_records->title; ?>
                                        </td>
                                        <td>
                                            <?php echo $obj_todo_records->date; ?>
                                        </td>
                                        <td>
                                            <?php echo $obj_todo_records->addedby; ?>
                                        </td>
                                        <td>
                                            <?php 
                    							if($obj_todo_records->status == 0)
                                                {
                    							 echo "Active";
                    							}
                                                else
                                                {
                    								echo "In active";					
                    							}
                    						?>
                                        </td>
                                        <td>
                                            <div class="btn-group pull pull-right">
                                                <button type="button" class="btn btn-info btn-flat">Action</button>
                                                <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown">
                                                    <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu"> 
                                                <li onclick="show_modal_page('<?php echo base_url().'todolist/popup/edit_todolist_model/'.$obj_todo_records->id?>')" ><a href="#"><i class="fa fa-pencil"></i> Edit</a></li>
                                                <li><a onclick="confirmation_alert('delete this','<?php echo base_url(); ?>todolist/delete/<?php echo $obj_todo_records->id; ?>')"  href="javascript:void(0)" ><i class="fa fa-trash-o"></i> Delete </a></li>
                                                <li>
                                                <?php
                        							if($obj_todo_records->status == 0)
                                                    {
                        						?>
                                                    <a onclick="confirmation_alert('make this in active ','<?php echo base_url(); ?>todolist/change_status/<?php echo $obj_todo_records->id; ?>/1')"  href="javascript:void(0)"   ><i class="fa fa-minus"></i> In active</a>
                                                    <?php
                        							}
                                                    else
                                                    {
                        						    ?>
                                                    <a onclick="confirmation_alert('make this active','<?php echo base_url(); ?>todolist/change_status/<?php echo $obj_todo_records->id; ?>/0')"  href="javascript:void(0)"  ><i class="fa fa-plus"></i> Active</a>
                                                    <?php
                        							     }
                        							?>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <?php		
                					   }
                				    }
                				?>
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
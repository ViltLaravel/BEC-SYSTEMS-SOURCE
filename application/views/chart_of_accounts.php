<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="pull pull-right">
                <button type="button" class="btn btn-info btn-outline-primary" style="font-size: 12px;"  onclick="show_modal_page('<?php echo base_url().'accounts/popup/add_chart_of_accounts'; ?>')" ><i class="fas fa-plus-square" aria-hidden="true"></i> Create Head
                </button>
                <button onclick="printDiv('print-section')" class="btn btn-default btn-outline-primary   pull-right" style="font-size: 12px; margin-left: 2px"><i class="fas fa-print  pull-center"></i> Print Report</button>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box" id="print-section">
                <div class="box-header">
                    <h3 class="box-title" style="font-family: 'Poppins'"><i class="fas fa-arrow-circle-right" aria-hidden="true"></i> <?php echo $table_name; ?></h3>
                </div>
                <div class="box-body">
                     <div class="table-responsive col-md-12">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
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
                                    if($chart_list != NULL)
                                    {
                                    foreach ($chart_list as $single_account)
                                    {
                                ?>
                                    <tr>
                                        <td>
                                            <?php echo $single_account->name; ?>
                                        </td>
                                        <td>
                                            <?php echo $single_account->nature; ?>
                                        </td>
                                        <td>
                                            <?php echo $single_account->type; ?>
                                        </td>
                                        <td>
                                            <?php echo $single_account->relation_id; ?>
                                        </td>
                                        <td>
                                            <?php echo $single_account->expense_type; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group pull no-print pull-right">
                                                <button type="button" class="btn btn-info btn-flat">Action</button>
                                                <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown">
                                                    <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li  onclick="show_modal_page('<?php echo base_url().'accounts/popup/edit_chart_of_accounts/'.$single_account->id; ?>')" ><a href="#"> <i class="fa fa-pencil"></i>
                                                            Edit </a>
                                                    </li>
                                                    <li onclick="confirmation_alert('delete this head','<?php echo base_url().'accounts/delete/'.$single_account->id; ?>')" >
                                                        <i class="fa fa-trash-o"></i> Delete
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
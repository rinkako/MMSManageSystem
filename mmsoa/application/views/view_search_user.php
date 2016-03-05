<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="renderer" content="webkit">

    <title>MOA-查看用户列表</title>
    <?php $this->load->view('view_keyword'); ?>
    
    <link href="<?=base_url().'assets/images/moa.ico' ?>" rel="shortcut icon">
    
    <link href="<?=base_url().'assets/css/bootstrap.min.css?v=3.4.0' ?>" rel="stylesheet">
    <link href="<?=base_url().'assets/font-awesome/css/font-awesome.min.css' ?>" rel="stylesheet">
    
    <link href="<?=base_url().'assets/css/plugins/iCheck/custom.css' ?>" rel="stylesheet">
    
    <link href="<?=base_url().'assets/css/plugins/simditor/simditor.css' ?>" rel="stylesheet">
    
    <link href="<?=base_url().'assets/css/plugins/chosen/chosen.css' ?>" rel="stylesheet">
    
    <link href="<?=base_url().'assets/css/plugins/jasny/jasny-bootstrap.min.css' ?>" rel="stylesheet">
    
    <link href="<?=base_url().'assets/css/plugins/datepicker/datepicker3.css' ?>" rel="stylesheet">
    
    <!-- Data Tables -->
    <link href="<?=base_url().'assets/css/plugins/dataTables/dataTables.bootstrap.css' ?>" rel="stylesheet">
        
    <link href="<?=base_url().'assets/css/animate.css' ?>" rel="stylesheet">
    <link href="<?=base_url().'assets/css/style.css?v=2.2.0' ?>" rel="stylesheet">

</head>

<body onload="startTime()">
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
            	<?php $this->load->view('view_nav'); ?>
            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg dashbard-1">
            <?php $this->load->view('view_header'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2 id="time"></h2>
                    <ol class="breadcrumb">
                        <li>
                            MOA
                        </li>
                        <li>
                        	用户管理
                        </li>
                        <li>
                            <strong>查看</strong>
                        </li>	
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>查看用户列表</h5>
                            </div>
                            <div class="ibox-content">
                                
                                <table class="table table-striped table-bordered table-hover users-dataTable">
                                    <thead>
                                        <tr>
                                        	<th>序号</th>
                                            <th>姓名</th>
                                            <th>职务</th>
                                            <th>手机</th>
                                            <th>短号</th>
                                            <th>常检课室</th>
                                            <th>周检课室</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<?php for ($i = 0; $i < count($users); $i++) { ?>
	                                        <tr>
	                                        	<td><?php echo $i + 1; ?></td>
	                                            <td><?php echo $users[$i]->name; ?></td>
	                                            <td>
	                                            	<?php 
	                                            		switch ($users[$i]->level) {
	                                            			case 0: echo '普通助理'; break;
	                                            			case 1: echo '组长'; break;
	                                            			case 2: echo '负责人助理'; break;
	                                            			case 3: echo '助理负责人'; break;
	                                            			case 4: echo '管理员'; break;
	                                            			case 5: echo '办公室负责人'; break;
	                                            		}
	                                            	 ?>
	                                            </td>
	                                            <td><?php echo $users[$i]->phone; ?></td>
	                                            <td><?php echo $users[$i]->shortphone; ?></td>
	                                            <td>
	                                            	<?php 
	                                            		switch ($users[$i]->level) {
	                                            			case 0: echo str_replace(',', ' ', $workers[$i]->classroom); break;
	                                            			case 1: echo '无'; break;
	                                            			case 2: echo '无'; break;
	                                            			case 3: echo '无'; break;
	                                            			case 4: echo '无'; break;
	                                            			case 5: echo '无'; break;
	                                            		}
	                                            	 ?>
	                                            </td>
	                                            <td>
	                                            	<?php 
	                                            		switch ($users[$i]->level) {
	                                            			case 0: echo str_replace(',', ' ', $workers[$i]->week_classroom); break;
	                                            			case 1: echo '无'; break;
	                                            			case 2: echo '无'; break;
	                                            			case 3: echo '无'; break;
	                                            			case 4: echo '无'; break;
	                                            			case 5: echo '无'; break;
	                                            		}
	                                            	 ?>
	                                            </td>
	                                            <td>
	                                            	<button type="button" data-toggle="modal" href="<?php echo 'searchuser#modal-form' . $i; ?>" id="<?php echo $i; ?>" name="more_info"
	                                            	class="btn btn-xs btn-outline btn-default" value="<?php echo $i; ?>" onclick="showMore(event);" style="margin-bottom: -5px;">更多信息</button>
	                                            </td>
	                                        </tr>
	                                    <?php } ?>
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->load->view('view_footer'); ?>
        </div>
    </div>
    
    <?php for ($i = 0; $i < count($users); $i++) { ?>
	    <div id="<?php echo 'modal-form' . $i; ?>" class="modal fade" aria-hidden="true">
	        <div class="modal-dialog">
	            <div class="modal-content">
	            	<div class="modal-header">
	            		<h4 class='modal-title' id="modal_header" style='text-align: center'><?php echo $users[$i]->name; ?> - 详细信息</h4>
	                </div>
	                <div class="modal-body">
	                    <div class="row">
	                    
	                            <div class="list-group col-sm-6">
	                                <div class="list-group-item">
	                                    <h3 class="list-group-item-heading">用户名</h3>
	                                    <p class="list-group-item-text" id="info_username"><?php echo $users[$i]->username; ?></p>
	                                </div>
	
	                                <div class="list-group-item">
	                                    <h3 class="list-group-item-heading">QQ</h3>
	                                    <p class="list-group-item-text" id="info_qq"><?php echo $users[$i]->qq; ?></p>
	                                </div>
	
	                                <div class="list-group-item">
	                                    <h3 class="list-group-item-heading">微信</h3>
	                                    <p class="list-group-item-text" id="info_wechat"><?php echo $users[$i]->wechat; ?></p>
	                                </div>
	                            </div>
	                            
	                            <div class="list-group col-sm-6">
	                                <div class="list-group-item">
	                                    <h3 class="list-group-item-heading">学院</h3>
	                                    <p class="list-group-item-text" id="info_school"><?php echo $users[$i]->school; ?></p>
	                                </div>
	
	                                <div class="list-group-item">
	                                    <h3 class="list-group-item-heading">宿舍</h3>
	                                    <p class="list-group-item-text" id="info_address"><?php echo $users[$i]->address; ?></p>
	                                </div>
	                                
	                                <div class="list-group-item">
	                                    <h3 class="list-group-item-heading">入职日期</h3>
	                                    <p class="list-group-item-text" id="info_indate"><?php echo substr($users[$i]->indate, 0, 10); ?></p>
	                                </div>
	                            </div>
	                        
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
    <?php } ?>

    <!-- Mainly scripts -->
    <script src="<?=base_url().'assets/js/jquery-2.1.1.min.js' ?>"></script>
    <script src="<?=base_url().'assets/js/bootstrap.min.js?v=3.4.0' ?>"></script>
    <script src="<?=base_url().'assets/js/plugins/metisMenu/jquery.metisMenu.js' ?>"></script>
    <script src="<?=base_url().'assets/js/plugins/slimscroll/jquery.slimscroll.min.js' ?>"></script>
    <script src="<?=base_url().'assets/js/searchuser.js' ?>"></script>
    
    <!-- nav item active -->
	<script>
		$(document).ready(function () {
			$("#active-usermanagement").addClass("active");
			$("#active-searchuser").addClass("active");
			$("#mini").attr("href", "searchuser#");
		});
	</script>

    <!-- Custom and plugin javascript -->
    <script src="<?=base_url().'assets/js/hplus.js?v=2.2.0' ?>"></script>
    <script src="<?=base_url().'assets/js/plugins/pace/pace.min.js' ?>"></script>
    
    <!-- Dynamic date -->
    <script src="<?=base_url().'assets/js/dynamicDate.js' ?>"></script>
    
    <!-- Jquery Validate -->
    <script type="text/javascript" src="<?=base_url().'assets/js/plugins/validate/jquery.validate.min.js' ?>"></script>
    <script type="text/javascript" src="<?=base_url().'assets/js/plugins/validate/messages_zh.min.js' ?>"></script>
    
    <!-- iCheck -->
    <script src="<?=base_url().'assets/js/plugins/iCheck/icheck.min.js' ?>"></script>
    
    <!-- Chosen -->
    <script src="<?=base_url().'assets/js/plugins/chosen/chosen.jquery.js' ?>"></script>

    <!-- JSKnob -->
    <script src="<?=base_url().'assets/js/plugins/jsKnob/jquery.knob.js' ?>"></script>

    <!-- Input Mask-->
    <script src="<?=base_url().'assets/js/plugins/jasny/jasny-bootstrap.min.js' ?>"></script>

    <!-- Date picker -->
    <script src="<?=base_url().'assets/js/plugins/datepicker/bootstrap-datepicker.js' ?>"></script>
    
    <!-- Data Tables -->
    <script src="<?=base_url().'assets/js/plugins/dataTables/jquery.dataTables.js' ?>"></script>
    <script src="<?=base_url().'assets/js/plugins/dataTables/dataTables.bootstrap.js' ?>"></script>
    
    <script>
        $(document).ready(function () {
           
        	$('.users-dataTable').dataTable();

            /* Calendar */
            $('#calendar_date .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });
            
        });

        /* Chosen */
        var config = {
                '.chosen-select': {},
                '.chosen-select-deselect': {
                    allow_single_deselect: true
                },
                '.chosen-select-no-single': {
                    disable_search_threshold: 10
                },
                '.chosen-select-no-results': {
                    no_results_text: 'Oops, nothing found!'
                },
                '.chosen-select-width': {
                    width: "95%"
                }
            }
        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }
        
    </script>

</body>

</html>

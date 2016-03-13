<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="renderer" content="webkit">

    <title>MOA-值班报名</title>
    <?php $this->load->view('view_keyword'); ?>
    
    <link href="<?=base_url().'assets/images/moa.ico' ?>" rel="shortcut icon">
    
    <link href="<?=base_url().'assets/css/bootstrap.min.css?v=3.4.0' ?>" rel="stylesheet">
    <link href="<?=base_url().'assets/font-awesome/css/font-awesome.min.css' ?>" rel="stylesheet">
    
    <link href="<?=base_url().'assets/css/plugins/jasny/jasny-bootstrap.min.css' ?>" rel="stylesheet">
    
    <link href="<?=base_url().'assets/css/plugins/chosen/chosen.css' ?>" rel="stylesheet">
    
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
                        	值班安排
                        </li>
                        <li>
                            <strong>排班</strong>
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
                                <h5>排班</h5>
                            </div>
                            <div class="ibox-content" style="padding: 30px 65px;">
								<div class="form-group">
									<div class="row" style="margin-bottom: -15px;">
										<div class="col-sm-12">
					                        <table class="table table-bordered">
					                            <thead>
					                                <tr>
					                                    <th scope="col" abbr="per">时段</th>
					                                    <th scope="col" abbr="mon">周一</th>
					                                    <th scope="col" abbr="tue">周二</th>
					                                    <th scope="col" abbr="wed">周三</th>
					                                    <th scope="col" abbr="thu">周四</th>
					                                    <th scope="col" abbr="fri">周五</th>
					                                    <th scope="col" abbr="sat">周六</th>
					                                    <th scope="col" abbr="sun">周日</th>
					                                </tr>
					                            </thead>
					                            <tbody>
					                                <tr>
					                                    <th scope="row">07:30-10:30</th>
					                                    <td>
					                                    	<select id="select_MON1" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
					                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																	<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																<?php } ?>
				                                        	</select>
					                                    </td>
					                                    <td>
					                                    	<select id="select_TUE1" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
					                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																	<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																<?php } ?>
				                                        	</select>
					                                    </td>
					                                    <td>
					                                    	<select id="select_WED1" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
					                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																	<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																<?php } ?>
				                                        	</select>
					                                    </td>
					                                    <td>
					                                    	<select id="select_THU1" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
					                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																	<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																<?php } ?>
				                                        	</select>
					                                    </td>
					                                    <td>
					                                    	<select id="select_FRI1" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
					                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																	<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																<?php } ?>
				                                        	</select>
					                                    </td>
					                                    <td rowspan="2">
					                                    	<select id="select_SAT1" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
					                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																	<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																<?php } ?>
				                                        	</select>
					                                    </td>
					                                    <td rowspan="2">
					                                    	<select id="select_SUN1" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
					                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																	<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																<?php } ?>
				                                        	</select>
						                                    </td>
						                                </tr>
						                                <tr>
						                                    <th scope="row">10:30-12:30</th>
						                                    <td>
						                                    	<select id="select_MON2" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_TUE2" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_WED2" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_THU2" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_FRI2" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                </tr>
						                                <tr>
						                                    <th scope="row">12:30-14:00</th>
						                                    <td>
						                                    	<select id="select_MON3" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_TUE3" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_WED3" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_THU3" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_FRI3" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td rowspan="3">
						                                    	<select id="select_SAT2" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td rowspan="3">
						                                    	<select id="select_SUN2" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                </tr>
						                                <tr>
						                                    <th scope="row">14:00-16:00</th>
						                                    <td>
						                                    	<select id="select_MON4" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_TUE4" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_WED4" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_THU4" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_FRI4" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                </tr>
						                                <tr>
						                                    <th scope="row">16:00-18:00</th>
						                                    <td>
						                                    	<select id="select_MON5" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_TUE5" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_WED5" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_THU5" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_FRI5" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                </tr>
						                                <tr>
						                                    <th scope="row">18:00-22:00</th>
						                                    <td>
						                                    	<select id="select_MON6" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_TUE6" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_WED6" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_THU6" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_FRI6" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_SAT3" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                    <td>
						                                    	<select id="select_SUN3" data-placeholder="选择助理" class="chosen-select" multiple tabindex="4">
						                                            <?php for ($i = 0; $i < count($wid_list); $i++) { ?>
																		<option value="<?php echo $wid_list[$i]; ?>" hassubinfo="true"><?php echo $name_list[$i]; ?></option>
																	<?php } ?>
					                                        	</select>
						                                    </td>
						                                </tr>
						                            </tbody>
						                        </table>
									        </div>
								        </div>
								    </div>
						            <div class="hr-line-dashed"></div>
						            <div class="row">
	                                    <div class="form-group">
	                                        <div class="col-sm-4 col-sm-offset-5">
	                                            <button id="submit_duty" class="btn btn-primary" type="submit" 
	                                             data-toggle="modal" data-target="#myModal">发布</button>
	                                        </div>
	                                    </div>
                                    </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
            </div>
            <?php $this->load->view('view_footer'); ?>
	    </div>
	</div>
	
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">提示</h4>
                </div>
                <div class="modal-body">
                        <h1 id="submit_result" style="color:#ED5565;text-align:center;"></h1>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="<?=base_url().'assets/js/jquery-2.1.1.min.js' ?>"></script>
    <script src="<?=base_url().'assets/js/bootstrap.min.js?v=3.4.0' ?>"></script>
    <script src="<?=base_url().'assets/js/plugins/metisMenu/jquery.metisMenu.js' ?>"></script>
    <script src="<?=base_url().'assets/js/plugins/slimscroll/jquery.slimscroll.min.js' ?>"></script>
    <script src="<?=base_url().'assets/js/duty-arrange.js' ?>"></script>
    
    <!-- nav item active -->
	<script>
		$(document).ready(function () {
			$("#active-scheduleManagement").addClass("active");
			$("#active-dutyArrange").addClass("active");
			$("#mini").attr("href", "dutyArrange#");
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
    
    <!-- Chosen -->
    <script src="<?=base_url().'assets/js/plugins/chosen/chosen.jquery.js' ?>"></script>

    <!-- Input Mask-->
    <script src="<?=base_url().'assets/js/plugins/jasny/jasny-bootstrap.min.js' ?>"></script>
    
    <!-- Data Tables -->
    <script src="<?=base_url().'assets/js/plugins/dataTables/jquery.dataTables.js' ?>"></script>
    <script src="<?=base_url().'assets/js/plugins/dataTables/dataTables.bootstrap.js' ?>"></script>
    
    <script>
    /* Chosen */
    var config = {
            '.chosen-select': {
                // 实现中间字符的模糊查询
            	search_contains: true,
            	width: "80px"
            },
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

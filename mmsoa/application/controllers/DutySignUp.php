<?php
header("Content-type: text/html; charset=utf-8");

require_once('PublicMethod.php');

/**
 * 值班报名控制类
 * @author 伟
 */
Class DutySignUp extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('moa_user_model');
		$this->load->model('moa_worker_model');
		$this->load->model('moa_nschedule_model');
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
		$this->load->helper('cookie');
	}
	
	/**
	 * 进入值班报名页面
	 */
	public function index() {
		if (isset($_SESSION['user_id'])) {
			// 检查权限: 0-普通助理 3-助理负责人 6-超级管理员
			if ($_SESSION['level'] != 0 && $_SESSION['level'] != 3 && $_SESSION['level'] != 6) {
				// 提示权限不够
				PublicMethod::permissionDenied();
			}
				
			$this->load->view('view_duty_signup');
		} else {
			// 未登录的用户请先登录
			PublicMethod::requireLogin();
		}
	}
	
	/*
	 * 值班报名
	 */
	public function signUp() {
		if (isset($_SESSION['user_id'])) {
			$uid = $_SESSION['user_id'];
			$wid = $this->moa_worker_model->get_wid_by_uid($uid);
			
			$groupid = '';
			if (isset($_POST['groupid'])) {
				$groupid = $_POST['groupid'];
			}
			
			// 值班时间段，以“,”分隔
			$periods = '';
				
			for($i = 1; $i <= 6; $i++) {
				if(isset($_POST['MON'.$i])) {
					if($periods != '') {
						$periods = $periods . ',';
					}
					$periods = $periods . 'MON' . $i;
				}
			}
			for($i = 1; $i <= 6; $i++) {
				if(isset($_POST['TUE'.$i])) {
					if($periods != '') {
						$periods = $periods . ',';
					}
					$periods = $periods . 'TUE' . $i;
				}
			}
			for($i = 1; $i <= 6; $i++) {
				if(isset($_POST['WED'.$i])) {
					if($periods != '') {
						$periods = $periods . ',';
					}
					$periods = $periods . 'WED' . $i;
				}
			}
			for($i = 1; $i <= 6; $i++) {
				if(isset($_POST['THU'.$i])) {
					if($periods != '') {
						$periods = $periods . ',';
					}
					$periods = $periods . 'THU' . $i;
				}
			}
			for($i = 1; $i <= 6; $i++) {
				if(isset($_POST['FRI'.$i])) {
					if($periods != '') {
						$periods = $periods . ',';
					}
					$periods = $periods . 'FRI' . $i;
				}
			}
			for($i = 1; $i <= 3; $i++) {
				if(isset($_POST['SAT'.$i])) {
					if($periods != '') {
						$periods = $periods . ',';
					}
					$periods = $periods . 'SAT' . $i;
				}
			}
			for($i = 1; $i <= 3; $i++) {
				if(isset($_POST['SUN'.$i])) {
					if($periods != '') {
						$periods = $periods . ',';
					}
					$periods = $periods . 'SUN' . $i;
				}
			}

			$ns_paras['wid'] = $wid;
			$ns_paras['groupid'] = $groupid;
			$ns_paras['period'] = $periods;
			
			// 写入数据库
			if ($periods != '') {
				$schedule_obj_list = $this->moa_nschedule_model->get($wid);
				// 该助理已报过名，则删除原记录
				if (count($schedule_obj_list) != 0) {
					$delete_res = $this->moa_nschedule_model->delete($schedule_obj_list[0]->nsid);
					if ($delete_res == 0) {
						//echo json_encode(array("status" => FALSE, "msg" => "你之前报过名，请联系助理负责人"));
						echo("提交失败");
						return;
					}
				}

				// 增加新记录
				$nsid = $this->moa_nschedule_model->add($ns_paras);
				if ($nsid == FALSE) {
					$this->signup_failure();
					return;
				} else {
					$this->signup_success();
					return;
				}
			}
		}
	}
	
	/**
	 * 值班报名成功页面
	 */
	private function signup_success() {
		$data['status'] = TRUE;
		$this->load->view('view_signup_result', $data);
	}
	
	/**
	 * 值班报名失败页面
	 */
	private function signup_failure() {
		$data['status'] = FALSE;
		$this->load->view('view_signup_result', $data);
	}
	
}
<?php
header("Content-type: text/html; charset=utf-8");

require_once('Public_methods.php');

/**
 * 常检录入控制类
 * @author 伟
 */
Class Dailycheck_in extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('moa_user_model');
		$this->load->model('moa_worker_model');
		$this->load->model('moa_check_model');
		$this->load->model('moa_attend_model');
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
		$this->load->helper('cookie');
	}
	
	public function index() {
		
	}
	
	/*
	 * 早检
	 */
	public function dailycheck_morning() {
		if (isset($_SESSION['user_id'])) {
			// 获取常检课室
			$uid = $_SESSION['user_id'];
			$wid = $this->moa_worker_model->get_wid_by_uid($uid);
			$classrooms = $this->moa_worker_model->get($wid)->classroom;
			$classroom_list = explode(',', $classrooms);
			$room_count = count($classroom_list);
			$success_count = 0;
			$time = date('Y-m-d H:i:s');
				
			if (isset($_POST['cond_morning'])) {
				// 周一为一周的第一天
				$check_paras['weekcount'] = Public_methods::cal_week();
	
				// 1-周一  2-周二  ... 6-周六  7-周日
				$check_paras['weekday'] = date("w") == 0 ? 7 : date("w");
	
				// type: 0-早检  1-午检  2-晚检  3-周检
				$check_paras['type'] = 0;
	
				// isChecked: 0-否     1-是，正常     2-是，课室有故障
				$check_paras['isChecked'] = 0;
	
				$check_paras['actual_wid'] = $wid;
				$check_paras['timestamp'] = $time;
	
				for ($i = 0; $i < $room_count; $i++) {
					$roomid = $this->moa_check_model->get_roomid_by_room($classroom_list[$i]);
					$check_paras['roomid'] = $roomid;
					// 课室情况
					$prob_description = $_POST['cond_morning'][$i];
						
					// 课室正常
					if ($prob_description == "" || $prob_description == "正常") {
						$check_paras['isChecked'] = 1;
						// 添加早检记录
						$checkid = $this->moa_check_model->add_check($check_paras);
						if ($checkid) {
							$success_count++;
							continue;
						} else {
							echo json_encode(array("status" => FALSE, "msg" => "提交失败"));
							return;
						}
					}
					// 课室有故障
					else {
						$check_paras['isChecked'] = 2;
						// 添加问题（故障）记录
						$prob_paras['founder_wid'] = $wid;
						$prob_paras['roomid'] = $roomid;
						$prob_paras['description'] = $prob_description;
						$prob_paras['found_time'] = $time;
						$prob_paras['solved_time'] = $time;
						$problemid = $this->moa_check_model->add_problem($prob_paras);
						// 添加早检记录
						$check_paras['problemid'] = $problemid;
						$checkid = $this->moa_check_model->add_check($check_paras);
						if ($problemid && $checkid) {
							$success_count++;
							continue;
						} else {
							echo json_encode(array("status" => FALSE, "msg" => "提交失败"));
							return;
						}
					}
				} // for
				// 是否全部成功写入数据库
				if ($success_count == $room_count) {
					// 添加考勤记录
					$attend_paras['wid'] = $wid;
					$attend_paras['timestamp'] = $time;
					// 周次，周一为一周的第一天
					$attend_paras['weekcount'] = Public_methods::cal_week();
					// 1-周一  2-周二  ... 6-周六  7-周日
					$attend_paras['weekday'] = date("w") == 0 ? 7 : date("w");
					// 签到type: 0-值班  1-早检 2-午检 3-晚检 4-周检
					$attend_paras['type'] = 1;
					// 是否迟到：0-否 1-是
					$attend_paras['isLate'] = 0;
					// 是否代班：0 - 否   1 - 是
					$attend_paras['isSubstitute'] = 0;
					$attend_id = $this->moa_attend_model->add($attend_paras);
					if (!($attend_id)) {
						echo json_encode(array("status" => FALSE, "msg" => "登记失败"));
						return;
					}
					// 成功写入考勤记录
					echo json_encode(array("status" => TRUE, "msg" => "提交成功"));
					return;
				} else {
					echo json_encode(array("status" => FALSE, "msg" => "提交失败"));
					return;
				}
			} else {
				echo json_encode(array("status" => FALSE, "msg" => "提交失败"));
				return;
			}
		}
	}
	
	/*
	 * 午检
	 */
	public function dailycheck_noon() {
		if (isset($_SESSION['user_id'])) {
			// 获取常检课室
			$uid = $_SESSION['user_id'];
			$wid = $this->moa_worker_model->get_wid_by_uid($uid);
			$classrooms = $this->moa_worker_model->get($wid)->classroom;
			$classroom_list = explode(',', $classrooms);
			$room_count = count($classroom_list);
			$success_count = 0;
			$time = date('Y-m-d H:i:s');
	
			if (isset($_POST['cond_noon'])) {
				// 周一为一周的第一天
				$check_paras['weekcount'] = Public_methods::cal_week();
	
				// 1-周一  2-周二  ... 6-周六  7-周日
				$check_paras['weekday'] = date("w") == 0 ? 7 : date("w");
	
				// type: 0-早检  1-午检  2-晚检  3-周检
				$check_paras['type'] = 1;
	
				// isChecked: 0-否     1-是，正常     2-是，课室有故障
				$check_paras['isChecked'] = 0;
	
				$check_paras['actual_wid'] = $wid;
				$check_paras['timestamp'] = $time;
	
				for ($i = 0; $i < $room_count; $i++) {
					$roomid = $this->moa_check_model->get_roomid_by_room($classroom_list[$i]);
					$check_paras['roomid'] = $roomid;
					// 课室情况
					$prob_description = $_POST['cond_noon'][$i];
	
					// 课室正常
					if ($prob_description == "" || $prob_description == "正常") {
						$check_paras['isChecked'] = 1;
						// 添加午检记录
						$checkid = $this->moa_check_model->add_check($check_paras);
						if ($checkid) {
							$success_count++;
							continue;
						} else {
							echo json_encode(array("status" => false, "msg" => "提交失败"));
							return;
						}
					}
					// 课室有故障
					else {
						$check_paras['isChecked'] = 2;
						// 添加问题（故障）记录
						$prob_paras['founder_wid'] = $wid;
						$prob_paras['roomid'] = $roomid;
						$prob_paras['description'] = $prob_description;
						$prob_paras['found_time'] = $time;
						$prob_paras['solved_time'] = $time;
						$problemid = $this->moa_check_model->add_problem($prob_paras);
						// 添加午检记录
						$check_paras['problemid'] = $problemid;
						$checkid = $this->moa_check_model->add_check($check_paras);
						if ($problemid && $checkid) {
							$success_count++;
							continue;
						} else {
							echo json_encode(array("status" => FALSE, "msg" => "提交失败"));
							return;
						}
					}
				} // for
				// 是否全部成功写入数据库
				if ($success_count == $room_count) {
					// 添加考勤记录
					$attend_paras['wid'] = $wid;
					$attend_paras['timestamp'] = $time;
					// 周次，周一为一周的第一天
					$attend_paras['weekcount'] = Public_methods::cal_week();
					// 1-周一  2-周二  ... 6-周六  7-周日
					$attend_paras['weekday'] = date("w") == 0 ? 7 : date("w");
					// 签到type: 0-值班  1-早检 2-午检 3-晚检 4-周检
					$attend_paras['type'] = 2;
					// 是否迟到：0-否 1-是
					$attend_paras['isLate'] = 0;
					// 是否代班：0 - 否   1 - 是
					$attend_paras['isSubstitute'] = 0;
					$attend_id = $this->moa_attend_model->add($attend_paras);
					if (!($attend_id)) {
						echo json_encode(array("status" => FALSE, "msg" => "登记失败"));
						return;
					}
					// 成功写入考勤记录
					echo json_encode(array("status" => TRUE, "msg" => "提交成功"));
					return;
				} else {
					echo json_encode(array("status" => FALSE, "msg" => "提交失败"));
					return;
				}
			} else {
				echo json_encode(array("status" => FALSE, "msg" => "提交失败"));
				return;
			}
		}
	}
	
	/*
	 * 晚检
	 */
	public function dailycheck_evening() {
		if (isset($_SESSION['user_id'])) {
			// 获取常检课室
			$uid = $_SESSION['user_id'];
			$wid = $this->moa_worker_model->get_wid_by_uid($uid);
			$classrooms = $this->moa_worker_model->get($wid)->classroom;
			$classroom_list = explode(',', $classrooms);
			$room_count = count($classroom_list);
			$success_count = 0;
			$time = date('Y-m-d H:i:s');
	
			if (isset($_POST['cond_evening'])) {
				// 周一为一周的第一天
				$check_paras['weekcount'] = Public_methods::cal_week();
	
				// 1-周一  2-周二  ... 6-周六  7-周日
				$check_paras['weekday'] = date("w") == 0 ? 7 : date("w");
	
				// type: 0-早检  1-午检  2-晚检  3-周检
				$check_paras['type'] = 2;
	
				// isChecked: 0-否     1-是，正常     2-是，课室有故障
				$check_paras['isChecked'] = 0;
	
				$check_paras['actual_wid'] = $wid;
				$check_paras['timestamp'] = $time;
	
				for ($i = 0; $i < $room_count; $i++) {
					$roomid = $this->moa_check_model->get_roomid_by_room($classroom_list[$i]);
					$check_paras['roomid'] = $roomid;
					// 课室情况
					$prob_description = $_POST['cond_evening'][$i];
	
					// 课室正常
					if ($prob_description == "" || $prob_description == "正常") {
						$check_paras['isChecked'] = 1;
						// 添加晚检记录
						$checkid = $this->moa_check_model->add_check($check_paras);
						if ($checkid) {
							$success_count++;
							continue;
						} else {
							echo json_encode(array("status" => FALSE, "msg" => "提交失败"));
							return;
						}
					}
					// 课室有故障
					else {
						$check_paras['isChecked'] = 2;
						// 添加问题（故障）记录
						$prob_paras['founder_wid'] = $wid;
						$prob_paras['roomid'] = $roomid;
						$prob_paras['description'] = $prob_description;
						$prob_paras['found_time'] = $time;
						$prob_paras['solved_time'] = $time;
						$problemid = $this->moa_check_model->add_problem($prob_paras);
						// 添加晚检记录
						$check_paras['problemid'] = $problemid;
						$checkid = $this->moa_check_model->add_check($check_paras);
						if ($problemid && $checkid) {
							$success_count++;
							continue;
						} else {
							echo json_encode(array("status" => FALSE, "msg" => "提交失败"));
							return;
						}
					}
				} // for
				// 是否全部成功写入数据库
				if ($success_count == $room_count) {
					// 添加考勤记录
					$attend_paras['wid'] = $wid;
					$attend_paras['timestamp'] = $time;
					// 周次，周一为一周的第一天
					$attend_paras['weekcount'] = Public_methods::cal_week();
					// 1-周一  2-周二  ... 6-周六  7-周日
					$attend_paras['weekday'] = date("w") == 0 ? 7 : date("w");
					// 签到type: 0-值班  1-早检 2-午检 3-晚检 4-周检
					$attend_paras['type'] = 3;
					// 是否迟到：0-否 1-是
					$attend_paras['isLate'] = 0;
					// 是否代班：0 - 否   1 - 是
					$attend_paras['isSubstitute'] = 0;
					$attend_id = $this->moa_attend_model->add($attend_paras);
					if (!($attend_id)) {
						echo json_encode(array("status" => FALSE, "msg" => "登记失败"));
						return;
					}
					// 成功写入考勤记录
					echo json_encode(array("status" => TRUE, "msg" => "提交成功"));
					return;
				} else {
					echo json_encode(array("status" => FALSE, "msg" => "提交失败"));
					return;
				}
			} else {
				echo json_encode(array("status" => FALSE, "msg" => "提交失败"));
				return;
			}
		}
	}
	
}
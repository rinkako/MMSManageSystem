<?php
header("Content-type: text/html; charset=utf-8");

require_once('Public_methods.php');

/**
 * 后台控制类
 * @author 伟
 */
Class Backend extends CI_Controller {
	public function __construct() {
		parent::__construct();
 		$this->load->model('moa_user_model');
 		$this->load->model('moa_worker_model');
 		$this->load->model('moa_check_model');
 		$this->load->model('moa_room_model');
 		$this->load->model('moa_problem_model');
 		$this->load->model('moa_attend_model');
 		$this->load->model('moa_leaderreport_model');
 		$this->load->helper(array('form', 'url'));
 		$this->load->library('session');
 		$this->load->helper('cookie');
	}

	public function index() {
		$this->load->view('view_homepage');
	}
	
	public function homepage() {
		if (isset($_SESSION['user_id'])) {
			$this->load->view('view_homepage');
		} else {
			// 未登录的用户请先登录
			$this->requireLogin();
		}
	}
	
	/**
	 * 常检签到、课室情况登记页面
	 * 常检课室列表加载
	 */
	public function dailyCheck() {
		if (isset($_SESSION['user_id'])) {
			// 获取常检课室
			$uid = $_SESSION['user_id'];
			$wid = $this->moa_worker_model->get_wid_by_uid($uid);
			$classrooms = $this->moa_worker_model->get($wid)->classroom;
			$classroom_list = explode(',', $classrooms);
			$data['classroom_list'] = $classroom_list;
			$this->load->view('view_daily_check', $data);
		} else {
			// 未登录的用户请先登录
			$this->requireLogin();
		}
	}
	
	/**
	 * 周检签到、课室情况登记页面
	 * 周检课室列表加载
	 */
	public function weeklyCheck() {
		if (isset($_SESSION['user_id'])) {
			// 获取周检课室
			$uid = $_SESSION['user_id'];
			$wid = $this->moa_worker_model->get_wid_by_uid($uid);
			$classrooms = $this->moa_worker_model->get($wid)->week_classroom;
			$classroom_list = explode(',', $classrooms);
			$data['classroom_list'] = $classroom_list;
			$this->load->view('view_weekly_check', $data);
		} else {
			// 未登录的用户请先登录
			$this->requireLogin();
		}
	}
	
	/**
	 * 值班
	 */
	public function onDuty() {
		if (isset($_SESSION['user_id'])) {
			// 取所有普通助理的wid与name, level: 0-普通助理  1-组长  2-负责人助理  3-助理负责人  4-管理员  5-办公室负责人
			$level = 0;
			$common_worker = $this->moa_user_model->get_by_level($level);
				
			for ($i = 0; $i < count($common_worker); $i++) {
				$uid_list[$i] = $common_worker[$i]->uid;
				$name_list[$i] = $common_worker[$i]->name;
				$wid_list[$i] = $this->moa_worker_model->get_wid_by_uid($uid_list[$i]);
			}
			
			$wid = $this->moa_worker_model->get_wid_by_uid($_SESSION['user_id']);
			$data['wid'] = $wid;
			$data['name_list'] = $name_list;
			$data['wid_list'] = $wid_list;
			// 传入wid列表用于选择被代班助理
			$this->load->view('view_duty', $data);
		} else {
			// 未登录的用户请先登录
			$this->requireLogin();
		}
	}
	
	/**
	 * 拍摄
	 */
	public function filming() {
		if (isset($_SESSION['user_id'])) {
			$this->load->view('view_filming');
		} else {
			// 未登录的用户请先登录
			$this->requireLogin();
		}
	}
	
	/**
	 * 发布坐班日志
	 */
	public function writeJournal() {
		if (isset($_SESSION['user_id'])) {
			// 取所有普通助理的wid与name, level: 0-普通助理  1-组长  2-负责人助理  3-助理负责人  4-管理员  5-办公室负责人
			$level = 0;
			$common_worker = $this->moa_user_model->get_by_level($level);
			
			for ($i = 0; $i < count($common_worker); $i++) {
				$uid_list[$i] = $common_worker[$i]->uid;
				$name_list[$i] = $common_worker[$i]->name;
				$wid_list[$i] = $this->moa_worker_model->get_wid_by_uid($uid_list[$i]);
			}
			$data['name_list'] = $name_list;
			$data['wid_list'] = $wid_list;
			$this->load->view('view_write_journal', $data);
		} else {
			// 未登录的用户请先登录
			$this->requireLogin();
		}
	}
	
	/**
	 * 查看坐班日志
	 */
	public function readJournal() {
		if (isset($_SESSION['user_id'])) {
			// 获取最近的一篇坐班日志
			$data['leader_name'] = '';
			$data['group'] = '';
			$data['timestamp'] = '';
			$data['weekcount'] = '';
			$data['weekday'] = '';
			$data['body_list'] = array('', '', '', '', '', '');
			$data['best_list'] = array();
			$data['bad_list'] = array();
			
			// state： 0 - 正常  1- 已删除
			$state = 0;
			$report_obj = $this->moa_leaderreport_model->get_lasted($state);
			// 正确获取到所需记录
			if ($report_obj) {
				$data['group'] = Public_methods::translate_group($report_obj->group);
				$data['timestamp'] = $report_obj->timestamp;
				$data['weekcount'] = $report_obj->weekcount;
				$data['weekday'] = Public_methods::translate_weekday($report_obj->weekday);
				$body_list = explode(' ## ', $report_obj->body);
				$data['body_list'] = $body_list;
				
				// 获取组长姓名
				$leader_wid = $report_obj->wid;
				$r_worker_obj = $this->moa_worker_model->get($leader_wid);
				$r_user_obj = $this->moa_user_model->get($r_worker_obj->uid);
				$data['leader_name'] = $r_user_obj->name;
				
				// 获取优秀助理姓名列表
				$best_list = array();
				if (!is_null($report_obj->bestlist)) {
					$best_wid_list = explode(',', $report_obj->bestlist);
					for ($i = 0; $i < count($best_wid_list); $i++) {
						$best_wid = $best_wid_list[$i];
						$best_worker_obj = $this->moa_worker_model->get($best_wid);
						$best_user_obj = $this->moa_user_model->get($best_worker_obj->uid);
						$best_list[$i] = $best_user_obj->name;
					}
				}
				$data['best_list'] = $best_list;
				
				// 获取异常助理姓名列表
				$bad_list = array();
				if (!is_null($report_obj->badlist)) {
					$bad_wid_list = explode(',', $report_obj->badlist);
					for ($j = 0; $j < count($bad_wid_list); $j++) {
						$bad_wid = $bad_wid_list[$j];
						$bad_worker_obj = $this->moa_worker_model->get($bad_wid);
						$bad_user_obj = $this->moa_user_model->get($bad_worker_obj->uid);
						$bad_list[$j] = $bad_user_obj->name;
					}
				}
				$data['bad_list'] = $bad_list;
			}
			
			$this->load->view('view_read_journal', $data);
		} else {
			// 未登录的用户请先登录
			$this->requireLogin();
		}
	}
	
	/**
	 * 查看/修改个人资料
	 */
	public function personalData() {
		if (isset($_SESSION['user_id'])) {
			// 获取个人信息
			$obj = $this->moa_user_model->get($_SESSION['user_id']);
			$data['personal_data'] = $obj;
			$this->load->view('view_personal_data', $data);
			//$this->load->view('view_personal_data');
		} else {
			// 未登录的用户请先登录
			$this->requireLogin();
		}
	}

	/*
	public function new_ui() {
		if (isset($_SESSION['user_id'])) {
			// 获取个人信息
			$obj = $this->moa_user_model->get($_SESSION['user_id']);
			$data['personal_data'] = $obj;
			//$this->load->view('view_personal_data', $data);
			$this->load->view('new_ui_test', $data);
		} else {
			// 未登录的用户请先登录
			$this->requireLogin();
		}
	}
	*/
	
	/**
	 * 修改密码
	 */
	public function changePassword() {
		if (isset($_SESSION['user_id'])) {
			// 获取个人信息
			$obj = $this->moa_user_model->get($_SESSION['user_id']);
			$data['username'] = $obj->username;
			$this->load->view('view_change_password', $data);
		} else {
			// 未登录的用户请先登录
			$this->requireLogin();
		}
	}
	
	/**
	 * 添加新用户
	 */
	public function addUser() {
		if (isset($_SESSION['user_id'])) {
			$data['daily_classrooms'] = Public_methods::get_daily_classrooms();
			$data['weekly_classrooms'] = Public_methods::get_weekly_classrooms();
			$this->load->view('view_add_user', $data);
		} else {
			// 未登录的用户请先登录
			$this->requireLogin();
		}
	}
	
	/**
	 * 查看用户列表
	 */
	public function searchUser() {
		if (isset($_SESSION['user_id'])) {
			// state: 0-正常  1-锁定  2-已删除
			$state = 0;
			// 取状态为正常的所有用户
			$users = $this->moa_user_model->get_by_state($state);
			// 获取普通助理的常检周检课室列表
			$workers = array();
			for ($i = 0; $i < count($users); $i++) {
				if ($users[$i]->level == 0) {
					$tmp_wid = $this->moa_worker_model->get_wid_by_uid($users[$i]->uid);
					$workers[$i] = $this->moa_worker_model->get($tmp_wid);
				}
			}
			$data['users'] = $users;
			$data['workers'] = $workers;
			$this->load->view('view_search_user', $data);
		} else {
			// 未登录的用户请先登录
			$this->requireLogin();
		}
	}
	
	/**
	 * 查看常检记录
	 */
	public function dailyReview() {
		if (isset($_SESSION['user_id'])) {
			// 周一为一周的第一天
			$weekcount = Public_methods::cal_week();
			
			// 1-周一  2-周二  ... 6-周六  7-周日
			$weekday = date("w") == 0 ? 7 : date("w");
			$weekday_desc = Public_methods::translate_weekday($weekday);
			
			// 已完成早检助理人数
			$m_count = 0;
			$data['m_count'] = $m_count;
			
			// 已完成午检助理人数
			$n_count = 0;
			$data['n_count'] = $n_count;
			
			// 已完成晚检助理人数
			$e_count = 0;
			$data['e_count'] = $e_count;
			
			/* 
			 * 今日早检记录 
			 */
			// 获取今日所有早检记录
			$check_type = 0;
			$m_check_obj = $this->moa_check_model->get_by_week_type($weekcount, $weekday, $check_type);
			if ($m_check_obj != FALSE) {
				// 获取已完成早检的助理名单
				$m_wid_list = array();
				$m_prob_list = array();
				$m_time_list = array();
				$m_room_list = array();
				$m_name_list = array();
				
				$m_tmp_wid = $m_check_obj[0]->actual_wid;
				$m_wid_list[$m_count] = $m_tmp_wid;
				$m_prob_list[$m_count] = '';
				$m_time_list[$m_count] = $m_check_obj[0]->timestamp;
				
				// 获取常检课室
				$m_worker_obj = $this->moa_worker_model->get($m_tmp_wid);
				$m_room_list[$m_count] = $m_worker_obj->classroom;
				
				// 获取姓名
				$m_user_obj = $this->moa_user_model->get($m_worker_obj->uid);
				$m_name_list[$m_count] = $m_user_obj->name;
				
				for ($i = 0; $i < count($m_check_obj); $i++) {
					// 不同的wid,添加相关信息
					if ($m_check_obj[$i]->actual_wid != $m_tmp_wid) {
						$m_count++;
						$m_tmp_wid = $m_check_obj[$i]->actual_wid;
						$m_wid_list[$m_count] = $m_tmp_wid;
						$m_prob_list[$m_count] = '';
						$m_time_list[$m_count] = $m_check_obj[$i]->timestamp;
						$m_worker_obj = $this->moa_worker_model->get($m_tmp_wid);
						$m_room_list[$m_count] = $m_worker_obj->classroom;
						$m_user_obj = $this->moa_user_model->get($m_worker_obj->uid);
						$m_name_list[$m_count] = $m_user_obj->name;
					}
					// 课室有故障，添加故障说明到$m_prob_list
					if ($m_check_obj[$i]->isChecked == 2) {
						$m_room_obj = $this->moa_room_model->get($m_check_obj[$i]->roomid);
						$m_pro_obj = $this->moa_problem_model->get($m_check_obj[$i]->problemid);
						$m_prob_list[$m_count] = $m_prob_list[$m_count] . '<b>' . $m_room_obj->room . '</b> ' . 
												$m_pro_obj->description . ' <br />';
					}
				}
				
				// 装载前端所需数据
				$data['m_count'] = $m_count + 1;
				$data['m_weekcount'] = $weekcount;
				$data['m_weekday'] = $weekday_desc;
				$data['m_name_list'] = $m_name_list;
				$data['m_room_list'] = $m_room_list;
				$data['m_prob_list'] = $m_prob_list;
				$data['m_time_list'] = $m_time_list;
			}
			
			
			/*
			 * 今日午检记录
			 */
			// 获取今日所有午检记录
			$check_type = 1;
		    $n_check_obj = $this->moa_check_model->get_by_week_type($weekcount, $weekday, $check_type);
			if ($n_check_obj != FALSE) {
				// 获取已完成早检的助理名单
				$n_wid_list = array();
				$n_prob_list = array();
				$n_time_list = array();
				$n_room_list = array();
				$n_name_list = array();
				
				$n_tmp_wid = $n_check_obj[0]->actual_wid;
				$n_wid_list[$n_count] = $n_tmp_wid;
				$n_prob_list[$n_count] = '';
				$n_time_list[$n_count] = $n_check_obj[0]->timestamp;
				
				// 获取常检课室
				$n_worker_obj = $this->moa_worker_model->get($n_tmp_wid);
				$n_room_list[$n_count] = $n_worker_obj->classroom;
				
				// 获取姓名
				$n_user_obj = $this->moa_user_model->get($n_worker_obj->uid);
				$n_name_list[$n_count] = $n_user_obj->name;
				
				for ($j = 0; $j < count($n_check_obj); $j++) {
					// 不同的wid,添加相关信息
					if ($n_check_obj[$j]->actual_wid != $n_tmp_wid) {
						$n_count++;
						$n_tmp_wid = $n_check_obj[$j]->actual_wid;
						$n_wid_list[$n_count] = $n_tmp_wid;
						$n_prob_list[$n_count] = '';
						$n_time_list[$n_count] = $n_check_obj[$j]->timestamp;
						$n_worker_obj = $this->moa_worker_model->get($n_tmp_wid);
						$n_room_list[$n_count] = $n_worker_obj->classroom;
						$n_user_obj = $this->moa_user_model->get($n_worker_obj->uid);
						$n_name_list[$n_count] = $n_user_obj->name;
					}
					// 课室有故障，添加故障说明到$n_prob_list
					if ($n_check_obj[$j]->isChecked == 2) {
						$n_room_obj = $this->moa_room_model->get($n_check_obj[$j]->roomid);
						$n_pro_obj = $this->moa_problem_model->get($n_check_obj[$j]->problemid);
						$n_prob_list[$n_count] = $n_prob_list[$n_count] . '<b>' . $n_room_obj->room . '</b> ' . 
												$n_pro_obj->description . ' <br />';
					}
				}
				
				// 装载前端所需数据
				$data['n_count'] = $n_count + 1;
				$data['n_weekcount'] = $weekcount;
				$data['n_weekday'] = $weekday_desc;
				$data['n_name_list'] = $n_name_list;
				$data['n_room_list'] = $n_room_list;
				$data['n_prob_list'] = $n_prob_list;
				$data['n_time_list'] = $n_time_list;
			}
			
			/*
			 * 今日晚检记录
			 */
			// 获取今日所有晚检记录
			$check_type = 2;
		            $e_check_obj = $this->moa_check_model->get_by_week_type($weekcount, $weekday, $check_type);
			if ($e_check_obj != FALSE) {
				// 获取已完成早检的助理名单
				$e_wid_list = array();
				$e_prob_list = array();
				$e_time_list = array();
				$e_room_list = array();
				$e_name_list = array();
				
				$e_tmp_wid = $e_check_obj[0]->actual_wid;
				$e_wid_list[$e_count] = $e_tmp_wid;
				$e_prob_list[$e_count] = '';
				$e_time_list[$e_count] = $e_check_obj[0]->timestamp;
				
				// 获取常检课室
				$e_worker_obj = $this->moa_worker_model->get($e_tmp_wid);
				$e_room_list[$e_count] = $e_worker_obj->classroom;
				
				// 获取姓名
				$e_user_obj = $this->moa_user_model->get($e_worker_obj->uid);
				$e_name_list[$e_count] = $e_user_obj->name;
				
				for ($k = 0; $k < count($e_check_obj); $k++) {
					// 不同的wid,添加相关信息
					if ($e_check_obj[$k]->actual_wid != $e_tmp_wid) {
						$e_count++;
						$e_tmp_wid = $e_check_obj[$k]->actual_wid;
						$e_wid_list[$e_count] = $e_tmp_wid;
						$e_prob_list[$e_count] = '';
						$e_time_list[$e_count] = $e_check_obj[$k]->timestamp;
						$e_worker_obj = $this->moa_worker_model->get($e_tmp_wid);
						$e_room_list[$e_count] = $e_worker_obj->classroom;
						$e_user_obj = $this->moa_user_model->get($e_worker_obj->uid);
						$e_name_list[$e_count] = $e_user_obj->name;
					}
					// 课室有故障，添加故障说明到$e_prob_list
					if ($e_check_obj[$k]->isChecked == 2) {
						$e_room_obj = $this->moa_room_model->get($e_check_obj[$k]->roomid);
						$e_pro_obj = $this->moa_problem_model->get($e_check_obj[$k]->problemid);
						$e_prob_list[$e_count] = $e_prob_list[$e_count] . '<b>' . $e_room_obj->room . '</b> ' . 
												$e_pro_obj->description . ' <br />';
					}
				}
				
				// 装载前端所需数据
				$data['e_count'] = $e_count + 1;
				$data['e_weekcount'] = $weekcount;
				$data['e_weekday'] = $weekday_desc;
				$data['e_name_list'] = $e_name_list;
				$data['e_room_list'] = $e_room_list;
				$data['e_prob_list'] = $e_prob_list;
				$data['e_time_list'] = $e_time_list;
			}
			
			$this->load->view('view_daily_review', $data);
		} else {
			// 未登录的用户请先登录
			$this->requireLogin();
		}
	}
	
	/**
	 * 查看周检记录
	 */
	public function weeklyReview() {
		if (isset($_SESSION['user_id'])) {
			// 周一为一周的第一天
			$weekcount = Public_methods::cal_week();
				
			// 已完成早检助理人数
			$w_count = 0;
			$data['w_count'] = $w_count;
			
			// 获取本周所有周检记录
			$check_type = 3;
			$w_check_obj = $this->moa_check_model->get_by_weekcount_type($weekcount, $check_type);
			if ($w_check_obj != FALSE) {
				// 获取已完成周检的助理名单
				$w_wid_list = array();
				$w_prob_list = array();
				$w_time_list = array();
				$w_room_list = array();
				$w_name_list = array();
				$w_day_list = array();
				$w_lamp_list = array();
	
				for ($i = 0; $i < count($w_check_obj); $i++) {
					$w_tmp_wid = $w_check_obj[$i]->actual_wid;
					$w_wid_list[$w_count] = $w_tmp_wid;
					$w_day_list[$w_count] = Public_methods::translate_weekday($w_check_obj[$i]->weekday);
					$w_time_list[$w_count] = $w_check_obj[$i]->timestamp;
					$w_lamp_list[$w_count] = $w_check_obj[$i]->light;
					$w_worker_obj = $this->moa_worker_model->get($w_tmp_wid);
					$w_user_obj = $this->moa_user_model->get($w_worker_obj->uid);
					$w_name_list[$w_count] = $w_user_obj->name;
					$w_room_obj = $this->moa_room_model->get($w_check_obj[$i]->roomid);
					$w_room_list[$w_count] = $w_room_obj->room;
					
					// 课室有故障，添加故障说明到$w_prob_list
					$w_prob_list[$w_count] = '';
					if ($w_check_obj[$i]->isChecked == 2) {
						$w_pro_obj = $this->moa_problem_model->get($w_check_obj[$i]->problemid);
						$w_prob_list[$w_count] = $w_prob_list[$w_count] . $w_pro_obj->description;
					}
					
					$w_count++;
				}
	
				// 装载前端所需数据
				$data['w_count'] = $w_count;
				$data['w_weekcount'] = $weekcount;
				$data['w_day_list'] = $w_day_list;
				$data['w_name_list'] = $w_name_list;
				$data['w_room_list'] = $w_room_list;
				$data['w_prob_list'] = $w_prob_list;
				$data['w_lamp_list'] = $w_lamp_list;
				$data['w_time_list'] = $w_time_list;
			}
				
			$this->load->view('view_weekly_review', $data);
		} else {
			// 未登录的用户请先登录
			$this->requireLogin();
		}
	}
	
	/**
	 * 查看值班记录
	 */
	public function dutyReview() {
		if (isset($_SESSION['user_id'])) {
			// 周一为一周的第一天
			$weekcount = Public_methods::cal_week();
			
			// 1-周一  2-周二  ... 6-周六  7-周日
			$weekday = date("w") == 0 ? 7 : date("w");
			$weekday_desc = Public_methods::translate_weekday($weekday);
			
			// 已完成值班助理人数
			$d_count = 0;
			$data['d_count'] = $d_count;
			
			// 获取今日所有值班记录
			// 考勤类型：0 - 值班 1 - 早检 2 - 午检 3 - 晚检 4 - 周检
			$attend_type = 0;
			$d_attend_obj = $this->moa_attend_model->get_by_week_type($weekcount, $weekday, $attend_type);
			if ($d_attend_obj != FALSE) {
				// 获取已完成值班的助理名单
				$d_wid_list = array();
				$d_time_list = array();
				$d_duration_list = array();
				$d_name_list = array();
				$d_sub_list = array();
			
				for ($i = 0; $i < count($d_attend_obj); $i++) {
					$d_tmp_wid = $d_attend_obj[$i]->wid;
					$d_wid_list[$d_count] = $d_tmp_wid;
					$d_time_list[$d_count] = $d_attend_obj[$i]->timestamp;
					$d_tmp_period = $d_attend_obj[$i]->dutyPeriod;
					$d_tmp_duration = Public_methods::get_duty_duration($d_tmp_period);
					$d_tmp_hours = Public_methods::get_working_hours($d_tmp_period);
					$d_duration_list[$d_count] = '<b>' . $d_tmp_duration . '</b> &nbsp;&nbsp;' . $d_tmp_hours . '小时';
					
					// 获取姓名
					$d_worker_obj = $this->moa_worker_model->get($d_tmp_wid);
					$d_user_obj = $this->moa_user_model->get($d_worker_obj->uid);
					$d_name_list[$d_count] = $d_user_obj->name;
						
					// 若有代班，添加代班说明到$d_sub_list
					$d_sub_list[$d_count] = '';
					// 是否代班： 0 - 否  1 - 是
					if ($d_attend_obj[$i]->isSubstitute == 1) {
						// 获取被代班助理姓名
						$d_subed_wid = $d_attend_obj[$i]->substituteFor;
						$d_subed_worker_obj = $this->moa_worker_model->get($d_subed_wid);
						$d_subed_user_obj = $this->moa_user_model->get($d_subed_worker_obj->uid);
						$d_subed_tmp_name = $d_subed_user_obj->name;
						$d_sub_list[$d_count] = '代 ' . $d_subed_tmp_name;
					}
						
					$d_count++;
				}
			
				// 装载前端所需数据
				$data['d_count'] = $d_count;
				$data['d_weekcount'] = $weekcount;
				$data['d_weekday'] = $weekday;
				$data['d_name_list'] = $d_name_list;
				$data['d_duration_list'] = $d_duration_list;
				$data['d_sub_list'] = $d_sub_list;
				$data['d_time_list'] = $d_time_list;
			}
			
			$this->load->view('view_duty_review', $data);
		} else {
			// 未登录的用户请先登录
			$this->requireLogin();
		}
	}
	
	/**
	 * 全员（办公室负责人及管理员除外）工时统计
	 */
	public function allWorkingTime() {
		if (isset($_SESSION['user_id'])) {
			// level: 0-普通助理 1-组长 2-负责人助理 3-助理负责人
			$level_arr = array('0', '1', '2', '3');
			// 正常有效记录
			$state = 0;
			$u_obj_list = $this->moa_user_model->get_by_multiple_level($level_arr, $state);
			
			$u_name_list = array();
			$u_card_list = array();
			$u_phone_list = array();
			$u_total_contri_list = array();
			$u_total_salary_list = array();
			$w_month_contri_list = array();
			$w_month_salary_list = array();
			$count = 0;
			
			if ($u_obj_list != FALSE) {
				for ($count = 0; $count < count($u_obj_list); $count++) {
					$u_name_list[$count] = $u_obj_list[$count]->name;
					$tmp_uid = $u_obj_list[$count]->uid;
					$u_card_list[$count] = $u_obj_list[$count]->creditcard;
					$u_phone_list[$count] = $u_obj_list[$count]->phone;
					$tmp_total_contri = $u_obj_list[$count]->contribution;
					$tmp_total_penalty = $u_obj_list[$count]->totalPenalty;
					// 历史总实际工时 = 历史总工时 - 历史总扣除工时
					$tmp_total_real_contri = $tmp_total_contri - $tmp_total_penalty;
					$u_total_contri_list[$count] = $tmp_total_real_contri;
					$u_total_salary_list[$count] = Public_methods::cal_salary($tmp_total_real_contri);
					// 从Worker表获取本月工时
					$tmp_wid = $this->moa_worker_model->get_wid_by_uid($tmp_uid);
					$tmp_worker_obj = $this->moa_worker_model->get($tmp_wid);
					$tmp_month_contri = $tmp_worker_obj->worktime;
					$tmp_month_penalty = $tmp_worker_obj->penalty;
					// 本月实际工时 = 本月总工时 - 本月总扣除工时
					$tmp_month_real_contri = $tmp_month_contri - $tmp_month_penalty;
					$w_month_contri_list[$count] = $tmp_month_real_contri;
					$w_month_salary_list[$count] = Public_methods::cal_salary($tmp_month_real_contri);
				}
			}
			
			$data['count'] = $count;
			$data['name_list'] = $u_name_list;
			$data['card_list'] = $u_card_list;
			$data['phone_list'] = $u_phone_list;
			$data['total_contri_list'] = $u_total_contri_list;
			$data['total_salary_list'] = $u_total_salary_list;
			$data['month_contri_list'] = $w_month_contri_list;
			$data['month_salary_list'] = $w_month_salary_list;
			
			$this->load->view('view_all_time', $data);
		} else {
			// 未登录的用户请先登录
			$this->requireLogin();
		}
	}
	
	/**
	 * 个人工时详情
	 */
	public function perWorkingTime() {
		if (isset($_SESSION['user_id'])) {
			// 入职日期
			$indate = '';
			// 在职年数
			$working_age = '';
			// 银行卡号
			$card = '';
			// 本月实际工时
			$month_contri = 0;
			// 本月被扣工时
			$month_penalty = 0;
			// 本月工资
			$month_salary = 0;
			// 历史累计实际工时
			$total_contri = 0;
			// 历史累计被扣工时
			$total_penalty = 0;
			// 历史累计工资
			$total_salary = 0;
			
			$uid = $_SESSION['user_id'];
			$user_obj = $this->moa_user_model->get($uid);
			
			if ($user_obj != FALSE) {
				$indate = $user_obj->indate;
				$working_age = Public_methods::cal_working_age($indate);
				$card = $user_obj->creditcard;
				// 历史累计
				$tmp_total_contri = $user_obj->contribution;
				$total_penalty = $user_obj->totalPenalty;
				$total_contri = $tmp_total_contri - $total_penalty;
				$total_salary = Public_methods::cal_salary($total_contri);
				// 本月
				$wid = $this->moa_worker_model->get_wid_by_uid($uid);
				$worker_obj = $this->moa_worker_model->get($wid);
				$tmp_month_contri = $worker_obj->worktime;
				$month_penalty = $worker_obj->penalty;
				$month_contri = $tmp_month_contri - $month_penalty;
				$month_salary = Public_methods::cal_salary($month_contri);
			}
			
			$data['indate'] = substr($indate, 0, 10);
			$data['working_age'] = $working_age;
			$data['month_contri'] = $month_contri;
			$data['month_penalty'] = $month_penalty;
			$data['month_salary'] = $month_salary;
			$data['total_contri'] = $total_contri;
			$data['total_penalty'] = $total_penalty;
			$data['total_salary'] = $total_salary;
			$data['card'] = Public_methods::creditcard_format($card);
			
			$this->load->view('view_per_time', $data);
		} else {
			// 未登录的用户请先登录
			$this->requireLogin();
		}
	}
	
	/**
	 * 值班报名
	 */
	public function dutySignUp() {
		if (isset($_SESSION['user_id'])) {
			$this->load->view('view_duty_signup');
		} else {
			// 未登录的用户请先登录
			$this->requireLogin();
		}
	}
	
	/**
	 * 排班
	 */
	public function dutyArrange() {
		if (isset($_SESSION['user_id'])) {
			// 取所有普通助理的wid与name, level: 0-普通助理  1-组长  2-负责人助理  3-助理负责人  4-管理员  5-办公室负责人
			$level = 0;
			$common_worker = $this->moa_user_model->get_by_level($level);
				
			for ($i = 0; $i < count($common_worker); $i++) {
				$uid_list[$i] = $common_worker[$i]->uid;
				$name_list[$i] = $common_worker[$i]->name;
				$wid_list[$i] = $this->moa_worker_model->get_wid_by_uid($uid_list[$i]);
			}
			$data['name_list'] = $name_list;
			$data['wid_list'] = $wid_list;
			$this->load->view('view_duty_arrange', $data);
		} else {
			// 未登录的用户请先登录
			$this->requireLogin();
		}
	}
	
	
	/**
	 * 登录要求
	 */
	private function requireLogin() {
		// 未登录的用户请先登录
		echo "<script language=javascript>alert('要访问的页面需要先登录！');</script>";
		$_SESSION['user_url'] = $_SERVER['REQUEST_URI'];
		echo '<script language=javascript>window.location.href="../Login"</script>';
	}
	
	
	
	/**
	 * 根据worker的classroom录入所有课室
	 */
	public function addRoom() {
		$state = 0;
		$level = 0;
		$users = $this->moa_user_model->get_by_level_state($level, $state);
		$workers = array();
		for ($i = 0; $i < count($users); $i++) {
			$wid = $this->moa_worker_model->get_wid_by_uid($users[$i]->uid);
			$workers[$i] = $this->moa_worker_model->get($wid);
			$room_list = array();
			$room_list = explode(',', $workers[$i]->classroom);
			for ($j = 0; $j < count($room_list); $j++) {
				$res = $this->moa_check_model->get_roomid_by_room($room_list[$j]);
				if (!$res) {
					$paras['room'] = $room_list[$j];
					$paras['wid'] = $wid;
					$paras['state'] = 0;
					$roomid = $this->moa_room_model->add($paras);
				}
			}
			unset($room_list);
		}
		
	}
	
}
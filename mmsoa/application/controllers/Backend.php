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
 		$this->load->helper(array('form', 'url'));
 		$this->load->library('session');
 		$this->load->helper('cookie');
	}

	public function index() {
		$this->load->view('view_homepage');
	}
	
	public function homepage() {
		$this->load->view('view_homepage');
	}
	
	/*
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
			echo "<script language=javascript>alert('要访问的页面需要先登录！');</script>";
			$_SESSION['user_url'] = $_SERVER['REQUEST_URI'];
			echo '<script language=javascript>window.location.href="../Login"</script>';
		}
	}
	
	/*
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
			echo "<script language=javascript>alert('要访问的页面需要先登录！');</script>";
			$_SESSION['user_url'] = $_SERVER['REQUEST_URI'];
			echo '<script language=javascript>window.location.href="../Login"</script>';
		}
	}
	
	/*
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
			$this->load->view('view_on_duty', $data);
		} else {
			// 未登录的用户请先登录
			echo "<script language=javascript>alert('要访问的页面需要先登录！');</script>";
			$_SESSION['user_url'] = $_SERVER['REQUEST_URI'];
			echo '<script language=javascript>window.location.href="../Login"</script>';
		}
	}
	
	/*
	 * 拍摄
	 */
	public function filming() {
		if (isset($_SESSION['user_id'])) {
			$this->load->view('view_filming');
		} else {
			// 未登录的用户请先登录
			echo "<script language=javascript>alert('要访问的页面需要先登录！');</script>";
			$_SESSION['user_url'] = $_SERVER['REQUEST_URI'];
			echo '<script language=javascript>window.location.href="../Login"</script>';
		}
	}
	
	/*
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
			echo "<script language=javascript>alert('要访问的页面需要先登录！');</script>";
			$_SESSION['user_url'] = $_SERVER['REQUEST_URI'];
			echo '<script language=javascript>window.location.href="../Login"</script>';
		}
	}
	
	/*
	 * 查看/修改个人资料
	 */
	public function personalData() {
		if (isset($_SESSION['user_id'])) {
			// 获取个人信息
			$obj = $this->moa_user_model->get($_SESSION['user_id']);
			$data['personal_data'] = $obj;
			$this->load->view('view_personal_data', $data);
		} else {
			// 未登录的用户请先登录
			echo "<script language=javascript>alert('要访问的页面需要先登录！');</script>";
			$_SESSION['user_url'] = $_SERVER['REQUEST_URI'];
			echo '<script language=javascript>window.location.href="../Login"</script>';
		}
	}
	
	/*
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
			echo "<script language=javascript>alert('要访问的页面需要先登录！');</script>";
			$_SESSION['user_url'] = $_SERVER['REQUEST_URI'];
			echo '<script language=javascript>window.location.href="../Login"</script>';
		}
	}
	
	/*
	 * 添加新用户
	 */
	public function addUser() {
		if (isset($_SESSION['user_id'])) {
			$data['daily_classrooms'] = Public_methods::get_daily_classrooms();
			$data['weekly_classrooms'] = Public_methods::get_weekly_classrooms();
			$this->load->view('view_add_user', $data);
		} else {
			// 未登录的用户请先登录
			echo "<script language=javascript>alert('要访问的页面需要先登录！');</script>";
			$_SESSION['user_url'] = $_SERVER['REQUEST_URI'];
			echo '<script language=javascript>window.location.href="../Login"</script>';
		}
	}
	
	/*
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
			echo "<script language=javascript>alert('要访问的页面需要先登录！');</script>";
			$_SESSION['user_url'] = $_SERVER['REQUEST_URI'];
			echo '<script language=javascript>window.location.href="../Login"</script>';
		}
	}
	
	/*
	 * 查看常检记录
	 */
	public function dailyReview() {
		if (isset($_SESSION['user_id'])) {
			// 周一为一周的第一天
			$weekcount = Public_methods::cal_week();
			
			// 1-周一  2-周二  ... 6-周六  7-周日
			$weekday = date("w") == 0 ? 7 : date("w");
			$weekday_desc = '';
			switch ($weekday) {
				case 1: $weekday_desc = '一'; break;
				case 2: $weekday_desc = '二'; break;
				case 3: $weekday_desc = '三'; break;
				case 4: $weekday_desc = '四'; break;
				case 5: $weekday_desc = '五'; break;
				case 6: $weekday_desc = '六'; break;
				case 7: $weekday_desc = '天'; break;
			}
			
			// 已完成早检助理人数
			$m_count = 0;
			$data['m_count'] = $m_count;
			
			// 已完成午检助理人数
			$n_count = 0;
			$data['_count'] = $n_count;
			
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
			echo "<script language=javascript>alert('要访问的页面需要先登录！');</script>";
			$_SESSION['user_url'] = $_SERVER['REQUEST_URI'];
			echo '<script language=javascript>window.location.href="../Login"</script>';
		}
	}
	
	
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
<?php
/**
 * 用户模型类
 * @author Rinka
 */
class moa_user_model extends CI_Model {
 	/**
	 * 加入一个新的用户
	 * @param paras - 参数列表
	 * @return 这个用户的uid
	 */
	public function add($paras) {
		if (isset($paras)) {
			$this->db->insert('MOA_User', $paras);
			return $this->db->insert_id();
		}
		else {
			return false;
		}
	}

	/**
	 * 用id取用户信息
	 * @param id - 用户id
	 */
	public function get($id) {
		if (isset($id)) {
			$this->db->where(array('uid'=>$id));
			$res = $this->db->get('MOA_User')->result();
			return $res[0];
		}
		else {
			$res = $this->db->get('MOA_User')->result();
			return $res;
		}
		return false;
	}

	/**
	 * 删除/恢复一个用户
	 * @param id - 用户id
	 */
	public function delete($id, $isrecovere = false) {
		if(isset($id)) {
			$this->db->where(array('uid'=>$id));
			if (!$isrecovere) {
				$this->db->update('MOA_User', array('state'=>1));
			}
			else {
				$this->db->update('MOA_User', array('state'=>0));
			}
			return $this->db->affected_rows();
		}
		else {
			return false;
		}
	}

	/**
	 * 更新一个用户信息
	 * @param id - 用户id
	 * @param paras - 信息
	 */
	public function update($id, $paras) {
		if(isset($id)) {
			$this->db->where(array('uid'=>$id));
			$this->db->update('MOA_User', $paras);
			return $this->db->affected_rows();
		}
		else {
			return false;
		}
	}

    /**
	 * 取某级别所有用户
	 * @param mylevel - 级别
	 */
	public function get_by_level($mylevel) {
		if (isset($mylevel)) {
			$this->db->where(array('level'=>$mylevel));
			return $this->db->get('MOA_User')->result();
		}
		else {
			return false;
		}
	}
	
	/**
	 * 取某状态所有用户
	 * @param mystate - 状态
	 */
	public function get_by_state($mystate) {
		if (isset($mystate)) {
			$this->db->where(array('state'=>$mystate));
			return $this->db->get('MOA_User')->result();
		}
		else {
			return false;
		}
	}
	
	/**
	 * 取某级别某状态所有用户
	 * @param mylevel - 级别
	 * @param mystate - 状态
	 */
	public function get_by_level_state($mylevel, $mystate) {
		if (isset($mylevel) && isset($mystate)) {
			$this->db->where(array('level'=>$mylevel, 'state'=>$mystate));
			return $this->db->get('MOA_User')->result();
		}
		else {
			return false;
		}
	}

    /**
	 * 取指定用户名的有效记录
	 * @param username - 用户名称
	 * @param nums - 最大条目
	 * @param offset - 偏移量
	 */
	public function get_by_username($username, $nums = NULL, $offset = 0) {
		if (isset($username)) {
			$this->db->where(array('username'=>$username, 'state'=>0));
			if (!is_null($nums)) {
				$this->db->limit($nums, $offset);
			}
			return $this->db->get('MOA_User')->result();
		}
		else {
			return false;
		}
	}

	/**
	 * 返回某用户的level 
	 * @param uid - 用户id
	 * @return 用户level值
	 */
	public function get_level($uid) {
		if (isset($uid)) {
			$this->db->where(array('uid'=>$uid));
			$res = $this->db->get('MOA_User')->result();
			return $res[0]->level;
		}
		return false;
	}

	/**
	 * 给指定用户修改总工时 
	 * @param uid - 用户id
	 * @param contrib - 修改量
	 * @return 该用户最新的总工时
	 */
	public function update_contribution($uid, $contrib) {
		if (isset($uid) and isset($contrib)) {
			$sb = 'UPDATE MOA_User SET contribution = contribution + ' . $contrib . ' WHERE uid = ' . $uid;
			$affected_lines = $this->db->query($sb);
			return $affected_lines;
		}
		else {
			return false;
		}
	}

	/**
	 * 给指定用户修改总优异助理次数 
	 * @param uid - 用户id
	 * @param contrib - 修改量
	 * @return 该用户最新的总优异次数
	 */
	public function update_best($uid, $contrib = 1) {
		if (isset($uid) and isset($contrib)) {
			$sb = 'UPDATE MOA_User SET totalBest = totalBest + ' . $contrib . ' WHERE uid = ' . $uid;
			$affected_lines = $this->db->query($sb);
			return $affected_lines;
		}
		else {
			return false;
		}
	}

	/**
	 * 给指定用户修改异常助理次数 
	 * @param uid - 用户id
	 * @param contrib - 修改量
	 * @return 该用户最新的总异常次数
	 */
	public function update_bad($uid, $contrib = 1) {
		if (isset($uid) and isset($contrib)) {
			$sb = 'UPDATE MOA_User SET totalBad = totalBad + ' . $contrib . ' WHERE uid = ' . $uid;
			$affected_lines = $this->db->query($sb);
			return $affected_lines;
		}
		else {
			return false;
		}
	}

	/**
	 * 给指定用户修改抽查优秀次数 
	 * @param uid - 用户id
	 * @param contrib - 修改量
	 * @return 该用户最新的总抽查优秀次数
	 */
	public function update_perfect($uid, $contrib = 1) {
		if (isset($uid) and isset($contrib)) {
			$sb = 'UPDATE MOA_User SET totalPerfect = totalPerfect + ' . $contrib . ' WHERE uid = ' . $uid;
			$affected_lines = $this->db->query($sb);
			return $affected_lines;
		}
		else {
			return false;
		}
	}

	/**
	 * 给指定用户修改总旷工次数 
	 * @param uid - 用户id
	 * @param contrib - 修改量
	 * @return 该用户最新的总旷工次数
	 */
	public function update_absence($uid, $contrib = 1) {
		if (isset($uid) and isset($contrib)) {
			$sb = 'UPDATE MOA_User SET totalAbsence = totalAbsence + ' . $contrib . ' WHERE uid = ' . $uid;
			$affected_lines = $this->db->query($sb);
			return $affected_lines;
		}
		else {
			return false;
		}
	}

	/**
	 * 给指定用户修改总请假次数 
	 * @param uid - 用户id
	 * @param contrib - 修改量
	 * @return 该用户最新的总请假次数
	 */
	public function update_leave($uid, $contrib = 1) {
		if (isset($uid) and isset($contrib)) {
			$sb = 'UPDATE MOA_User SET totalLeave = totalLeave + ' . $contrib . ' WHERE uid = ' . $uid;
			$affected_lines = $this->db->query($sb);
			return $affected_lines;
		}
		else {
			return false;
		}
	}

    /**
	 * 给指定用户修改总被抽查次数 
	 * @param uid - 用户id
	 * @param contrib - 修改量
	 * @return 该用户最新的总被抽查次数
	 */
	public function update_check($uid, $contrib = 1) {
		if (isset($uid) and isset($contrib)) {
			$sb = 'UPDATE MOA_User SET totalCheck = totalCheck + ' . $contrib . ' WHERE uid = ' . $uid;
			$affected_lines = $this->db->query($sb);
			return $affected_lines;
		}
		else {
			return false;
		}
	}
	
	
	/**
	 * 登录验证
	 * @param username - 用户名
	 * @param password - 哈希后的密码
	 * @return 登录成功与否
	 */
	public function login_check($username, $password) {
		if (isset($username) and isset($password)) {
			$sb = 'SELECT username, password FROM MOA_User WHERE username = "' . $username . '" AND password = "' . $password . '"';
			$sqlquery = $this->db->query($sb);
			$dataarr = $sqlquery->result();
			if (count($dataarr) == 0) {
				return false;
			}
			else {
				return true;
			}
		}
	}
	
	/**
	 * 获取用户id
	 * @param username - 用户名
	 * @param password - 哈希后的密码
	 * @return 用户id
	 */
	public function get_uid($username, $password) {
		if (isset($username) and isset($password)) {
			$sb = 'SELECT uid FROM MOA_User WHERE username = "' . $username . '" AND password = "' . $password . '"';
			$sqlquery = $this->db->query($sb);
			$dataarr = $sqlquery->result();
			return $dataarr[0]->uid;
		}
		else {
			return false;
		}
	}

}
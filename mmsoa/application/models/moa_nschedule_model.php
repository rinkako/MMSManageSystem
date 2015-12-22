<?php
/**
 * 空余时间表类
 * @author Rinka
 */
class moa_nschedule_model extends CI_Model {
 	/**
	 * 加入一个新空余时间记录
	 * @param paras - 参数列表
	 * @return 这个记录的id
	 */
	public function add($paras) {
		if (isset($paras)) {
			$this->db->insert('MOA_nschedule', $paras);
			return $this->db->insert_id();
		}
		else {
			return false;
		}
	}

	/**
	 * 取空余时间表
	 * @param id - 空余时间段id
	 */
	public function get($id) {
		if (isset($id)) {
			$this->db->where(array('logid'=>$id));
			return $this->db->get('MOA_nschedule')->result();
		}
		else {
			return false;
		}
	}

	/**
	 * 获得某个时间段的空余记录
	 * @param id - 空余时间段id
	 */
	public function get_by_period($period) {
		if (isset($id)) {
			$sb = 'SELECT * FROM MOA_nschedule WHERE CHARINDEX(\'' . $period . '\', period) > 0';
			$sqlquery = $this->db->query($sb);
			return $sqlquery->result();
		}
		else {
			return false;
		}
	}
    
	/**
	 * 删除空余时间表记录
	 * @param id - 空余时间段id
	 */
	public function delete($id) {
		if (isset($id)) {
			$sb = 'DELETE FROM MOA_nschedule WHERE nsid = ' . $id;
			$sqlquery = $this->db->query($sb);
			return $this->db->affected_rows();
		}
		else {
			return false;
		}
	}

	/**
	 * 清空空余时间表
	 */
	public function clear() {
		$sb = 'DELETE FROM MOA_nschedule';
		$sqlquery = $this->db->query($sb);
		return $this->db->affected_rows();
	}
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function check_login($username, $password) {
        // 1. ดึงข้อมูล User และ JOIN ข้อมูล Company (ใช้ Left Join เพราะ Freelancer อาจไม่มี Company)
        $this->db->select('u.*, c.name as company_name, c.status as company_status, c.subscription_expires_at as company_expire');
        $this->db->from('planner_users u');
        $this->db->join('planner_companies c', 'u.company_id = c.id', 'left');
        
        // รองรับการ Login ทั้งแบบใส่ Username หรือ Email
        $this->db->group_start();
        $this->db->where('u.username', $username);
        $this->db->or_where('u.email', $username);
        $this->db->group_end();
        
        $query = $this->db->get();

        // 2. ถ้าเจอผู้ใช้งาน
        if ($query->num_rows() == 1) {
            $user = $query->row();
            
            // ตรวจสอบรหัสผ่าน (หมายเหตุ: ตอนนี้ใช้ == ก่อนเพื่อให้เทสต์กับ Dummy Data ได้ แต่ระบบจริงควรใช้ password_verify)
            if ($password === $user->password) {
                
                // ตรวจสอบสถานะการระงับบัญชี (is_active)
                if ($user->is_active == 0) {
                    return ['status' => false, 'message' => 'บัญชีของคุณถูกระงับการใช้งาน'];
                }

                // ถ้าเป็น User ของ Company ให้เช็คสถานะบริษัทด้วย
                if ($user->role == 'company_admin' || ($user->role == 'freelancer' && $user->company_id != NULL)) {
                    if ($user->company_status == 'pending') {
                        return ['status' => false, 'message' => 'องค์กรของคุณอยู่ระหว่างรอการอนุมัติ'];
                    }
                    if ($user->company_status == 'banned' || $user->company_status == 'rejected') {
                        return ['status' => false, 'message' => 'องค์กรของคุณไม่สามารถเข้าสู่ระบบได้'];
                    }
                }

                // หากผ่านเงื่อนไขทั้งหมด คืนค่าข้อมูล User กลับไป
                return ['status' => true, 'data' => $user];
            }
        }
        
        return ['status' => false, 'message' => 'ชื่อผู้ใช้งาน หรือรหัสผ่านไม่ถูกต้อง'];
    }
}
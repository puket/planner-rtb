<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->library('session'); // เรียกใช้ Session ของ CI3
    }

	// เพิ่มฟังก์ชันนี้เข้าไปในคลาส Auth
    public function index() {
        // ตรวจสอบว่าถ้า Login อยู่แล้ว ให้เด้งไปหน้า Dashboard เลย จะได้ไม่ต้อง Login ซ้ำ
        if ($this->session->userdata('logged_in')) {
            redirect(base_url('dashboard'));
        }
        
        // ถ้ายังไม่ Login ให้โหลดไฟล์ View ชื่อ login_view.php
        $this->load->view('login_view');
    }

	
    // API สำหรับตรวจสอบ Login
    public function process_login() {
        // บังคับให้ส่งออกเป็น JSON (สำคัญมากสำหรับการคุยกับ Vue.js)
        header('Content-Type: application/json');

        // รับค่า JSON payload ที่ส่งมาจาก Vue.js (Axios / Fetch API)
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        // ตรวจสอบว่ามีการส่งข้อมูลมาครบไหม
        if (empty($request->username) || empty($request->password)) {
            echo json_encode(['success' => false, 'message' => 'กรุณากรอก Username และ Password']);
            return;
        }

        $username = $request->username;
        $password = $request->password;

        // เรียกใช้ Model
        $login_result = $this->Auth_model->check_login($username, $password);

        if ($login_result['status'] === true) {
            $user_data = $login_result['data'];

            // ลบ password ออกก่อนเก็บลง Session เพื่อความปลอดภัย
            unset($user_data->password); 

            // บันทึกข้อมูลลง Session
            $this->session->set_userdata('logged_in', true);
            $this->session->set_userdata('user_info', $user_data);

            echo json_encode([
                'success' => true, 
                'message' => 'เข้าสู่ระบบสำเร็จ',
                'redirect_url' => base_url('dashboard'), // กำหนด URL ที่จะให้ Vue Redirect ไป
                'user' => $user_data
            ]);
        } else {
            // กรณี Login ไม่ผ่าน
            echo json_encode(['success' => false, 'message' => $login_result['message']]);
        }
    }

    // API สำหรับ Logout
    public function logout() {
        $this->session->sess_destroy();
        redirect(base_url('login')); // เด้งกลับหน้า Login
    }
}
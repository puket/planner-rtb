<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - Project Management System</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/dist/css/bootstrap.min.css') ?>">
    <style>
        body { 
            background-color: #f4f6f9; 
            display: flex; 
            align-items: center; 
            justify-content: center;
            height: 100vh; 
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

    <div id="app" class="container d-flex justify-content-center">
        <div class="card login-card p-4">
            <h3 class="text-center font-weight-bold mb-4">เข้าสู่ระบบ</h3>

            <div v-if="errorMessage" class="alert alert-danger text-center" role="alert">
                {{ errorMessage }}
            </div>

            <form @submit.prevent="submitLogin">
                <div class="form-group">
                    <label>ชื่อผู้ใช้งาน หรือ อีเมล</label>
                    <input type="text" class="form-control" v-model="form.username" required placeholder="กรอก Username / Email">
                </div>
                
                <div class="form-group">
                    <label>รหัสผ่าน</label>
                    <input type="password" class="form-control" v-model="form.password" required placeholder="กรอกรหัสผ่าน">
                </div>

                <button type="submit" class="btn btn-primary btn-block mt-4" :disabled="isLoading">
                    <span v-if="isLoading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span v-else>เข้าสู่ระบบ</span>
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        new Vue({
            el: '#app',
            data: {
                // ตัวแปรเก็บค่าฟอร์ม ที่ถูกผูก (Binding) ไว้กับ input ด้วย v-model
                form: {
                    username: '',
                    password: ''
                },
                isLoading: false,   // สถานะปุ่มโหลด
                errorMessage: ''    // ข้อความแจ้งเตือน Error
            },
            methods: {
                submitLogin() {
                    this.isLoading = true;
                    this.errorMessage = '';

                    // URL ชี้ไปยัง Controller API ที่เราเขียนไว้
                    const apiUrl = '<?= base_url("auth/process_login") ?>';

                    // ส่งข้อมูลแบบ POST (JSON)
                    axios.post(apiUrl, this.form)
                        .then(response => {
                            if (response.data.success) {
                                // หาก Login ผ่าน ให้ Redirect หน้าไปยัง Dashboard
                                window.location.href = response.data.redirect_url;
                            } else {
                                // หาก Login ไม่ผ่าน ให้แสดงข้อความแจ้งเตือนจาก Backend
                                this.errorMessage = response.data.message;
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            this.errorMessage = 'เกิดข้อผิดพลาด ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้';
                        })
                        .finally(() => {
                            this.isLoading = false; // หยุดวงกลมหมุนที่ปุ่ม
                        });
                }
            }
        });
    </script>
</body>
</html>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['dsn']      The full DSN string describe a connection to the database.
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database driver. e.g.: mysqli.
|			Currently supported:
|				 cubrid, ibase, mssql, mysql, mysqli, oci8,
|				 odbc, pdo, postgre, sqlite, sqlite3, sqlsrv
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Query Builder class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['encrypt']  Whether or not to use an encrypted connection.
|
|			'mysql' (deprecated), 'sqlsrv' and 'pdo/sqlsrv' drivers accept TRUE/FALSE
|			'mysqli' and 'pdo/mysql' drivers accept an array with the following options:
|
|				'ssl_key'    - Path to the private key file
|				'ssl_cert'   - Path to the public key certificate file
|				'ssl_ca'     - Path to the certificate authority file
|				'ssl_capath' - Path to a directory containing trusted CA certificats in PEM format
|				'ssl_cipher' - List of *allowed* ciphers to be used for the encryption, separated by colons (':')
|				'ssl_verify' - TRUE/FALSE; Whether verify the server certificate or not ('mysqli' only)
|
|	['compress'] Whether or not to use client compression (MySQL only)
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|	['ssl_options']	Used to set various SSL options that can be used when making SSL connections.
|	['failover'] array - A array with 0 or more data for connections if the main should fail.
|	['save_queries'] TRUE/FALSE - Whether to "save" all executed queries.
| 				NOTE: Disabling this will also effectively disable both
| 				$this->db->last_query() and profiling of DB queries.
| 				When you run a query, with this setting set to TRUE (default),
| 				CodeIgniter will store the SQL statement for debugging purposes.
| 				However, this may cause high memory usage, especially if you run
| 				a lot of SQL queries ... disable this to avoid that problem.
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $query_builder variables lets you determine whether or not to load
| the query builder class.
*/
$active_group = 'default';
$query_builder = TRUE;

// mysql -u ake -p app98120

$db['default'] = array(
        'dsn'   => '',
        'hostname' => '150.95.27.54',
        'database' => 'rotibit',
        'username' => 'ake',
        'password' => 'app98120',
        'dbdriver' => 'mysqli',
        'dbprefix' => 'planner_',
        'pconnect' => FALSE,
        'db_debug' => (ENVIRONMENT !== 'production'),
        'cache_on' => FALSE,
        'cachedir' => '/home/rtbftp/rotibit_com/www/app_pk/cache',
        'char_set' => 'utf8',
        'dbcollat' => 'utf8_general_ci',
        'swap_pre' => '',
        'encrypt' => FALSE,
        'compress' => FALSE,
        'stricton' => FALSE,
        'failover' => array(),
        'save_queries' => TRUE
);
/*
-- 1. สร้างตารางองค์กร/บริษัท (Companies)
CREATE TABLE `planner_companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `tax_id` varchar(20) DEFAULT NULL,
  `contact_email` varchar(100) NOT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `status` enum('pending','approved','rejected','banned') DEFAULT 'pending',
  `plan_name` varchar(50) DEFAULT 'Free', 
  `subscription_expires_at` date DEFAULT NULL, -- วันหมดอายุการใช้งาน
  `last_payment_date` date DEFAULT NULL, -- วันที่ชำระเงินล่าสุด
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. สร้างตารางผู้ใช้งาน (Users / Freelancers)
-- ** เอา company_id ออกเพื่อให้ User เป็นอิสระรับงานได้หลายที่ **
CREATE TABLE `planner_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('super_admin','company_admin','freelancer') NOT NULL DEFAULT 'freelancer',
  `is_active` tinyint(1) DEFAULT 1,
  `plan_name` varchar(50) DEFAULT 'Free', 
  `subscription_expires_at` date DEFAULT NULL, 
  `last_payment_date` date DEFAULT NULL, 
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. สร้างตารางประวัติการชำระเงิน / การต่ออายุ (Payment Logs)
-- ** ใช้ตารางเดียวเก็บได้ทั้งประวัติของ Company และ Freelancer **
CREATE TABLE `planner_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payer_type` enum('company','user') NOT NULL, -- ระบุว่าใครเป็นคนจ่าย
  `payer_id` int(11) NOT NULL, -- ไอดีของ company หรือ ไอดีของ user
  `amount` decimal(10,2) NOT NULL, -- จำนวนเงิน
  `payment_method` varchar(50) DEFAULT NULL, -- ช่องทางชำระเงิน (เช่น Credit Card, PromptPay)
  `transaction_ref` varchar(100) DEFAULT NULL, -- รหัสอ้างอิงใบเสร็จ
  `plan_name` varchar(50) NOT NULL,
  `start_date` date NOT NULL, -- วันที่เริ่มแพ็กเกจ
  `end_date` date NOT NULL, -- วันที่หมดอายุ
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. สร้างตารางโปรเจกต์ (Projects)
CREATE TABLE `planner_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','in_progress','completed','on_hold') DEFAULT 'pending',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_by` int(11) NOT NULL, -- User/Company Admin ที่เป็นคนสร้าง
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`company_id`) REFERENCES `planner_companies`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`created_by`) REFERENCES `planner_users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. สร้างตารางเชิญสมาชิกเข้าโปรเจกต์ (Project Members)
-- ** เชื่อม Company -> Project -> Freelancer **
CREATE TABLE `planner_project_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL, -- Freelancer ที่ถูกเชิญ
  `status` enum('invited','joined','declined') DEFAULT 'invited', -- สถานะการตอบรับคำเชิญ
  `joined_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`company_id`) REFERENCES `planner_companies`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`project_id`) REFERENCES `planner_projects`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `planner_users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. สร้างตารางงานย่อย (Tasks)
CREATE TABLE `planner_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('todo','doing','done') DEFAULT 'todo',
  `assigned_to` int(11) DEFAULT NULL, -- มอบหมายให้ Freelancer คนไหนทำ
  `due_date` date DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`project_id`) REFERENCES `planner_projects`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`assigned_to`) REFERENCES `planner_users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`created_by`) REFERENCES `planner_users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ==========================================
-- ข้อมูลตัวอย่าง (Dummy Data) สำหรับระบบ SaaS
-- ==========================================

-- 1. เพิ่มข้อมูลองค์กร/บริษัท (Companies)
INSERT INTO `planner_companies` (`id`, `name`, `tax_id`, `contact_email`, `contact_phone`, `status`, `plan_name`, `subscription_expires_at`, `last_payment_date`) VALUES 
(1, 'บริษัท อัลฟ่า เทค จำกัด', '0105555555551', 'admin@alphatech.com', '02-111-1111', 'approved', 'Pro', '2025-12-31', '2024-01-01'),
(2, 'บริษัท เบต้า สตูดิโอ', '0105555555552', 'hello@betastudio.com', '02-222-2222', 'pending', 'Free', NULL, NULL);

-- 2. เพิ่มข้อมูลผู้ใช้งานระบบ (Users / Freelancers)
-- รหัสผ่านสมมติคือ '123456' (ในระบบจริงควรใช้ password_hash)
INSERT INTO `planner_users` (`id`, `username`, `password`, `email`, `role`, `is_active`, `plan_name`, `subscription_expires_at`, `last_payment_date`) VALUES 
(1, 'superadmin', '123456', 'super@rotibit.com', 'super_admin', 1, 'Free', NULL, NULL),
(2, 'alpha_admin', '123456', 'admin@alphatech.com', 'company_admin', 1, 'Free', NULL, NULL),
(3, 'beta_admin', '123456', 'hello@betastudio.com', 'company_admin', 1, 'Free', NULL, NULL),
(4, 'freelance_john', '123456', 'john.dev@email.com', 'freelancer', 1, 'Premium', '2025-06-30', '2024-06-01'),
(5, 'freelance_jane', '123456', 'jane.design@email.com', 'freelancer', 1, 'Free', NULL, NULL);

-- 3. เพิ่มประวัติการชำระเงิน (Payment Logs)
-- สมมติว่า Company 1 จ่ายค่าแพ็กเกจรายปี และ Freelancer (John) จ่ายค่าแพ็กเกจโปรไฟล์
INSERT INTO `planner_payments` (`payer_type`, `payer_id`, `amount`, `payment_method`, `transaction_ref`, `plan_name`, `start_date`, `end_date`) VALUES 
('company', 1, 15000.00, 'Credit Card', 'TXN-COMP-001', 'Pro Plan', '2024-01-01', '2025-12-31'),
('user', 4, 1500.00, 'PromptPay', 'TXN-USER-001', 'Premium', '2024-06-01', '2025-06-30');

-- 4. เพิ่มข้อมูลโปรเจกต์ (Projects)
-- ให้ Company 1 (อัลฟ่า เทค) สร้าง 2 โปรเจกต์ โดยมี alpha_admin (id=2) เป็นคนสร้าง
INSERT INTO `planner_projects` (`id`, `company_id`, `name`, `description`, `status`, `start_date`, `end_date`, `created_by`) VALUES 
(1, 1, 'พัฒนาแอปพลิเคชัน E-Commerce', 'แอปสำหรับขายของออนไลน์', 'in_progress', '2024-01-15', '2024-08-30', 2),
(2, 1, 'ออกแบบโลโก้และ CI', 'รีแบรนด์สินค้าใหม่', 'pending', '2024-05-01', '2024-05-31', 2);

-- 5. เพิ่มประวัติการเชิญ Freelancer เข้าโปรเจกต์ (Project Members)
-- Company 1 เชิญ John และ Jane เข้าโปรเจกต์ที่ 1
INSERT INTO `planner_project_members` (`company_id`, `project_id`, `user_id`, `status`, `joined_at`) VALUES 
(1, 1, 4, 'joined', '2024-01-16 10:00:00'), -- John กดตอบรับแล้ว
(1, 1, 5, 'invited', NULL),                   -- Jane ยังไม่ได้กดตอบรับ
(1, 2, 5, 'joined', '2024-05-02 09:30:00');  -- Jane รับงานออกแบบโลโก้

-- 6. เพิ่มข้อมูลงานย่อย (Tasks)
INSERT INTO `planner_tasks` (`project_id`, `title`, `description`, `status`, `assigned_to`, `due_date`, `created_by`) VALUES 
(1, 'เขียน API ระบบตะกร้าสินค้า', 'ใช้ Node.js และ MongoDB', 'doing', 4, '2024-02-28', 2),
(1, 'ทำหน้า UI ตะกร้าสินค้า', 'ใช้ Vue.js ตาม Design', 'todo', 4, '2024-03-15', 2),
(2, 'ร่างแบบโลโก้ 3 แบบ', 'ส่งดราฟต์แรกให้ลูกค้าเลือก', 'done', 5, '2024-05-10', 2);

 */
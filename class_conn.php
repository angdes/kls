<?php
date_default_timezone_set('Asia/Bangkok');

class class_conn {
    // ตั้งค่าฐานข้อมูล
    public $db_server = "localhost";
    public $db_username = "root";
    public $db_password = "";
    public $db_database = "myproject";
    public $con;

    // ฟังก์ชันในการเชื่อมต่อฐานข้อมูล
    public function connect() {
        $this->con = mysqli_connect($this->db_server, $this->db_username, $this->db_password, $this->db_database);
        mysqli_set_charset($this->con, "utf8"); // ตั้งค่าภาษาเป็น utf8
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    }

    // ฟังก์ชันในการปิดการเชื่อมต่อฐานข้อมูล
    public function close() {
        mysqli_close($this->con);
    }

    // ฟังก์ชันในการดึงข้อมูล
    public function select_base($sql) {
        $this->connect(); // เรียกใช้ฟังก์ชัน connect
        $result = mysqli_query($this->con, $sql);
        $this->close(); // เรียกใช้ฟังก์ชัน close
        return $result; 
    }

    // ฟังก์ชันในการเพิ่ม/ลบ/แก้ไขข้อมูล
    public function write_base ($sql) {
        $this->connect(); // เรียกใช้ฟังก์ชัน connect
        $result = mysqli_query($this->con, $sql);
        $this->close(); // เรียกใช้ฟังก์ชัน close
        return $result; 
    }

    // ฟังก์ชันในการนับจำนวนแถว
    public function select_numrows($sql) {
        $this->connect(); // เรียกใช้ฟังก์ชัน connect
        $result = mysqli_query($this->con, $sql);
        $rowcount = mysqli_num_rows($result);
        $this->close(); // เรียกใช้ฟังก์ชัน close
        return $rowcount;
    }

    // ฟังก์ชันแสดงข้อความ
    public function show_message($word) {
        return "<script type='text/javascript'>alert('$word');</script>";
    }

    // ฟังก์ชันในการลิงค์ไปหน้าอื่น
    public function goto_page($speed, $url) {
        return "<meta http-equiv='refresh' content='$speed;$url' />";
    }

    // ฟังก์ชันในการดึงข้อมูลและวนลูปอ่านข้อมูลที่ดึงมา
    public function fetch_data($sql) {
        $this->connect(); // เรียกใช้ฟังก์ชัน connect
        $result = mysqli_query($this->con, $sql);

        if (mysqli_num_rows($result) > 0) {
            // มีข้อมูลที่ดึงมา
            while ($row = mysqli_fetch_assoc($result)) {
                // ประมวลผลข้อมูลในแต่ละแถว
                print_r($row); // หรือทำอย่างอื่นกับข้อมูลที่ดึงมา
            }
        } else {
            // ไม่มีข้อมูลที่ดึงมา
            echo "No data found.";
        }

        $this->close(); // เรียกใช้ฟังก์ชัน close
    }
}

// Check connection

function conndb() 
    {
        global $conn;
        global $host;
        global $user;
        global $pass;
        global $dbname;
        $conn = mysqli_connect($host,$user,$pass);

        mysqli_select_db($conn,$dbname);
        mysqli_query($conn,"SET NAMES utf8");

    }

function closedb() 
    {
        global $conn;
        mysqli_close($conn);
    }
?>

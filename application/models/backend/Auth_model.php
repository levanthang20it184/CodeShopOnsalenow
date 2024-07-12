<?php defined('BASEPATH') or exit('No direct script access allowed');


class Auth_model extends CI_Model
{


    public function login($data)
    {

        $this->db->from('ci_admin');

        $this->db->where('username', $data['username']);

        $query = $this->db->get();

        // echo $this->db->last_query(); die;
        // dd($this->db->last_query());
        if ($query->num_rows() == 0) {

            return false;

        } else {

            //Compare the password attempt with the password we have stored.

            $result = $query->row_array();

            $validPassword = password_verify($data['password'], $result['password']);

            if ($validPassword) {

                return $result = $query->row_array();

            }

        }

    }
    public function saveAuthCode($admin_id, $auth_code) {
        // Cập nhật hoặc thêm mới mã xác thực cho người dùng có ID là $user_id
        $data = array(
            'auth_code' => $auth_code
        );
        $this->db->where('admin_id', $admin_id);
        $this->db->update('ci_admin', $data);
        
        // Kiểm tra xem câu lệnh SQL đã được thực thi thành công hay không
        return $this->db->affected_rows() > 0;
    }


}

?>
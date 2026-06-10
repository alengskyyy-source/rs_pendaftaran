<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_auth extends CI_Model
{
    public function get_user_by_username($username)
    {
        return $this->db
            ->where('username', $username)
            ->get('users')
            ->row();
    }

    public function register_pasien($data)
    {
        $this->db->trans_begin();

        $data_user = array(
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role'     => 'pasien'
        );

        $this->db->insert('users', $data_user);
        $id_user = $this->db->insert_id();

        $data_pasien = array(
            'id_user'      => $id_user,
            'nama_pasien'  => $data['nama_pasien'],
            'tgl_lahir'    => $data['tgl_lahir'],
            'alamat'       => $data['alamat'],
            'no_telp'      => $data['no_telp']
        );

        $this->db->insert('pasien', $data_pasien);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }

        $this->db->trans_commit();
        return TRUE;
    }
}

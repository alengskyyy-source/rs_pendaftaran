<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_admin extends CI_Model
{
    public function get_all_pendaftaran()
    {
        $this->db->select('
            pendaftaran.*,
            pasien.nama_pasien,
            pasien.tgl_lahir,
            pasien.alamat,
            pasien.no_telp,
            dokter.nama_dokter,
            dokter.spesialis
        ');
        $this->db->from('pendaftaran');
        $this->db->join('pasien', 'pasien.id_pasien = pendaftaran.id_pasien');
        $this->db->join('dokter', 'dokter.id_dokter = pendaftaran.id_dokter');
        $this->db->order_by('pendaftaran.tgl_kunjungan', 'ASC');
        $this->db->order_by('pendaftaran.jam_kunjungan', 'ASC');

        return $this->db->get()->result();
    }

    public function get_statistik()
    {
        $this->db->select("
            COUNT(*) AS total_pendaftar,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending,
            SUM(CASE WHEN status = 'disetujui' THEN 1 ELSE 0 END) AS diterima,
            SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) AS ditolak
        ", FALSE);

        return $this->db->get('pendaftaran')->row();
    }

    public function update_status($id_daftar, $status)
    {
        $data = array(
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s')
        );

        return $this->db
            ->where('id_daftar', $id_daftar)
            ->update('pendaftaran', $data);
    }

    public function get_all_pasien()
    {
        $this->db->select('pasien.*, users.username, users.id_user');
        $this->db->from('pasien');
        $this->db->join('users', 'users.id_user = pasien.id_user');
        $this->db->order_by('pasien.id_pasien', 'DESC');

        return $this->db->get()->result();
    }

    public function get_pasien_by_id($id_pasien)
    {
        $this->db->select('pasien.*, users.username, users.id_user');
        $this->db->from('pasien');
        $this->db->join('users', 'users.id_user = pasien.id_user');
        $this->db->where('pasien.id_pasien', $id_pasien);

        return $this->db->get()->row();
    }

    public function username_exists_except($username, $id_user)
    {
        $this->db->where('username', $username);
        $this->db->where('id_user !=', $id_user);

        return $this->db->get('users')->num_rows() > 0;
    }

    public function insert_pasien($data)
    {
        $this->db->where('username', $data['username']);
        $check_user = $this->db->get('users');
        
        if ($check_user->num_rows() > 0) {
            return FALSE;
        }
        
        $this->db->trans_begin();

        $data_user = array(
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role'     => 'pasien',
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('users', $data_user);
        $id_user = $this->db->insert_id();

        $data_pasien = array(
            'id_user'      => $id_user,
            'nama_pasien'  => $data['nama_pasien'],
            'tgl_lahir'    => $data['tgl_lahir'],
            'alamat'       => $data['alamat'],
            'no_telp'      => $data['no_telp'],
            'created_at'   => date('Y-m-d H:i:s')
        );

        $this->db->insert('pasien', $data_pasien);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }

        $this->db->trans_commit();
        return TRUE;
    }

    public function update_pasien($id_pasien, $data)
    {
        $this->db->where('username', $data['username']);
        $this->db->where('id_user !=', $data['id_user']);
        $check_user = $this->db->get('users');
        
        if ($check_user->num_rows() > 0) {
            return FALSE;
        }
        
        $this->db->trans_begin();

        $data_user = array('username' => $data['username']);

        if (!empty($data['password'])) {
            $data_user['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $this->db
            ->where('id_user', $data['id_user'])
            ->update('users', $data_user);

        $data_pasien = array(
            'nama_pasien' => $data['nama_pasien'],
            'tgl_lahir'   => $data['tgl_lahir'],
            'alamat'      => $data['alamat'],
            'no_telp'     => $data['no_telp']
        );

        $this->db
            ->where('id_pasien', $id_pasien)
            ->update('pasien', $data_pasien);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }

        $this->db->trans_commit();
        return TRUE;
    }

    public function count_pendaftaran_by_pasien($id_pasien)
    {
        return $this->db
            ->where('id_pasien', $id_pasien)
            ->get('pendaftaran')
            ->num_rows();
    }

    public function delete_pasien($id_pasien, $id_user)
    {
        $this->db->trans_begin();

        $this->db
            ->where('id_pasien', $id_pasien)
            ->delete('pasien');

        $this->db
            ->where('id_user', $id_user)
            ->delete('users');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }

        $this->db->trans_commit();
        return TRUE;
    }
}
?>
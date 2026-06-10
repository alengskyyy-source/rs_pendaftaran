<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_pasien extends CI_Model
{
    public function get_profile_by_user($id_user)
    {
        return $this->db
            ->where('id_user', $id_user)
            ->get('pasien')
            ->row();
    }

    public function update_profile($id_pasien, $data)
    {
        return $this->db
            ->where('id_pasien', $id_pasien)
            ->update('pasien', $data);
    }

    public function get_all_dokter()
    {
        return $this->db
            ->order_by('spesialis', 'ASC')
            ->get('dokter')
            ->result();
    }

    public function insert_pendaftaran($data)
    {
        return $this->db->insert('pendaftaran', $data);
    }

    public function get_pendaftaran_by_pasien($id_pasien)
    {
        $this->db->select('pendaftaran.*, dokter.nama_dokter, dokter.spesialis');
        $this->db->from('pendaftaran');
        $this->db->join('dokter', 'dokter.id_dokter = pendaftaran.id_dokter');
        $this->db->where('pendaftaran.id_pasien', $id_pasien);
        $this->db->order_by('pendaftaran.created_at', 'DESC');

        return $this->db->get()->result();
    }
}

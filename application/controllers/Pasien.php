<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pasien extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_pasien');

        if ($this->session->userdata('logged_in') !== TRUE) {
            redirect('auth/login');
        }

        if ($this->session->userdata('role') != 'pasien') {
            redirect('admin');
        }
    }

    public function index()
    {
        $id_user = $this->session->userdata('id_user');
        $data['pasien'] = $this->M_pasien->get_profile_by_user($id_user);

        if (!$data['pasien']) {
            $this->session->set_flashdata('error', 'Data pasien tidak ditemukan.');
            redirect('auth/logout');
        }

        $data['pendaftaran'] = $this->M_pasien->get_pendaftaran_by_pasien($data['pasien']->id_pasien);
        $data['active_menu'] = 'dashboard';
        $data['page_title'] = 'Dashboard Pasien';
        $data['icon'] = 'home';
        $this->load->view('v_dashboard_pasien', $data);
    }

    public function daftar()
    {
        $id_user = $this->session->userdata('id_user');
        $data['pasien'] = $this->M_pasien->get_profile_by_user($id_user);
        $data['dokter'] = $this->M_pasien->get_all_dokter();

        if (!$data['pasien']) {
            $this->session->set_flashdata('error', 'Data pasien tidak ditemukan.');
            redirect('auth/logout');
        }

        $this->form_validation->set_rules('nama_pasien', 'Nama Pasien', 'required|trim');
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required|trim');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
        $this->form_validation->set_rules('no_telp', 'No. Telepon', 'required|trim|regex_match[/^[0-9]{10,13}$/]');
        $this->form_validation->set_rules('keluhan', 'Keluhan', 'required|trim|min_length[5]');
        $this->form_validation->set_rules('tgl_kunjungan', 'Tanggal Kunjungan', 'required|trim');
        $this->form_validation->set_rules('jam_kunjungan', 'Jam Kunjungan', 'required|trim');
        $this->form_validation->set_rules('id_dokter', 'Dokter Spesialis', 'required|trim');
        
        $this->form_validation->set_message('required', '{field} wajib diisi.');
        $this->form_validation->set_message('regex_match', 'Format No. Telepon tidak valid (10-13 digit).');
        $this->form_validation->set_message('min_length', '{field} minimal {param} karakter.');

        if ($this->form_validation->run() == FALSE) {
            $data['active_menu'] = 'daftar';
            $data['page_title'] = 'Form Pendaftaran';
            $data['icon'] = 'edit';
            $this->load->view('v_form_daftar', $data);
        } else {
            $data_update_pasien = array(
                'nama_pasien' => $this->input->post('nama_pasien', TRUE),
                'tgl_lahir'   => $this->input->post('tgl_lahir', TRUE),
                'alamat'      => $this->input->post('alamat', TRUE),
                'no_telp'     => $this->input->post('no_telp', TRUE)
            );

            $this->M_pasien->update_profile($data['pasien']->id_pasien, $data_update_pasien);

            $data_daftar = array(
                'id_pasien'     => $data['pasien']->id_pasien,
                'id_dokter'     => $this->input->post('id_dokter', TRUE),
                'keluhan'       => $this->input->post('keluhan', TRUE),
                'tgl_kunjungan' => $this->input->post('tgl_kunjungan', TRUE),
                'jam_kunjungan' => $this->input->post('jam_kunjungan', TRUE),
                'status'        => 'pending'
            );

            if ($this->M_pasien->insert_pendaftaran($data_daftar)) {
                $this->session->set_flashdata('success', 'Pendaftaran berhasil dikirim. Status saat ini Pending.');
                redirect('pasien');
            }

            $this->session->set_flashdata('error', 'Pendaftaran gagal disimpan.');
            redirect('pasien/daftar');
        }
    }
}
?>
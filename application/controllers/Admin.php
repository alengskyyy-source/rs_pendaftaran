<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_admin');

        if ($this->session->userdata('logged_in') !== TRUE) {
            redirect('auth/login');
        }

        if ($this->session->userdata('role') != 'admin') {
            redirect('pasien');
        }
    }

    public function index()
    {
        $data['pendaftaran'] = $this->M_admin->get_all_pendaftaran();
        $data['statistik'] = $this->M_admin->get_statistik();
        $data['active_menu'] = 'dashboard';
        $data['page_title'] = 'Dashboard Admin';
        $data['icon'] = 'tachometer-alt';
        $this->load->view('v_dashboard_admin', $data);
    }

    public function update_status($id_daftar = NULL, $status = NULL)
    {
        if ($id_daftar == NULL || $status == NULL) {
            show_404();
        }

        $allowed_status = array('pending', 'disetujui', 'ditolak');
        if (!in_array($status, $allowed_status)) {
            show_404();
        }

        if ($this->M_admin->update_status($id_daftar, $status)) {
            $this->session->set_flashdata('success', 'Status pendaftaran berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Status pendaftaran gagal diperbarui.');
        }

        redirect('admin');
    }

    public function export_csv()
    {
        $pendaftaran = $this->M_admin->get_all_pendaftaran();
        $filename = 'laporan_pendaftaran_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo "\xEF\xBB\xBF";

        $output = fopen('php://output', 'w');
        fputcsv($output, array(
            'No', 'Nama Pasien', 'Tanggal Lahir', 'Alamat', 'No Telepon',
            'Dokter', 'Spesialis', 'Keluhan', 'Tanggal Kunjungan',
            'Jam Kunjungan', 'Status', 'Tanggal Daftar'
        ));

        $no = 1;
        foreach ($pendaftaran as $row) {
            fputcsv($output, array(
                $no++,
                $row->nama_pasien,
                $row->tgl_lahir,
                $row->alamat,
                $row->no_telp,
                $row->nama_dokter,
                $row->spesialis,
                $row->keluhan,
                $row->tgl_kunjungan,
                $row->jam_kunjungan,
                $row->status,
                $row->created_at
            ));
        }

        fclose($output);
        exit;
    }

    public function export_pdf()
    {
        $pendaftaran = $this->M_admin->get_all_pendaftaran();
        $statistik = $this->M_admin->get_statistik();
        
        // Load library PDF sederhana (menggunakan HTML2PDF atau DOMPDF)
        // Untuk kemudahan, kita buat output HTML yang bisa di-print ke PDF
        $html = $this->load->view('v_laporan_pdf', array('pendaftaran' => $pendaftaran, 'statistik' => $statistik), TRUE);
        
        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'landscape');
        $this->pdf->render();
        $this->pdf->stream("laporan_pendaftaran_" . date('Ymd_His') . ".pdf", array("Attachment" => 1));
    }

    public function pasien()
    {
        $data['pasien'] = $this->M_admin->get_all_pasien();
        $data['active_menu'] = 'pasien';
        $data['page_title'] = 'Data Pasien';
        $data['icon'] = 'users';
        $this->load->view('v_pasien_admin', $data);
    }

    public function pasien_add()
    {
        $this->form_validation->set_rules('nama_pasien', 'Nama Pasien', 'required|trim|min_length[3]|max_length[100]');
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required|trim');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim|min_length[5]');
        $this->form_validation->set_rules('no_telp', 'No. Telepon', 'required|trim|regex_match[/^[0-9]{10,13}$/]');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[3]|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]');

        $this->form_validation->set_message('required', '{field} wajib diisi.');
        $this->form_validation->set_message('is_unique', '{field} sudah digunakan.');
        $this->form_validation->set_message('min_length', '{field} minimal {param} karakter.');
        $this->form_validation->set_message('regex_match', 'Format {field} tidak valid (10-13 digit).');
        $this->form_validation->set_message('max_length', '{field} maksimal {param} karakter.');

        if ($this->form_validation->run() == FALSE) {
            $data['mode'] = 'add';
            $data['pasien'] = NULL;
            $data['active_menu'] = 'pasien';
            $data['page_title'] = 'Tambah Pasien';
            $data['icon'] = 'user-plus';
            $this->load->view('v_form_pasien_admin', $data);
        } else {
            $data_insert = array(
                'nama_pasien' => $this->input->post('nama_pasien', TRUE),
                'tgl_lahir'   => $this->input->post('tgl_lahir', TRUE),
                'alamat'      => $this->input->post('alamat', TRUE),
                'no_telp'     => $this->input->post('no_telp', TRUE),
                'username'    => $this->input->post('username', TRUE),
                'password'    => $this->input->post('password', TRUE)
            );

            if ($this->M_admin->insert_pasien($data_insert)) {
                $this->session->set_flashdata('success', 'Data pasien berhasil ditambahkan.');
                redirect('admin/pasien');
            } else {
                $this->session->set_flashdata('error', 'Username sudah digunakan atau data gagal disimpan.');
                redirect('admin/pasien_add');
            }
        }
    }

    public function pasien_edit($id_pasien = NULL)
    {
        if ($id_pasien == NULL) {
            show_404();
        }

        $pasien = $this->M_admin->get_pasien_by_id($id_pasien);
        if (!$pasien) {
            show_404();
        }

        $this->form_validation->set_rules('nama_pasien', 'Nama Pasien', 'required|trim|min_length[3]|max_length[100]');
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required|trim');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim|min_length[5]');
        $this->form_validation->set_rules('no_telp', 'No. Telepon', 'required|trim|regex_match[/^[0-9]{10,13}$/]');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[3]');

        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Password', 'trim|min_length[6]');
        }

        $this->form_validation->set_message('required', '{field} wajib diisi.');
        $this->form_validation->set_message('min_length', '{field} minimal {param} karakter.');
        $this->form_validation->set_message('regex_match', 'Format {field} tidak valid (10-13 digit).');

        if ($this->form_validation->run() == FALSE) {
            $data['mode'] = 'edit';
            $data['pasien'] = $pasien;
            $data['active_menu'] = 'pasien';
            $data['page_title'] = 'Edit Pasien';
            $data['icon'] = 'user-edit';
            $this->load->view('v_form_pasien_admin', $data);
        } else {
            $username = $this->input->post('username', TRUE);

            $data_update = array(
                'id_user'      => $pasien->id_user,
                'nama_pasien'  => $this->input->post('nama_pasien', TRUE),
                'tgl_lahir'    => $this->input->post('tgl_lahir', TRUE),
                'alamat'       => $this->input->post('alamat', TRUE),
                'no_telp'      => $this->input->post('no_telp', TRUE),
                'username'     => $username,
                'password'     => $this->input->post('password', TRUE)
            );

            if ($this->M_admin->update_pasien($id_pasien, $data_update)) {
                $this->session->set_flashdata('success', 'Data pasien berhasil diperbarui.');
                redirect('admin/pasien');
            } else {
                $this->session->set_flashdata('error', 'Username sudah digunakan atau data gagal diperbarui.');
                redirect('admin/pasien_edit/' . $id_pasien);
            }
        }
    }

    public function pasien_delete($id_pasien = NULL)
    {
        if ($id_pasien == NULL) {
            show_404();
        }

        $pasien = $this->M_admin->get_pasien_by_id($id_pasien);
        if (!$pasien) {
            show_404();
        }

        $jumlah_pendaftaran = $this->M_admin->count_pendaftaran_by_pasien($id_pasien);
        if ($jumlah_pendaftaran > 0) {
            $this->session->set_flashdata('error', 'Data pasien tidak bisa dihapus karena sudah memiliki data pendaftaran.');
            redirect('admin/pasien');
        }

        if ($this->M_admin->delete_pasien($id_pasien, $pasien->id_user)) {
            $this->session->set_flashdata('success', 'Data pasien berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Data pasien gagal dihapus.');
        }

        redirect('admin/pasien');
    }
}
?>
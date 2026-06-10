<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_auth');
    }

    public function index()
    {
        redirect('auth/login');
    }

    private function redirect_if_logged_in()
    {
        if ($this->session->userdata('logged_in') === TRUE) {
            if ($this->session->userdata('role') == 'admin') {
                redirect('admin');
            } else {
                redirect('pasien');
            }
        }
    }

    public function login()
    {
        $this->redirect_if_logged_in();

        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        $this->form_validation->set_message('required', '{field} wajib diisi.');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('v_login');
        } else {
            $username = $this->input->post('username', TRUE);
            $password = $this->input->post('password', TRUE);
            $user = $this->M_auth->get_user_by_username($username);

            if ($user && password_verify($password, $user->password)) {
                $session_data = array(
                    'id_user'   => $user->id_user,
                    'username'  => $user->username,
                    'role'      => $user->role,
                    'logged_in' => TRUE
                );

                $this->session->set_userdata($session_data);

                if ($user->role == 'admin') {
                    redirect('admin');
                } else {
                    redirect('pasien');
                }
            }

            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('auth/login');
        }
    }

    public function register()
    {
        $this->redirect_if_logged_in();

        $this->form_validation->set_rules('nama_pasien', 'Nama Pasien', 'required|trim');
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required|trim');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
        $this->form_validation->set_rules('no_telp', 'No. Telepon', 'required|trim');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]');
        $this->form_validation->set_rules('password_confirm', 'Konfirmasi Password', 'required|trim|matches[password]');

        $this->form_validation->set_message('required', '{field} wajib diisi.');
        $this->form_validation->set_message('is_unique', '{field} sudah digunakan.');
        $this->form_validation->set_message('min_length', '{field} minimal {param} karakter.');
        $this->form_validation->set_message('matches', '{field} tidak sama dengan Password.');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('v_register');
        } else {
            $data = array(
                'nama_pasien' => $this->input->post('nama_pasien', TRUE),
                'tgl_lahir'   => $this->input->post('tgl_lahir', TRUE),
                'alamat'      => $this->input->post('alamat', TRUE),
                'no_telp'     => $this->input->post('no_telp', TRUE),
                'username'    => $this->input->post('username', TRUE),
                'password'    => $this->input->post('password', TRUE)
            );

            if ($this->M_auth->register_pasien($data)) {
                $this->session->set_flashdata('success', 'Registrasi berhasil. Silakan login.');
                redirect('auth/login');
            }

            $this->session->set_flashdata('error', 'Registrasi gagal. Silakan coba lagi.');
            redirect('auth/register');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}

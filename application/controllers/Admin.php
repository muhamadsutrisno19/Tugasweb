<?php
defined('BASEPATH') or exit('no direct script access allowed');

class Admin extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        //cek login
        if ($this->session->userdata('status') != "login") {
            redirect(base_url() . 'welcome?pesan=belumlogin');
        }
    }

    function index()
    {
        $data['transaksi'] = $this->db->query("select * from transaksi order by id_pinjam desc limit 10")->result();
        $data['anggota'] = $this->db->query("select * from anggota order by id_anggota desc limit 10")->result();
        $data['buku'] = $this->db->query("select * from buku order by id_buku desc limit 10")->result();

        $this->load->view('admin/header');
        $this->load->view('admin/index', $data);
        $this->load->view('admin/footer');
    }

    function logout()
    {
        $this->sesion->sess_destroy();
        redirect(base_url() . 'welcome?pesan=logout');
    }
    function ganti_password()
    {
        $this->load->view('admin/header');
        $this->load->view('admin/ganti_password');
        $this->load->view('admin/footer');
    }

    function ganti_password_act()
    {
        $pass_baru = $this->input->post('pass_baru');
        $ulang_pass = $this->input->post('ulang_pass');

        $this->form_validation->set_rules('pass_baru', 'Password Baru', 'required|matches[ulang_pass]');
        $this->form_validation->set_rules('ulang_pass', 'Ulangi Password Baru', 'required');
        if ($this->form_validation->run() != false) {
            $data = array('password' => md5($pass_baru));
            $w = array('id_admin' => $this->session->userdata('id'));
            $this->M_perpus->update_data($w, $data, 'admin');
            redirect(base_url() . 'admin/ganti_password?pesan=berhasil');
        } else {
            $this->load->view('admin/header');
            $this->load->view('admin/ganti_password');
            $this->load->view('admin/footer');
        }
    }
    function buku()
    {
        $data['buku'] = $this->M_perpus->get_data('buku')->result();
        $this->load->view('admin/header');
        $this->load->view('admin/buku', $data);
        $this->load->view('admin/footer');
    }

    function tambah_buku()
    {
        $data['kategori'] = $this->M_perpus->get_data('kategori')->result();
        $this->load->view('admin/header');
        $this->load->view('admin/tambahbuku', $data);
        $this->load->view('admin/footer');
    }

    function tambah_buku_act()
    {
        $tgl_input = date('Y-m-d');
        $id_kategori = $this->input->post('id_kategori', true);
        $judul = $this->input->post('judul_buku', true);
        $pengarang = $this->input->post('pengarang', true);
        $penerbit = $this->input->post('penerbit', true);
        $thn_terbit = $this->input->post('thn_terbit', true);
        $isbn = $this->input->post('isbn', true);
        $jumlah_buku = $this->input->post('jumlah_buku', true);
        $lokasi = $this->input->post('lokasi', true);
        $status = $this->input->post('status', true);
        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('judul_buku', 'Judul Buku', 'required');
        $this->form_validation->set_rules('status', 'Status Buku', 'required');
        if ($this->form_validation->run() != false) {
            $config['upload_path'] = './assets/upload/';
            $config['allowed_types'] = '*';
            $config['max_size'] = '2048';
            $config['file_name'] = 'gambar' . time();
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('foto')) {
                $image = $this->upload->data();
                $data = array(
                    'id_kategori' => $id_kategori,
                    'judul_buku' => $judul,
                    'pengarang' => $pengarang,
                    'penerbit' => $penerbit,
                    'thn_terbit' => $thn_terbit, 'isbn' => $isbn,
                    'jumlah_buku' => $jumlah_buku,
                    'lokasi' => $lokasi,
                    'gambar' => $image['file_name'],
                    'tgl_input' => $tgl_input,
                    'status_buku' => $status
                );
                $this->M_perpus->insert_data('buku', $data);
                redirect(base_url() . 'admin/buku');
            } else {
                echo "gagal upload gambar";
            }
            echo validation_errors();
        } else {
            echo validation_errors();
            $data['kategori'] = $this->M_perpus->get_data('kategori')->result();
            $this->load->view('admin/header');
            $this->load->view('admin/tambahbuku', $data);
            $this->load->view('admin/footer');
        }
    }
    function hapus_buku($id)
    {
        $where = array('id_buku' => $id);
        $this->M_perpus->hapuse_data($where, 'buku');
        redirect(base_url() . 'admin/buku');
    }

    function edit_buku($id)
    {
        $where = array('id_buku' => $id);
        $data['buku'] = $this->db->query("select * from buku B, kategori K where B.id_kategori=K.id_kategori and B.id_buku='$id'")->result();
        $data['kategori'] = $this->M_perpus->get_data('kategori')->result();

        $this->load->view('admin/header');
        $this->load->view('admin/editbuku', $data);
        $this->load->view('admin/footer');
    }

    function update_buku()
    {
        $id = $this->input->post('id');
        $id_kategori = $this->input->post('id_kategori');
        $judul = $this->input->post('judul_buku');
        $pengarang = $this->input->post('pengarang');
        $penerbit = $this->input->post('penerbit');
        $thn_terbit = $this->input->post('thn_terbit');
        $isbn = $this->input->post('isbn');
        $jumlah_buku = $this->input->post('jumlah_buku');
        $lokasi = $this->input->post('lokasi');
        $status = $this->input->post('status');

        $this->form_validation->set_rules('id_kategori', 'ID Kategori', 'required');
        $this->form_validation->set_rules('judul_buku', 'Judul Buku', 'required|min_length[4]');
        $this->form_validation->set_rules('pengarang', 'Pengarang', 'required|min_length[4]');
        $this->form_validation->set_rules('penerbit', 'Penerbit', 'required|min_length[4]');
        $this->form_validation->set_rules('thn_terbit', 'Tahun Terbit', 'required|min_length[4]');
        $this->form_validation->set_rules('isbn', 'Nomor ISBN', 'required|numeric');
        $this->form_validation->set_rules('jumlah_buku', 'Jumlah Buku', 'required|numeric');
        $this->form_validation->set_rules('lokasi', 'Lokasi Buku', 'required|min_length[4]');
        $this->form_validation->set_rules('status', 'Status Buku', 'required');

        if ($this->form_validation->run() != false) {
            $config['upload_path'] = './assets/upload/';
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['max_size'] = '2048';
            $config['file_name'] = 'gambar' . time();

            $this->load->library('upload', $config);

            $where = array('id_buku' => $id);
            $data = array(
                'id_kategori' => $id_kategori,
                'judul_buku' => $judul,
                'pengarang' => $pengarang,
                'penerbit' => $penerbit,
                'thn_terbit' => $thn_terbit,
                'isbn' => $isbn,
                'jumlah_buku' => $jumlah_buku,
                'lokasi' => $lokasi,
                'status_buku' => $status
            );

            if ($this->upload->do_upload('foto')) {
                //proses upload Gambar
                $imge = $this->upload->data();
                unlink('assets/upload/' . $this->input->post('old_pict', TRUE));
                $data['gambar'] = $imge['file_name'];

                $this->M_perpus->update_data('buku', $data, $where);
            } else {
                $this->M_perpus->update_data('buku', $data, $where);
            }


            $this->M_perpus->update_data('buku', $data, $where);
            redirect(base_url() . 'admin/buku');
        } else {
            $where = array('id_buku' => $id);
            $data['buku'] = $this->db->query("select * from buku B, kategori K where B.id_kategori=K.id_kategori and B.id_buku='$id'")->result();
            $data['kategori'] = $this->M_perpus->get_data('kategori')->result();
            $this->load->view('admin/header');
            $this->load->view('admin/editbuku', $data);
            $this->load->view('admin/footer');
        }
    }
    function anggota()
    {
        $data['anggota'] = $this->M_perpus->get_data('anggota')->result();
        $this->load->view('admin/header');
        $this->load->view('admin/anggota', $data);
        $this->load->view('admin/footer');
    }
    function tambah_anggota()
    {
        $this->load->view('admin/header');
        $this->load->view('admin/tambahanggota');
        $this->load->view('admin/footer');
    }
    function tambah_anggota_act()
    {

        $nama_anggota = $this->input->post('nama_anggota', true);
        $gender = $this->input->post('gender', true);
        $no_tlpn = $this->input->post('no_tlpn', true);
        $alamat = $this->input->post('alamat', true);
        $email = $this->input->post('email', true);

        $this->form_validation->set_rules('nama_anggota', 'Nama', 'required');
        $this->form_validation->set_rules('gender', 'Gender', 'required');
        $this->form_validation->set_rules('no_tlpn', 'Nomor Telephone', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        if ($this->form_validation->run() != false) {
            $data = array(
                'nama_anggota' => $nama_anggota,
                'gender' => $gender,
                'no_tlpn' => $no_tlpn,
                'alamat' => $alamat,
                'email' => $email,
            );
            $this->M_perpus->insert_data('anggota', $data);
            redirect(base_url() . 'admin/anggota');
        } else {
            echo validation_errors();
            $this->load->view('admin/header');
            $this->load->view('admin/tambahanggota');
            $this->load->view('admin/footer');
        }
    }
    function hapus_anggota($id)
    {
        $where = array('Id_anggota' => $id);
        $this->M_perpus->hapus_data('anggota', $where);
        redirect(base_url() . 'admin/anggota');
    }
    function edit_anggota($id)
    {
        $where = array('Id_anggota' => $id);
        $data['anggota'] = $this->db->query("select * from anggota where Id_anggota='$id'")->result();


        $this->load->view('admin/header');
        $this->load->view('admin/editanggota', $data);
        $this->load->view('admin/footer');
    }
    function update_anggota()
    {
        $id = $this->input->post('id');
        $nama_anggota = $this->input->post('nama_anggota', true);
        $gender = $this->input->post('gender', true);
        $no_tlpn = $this->input->post('no_tlpn', true);
        $alamat = $this->input->post('alamat', true);
        $email = $this->input->post('email', true);

        $this->form_validation->set_rules('nama_anggota', 'Nama', 'required');
        $this->form_validation->set_rules('gender', 'Gender', 'required');
        $this->form_validation->set_rules('no_tlpn', 'Nomor Telephone', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        if ($this->form_validation->run() != false) {
            $data = array(
                'nama_anggota' => $nama_anggota,
                'gender' => $gender,
                'no_tlpn' => $no_tlpn,
                'alamat' => $alamat,
                'email' => $email,
            );
            $where = array('Id_anggota' => $id);
            $this->M_perpus->update_data('anggota', $data, $where);
            redirect(base_url() . 'admin/anggota');
        } else {
            echo validation_errors();
            $this->load->view('admin/header');
            $this->load->view('admin/tambahanggota');
            $this->load->view('admin/footer');
        }
    }
}
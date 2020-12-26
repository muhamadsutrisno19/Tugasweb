<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_perpus extends CI_Model
{
    public function edit_data($where, $table)
    {
        return $this->db->get_where($table, $where);
    }
    public function get_data($table)
    {
        return $this->db->get($table);
    }

    public function get_query($query)
    {
        return $this->db->query("$query");
    }

    public function katalog_buku($id)
    {
        return $this->db->query("SELECT id_buku, judul_buku, pengarang, penerbit, isbn, gambar, buku.id_kategori, nama_kategori, jumlah_buku, tahun_terbit, lokasi, status_buku
        FROM buku
        INNER JOIN kategori ON kategori.id_kategori = buku.id_kategori WHERE id_buku = '$id'");
    }

    public function update_data($table, $data, $where)
    {
        return $this->db->update($table, $data, $where);
    }

    public function insert_data($table, $data)
    {
        return $this->db->insert($table, $data);
    }

    public function hapus_data($table, $where)
    {
        $this->db->where($where);
        $this->db->delete($table);
    }
}
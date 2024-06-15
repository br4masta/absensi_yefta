<?php
defined('BASEPATH') OR die('No direct script access allowed!');

class Absensi_model extends CI_Model 
{
    public function get_absen($id_user, $bulan, $tahun)
    {
        $this->db->select("
            a.jenis,
            a.id_absen,
            DATE_FORMAT(a.tgl, '%d-%m-%Y') AS tgl,
            a.waktu AS jam_masuk,
            a.latitude AS latitude_masuk,
            a.longitude AS longitude_masuk,
            (
                SELECT al.waktu 
                FROM absensi al 
                WHERE al.tgl = a.tgl 
                  AND al.id_user = a.id_user 
                  AND al.keterangan != a.keterangan
                LIMIT 1
            ) AS jam_pulang,
            (
                SELECT al.latitude 
                FROM absensi al 
                WHERE al.tgl = a.tgl 
                  AND al.id_user = a.id_user 
                  AND al.keterangan != a.keterangan
                LIMIT 1
            ) AS latitude_pulang,
            (
                SELECT al.longitude 
                FROM absensi al 
                WHERE al.tgl = a.tgl 
                  AND al.id_user = a.id_user 
                  AND al.keterangan != a.keterangan
                LIMIT 1
            ) AS longitude_pulang,
            (
                SELECT al.id_absen 
                FROM absensi al 
                WHERE al.tgl = a.tgl 
                  AND al.id_user = a.id_user 
                  AND al.keterangan != a.keterangan
                LIMIT 1
            ) AS id_absen_pulang,
             (
                SELECT al.jenis 
                FROM absensi al 
                WHERE al.tgl = a.tgl 
                  AND al.id_user = a.id_user 
                  AND al.keterangan != a.keterangan
                LIMIT 1
            ) AS jenis_pulang,
            u.nama AS user_name
        ");
        $this->db->from('absensi a');
        $this->db->join('users u', 'a.id_user = u.id_user');
        $this->db->where('a.id_user', $id_user);
        $this->db->where("DATE_FORMAT(a.tgl, '%m') =", $bulan);
        $this->db->where("DATE_FORMAT(a.tgl, '%Y') =", $tahun);
        $this->db->group_by("a.tgl");
    
        $result = $this->db->get();
        return $result->result_array();
    }
    

    public function absen_harian_user($id_user)
    {
        $today = date('Y-m-d');
        $this->db->where('tgl', $today);
        $this->db->where('id_user', $id_user);
        $data = $this->db->get('absensi');
        return $data;
    }

    public function insert_data($data)
    {
        $result = $this->db->insert('absensi', $data);
        return $result;
    }

    public function get_jam_by_time($time)
    {
        $this->db->where('start', $time, '<=');
        $this->db->or_where('finish', $time, '>=');
        $data = $this->db->get('jam');
        return $data->row();
    }

    public function get_file_path($id_absen) {
        $this->db->select('file_path');
        $this->db->from('absensi');
        $this->db->where('id_absen', $id_absen);
        $query = $this->db->get();
        return $query->row();
    }
}



/* End of File: d:\Ampps\www\project\absen-pegawai\application\models\Absensi_model.php */
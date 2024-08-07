<?php
defined('BASEPATH') OR die('No direct script access allowed!');
use Dompdf\Dompdf;
use Dompdf\Options;

class Absensi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_login();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Absensi_model', 'absensi');
        $this->load->model('Karyawan_model', 'karyawan');
        $this->load->model('Jam_model', 'jam');
        $this->load->helper('Tanggal');
        require 'vendor/autoload.php';
    }

    public function index()
    {
        if (is_level('Karyawan')) {
            return $this->detail_absensi();
        } else {
            return $this->list_karyawan();
        }
    }

    public function list_karyawan()
    {
        $data['karyawan'] = $this->karyawan->get_all();
        return $this->template->load('template', 'absensi/list_karyawan', $data);
    }

    public function detail_absensi()
    {
        $data = $this->detail_data_absen();
        return $this->template->load('template', 'absensi/detailNew', $data);
    }

    public function check_absen()
    {
        $now = date('H:i:s');
        $data['absen'] = $this->absensi->absen_harian_user($this->session->id_user)->num_rows();
        // var_dump($data['absen']);
        // die;
        return $this->template->load('template', 'absensi/absenNew', $data);
    }

    public function save_image(){
        if(isset($_POST['image']) && isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['keterangan']) && isset($_POST['jenis'])) {
            $file='';
            if($_POST['jenis']==0){
                $data = $_POST['image'];
                
            
                $image_parts = explode(";base64,", $data);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $file_name = 'snapshot_' . uniqid() . '.png';
                $file = 'uploads/' . $file_name;
            
                if (!file_exists('uploads')) {
                    mkdir('uploads', 0777, true);
                }
            
                file_put_contents($file, $image_base64);
            }
            

            //save absen
            if ($_POST['keterangan']) {
                $keterangan = ucfirst($_POST['keterangan']);
            } else {
                $absen_harian = $this->absensi->absen_harian_user($this->session->id_user)->num_rows();
                $keterangan = ($absen_harian < 2 && $absen_harian < 1) ? 'Masuk' : 'Pulang';
            }
    
            $latitude = $_POST['latitude'];
            $longitude = $_POST['longitude'];
            $data = [
                'tgl' => date('Y-m-d'),
                'waktu' => date('H:i:s'),
                'keterangan' => $keterangan,
                'id_user' => $this->session->id_user,
                'file_path'=>$file,
                'latitude'=>$latitude,
                'longitude'=>$longitude,
                'capture_time'=>date('Y-m-d H:i:s'),
                'jenis'=>$_POST['jenis'],
            ];
            $result = $this->absensi->insert_data($data);
        
            if ($result) {
                echo json_encode(["file" => $file, "status" => "success"]);
            } else {
                echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
            }
        } else {
            echo json_encode(["error" => "No image data or location data"]);
        }
    }

    public function absen()
    {
        if (@$this->uri->segment(3)) {
            $keterangan = ucfirst($this->uri->segment(3));
        } else {
            $absen_harian = $this->absensi->absen_harian_user($this->session->id_user)->num_rows();
            $keterangan = ($absen_harian < 2 && $absen_harian < 1) ? 'Masuk' : 'Pulang';
        }


        $data = [
            'tgl' => date('Y-m-d'),
            'waktu' => date('H:i:s'),
            'keterangan' => $keterangan,
            'id_user' => $this->session->id_user
        ];
        $result = $this->absensi->insert_data($data);
        if ($result) {
            $this->session->set_flashdata('response', [
                'status' => 'success',
                'message' => 'Absensi berhasil dicatat'
            ]);
        } else {
            $this->session->set_flashdata('response', [
                'status' => 'error',
                'message' => 'Absensi gagal dicatat'
            ]);
        }
        redirect('absensi/detail_absensi');
    }

    public function export_pdf()
    {
        $this->load->library('pdf');
        $data = $this->detail_data_absen();
        
        $html_content = $this->load->view('absensi/print_pdf', $data, true);
        $filename = 'Absensi ' . $data['karyawan']->nama . ' - ' . bulan($data['bulan']) . ' ' . $data['tahun'] . '.pdf';

        // Memuat HTML ke Dompdf
        $this->pdf->loadHtml($html_content);
        // var_dump($html_content);
        // die();
        // Instansiasi objek Options
        // $options = new Options();
        // $options->set('isPhpEnabled', true); // Opsional, jika Anda perlu PHP dalam dokumen HTML
        // $options->set('isHtml5ParserEnabled', true); // Opsional, tergantung pada konten HTML Anda
        // $options->set('zoom', '0.7'); // Set zoom ke 70%
        
        // // Terapkan opsi pada objek Dompdf
        // $this->pdf->setOptions($options);
        
        // Merender PDF
        $this->pdf->render();
        
        // Stream PDF ke browser dengan nama file
        $this->pdf->stream($filename, ['Attachment' => 1]);
    }

    
    

    public function export_excel()
    {
        include_once APPPATH . 'third_party/PHPExcel.php';
        $data = $this->detail_data_absen();
        $hari = $data['hari'];
        $absen = $data['absen'];
        $excel = new PHPExcel();

        $excel->getProperties()
                ->setCreator('IndoExpress')
                ->setLastModifiedBy('IndoExpress')
                ->setTitle('Data Absensi')
                ->setSubject('Absensi')
                ->setDescription('Absensi' . $data['karyawan']->nama . ' bulan ' . bulan($data['bulan']) . ', ' . $data['tahun'])
                ->setKeyWords('data absen');

        $style_col = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $style_row = [
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $style_row_libur = [
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'fff']
            ],
            'font' => [
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $style_row_tidak_masuk = [
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'DC3545']
            ],
            'font' => [
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $style_telat = [
            'font' => [
                'color' => ['rgb' => 'DC3545']
            ]
        ];

        $style_lembur = [
            'font' => [
                'color' => ['rgb' => '28A745']
            ]
        ];

        $excel->setActiveSheetIndex(0)->setCellValue('A1', 'Nama : ' . $data['karyawan']->nama);
        $excel->getActiveSheet()->mergeCells('A1:D1');
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

        $excel->setActiveSheetIndex(0)->setCellValue('A2', 'Divisi : ' . $data['karyawan']->nama_divisi);
        $excel->getActiveSheet()->mergeCells('A2:D2');
        $excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);

        $excel->setActiveSheetIndex(0)->setCellValue('A3', '');
        $excel->getActiveSheet()->mergeCells('A3:D3');

        $excel->setActiveSheetIndex(0)->setCellValue('A4', 'Data Absensi Bulan ' . bulan($data['bulan']) . ', ' . $data['tahun']);
        $excel->getActiveSheet()->mergeCells('A4:D4');
        $excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(12);

        $excel->setActiveSheetIndex(0)->setCellValue('A5', 'NO');
        $excel->setActiveSheetIndex(0)->setCellValue('B5', 'Tanggal');
        $excel->setActiveSheetIndex(0)->setCellValue('C5', 'Jam Masuk');
        $excel->setActiveSheetIndex(0)->setCellValue('D5', 'Jam Keluar');

        $excel->getActiveSheet()->getStyle('A5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D5')->applyFromArray($style_col);

        $numrow = 6;
        foreach ($hari as $i => $h) {
            $absen_harian = array_search($h['tgl'], array_column($absen, 'tgl')) !== false ? $absen[array_search($h['tgl'], array_column($absen, 'tgl'))] : '';

            $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, ($i+1));
            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $h['hari'] . ', ' . $h['tgl']);
            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, is_weekend($h['tgl']) ? 'Libur Akhir Pekan' : check_jam(@$absen_harian['jam_masuk'], 'masuk', true)['text']);
            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, is_weekend($h['tgl']) ? 'Libur Akhir Pekan' : check_jam(@$absen_harian['jam_pulang'], 'pulang', true)['text']);

            if (check_jam(@$absen_harian['jam_masuk'], 'masuk', true)['status'] == 'telat') {
                $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_telat);
            }

            if (check_jam(@$absen_harian['jam_pulang'], 'pulang', true)['status'] == 'lembur') {
                $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_lembur);
            }

            if (is_weekend($h['tgl'])) {
                $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row_libur);
                $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row_libur);
                $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row_libur);
                $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row_libur);
            } elseif ($absen_harian == '') {
                $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row_tidak_masuk);
                $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row_tidak_masuk);
                $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row_tidak_masuk);
                $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row_tidak_masuk);
            } else {
                $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
            }
            $numrow++;
        }

        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Absensi ' . $data['karyawan']->nama . ' - ' . bulan($data['bulan']) . ' ' . $data['tahun'] . '.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }

    public function export_excelNew()
    {
        include_once APPPATH . 'third_party/PHPExcel.php';
        $data = $this->detail_data_absen();
        $hari = $data['hari'];
        $absen = $data['absen'];
        $excel = new PHPExcel();

        $excel->getProperties()
                ->setCreator('IndoExpress')
                ->setLastModifiedBy('IndoExpress')
                ->setTitle('Data Absensi')
                ->setSubject('Absensi')
                ->setDescription('Absensi' . $data['karyawan']->nama . ' bulan ' . bulan($data['bulan']) . ', ' . $data['tahun'])
                ->setKeyWords('data absen');

        $style_col = [
            'font' => ['bold' => true,'color' => ['rgb' => '000000']],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        $style_row = [
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
            ]
        ];

        

        $excel->setActiveSheetIndex(0)->setCellValue('A1', 'Nama : ' . $data['karyawan']->nama);
        $excel->getActiveSheet()->mergeCells('A1:D1');
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

        $excel->setActiveSheetIndex(0)->setCellValue('A2', 'Divisi : ' . $data['karyawan']->nama_divisi);
        $excel->getActiveSheet()->mergeCells('A2:D2');
        $excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);

        $excel->setActiveSheetIndex(0)->setCellValue('A3', '');
        $excel->getActiveSheet()->mergeCells('A3:D3');

        $excel->setActiveSheetIndex(0)->setCellValue('A4', 'Data Absensi Bulan ' . bulan($data['bulan']) . ', ' . $data['tahun']);
        $excel->getActiveSheet()->mergeCells('A4:D4');
        $excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(12);

        $excel->setActiveSheetIndex(0)->setCellValue('A5', 'NO');
        $excel->setActiveSheetIndex(0)->setCellValue('B5', 'Tanggal');
        $excel->setActiveSheetIndex(0)->setCellValue('C5', 'Jam Masuk');
        $excel->setActiveSheetIndex(0)->setCellValue('D5', 'Jam Keluar');

        $excel->getActiveSheet()->getStyle('A5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C5')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D5')->applyFromArray($style_col);

        $numrow = 6;
        foreach ($hari as $i => $h) {
            $absen_harian = array_search($h['tgl'], array_column($absen, 'tgl')) !== false ? $absen[array_search($h['tgl'], array_column($absen, 'tgl'))] : '';

            $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, ($i+1));
            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $h['hari'] . ', ' . $h['tgl']);

            $jamMasuk = check_jamNew(@$absen_harian['jam_masuk'], 'Masuk',true,@$absen_harian['id_absen'],@$absen_harian['latitude_masuk'],@$absen_harian['longitude_masuk'],@$absen_harian['user_name'],$absen_harian);
            $jamPulang = check_jamNew(@$absen_harian['jam_pulang'], 'Pulang',true,@$absen_harian['id_absen_pulang'],@$absen_harian['latitude_pulang'],@$absen_harian['longitude_pulang'],@$absen_harian['user_name'],$absen_harian);

            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $jamMasuk['text']);
            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $jamPulang['text']);

            
            $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
            $numrow++;
        }

        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Absensi ' . $data['karyawan']->nama . ' - ' . bulan($data['bulan']) . ' ' . $data['tahun'] . '.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }

    private function detail_data_absen()
    {
        $id_user = @$this->uri->segment(3) ? $this->uri->segment(3) : $this->session->id_user;
        $bulan = @$this->input->get('bulan') ? $this->input->get('bulan') : date('m');
        $tahun = @$this->input->get('tahun') ? $this->input->get('tahun') : date('Y');
        
        $data['karyawan'] = $this->karyawan->find($id_user);
        $data['absen'] = $this->absensi->get_absen($id_user, $bulan, $tahun);
        $data['jam_kerja'] = (array) $this->jam->get_all();
        $data['all_bulan'] = bulan();
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['hari'] = hari_bulan($bulan, $tahun);

        return $data;
    }
}


/* End of File: d:\Ampps\www\project\absen-pegawai\application\controllers\Absensi.php */
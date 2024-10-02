<?php
defined('BASEPATH') or exit('No direct script access allowed');
$php_versi = substr(phpversion(),0,1);
if($php_versi>6){
    require 'vendor/autoload.php';
}
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    
class Skpd extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_fungsi', 'fungsi');
        $this->user = is_logged_in();
        $this->akses = cek_akses_user();
        $php_versi = substr(phpversion(),0,1);
        if($php_versi<6){$this->load->library('PHPExcel');}
        
    }

    public function index()
    {
        if ($this->akses['akses'] == 'Y') {
            $data = [
                "menu_active" => "upload_data",
                "submenu_active" => "upload-lra-opd"
            ];
            $this->load->view('upload_lra/skpd/view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }
    

    public function load()
    {
        if ($this->akses['akses'] == 'Y') {
            $tahun = $this->input->post('tahun');
            if ($this->user['is_skpd'] == 'Y') {
                $user = $this->mquery->select_id('users', ['id_user' => $this->user['user']]);
                $result = $this->mquery->select_by("data_skpd_tahun", ['id_skpd'=>$user['id_skpd'], 'tahun'=>$tahun]);
            } else {
                $result = $this->mquery->select_by("data_skpd_tahun", ['tahun'=>$tahun], "id_skpd ASC");
            }
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $encrypt_id = encrypt_url($r['id_skpd']);
                $result_realisasi = 0;

                $cek = $this->mquery->count_data('log_upload', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'st_data' => 2]);
                if($cek==0){
                    $total_pendapatan=0;
                    $hsl_realisasi_pendapatan = 0;
                    $persen_total_pendapatan=0;
                    $total_belanja=0;
                    $hsl_realisasi_belanja = 0;
                    $persen_total_belanja=0;
                    $tamp_bulan="";
                    $tanggal_data="";
                    $user_input="";
                }else{
                    $where = array('id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'st_data' => 2);
                    $result_max = $this->mquery->max_data_where("log_upload", "bulan", $where);
                    $jml_upload = $this->mquery->count_data('log_upload', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'st_data' => 2, 'bulan' => $result_max['bulan']]);
                    if($jml_upload==0){
                        $total_pendapatan=0;
                        $hsl_realisasi_pendapatan = 0;
                        $persen_total_pendapatan=0;
                        $total_belanja=0;
                        $hsl_realisasi_belanja = 0;
                        $persen_total_belanja=0;
                        $tamp_bulan="";
                        $tanggal_data="";
                        $user_input="";
                    }else{
                        $tamp_bulan=bulan($result_max['bulan']);
                        $row_log_upload = $this->mquery->select_id('log_upload', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'st_data' => 2, 'bulan' => $result_max['bulan']]);
                
                        $row_users = $this->mquery->select_id('users', ['id_user' => $row_log_upload['user_input']]);
                        $user_input =$row_users['username'];
                        $cek_papbd = $this->mquery->select_id('setting_anggaran', ['tahun' => $tahun]);
                        $tgl_papbd=$cek_papbd['papbd'];
                        $tanggal_data=$row_log_upload['tgl_data'];
    
                        if($tanggal_data<$tgl_papbd){$hsl_stanggaran=1;}
                        else{$hsl_stanggaran=2;}
    
                        $row_data_uraian_belanja = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $r['id_skpd'], 'kode_rekening' => 5,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
                        $total_belanja=$row_data_uraian_belanja['anggaran'];
                        $row_data_uraian_pendapatan = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $r['id_skpd'], 'kode_rekening' => 4,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
                        $total_pendapatan=$row_data_uraian_pendapatan['anggaran'];
                        
                        // $row_realisasi_pendapatan = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'bulan' => $result_max['bulan'], 'kode_rekening' => 4]);
                        // $row_realisasi_belanja = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'bulan' => $result_max['bulan'], 'kode_rekening' => 5]);
                    
                        $row_realisasi_pendapatan = $this->mquery->sum_data('tbl_realisasi_skpd', 'realisasi', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'kode_rekening' => 4]);
                        $row_realisasi_belanja = $this->mquery->sum_data('tbl_realisasi_skpd', 'realisasi', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'kode_rekening' => 5]);
                        
                        $hsl_realisasi_pendapatan = $row_realisasi_pendapatan['realisasi'];
                        $hsl_realisasi_belanja = $row_realisasi_belanja['realisasi'];
    
                        if($total_belanja==0){$persen_total_belanja=0;}
                        else{$persen_total_belanja = round(($hsl_realisasi_belanja / $total_belanja * 100), 2);;}
    
                        if($total_pendapatan==0){$persen_total_pendapatan=0;}
                        else{$persen_total_pendapatan = round(($hsl_realisasi_pendapatan / $total_pendapatan * 100), 2);}
                    }
                }
                

                $tampil_pendapatan = "<table class='table-detail' style='width:100%;'>
                    <tr><td>Anggaran</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($total_pendapatan) . "</td></tr>
                    <tr><td>Realisasi</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($hsl_realisasi_pendapatan) . "</td></tr>
                    <tr><td>Persen</td><td style='text-align:right; font-weight:bold;'>" . $persen_total_pendapatan . "%</td></tr>
                </table>";

                $tampil_belanja = "<table class='table-detail' style='width:100%;'>
                    <tr><td>Anggaran</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($total_belanja) . "</td></tr>
                    <tr><td>Realisasi</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($hsl_realisasi_belanja) . "</td></tr>
                    <tr><td>Persen</td><td style='text-align:right; font-weight:bold;'>" . $persen_total_belanja . "%</td></tr>
                </table>";
                $nama_skpd = "<a href=" . base_url("upload-lra-opd/detail/" . $tahun . '/' .$encrypt_id) . "><h2>" . $r['nama_skpd'] . "</h2></a>";
                    
                $no++;
                $row = [
                    'no' => $no,
                    'nama_skpd' => $nama_skpd,
                    'bulan' => $tamp_bulan,
                    'pendapatan' => $tampil_pendapatan,
                    'belanja' => $tampil_belanja,
                    'tanggal_data' => $tanggal_data,
                    'user_input' => $user_input
                ];
                $data[] = $row;
            }
            $output['data'] = $data;
            echo json_encode($output);
        } else {
            $data = ['status' => FALSE, 'pesan' => 'blocked'];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function detail($tahun, $encrypt_id)
    {
        $tahun_now=date('Y');
        $id_skpd = decrypt_url($encrypt_id);
        $skpd = $this->mquery->select_id('data_skpd_tahun', ['id_skpd' => $id_skpd, 'tahun'=> $tahun]);
        $data = [
            "menu_active" => "upload_data",
            "submenu_active" => "upload-lra-opd",
            "skpd" => $skpd,
            "tahun" => $tahun,
            "tahun_now" => $tahun_now,
            "encrypt_id" => $encrypt_id
        ];
        $this->load->view('upload_lra/skpd/view_detail', $data);
    }

    public function load_apbd()
    {
        //$tahun=2022;
        $tahun = $this->input->post('tahun');
        $skpd = $this->input->post('id');
        $encrypt_id = encrypt_url($skpd);
        $result = $this->mquery->select_by('log_upload', ['id_skpd' => $skpd, 'tahun'=> $tahun, 'st_data'=> 2], "bulan ASC");
        $data = [];
        $no = 0;
        $tahun_now=date('Y');
        foreach ($result as $r) {
            $no++;
            $nama_bulan = "<a href=" . base_url("upload-lra-opd/detail2/" .$encrypt_id."/". $tahun. "/". $r['bulan']) . ">" . bulan($r['bulan']) . "</a>";
            
            $cek_papbd = $this->mquery->select_id('setting_anggaran', ['tahun' => $tahun]);
            $tgl_papbd=$cek_papbd['papbd'];
            $tanggal_data=$r['tgl_data'];

            if($tanggal_data<$tgl_papbd){$hsl_stanggaran=1;}
            else{$hsl_stanggaran=2;}

            $row_data_uraian_belanja = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $skpd, 'kode_rekening' => 5,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
            $total_belanja=$row_data_uraian_belanja['anggaran'];
            $row_data_uraian_pendapatan = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $skpd, 'kode_rekening' => 4,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
            $total_pendapatan=$row_data_uraian_pendapatan['anggaran'];
            
            $row_realisasi_pendapatan = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_skpd' => $skpd, 'tahun' => $tahun, 'bulan' => $r['bulan'], 'kode_rekening' => 4]);
            $row_realisasi_belanja = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_skpd' => $skpd, 'tahun' => $tahun, 'bulan' => $r['bulan'], 'kode_rekening' => 5]);
            $row_users = $this->mquery->select_id('users', ['id_user' => $r['user_input']]);
            
            if($total_belanja==0){$persen_total_belanja=0;}
            else{$persen_total_belanja = round(($row_realisasi_belanja['realisasi'] / $total_belanja * 100), 2);}
            if($total_pendapatan==0){$persen_total_pendapatan=0;}
            else{$persen_total_pendapatan = round(($row_realisasi_pendapatan['realisasi'] / $total_pendapatan * 100), 2);}

            $tampil_pendapatan = "<table class='table-detail' style='width:100%;'>
                <tr><td>Anggaran</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($total_pendapatan) . "</td></tr>
                <tr><td>Realisasi</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($row_realisasi_pendapatan['realisasi']) . "</td></tr>
                <tr><td>Persen</td><td style='text-align:right; font-weight:bold;'>" . $persen_total_pendapatan . "%</td></tr>
            </table>";

            $tampil_belanja = "<table class='table-detail' style='width:100%;'>
                <tr><td>Anggaran</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($total_belanja) . "</td></tr>
                <tr><td>Realisasi</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($row_realisasi_belanja['realisasi']) . "</td></tr>
                <tr><td>Persen</td><td style='text-align:right; font-weight:bold;'>" . $persen_total_belanja . "%</td></tr>
            </table>";

            if(empty($r['namafile']))
            {
                $tgl_data=str_replace("-","",$r['tanggal_input']);
                $tgl_data=str_replace(":","",$tgl_data);
                $tgl_data=str_replace(" ","-",$tgl_data);
                $nama_file=$skpd."-lra-".$tgl_data.".xls";
                //$nama_file=cek_file_excel($namafile);
            }
            else
            {
                $nama_file = "<a href=" . base_url("uploads/berkas/excel/" . $r['namafile']) . ">" . $r['namafile'] . "</a>";
            }

            if($tahun_now==$tahun)
            {
                if ($this->akses['hapus'] == 'Y') 
                    {$delete = action_delete(encrypt_url($r['id_log']));}
                else{$delete = "-";}
            }
            else
            {
                if ($this->akses['hapus_1'] == 'Y') 
                    {$delete = action_delete(encrypt_url($r['id_log']));}
                else{$delete = "-";}
            }
            
            //sub rutin untuk isi tabel realisasi per bulan-------------------
            if($total_pendapatan==0){$data_realisasi_pendapatan=0;}else{
                if($r['bulan']==1){$data_realisasi_pendapatan=$row_realisasi_pendapatan['realisasi'];}else{
                    $row_realisasi_pendapatan_1 = $this->mquery->sum_data('tbl_realisasi_skpd', 'realisasi', ['id_skpd' => $skpd, 'tahun' => $tahun, 'bulan<' => $r['bulan'], 'kode_rekening' => 4]);
                    $data_realisasi_pendapatan=$row_realisasi_pendapatan['realisasi'] - $row_realisasi_pendapatan_1['realisasi'];
                }
            }
            $cek_data_pendapatan = $this->mquery->count_data('tbl_realisasi_skpd', ['id_skpd' => $skpd, 'tahun' => $tahun, 'bulan' => $r['bulan'], 'kode_rekening' => 4]);
            if($cek_data_pendapatan==0){
                $array_insert =  [
                    'id_skpd' => $skpd,
                    'tahun' => $tahun,
                    'bulan' => $r['bulan'],
                    'kode_rekening' => 4,
                    'realisasi' => $data_realisasi_pendapatan
                ];
                if($r['bulan']!=0){$this->db->insert('tbl_realisasi_skpd', $array_insert);}
            }else{
                $array_update =  ['realisasi' => $data_realisasi_pendapatan];
                if($r['bulan']!=0){
                    $this->db->update('tbl_realisasi_skpd', $array_update, ['id_skpd' => $skpd, 'tahun' => $tahun, 'bulan' => $r['bulan'], 'kode_rekening' => 4]);
                }
            }
            
            if($r['bulan']==1){$data_realisasi_belanja=$row_realisasi_belanja['realisasi'];}else{
                $row_realisasi_belanja_1 = $this->mquery->sum_data('tbl_realisasi_skpd', 'realisasi', ['id_skpd' => $skpd, 'tahun' => $tahun, 'bulan<' => $r['bulan'], 'kode_rekening' => 5]);
                $data_realisasi_belanja=$row_realisasi_belanja['realisasi'] - $row_realisasi_belanja_1['realisasi'];
            }

            $cek_data_belanja = $this->mquery->count_data('tbl_realisasi_skpd', ['id_skpd' => $skpd, 'tahun' => $tahun, 'bulan' => $r['bulan'], 'kode_rekening' => 5]);
            if($cek_data_belanja==0){
                $array_insert =  [
                    'id_skpd' => $skpd,
                    'tahun' => $tahun,
                    'bulan' => $r['bulan'],
                    'kode_rekening' => 5,
                    'realisasi' => $data_realisasi_belanja
                ];
                if($r['bulan']!=0){$this->db->insert('tbl_realisasi_skpd', $array_insert);}
            }else{
                $array_update =  ['realisasi' => $data_realisasi_belanja];
                if($r['bulan']!=0){
                    $this->db->update('tbl_realisasi_skpd', $array_update, ['id_skpd' => $skpd, 'tahun' => $tahun, 'bulan' => $r['bulan'], 'kode_rekening' => 5]);
                }
            }

            $row_tampil_real_pendapatan=$this->mquery->sum_data('tbl_realisasi_skpd', 'realisasi', ['id_skpd' => $skpd, 'tahun' => $tahun, 'bulan<=' => $r['bulan'], 'kode_rekening' => 4]);
            $row_tampil_real_belanja=$this->mquery->sum_data('tbl_realisasi_skpd', 'realisasi', ['id_skpd' => $skpd, 'tahun' => $tahun, 'bulan<=' => $r['bulan'], 'kode_rekening' => 5]);
            //----------------------------------------

            $row = [
                'no' => $no,
                'tahun' => $tahun,
                'bulan' => $nama_bulan,
                'pendapatan' => $tampil_pendapatan,
                //'pendapatan' => $tampil_pendapatan."<br>".format_rupiah($row_tampil_real_pendapatan['realisasi']),
                'belanja' => $tampil_belanja,
                //'belanja' => $tampil_belanja."<br>".format_rupiah($row_tampil_real_belanja['realisasi']),
                'tanggal_data' => "Data:".$r['tgl_data']."<br>Input:".$r['tanggal_input']."<br>File:".$nama_file,
                'user_input' => $row_users['username'],
                'opsi' => $delete
            ];
            
            $data[] = $row;
        }

        $output['data'] = $data;
        echo json_encode($output);
    }

    public function detail2($encrypt_id, $tahun, $bulan)
    {
        $id_skpd = decrypt_url($encrypt_id);
        $row_log_upload = $this->mquery->select_id('log_upload', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $bulan, 'st_data' => 2]);
        $skpd = $this->mquery->select_id('data_skpd_tahun', ['id_skpd' => $id_skpd, 'tahun'=> $tahun]);
        $data = [
            "menu_active" => "upload_data",
            "submenu_active" => "upload-lra-opd",
            "skpd" => $skpd,
            "tahun" => $tahun,
            "bulan" => $bulan,
            "encrypt_id" => $encrypt_id,
            "nama_periode" => $this->fungsi->nama_bulan($row_log_upload['tgl_data'])
        ];
        $this->load->view('upload_lra/skpd/view_detail2', $data);
    }

    public function load_apbd2()
    {
        //$tahun=2022;
        $skpd = $this->input->post('id');
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        $encrypt_id = encrypt_url($skpd);
        $result = $this->mquery->select_by('data_realisasi_detail_skpd', ['id_skpd' => $skpd, 'tahun' => $tahun, 'bulan' => $bulan], "kode_rekening ASC");
        $data = [];
        $no = 0;
        $tahun_now=date('Y');
        foreach ($result as $r) {
            $no++;
            $row_anggaran = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $skpd, 'tahun' => $tahun, 'kode_rekening' => $r['kode_rekening']]);
            $row_uraian = $this->mquery->select_id('data_uraian_kegiatan_pemko', ['kode_rekening' => $r['kode_rekening']]);
            
            if($row_anggaran['anggaran']==0){$persen=0;}
            else{$persen=round(($r['realisasi']/$row_anggaran['anggaran']*100),2);}

            if($tahun_now==$tahun)
            {
                if ($this->akses['ubah'] == 'Y') 
                    {$edit = action_edit(encrypt_url($r['id_realisasi']));}
                else{$edit = "-";}

                if ($this->akses['hapus'] == 'Y') 
                    {$delete = action_delete(encrypt_url($r['id_realisasi']));}
                else{$delete = "-";}
            }
            else
            {
                if ($this->akses['ubah_1'] == 'Y') 
                    {$edit = action_edit(encrypt_url($r['id_realisasi']));}
                else{$edit = "-";}

                if ($this->akses['hapus_1'] == 'Y') 
                    {$delete = action_delete(encrypt_url($r['id_realisasi']));}
                else{$delete = "-";}
            }

            if($row_anggaran['anggaran']!=0)
            {
                $row = [
                    'no' => $no,
                    'tahun' => $tahun,
                    'bulan' => bulan($bulan),
                    'kode_rekening' => $r['kode_rekening'],
                    'uraian' => $row_uraian['uraian'],
                    'anggaran' => format_rupiah($row_anggaran['anggaran']),
                    'realisasi' => format_rupiah($r['realisasi']),
                    'persen' => format_rupiah($persen)." %",
                    'opsi' => $edit . ' ' . $delete
                ];
                $data[] = $row;
            }
        }

        $output['data'] = $data;
        echo json_encode($output);
    }

    public function form_upload()
    {
        $id_skpd = htmlspecialchars($this->input->post('skpd', TRUE));
        $tahun = htmlspecialchars($this->input->post('tahun', TRUE));
        $data = [
            'id_skpd' => $id_skpd,
            'tahun' => $tahun
        ];
        $this->load->view('upload_lra/skpd/form_upload', $data);
    }


    private function _rule1_form()
    {
        $this->form_validation->set_rules('tanggal', 'Tanggal Data', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
    }

    private function _send1_error()
    {
        $errors = [
            'tanggal' => form_error('tanggal')
        ];
        $data = ['status' => FALSE, 'errors' => $errors, 'pesan' => 'Data Gagal Disimpan'];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }  

    function upload()
    {
        $this->_rule1_form();
        if ($this->form_validation->run() == false) {
            $this->_send1_error();
        } else {
            $post = $this->input->post(null, TRUE);
            $id_skpd = htmlspecialchars($post['id_skpd']);

            $new_file = "";
            
            $config['upload_path'] = "./uploads/berkas/excel/";
            $config['allowed_types'] = 'xlsx';
            $config['file_name'] = $id_skpd . '_lra_' . date("Ymd-His");
            $config['max_size'] = 1024;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('file_upload')) {
                $errors = [
                    'file_upload' => $this->upload->display_errors()
                ];
                $data = ['status' => FALSE, 'errors' => $errors, 'pesan' => 'Data Gagal Disimpan'];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $upload = $this->upload->data();
                $new_file = $upload['file_name'];

                if ($new_file != "" || $new_file != NULL) {
                    
                    $php_versi = substr(phpversion(),0,1);
                    if($php_versi<6){
                        $object = new PHPExcel();
                        $excelreader     = new PHPExcel_Reader_Excel2007();
                        $loadexcel         = $excelreader->load('./uploads/berkas/excel/' . $new_file);
                        $sheetData = $loadexcel->getSheet(0)->toArray(null, true, true, true);

                        $numrow = 0;
                        $cek_validasi=0;
                        foreach ($sheetData as $row) {
                            if ($numrow > 10) {
                                $cek_D = strlen($row['D']);
                                if ($cek_D != 0) {
                                    if ($row['H']== "BELANJA DAERAH"){$cek_validasi=1;}
                                }
                            }
                            $numrow++;
                        }
                    }else{
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                        $spreadsheet = $reader->load('./uploads/berkas/excel/' . $new_file);
                        $numrow = 0;
                        $cek_validasi=0;
                        $sheetData = $spreadsheet->getSheet(0)->toArray();
                        unset($sheetData[0]);
                        foreach ($sheetData as $row) {
                            if ($numrow > 10) {
                                $cek_D = strlen($row[3]);
                                if ($cek_D != 0) {
                                    if ($row[7]== "BELANJA DAERAH"){$cek_validasi=1;}
                                }
                            }
                            $numrow++;
                        }
                    }

                    if($cek_validasi==0)
                    {
                        $errors = [
                            'data error' => 'data error'
                        ];
                        $data = ['status' => FALSE, 'errors' => $errors, 'pesan' => 'Format Tidak Sesuai'];
                        $this->output->set_content_type('application/json')->set_output(json_encode($data));
                    }
                    else
                    {
                        $this->db->trans_start();
                        $data = array();
                        $numrow = 0;
                        $kode_rekening='';
                        $level='';
                        $anggaran='';
                        $jenis=1;
                        $cek_papbd = $this->mquery->select_id('setting_anggaran', ['tahun' => $post['tahun']]);
                        $tgl_papbd=$cek_papbd['papbd'];
                        $tgl_now=date('Y-m-d');
                        $cek_tanggal=$post['tanggal'];
                        if($cek_tanggal<=$tgl_papbd){$hsl_stanggaran=1;}
                        else{$hsl_stanggaran=2;}
                        $temp_bulan=substr($cek_tanggal,5,2);
                        $hsl_bulan=intval($temp_bulan);

                        foreach ($sheetData as $row) {
                            if ($numrow > 10) {
                                if($php_versi<6){$cek_D = strlen($row['D']);}
                                else{$cek_D = strlen($row[3]);}
                                
                                if ($cek_D != 0) {
                                    if($php_versi<6){
                                        $kode_rekening=str_replace(' ','',$row['D']);
                                        $cek_koma=strpos($row['Q'],",");
                                        if ($cek_koma !== FALSE)
                                        {$anggaran = konversi_angka($row['Q']);}
                                        else {$anggaran = number_only($row['Q']);}
                                        $cek_H = strlen($row['H']);
                                        $cek_I = strlen($row['I']);
                                        $cek_J = strlen($row['J']);
                                        $cek_K = strlen($row['K']);
                                        $cek_L= strlen($row['L']);
                                        $cek_M= strlen($row['M']);
    
                                        if ($cek_H >= 1){$uraian=$row['H'];$level=1;}
                                        elseif ($cek_I >= 1){$uraian=$row['I'];$level=2;}
                                        elseif ($cek_J >= 1){$uraian=$row['J'];$level=3;}
                                        elseif ($cek_K >= 1){$uraian=$row['K'];$level=4;}
                                        elseif ($cek_L >= 1){$uraian=$row['L'];$level=5;}
                                        elseif ($cek_M >= 1){$uraian=$row['M'];$level=6;}
                                        else {$uraian="";$level=7;}
            
                                        if ($row['H']== "BELANJA DAERAH"){$jenis=2;}
                                        if ($row['I']== "PENERIMAAN PEMBIAYAAN"){$jenis=3;}
                                        if ($row['I']== "PENGELUARAN PEMBIAYAAN"){$jenis=4;}
                                    }else{
                                        $kode_rekening=str_replace(' ','',$row[3]);
                                        $cek_koma=strpos($row[16],",");
                                        if ($cek_koma !== FALSE)
                                        {$anggaran = konversi_angka($row[16]);}
                                        else {$anggaran = number_only($row[16]);}
                                        $cek_H = strlen($row[7]);
                                        $cek_I = strlen($row[8]);
                                        $cek_J = strlen($row[9]);
                                        $cek_K = strlen($row[10]);
                                        $cek_L= strlen($row[11]);
                                        $cek_M= strlen($row[12]);
        
                                        if ($cek_H >= 1){$uraian=$row[7];$level=1;}
                                        elseif ($cek_I >= 1){$uraian=$row[8];$level=2;}
                                        elseif ($cek_J >= 1){$uraian=$row[9];$level=3;}
                                        elseif ($cek_K >= 1){$uraian=$row[10];$level=4;}
                                        elseif ($cek_L >= 1){$uraian=$row[11];$level=5;}
                                        elseif ($cek_M >= 1){$uraian=$row[12];$level=6;}
                                        else {$uraian="";$level=7;}
            
                                        if ($row[7]== "BELANJA DAERAH"){$jenis=2;}
                                        if ($row[8]== "PENERIMAAN PEMBIAYAAN"){$jenis=3;}
                                        if ($row[8]== "PENGELUARAN PEMBIAYAAN"){$jenis=4;}
                                    }
                                    
                                    $array_alokasi_skpd =  [
                                        'id_skpd' => $id_skpd,
                                        'kode_rekening' => $kode_rekening,
                                        'level' => $level,
                                        'anggaran' => $anggaran,
                                        'jenis' => $jenis,
                                        'tahun' => $post['tahun'],
                                        'st_anggaran' => $hsl_stanggaran
                                    ];

                                    $update_alokasi_skpd =  [
                                        'anggaran' => $anggaran
                                    ];

                                    $cek_jml_data2 = $this->mquery->count_data('data_uraian_kegiatan_skpd', ['id_skpd' => $id_skpd, 'kode_rekening' => $kode_rekening, 'tahun' => $post['tahun'], 'st_anggaran' => $hsl_stanggaran]);
                                    if($cek_jml_data2==0)
                                    {
                                        $this->db->insert('data_uraian_kegiatan_skpd', $array_alokasi_skpd);
                                    }
                                    else
                                    {
                                        $this->db->update('data_uraian_kegiatan_skpd', $update_alokasi_skpd, ['id_skpd' => $id_skpd, 'kode_rekening' => $kode_rekening, 'tahun' => $post['tahun'], 'st_anggaran' => $hsl_stanggaran]);
                                    }
                                }
                            }
                            $numrow++;
                        }

                        
                        $this->db->trans_complete();
                        $res = $this->db->trans_status();

                        $this->db->trans_start();
                        $this->db->delete('data_realisasi_detail_skpd', ['id_skpd' => $id_skpd, 'tahun' => $post['tahun'], 'bulan' => $hsl_bulan]);
                        $this->db->delete('data_serapan_skpd', ['id_skpd' => $id_skpd, 'tahun' => $post['tahun'], 'bulan' => $hsl_bulan]);
                        
                        $cek = $this->mquery->count_data('log_upload', ['id_skpd' => $id_skpd, 'tahun' => $post['tahun'], 'bulan' => $hsl_bulan, 'st_data' => 2]);
                        if($cek>0){
                            $log_upload = $this->mquery->select_id('log_upload', ['id_skpd' => $id_skpd, 'tahun' => $post['tahun'], 'bulan' => $hsl_bulan, 'st_data' => 2]);
                            $this->db->delete('log_upload', ['id_skpd' => $id_skpd, 'tahun' => $post['tahun'], 'bulan' => $hsl_bulan, 'st_data' => 2]);
                            hapus_file("/uploads/berkas/excel/" , $log_upload['namafile']);
                        }
                        

                        $data = array();
                        $numrow = 0;
                        $kode_rekening='';
                        $realisasi='';
                        $serapan='';
                        foreach ($sheetData as $row) {
                            if ($numrow > 10) {
                                if($php_versi<6){$cek_D = strlen($row['D']);}
                                else{$cek_D = strlen($row[3]);}
                                if ($cek_D != 0) {
                                    if($php_versi<6){
                                        $kode_rekening=$row['D'];
                                        $row_uraian = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $id_skpd, 'tahun' => $post['tahun'], 'kode_rekening' => $kode_rekening]);
                                        $nilai_anggaran=$row_uraian['anggaran'];
                                        $cek_koma=strpos($row['R'],",");
                                        if ($cek_koma !== FALSE)
                                        {$realisasi = konversi_angka($row['R']);}
                                        else {$realisasi = number_only($row['R']);}
                                    }else{
                                        $kode_rekening=$row[3];
                                        $row_uraian = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $id_skpd, 'tahun' => $post['tahun'], 'kode_rekening' => $kode_rekening]);
                                        $nilai_anggaran=$row_uraian['anggaran'];
                                        $cek_koma=strpos($row[17],",");
                                        if ($cek_koma !== FALSE)
                                        {$realisasi = konversi_angka($row[17]);}
                                        else {$realisasi = number_only($row[17]);}
                                    }

                                    if($realisasi==0){$persen=0;}
                                    else
                                    {
                                        if($nilai_anggaran==0){$persen=0;}
                                        else{
                                            $temp_persen=$realisasi/$nilai_anggaran*100;
                                            $persen=round($temp_persen,2);
                                        }
                                    }
                                    $array_realisasi =  [
                                        'id_skpd' => $id_skpd,
                                        'kode_rekening' => $kode_rekening,
                                        'realisasi' => $realisasi,
                                        'persen' => $persen,
                                        'tahun' => $post['tahun'],
                                        'bulan' => $hsl_bulan
                                    ];
                                    $this->db->insert('data_realisasi_detail_skpd', $array_realisasi);
                                }
                            }
                            $numrow++;
                        }

                        $array_tanggal =  [
                            'tahun' => $post['tahun'],
                            'bulan' => $hsl_bulan,
                            'id_skpd' => $id_skpd,
                            'st_data' => 2,
                            'tgl_data' => $post['tanggal'],
                            'tanggal_input' => date('Y-m-d H:i:s'),
                            'user_input' => $this->user['user'],
                            'namafile' => $new_file
                        ];
                        $this->db->insert('log_upload', $array_tanggal);

                        $this->db->trans_complete();
                        $res = $this->db->trans_status();
                        $data = ['status' => TRUE, 'notif' => $res];
                        $this->output->set_content_type('application/json')->set_output(json_encode($data));
                    }
                }else{
                    $data = ['status' => FALSE, 'notif' => "FILE TIDAK DIKENALI"];
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));  
                }
            }
        } 
    }

    function delete()
    {
        $id = htmlspecialchars($this->input->post('id', TRUE));
        $id_log = decrypt_url($id);
        if ($id_log == "error") {
            $data = ['notif' => FALSE, 'pesan' => "blocked"];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $temp = $this->mquery->select_id('log_upload', ['id_log' => $id_log]);
            $string = ['log_upload' => $temp];
            $log = simpan_log("delete log_upload", json_encode($string));
            $this->db->trans_start();
            $this->db->delete('log_upload', ['id_log' => $id_log]);
            $this->db->delete('data_realisasi_detail_skpd', ['id_skpd' => $temp['id_skpd'], 'tahun' => $temp['tahun'], 'bulan' => $temp['bulan']]);
            $this->db->delete('tbl_realisasi_skpd', ['id_skpd' => $temp['id_skpd'], 'tahun' => $temp['tahun'], 'bulan' => $temp['bulan']]);
            $this->db->insert('log_user', $log);
            $this->db->trans_complete();
            $res = $this->db->trans_status();
            hapus_file("/uploads/berkas/excel/" , $temp['namafile']);
            $data = ['notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function form()
    {
        $opsi = htmlspecialchars($this->input->post('opsi', TRUE));
        $id_skpd = htmlspecialchars($this->input->post('id_skpd', TRUE));
        $tahun = htmlspecialchars($this->input->post('tahun', TRUE));
        $bulan = htmlspecialchars($this->input->post('bulan', TRUE));
        if ($opsi == "add") {
            $data = [
                "skpd" => $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]),
                "uraian" => $this->mquery->select_data('data_kode_rekening'),
                "tahun" => $tahun,
                "bulan" => $bulan
            ];
            $this->load->view('upload_lra/skpd/form_add', $data);
        } elseif ($opsi == "edit") {
            $encrypt_id = htmlspecialchars($this->input->post('id', TRUE));
            $id_no = decrypt_url($encrypt_id);
            $data_realisasi = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_realisasi' => $id_no]);
            $data = [
                "data_realisasi" => $data_realisasi,
                "skpd" => $this->mquery->select_id('data_skpd', ['id_skpd' => $data_realisasi['id_skpd']]),
                "uraian" => $this->mquery->select_data('data_kode_rekening')
            ];
            $this->load->view('upload_lra/skpd/form_edit', $data);
        } else {
            $this->load->view('blocked');
        }
    }

    private function _rule_form()
    {
        $this->form_validation->set_rules('id_skpd', 'Satuan kerja', 'required|trim');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('bulan', 'bulan', 'required|trim');
        $this->form_validation->set_rules('id_uraian', 'Uraian', 'required|trim');
        $this->form_validation->set_rules('realisasi', 'realisasi', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
    }

    private function _send_error()
    {
        $errors = [
            'id_skpd' => form_error('id_skpd'),
            'tahun' => form_error('tahun'),
            'bulan' => form_error('bulan'),
            'id_uraian' => form_error('id_uraian'),
            'realisasi' => form_error('realisasi')
        ];
        $data = ['status' => FALSE, 'errors' => $errors, 'pesan' => 'Data Gagal Disimpan'];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function add()
    {
        $this->_rule_form();
        if ($this->form_validation->run() == false) {
            $this->_send_error();
        } else {
            $post = $this->input->post(null, TRUE);
            $id_uraian=htmlspecialchars($post['id_uraian']);
            $temp = $this->mquery->select_id('data_kode_rekening', ['id_uraian' => $id_uraian]);
            $realisasi = str_replace(".", "", htmlspecialchars($post['realisasi']));
            $array =  [
                'id_skpd' => htmlspecialchars($post['id_skpd']),
                'kode_rekening' => $temp['kode_rekening'],
                'realisasi' => $realisasi,
                'tahun' => htmlspecialchars($post['tahun']),
                'bulan' => htmlspecialchars($post['bulan'])
            ];

            $string = ['data_realisasi_detail_skpd' => $array];
            $log = simpan_log("insert data_realisasi_detail_skpd", json_encode($string));
            $res = $this->mquery->insert_data('data_realisasi_detail_skpd', $array, $log);
            $data = ['status' => TRUE, 'notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    function edit()
    {
        $this->_rule_form();
        if ($this->form_validation->run() == false) {
            $this->_send_error();
        } else {
            $post = $this->input->post(null, TRUE);
            $id_no = htmlspecialchars($post['id_no']);
            $realisasi = str_replace(".", "", htmlspecialchars($post['realisasi']));
            $array =  [
                'realisasi' => $realisasi
            ];
            $temp = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_realisasi' => $id_no]);
            $string = ['data_realisasi_detail_skpd' => ['old' => $temp, 'new' => $array]];
            $log = simpan_log("update data_realisasi_detail_skpd", json_encode($string));
            $res = $this->mquery->update_data('data_realisasi_detail_skpd', $array, ['id_realisasi' => $id_no], $log);
            $data = ['status' => TRUE, 'notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function delete_data()
    {
        $encrypt_id = htmlspecialchars($this->input->post('id', TRUE));
        $id_uraian = decrypt_url($encrypt_id);
        $temp = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_realisasi' => $id_uraian]);
        $string = ['data_realisasi_detail_skpd' => $temp];
        $log = simpan_log("delete data_realisasi_detail_skpd", json_encode($string));
        $res = $this->mquery->delete_data('data_realisasi_detail_skpd', ['id_realisasi' => $id_uraian], $log);
        $data = ['status' => TRUE, 'notif' => $res];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }


}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data_apbd extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_fungsi', 'fungsi');
        $this->user = is_logged_in();
        $this->akses = cek_akses_user();
    }
    
    public function index()
    {
        if ($this->akses['akses'] == 'Y') {
            $data = [
                "menu_active" => "data-apbd",
                "submenu_active" => NULL
            ];
            $this->load->view('daerah/data_apbd/view', $data);
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
                $nama_skpd = "<a href=" . base_url("data-apbd/detail/" . $tahun . '/' .$encrypt_id) . "><h2>" . $r['nama_skpd'] . "</h2></a>";
                $result_realisasi = 0;
                $cek_data_bulan = $this->mquery->count_data('log_upload', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'st_data' => 2]);
                if($cek_data_bulan!=0)
                {
                    $where = array('id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'st_data' => 2);
                    $result_max = $this->mquery->max_data_where("log_upload", "bulan", $where);
                    $row_log_upload = $this->mquery->select_id('log_upload', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'st_data' => 2, 'bulan' => $result_max['bulan']]);
                    $row_users = $this->mquery->select_id('users', ['id_user' => $row_log_upload['user_input']]);
                    $cek_papbd = $this->mquery->select_id('setting_anggaran', ['tahun' => $tahun]);
                    $tgl_papbd=$cek_papbd['papbd'];
                    $tanggal_data=$row_log_upload['tgl_data'];
                    if($tanggal_data<$tgl_papbd){$hsl_stanggaran=1;}
                    else{$hsl_stanggaran=2;}
                    $bulan_tampil=bulan($result_max['bulan']);

                    $cek_data = $this->mquery->count_data('data_uraian_kegiatan_skpd', ['id_skpd' => $r['id_skpd'], 'kode_rekening' => 5,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
                    if($cek_data!=0)
                    {
                        $cek_uraian_belanja = $this->mquery->count_data('data_uraian_kegiatan_skpd', ['id_skpd' => $r['id_skpd'], 'kode_rekening' => 5,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
                        if($cek_uraian_belanja!=0)
                        {
                            $row_data_uraian_belanja = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $r['id_skpd'], 'kode_rekening' => 5,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
                            $total_belanja=$row_data_uraian_belanja['anggaran'];
                        }else{$total_belanja=0;}

                        $cek_uraian_pendapatan = $this->mquery->count_data('data_uraian_kegiatan_skpd', ['id_skpd' => $r['id_skpd'], 'kode_rekening' => 4,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
                        if($cek_uraian_pendapatan!=0)
                        {
                            $row_data_uraian_pendapatan = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $r['id_skpd'], 'kode_rekening' => 4,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
                            $total_pendapatan=$row_data_uraian_pendapatan['anggaran'];
                        }else{$total_pendapatan=0;}
                        
                        $cek = $this->mquery->count_data('data_realisasi_detail_skpd', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'bulan' => $result_max['bulan'], 'kode_rekening' => 4]);
                        if($cek!=0){
                            $row_realisasi_pendapatan = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'bulan' => $result_max['bulan'], 'kode_rekening' => 4]);
                            $realisasi_pendapatan=$row_realisasi_pendapatan['realisasi'];
                        }else{$realisasi_pendapatan=0;}
                        
                        $cek = $this->mquery->count_data('data_realisasi_detail_skpd', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'bulan' => $result_max['bulan'], 'kode_rekening' => 5]);
                        if($cek!=0){
                            $row_realisasi_belanja = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'bulan' => $result_max['bulan'], 'kode_rekening' => 5]);
                            $realisasi_belanja=$row_realisasi_belanja['realisasi']; 
                        }else{$realisasi_belanja=0;}
                    }
                    else
                    {
                        $total_belanja=0;
                        $total_pendapatan=0;
                        $realisasi_belanja=0;  
                        $realisasi_pendapatan=0;
                        
                    }
                }
                else
                {
                    $hsl_stanggaran=1;
                    $total_belanja=0;
                    $total_pendapatan=0;
                    $realisasi_belanja=0;  
                    $realisasi_pendapatan=0;
                    $serapan_belanja=0;
                    $bulan_tampil="-";
                }

                if($total_belanja==0){$persen_total_belanja=0;}
                else{$persen_total_belanja = round(($realisasi_belanja / $total_belanja * 100), 2);}

                if($total_pendapatan==0){$persen_total_pendapatan=0;}
                else{$persen_total_pendapatan = round(($realisasi_pendapatan / $total_pendapatan * 100), 2);}
                
                $tampil_pendapatan = "<table class='table-detail' style='width:100%;'>
                    <tr><td>Target</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($total_pendapatan) . "</td></tr>
                    <tr><td>Realisasi</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($realisasi_pendapatan) . "</td></tr>
                    <tr><td>Persen</td><td style='text-align:right; font-weight:bold;'>" . $persen_total_pendapatan . "%</td></tr>
                </table>";

                $tampil_belanja = "<table class='table-detail' style='width:100%;'>
                    <tr><td>Anggaran</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($total_belanja) . "</td></tr>
                    <tr><td>Realisasi</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($realisasi_belanja) . "</td></tr>
                    <tr><td>Persen</td><td style='text-align:right; font-weight:bold;'>" . $persen_total_belanja . "%</td></tr>
                </table>";

                $no++;
                $row = [
                    'no' => $no,
                    'nama_skpd' => $nama_skpd,
                    'bulan' => $bulan_tampil,
                    'pendapatan' => $tampil_pendapatan,
                    'belanja' => $tampil_belanja
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
        $id_skpd = decrypt_url($encrypt_id);
        $skpd = $this->mquery->select_id('data_skpd_tahun', ['id_skpd' => $id_skpd, 'tahun'=>$tahun]);
        $data = [
            "menu_active" => "data-apbd",
            "submenu_active" => NULL,
            "skpd" => $skpd,
            "tahun" => $tahun
        ];
        $this->load->view('daerah/data_apbd/view_detail', $data);
    }

    public function realisasi_pad_opd()
    {
        $tahun = $this->input->post('tahun');
        $id_skpd = $this->input->post('id_skpd');
        $row_skpd = $this->mquery->select_id('data_skpd_tahun', ['id_skpd' => $id_skpd, 'tahun'=>$tahun]);

        $cek_data = $this->mquery->count_data('data_realisasi_detail_skpd', ['id_skpd' => $id_skpd, 'tahun'=>$tahun]);
        $cek_max = $this->mquery->max_data_where('data_realisasi_detail_skpd', 'bulan', ['id_skpd' => $id_skpd, 'tahun'=>$tahun]);
        $cek_log_upload = $this->mquery->max_data_where('log_upload', 'bulan', ['id_skpd' => $id_skpd, 'tahun'=>$tahun, 'st_data'=>2]);
        
        $tgl_now=date('Y-m-d');
        $cek_setting = $this->mquery->count_data('setting_anggaran', ['tahun'=>$tahun]);
        if($cek_setting!=0)
        {
            $row_pendapatan = $this->mquery->select_id('setting_anggaran', ['tahun' => $tahun]);
            $tgl_papbd=$row_pendapatan['papbd'];
            if($tgl_now<$tgl_papbd){$st_anggaran=1;}
            else{$st_anggaran=2;}
        }else{$st_anggaran=1;}

        $tahun_now=date('Y');
        if($tahun==$tahun_now)
        {
            $cek_max = $this->mquery->max_data_where('data_realisasi_detail_skpd', 'bulan', ['id_skpd' => $id_skpd, 'tahun'=>$tahun]);
            $bulan=$cek_max['bulan'];
        }
        else
        {
            $st_anggaran=2;
            if($cek_setting!=0){$bulan=12; }
            else{$bulan=0; }
        }

        $cek_row_pendapatan = $this->mquery->count_data('data_uraian_kegiatan_skpd', ['id_skpd' => $id_skpd, 'kode_rekening' => 4, 'st_anggaran' => $st_anggaran, 'tahun' => $tahun]);
        if($cek_row_pendapatan!=0)
        {
            $row_pendapatan = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $id_skpd, 'kode_rekening' => 4, 'st_anggaran' => $st_anggaran, 'tahun' => $tahun]);
            $anggaran_pendapatan=$row_pendapatan['anggaran'];
        }else{$anggaran_pendapatan=0;}
        
        $cek_data_pad = $this->mquery->count_data('data_uraian_kegiatan_skpd', ['id_skpd' => $id_skpd, 'kode_rekening' => 4.1, 'st_anggaran' => $st_anggaran, 'tahun' => $tahun]);
        if($cek_data_pad!=0)
        {
            $row_data_pad = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $id_skpd, 'kode_rekening' => 4.1, 'st_anggaran' => $st_anggaran, 'tahun' => $tahun]);
            $anggaran_pad=$row_data_pad['anggaran'];
        }else{$anggaran_pad=0;}

        if($cek_data!=0)
        {
            $row_log_upload = $this->mquery->select_id('log_upload', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $cek_log_upload['bulan']]);
            $periode = $this->fungsi->nama_bulan($row_log_upload['tgl_data']);
        }
        else
        {
            $periode = "";
        }

        if($anggaran_pad==0){$target_pad_bulanan=0;}
        else{$target_pad_bulanan=$anggaran_pad/12;}

        $batas=$bulan+1;
        $nama_bulan= null;
        $arr_pendapatan_pad= null;
        $realisasi_pad=0;
        $arr_target_pad= null;
        $get_target_pad=0;
        $persen_realisasi_pad=0;

        for ($i23 = 1; $i23 < $batas; $i23++)
        {
            $nama_bulan .= "'" . bulan($i23) . "',";
            $get_target_pad=$get_target_pad+$target_pad_bulanan;
            $arr_target_pad .=$get_target_pad . ",";

            $cek_pendapatan_pad = $this->mquery->count_data('data_realisasi_detail_skpd', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $i23, 'kode_rekening' => 4.1]);
            if($cek_pendapatan_pad!=0)
            {
                $row_pendapatan_pad = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $i23, 'kode_rekening' => 4.1]);
                $row_realisasi_pendapatan_pad=$row_pendapatan_pad['realisasi'];
            }else{$row_realisasi_pendapatan_pad=0;}
            
            if(empty($row_realisasi_pendapatan_pad)){$arr_pendapatan_pad .= "0,";}
            else{$arr_pendapatan_pad .= $row_realisasi_pendapatan_pad . ",";}

            if($i23==$bulan)
            {
                $realisasi_pad=$row_realisasi_pendapatan_pad;
                if($anggaran_pad==0){$persen_realisasi_pad=0;}
                else{$persen_realisasi_pad = round(($realisasi_pad / $anggaran_pad * 100), 2);}
            }
        }
        
        $data = [
            "menu_active" => "data-apbd",
            "submenu_active" => NULL,
            "periode" => $periode,
            "row_skpd" => $row_skpd,
            "tahun" => $tahun,
            "nama_bulan" => $nama_bulan,
            "arr_pendapatan_pad" => $arr_pendapatan_pad,
            "anggaran_pendapatan" => $anggaran_pendapatan,
            "anggaran_pad" => $anggaran_pad,
            "realisasi_pad" => $realisasi_pad,
            "persen_realisasi_pad" => $persen_realisasi_pad,
            "arr_target_pad" => $arr_target_pad
        ];
        $this->load->view('widget/skpd/realisasi_pad_opd', $data);
    }
    
    public function realisasi_belanja_opd()
    {
        $tahun = $this->input->post('tahun');
        $id_skpd = $this->input->post('id_skpd');
        $row_skpd = $this->mquery->select_id('data_skpd_tahun', ['id_skpd' => $id_skpd, 'tahun'=>$tahun]);

        $cek_data = $this->mquery->count_data('data_realisasi_detail_skpd', ['id_skpd' => $id_skpd, 'tahun'=>$tahun]);
        $cek_max = $this->mquery->max_data_where('data_realisasi_detail_skpd', 'bulan', ['id_skpd' => $id_skpd, 'tahun'=>$tahun]);
        $cek_log_upload = $this->mquery->max_data_where('log_upload', 'bulan', ['id_skpd' => $id_skpd, 'tahun'=>$tahun, 'st_data'=>2]);

        $tgl_now=date('Y-m-d');
        $cek_setting = $this->mquery->count_data('setting_anggaran', ['tahun'=>$tahun]);
        if($cek_setting!=0)
        {
            $row_pendapatan = $this->mquery->select_id('setting_anggaran', ['tahun' => $tahun]);
            $tgl_papbd=$row_pendapatan['papbd'];
            if($tgl_now<$tgl_papbd){$st_anggaran=1;}
            else{$st_anggaran=2;}
        }else{$st_anggaran=1;}

        $tahun_now=date('Y');
        if($tahun==$tahun_now)
        {
            $cek_max = $this->mquery->max_data_where('data_realisasi_detail_skpd', 'bulan', ['id_skpd' => $id_skpd, 'tahun'=>$tahun]);
            $bulan=$cek_max['bulan'];
        }
        else
        {
            $st_anggaran=2;
            if($cek_setting!=0){$bulan=12; }
            else{$bulan=0; }
        }

        $cek_belanja = $this->mquery->count_data('data_uraian_kegiatan_skpd', ['id_skpd' => $id_skpd, 'kode_rekening' => 5, 'st_anggaran' => $st_anggaran, 'tahun' => $tahun]);
        if($cek_belanja!=0)
        {
            $row_belanja = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $id_skpd, 'kode_rekening' => 5, 'st_anggaran' => $st_anggaran, 'tahun' => $tahun]);
            $anggaran_belanja=$row_belanja['anggaran'];
        }else{$anggaran_belanja=0;}
        
        $cek_bel_operasi = $this->mquery->count_data('data_uraian_kegiatan_skpd', ['id_skpd' => $id_skpd, 'kode_rekening' => 5.1, 'st_anggaran' => $st_anggaran, 'tahun' => $tahun]);
        if($cek_bel_operasi!=0)
        {
            $row_operasi = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $id_skpd, 'kode_rekening' => 5.1, 'st_anggaran' => $st_anggaran, 'tahun' => $tahun]);
            $belanja_operasi=$row_operasi['anggaran'];
        }else{$belanja_operasi=0;}
        
        $cek_bel_modal = $this->mquery->count_data('data_uraian_kegiatan_skpd', ['id_skpd' => $id_skpd, 'kode_rekening' => 5.2, 'st_anggaran' => $st_anggaran, 'tahun' => $tahun]);
        if($cek_bel_modal!=0)
        {
            $row_modal = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $id_skpd, 'kode_rekening' => 5.2, 'st_anggaran' => $st_anggaran, 'tahun' => $tahun]);
            $belanja_modal=$row_modal['anggaran'];
        }else{$belanja_modal=0;}
        

        $cek_bel_takterduga = $this->mquery->count_data('data_uraian_kegiatan_skpd', ['id_skpd' => $id_skpd, 'kode_rekening' => 5.3, 'st_anggaran' => $st_anggaran, 'tahun' => $tahun]);
        if($cek_bel_takterduga!=0)
        {
            $row_takterduga = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $id_skpd, 'kode_rekening' => 5.3, 'st_anggaran' => $st_anggaran, 'tahun' => $tahun]);
            $belanja_takterduga=$row_takterduga['anggaran'];
        }else{$belanja_takterduga=0;}
        
        $cek_bel_transfer = $this->mquery->count_data('data_uraian_kegiatan_skpd', ['id_skpd' => $id_skpd, 'kode_rekening' => 5.4, 'st_anggaran' => $st_anggaran, 'tahun' => $tahun]);
        if($cek_bel_transfer!=0)
        {
            $row_transfer = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $id_skpd, 'kode_rekening' => 5.4, 'st_anggaran' => $st_anggaran, 'tahun' => $tahun]);
            $belanja_transfer=$row_transfer['anggaran'];
        }else{$belanja_transfer=0;}
        
        if($cek_data!=0)
        {
            $row_log_upload = $this->mquery->select_id('log_upload', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $cek_log_upload['bulan']]);
            $periode = $this->fungsi->nama_bulan($row_log_upload['tgl_data']);
        }
        else
        {
            $periode = "";
        }

        $batas=$bulan+1;
        $nama_bulan= null;
        $arr_belanja= null;
        $realisasi_belanja=0;
        $persen_realisasi_belanja=0;
        $realisasi_operasi=0;
        $realisasi_modal=0;
        $persen_realisasi_operasi=0;
        $persen_realisasi_modal=0;
        $persen_realisasi_takterduga=0;
        $realisasi_takterduga=0;
        $realisasi_transfer=0;
        $persen_realisasi_transfer=0;

        for ($i23 = 1; $i23 < $batas; $i23++)
        {
            $nama_bulan .= "'" . bulan($i23) . "',";
            
            $cek_row_belanja = $this->mquery->count_data('data_realisasi_detail_skpd', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $i23, 'kode_rekening' => 5]);
            if($cek_row_belanja!=0)
            {
                $row_belanja = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $i23, 'kode_rekening' => 5]);
                $row_belanja_realisasi=$row_belanja['realisasi'];
            }
            else{$row_belanja_realisasi=0;}

            if(empty($row_belanja_realisasi)){$arr_belanja .= "0,";}
            else{$arr_belanja .= $row_belanja_realisasi . ",";}

            if($i23==$bulan)
            {
                $realisasi_belanja=$row_belanja_realisasi;
                if($anggaran_belanja==0){$persen_realisasi_belanja=0;}
                else{$persen_realisasi_belanja = round(($realisasi_belanja / $anggaran_belanja * 100), 2);}

                $cek_operasi = $this->mquery->count_data('data_realisasi_detail_skpd', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $i23, 'kode_rekening' => 5.1]);
                if($cek_operasi!=0)
                {
                    $row_operasi = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $i23, 'kode_rekening' => 5.1]);
                    $realisasi_operasi=$row_operasi['realisasi'];
                }else{$realisasi_operasi=0;}
                
                if($belanja_operasi==0){$persen_realisasi_operasi=0;}
                else{$persen_realisasi_operasi = round(($realisasi_operasi / $belanja_operasi * 100), 2);}
                
                $cek_modal = $this->mquery->count_data('data_realisasi_detail_skpd', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $i23, 'kode_rekening' => 5.2]);
                if($cek_modal!=0)
                {
                    $row_modal = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $i23, 'kode_rekening' => 5.2]);
                    $realisasi_modal=$row_modal['realisasi'];
                }else{$realisasi_modal=0;}
                
                if($belanja_modal==0){$persen_realisasi_modal=0;}
                else{$persen_realisasi_modal = round(($realisasi_modal / $belanja_modal * 100), 2);}
                
                $cek_takterduga = $this->mquery->count_data('data_realisasi_detail_skpd', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $i23, 'kode_rekening' => 5.3]);
                if($cek_takterduga!=0)
                {
                    $row_takterduga = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $i23, 'kode_rekening' => 5.3]);
                    $realisasi_takterduga=$row_takterduga['realisasi'];
                }else{$realisasi_takterduga=0;}
                
                if($belanja_takterduga==0){$persen_realisasi_takterduga=0;}
                else{$persen_realisasi_takterduga = round(($realisasi_takterduga / $belanja_takterduga * 100), 2);}
                
                $cek_transfer = $this->mquery->count_data('data_realisasi_detail_skpd', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $i23, 'kode_rekening' => 5.4]);
                if($cek_transfer!=0)
                {
                    $row_transfer = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $i23, 'kode_rekening' => 5.4]);
                    $realisasi_transfer=$row_transfer['realisasi'];
                }else{$realisasi_transfer=0;}
                
                if($belanja_transfer==0){$persen_realisasi_transfer=0;}
                else{$persen_realisasi_transfer = round(($realisasi_transfer / $belanja_transfer * 100), 2);}
            }
        }

        
        if($anggaran_belanja==0)
        {
            $persen_operasi=0;
            $persen_modal=0;
            $persen_takterduga=0;
            $persen_beltransfer=0;
        }
        else
        {
            $persen_operasi = round(($belanja_operasi / $anggaran_belanja * 100), 2);
            $persen_modal=round(($belanja_modal / $anggaran_belanja * 100), 2);
            $persen_takterduga=round(($belanja_takterduga / $anggaran_belanja * 100), 2);
            $persen_beltransfer=round(($belanja_transfer / $anggaran_belanja * 100), 2);
        }
        
        $data = [
            "menu_active" => "data-apbd",
            "submenu_active" => NULL,
            "periode" => $periode,
            "row_skpd" => $row_skpd,
            "tahun" => $tahun,
            "nama_bulan" => $nama_bulan,
            "arr_belanja" => $arr_belanja,
            "anggaran_belanja" => $anggaran_belanja,
            "belanja_operasi" => $belanja_operasi,
            "belanja_modal" => $belanja_modal,
            "belanja_takterduga" => $belanja_takterduga,
            "belanja_transfer" => $belanja_transfer,
            "realisasi_belanja" => $realisasi_belanja,
            "persen_realisasi_belanja" => $persen_realisasi_belanja,
            "persen_operasi" => $persen_operasi,
            "persen_modal" => $persen_modal,
            "persen_takterduga" => $persen_takterduga,
            "persen_beltransfer" => $persen_beltransfer,
            "realisasi_operasi" => $realisasi_operasi,
            "persen_realisasi_operasi" => $persen_realisasi_operasi,
            "realisasi_modal" => $realisasi_modal,
            "persen_realisasi_modal" => $persen_realisasi_modal,
            "realisasi_takterduga" => $realisasi_takterduga,
            "persen_realisasi_takterduga" => $persen_realisasi_takterduga,
            "realisasi_transfer" => $realisasi_transfer,
            "persen_realisasi_transfer" => $persen_realisasi_transfer

        ];
        $this->load->view('widget/skpd/realisasi_belanja_opd', $data);
    }
    
    public function arus_kas_opd()
    {
        $tahun = $this->input->post('tahun');
        $id_skpd = $this->input->post('id_skpd');
        $row_skpd = $this->mquery->select_id('data_skpd_tahun', ['id_skpd' => $id_skpd, 'tahun'=>$tahun]);

        $cek_data = $this->mquery->count_data('data_realisasi_detail_skpd', ['id_skpd' => $id_skpd, 'tahun'=>$tahun]);
        $cek_max = $this->mquery->max_data_where('data_realisasi_detail_skpd', 'bulan', ['id_skpd' => $id_skpd, 'tahun'=>$tahun]);
        $cek_log_upload = $this->mquery->max_data_where('log_upload', 'bulan', ['id_skpd' => $id_skpd, 'tahun'=>$tahun, 'st_data'=>2]);
        
        if($cek_data!=0)
        {
            $row_log_upload = $this->mquery->select_id('log_upload', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $cek_log_upload['bulan']]);
            $periode = $this->fungsi->nama_bulan($row_log_upload['tgl_data']);
        }
        else
        {
            $periode = "";
        }

        $tgl_now=date('Y-m-d');
        $cek_setting = $this->mquery->count_data('setting_anggaran', ['tahun'=>$tahun]);
        if($cek_setting!=0)
        {
            $row_pendapatan = $this->mquery->select_id('setting_anggaran', ['tahun' => $tahun]);
            $tgl_papbd=$row_pendapatan['papbd'];
            if($tgl_now<$tgl_papbd){$st_anggaran=1;}
            else{$st_anggaran=2;}
        }else{$st_anggaran=1;}

        $tahun_now=date('Y');
        if($tahun==$tahun_now)
        {
            $cek_max = $this->mquery->max_data_where('data_realisasi_detail_skpd', 'bulan', ['id_skpd' => $id_skpd, 'tahun'=>$tahun]);
            $bulan=$cek_max['bulan'];
        }
        else
        {
            $st_anggaran=2;
            if($cek_setting!=0){$bulan=12; }
            else{$bulan=0; }
        }

        $batas=$bulan+1;
        $nama_bulan= null;
        $arr_belanja= null;
        $arr_arus_kas= null;
        $total_arus_kas=0;
        $h_tri=array();

        for ($i23 = 1; $i23 < $batas; $i23++)
        {
            $nama_bulan .= "'" . bulan($i23) . "',";

            $cek_row_belanja = $this->mquery->count_data('data_realisasi_detail_skpd', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $i23, 'kode_rekening' => 5]);
            if($cek_row_belanja!=0)
            {
                $row_belanja = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $i23, 'kode_rekening' => 5]);
                $row_belanja_realisasi=$row_belanja['realisasi'];
            }
            else{$row_belanja_realisasi=0;}

            if(empty($row_belanja_realisasi)){$arr_belanja .= "0,";}
            else{$arr_belanja .= $row_belanja_realisasi . ",";}
            $h_tri[$i23]=$row_belanja_realisasi;
        
            $row_arus_kas = $this->mquery->sum_data('anggarankas_detail', 'nilai', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $i23]);
            $total_arus_kas=$total_arus_kas+$row_arus_kas['nilai'];
            $arr_arus_kas .= $total_arus_kas . ",";
        }

        $arr_arus_kas_triwulan= null;
        $total_arus_kas=0;
        $a_tri=array();
        for ($i2 = 1; $i2 < 13; $i2++)
        {
            $row_arus_kas = $this->mquery->sum_data('anggarankas_detail', 'nilai', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $i2]);
            $total_arus_kas=$total_arus_kas+$row_arus_kas['nilai'];
            if($i2==3){$a_tri[1]=$total_arus_kas;}
            if($i2==6){$a_tri[2]=$total_arus_kas;}
            if($i2==9){$a_tri[3]=$total_arus_kas;}
            if($i2==12){$a_tri[4]=$total_arus_kas;}
        }

        if($bulan<4){$arr_arus_kas_triwulan .= $a_tri[1];}
        elseif($bulan<7){$arr_arus_kas_triwulan .= $a_tri[1].",".$a_tri[2];}
        elseif($bulan<10){$arr_arus_kas_triwulan .= $a_tri[1].",".$a_tri[2].",".$a_tri[3];}
        else{$arr_arus_kas_triwulan .= $a_tri[1].",".$a_tri[2].",".$a_tri[3].",".$a_tri[4];}

        $arr_target3= null;
        if($bulan==1){$arr_target3 .= $h_tri[1];}
        elseif($bulan==2){$arr_target3 .= $h_tri[2];}
        elseif($bulan==3){$arr_target3 .= $h_tri[3];}
        elseif($bulan==4){$arr_target3 .= $h_tri[3] . ","; $arr_target3 .= $h_tri[4] . ",";}
        elseif($bulan==5){$arr_target3 .= $h_tri[3] . ","; $arr_target3 .= $h_tri[5] . ",";}
        elseif($bulan==6){$arr_target3 .= $h_tri[3] . ","; $arr_target3 .= $h_tri[6] . ",";}
        elseif($bulan==7)
        {
            $arr_target3 .= $h_tri[3] . ","; 
            $arr_target3 .= $h_tri[6] . ",";
            $arr_target3 .= $h_tri[7] . ",";
        }
        elseif($bulan==8)
        {
            $arr_target3 .= $h_tri[3] . ","; 
            $arr_target3 .= $h_tri[6] . ",";
            $arr_target3 .= $h_tri[8] . ",";
        }
        elseif($bulan==9)
        {
            $arr_target3 .= $h_tri[3] . ","; 
            $arr_target3 .= $h_tri[6] . ",";
            $arr_target3 .= $h_tri[9] . ",";
        }
        elseif($bulan==10)
        {
            $arr_target3 .= $h_tri[3] . ","; 
            $arr_target3 .= $h_tri[6] . ",";
            $arr_target3 .= $h_tri[9] . ",";
            $arr_target3 .= $h_tri[10] . ",";
        }
        elseif($bulan==11)
        {
            $arr_target3 .= $h_tri[3] . ","; 
            $arr_target3 .= $h_tri[6] . ",";
            $arr_target3 .= $h_tri[9] . ",";
            $arr_target3 .= $h_tri[11] . ",";
        }
        elseif($bulan==12)
        {
            $arr_target3 .= $h_tri[3] . ","; 
            $arr_target3 .= $h_tri[6] . ",";
            $arr_target3 .= $h_tri[9] . ",";
            $arr_target3 .= $h_tri[12] . ",";
        }
        
        $data = [
            "menu_active" => "data-apbd",
            "submenu_active" => NULL,
            "periode" => $periode,
            "row_skpd" => $row_skpd,
            "tahun" => $tahun,
            "nama_bulan" => $nama_bulan,
            "arr_belanja" => $arr_belanja,
            "arr_arus_kas" => $arr_arus_kas,
            "arr_arus_kas_triwulan" => $arr_arus_kas_triwulan,
            "arr_target3" => $arr_target3
        ];
        $this->load->view('widget/skpd/arus_kas_opd', $data);
    }


}

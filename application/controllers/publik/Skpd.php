<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Skpd extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Belanja_model', 'belanja');
        $this->load->model('M_fungsi', 'fungsi');
    }

    public function index()
    {
        $tahun=2021;
        $cek_max = $this->mquery->max_data_where('data_realisasi_detail_skpd', 'bulan', ['tahun'=>$tahun]);
        $cek_log_upload = $this->mquery->max_data_where('log_upload', 'bulan', ['tahun'=>$tahun, 'st_data'=>2]);
        $row_log_upload = $this->mquery->select_id('log_upload', ['tahun' => $tahun, 'bulan' => $cek_log_upload['bulan']]);
        $periode = $this->fungsi->nama_bulan($row_log_upload['tgl_data']);

        $data = [
            "menu_active" => "belanja_skpd",
            "submenu_active" => null,
            "periode" => $periode
        ];
        $this->load->view('belanja/skpd/view', $data);
    }

    public function load()
    {
        $tahun=2021;
        $cek_max = $this->mquery->max_data_where('data_realisasi_detail_skpd', 'bulan', ['tahun'=>$tahun]);
        $cek_log_upload = $this->mquery->max_data_where('log_upload', 'bulan', ['tahun'=>$tahun, 'st_data'=>2]);
        $row_log_upload = $this->mquery->select_id('log_upload', ['tahun' => $tahun, 'bulan' => $cek_log_upload['bulan']]);
        $periode = $this->fungsi->nama_bulan($row_log_upload['tgl_data']);

        $result = $this->mquery->select_by('data_realisasi_detail_skpd', ['bulan' =>  $cek_max['bulan'], 'tahun'=>$tahun, 'kode_rekening' => 5], 'persen DESC');
        $data = [];
        $no = 0;
        
        $bulan=date('m');
        $cek_papbd = $this->mquery->select_id('setting_anggaran', ['id_setting' => 1]);
        $tgl_papbd=$cek_papbd['papbd'];
        $temp_bulan=substr($tgl_papbd,5,2);
        $hsl_bulan=intval($temp_bulan);
        if($bulan<$hsl_bulan){$st_anggaran=1;}
        else{$st_anggaran=2;}

        foreach ($result as $r) {
            $encrypt_id = encrypt_url($r['id_skpd']);
            $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $r['id_skpd']]);
            $nama_skpd = "<a href=" . base_url("belanja/skpd/detail/" . $encrypt_id) . ">" . $skpd['nama_skpd'] . "</a>";
            
            if($skpd['acuan_belanja']==0)
            {
                $row_anggaran = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['tahun' => $tahun, 'id_skpd' => $r['id_skpd'], 'kode_rekening' => 5, 'st_anggaran' => $st_anggaran]);
                $hsl_belanja=$row_anggaran['anggaran'];
            }
            else{$hsl_belanja=$skpd['belanja'];}

                $persen=round(($r['realisasi']/$hsl_belanja*100),2);
                $no++;
                $row = [
                    'no' => $no,
                    'nama_skpd' => $nama_skpd,
                    'pagu' => "Rp" . number_format($hsl_belanja, 0, ',', '.'),
                    'realisasi' => "Rp" . number_format($r['realisasi'], 0, ',', '.'),
                    'npersen' => $persen ." %"
                ];
                $data[] = $row;
        }
            $output['data'] = $data;
            echo json_encode($output);
       
    }

    public function detail($tahun, $encrypt_id)
    {
        $id_skpd = decrypt_url($encrypt_id);
        $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
        $result_daerah = $this->mquery->select_id("ta_kabupaten",['id_kabupaten'=>34]);
        $data = [
            "skpd" => $skpd,
            "tahun" => $tahun,
            "nama_kabupaten" => $result_daerah['nama_kabupaten']
        ];
        $this->load->view('publik/skpd_new/view_detail', $data);
    }

    public function load_kegiatan()
    {
        $id_skpd = $this->input->post('skpd');
        $tahun = $this->input->post('tahun_data');
        $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
        $result = $this->mquery->select_by('ta_kontrak', ['tahun' => $tahun, 'kd_urusan' => $skpd['kd_urusan'],'kd_bidang' => $skpd['kd_bidang'],'kd_unit' => $skpd['kd_unit'],'kd_sub' => $skpd['kd_sub']], 'id_prioritas DESC, id_kegiatan ASC');
        $data = [];
        $no = 0;
        $realisasi_total=0;
        $nilai_total=0;
        $fisik_total=0;
        $jml_input_data=0;
        foreach ($result as $r) {
            $no++;
            $encrypt_id = encrypt_url($r['id_kontrak']);
            $jml_kontrak_pa = $this->mquery->count_data('ta_kontrak_pa', ['id_kontrak' => $r['id_kontrak']]);
            
            if($jml_kontrak_pa==0){$nama_pa="";}
            else{
                $kontrak_pa = $this->mquery->select_id('ta_kontrak_pa', ['id_kontrak' => $r['id_kontrak']]);
                $nama_pa=$kontrak_pa['nama_pa'];
            }

            $no_kontrak=$r['no_kontrak'];

            $hit_kontrak_real = $this->mquery->count_data('data_kontrak_real', ['id_kontrak' => $r['id_kontrak']]);
            if($hit_kontrak_real==0){$realisasi=0;}
            {
                $sum_kontrak_real = $this->mquery->sum_data('data_kontrak_real', 'nilai', ['id_kontrak' => $r['id_kontrak']]);
                $realisasi=$sum_kontrak_real['nilai'];
                $realisasi_total=$realisasi_total+$realisasi;
            }

            if($r['nilai']==0){$persen_real=0;}
            else{
                $persen_real=hitung_persen($realisasi, $r['nilai'], 2);
            }

            $jml_fisik = $this->mquery->count_data('data_kegiatan_detail', ['id_kegiatan' => $r['id_kontrak']]);
            
            if($jml_fisik==0){$realisasi_fisik=0;}
            else{
                $max_realisasi = $this->mquery->max_data_where('data_kegiatan_detail', 'realisasi', ['id_kegiatan' => $r['id_kontrak']]);
                $realisasi_fisik=$max_realisasi['realisasi'];
                $jml_input_data++;
            }

            if($realisasi_fisik>=$persen_real){$tamp_realisasi_fisik="<button class='btn btn-success btn-sm'>".$realisasi_fisik." %</button>";}
            else {$tamp_realisasi_fisik="<button class='btn btn-danger btn-sm'>".$realisasi_fisik." %</button>";}

            if($jml_fisik==0){$tamp_realisasi_fisik="-";}

            $data_kontrak="Nomor : ".$r['no_kontrak']."<br>Tanggal : ".substr($r['tgl_kontrak'],0,10);
            $nilai_total=$nilai_total+$r['nilai'];
            $fisik_total=$fisik_total+$realisasi_fisik;

            
            $keperluan=$r['keperluan'];
            if($r['id_prioritas']!=0)
            {
                if($r['id_prioritas']==1){$keperluan=$keperluan." <img src=".cek_file('images/1.png')." height='20';>";}
                elseif($r['id_prioritas']==2){$keperluan=$keperluan." <img src=".cek_file('images/2.png')." height='20';>";}
                elseif($r['id_prioritas']==3){$keperluan=$keperluan." <img src=".cek_file('images/3.png')." height='20';>";}
                elseif($r['id_prioritas']==4){$keperluan=$keperluan." <img src=".cek_file('images/4.png')." height='20';>";}
                elseif($r['id_prioritas']==5){$keperluan=$keperluan." <img src=".cek_file('images/5.png')." height='20';>";}
                elseif($r['id_prioritas']==6){$keperluan=$keperluan." <img src=".cek_file('images/6.png')." height='20';>";}
                elseif($r['id_prioritas']==7){$keperluan=$keperluan." <img src=".cek_file('images/7.png')." height='20';>";}
                elseif($r['id_prioritas']==8){$keperluan=$keperluan." <img src=".cek_file('images/8.png')." height='20';>";}
                
            }
            $row = [
                'no' => $no,
                'keperluan' => $keperluan,
                'waktu' => $r['waktu'],
                'nilai' => 'Rp '.format_rupiah($r['nilai']),
                'realisasi' => format_rupiah($realisasi)."<br>Persen : ".$persen_real." %",
                'persen' => $tamp_realisasi_fisik
            ];
            $data[] = $row;
        }
        if($no==0){$persen_fisik=0;}else
        {
            $persen_fisik=round(($fisik_total/$no),2);
        }
        if($nilai_total==0){$persen_total=0;}
        else{$persen_total=round(($realisasi_total/$nilai_total*100),2);}
        $array_update =  [
            'realisasi' => $realisasi_total,
            'persen_realisasi' => $persen_total,
            'persen_fisik' => $persen_fisik,
            'jml_input' => $jml_input_data
        ];

        $this->db->update('data_skpd', $array_update, ['id_skpd'=>$id_skpd]);
        
        $output['data'] = $data;
        echo json_encode($output);
    }

}

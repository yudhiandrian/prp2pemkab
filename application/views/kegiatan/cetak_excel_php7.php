<?php 
   
$bulan = array(
    '01' => 'Jan',
    '02' => 'Feb',
    '03' => 'Mar',
    '04' => 'Apr',
    '05' => 'Mei',
    '06' => 'Jun',
    '07' => 'Jul',
    '08' => 'Agu',
    '09' => 'Sep',
    '10' => 'Okt',
    '11' => 'Nov',
    '12' => 'Des',
);
// echo $id_pertemuan."<br>";

$object = new PHPExcel();
// Set document properties
$object->getProperties()->setCreator("PRP2SUMUT")
 ->setLastModifiedBy("PRP2SUMUT")
 ->setTitle("Presensi")
 ->setSubject("Laporan Kegiatan Fisik")
 ->setDescription("PRP2SUMUT")
 ->setKeywords("office 2007 openxml php")
 ->setCategory("PRP2SUMUT");

$object->setActiveSheetIndex(0)->setTitle("Kegiatan");

$styleArray = array(
    'font'  => array(
        'bold'  => false,
        'color' => array('rgb' => 'FF0000'),
        'size'  => 10,
        'name'  => 'Arial'
    ));

$HeaderstyleArray = array(
        'font'  => array(
            'bold'  => false,
            'color' => array('rgb' => '000000'),
            'size'  => 12,
            'name'  => 'Arial'
        ));

        $styleArrayBorderAll = array(
            'borders' => array(
              'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              )
            )
          );
    
          $styleArrayBorderBottom = array(
              'borders' => array(
                'bottom' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
                )
              )
            );
    
    $HeaderstyleArray = array(
            'font'  => array(
                'bold'  => false,
                'color' => array('rgb' => '000000'),
                'size'  => 12,
                'name'  => 'Arial'
            ));


$object->getActiveSheet()->getStyle('A1:A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('A1:A2')->getFont()->setSize(14)->setBold(true);
$object->getActiveSheet()->mergeCells('A1:M1')->setCellValue('A1', "PEMERINTAH KABUPATEN DELI SERDANG");
$object->getActiveSheet()->getStyle('A1:A2')->getFont()->setSize(14)->setBold(true);
$object->getActiveSheet()->mergeCells('A2:M2')->setCellValue('A2', "DAFTAR KEGIATAN FISIK");
$object->getActiveSheet()->setCellValue('A3', "SKPD : ".$skpd['nama_skpd']);
  
$object->getActiveSheet()->getColumnDimension('A')->setWidth(7);
$object->getActiveSheet()->getColumnDimension('B')->setWidth(7);
$object->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$object->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$object->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$object->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$object->getActiveSheet()->getColumnDimension('G')->setWidth(12);
$object->getActiveSheet()->getColumnDimension('H')->setWidth(15);
$object->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$object->getActiveSheet()->getColumnDimension('J')->setWidth(25);
$object->getActiveSheet()->getColumnDimension('K')->setWidth(7);
$object->getActiveSheet()->getColumnDimension('L')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('M')->setWidth(25);


$object->getActiveSheet()->getRowDimension('5')->setRowHeight(45);
$object->getActiveSheet()->getStyle('A5:M5')->getAlignment()->setWrapText(true); 
$object->getActiveSheet()->getStyle('A5:M5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('A5:M5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$object->getActiveSheet()->getStyle('A3:M5')->getFont()->setSize(12)->setBold(true);

$object->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('K')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('K')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('L')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('M')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$object->getActiveSheet()->setCellValueByColumnAndRow(0, 5, "NO");
$object->getActiveSheet()->setCellValueByColumnAndRow(1, 5, "KODE");
$object->getActiveSheet()->setCellValueByColumnAndRow(2, 5, "NAMA KEGIATAN");
$object->getActiveSheet()->setCellValueByColumnAndRow(3, 5, "PAGU ANGGARAN");
$object->getActiveSheet()->setCellValueByColumnAndRow(4, 5, "TAHUN KONTRAK");
$object->getActiveSheet()->setCellValueByColumnAndRow(5, 5, "NO. KONTRAK");
$object->getActiveSheet()->setCellValueByColumnAndRow(6, 5, "TGL. KONTRAK");
$object->getActiveSheet()->setCellValueByColumnAndRow(7, 5, "NILAI KONTRAK");
$object->getActiveSheet()->setCellValueByColumnAndRow(8, 5, "WAKTU PEKERJAAN");
$object->getActiveSheet()->setCellValueByColumnAndRow(9, 5, "NAMA PERUSAHAAN");
$object->getActiveSheet()->setCellValueByColumnAndRow(10, 5, "DAK (Y/N)");
$object->getActiveSheet()->setCellValueByColumnAndRow(11, 5, "KOORDINAT");
$object->getActiveSheet()->setCellValueByColumnAndRow(12, 5, "LOKASI KEGIATAN");
$object->getActiveSheet()->getStyle('A5:M5')->applyFromArray($styleArrayBorderAll);

$excel_row = 6;
$no=1;
foreach ($ta_kontrak as $r)   
{   
    $target_merge1='A'.$excel_row.':M'.$excel_row;
    $object->getActiveSheet()->getStyle($target_merge1)->applyFromArray($styleArrayBorderAll);
    $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no);
    $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $r['id_kontrak']);
    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $r['keperluan']);
    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $r['pagu']);
    $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $r['tahun']);
    $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $r['no_kontrak']);
    $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, substr($r['tgl_kontrak'],0,10));
    $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $r['nilai']);
    $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $r['waktu']);
    $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $r['nm_perusahaan']);
    $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $r['status_2']);
    $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $r['koordinat']);
    $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $r['lokasi_pekerjaan']);
    $excel_row++;
    $no++;
}

// Halaman Realisasi Fisik

$object->createSheet();
$object->setActiveSheetIndex(1)->setTitle("Realisasi Fisik");

$object->getActiveSheet()->getStyle('A1:A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('A1:A2')->getFont()->setSize(14)->setBold(true);
$object->getActiveSheet()->mergeCells('A1:J1')->setCellValue('A1', "PEMERINTAH KABUPATEN DELI SERDANG");
$object->getActiveSheet()->getStyle('A1:A2')->getFont()->setSize(14)->setBold(true);
$object->getActiveSheet()->mergeCells('A2:J2')->setCellValue('A2', "DATA REALISASI FISIK");
$object->getActiveSheet()->setCellValue('A3', "SKPD : ".$skpd['nama_skpd']);
  
$object->getActiveSheet()->getColumnDimension('A')->setWidth(7);
$object->getActiveSheet()->getColumnDimension('B')->setWidth(7);
$object->getActiveSheet()->getColumnDimension('C')->setWidth(120);
$object->getActiveSheet()->getColumnDimension('D')->setWidth(10);
$object->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$object->getActiveSheet()->getColumnDimension('F')->setWidth(10);
$object->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$object->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$object->getActiveSheet()->getColumnDimension('I')->setWidth(10);
$object->getActiveSheet()->getColumnDimension('J')->setWidth(30);

$object->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true); 
$object->getActiveSheet()->getStyle('A')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$object->getActiveSheet()->getStyle('B')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$object->getActiveSheet()->getStyle('D')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$object->getActiveSheet()->getStyle('E')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$object->getActiveSheet()->getStyle('F')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$object->getActiveSheet()->getStyle('G')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$object->getActiveSheet()->getStyle('H')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$object->getActiveSheet()->getStyle('I')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$object->getActiveSheet()->getStyle('J')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$object->getActiveSheet()->getRowDimension('5')->setRowHeight(45);
$object->getActiveSheet()->getStyle('A5:J5')->getAlignment()->setWrapText(true); 
$object->getActiveSheet()->getStyle('A5:J5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('A5:J5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$object->getActiveSheet()->getStyle('A3:J5')->getFont()->setSize(12)->setBold(true);

$object->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$object->getActiveSheet()->setCellValueByColumnAndRow(0, 5, "NO");
$object->getActiveSheet()->setCellValueByColumnAndRow(1, 5, "KODE");
$object->getActiveSheet()->setCellValueByColumnAndRow(2, 5, "NAMA KEGIATAN");
$object->getActiveSheet()->setCellValueByColumnAndRow(3, 5, "KODE FISIK");
$object->getActiveSheet()->setCellValueByColumnAndRow(4, 5, "JENIS TARGET [H, M, B, T]");
$object->getActiveSheet()->setCellValueByColumnAndRow(5, 5, "TAHAPAN TARGET [Angka]");
$object->getActiveSheet()->setCellValueByColumnAndRow(6, 5, "JADWAL TARGET [Tanggal]");
$object->getActiveSheet()->setCellValueByColumnAndRow(7, 5, "TARGET [%]");
$object->getActiveSheet()->setCellValueByColumnAndRow(8, 5, "REALISASI [%]");
$object->getActiveSheet()->setCellValueByColumnAndRow(9, 5, "KETERANGAN");
$object->getActiveSheet()->getStyle('A5:J5')->applyFromArray($styleArrayBorderAll);

$excel_row = 6;
$no=1;
foreach ($ta_kontrak as $r)   
{   
    $cek_real_fisik = $this->mquery->count_data('data_kegiatan_detail', ['id_kegiatan' => $r['id_kontrak']]);
    if($cek_real_fisik==0)
    {
      $target_merge1='A'.$excel_row.':J'.$excel_row;
      $object->getActiveSheet()->getStyle($target_merge1)->applyFromArray($styleArrayBorderAll);
      $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no);
      $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $r['id_kontrak']);
      $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $r['keperluan']);
      $excel_row++;
      $no++;
    }
    else
    {
      $real_fisik = $this->mquery->select_by('data_kegiatan_detail', ['id_kegiatan' => $r['id_kontrak']]);
      foreach ($real_fisik as $s)   
      {  
        $target_merge1='A'.$excel_row.':J'.$excel_row;
        $object->getActiveSheet()->getStyle($target_merge1)->applyFromArray($styleArrayBorderAll);
        $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no);
        $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $r['id_kontrak']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $r['keperluan']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $s['id_kegiatan_detail']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $s['jenis_target']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $s['tahapan_target']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $s['jadwal_target']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $s['target']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $s['realisasi']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $s['keterangan_target']);
        $excel_row++;
        $no++;
      }
    }
}

// Halaman Realisasi Keuangan

$object->createSheet();
$object->setActiveSheetIndex(2)->setTitle("Realisasi Keuangan");

$object->getActiveSheet()->getStyle('A1:A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('A1:A2')->getFont()->setSize(14)->setBold(true);
$object->getActiveSheet()->mergeCells('A1:G1')->setCellValue('A1', "PEMERINTAH KABUPATEN DELI SERDANG");
$object->getActiveSheet()->getStyle('A1:A2')->getFont()->setSize(14)->setBold(true);
$object->getActiveSheet()->mergeCells('A2:G2')->setCellValue('A2', "DATA REALISASI KEUANGAN");
$object->getActiveSheet()->setCellValue('A3', "SKPD : ".$skpd['nama_skpd']);
  
$object->getActiveSheet()->getColumnDimension('A')->setWidth(7);
$object->getActiveSheet()->getColumnDimension('B')->setWidth(7);
$object->getActiveSheet()->getColumnDimension('C')->setWidth(120);
$object->getActiveSheet()->getColumnDimension('D')->setWidth(10);
$object->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$object->getActiveSheet()->getColumnDimension('F')->setWidth(10);
$object->getActiveSheet()->getColumnDimension('G')->setWidth(15);

$object->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true); 
$object->getActiveSheet()->getStyle('A')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$object->getActiveSheet()->getStyle('B')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$object->getActiveSheet()->getStyle('D')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$object->getActiveSheet()->getStyle('E')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$object->getActiveSheet()->getStyle('F')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$object->getActiveSheet()->getStyle('G')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$object->getActiveSheet()->getRowDimension('5')->setRowHeight(45);
$object->getActiveSheet()->getStyle('A5:G5')->getAlignment()->setWrapText(true); 
$object->getActiveSheet()->getStyle('A5:G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('A5:G5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$object->getActiveSheet()->getStyle('A3:G5')->getFont()->setSize(12)->setBold(true);

$object->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$object->getActiveSheet()->setCellValueByColumnAndRow(0, 5, "NO");
$object->getActiveSheet()->setCellValueByColumnAndRow(1, 5, "KODE");
$object->getActiveSheet()->setCellValueByColumnAndRow(2, 5, "NAMA KEGIATAN");
$object->getActiveSheet()->setCellValueByColumnAndRow(3, 5, "KODE KEUANGAN");
$object->getActiveSheet()->setCellValueByColumnAndRow(4, 5, "TANGGAL REALISASI");
$object->getActiveSheet()->setCellValueByColumnAndRow(5, 5, "REALISASI [RP]");
$object->getActiveSheet()->setCellValueByColumnAndRow(6, 5, "KETERANGAN");
$object->getActiveSheet()->getStyle('A5:G5')->applyFromArray($styleArrayBorderAll);

$excel_row = 6;
$no=1;
foreach ($ta_kontrak as $r)   
{   
    $cek_real_fisik = $this->mquery->count_data('data_kontrak_real', ['id_kontrak' => $r['id_kontrak']]);
    if($cek_real_fisik==0)
    {
      $target_merge1='A'.$excel_row.':G'.$excel_row;
      $object->getActiveSheet()->getStyle($target_merge1)->applyFromArray($styleArrayBorderAll);
      $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no);
      $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $r['id_kontrak']);
      $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $r['keperluan']);
      $excel_row++;
      $no++;
    }
    else
    {
      $real_fisik = $this->mquery->select_by('data_kontrak_real', ['id_kontrak' => $r['id_kontrak']]);
      foreach ($real_fisik as $s)   
      {  
        $target_merge1='A'.$excel_row.':G'.$excel_row;
        $object->getActiveSheet()->getStyle($target_merge1)->applyFromArray($styleArrayBorderAll);
        $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no);
        $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $r['id_kontrak']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $r['keperluan']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $s['id_real']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $s['tgl_realisasi']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $s['nilai']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $s['keterangan']);
        $excel_row++;
        $no++;
      }
    }
}
// Halaman Nama KPA

$object->createSheet();
$object->setActiveSheetIndex(3)->setTitle("Nama KPA");

$object->getActiveSheet()->getStyle('A1:A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('A1:A2')->getFont()->setSize(14)->setBold(true);
$object->getActiveSheet()->mergeCells('A1:E1')->setCellValue('A1', "PEMERINTAH KABUPATEN DELI SERDANG");
$object->getActiveSheet()->getStyle('A1:A2')->getFont()->setSize(14)->setBold(true);
$object->getActiveSheet()->mergeCells('A2:E2')->setCellValue('A2', "DATA REALISASI KEUANGAN");
$object->getActiveSheet()->setCellValue('A3', "SKPD : ".$skpd['nama_skpd']);
  
$object->getActiveSheet()->getColumnDimension('A')->setWidth(7);
$object->getActiveSheet()->getColumnDimension('B')->setWidth(7);
$object->getActiveSheet()->getColumnDimension('C')->setWidth(120);
$object->getActiveSheet()->getColumnDimension('D')->setWidth(30);
$object->getActiveSheet()->getColumnDimension('E')->setWidth(20);

$object->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true); 
$object->getActiveSheet()->getStyle('A')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$object->getActiveSheet()->getStyle('B')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$object->getActiveSheet()->getStyle('D')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$object->getActiveSheet()->getStyle('E')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$object->getActiveSheet()->getRowDimension('5')->setRowHeight(45);
$object->getActiveSheet()->getStyle('A5:E5')->getAlignment()->setWrapText(true); 
$object->getActiveSheet()->getStyle('A5:E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('A5:E5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$object->getActiveSheet()->getStyle('A3:E5')->getFont()->setSize(12)->setBold(true);

$object->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$object->getActiveSheet()->setCellValueByColumnAndRow(0, 5, "NO");
$object->getActiveSheet()->setCellValueByColumnAndRow(1, 5, "KODE");
$object->getActiveSheet()->setCellValueByColumnAndRow(2, 5, "NAMA KEGIATAN");
$object->getActiveSheet()->setCellValueByColumnAndRow(3, 5, "NAMA KPA");
$object->getActiveSheet()->setCellValueByColumnAndRow(4, 5, "NIP KPA");
$object->getActiveSheet()->getStyle('A5:E5')->applyFromArray($styleArrayBorderAll);

$excel_row = 6;
$no=1;
foreach ($ta_kontrak as $r)   
{   
    $cek_real_fisik = $this->mquery->count_data('ta_kontrak_pa', ['id_kontrak' => $r['id_kontrak']]);
    if($cek_real_fisik==0)
    {
      $target_merge1='A'.$excel_row.':E'.$excel_row;
      $object->getActiveSheet()->getStyle($target_merge1)->applyFromArray($styleArrayBorderAll);
      $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no);
      $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $r['id_kontrak']);
      $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $r['keperluan']);
      $excel_row++;
      $no++;
    }
    else
    {
      $real_fisik = $this->mquery->select_id('ta_kontrak_pa', ['id_kontrak' => $r['id_kontrak']]);

      $target_merge1='A'.$excel_row.':E'.$excel_row;
        $object->getActiveSheet()->getStyle($target_merge1)->applyFromArray($styleArrayBorderAll);
        $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no);
        $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $r['id_kontrak']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $r['keperluan']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $real_fisik['nama_pa']);
        $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $real_fisik['nip_pa']);
        $excel_row++;
        $no++;
    }
}

$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Laporan Kegiatan Fisik.xls"');
$object_writer->save('php://output');
?>
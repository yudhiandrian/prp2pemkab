<?php
 header('Content-Type: application/json');
 $hasil_skpd=$this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
 $result = $this->mquery->select_by('ta_kontrak', ['tahun'=> $tahun, 'kd_urusan' => $hasil_skpd['kd_urusan'], 'kd_bidang' => $hasil_skpd['kd_bidang'], 'kd_unit' => $hasil_skpd['kd_unit'], 'kd_sub' => $hasil_skpd['kd_sub']]);

 $geojson = array(
 'type' => 'FeatureCollection',
 'features' => array()
 );

 foreach ($result as $rst) :
    $koordinat=$rst['koordinat'];
    $cek_isi=strlen($koordinat);
    if($cek_isi>10)
    {
        if (strpos($koordinat, ',')) 
        {
            $pieces = explode(",", $koordinat);  
            $latitude=$pieces[0];
            $longitude=$pieces[1];
            $feature = array(
                'type' => 'Feature',
                'geometry' => array(
                    'type' => "Point",
                    'coordinates' => array(
                        '0' => ((double) $longitude),
                        '1' => ((double) $latitude)
                        )
                ),
                'properties' => array(
                'no' => $hasil_skpd['nama_skpd'],
                'nama' => $rst['keperluan'],
                'lokasi' => $rst['lokasi_pekerjaan']
                )
            );
            array_push($geojson['features'], $feature);
        }
    }
endforeach; 
 echo json_encode($geojson);
?>

<?php
	$kordinat=$this->mquery->select_id('tbl_tampilan', ['id_data' => 1]);
	$zoom=$kordinat['zoom'];
	if($zoom==0){$latitude=3.5803759263329638; $longitude=98.6732041289272; $zoom=12;}else{
		$pieces = explode(",", $kordinat['koordinat']); 
		$latitude=$pieces[0];
		$longitude=$pieces[1];
	}
?>
<link rel="stylesheet" href="<?= base_url(); ?>assets/cssmap/bootstrap.min.css" />
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyArO22tHwyNtWsMtBPB2t1_KB1gRcRaM3Q&callback=initMap"></script>
        <script>
          function initialize() 
		  {
			var mapOptions = {
				center: {lat: <?=$latitude?>, lng: <?=$longitude?>},
				zoom: <?=$zoom?>
				};
				map = new google.maps.Map(document.getElementById('map'),  mapOptions);
				peta_proyek = new google.maps.Data();
				peta_proyek.loadGeoJson('<?= site_url('kegiatan/koor_kegiatan/koordinat/'.$tahun); ?>');
				peta_proyek.setMap(map);	
				info_peta_proyek = new google.maps.InfoWindow();
				peta_proyek.addListener('click', function(e) 
				{
					info_peta_proyek.close();
					var nama = e.feature.getProperty('nama');
					var lokasi = e.feature.getProperty('lokasi');
					var no = e.feature.getProperty('no');
					info_peta_proyek.setContent("<b>SKPD</b> : "+no+"<br><b>Nama Kegiatan</b> : "+nama+"<br><b>Lokasi</b> : "+lokasi);
					info_peta_proyek.setPosition(e.latLng);
					info_peta_proyek.setOptions({pixelOffset: new
					google.maps.Size(0,-35)});
					info_peta_proyek.open(map);
				});
      		}
      google.maps.event.addDomListener(window, 'load', initialize);
        </script>
  <div id="map" class="col-map">
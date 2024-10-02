<?php
	$koordinat=$ta_kontrak['koordinat'];
	if(strlen($koordinat)>10)
	{
		$pieces = explode(",", $koordinat);  
		$latitude=$pieces[0];
		$longitude=$pieces[1];
		$zoom=19;
	}
	else
	{ 
		$latitude=2.936025308775414; 
		$longitude=99.00081646225541; 
		$zoom=11;
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
				peta_proyek.loadGeoJson('<?= site_url('kegiatan/koor_kegiatan/koordinat_detail/'.$ta_kontrak['id_kontrak'])?>');
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
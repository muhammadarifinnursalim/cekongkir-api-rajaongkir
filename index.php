<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Cek Ongkir JNE, TIKI dan Pos Indonesia Menggunakan API RajaOngkir">
	<title>Cek Ongkir JNE, TIKI dan Pos Indonesia Menggunakan API RajaOngkir</title>
     
    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
	<link href="css/style.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
  </head>

  <body>
	<header>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="logo">
                    <h1><a href="#">Cek Ongkir <span class="color"></span></a></h1>
                    <div class="hidden-phone hmeta">API Raja Ongkir By Arifin</div>
                </div>
            </div>
            <div class="span8">
            </div>
        </div>
    </div>
	</header>
    <!-- Page Content -->
    <div class="container">
      <div class="row">
        
		<div class="col-md-4">
          <div class="card my-4">
			<h5 class="card-header">CEK ONGKOS KIRIM</h5>
            <div class="card-body">
<?php
			//Get Data Kabupaten
			$curl = curl_init();	
			curl_setopt_array($curl, array(
				CURLOPT_URL => "http://api.rajaongkir.com/starter/city",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
					"key: 6cc12265545f825efbdabfa5d30f74c5"
				),
			));
			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			echo "<label>Kota/Kabupaten Asal</label><br>";
			echo "<select name='asal' id='asal' class='custom-select'>";
			echo "<option>Pilih Kota Asal</option>";
			$data = json_decode($response, true);
			for ($i=0; $i < count($data['rajaongkir']['results']); $i++) { 
				echo "<option value='".$data['rajaongkir']['results'][$i]['city_id']."'>".$data['rajaongkir']['results'][$i]['city_name']."</option>";
			}
			echo "</select><br><br>";
	
			//Get Data Provinsi
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "http://api.rajaongkir.com/starter/province",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
					"key: 6cc12265545f825efbdabfa5d30f74c5"
				),
			));
			$response = curl_exec($curl);
			$err = curl_error($curl);
			
			echo "<label>Provinsi Tujuan</label><br>";
			echo "<select name='provinsi' id='provinsi' class='custom-select'>";
			echo "<option>Pilih Provinsi Tujuan</option>";
			$data = json_decode($response, true);
			for ($i=0; $i < count($data['rajaongkir']['results']); $i++) {
				echo "<option value='".$data['rajaongkir']['results'][$i]['province_id']."'>".$data['rajaongkir']['results'][$i]['province']."</option>";
			}
			echo "</select><br>";
?>
			<label>Kota/Kabupaten Tujuan</label><br>
			<select id="kabupaten" name="kabupaten" class='custom-select'></select><br><br>

			<label>Kurir</label><br>
			<select id="kurir" name="kurir" class='custom-select'>
				<option value="jne">JNE</option>
				<option value="tiki">TIKI</option>
				<option value="pos">POS INDONESIA</option>
			</select><br>

			<label>Berat (Gram)</label><br>
			<input id="berat" type="text" name="berat" value="1000" class="form-control">
			<br>

			<input id="cek" type="submit" value="Cek Ongkir" class="btn btn-success">
            </div>       
          </div>
        </div>
     
        <div class="col-md-8">
		  <div class="card my-4">
            <div class="card-body">
				<div id="loading"><img src="img/ajax-loader.gif"></div>
				<p id="kurirname" style="font-weight:700;text-transform:uppercase;color:green;"></p>
				<table id="details" class="table table-bordered table-responsive"></table>
				<table id="ongkos" class="table table-bordered table-responsive"></table>
				<div id="ongkir"></div>
		   </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Footer -->
    <footer class="py-5 bg-green">
      <div class="container">
        <p class="m-0 text-center text-white"> Developed by  <a href="#">Arifin </a></p>
      </div>
      <!-- /.container -->
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="js/seribu.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$("#loading").hide();
		$('#provinsi').change(function(){
			//Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax 
			var prov = $('#provinsi').val();

      		$.ajax({
            	type : 'GET',
           		url : 'php/cek_kabupaten.php',
            	data :  'prov_id=' + prov,
					success: function (data) {

					//jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
					$("#kabupaten").html(data);
				}
          	});
		});

		$("#cek").click(function(){
			$("#loading").show();
			$("#kurirname").hide();
			$("#details").hide();
			$("#ongkos").hide();
			//Mengambil value dari option select provinsi asal, kabupaten, kurir, berat kemudian parameternya dikirim menggunakan ajax 
			var asal = $('#asal').val();
			var kab = $('#kabupaten').val();
			var kurir = $('#kurir').val();
			var berat = $('#berat').val();
			
      		$.ajax({
            	type : 'POST',
           		url : 'php/cek_ongkir.php',
            	data :  {'kab_id' : kab, 'kurir' : kurir, 'asal' : asal, 'berat' : berat},
					success: function (data) {
						$("#kurirname").show();
						$("#details").show();
						$("#ongkos").show();
						
						var obj=$.parseJSON(data);
						var kurirname=obj['rajaongkir'].results[0].name;
						$("#kurirname").text(kurirname);
						
						var origin=obj['rajaongkir'].origin_details.city_name;
						var destination=obj['rajaongkir'].destination_details.city_name;
						var weight=obj['rajaongkir'].query.weight;
						weight=seribu(weight,",", ".",0);
						$("#details").html('<thead style="background-color:green;color:white;"><tr><th>Kota/Kabupaten Asal</th><th>Kota/Kabupaten Tujuan</th><th>Berat (Gram)</th></tr></thead><tbody><tr><td>'+origin+'</td><td>'+destination+'</td><td>'+weight+'</td></tr></tbody>');
						
						var service=[];
						var description=[];
						var ongkos=[];
						var sampai=[];
						var kurirkode=obj['rajaongkir'].results[0].code;
						
						var n=obj['rajaongkir'].results[0].costs;
						for(i=0;i<n.length;i++){
							service[i]=obj['rajaongkir'].results[0].costs[i].service;
							description[i]=obj['rajaongkir'].results[0].costs[i].description;
							ongkos[i]=obj['rajaongkir'].results[0].costs[i].cost[0].value;
							ongkos[i]=seribu(ongkos[i],",", ".",0);
							
							sampai[i]=obj['rajaongkir'].results[0].costs[i].cost[0].etd;
							if(kurirkode!='pos'){
								sampai[i]=sampai[i]+' HARI';
							}
						}

						if(n.length==1){
							$("#ongkos").html('<thead style="background-color:green;color:white;"><tr><th style="text-align:left">Paket</th><th>Deskripsi</th><th>Lama Pengiriman</th><th style="text-align:right;">Ongkir (Rp)</th></tr></thead><tbody><tr><td style="text-align:left;">'+service[0]+'</td><td>'+description[0]+'</td><td>'+sampai[0]+'</td><td style="text-align:right;">'+ongkos[0]+'</td></tr></tbody>');
						} else if(n.length==2){
							$("#ongkos").html('<thead style="background-color:green;color:white;"><tr><th style="text-align:left">Paket</th><th>Deskripsi</th><th>Lama Pengiriman</th><th style="text-align:right;">Ongkir (Rp)</th></tr></thead><tbody><tr><td style="text-align:left;">'+service[0]+'</td><td>'+description[0]+'</td><td>'+sampai[0]+'</td><td style="text-align:right;">'+ongkos[0]+'</td></tr><tr><td style="text-align:left;">'+service[1]+'</td><td>'+description[1]+'</td><td>'+sampai[1]+'</td><td style="text-align:right;">'+ongkos[1]+'</td></tr></tbody>');
						} else if(n.length==3){
							$("#ongkos").html('<thead style="background-color:green;color:white;"><tr><th style="text-align:left">Paket</th><th>Deskripsi</th><th>Lama Pengiriman</th><th style="text-align:right;">Ongkir (Rp)</th></tr></thead><tbody><tr><td style="text-align:left;">'+service[0]+'</td><td>'+description[0]+'</td><td>'+sampai[0]+'</td><td style="text-align:right;">'+ongkos[0]+'</td></tr><tr><td style="text-align:left;">'+service[1]+'</td><td>'+description[1]+'</td><td>'+sampai[1]+'</td><td style="text-align:right;">'+ongkos[1]+'</td></tr><tr><td style="text-align:left;">'+service[2]+'</td><td>'+description[2]+'</td><td>'+sampai[2]+'</td><td style="text-align:right;">'+ongkos[2]+'</td></tr></tbody>');
						} else if(n.length==4){
							$("#ongkos").html('<thead style="background-color:green;color:white;"><tr><th style="text-align:left">Paket</th><th>Deskripsi</th><th>Lama Pengiriman</th><th style="text-align:right;">Ongkir (Rp)</th></tr></thead><tbody><tr><td style="text-align:left;">'+service[0]+'</td><td>'+description[0]+'</td><td>'+sampai[0]+'</td><td style="text-align:right;">'+ongkos[0]+'</td></tr><tr><td style="text-align:left;">'+service[1]+'</td><td>'+description[1]+'</td><td>'+sampai[1]+'</td><td style="text-align:right;">'+ongkos[1]+'</td></tr><tr><td style="text-align:left;">'+service[2]+'</td><td>'+description[2]+'</td><td>'+sampai[2]+'</td><td style="text-align:right;">'+ongkos[2]+'</td></tr><tr><td style="text-align:left;">'+service[3]+'</td><td>'+description[3]+'</td><td>'+sampai[3]+'</td><td style="text-align:right;">'+ongkos[3]+'</td></tr></tbody>');
						} else if(n.length==5){
							$("#ongkos").html('<thead style="background-color:green;color:white;"><tr><th style="text-align:left">Paket</th><th>Deskripsi</th><th>Lama Pengiriman</th><th style="text-align:right;">Ongkir (Rp)</th></tr></thead><tbody><tr><td style="text-align:left;">'+service[0]+'</td><td>'+description[0]+'</td><td>'+sampai[0]+'</td><td style="text-align:right;">'+ongkos[0]+'</td></tr><tr><td style="text-align:left;">'+service[1]+'</td><td>'+description[1]+'</td><td>'+sampai[1]+'</td><td style="text-align:right;">'+ongkos[1]+'</td></tr><tr><td style="text-align:left;">'+service[2]+'</td><td>'+description[2]+'</td><td>'+sampai[2]+'</td><td style="text-align:right;">'+ongkos[2]+'</td></tr><tr><td style="text-align:left;">'+service[3]+'</td><td>'+description[3]+'</td><td>'+sampai[3]+'</td><td style="text-align:right;">'+ongkos[3]+'</td></tr><tr><td style="text-align:left;">'+service[4]+'</td><td>'+description[4]+'</td><td>'+sampai[4]+'</td><td style="text-align:right;">'+ongkos[4]+'</td></tr></tbody>');
						} else {
							$("#ongkos").html('<thead style="background-color:green;color:white;"><tr><th style="text-align:left">Paket</th><th>Deskripsi</th><th>Lama Pengiriman</th><th style="text-align:right;">Ongkir (Rp)</th></tr></thead><tbody><tr><td style="text-align:left;color:red;">KOSONG</td><td style="color:red;">KOSONG</td><td style="color:red;">KOSONG</td><td style="text-align:right;color:red;">KOSONG</td></tr></tbody>');
						}
						
						//$("#ongkir").text(data);
						$("#loading").hide();
				}
          	});
		});
	});
	</script>
	</body>
</html>

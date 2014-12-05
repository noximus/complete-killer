<?php

	require_once("dompdf/dompdf_config.inc.php");

	$html = '<html><head><style>
			.name {z-index:11; position:absolute; top:643px; left: 740px; font-size: 12px;}
			.address {z-index:11; position:absolute; top:670px; left: 700px;}
			.city {z-index:11; position:absolute; top:697px; left: 670px;}
			.state {z-index:11; position:absolute; top:722px; left: 670px;}
			.zip {z-index:11; position:absolute; top:722px; left: 755px;}
			.email{z-index:11; position:absolute; top:343px; left: 880px;}
			.bgimg { position:absolute;left:0px;top:0px;z-index:-1;}
			body {  }
			</style></head> 
			<body> 
			<div class="holder" >
			<img src="11FLMCRG-Completekiller.jpg" class="bgimg" />
			<div>
			
			<div class="name"  >'. $_POST['ClinicName'] .'</div>
			<div class="address" >'. $_POST['Address'] .'</div>
			<div class="city" >'. $_POST['City'] .'</div>
			<div class="state" >'. $_POST['State'] .'</div>
			<div class="zip" >'. $_POST['ZipCode'] .'</div>
			</body> 
			</html>';

	$filename = preg_replace('/[^a-zA-Z0-9]/', '_', $_POST['ClinicName']);
	while (stristr($filename, "__") !== FALSE) {
		$filename = str_replace("__", "_", $filename);
	} 
	$filename .= "_Coupon.pdf";
	
	$dompdf = new DOMPDF();
	$dompdf->load_html($html);
	$dompdf->set_paper('a3', 'landscape');
	$dompdf->render();
	$dompdf->stream($filename);
	
?>

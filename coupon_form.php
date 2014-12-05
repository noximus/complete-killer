<?
//echo(realpath(dirname(__FILE__)));
if($_GET['generate'] == "test"){
	//require_once('dompdf/dompdf.php');
	require_once("dompdf/dompdf_config.inc.php");

$html =
  '<html><head><style>
.name {z-index:11; position:absolute; top:642px; left: 740px;}
.address {z-index:11; position:absolute; top:642px; left: 965px;}
.city {z-index:11; position:absolute; top:676px; left: 670px;}
.state {z-index:11; position:absolute; top:676px; left: 956px;}
.zip {z-index:11; position:absolute; top:710px; left: 666px;}
.email{z-index:11; position:absolute; top:343px; left: 880px;}
.bgimg { position:absolute;left:0px;top:0px;z-index:-1;}
body {  }
</style></head> 
<body> 
<div class="holder" >
<img src="Green_Men_Rabies3.jpg" class="bgimg" />
<div>

<div class="name"  >'. $_GET['Name'] .'</div>
<div class="address" >'. $_GET['Address'] .'</div>
<div class="city" >'. $_GET['City'] .'</div>
<div class="state" >'. $_GET['State'] .'</div>
<div class="zip" >'. $_GET['Zip'] .'</div>
<div class="email" >'. $_GET['e-mail'] .'</div>
</body> 
</html>';

/*$html =
  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
  <title>z-index</title>
  <style type="text/css">
    div {
      position: absolute;
    }
  </style>
  </head>
  <body>
    
    <div style="background: #fff; opacity: 0.8; border: 6px solid red; z-index: 3; padding: 3em; top: 3em; left: 2em; width: 6em;">
    z-index: 3, order: 1
    </div>
    
    <div style="background: #fff; opacity: 0.8; border: 6px solid green; z-index: 2; padding: 3em; top: 6em; left: 6em; width: 6em;">
    z-index: 2, order: 2
    </div>
    
    <div style="background: #fff; opacity: 0.8; border: 6px solid blue; z-index: 1; padding: 3em; top: 1em; left: 4em; width: 6em;">
    z-index: 1, order: 3
    </div>
    
    <div style="background: #fff; opacity: 0.8; border: 6px solid red; z-index: auto; padding: 3em; top: 23em; left: 2em; width: 6em;">
    z-index: auto, order: 1
    </div>
    
    <div style="background: #fff; opacity: 0.8; border: 6px solid green; z-index: auto; padding: 3em; top: 26em; left: 6em; width: 6em;">
    z-index: auto, order: 2
    </div>
    
    <div style="background: #fff; opacity: 0.8; border: 6px solid blue; z-index: auto; padding: 3em; top: 21em; left: 4em; width: 6em;">
    z-index: auto, order: 3
    </div>
    
  </body>
</html>';
*/

if(!$_GET['generatePDF']){
echo($html .'<a href="coupon_form.php" >return</a>');
exit();
}

$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->set_paper('a3', 'landscape');
$dompdf->render();
$dompdf->stream("sample.pdf");


/*<style type="text/css">

div.c1 {
   	background-repeat: no-repeat;
	background-image: url("FL-11015_Green_Men_Rabies_Coupon.jpg");
   	width:1000px; 
 	height:733px;
 	position: absolute;
}

div.Name{
	position: absolute;
	display: block;
	width: 350px;
	height: 22px;
	top: 223px;
	left: 600px;
	border: 1px solid #444;

}

</style> 
*/

}

?>

<form >
<input type="hidden" name="generate" value="test" />
Name:  <input type="text" name="Name" value="Nick Trienens" /><br/>
Address:  <input type="text" name="Address" value="1615 N 40th st"/><br/>
City:  <input type="text" name="City" value="Seattle"/><br/>
State:  <input type="text" name="State" value="Washington" /><br/>
ZIP:  <input type="text" name="Zip"  value="zip"/><br/>
E-Mail:Name:  <input type="text" name="e-mail"  value="email"/><br/>
generatePDF<input type="checkbox" value="1" name="generatePDF" /><br/>
<input type="submit" value="Submit" />




</form>

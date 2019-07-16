<?php

require_once "assets/plugins/dompdf/autoload.inc.php";
require_once "assets/plugins/phpmailer/src/Exception.php";
require_once "assets/plugins/phpmailer/src/PHPMailer.php";
require_once "assets/plugins/phpmailer/src/SMTP.php";

use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$css = '
	html, body {
		font-family: sans-serif;
		margin: 0;
	}

	#id-card {
		width: 2.125in;
		height: 3.370in;
		text-align: center;
		// border: 2px solid #000;
		border-radius: 0.25rem;
	    position: absolute;
	    left: 50%;
	    top: 50%;
	    transform: translate(-50%, -50%);
	    margin-top: -6px;
	    margin-left: -1px;
	}

	#id-card .id-card-header img {
		margin-top: 2rem;
	}

	#id-card .id-card-body {
		height: 100%;
	}

	#id-card .id-card-body td {
		padding: 2rem;	
	}

	#id-card .id-card-body img {
		margin-bottom: 2rem;
		border-radius: 0.5rem;
		border: 2px solid #000;
	}

	#id-card .id-card-body strong {
		font-size: 18px;
	}
';

$html = '
	<html>
	<head>
		<style>
			' . $css . '
		</style>
	</head>
	<body>
		<table id="id-card">
		<thead class="id-card-header">
			<tr>
				<td>
					<img src="assets/images/logo.png" height="72" alt="Logo">
				</td>
			</tr>
		</thead>
		<tbody class="id-card-body">
			<tr>
				<td>
					<div>
						<img src="assets/images/photo.png" alt="Photo">
					</div>
					<div>
						<strong>
							John Doe
						</strong>
					</div>
					<div>
						<i>
							Lorem Ipsum
						</i>
					</div>
				</td>
			</tr>
		</tbody>
		</table>
	</body>
	</html>
';

$filePath = 'assets/pdf/id-card-' . time() . '.pdf';

$dompdf = new Dompdf;

$dompdf->loadHtml($html);
$dompdf->setPaper([0, 0, 153 + 50, 242.64 + 100], 'potrait');
$dompdf->render();
// $dompdf->stream();
// echo $html;
file_put_contents($filePath, $dompdf->output());

$mail = new PHPMailer;

$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Host	= "smtp.gmail.com"; // SMTP host
$mail->Port = 465; // SMTP port
$mail->IsHtml(true);
$mail->Username = "example@gmail.com"; // Your email
$mail->Password = "secret"; // Your email password
$mail->From = "example@gmail.com"; // Sender email
$mail->FromName = "John Doe"; // Sender name
$mail->Subject = "Your ID Card";
$mail->Body = "
	Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
	tempor incididunt ut labore et dolore magna aliqua.
";
$mail->AddAddress('example@gmail.com'); // Recipient email
$mail->AddAttachment($filePath, '', $encoding = 'base64', $type = 'application/pdf');

if($mail->Send()) {
	echo "Message has been sent";
} else {
	echo "Message has not been sent. Error : " . $mail->ErrorInfo;
}

?>

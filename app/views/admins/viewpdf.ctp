<?php
	//HELP FILE - http://api.joomla.org/com-tecnick-tcpdf/TCPDF.html
	
	App::import('Vendor','xtcpdf');  
	$tcpdf = new XTCPDF();
	
	$date = date('d/m/Y');
	$output = $name.'.pdf';
	$dest = SENDER_ID_PATH . DS . $output;
	
	$textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans' 
	
	//$tcpdf->SetAuthor("KBS Homes & Properties at http://kbs-properties.com"); 
	$tcpdf->SetAutoPageBreak( false ); 
	//$tcpdf->setHeaderFont(array($textfont,'',40)); 
	//$tcpdf->xheadercolor = array(150,0,0); 
	//$tcpdf->xheadertext = 'KBS Homes & Properties'; 
	$tcpdf->xfootertext = 'Copyright © %d '. PARENT_COMPANY .'. All rights reserved.'; 
	
	// add a page (required with recent versions of tcpdf) 
	$tcpdf->AddPage(); 
	
	// Now you position and print your page content 
	// example:  
	
	
	$img_file = SENDER_ID_FILE;
	$tcpdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
	
	$tcpdf->SetTextColor(10, 10, 10); 
	$tcpdf->SetFont($textfont,'',10); 
	$tcpdf->MultiCell(20,20, $date, 1,1,0,'L',136,63.5);
	
	$tcpdf->SetTextColor(10, 10, 10);
	$tcpdf->SetFont($textfont,'',10);
	$tcpdf->MultiCell(20,20, $name, 1,1,0,'L',107,102.8);
	
	// ... 
	// etc. 
	// see the TCPDF examples  
	
	//echo $tcpdf->Output($dest, 'F');
	
	// This is only for 'F' else 'echo' the output
	if($tcpdf->Output($dest, 'F')) $admin->sendPdf($name);

?>
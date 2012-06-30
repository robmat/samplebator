<?php

include('lsdb_layout.php');	# // -> require_once("mainfile.php"); --> require_once("config.php");
require("func_lsdb.php");	// Presentation Layer and basic access vars
require("lsdbcontroller.php");
require("api_rs.php");	// Backend DB API
require("api_format.php");	// Backend DB API
require('fpdf/fpdf.php');

class PDF extends FPDF
{
	// Load data
	function LoadData($file) {
		// Read file lines
		$lines = file($file);
		$data = array();
		foreach($lines as $line)
		$data[] = explode(';',trim($line));
		return $data;
	}

	// Simple table
	function BasicTable($header, $data, $widths) {
		// Header
		foreach($header as $index => $col)
		$this->Cell($widths[$index], 7, $col, 1);
		$this->Ln();
		// Data
		foreach($data as $row) {
			foreach($row as $index =>  $col) {
				$this->Cell($widths[$index], 6, $col, 1);
			}
			$this->Ln();
		}
	}

	// Better table
	function ImprovedTable($header, $data) {
		// Column widths
		$w = array(40, 35, 40, 45);
		// Header
		for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C');
		$this->Ln();
		// Data
		foreach($data as $row)
		{
			$this->Cell($w[0],6,$row[0],'LR');
			$this->Cell($w[1],6,$row[1],'LR');
			$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
			$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
			$this->Ln();
		}
		// Closing line
		$this->Cell(array_sum($w),0,'','T');
	}

	// Colored table
	function FancyTable($header, $data) {
		// Colors, line width and bold font
		$this->SetFillColor(255,0,0);
		$this->SetTextColor(255);
		$this->SetDrawColor(128,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');
		// Header
		$w = array(40, 35, 40, 45);
		for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
		$this->Ln();
		// Color and font restoration
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Data
		$fill = false;
		foreach($data as $row)
		{
			$this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
			$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
			$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
			$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
			$this->Ln();
			$fill = !$fill;
		}
		// Closing line
		$this->Cell(array_sum($w),0,'','T');
	}
}

global $dbi;

$queryResult = sql_query( "SELECT pl.pid AS playerid, pl.pfname AS name, pl.plname AS surname, pl.pfkey2 AS userkey, le.lstart, le.ldarts AS darts, le.lscore, le.lfinish, le.lhighscore, le.lhighscore171, COUNT(*) AS howmany, SUM(le.lhighscore) AS max180, SUM(le.lhighscore171) AS max171 FROM tplayer pl JOIN tblgameplayer gp ON gp.gppid = pl.pid JOIN tblgame ga ON ga.gid = gp.gpgid JOIN tblleg le ON le.lgid = ga.gid WHERE le.lscore = 501 GROUP BY playerid, le.ldarts ORDER BY surname, playerid, darts", $dbi );
$resultArray = createRecordSet( $queryResult, $dbi );

$currentPlayerId = "";
$playerCount = 0;
$pdfDataTable = array();
$sum180max = 0;
$sum171max = 0;

foreach ( $resultArray as $index => $row ) {
	if ( $currentPlayerId != $row[0] ) {
		$currentPlayerId = $row[0];
		$pdfDataTable[$playerCount] = array();
		$playerCount++;

		$sum180max = 0;
		$sum171max = 0;
	}
	$pdfDataTable[$playerCount - 1][0] = $row[1];
	$pdfDataTable[$playerCount - 1][1] = $row[2];
	$pdfDataTable[$playerCount - 1][2] = $row[3];

	$dartCounter = 0;

	for ($darts = 18; $darts > 8; $darts--) {
		if ($darts == $row[5]) {
			$pdfDataTable[$playerCount - 1][$dartCounter + 3] = $row[10];
		} else if (!isset($pdfDataTable[$playerCount - 1][$dartCounter + 3])) {
			$pdfDataTable[$playerCount - 1][$dartCounter + 3] = 0;
		}
		$dartCounter++;
	}

	$sum180max += $row[11];
	$sum171max += $row[12];
	
	$pdfDataTable[$playerCount - 1][13] = $sum180max;
	$pdfDataTable[$playerCount - 1][14] = $sum171max;
}


//var_dump( $pdfDataTable );

$widths = array(30, 40, 20, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 10, 10);

$pdf = new PDF();
// Column headings
$header = array('Name', 'Vorname', 'HDSV nr.', '18', '17', '16', '15', '14', '13', '12', '11', '10', '9', '180er', '171er');
// Data loading
$pdf->SetFont('Arial','',9);
$pdf->AddPage();
$pdf->BasicTable($header,$pdfDataTable,$widths);
//$pdf->AddPage();
//$pdf->ImprovedTable($header,$data);
//$pdf->AddPage();
//$pdf->FancyTable($header,$data);
$pdf->Output();

?>
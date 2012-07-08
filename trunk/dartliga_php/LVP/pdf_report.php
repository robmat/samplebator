<?php

include('lsdb_layout.php');	# // -> require_once("mainfile.php"); --> require_once("config.php");
require("func_lsdb.php");	// Presentation Layer and basic access vars
require("lsdbcontroller.php");
require("api_rs.php");	// Backend DB API
require("api_format.php");	// Backend DB API
require('fpdf/fpdf.php');

class PDF extends FPDF {
	
	public $widths = array(30, 40, 20, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 10, 10);
	
	function Header() {
		$this->SetFont('Arial','B',9);
		
		$header = array('Vorname', 'Name', 'HDSV nr.', '18', '17', '16', '15', '14', '13', '12', '11', '10', '9', '180er', '171er');
		
		$this->MultiCell(200, 2, "Sportabzeichen H.D.S.V. e.V. - Kategorie Short Leg, 180 und 171", 0, 'C', FALSE);		
		$this->Ln();
		$this->MultiCell(200, 2, "501-Wertungen: 18 Darts abwärts", 0, 'C', FALSE);
		$this->Ln();
		$this->MultiCell(200, 2, date('d.m.o G:i:s'), 0, 'C', FALSE);
		
		$this->Ln();
		$this->Ln();
		foreach($header as $index => $col) {
			$this->Cell($this->widths[$index], 7, $col, 1, 0, 'C');
		}
		$this->Ln();
		$this->SetFont('Arial','',9);
	}
	
	function BasicTable($data) {
		// Data
		foreach($data as $row) {
			foreach($row as $index => $col) {
				$this->SetFillColorBasedOnCell( $index, $col );
				$this->Cell( $this->widths[$index], 6, $col, 1, 0, 'C', TRUE );
				$this->SetFillColor( 255, 255, 255 );
			}
			$this->Ln();
		}
	}
	
	function SetFillColorBasedOnCell($columnIndex, $cellValue) {
		//echo $columnIndex." ".$cellValue."<BR>";
		if ( $columnIndex == 13 || $columnIndex == 14 ) {
			if ( $cellValue >= 30 ) {
				$this->SetFillColor(255, 255, 0);
			}
			if ( $cellValue <= 29 ) {
				$this->SetFillColor(192, 192, 192);
			}
			if ( $cellValue <= 14 ) {
				$this->SetFillColor(204, 127, 50);
			}
			if ( $cellValue <= 5 ) {
				$this->SetFillColor(255, 255, 255);
			}
		}
		if ( $columnIndex == 3 ) {
			if ( $cellValue >= 21 ) {
				$this->SetFillColor(255, 255, 0);
			}
			if ( $cellValue < 21 ) {
				$this->SetFillColor(192, 192, 192);
			}
			if ( $cellValue < 12 ) {
				$this->SetFillColor(204, 127, 50);
			}
			if ( $cellValue < 5 ) {
				$this->SetFillColor(255, 255, 255);
			}
		}
		if ( $columnIndex == 4 ) {
			if ( $cellValue >= 15 ) {
				$this->SetFillColor(255, 255, 0);
			}
			if ( $cellValue < 15 ) {
				$this->SetFillColor(192, 192, 192);
			}
			if ( $cellValue < 9 ) {
				$this->SetFillColor(204, 127, 50);
			}
			if ( $cellValue < 4 ) {
				$this->SetFillColor(255, 255, 255);
			}
		}
		if ( $columnIndex == 5 ) {
			if ( $cellValue >= 13 ) {
				$this->SetFillColor(255, 255, 0);
			}
			if ( $cellValue < 13 ) {
				$this->SetFillColor(192, 192, 192);
			}
			if ( $cellValue < 7 ) {
				$this->SetFillColor(204, 127, 50);
			}
			if ( $cellValue < 3 ) {
				$this->SetFillColor(255, 255, 255);
			}
		}
		if ( $columnIndex == 5 || $columnIndex == 6 || $columnIndex == 7 ) {
			if ( $cellValue >= 9 ) {
				$this->SetFillColor(255, 255, 0);
			}
			if ( $cellValue < 9 ) {
				$this->SetFillColor(192, 192, 192);
			}
			if ( $cellValue < 5 ) {
				$this->SetFillColor(204, 127, 50);
			}
			if ( $cellValue < 2 ) {
				$this->SetFillColor(255, 255, 255);
			}
		}
		if ( $columnIndex == 8 || $columnIndex == 9 || $columnIndex == 10 || $columnIndex == 11 ) {
			if ( $cellValue >= 9 ) {
				$this->SetFillColor(255, 255, 0);
			}
			if ( $cellValue < 9 ) {
				$this->SetFillColor(192, 192, 192);
			}
			if ( $cellValue < 5 ) {
				$this->SetFillColor(204, 127, 50);
			}
			if ( $cellValue < 2 ) {
				$this->SetFillColor(255, 255, 255);
			}
		}
	}
}

global $dbi;

$queryResult = sql_query( "SELECT pl.pid AS playerid, pl.pfname AS name, pl.plname AS surname, pl.pfkey2 AS userkey, le.lstart, le.ldarts AS darts, le.lscore, le.lfinish, le.lhighscore, le.lhighscore171, COUNT(*) AS howmany, SUM(le.lhighscore) AS max180, SUM(le.lhighscore171) AS max171 FROM tplayer pl JOIN tblleg le ON le.lpid = pl.pid WHERE le.lscore = 501 AND pl.pfkey2 LIKE 'HE%' AND le.ldarts BETWEEN 9 AND 18 GROUP BY playerid, le.ldarts ORDER BY surname, playerid, darts", $dbi );
$resultArray = createRecordSet( $queryResult, $dbi );

$maxesQueryResult = sql_query( "SELECT playerid, name, surname, userkey, SUM(max180), SUM(max171) FROM (SELECT pl.pid AS playerid, pl.pfname AS name, pl.plname AS surname, pl.pfkey2 AS userkey, le.lstart, le.ldarts AS darts, le.lscore, le.lfinish, le.lhighscore, le.lhighscore171, COUNT(*) AS howmany, SUM(le.lhighscore) AS max180, SUM(le.lhighscore171) AS max171 FROM tplayer pl JOIN tblleg le ON le.lpid = pl.pid WHERE pl.pfkey2 LIKE 'HE%' GROUP BY playerid, le.ldarts ORDER BY surname, playerid, darts ) AS sub GROUP BY playerid", $dbi );
$maxesResultArray = createRecordSet( $maxesQueryResult, $dbi );

$currentPlayerId = "";
$playerCount = 0;
$pdfDataTable = array();
$idsArray = array();


foreach ( $resultArray as $index => $row ) {
	$idsArray[] = $row[0];
}
foreach ( $maxesResultArray as $maxesRow ) {
	$idsArray[] = $maxesRow[0];
}

$idsArray = array_unique( $idsArray );

foreach ( $resultArray as $index => $row ) {
	if ( $currentPlayerId != $row[0] ) {
		$currentPlayerId = $row[0];
		$pdfDataTable[$playerCount] = array();
		$playerCount++;
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
	
	foreach ( $maxesResultArray as $maxesRow ) {
		if ( $row[0] == $maxesRow[0] ) {
			$pdfDataTable[$playerCount - 1][13] = $maxesRow[4];
			$pdfDataTable[$playerCount - 1][14] = $maxesRow[5];
		}
	}
	
	foreach ( $idsArray as $idsIndex => $id ) {
		if ( $row[0] ==  $id ) {
			$idsArray[$idsIndex] = -1;
		}
	}
}

foreach ( $idsArray as $idsIndex => $id ) {
	foreach ( $maxesResultArray as $maxesIndex => $maxesRow ) {
		if  ( $id != -1 && $maxesRow[0] == $id && ( $maxesRow[4] != 0 || $maxesRow[5] != 0 ) ) {
			$pdfDataTable[] = array( $maxesRow[1], $maxesRow[2], $maxesRow[3], 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, $maxesRow[4], $maxesRow[5] );
		}
	}
}

function pdfReportComparator($row1, $row2) {
	return $row1[1] > $row2[1];
}

usort( $pdfDataTable, "pdfReportComparator" );

$pdf = new PDF();
$pdf->SetFont('Arial','',9);
$pdf->SetFillColor( 255, 255, 255 );
$pdf->AddPage();
$pdf->BasicTable($pdfDataTable);
$pdf->Output();

?>
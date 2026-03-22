<?php
	if($_SERVER['REQUEST_METHOD'] == "POST"){
		//variaveis
		$DigitoContaCorente = $_POST['DigitoContaCorente'];
		$RazaoSocial = $_POST['RazaoSocial'];
		$DataGravacao = $_POST['DataGravacao'];
		$ContaCorente = $_POST['ContaCorente'];
		//Header do arquivo
		$linha1 = "01REMESSA01COBRANCA". str_repeat('0', 10) . $DigitoContaCorente . $RazaoSocial . "482SBCACH CRED.DIR" . $DataGravacao . str_repeat(' ', 8) . "MX0000001" . str_repeat(' ', 277) . "000001";
		//Dados do Titulo
		$linha2 = "100000 000000000000 0210001" . $ContaCorente . $DigitoContaCorente . ;
	}
?>

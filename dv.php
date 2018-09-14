<?php

$nro = '305485609300100046621010577856320160530';

$pares = 0;
$impares = 0;
for ($i = 1; $i <= strlen($nro); $i++) {
	// If I Mod 2 = 0 Then
	if ($i % 2 == 0) {
		// es par
		$pares += (int) substr($nro,$i-1,1);
		} else {
		// es impar
		$impares += (int) substr($nro,$i-1,1);
	}
}
//
$impares = 3 * $impares;
$total = $pares + $impares;
$digito = 10 - ($total % 10);
//
if ($digito == 10) {
$digito = 0;
}
echo $digito;
?>
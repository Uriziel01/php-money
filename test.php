<?PHP
require 'money.php';
require 'currency.php';

$kwota = money::EUR(666);

var_dump($kwota);

list ($zaliczka1, $zaliczka2, $reszta) = ($kwota->allocate([43,6,51]));

$zaliczkaPlusVat = $zaliczka1->add(money::GBP(200));

$zaliczkaOstateczna = $zaliczkaPlusVat->substract(money::PLN(50));

var_dump((string)$zaliczka1, (string)$zaliczkaPlusVat, (string)$zaliczkaOstateczna);

$kwotaPLN = money::PLN(0)->add($zaliczkaOstateczna);

var_dump((string)$kwotaPLN);

$kwotaPLN2 = $zaliczkaOstateczna->convertTo('PLN');

var_dump((string)$kwotaPLN2);

$vat = $kwotaPLN->multiply(0.23);

var_dump((string)$vat);

$vat = $kwotaPLN->multiply(0.77);

var_dump((string)$vat);

var_dump($kwotaPLN->equals($kwotaPLN2));

var_dump($zaliczkaOstateczna->isSameAmount($kwotaPLN));

var_dump((string)$kwota);
var_dump((string)$kwota->add(money::CLP(500)));
var_dump((string)$kwota->add(money::CLP(500))->convertTo('CLP'));
$string = file_get_contents('http://rozumiem.blox.pl/2008/10/Kursy-walut-8211-wartosc-pieniadza.html');

money::parseMoneyFromString($string);
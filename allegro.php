<html>
<head>
<meta charset="utf-8">
</head>
<body>
<?php
$login = 'TwojLoginAllegro';
$pass = 'TwojeHasloAllegro';
$webapi_key = 'TwojAllegroWebApi';
/*Login i hasło nie są potrzebne do każdej operacji. Zostały dodane na początek, aby
zapobiec problemom z autoryzacją użytkownika w przyszłości.
*/
$client = new SoapClient('https://webapi.allegro.pl/uploader.php?wsdl');
$version = $client->doQuerySysStatus(1, 1, $webapi_key);
$session = $client->doLoginEnc($login, base64_encode( hash('sha256', $pass, true) ), 1, $webapi_key, $version['ver-key']);

$nr_sesji = $session['session-handle-part'];
 
// pobranie danych
$items = $client->doGetMyWonItems($nr_sesji);
$sell = $client->doGetMySellItems($nr_sesji);
echo $login;
?>
<b>, Przedmioty, które do tej pory kupiłeś: </b>
<br><br>
<?php
for($i=0;$i<$items['won-items-counter'];$i++)
{	
	echo $items['won-items-list'][$i]->{'item-title'}," - ";
	echo $items['won-items-list'][$i]->{'item-price'}['0']->{'price-value'}," zł<br>";
} 
?>
<br>
<br>
<h1>Zobacz, co aktualnie sprzedaję!</h1>
<?php
for($i=0;$i<$sell['sell-items-counter'];$i++)
{
		$id = $sell['sell-items-list'][$i]->{'item-id'};
		$opis = $client->doShowItemInfoExt($nr_sesji, $id, 1);
		?>
		<h2><?php echo $sell['sell-items-list'][$i]->{'item-title'};?> </h2>
		<img src="<?php echo $sell['sell-items-list'][$i]->{'item-thumbnail-url'}; ?>" alt="Product Image" class="product-image" width="100" height="100">
		<p><?php echo "Identyfikator przedmiotu: "; ?> </p>
		<p><?php echo $sell['sell-items-list'][$i]->{'item-id'}; ?> </p>
		<p><?php echo "Cena przedmiotu: "; ?> </p>
		<p><?php echo $sell['sell-items-list'][$i]->{'item-price'}[$i]->{'price-value'}, " zł"; ?> </p>
		<p><?php echo "Opis aukcji: Może być formatowany, lub nie <br>"; ?></p>
		<p><?php echo $opis['item-list-info-ext']->{'it-description'}; ?> </p>
</body>
</html>
<?php
}
?>

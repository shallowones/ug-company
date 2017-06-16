<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Карта месторасположения компании");
?>                    <h1>Карта месторасположения компании</h1>
<br><br>
<?	// включаемая область - карта
$APPLICATION->IncludeFile("/geography.php", Array(), Array(
	"MODE" => "php", // будет редактировать в веб-редакторе
	"NAME" => "География расположения объектов", // текст всплывающей подсказки на иконке
	));
?>	


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
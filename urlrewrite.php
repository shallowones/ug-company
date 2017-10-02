<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/lk/statements/#",
		"RULE" => "",
		"ID" => "ugraweb:statements",
		"PATH" => "/lk/statements/index.php",
	),
	array(
		"CONDITION" => "#^/news/(.*)?/.*#",
		"RULE" => "ELEMENT_CODE=\$1",
		"ID" => "",
		"PATH" => "/news/detail.php",
	),
	array(
		"CONDITION" => "#^/#",
		"RULE" => "",
		"ID" => "ugraweb:requisites",
		"PATH" => "/lk/index.php",
	),
);

?>
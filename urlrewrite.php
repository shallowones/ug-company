<?
$arUrlRewrite = array(
    array(
        "CONDITION" => "#^/news/(.*)?/.*#",
        "RULE" => "ELEMENT_CODE=$1",
        "ID" => "",
        "PATH" => "/news/detail.php",
    ),
);

?>
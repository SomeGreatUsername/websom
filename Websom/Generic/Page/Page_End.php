<?php
Websom_Check_Responsive();

$Properties = GetProperties();

$Properties['Body'] .= ob_get_contents();
ob_end_clean();

$Page = '';

$Page = file_get_contents(Websom_root."/Website/Page/".$Properties['TemplatePage'], true);

$Properties['Input'] = Get_Input_Scripts().Get_Responsive_Scripts();


preg_match_all("~%(.*?)%~", $Page, $Propertie);

foreach($Propertie[0] as $PropertieSet){
	$Page = str_replace($PropertieSet, $Properties[str_replace("%", "", $PropertieSet)], $Page);
}


preg_match_all("~!require-(.*?)-!~s", $Page, $Requires);

foreach($Requires[1] as $ReqSet){
	ob_start();
	include(Websom_root.'/Website/Requires/'.$ReqSet);
	$RequireInclude = ob_get_clean();
	$Page = str_replace($ReqSet, $RequireInclude, $Page);
}
$Page = preg_replace("~!require-(.*?)-!~s", '$1', $Page);



//Do all form checking after the user has created the forms\\
include(Websom_root."/Generic/Core/Data_Form_Check.php");



$Render = Render();
if ($Render === false){
	echo $Page;
}else{
	echo $Render;
}
?>
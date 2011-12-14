<?
    
    define('dire','../../');
    include(dire.'_env/exec.php');

    allowed();
    session_start();
    
    $type = vGET('type');
    if(!$type)
        $type="excel";

    $query = mysql_query('SELECT COUNT(*) FROM `device`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $devicecount = mysql_fetch_array($query);
    
    $query = mysql_query('SELECT device.*, bureau.name as bureauname,
                                 location.name as locationname
                            FROM `device`
                            LEFT JOIN `bureau` ON bureau.id=device.bid
                            LEFT JOIN `location` ON location.id=bureau.lid
                            ') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $devices = array();
    while($fetch=mysql_fetch_array($query))
        array_push($devices,$fetch);
        
    if($type=="excel") {
        header("Content-type: application/vnd-ms-excel"); 
        header("Content-Disposition: attachment; filename=export.xls");

        echo '
<html xmlns:o="urn:schemas-microsoft-com:office:office"
  xmlns:x="urn:schemas-microsoft-com:office:excel"
  xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv=Content-Type content="text/html; charset=macintosh">
<meta name=ProgId content=Excel.Sheet>
<meta name=Generator content="iStock">
<style>
<!--table {}
.style0
	{text-align:general;
	vertical-align:bottom;
	white-space:nowrap;
	color:windowtext;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Verdana;
	border:none;}
td
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	color:windowtext;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Verdana;
	text-align:general;
	vertical-align:bottom;
	border:none;
	white-space:nowrap;}
ruby
	{ruby-align:left;}
rt
	{color:windowtext;
	font-size:8.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Verdana;
	display:none;}
-->
</style>
</head>

<body link="#0000d4" vlink="#993366">

<table border=0 cellpadding=0 cellspacing=0 width=75 style="border-collapse:
 collapse;table-layout:fixed">
 <col width=75>
 <tr height=13>
  <td height=13 width=80><b>Ger&auml;tename</b></td>
  <td height=13 width=80><b>Einsatzort</b></td>
  <td height=13 width=112><b>Inventarnummer</b></td>
    </tr>';
        foreach($devices as $d) {
            echo '
<tr>
    <td>'.$d['type'].'</td>
    <td>'.$d['locationname'].'</td>
    <td>'.$d['stockid'].'</td>
</tr>
                ';
        }
        echo '</table>
 </body>
</html>';
    }
    elseif($type=="csv") {
        require('SpreadsheetExport.php');
        
        $export = new SpreadsheetExport();

        // Set the target filename. The extension will automatically be added.
        $export->filename = "export";

        // We add a few columns now using dates, strings and floats. The second
        // parameter specifies the column with in cm when using ODS export.
        $export->AddColumn("string");
        $export->AddColumn("string");
        $export->AddColumn("string");

        $export->AddRow(array("Gerätename", "Einsatzort", "Inventarnummer"));
        $export->AddRow(array("", "", ""));

        // Now we add 3 rows
        foreach($devices as $d) {
            $export->AddRow(array($d['type'], $d['locationname'], $d['stockid']));
        }

        // And now export it either as ODS or CSV (you need the zip library installed to use ODS!)
        $export->DownloadCSV();
    }
    
?>

<?php


/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */



require_once("./inc/connect_db.php");

// this file returns an xml file that confirms that the email address is updated and they agreement number the borrower has signed


//header("Content-type: text/xml");

/*
print("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<data>
");
*/

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Toronto');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Raymond Gubala")
                             ->setLastModifiedBy("Raymond Gubala")
                             ->setTitle("Office 2007 XLSX Fluuid Translation")
                             ->setSubject("Office 2007 XLSX Fluuid Translation")
                             ->setDescription("MAD Signout document for Office 2007 XLSX, generated using PHP classes.")
                             ->setKeywords("office 2007 openxml php MAD SignOut")
                             ->setCategory("MAD SignOut");

$line = 1;
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$line", "MAD SignOut");


++$line;


$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$line", "Asset Description")
            ->setCellValue("B$line", "Notes")
            ->setCellValue("C$line", "Serial Number")
            ->setCellValue("D$line", "Barcode")
            ->setCellValue("E$line", "Time Logged Out")
            ->setCellValue("F$line", "Time Due")
            ->setCellValue("G$line", "First Name")
            ->setCellValue("H$line", "Last Name")
            ->setCellValue("I$line", "Student ID")
            ->setCellValue("J$line", "DC Email")
            ->setCellValue("K$line", "Other Email")
            ->setCellValue("L$line", "Over Due")
            ->setCellValue("M$line", "Over Due 10 Days");


$query = "SELECT assets.asset_description, assets.notes, assets.serial_number, assets.barcode,
assets_logged_out.out_time, assets_logged_out.due_time,
borrowers.first_name, borrowers.last_name, borrowers.student_id, borrowers.dc_email, borrowers.other_email
FROM assets, assets_logged_out, borrowers
WHERE assets.id = assets_logged_out.assets_id
AND assets_logged_out.borrowers_id = borrowers.id
AND assets_logged_out.in_time = '0'
ORDER BY asset_description ";
$mysql_result = mysql_query($query, $mysql_link);
while($row = mysql_fetch_row($mysql_result)) {
    $assets_asset_description = stripslashes($row[0]);
    $assets_notes = htmlspecialchars(stripslashes($row[1]));
    $assets_serial_number = htmlspecialchars(stripslashes($row[2]));
    $assets_barcode = htmlspecialchars(stripslashes($row[3]));
    $assets_logged_out_out_time = date("D d M Y h:i:s A",htmlspecialchars(stripslashes($row[4])));
    $assets_logged_out_due_time = date("D d M Y h:i:s A",htmlspecialchars(stripslashes($row[5])));
    $borrowers_first_name = htmlspecialchars(stripslashes($row[6]));
    $borrowers_last_name = htmlspecialchars(stripslashes($row[7]));
    $borrowers_student_id = htmlspecialchars(stripslashes($row[8]));
    $borrowers_dc_email = htmlspecialchars(stripslashes($row[9]));
    $borrowers_other_email = htmlspecialchars(stripslashes($row[10]));

    if ($row[5] < mktime()) {
        $overdue = "Yes";
    } else {
        $overdue = "No";
    }

    if ($row[5]+(60*60*24*10) < mktime()) {
        $overdue10 = "Yes";
    } else {
        $overdue10 = "No";
    }

    ++$line;

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$line", "$assets_asset_description")
            ->setCellValue("B$line", "$assets_notes")
            ->setCellValue("C$line", "$assets_serial_number")
            ->setCellValue("D$line", "$assets_barcode")
            ->setCellValue("E$line", "$assets_logged_out_out_time")
            ->setCellValue("F$line", "$assets_logged_out_due_time")
            ->setCellValue("G$line", "$borrowers_first_name")
            ->setCellValue("H$line", "$borrowers_last_name")
            ->setCellValue("I$line", "$borrowers_student_id")
            ->setCellValue("J$line", "$borrowers_dc_email")
            ->setCellValue("K$line", "$borrowers_other_email")
            ->setCellValue("L$line", "$overdue")
            ->setCellValue("M$line", "$overdue10");

    /*
    print("<asset_types>
    <asset_description>$assets_asset_description</asset_description>
    <assets_notes>$assets_notes</assets_notes>
    <assets_serial_number>$assets_serial_number</assets_serial_number>
    <assets_barcode>$assets_barcode</assets_barcode>
    <assets_logged_out_out_time>$assets_logged_out_out_time</assets_logged_out_out_time>
    <assets_logged_out_due_time>$assets_logged_out_due_time</assets_logged_out_due_time>
    <borrowers_first_name>$borrowers_first_name</borrowers_first_name>
    <borrowers_last_name>$borrowers_last_name</borrowers_last_name>
    <borrowers_student_id>$borrowers_student_id</borrowers_student_id>
    <borrowers_dc_email>$borrowers_dc_email</borrowers_dc_email>
    <borrowers_other_email>$borrowers_other_email</borrowers_other_email>
    <overdue>$overdue</overdue>
    <overdue10>$overdue10</overdue10>
    </asset_types>
    ");
   */

}
++$line;

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle("Fluiid Translation");


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


$addDate = date('d-M-Y_H_i_s');


// Redirect output to a clientÕs web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="sign_out_over_due_'.$addDate.'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');




exit;


$dbh = null;

?>

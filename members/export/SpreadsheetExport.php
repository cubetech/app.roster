<?php

/*
 * SpreadsheetExport 0.1.0
 * Copyright (C) 2009 Fusonic Interactive OG
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
 */

/**
 * Export spreadsheet data as CSV or ODF.
 */
class SpreadsheetExport {

	const ODF_NAMESPACE_MANIFEST = "urn:oasis:names:tc:opendocument:xmlns:manifest:1.0";
	const ODF_NAMESPACE_OFFICE = "urn:oasis:names:tc:opendocument:xmlns:office:1.0";
	const ODF_NAMESPACE_STYLE = "urn:oasis:names:tc:opendocument:xmlns:style:1.0";
	const ODF_NAMESPACE_TEXT = "urn:oasis:names:tc:opendocument:xmlns:text:1.0";
	const ODF_NAMESPACE_TABLE = "urn:oasis:names:tc:opendocument:xmlns:table:1.0";
	const ODF_VERSION = "1.1";

	/**
	 * Array holding all added columns with properties.
	 *
	 * @var	array
	 */
	private $columns = array();

	/**
	 * Array holding all added data rows.
	 *
	 * @var	array
	 */
	private $data = array();

	/**
	 * Field separator for CSV files.
	 *
	 * @var	string
	 */
	public $csvSeparator = ",";

	/**
	 * Text delimiter for CSV files.
	 *
	 * @var	string
	 */
	public $csvDelimiter = "\"";

	/**
	 * The download filename. Extension will automatically appended.
	 *
	 * @var	string
	 */
	public $filename = "Export";

	/**
	 * Adds a column to the table.
	 *
	 * @param	string			$dataType		string/float/date
	 * @param	int				$width
	 */
	public function AddColumn($dataType = "string", $width = 3) {

		$this->columns[] = array(
			"width" => $width,
			"dataType" => $dataType
		);

	}

	/**
	 * Adds multiple columns to the table.
	 *
	 * @param	int				$amount
	 * @param	string			$dataType
	 * @param	int				$width
	 */
	public function AddColumns($amount, $dataType = "string", $width = 3) {

		for($i = 0; $i < $amount; $i++) {
			$this->AddColumn($dataType, $width);
		}

	}

	/**
	 * Adds a row to the table.
	 *
	 * @param	array			$data
	 */
	public function AddRow(array $data) {

		$this->data[] = $data;

	}

	/**
	 * Sends general output headers (Cache)
	 */
	private function SendGeneralHeaders() {

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

		// IE needs it ...
		header("Pragma: public");

	}

	/**
	 * Starts the download as CSV.
	 */
	public function DownloadCSV() {

		$csv = $this->GetCSV();

		// Send headers
		$this->SendGeneralHeaders();
		header("Content-Type: application/csv");
		header("Content-Length: " . strlen($csv));
		header("Content-Disposition: attachment; filename=\"" . $this->filename . ".csv\"");

		echo $csv;

	}

	/**
	 * Starts the download as ODF Spreadsheet.
	 */
	public function DownloadODF() {

		$odf = $this->GetODF();

		// Send headers
		$this->SendGeneralHeaders();
		header("Content-Type: application/vnd.oasis.opendocument.spreadsheet");
		header("Content-Length: " . strlen($odf));
		header("Content-Disposition: attachment; filename=\"" . $this->filename . ".ods\"");

		echo $odf;

	}

	/**
	 * Formats a given timestamp to format required for ODF files.
	 *
	 * @param	string			$value
	 * @return	string
	 */
	private function FormatDateValue($value) {

		if(preg_match("/^\d{4}-\d{2}-\d{2}(T\d{2}:\d{2}:\d{2})?$/", $value))
			return $value;
		else {
			$dateTime = new DateTime($value);
			return $dateTime->format("Y-m-d\TH:i:s");
		}

	}

	/**
	 * Creates the CSV output.
	 *
	 * @return	string
	 */
	public function GetCSV() {

		$content = "";

		foreach($this->data AS $rowIndex => $row) {

			$i = 0;

			foreach($this->columns AS $columnIndex => $column) {

				if($i > 0)
					$content .= $this->csvSeparator;

				if(isset($row[$columnIndex]))
					$value = $row[$columnIndex];
				else
					$value = "";

				$content .= $this->csvDelimiter . str_replace($this->csvDelimiter, "\\" . $this->csvDelimiter, $value) . $this->csvDelimiter;

				$i++;

			}

			$content .= "\n";

		}

		return $content;

	}

	/**
	 * Creates the ODF output.
	 *
	 * @return	string
	 */
	public function GetODF() {

		$tmpName = tempnam("tmp", "zip");


		/*
		 * Open ZIP
		 */

		$zip = new ZipArchive();
		$zip->open($tmpName, ZipArchive::CREATE | ZipArchive::OVERWRITE);


		/*
		 * mimetype
		 */

		$zip->addFromString("mimetype", "application/vnd.oasis.opendocument.spreadsheet");


		/*
		 * META-INF/manifest.xml
		 */

		$xml = new SimpleXMLElement('<manifest:manifest '
			. 'xmlns:manifest="' . self::ODF_NAMESPACE_MANIFEST . '" />');
		// spreadsheet
		$xmlFileEntry = $xml->addChild("file-entry", null, self::ODF_NAMESPACE_MANIFEST);
		$xmlFileEntry->addAttribute("media-type", "application/vnd.oasis.opendocument.spreadsheet", self::ODF_NAMESPACE_MANIFEST);
		$xmlFileEntry->addAttribute("full-path", "/", self::ODF_NAMESPACE_MANIFEST);
		// content.xml
		$xmlFileEntry = $xml->addChild("file-entry", null, self::ODF_NAMESPACE_MANIFEST);
		$xmlFileEntry->addAttribute("media-type", "text/xml", self::ODF_NAMESPACE_MANIFEST);
		$xmlFileEntry->addAttribute("full-path", "content.xml", self::ODF_NAMESPACE_MANIFEST);

		$zip->addEmptyDir("META-INF");
		$zip->addFromString("META-INF/manifest.xml", $xml->asXML());




		/*
		 * content.xml STARTS HERE
		 */

		$xml = new SimpleXMLElement('<office:document-content '
			. 'xmlns:office="' . self::ODF_NAMESPACE_OFFICE . '" '
			. 'xmlns:style="' . self::ODF_NAMESPACE_STYLE . '" '
			. 'xmlns:text="' . self::ODF_NAMESPACE_TEXT . '" '
			. 'xmlns:table="' . self::ODF_NAMESPACE_TABLE . '" '
			. 'office:version="1.1" />');


		/*
		 * Styles
		 */

		$xmlAutomaticStyles = $xml->addChild("automatic-styles", null, self::ODF_NAMESPACE_OFFICE);

		foreach($this->columns AS $columnIndex => $column) {

			// <style>
			$xmlStyle = $xmlAutomaticStyles->addChild("style", null, self::ODF_NAMESPACE_STYLE);
			$xmlStyle->addAttribute("name", "col" . $columnIndex, self::ODF_NAMESPACE_STYLE);
			$xmlStyle->addAttribute("family", "table-column", self::ODF_NAMESPACE_STYLE);

			// <table-coluömn-properties>
			$xmlTableColumnProperties = $xmlStyle->addChild("table-column-properties", null, self::ODF_NAMESPACE_STYLE);
			$xmlTableColumnProperties->addAttribute("column-width", $column['width'] . "cm", self::ODF_NAMESPACE_STYLE);

		}


		/*
		 * The table itself ...
		 */

		$xmlBody = $xml->addChild("body", null, self::ODF_NAMESPACE_OFFICE);
		$xmlSpreadsheet = $xmlBody->addChild("spreadsheet", null, self::ODF_NAMESPACE_OFFICE);
		$xmlTable = $xmlSpreadsheet->addChild("table", null, self::ODF_NAMESPACE_TABLE);

		// Columns
		foreach($this->columns AS $columnIndex => $column) {

			// <table-column>
			$xmlTableColumn = $xmlTable->addChild("table-column", null, self::ODF_NAMESPACE_TABLE);
			$xmlTableColumn->addAttribute("style-name", "col" . $columnIndex, self::ODF_NAMESPACE_TABLE);

		}

		// Rows
		foreach($this->data AS $rowIndex => $row) {

			$xmlRow = $xmlTable->addChild("table-row", null, self::ODF_NAMESPACE_TABLE);

			// Cells
			foreach($this->columns AS $columnIndex => $column) {

				// <table-cell>
				$xmlCell = $xmlRow->addChild("table-cell", null, self::ODF_NAMESPACE_TABLE);

				if($column['dataType'] == "date") {

					// Date value

					$xmlCell->addAttribute("value-type", "date", self::ODF_NAMESPACE_OFFICE);
					$xmlCell->addAttribute("date-value", $this->FormatDateValue($row[$columnIndex]), self::ODF_NAMESPACE_OFFICE);

					$xmlCell->addChild("p", $row[$columnIndex], self::ODF_NAMESPACE_TEXT);

				}
				elseif($column['dataType'] == "float") {

					// Float value

					$xmlCell->addAttribute("value-type", "float", self::ODF_NAMESPACE_OFFICE);
					$xmlCell->addAttribute("value", (float)$row[$columnIndex], self::ODF_NAMESPACE_OFFICE);

					$xmlCell->addChild("p", (float)$row[$columnIndex], self::ODF_NAMESPACE_TEXT);

				}
				elseif(substr($column['dataType'], 0, 9) == "currency_" && strlen($column['dataType']) == 12) {

					// Currency value

					$xmlCell->addAttribute("value-type", "currency", self::ODF_NAMESPACE_OFFICE);
					$xmlCell->addAttribute("currency", strtoupper(substr($column['dataType'], 9)), self::ODF_NAMESPACE_OFFICE);
					$xmlCell->addAttribute("value", (float)$row[$columnIndex], self::ODF_NAMESPACE_OFFICE);

					$xmlCell->addChild("p", (float)$row[$columnIndex], self::ODF_NAMESPACE_TEXT);

				}
				else {

					// String value

					$xmlCell->addAttribute("value-type", "string", self::ODF_NAMESPACE_OFFICE);

					$xmlCell->addChild("p", (string)$row[$columnIndex], self::ODF_NAMESPACE_TEXT);

				}

			}

		}

		$zip->addFromString("content.xml", $xml->asXML());


		/*
		 * Close ZIP
		 */

		$zip->close();

		$content = file_get_contents($tmpName);

		@unlink($tmpName);

		return $content;

	}

}

/*
$test = new SpreadsheetExport();
$test->AddColumn("string", 2);
$test->AddColumn("currency_EUR", 5);

$test->AddRow(array("hallo", "1.2"));
$test->AddRow(array("hallo", "2.8"));

$test->DownloadODF();
*/

?>
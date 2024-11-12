<?php

class UtilityFiles{

    use DatabaseConnection;
    
    /**
	*	This function will delete a file from the server and update the
	*	file field, set it to blank
	*
	*	@param
	*			$data_id		Primary key associated with the table
	*			$column_id		Primary key column name
	*			$column     	Column name of the file
	*			$table			Name of the file
	*			$path			Path to the file or location of the file
	*
	*	@return NULL
	*/
	function deleteFile($data_id, $column_id , $column, $table, $path){

		//get the file name before deleting
		$select = "SELECT ".$column." FROM ".$table." WHERE ".$column_id."='".$data_id."'";
		
		$query  = $this->conn->query($select);
		
		$result = $query->fetch_array();
		
		if($query->num_rows > 0){

			$fileName = $result[$column];
            if (file_exists($path . $fileName)) {
                if (unlink($path . $fileName)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
		}
		//echo $select." <br />".$sql;exit;
	}//eof


    function purchaseImport($fileName, $ADMINID)
    {
        try {
            // Open the file
            $file = fopen($fileName, "r");
            if ($file === FALSE) {
                throw new Exception("Failed to open file.");
            }

            // Get the table header
            $tableHeader = fgetcsv($file, 10000, ",");
            if ($tableHeader === FALSE) {
                throw new Exception("Failed to read table header.");
            }

            // Check if "Bill No" is present in the required columns
            if (!in_array('Bill No', $tableHeader)) {
                throw new Exception("Bill No column is missing.");
            }

            // Check if required columns exist
            $requiredColumns = ['Bill No', 'Bill Date', 'Payment Status', 'Distributor', 'Taxable', 'SGST', 'CGST', 'Entry Date', 'Total'];
            $columnIndices = array_map(function ($col) use ($tableHeader) {
                return array_search($col, $tableHeader);
            }, $requiredColumns);

            if (in_array(FALSE, $columnIndices, TRUE)) {
                throw new Exception("Required columns are missing.");
            }

            // Start building the table
            // echo "<table><tr>";
            // foreach ($requiredColumns as $header) {
            //     echo "<th>$header</th>";
            // }
            // echo "<th>Amount</th></tr>";

            // Read the rest of the file
            while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
                // Check if the "Bill No" column is not blank or only consists of white spaces
                if (trim($getData[$columnIndices[array_search('Bill No', $requiredColumns)]]) === '') {
                    continue; // Skip this line and continue to the next one
                }

                // Check if the row has sufficient data
                if (count($getData) >= count($requiredColumns)) {
                    // Replace distributor data with its id
                    $distributorName = $getData[$columnIndices[array_search('Distributor', $requiredColumns)]];

                    $select1 = "SELECT id FROM `distributor` WHERE `distributor`.`name`= '$distributorName'";
                    $selectQuery1 = $this->conn->query($select1);

                    // Check if the query was successful
                    if ($selectQuery1) {
                        // Fetch the result as an object
                        $result = $selectQuery1->fetch_object();

                        // Check if a result was found
                        if ($result) {
                            $getData[$columnIndices[array_search('Distributor', $requiredColumns)]] = $result->id;
                        } else {
                            echo "No result found.";
                        }
                    } else {
                        echo "Error executing query: " . $this->conn->error;
                    }

                    // Display row
                    // echo "<tr>";
                    // foreach ($columnIndices as $index) {
                    //     echo "<td>{$getData[$index]}</td>";
                    // }

                    $billNo         = preg_replace('/^[^a-zA-Z0-9]*/', '', $getData[$columnIndices[array_search('Bill No', $requiredColumns)]]);
                    $billDate       = $getData[$columnIndices[array_search('Bill Date', $requiredColumns)]];
                    $paymentMode    = $getData[$columnIndices[array_search('Payment Status', $requiredColumns)]];
                    $distributor    = $getData[$columnIndices[array_search('Distributor', $requiredColumns)]];
                    $taxable        = $getData[$columnIndices[array_search('Taxable', $requiredColumns)]];
                    $SGST           = $getData[$columnIndices[array_search('SGST', $requiredColumns)]];
                    $CGST           = $getData[$columnIndices[array_search('CGST', $requiredColumns)]];
                    $entryDate      = $getData[$columnIndices[array_search('Entry Date', $requiredColumns)]];
                    $totalAmount    = $getData[$columnIndices[array_search('Total', $requiredColumns)]];

                    $GST = $SGST + $CGST;

                    $items = 0;
                    $totalQty = 0;

                    $addStockIn = "INSERT INTO `stock_in` (`distributor_id`, `distributor_bill`, `items`, `total_qty`, `bill_date`, `due_date`, `payment_mode`, `gst`, `amount`, `added_by`, `added_on`, `admin_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $responce =  $this->conn->prepare($addStockIn);

                    // binding parameters --------
                    $responce->bind_param("isisssssssss", $distributor, $billNo, $items, $totalQty, $billDate, $billDate, $paymentMode, $GST, $totalAmount, $ADMINID, $entryDate, $ADMINID);

                    // Execute the prepared statement
                    if ($responce->execute()) {
                        // Get the ID of the newly inserted record
                        $addStockInId = $this->conn->insert_id;
                    } else {
                        // Handle the error (e.g., log or return an error message)
                        throw new Exception("Error executing SQL statement: " . $responce->error);
                    }


                    // echo "<td>{$billNo}</td>";
                    // echo "<td>{$billDate}</td>";
                    // echo "<td>{$paymentMode}</td>";
                    // echo "<td>{$distributor}</td>";
                    // echo "<td>{$taxable}</td>";
                    // echo "<td>{$SGST}</td>";
                    // echo "<td>{$SGST}</td>";
                    // echo "<td>{$entryDate}</td>";
                    // echo "<td>{$totalAmount}</td>";

                    // // Calculate and display amount
                    // $amount = $SGST +$SGST +$taxable;
                    // echo "<td>{$amount}</td>";
                    // echo "</tr>";
                }
            }

            // Close table
            echo "</table>";

            // Close file
            fclose($file);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }



    function currentStockImport($fileName, $ADMINID)
    {
        try {
            // Open the file
            $file = fopen($fileName, "r");
            if ($file === FALSE) {
                throw new Exception("Failed to open file.");
            }

            // Get the table header
            $tableHeader = fgetcsv($file, 10000, ",");
            if ($tableHeader === FALSE) {
                throw new Exception("Failed to read table header.");
            }

            // Check if "Item Name" is present in the required columns
            if (!in_array('Item Name', $tableHeader)) {
                throw new Exception("Item Name column is missing.");
            }

            $requiredColumns = ['Item Name', 'Unit', 'Batch', 'Expiry', 'Stock', 'LP', 'MRP', 'Stock by MRP', 'GST'];
            $columnIndices = array_map(function ($col) use ($tableHeader) {
                return array_search($col, $tableHeader);
            }, $requiredColumns);

            if (in_array(FALSE, $columnIndices, TRUE)) {
                throw new Exception("Required columns are missing.");
            }

            // Start building the table
            $HTMLTable =  "<table style='text-align: left;'><tr>";
            foreach ($requiredColumns as $header) {
                if ($header == "Unit") {
                    $HTMLTable .= "<th style='padding-right: 20px;'>Qty</th>";
                    $HTMLTable .= "<th style='padding-right: 20px;'>ItemUnit</th>";
                } elseif ($header == "Stock") {
                    $HTMLTable .= "<th style='padding-right: 20px;'>Pack Stock</th>";
                    $HTMLTable .= "<th style='padding-right: 20px;'>Loose Stock</th>";
                } elseif ($header == "MRP") {
                    $HTMLTable .= "<th style='padding-right: 20px;'>$header</th>";
                    $HTMLTable .= "<th style='padding-right: 20px;'>Loose Price</th>";
                } else {
                    $HTMLTable .= "<th style='padding-right: 20px;'>$header</th>";
                }
            }
            $HTMLTable .= "</tr>"; // Close the header row

            $sl = 1;
            // Read the rest of the file
            while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
                // Check if the "Bill No" column is not blank or only consists of white spaces
                if (trim($getData[$columnIndices[array_search('Item Name', $requiredColumns)]]) === '') {
                    continue; // Skip this line and continue to the next one
                }

                // Check if the row has sufficient data

                if (count($getData) >= count($requiredColumns)) {
                    // Extracting each required column value to a separate variable
                    $itemName   = $getData[$columnIndices[array_search('Item Name', $requiredColumns)]];
                    $unit       = $getData[$columnIndices[array_search('Unit', $requiredColumns)]];
                    $batch      = $getData[$columnIndices[array_search('Batch', $requiredColumns)]];
                    $stock      = $getData[$columnIndices[array_search('Stock', $requiredColumns)]];
                    $expiry     = $getData[$columnIndices[array_search('Expiry', $requiredColumns)]];
                    $lp         = $getData[$columnIndices[array_search('LP', $requiredColumns)]];
                    $mrp        = $getData[$columnIndices[array_search('MRP', $requiredColumns)]];
                    $SBMRP      = $getData[$columnIndices[array_search('Stock by MRP', $requiredColumns)]];
                    $gst        = $getData[$columnIndices[array_search('GST', $requiredColumns)]];

                    $slectProduct = "SELECT product_id FROM products WHERE name = '$itemName'";
                    $stmt = $this->conn->query($slectProduct);
                    if ($stmt->num_rows > 0) {
                        while ($res = $stmt->fetch_array()) {
                            $productId = $res['product_id'];
                        }
                    } else {
                        echo $itemName . '-' . $batch . '<br>';
                        continue;
                    }

                    if (preg_match('/^([\d.]+)\s*(.*)$/', $unit, $matches)) {
                        $weightage = $matches[1];
                        $itemUnit = $matches[2];

                        // Fetching the item unit id
                        $select = "SELECT * FROM `item_unit` WHERE `name` = '$itemUnit'";
                        $stmt = $this->conn->query($select);

                        if ($stmt->num_rows > 0) {

                            while ($row = $stmt->fetch_array()) {
                                $itemUnitId = $row['id'];
                            }
                        }
                    } else {
                        $weightage  = '';
                        $itemUnit   = '';
                    }


                    // Checking The Loose Usnits
                    if (in_array(strtolower($itemUnit), LOOSEUNITS)) {
                        $packQty = $SBMRP / $mrp;
                        $looseQty = $stock;
                        $loosePrice = round($mrp / $weightage, 2);
                    } else {
                        $packQty = $stock;
                        $looseQty = 0;
                        $loosePrice = 0;
                    }



                    // Build row HTML
                    $HTMLTable .= "<tr>";
                    $HTMLTable .= "<td>$productId</td>";
                    $HTMLTable .= "<td>$weightage</td>";
                    $HTMLTable .= "<td>$itemUnitId</td>";
                    $HTMLTable .= "<td>$batch</td>";
                    $HTMLTable .= "<td>$expiry</td>";
                    $HTMLTable .= "<td>$packQty</td>";
                    $HTMLTable .= "<td>$looseQty</td>";
                    $HTMLTable .= "<td>$lp</td>";
                    $HTMLTable .= "<td>$mrp</td>";
                    $HTMLTable .= "<td>$loosePrice</td>";
                    $HTMLTable .= "<td>$SBMRP</td>";
                    $HTMLTable .= "<td>$gst</td>";
                    $HTMLTable .= "</tr>";


                    // $insert = "INSERT INTO `current_stock` (`product_id`, `weightage`, `unit`, `batch_no`, `exp_date`, `qty`, `loosely_count`, `loosely_price`, `mrp`, `ptr`, `gst`, `added_by`, `added_on`, `admin_id`) 
                    // VALUES 
                    // ('$productId', '$weightage', '$itemUnitId', '$batch', '$expiry', '$packQty', '$looseQty', '$loosePrice', '$mrp', '$lp', '$gst', '$ADMINID', NOW(), '$ADMINID')";

                    // $stmt = $this->conn->query($insert);

                    // if (!$stmt) {
                    //     echo "Insertion Error! - $batch <br>";
                    // }
                }
                $sl++;
                if ($sl == 5) {
                    break;
                }
            }

            // Close table
            $HTMLTable .= "</table>";
            echo $HTMLTable;

            // Close file
            fclose($file);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

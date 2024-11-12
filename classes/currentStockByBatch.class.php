<?php

class CurrentStockByBatch{
    use DatabaseConnection;
    
    function addStockByBatch($productId, $batchNo, $expDate, $distributorId, $qty, $looselyCount, $looselyPrice, $weightage, $unit, $ptr, $gst, $mrp, $addedBy){

        $insert = "INSERT INTO `current_stock_by_batch` (`product_id`, `batch_no`, `exp_date`, `distributor_id`, `qty`, `loosely_count`, `loosely_price`, `weightage`, `unit`, `ptr`, `gst`, `mrp`, `added_by`) VALUES ('$productId', '$batchNo', '$expDate', '$distributorId', '$looselyCount', '$qty', '$looselyPrice', '$weightage', '$unit', '$ptr', '$gst', '$mrp', '$addedBy')";

        $res = $this->conn->query($insert);

        return $res;
    }//eof addProduct function 




    function incrStockByBatch($productId, $quantity){

        $incrCurrentStock = " UPDATE `current_stock_by_batch` SET `qty` = '$quantity' WHERE `current_stock_by_batch`.`product_id` = '$productId'";

        $incrCurrentStockQuery = $this->conn->query($incrCurrentStock);

        return $incrCurrentStockQuery;
    }//eof incrementCurrentStock function 





    function showStockByBatch(){
        $select = "SELECT * FROM current_stock_by_batch";
        $selectQuery = $this->conn->query($select);
        $rows = $selectQuery->num_rows;
        if ($rows == 0) {
            return 0;
        }else {
            while ($result = $selectQuery->fetch_array()) {
                $data[] = $result;
            }
            return $data;
        }
    }//eof showCurrentStoc function





    function showStocByBatchByPId($productId){
        $data = array();
        $select = "SELECT * FROM current_stock_by_batch WHERE `current_stock_by_batch`.`product_id` = '$productId'";
        $selectQuery = $this->conn->query($select);
        $rows = $selectQuery->num_rows;
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }

    function checkStockByBatchExist($productId){
        $data = array();
        $select = "SELECT product_id FROM current_stock_by_batch WHERE `current_stock_by_batch`.`product_id` = '$productId'";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }
        return $data;
    }




}//eof Products class


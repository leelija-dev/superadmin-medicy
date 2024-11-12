<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'stockIn.class.php';
require_once CLASS_DIR . 'stockOut.class.php';
require_once CLASS_DIR . 'products.class.php';

$StockOut   = new StockOut;
$Products   = new Products;

if (isset($_GET['sortVal']) && isset($_GET['startDt']) && isset($_GET['endDt'])) {

    $sortVal = $_GET['sortVal'];
    $startDate = $_GET['startDt'];
    $endDate = $_GET['endDt'];

    if($sortVal == 'asc'){
        if($startDate === 'allData' && $endDate === 'allData'){
            // $check = 1;
            $soldItemsData = json_decode($StockOut->stockOutDataFetchFromStart($adminId, 'DESC'));
        }

        if($startDate === 'soldLst24hrs' && $endDate === 'soldLst24hrs'){
            // $check = 2;
            $soldItemsData = json_decode($StockOut->dailyStockOutDataFetch($adminId, 'DESC'));
        }

        if(($startDate !== 'allData') &&  ($startDate !== 'soldLst24hrs') && ($endDate !== 'allData') && ($endDate !== 'soldLst24hrs')){
            // $check = 3;
            $soldItemsData = json_decode($StockOut->stockOutDataFetchByRange($startDate, $endDate, 'DESC', $adminId));
        }
    }

    if($sortVal == 'dsc'){
        if($startDate === 'allData' && $endDate === 'allData'){
            // $check = 11;
            $soldItemsData = json_decode($StockOut->stockOutDataFetchFromStart($adminId, 'ASC'));
        }

        if($startDate === 'soldLst24hrs' && $endDate === 'soldLst24hrs'){
            // $check = 21;
            $soldItemsData = json_decode($StockOut->dailyStockOutDataFetch($adminId, 'ASC'));
        }

        if(($startDate !== 'allData') &&  ($startDate !== 'soldLst24hrs') && ($endDate !== 'allData') && ($endDate !== 'soldLst24hrs')){
            // $check = 31;
            $soldItemsData = json_decode($StockOut->stockOutDataFetchByRange($startDate, $endDate, 'ASC', $adminId));
        }
    }

    
    $updatedProductDataArray = [];
    if($soldItemsData->status == 1){
        $soldItemsData = $soldItemsData->data;

        foreach($soldItemsData as $item){
            $prodData = json_decode($Products->showProductNameById($item->product_id));
            if($prodData->status == 1){
                $updatedProductDataArray[$prodData->data->name] = $item->total_sold;
            }
        }
        print_r(json_encode(['status'=>'1', 'data'=>$updatedProductDataArray]));
    }else{
        print_r(json_encode(['status'=>'0']));
    }

}else{
    print_r('null');
}

?>
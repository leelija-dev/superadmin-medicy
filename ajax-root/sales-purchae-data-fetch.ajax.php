<?php

require_once dirname(__DIR__) . '/config/constant.php';
require_once ROOT_DIR . '_config/sessionCheck.php';

require_once CLASS_DIR . 'dbconnect.php';
require_once CLASS_DIR . 'stockIn.class.php';
require_once CLASS_DIR . 'stockOut.class.php';


$StockIn   = new StockIn;
$StockOut   = new StockOut;

if (isset($_GET['startDt']) && isset($_GET['endDt'])) {

    $salesData = $StockOut->selectStockOutDataOnDateFilter($_GET['startDt'], $_GET['endDt'], $ADMINID);
    $purchaseData = $StockIn->purchaseDatafetchByDateRange($_GET['startDt'], $_GET['endDt'], $ADMINID);

    if ($salesData != null || $purchaseData != null) {

        $mergedArray = [];

        $totalSalesCount = 0;
        $totalSalesAmount = 0;
        if ($salesData != null) {
            $maxSellAmount = 0;
            foreach ($salesData as $item) {
                if (floatval($item->sell_amount) > floatval($maxSellAmount)) {
                    $maxSellAmount = $item->sell_amount;
                }
                $totalSalesCount += intval($item->invoice_count);
                $totalSalesAmount += floatval($item->sell_amount);
            }
            $totalSalesAmount = round($totalSalesAmount, 2);


            foreach ($salesData as $item) {
                $date = $item->{'sell_date'};
                $mergedArray[$date]['sales_amount'] = $item->{'sell_amount'};
            }
        } else {
            $totalSalesCount = 0;
            $totalSalesAmount = 0;
            $maxSellAmount = 0;
        }


        $totalPurchseCount = 0;
        $totalPurchaseAmount = 0;
        $maxPurchaseAmount = 0;
        if ($purchaseData != null) {
            foreach ($purchaseData as $item) {
                if (floatval($item->stockin_amount) > floatval($maxPurchaseAmount)) {
                    $maxPurchaseAmount = $item->stockin_amount;
                }
                $totalPurchseCount += intval($item->id);
                $totalPurchaseAmount += floatval($item->stockin_amount);
            }
            $totalPurchaseAmount = round($totalPurchaseAmount, 2);

            foreach ($purchaseData as $item) {
                $date = $item->{'purchase_date'};
                $mergedArray[$date]['purchase_amount'] = $item->{'stockin_amount'};
            }
        } else {
            $totalPurchseCount = 0;
            $totalPurchaseAmount = 0;
        }




        // ===== y axis max val finding ======
        if (floatval($maxSellAmount) > floatval($maxPurchaseAmount)) {
            $maxVal = ceil($maxSellAmount);
        } else {
            $maxVal = ceil($maxPurchaseAmount);
        }
        $maxVal = round($maxVal, 0);
        $checkLength1 = strlen((string)$maxVal);
        $checkLength2 = intval($checkLength1 - 2);
        if (intval($checkLength2) < 0) {
            $checkLength2 = 0;
        }
        $power = pow(10, $checkLength2);
        $lowerVal = intdiv($maxVal, $power);
        $upperVal = intval($lowerVal) + 1;
        $yAxisUpperVal = intval($upperVal) * intval($power);



        // filling missing dates
        $dates = array_keys($mergedArray);
        $earliestDate = min($dates);
        $latestDate = max($dates);
        $currentDate = $earliestDate;
        while ($currentDate <= $latestDate) {
            if (!isset($mergedArray[$currentDate])) {
                $mergedArray[$currentDate] = ['sales_amount' => 0, 'purchase_amount' => 0];
            }
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }

        // sorting data
        ksort($mergedArray);

        // modify dates
        $updatedDataArray = [];
        $filterDate = [];
        foreach ($mergedArray as $date => $values) {
            $formattedDate = date('M d', strtotime($date));
            $updatedDataArray[$formattedDate] = $values;
            $filterDate[] = $formattedDate;
        }


        $returnData = json_encode(['status' => '1', 'totalSellCount' => $totalSalesCount, 'totalSellAmount' => $totalSalesAmount, 'totalPurchaseCount' => $totalPurchseCount, 'totalPurchaseAmount' => $totalPurchaseAmount, 'sellPurchaseDataArray' => $updatedDataArray, 'yAxisVal' => $maxVal]);

    } else {

        $startDate = $_GET['startDt'];
        $endDate = $_GET['startDt'];

        $currentDate = $startDate;

        while ($currentDate <= $endDate) {
            $dateArray[] = date('M d',strtotime($currentDate));
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }

        $returnData = json_encode(['status' => '0', 'filterDate'=>$dateArray]);
    }
    print_r($returnData);
}

<?php
require_once dirname(__DIR__). '/config/constant.php';

require_once CLASS_DIR.'dbconnect.php';

$includePath = get_include_path();

$maxPurchase = $StockIn->selectDistOnMaxPurchase($adminId);
$maxPurchase = json_decode($maxPurchase);
$distNameOnMaxPurchase = $Distributor->distributorDetail($maxPurchase->distributor_id);

// print_r($maxPurchase);
echo $maxPurchase->total_purchase_amount;

// print_r($distNameOnMaxPurchase);
echo $distNameOnMaxPurchase->name;



$maxItemPurchase = $StockIn->selectDistOnMaxItems($adminId);
$maxItemPurchase = json_decode($maxItemPurchase);
// print_r($maxItemPurchase);
echo $maxItemPurchase->number_of_purchases;
$distNameOnMaxItem = $Distributor->distributorDetail($maxItemPurchase->distributor_id);
// print_r($distNameOnMaxItem);
echo $distNameOnMaxItem->name;




?>

<div class="card border-left-info border-right-info h-100 py-2 pending_border animated--grow-in">
    <div class="d-flex justify-content-end px-2">
        <div id="datePickerDiv" style="display: none;">
            <input type="date" id="dateInput">
            <button class="btn btn-sm btn-primary" id="added_on" value="CR" onclick="getDates(this.value)" style="height: 2rem;">Find</button>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-light text-dark card-btn dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <!-- <img src=" IMG_PATH./arrow-down-sign-to-navigate.jpg" alt=""> -->

                <b>...</b>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <button class="dropdown-item" type="button" id="amount" onclick="checkSell(this.id)">By Amount</button>
                <button class="dropdown-item" type="button" id="items" onclick="checkSell(this.id)">By Items</button>
            </div>
        </div>
    </div>
    <div class="card-body pb-0">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    most purchaed distributor</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                    <label type="symble" id="rupeeSymble" name="rupeeSymble">₹</label>
                    <label type="text" id="salesAmount" name="salesAmount"></label>
                </div>
                <label type="text" id="distId" name="distId"><small></small></label>
            </div>
            <div class="col-auto">
                <i class="fas fa-rupee-sign"></i>
            </div>
        </div>
    </div>
</div>

<script>
    const checkSell = (id) => {
        if (id == 'amount') {
            console.log(id);
            var phpVal = "<?php echo $distName->name; ?>";
            console.log(phpVal);
            document.getElementById("salesAmount").textContent = phpVal;
            document.getElementById("distId").innerHTML = phpVal;


        }

        if (id == 'items') {
            console.log(id);
            // maxItemsUrl = `../admin/ajax/dist-on-sales.ajax.php?items=${id}`;
            // xmlhttp.open("GET", maxItemsUrl, false);
            // xmlhttp.send(null);
            // document.getElementById("salesAmount").innerHTML = xmlhttp.responseText;
            // document.getElementById("distId").innerHTML = xmlhttp.responseText;
        }

    }
</script>
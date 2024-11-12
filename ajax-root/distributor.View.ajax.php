<?php 
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'distributor.class.php';


$distributorId = $_GET['Id'];

$Distributor        = new Distributor();
$showDistributor    = $Distributor->showDistributorById($distributorId);
$showDistributor    = json_decode($showDistributor);


if (isset($showDistributor->status) && $showDistributor->status == 1) {
    $data = $showDistributor->data;

    if (!empty($data)) {
        $DistributorName    = $data->name;
        $DistributorGSTIN    = $data->gst_id;
        $DistributorAddress = $data->address;
        $DistributorPIN     = $data->area_pin_code;
        $DistributorPhno    = $data->phno;
        $DistributorEmail   = $data->email;
        $DistributorDsc     = $data->dsc;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Custom fonts for this template-->
    <link href="<?= PLUGIN_PATH ?>fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?= CSS_PATH ?>sb-admin-2.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="<?= CSS_PATH ?>bootstrap 5/bootstrap.css">

</head>

<body class="mx-2">

    <form >
        <input type="hidden" id="distributorId" value="<?php echo $distributorId;?>">
       
        <div class="col-sm-12 mt-2">
                    <div class="form-group">
                          <input type="text" class=" med-input" id="distributor-name" name="distributor-name" placeholder="" autocomplete="off" maxlength="155" value="<?php echo $DistributorName; ?>">   
                          <label  class="med-label" for="distributor-name">Distributor Name:</label> 
                      </div>

        </div>

        <div class="col-md-12">
            <input type="text" class="med-input" id="distributor-gstin"  value="<?php echo $DistributorGSTIN; ?>" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="15" oninput="javascript: if (this.value.length > this.minLength) this.value = this.value.slice(0, this.minLength);" minlength="15" required >
            <label class="med-label" for="distributor-gstin">GSTIN</label>
        </div> 

        <div class="col-sm-12 mt-2">
                    <div class="form-group">
                          <input type="number" class=" med-input" id="distributor-phno" name="distributor-phno" placeholder="" autocomplete="off" value="<?php echo $DistributorPhno; ?>"oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="10" oninput="javascript: if (this.value.length > this.minLength) this.value = this.value.slice(0, this.minLength);" minlength="10" required>   
                          <label  class=" med-label" for="distributor-phno">Mobile Number</label>
                        
                      </div>
               </div>


        <div class="col-sm-12 mt-2">
                    <div class="form-group">
                          <input type="email" class=" med-input" id="distributor-email"  autocomplete="off" value="<?php echo $DistributorEmail; ?>" >   
                          <label  class=" med-label" for="distributor-email" >Email Address</label>
                        
                      </div>
               </div>

        

        <div class="col-sm-12 mt-2">
                 <div class="form-group">                                  
                        <textarea class="med-input " name="distributor-address"  id="distributor-address" cols="30" rows="3" maxlength="255" ><?php echo $DistributorAddress; ?></textarea>
                            <label class=" med-label" for="distributor-address">Address:</label>
                            </div>
                    </div>


            <div class="col-sm-12 mt-2">
                    <div class="form-group">
                          <input type="number" class=" med-input" id="distributor-pin" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" oninput="javascript: if (this.value.length > this.minLength) this.value = this.value.slice(0, this.minLength);" minlength="6" value="<?php echo $DistributorPIN; ?>">   
                          <label  class=" med-label" for="distributor-area-pin">Area PIN :</label>
                        
                      </div>
               </div>


               <div class="col-sm-12 mt-2">
                 <div class="form-group">                                  
                        <textarea class="med-input distributor-dsc"   id="distributor-dsc"   rows="2" maxlength="355" ><?php echo $DistributorDsc; ?></textarea>
                            <label class=" med-label" for="distributor-dsc">Description :</label>
                            </div>
                    </div>

        

      


        <div class="mt-2 reportUpdate" id="reportUpdate">
            <!-- Ajax Update Reporet Goes Here -->
        </div>

        <div class="mt-2 d-flex justify-content-end">
            <button type="button" class="btn btn-sm btn-primary" onclick="editDist();">Update</button>
        </div>

    </form>

    

    <script src="<?= JS_PATH ?>ajax.custom-lib.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= PLUGIN_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-4/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Js -->
    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.js"></script>
    <script src="<?= JS_PATH ?>bootstrap-js-5/bootstrap.min.js"></script>


    <script>
    // function editDist() 
    function editDist(){
        // alert('Working');
        // let Id        = $("#distributorId").val();
        let Id      = document.getElementById("distributorId").value;
        let name      = document.getElementById("distributor-name").value;
        let gstin      = document.getElementById("distributor-gstin").value;
        let phno      = document.getElementById("distributor-phno").value;
        let email     = document.getElementById("distributor-email").value;
        let address   = document.getElementById("distributor-address").value;
        let areaPin  = document.getElementById("distributor-pin").value;
        let dsc       = document.getElementById("distributor-dsc").value;


        let url = "distributor.Edit.ajax.php?id=" + escape(Id) + "&name=" + escape(name) + "&gstin=" + escape(gstin) + "&phno=" + escape(phno) + "&email=" + escape(email) + "&address=" + escape(address) + "&pin=" + escape(areaPin) + "&dsc=" + escape(dsc);
        
        request.open('GET', url, true);
 
        request.onreadystatechange = getEditUpdates;

        request.send(null);
    }//eof editDist

    function getEditUpdates() {
        if (request.readyState == 4) {
            if (request.status == 200) {
                var xmlResponse = request.responseText;
                document.getElementById('reportUpdate').innerHTML = xmlResponse;
            } else if (request.status == 404) {
                alert("Request page doesn't exist");
            } else if (request.status == 403) {
                alert("Request page doesn't exist");
            } else {
                alert("Error: Status Code is " + request.statusText);
            }
        }
    } //eof getEditUpdates

    </script>

</body>

</html>

 <!-- <div class="form-group">
            <label for="distributor-name" class="form-label mb-0 mt-0">Distributor Name:</label>
            <input type="text" class="form-control" id="distributor-name" value="<?php echo $DistributorName; ?>">
        </div> -->

         <!-- <div class="form-group">
            <label for="distributor-name" class="form-label mb-0 mt-0">GSTIN:</label>
            <input type="text" class="form-control" id="distributor-gstin" value="<?php echo $DistributorGSTIN; ?>">
        </div> -->

        <!-- <div class="form-group">
            <label for="distributor-phno" class="form-label mb-0">Distributor Contact:</label>
            <input type="text" class="form-control" id="distributor-phno" value="<?php echo $DistributorPhno; ?>">
        </div> -->

        <!-- <div class="form-group">
            <label for="distributor-email" class="form-label mb-0">Distributor Email:</label>
            <input type="text" class="form-control" id="distributor-email" value="<?php echo $DistributorEmail; ?>">
        </div> -->

        <!-- <div class="form-group">
            <label for="distributor-address" class="form-label mb-0 mt-2">Distributor Address:</label>
            <textarea class="form-control" id="distributor-address" rows="3"><?php echo $DistributorAddress; ?></textarea>
        </div> -->

        <!-- <div class="form-group">
            <label for="distributor-area-pin" class="form-label mb-0">Area PIN:</label>
            <input type="text" class="form-control" id="distributor-pin" value="<?php echo $DistributorPIN; ?>">
        </div> -->

          <!-- <div class="form-group">
            <label for="distributor-dsc" class="form-label mb-0 mt-2">Description:</label>
            <textarea class="form-control" id="distributor-dsc" rows="2"><?php echo $DistributorDsc; ?></textarea>
        </div> -->

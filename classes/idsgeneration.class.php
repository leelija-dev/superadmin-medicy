<?php
class IdsGeneration
{

    use DatabaseConnection;

    function generateOrderId()
    {
        // Generate random number
        $randomNumber = mt_rand(1, 99999);

        // Generate product ID with prefix "MED"
        $orderId = "MED" . str_pad($randomNumber, 9, "0", STR_PAD_LEFT);

        // Check if product ID exists in the database
        $stmt = $this->conn->prepare("SELECT * FROM subscription WHERE order_id = ?");
        $stmt->bind_param("s", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();

        // If product ID exists, generate a new one recursively
        if ($result->num_rows > 0) {
            // Generate a new product ID recursively
            return $this->generateOrderId();
        } else {
            // Product ID does not exist, return the generated ID
            return $orderId;
        }
    }

    function generateAdminId()
    {

        $dateTimeFormatted = date("ymdHis", strtotime(NOW));

        $dateTime = new DateTime();
        $microsecond =  $dateTime->format("u");
        $uniquenumber = $dateTimeFormatted . $microsecond;
        $uniqueID = substr($uniquenumber, 0, 15);

        // Construct the final ADM ID with the current date
        $newID = "ADM{$uniqueID}";

        return $newID;
    }

    function generateClinicId($adminId)
    {

        $newId = filter_var($adminId, FILTER_SANITIZE_NUMBER_INT);
        return $newId;
    }


    function patientidGenerate()
    {
        $select = "SELECT * FROM patient_details";
        $selectQuery = $this->conn->query($select);
        $data = [];
        while ($result = $selectQuery->fetch_array()) {
            $data[] = $result;
        }

        $sl = count($data) + 1;

        // Generate the padded ID string based on $sl value
        if ($sl < 10) {
            $sl = "000000000$sl";
        } elseif ($sl >= 10 && $sl < 100) {
            $sl = "00000000$sl";
        } elseif ($sl >= 100 && $sl < 1000) {
            $sl = "0000000$sl";
        } elseif ($sl >= 1000 && $sl < 10000) {
            $sl = "000000$sl";
        } elseif ($sl >= 10000 && $sl < 100000) {
            $sl = "00000$sl";
        } elseif ($sl >= 100000 && $sl < 1000000) {
            $sl = "0000$sl";
        } elseif ($sl >= 1000000 && $sl < 10000000) {
            $sl = "000$sl";
        } elseif ($sl >= 10000000 && $sl < 100000000) {
            $sl = "00$sl";
        } elseif ($sl >= 100000000 && $sl < 1000000000) {
            $sl = "0$sl";
        }

        $alph = 'A';
        $patientId = "PE$alph$sl";

        // Check for duplicates and increment $sl if needed
        $checkQuery = $this->conn->prepare("SELECT * FROM patient_details WHERE patient_id = ?");
        $checkQuery->bind_param("s", $patientId);

        while (true) {
            $checkQuery->execute();
            $result = $checkQuery->get_result();
            if ($result->num_rows == 0) {
                // Unique patient ID found
                break;
            }
            $sl++;
            $patientId = sprintf("PE%s%09d", $alph, $sl);
        }

        $checkQuery->close();
        return $patientId;
    }



    // function getAppointmentIds()
    // {
    //     $data = array(); // Initialize the array

    //     try {
    //         $select = "SELECT appointment_id FROM appointments ORDER BY added_on ASC";
    //         $stmt = $this->conn->prepare($select);

    //         if ($stmt) {
    //             $stmt->execute();
    //             $result = $stmt->get_result();

    //             while ($row = $result->fetch_array()) {
    //                 $data[] = $row;
    //             }

    //             $stmt->close();
    //         } else {
    //             echo "Statement preparation failed: " . $this->conn->error;
    //         }
    //     } catch (Exception $e) {
    //         echo "Error: " . $e->getMessage();
    //     }

    //     return $data;
    // }


    function getAppointmentIds($startsWith)
{
    $data = array(); // Initialize the array

    try {
        $select = "SELECT appointment_id FROM appointments WHERE appointment_id LIKE '$startsWith%' ORDER BY added_on ASC";
        $stmt = $this->conn->prepare($select);

        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_array()) {
                $data[] = $row;
            }

            $stmt->close();
        } else {
            echo "Statement preparation failed: " . $this->conn->error;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    return $data;
}


function appointmentidGeneration($startsWith)
{
    // Get the list of IDs that start with the specified prefix
    $idList = $this->getAppointmentIds($startsWith);

    //get the appointment isd into a single array
    $existingIds = array_map(function ($row) {
        return $row['appointment_id'];
    }, $idList);

    $lastid = 0;

    // Check if there are any existing appointments
    if (!empty($idList)) {
        $lastAppointment = end($idList);
        $lastAppointment = $lastAppointment['appointment_id'];
        $parts = explode('-', $lastAppointment);
        $lastid = (int) end($parts);
    }

    // Generate a new appointment ID and check if it exists
    do {
        $lastid += 1;
        $tempappointmentid = $startsWith . '-' . $lastid;
    } while (in_array($tempappointmentid, $existingIds));

    // Return the generated appointment ID
    return $tempappointmentid;
}


    function generateAppointmentSerial($doctorId, $appointmentDate, $adminId)
    {
        // Prepare the SQL query to find the highest serial number for the given doctor, date, and admin
        $query = "SELECT MAX(sl_no) AS max_serial FROM appointments 
                  WHERE doctor_id = ? AND appointment_date = ? AND admin_id = ?";

        // Prepare and execute the statement
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isi", $doctorId, $appointmentDate, $adminId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Get the maximum serial number from the result
        $row = $result->fetch_assoc();
        $maxSerial = isset($row['max_serial']) ? $row['max_serial'] : 0;

        // Increment serial number by 1 if appointments exist, otherwise start from 1
        $newSerial = $maxSerial + 1;

        // Close statement and result set
        $stmt->close();

        // Return the new serial number
        return $newSerial;
    }


    function empIdGenerate($orgName)
    {
        // Split the string into words
        $words = explode(' ', $orgName);

        // Initialize an empty result string
        $prefix = '';

        // Loop through the first two words and get their first characters
        for ($i = 0; $i < 2; $i++) {
            if (isset($words[$i])) {
                $prefix .= substr($words[$i], 0, 1);
            }
        }

        // Generate a random number between 10000000 and 99999999
        $randomNumber = mt_rand(10000000, 99999999);

        return $prefix . $randomNumber;
    }








    function otpGgenerator()
    {

        $characters = '0123456789';
        $randomString = '';
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        // $randomString = $randomString;

        return $randomString;
    }




    function lastAdminId()
    {
        $sql = "SELECT admin_id FROM `admin` ORDER BY added_on DESC LIMIT 1";
        $query = $this->conn->query($sql);
        if ($query->num_rows > 0) {

            while ($result = $query->fetch_array()) {
                $data = $result['admin_id'];
            }
            return $data;
        }
        return;
    }


    function pharmecyInvoiceId()
    {
        // $data = array();
        // $select = "SELECT * FROM stock_out";
        // $selectQuery = $this->conn->query($select);
        // while ($result = $selectQuery->fetch_array()) {
        //     $data[]    = $result;
        // }
        // $invoice = count($data) + 1;
        // return $invoice;

        // Initialize the new invoice ID
        $invoice = 1;

        // SQL query to get the maximum ID from the stock_out table
        $select = "SELECT MAX(invoice_id ) as max_id FROM stock_out";
        $selectQuery = $this->conn->query($select);

        if ($selectQuery) {
            $result = $selectQuery->fetch_assoc();

            // Check if there's a result and fetch the maximum ID
            if ($result && $result['max_id'] !== null) {
                $invoice = intval($result['max_id']) + 1;
            }
        }

        // Return the new invoice ID
        return $invoice;
    }


    function stockReturnId()
    {
        $data = array();
        $select = "SELECT * FROM stock_return";
        $selectQuery = $this->conn->query($select);
        while ($result = $selectQuery->fetch_array()) {
            $data[]    = $result;
        }
        $id = count($data) + 1;
        return $id;
    }


    function generateProductId()
    {
        // Generate random number
        $randomNumber = mt_rand(1, 999999999999);

        // Generate product ID with prefix "PR"
        $productId = "PR" . str_pad($randomNumber, 12, "0", STR_PAD_LEFT);

        // Check if product ID exists in the database
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE product_id = ?");
        $stmt->bind_param("s", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        // If product ID exists, generate a new one recursively
        if ($result->num_rows > 0) {
            // Generate a new product ID recursively
            return $this->generateProductId();
        } else {
            // Product ID does not exist, return the generated ID
            return $productId;
        }
    }


    // public function generateLabBillId() {
    //     $query = "SELECT bill_id FROM lab_billing";
    //     $result = $this->conn->query($query);

    //     $existingBillIds = [];
    //     if ($result->num_rows > 0) {
    //         while ($row = $result->fetch_assoc()) {
    //             $existingBillIds[] = $row['bill_id'];
    //         }
    //     }

    //     $newBillId = 'ML' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);;

    //     if (in_array($newBillId, $existingBillIds)) {
    //         $this->generateLabBillId();
    //     }else {
    //         return $newBillId;
    //     }
    // }


}

// $id = new IdsGeneration();

// echo $id->lastAdminId();
// echo '<br>';
// echo $id->generateAdminId();

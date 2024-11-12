<?php
class Subscription
{
    use DatabaseConnection;

    function createSubscription($order_id, $adminId, $plan, $startDate, $endDate, $paidAmount, $status, $msg="")
    {
        try {
            $query = "INSERT INTO subscription (order_id, admin_id, plan, start, end, amount, status, txn_msg) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);

            if ($stmt) {
                $stmt->bind_param("ssssssss", $order_id, $adminId, $plan, $startDate, $endDate, $paidAmount, $status, $msg);
                $success = $stmt->execute();

                if ($success) {
                    $stmt->close();
                    return true;
                } else {
                    $stmt->close();
                    throw new Exception("Subscription creation failed: " . $stmt->error);
                }
            } else {
                throw new Exception("Error preparing subscription statement: " . $this->conn->error);
            }
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            return "Error => " . $e->getMessage();
        }
    }



    function getSubscription($adminId = '')
    {
        try {
            // Query to get subscription information for the given admin ID
            if (!empty($adminId)) {
                $query = "SELECT * FROM subscription WHERE admin_id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("s", $adminId);
            } else {
                $query = "SELECT * FROM subscription ";
                $stmt = $this->conn->prepare($query);
            }
            // $stmt = $this->conn->prepare($query);

            if ($stmt) {
                // $stmt->bind_param("s", $adminId); // Use "s" for integer
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $subscriptions = [];
                    while ($row = $result->fetch_assoc()) {
                        $subscriptions[] = $row;
                    }
                    return json_encode(['status' => 1, 'msg' => 'success', 'data' => $subscriptions]);
                }

                $stmt->close();
            }

            return json_encode(['status' => 0, 'msg' => 'No subscriptions found for the given admin ID']);
        } catch (Exception $e) {
            // Handle any exceptions that may occur during the database operation
            error_log("Error getting subscription: " . $e->getMessage());
            return json_encode(['status' => 0, 'msg' => "Error: " . $e->getMessage()]);
        }
    }





    function getSubscriptionOnPlanId($adminId = '')
    {
        try {
            // Query to get subscription information for the given admin ID
            if (!empty($adminId)) {
                $query = "SELECT * FROM subscription WHERE admin_id = ? AND plan = 1;";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("s", $adminId);
            } else {
                $query = "SELECT * FROM subscription WHERE plan = 1";
                $stmt = $this->conn->prepare($query);
            }
            // $stmt = $this->conn->prepare($query);

            if ($stmt) {
                // $stmt->bind_param("s", $adminId); // Use "s" for integer
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $subscriptions = [];
                    while ($row = $result->fetch_assoc()) {
                        $subscriptions[] = $row;
                    }
                    return json_encode(['status' => 1, 'msg' => 'success', 'data' => $subscriptions]);
                }

                $stmt->close();
            }

            return json_encode(['status' => 0, 'msg' => 'No subscriptions found for the given admin ID']);
        } catch (Exception $e) {
            // Handle any exceptions that may occur during the database operation
            error_log("Error getting subscription: " . $e->getMessage());
            return json_encode(['status' => 0, 'msg' => "Error: " . $e->getMessage()]);
        }
    }






    function getSubscriptionByStatusData($adminId = '')
    {
        try {
            // Query to get subscription information for the given admin ID
            if (!empty($adminId)) {
                $query = "SELECT * FROM subscription WHERE admin_id = ? AND `status` = 0";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("s", $adminId);
            } else {
                $query = "SELECT * FROM subscription WHERE `status` = 0";
                $stmt = $this->conn->prepare($query);
            }
            // $stmt = $this->conn->prepare($query);

            if ($stmt) {
                // $stmt->bind_param("s", $adminId); // Use "s" for integer
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $subscriptions = [];
                    while ($row = $result->fetch_assoc()) {
                        $subscriptions[] = $row;
                    }
                    return json_encode(['status' => 1, 'msg' => 'success', 'data' => $subscriptions]);
                }

                $stmt->close();
            }

            return json_encode(['status' => 0, 'msg' => 'No subscriptions found for the given admin ID']);
        } catch (Exception $e) {
            // Handle any exceptions that may occur during the database operation
            error_log("Error getting subscription: " . $e->getMessage());
            return json_encode(['status' => 0, 'msg' => "Error: " . $e->getMessage()]);
        }
    }




    function checkSubscription($adminId, $today = '')
    {
        // Query to get subscription information for the given admin ID
        $query = "SELECT end FROM subscription WHERE admin_id = ? ORDER BY end DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $adminId);

        // $stmt = $this->conn->prepare($query);

        if ($stmt) {
            // $stmt->bind_param("s", $adminId); // Use "s" for integer
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $endDate = $row['end'];
                }

                $stmt->close();

                // Check if any subscription end date is greater than or equal to the current date
                $endDateObject = new DateTime($endDate);
                $endDateObject->setTime(0, 0, 0);

                $todayObject = new DateTime($today);
                $todayObject->setTime(0, 0, 0);

                if ($todayObject <= $endDateObject) {
                    // Subscription is not expired
                    return true;
                }

                // All subscriptions are expired
                return false;
            } else {
                // No subscriptions found for the given admin ID
                $stmt->close();
                return false;
            }
        } else {
            // Error in preparing the statement
            return false;
        }
    }


    function checkPaymentStatus($admin_id, $order_id, $match)
    {
        // Prepare the SQL query
        $sql = "SELECT status FROM subscription WHERE admin_id = ? AND order_id = ?";

        // Initialize the statement
        if ($stmt = $this->conn->prepare($sql)) {
            // Bind the parameters
            $stmt->bind_param("ss", $admin_id, $order_id);

            // Execute the statement
            $stmt->execute();

            // Bind the result variable
            $stmt->bind_result($db_status);

            // Fetch the result
            if ($stmt->fetch()) {
                // Check if the status matches
                if ($db_status === $match) {
                    return true;
                }
            }

            // Close the statement
            $stmt->close();
        }

        return false;
    }



    function updateSubscription($admin_id, $order_id, $referenceId, $cf_order_id, $txn_msg, $txn_time, $amount, $payment_mode, $status, $start, $expiry)
    {
        try {
            if (!$this->checkPaymentStatus($admin_id, $order_id, "SUCCESS")) {
                // Start a transaction
                $this->conn->begin_transaction();

                // Update subscription information
                $subscriptionUpdate = $this->updateSubscriptionDetails($admin_id, $order_id, $referenceId, $cf_order_id, $txn_msg, $txn_time, $amount, $payment_mode, $status, $start, $expiry);

                if ($subscriptionUpdate) {
                    // Update admin expiry date
                    if ($this->updateAdminExpiry($admin_id, $expiry)) {
                        // Commit the transaction
                        $this->conn->commit();

                        // Return success response
                        return json_encode(['status' => 1, 'msg' => "success"]);
                    } else {
                        throw new Exception("Admin update failed.");
                    }
                } else {
                    throw new Exception("Subscription update failed.");
                }
            }else {
                return json_encode(['status' => 1, 'msg' => "Already Paid"]);
            }
        } catch (Exception $e) {
            // Rollback the transaction on error
            $this->conn->rollback();

            // Log the error and return error response
            error_log("Error: " . $e->getMessage());
            return json_encode(['status' => 0, 'msg' => "Error: " . $e->getMessage()]);
        }
    }


    private function updateSubscriptionDetails($admin_id, $order_id, $referenceId, $cf_order_id, $txn_msg, $txn_time, $amount, $payment_mode, $status, $start, $expiry)
    {
        $subscriptionQuery = "UPDATE subscription SET referenceId = ?, cf_order_id = ?, txn_msg = ?, txn_time = ?, amount = ?, payment_mode = ?, status = ?, start = ?, end = ? WHERE admin_id = ? AND order_id = ?";

        $subscriptionStmt = $this->conn->prepare($subscriptionQuery);

        if ($subscriptionStmt) {
            // Bind parameters with correct types
            $subscriptionStmt->bind_param("ssssdssssss", $referenceId, $cf_order_id, $txn_msg, $txn_time, $amount, $payment_mode, $status, $start, $expiry, $admin_id, $order_id);

            $subscriptionSuccess = $subscriptionStmt->execute();

            if (!$subscriptionSuccess) {
                // Log the error if the execution failed
                error_log("Error executing subscription statement: " . $subscriptionStmt->error);
            }

            $subscriptionStmt->close();

            return $subscriptionSuccess;
        } else {
            throw new Exception("Error preparing subscription statement: " . $this->conn->error);
        }
    }


    private function updateAdminExpiry($admin_id, $expiry)
    {
        $updateAdminQuery = "UPDATE admin SET expiry = ? WHERE admin_id = ?";
        $updateAdminStmt = $this->conn->prepare($updateAdminQuery);

        if ($updateAdminStmt) {
            $updateAdminStmt->bind_param("ss", $expiry, $admin_id);
            $updateAdminSuccess = $updateAdminStmt->execute();
            $updateAdminStmt->close();

            return $updateAdminSuccess;
        } else {
            throw new Exception("Error preparing admin update statement: " . $this->conn->error);
        }
    }
}

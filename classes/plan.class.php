<?php
class Plan
{
    use DatabaseConnection;

    public function addPlanWithFeatures(string $planName, string $permissions, string $duration, float $price, int $status, array $featuresArr)
    {
        try {
            // Begin transaction for data integrity
            //   $this->conn->beginTransaction();

            $insertedPlanId = $this->addPlan($planName, $permissions, $duration, $price, $status);

            if (!$insertedPlanId) {
                throw new Exception("Failed to insert plan data");
            }

            $insertResult = $this->insertPlanFeatures($insertedPlanId, $featuresArr);

            if (!$insertResult) {
                throw new Exception("Failed to insert plan features");
            }

            $this->conn->commit();

            return json_encode(['status' => 1, 'msg' => "Added Plan Id is: $insertedPlanId"]);
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
            return json_encode(['status' => 0, 'msg' => 'ERROR: ' . $e]);
        }
    }

    private function addPlan(string $planName, string $permissions, string $duration, float $price, int $status)
    {
        $sql = "INSERT INTO plans (name, permission_id, duration, price, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssssi', $planName, $permissions, $duration, $price, $status);
        $stmt->execute();

        return $stmt->insert_id;
    }



    function allPlans()
    {
        try {
            $query = "SELECT * FROM plans";
            $stmt = $this->conn->prepare($query);

            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $subscriptions = [];
                    while ($row = $result->fetch_assoc()) {
                        $features = $this->planFeatureById($row['id']);
                        $row['features'] = json_decode($features);
                        $subscriptions[] = $row;
                    }
                    return json_encode(['status' => 1, 'msg' => 'success', 'data' => $subscriptions]);
                }

                $stmt->close();
            }

            return json_encode(['status' => 0, 'msg' => 'No subscriptions found for the given Plan ID']);
        } catch (Exception $e) {
            // Handle any exceptions that may occur during the database operation
            error_log("Error getting subscription: " . $e->getMessage());
            return json_encode(['status' => 0, 'msg' => "Error: " . $e->getMessage()]);
        }
    }



    function getAllPlansById($planId)
    {
        try {
            $query = "SELECT * FROM plans WHERE id = $planId";
            $stmt = $this->conn->prepare($query);

            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $features = $this->planFeatureById($row['id']);
                        $row['features'] = json_decode($features);
                        $res = $row;
                    }
                    return json_encode(['status' => 1, 'msg' => 'success', 'data' => $res]);
                }
                $stmt->close();
            }
            return json_encode(['status' => 0, 'msg' => 'No subscriptions found for the given Plan ID']);
        } catch (Exception $e) {
            // Handle any exceptions that may occur during the database operation
            error_log("Error getting subscription: " . $e->getMessage());
            return json_encode(['status' => 0, 'msg' => "Error: " . $e->getMessage()]);
        }
    }



    function getPlan($planId)
    {
        try {
            $query = "SELECT * FROM plans WHERE id = $planId AND status = 1";
            $stmt = $this->conn->prepare($query);

            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $features = $this->planFeatureById($row['id']);
                        $row['features'] = json_decode($features);
                        $res = $row;
                    }
                    return json_encode(['status' => 1, 'msg' => 'success', 'data' => $res]);
                }
                $stmt->close();
            }
            return json_encode(['status' => 0, 'msg' => 'No subscriptions found for the given Plan ID']);
        } catch (Exception $e) {
            // Handle any exceptions that may occur during the database operation
            error_log("Error getting subscription: " . $e->getMessage());
            return json_encode(['status' => 0, 'msg' => "Error: " . $e->getMessage()]);
        }
    }

    function updatePlan($planId, $name, $price, $duration, $status, $permissions)
    {
        try {
            // Prepare the SQL query with placeholders for the parameters
            $query = "UPDATE plans SET name = ?, price = ?, duration = ?, status = ?, permission_id = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);

            if ($stmt) {
                // Bind the parameters to the placeholders
                $stmt->bind_param("sdsisi", $name, $price, $duration, $status, $permissions, $planId);

                // Execute the statement
                if ($stmt->execute()) {
                    // Check if any rows were affected (i.e., if the update was successful)
                    if ($stmt->affected_rows > 0) {
                        $stmt->close();
                        return json_encode(['status' => 1, 'msg' => 'Plan updated successfully']);
                    } else {
                        $stmt->close();
                        return json_encode(['status' => 1, 'msg' => 'No plan found with the given ID or no changes made']);
                    }
                } else {
                    // If execution fails, close the statement and return an error
                    $stmt->close();
                    return json_encode(['status' => 0, 'msg' => 'Failed to execute update']);
                }
            } else {
                // If preparation fails, return an error
                return json_encode(['status' => 0, 'msg' => 'Failed to prepare update query']);
            }
        } catch (Exception $e) {
            // Handle any exceptions that may occur during the database operation
            error_log("Error updating plan: " . $e->getMessage());
            return json_encode(['status' => 0, 'msg' => "Error: " . $e->getMessage()]);
        }
    }

    function getAllPlans($planId){
        try {
            // Prepare the SQL query with placeholders for the parameters
            $query = "SELECT * FROM plans WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                // Bind the parameters to the placeholders
                $stmt->bind_param("i", $planId);
                // Execute the statement
                if ($stmt->execute()) {
                    // Fetch the result set as an associative array
                    $result = $stmt->get_result();
                    $plans = array();
                    while ($res = $result->fetch_object()) {
                        $plans[] =$res;
                    }
                    $stmt->close();
                    return json_encode(['status' => 1, 'plans' => $plans]);
                    } else {
                        $stmt->close();
                        return json_encode(['status' => 1, 'msg' => 'No plans found with
                        the given ID']);
                        }
                        } else {
                            return json_encode(['status' => 0, 'msg' => 'Failed to prepare
                            query']);
                            }
                            } catch (Exception $e) {
                                // Handle any exceptions that may occur during the database operation
                                error_log("Error getting plans: " . $e->getMessage());
                                return json_encode(['status' => 0, 'msg' => "Error: " .
                                $e->getMessage()]);
                                }
    }


    /************************************************************************************************************
     *                                                                                                          *
     *                                      Plans Features Management                                           *
     *                                                                                                          *
     ************************************************************************************************************/

    private function insertPlanFeatures(int $planId, array $features)
    {
        $sql = "INSERT INTO plan_features (plan_id, features, status) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $success = true;
        $status  = 1;

        foreach ($features as $feature) {
            $stmt->bind_param('sss', $planId, $feature, $status);
            if (!$stmt->execute()) {
                $success = false;
                break;
            }
        }

        return $success;
    }



    function planFeatures()
    {
        try {
            $query = "SELECT * FROM plan_features";
            $stmt = $this->conn->prepare($query);

            if ($stmt) {
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

    function planFeatureById($planId)
    {
        try {
            $query = "SELECT id, features as feature, status FROM plan_features WHERE plan_id = $planId";
            $stmt = $this->conn->prepare($query);

            if ($stmt) {
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

    function addPlanFeature($planId, $feature, $status)
    {
        try {
            // Prepare the SQL query with placeholders for the parameters
            $query = "INSERT INTO plan_features (plan_id, features, status) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);

            if ($stmt) {
                // Bind the parameters to the placeholders
                $stmt->bind_param("isi", $planId, $feature, $status);

                // Execute the statement
                if ($stmt->execute()) {
                    // Check if any rows were affected (i.e., if the insert was successful)
                    if ($stmt->affected_rows > 0) {
                        $stmt->close();
                        return json_encode(['status' => 1, 'msg' => 'Feature added successfully']);
                    } else {
                        $stmt->close();
                        return json_encode(['status' => 0, 'msg' => 'Failed to add feature']);
                    }
                } else {
                    // If execution fails, close the statement and return an error
                    $stmt->close();
                    return json_encode(['status' => 0, 'msg' => 'Failed to execute insert']);
                }
            } else {
                // If preparation fails, return an error
                return json_encode(['status' => 0, 'msg' => 'Failed to prepare insert query']);
            }
        } catch (Exception $e) {
            // Handle any exceptions that may occur during the database operation
            error_log("Error inserting plan feature: " . $e->getMessage());
            return json_encode(['status' => 0, 'msg' => "Error: " . $e->getMessage()]);
        }
    }



    function deletePlanFeatures($planId)
    {
        try {
            $query = "DELETE FROM plan_features WHERE plan_id = ?";
            $stmt = $this->conn->prepare($query);

            if ($stmt) {
                // Bind the plan_id parameter to the placeholder
                $stmt->bind_param("i", $planId);

                // Execute the statement
                if ($stmt->execute()) {
                    // Check if any rows were affected (i.e., if the delete was successful)
                    if ($stmt->affected_rows > 0) {
                        $stmt->close();
                        return json_encode(['status' => 1, 'msg' => 'Plan features deleted successfully']);
                    } else {
                        $stmt->close();
                        return json_encode(['status' => 1, 'msg' => 'No plan features found for the given plan ID']);
                    }
                } else {
                    // If execution fails, close the statement and return an error
                    $stmt->close();
                    return json_encode(['status' => 0, 'msg' => 'Failed to execute delete']);
                }
            } else {
                // If preparation fails, return an error
                return json_encode(['status' => 0, 'msg' => 'Failed to prepare delete query']);
            }
        } catch (Exception $e) {
            // Handle any exceptions that may occur during the database operation
            error_log("Error deleting plan features: " . $e->getMessage());
            return json_encode(['status' => 0, 'msg' => "Error: " . $e->getMessage()]);
        }
    }
}

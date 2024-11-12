<?php

namespace Models;

require_once dirname(__DIR__, 2) . '/classes/dbconnection.php';

use DatabaseConnection\DatabaseConnection;
use Exception;

class PlanModel
{


    private $conn;

    public function __construct()
    {
        $db = new DatabaseConnection();
        $this->conn = $db->conn;
    }

    public function getAllPlans()
    {
        $query = "SELECT * FROM plans";
        $result = $this->conn->query($query);

        $plans = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // $plans[] = $row;
                $planId = $row['id'];
                $row['features'] = $this->getPlanFeature($planId);
                $plans[] = $row;
            }
        }
        return $plans;
    }

    public function getPlanFeature($planId)
    {
        try {
            // Prepare the SQL statement
            if ($stmt = $this->conn->prepare("SELECT id, features as feature, status FROM plan_features WHERE plan_id = ?")) {
                $stmt->bind_param("i", $planId);
                $stmt->execute();
                $result = $stmt->get_result();
                $plan_features = $result->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                return $plan_features;
            } else {
                throw new Exception("Failed to prepare statement: " . $this->conn->error);
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}

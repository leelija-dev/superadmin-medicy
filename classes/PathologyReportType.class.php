<?php
/**
 * Author: Dipak Majumdar
 * @since: 23-10-2024
 * 
 */
class PathologyReportType{
    use DatabaseConnection;
    
    // Table Name 
    private $table =  'report_type';


/**
 * ####### Used In
 * 1. /admin/edit-labTestData.php
 */
    function pathalogyReportTypes() {
        try {
            // Prepare the SQL statement to avoid SQL injection
            $stmt = $this->conn->prepare("SELECT * FROM $this->table");
            
            // Execute the statement
            $stmt->execute();
            
            // Fetch results
            $result = $stmt->get_result();
            $res = $result->fetch_all(MYSQLI_ASSOC);
            
            return json_encode([ 'status' => true, 'data' => $res ]);
        } catch (Exception $e) {

            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    } // end pathalogyReportTypes function





    function selectSalesReturn($table, $data)
    {
        try {
            $res = array();

            $sql = "SELECT * FROM sales_return WHERE $table = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $data);

            if ($stmt) {

                $stmt->execute();

                $result = $stmt->get_result();

                if ($result) {
                    while ($row = $result->fetch_array()) {
                        $res[] = $row;
                    }
                } else {
                    echo "Query failed: " . $this->conn->error;
                }

                $stmt->close();
            } else {
                echo "Statement preparation failed: " . $this->conn->error;
            }

            return $res;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


}

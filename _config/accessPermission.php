<?php
// require_once CLASS_DIR . 'dbconnect.php';
// require_once CLASS_DIR . 'employee.class.php';
// require_once CLASS_DIR . 'accessPermission.class.php';
// require_once CLASS_DIR . 'utility.class.php';

// $AccessPermission  = new AccessPermission;
// $Employees         = new Employees;
// $Utility           = new Utility;

// $currentURL        = $Utility->currentUrl();
// $currentFile = basename($currentURL);


// $allowedPages = $AccessPermission->showPermission($userRole, $adminId);
// $allowedPages = json_decode($allowedPages);

//   if ($allowedPages->status == 1) {
//     $allowedPages = $allowedPages->data;

//     if(!in_array($currentFile, $allowedPages)) {
//       echo "You are not allowed";
//     }
//     }

// if($userRole != 'ADMIN'){

//     $flag = 0; 

//     $currentURL = $_SERVER['REQUEST_URI'];

//     for($i = 0; $i<count($permissonPages); $i++){
//         if($currentURL == LOCAL_DIR.$permissonPages[$i] || $currentURL == LOCAL_DIR){
//             $flag = 1;
//             break;
//         }
//     }


/*
    if ($flag == 0) {
        echo '<link rel="stylesheet" href="' . CSS_PATH . 'sweetalert2/sweetalert2.min.css">';
        echo '<script src="' . JS_PATH . 'sweetalert2/sweetalert2.all.min.js"></script>';
        
        echo '<script>
                document.addEventListener("DOMContentLoaded", function () {
                    var toastMixin = Swal.mixin({
                        toast: true,
                        icon: "success",
                        title: "General Title",
                        animation: false,
                        position: "top-right",
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener("mouseenter", Swal.stopTimer)
                            toast.addEventListener("mouseleave", Swal.resumeTimer)
                        }
                    });
    
                    toastMixin.fire({
                        title: "You have no accessable permission for this page.",
                        icon: "warning",
                        animation: true
                    }).then(() => {
                        window.location.href = "'.$_SERVER['HTTP_REFERER'].'"; 
                    });
                });
              </script>';
    }
}
*/
?>
<?php


if (isset($_GET['redirected']) && $_GET['redirected'] == 'true') {
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
                        toast.addEventListener("mouseenter", Swal.stopTimer);
                        toast.addEventListener("mouseleave", Swal.resumeTimer);
                    }
                });

                toastMixin.fire({
                    title: "You have been redirected.",
                    icon: "warning",
                    animation: true
                });
            });
          </script>';
}

?>
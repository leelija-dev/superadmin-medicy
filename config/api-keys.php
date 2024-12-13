<?php

/**
 * Author: Dipak Majumdar
 * Date:  2014-11-20
 * Description:  This is a simple PHP script to define constamts of api.
 */

/**
 * 1. test for development
 * 2. live for production
 */
// const  APIMODE = 'test';
if (is_localhost()) {
    define('APIMODE',   'test');
}else {
    define('APIMODE',   'live');
}


if (APIMODE ==  'test') {
    define('API_URL', 'http://localhost/superadmin-medicy/api/');
    define('API_IMAGE_URL', 'http://localhost/superadmin-medicy/assets/images/');
    define( 'PROF_IMG_PATH', 'http://localhost/superadmin-medicy/');
} else {
    define('API_URL', 'https://superadmin.medicy.in/api/');
    define('API_IMAGE_URL', 'https://superadmin.medicy.in/assets/images/');
    define( 'PROF_IMG_PATH', 'https://superadmin.medicy.in/');
}

// const ADM_IMG_PATH                        = API_IMAGE_URL . 'admin-images/';
// const ADM_IMG_PATH                        = API_IMAGE_URL . 'admin-images/';


// define('PROD_IMG_DIR',         API_IMAGE_URL . 'product-image/');
// define('ADM_IMG_DIR',         API_IMAGE_URL . 'admin-images/');
// define('EMP_IMG_DIR',         API_IMAGE_URL . 'employee-images/');

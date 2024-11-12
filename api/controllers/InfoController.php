<?php

namespace Api\Controllers;

require_once dirname(__DIR__, 1) . '/models/InfoModel.php';

use Models\Info;

class ApiInfoController {
    public function getAllInfos() {
        $infoModel = new Info();
        $infos = $infoModel->getAllInfos();
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($infos);
    }

    public function getInfoByName($infoname) {
        $infoModel = new Info();
        $info = $infoModel->getInfoByName($infoname);
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($info);
    }

    public function createInfo($data) {
        $infoModel = new Info();
        $result = $infoModel->createInfo($data);
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode(['success' => $result]);
    }

    public function updateInfo($id, $data) {
        $infoModel = new Info();
        $result = $infoModel->updateInfo($id, $data);
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode(['success' => $result]);
    }

    public function deleteInfo($id) {
        $infoModel = new Info();
        $result = $infoModel->deleteInfo($id);
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode(['success' => $result]);
    }
}

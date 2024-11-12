<?php

namespace Api\Controllers;

require_once dirname(__DIR__, 1) . '/models/PlanModel.php';

use Models\PlanModel;

class PlansController
{
    private $planModel;

    public function __construct()
    {
        $this->planModel = new PlanModel();
    }

    public function getAllPlans()
    {
        return $this->planModel->getAllPlans();
    }
}

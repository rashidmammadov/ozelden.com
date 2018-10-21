<?php

namespace App\Http\Controllers;

use App\SuitabilitySchedule;

class SuitabilityScheduleController extends ApiController {

    public function __construct() {
        // TODO:
    }

    /**
     * @description: create default user suitability schedule
     * @param integer $userId
     * @return void : created successfully.
     */
    public function create($userId) {
        if ($userId) {
            SuitabilitySchedule::create([
                'userId' => $userId
            ]);
        }
    }
}

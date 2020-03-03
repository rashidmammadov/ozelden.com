<?php

namespace App\Console\Commands;

use App\Http\Models\AverageModel;
use App\Http\Models\TutorLectureModel;
use App\Http\Queries\MySQL\AverageQuery;
use App\Http\Queries\MySQL\TutorLectureQuery;
use App\Http\Queries\MySQL\UserQuery;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AverageUpdater extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'average:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update average (ranking, experience, price) information of tutors';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        Log::info('------------------------------');
        Log::info('CronJob: Tutor average calculator starts');
        $tutorsFromDB = UserQuery::getTutors();
        if ($tutorsFromDB) {
            foreach ($tutorsFromDB as $tutorFromDB) {
                $average = new AverageModel();
                $tutorId = $tutorFromDB[IDENTIFIER];

                list($experienceAvg, $priceAvg) = $this->getTutorExperienceAndPriceAverages($tutorId);
                $average->setExperienceAvg($experienceAvg);
                $average->setPriceAvg($priceAvg);

                $result = AverageQuery::update($tutorId, $average->get());
                if ($result) {
                    Log::info('CronJob: Tutor with id ' . $tutorId . ' average updated to ' . json_encode($average->get()));
                } else {
                    Log::error('CronJob: Tutor with id ' . $tutorId . ' average can not updated');
                }
            }
        } else {
            Log::error('CronJob: ' . SOMETHING_WRONG_WITH_DB);
        }
    }

    /**
     * @param $tutorId
     * @return array
     */
    private function getTutorExperienceAndPriceAverages($tutorId): array {
        $experienceAvg = 0;
        $priceAvg = 0;
        $lecturesFromDB = TutorLectureQuery::get($tutorId);
        if ($lecturesFromDB) {
            $totalExperience = 0;
            $totalPrice = 0;
            $totalLectures = 0;
            foreach ($lecturesFromDB as $lectureFromDB) {
                $lecture = new TutorLectureModel($lectureFromDB);
                $totalExperience = $lecture->getExperience();
                $totalPrice += $lecture->getPrice();
                $totalLectures++;
            }
            if ($totalLectures > 0) {
                $experienceAvg = number_format(($totalExperience / $totalLectures), 0, '.', '');
                $priceAvg = number_format(($totalPrice / $totalLectures), 2, '.', '');
            }
        }
        return array($experienceAvg, $priceAvg);
    }


}

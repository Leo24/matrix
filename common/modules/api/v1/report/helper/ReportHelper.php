<?php
namespace common\modules\api\v1\report\helper;

/**
 * Class ReportHelper
 * @package common\modules\api\v1\report\helper
 */
class ReportHelper
{
    /**
     * Method of getting max value from array of data
     *
     * @param $data
     * @param $index
     * @return int
     */
    public static function getMaxValue($data, $index)
    {
        $max = 0;
        foreach ($data as $value) {
            if ($value[$index] > $max) {
                $max = $value[$index];
            }
        }
        return $max;
    }

    /**
     * Get message for Breathing daily report
     *
     * @param $average
     * @return string
     */
    public function getBreathingMessage($average)
    {
        if ($average >= 12 && $average <= 15) {
            return ['confirmation text' => 'Very Healthy',
                    'category'          => 'VERY GOOD'
            ];
        } elseif ($average >= 16 && $average <= 20) {
            return [ 'confirmation text' => 'Healthy',
                     'category'          => 'GOOD'
            ];
        } elseif ($average >= 21 && $average <= 25) {
            return [ 'confirmation text' => 'Relatively High',
                'category'          => 'OKAY'
            ];
        } elseif ($average > 25) {
            return [ 'confirmation text' => 'Abnormally High',
                     'category'          => 'NOT GOOD'
            ];
        } elseif ($average < 12) {
            return [ 'confirmation text' => 'Abnormally Low',
                     'category'          => 'BAD'
            ];
        }
    }

    /**
     * Get message for Heart Health daily report
     *
     * @param $recovery
     * @return string
     */
    public function getHeartHealthMessage($recovery)
    {
        if ($recovery >= 21) {
            return ['confirmation text' => 'Great Increase',
                    'category'          => 'VERY GOOD'
            ];
        } elseif ($recovery >= 6 && $recovery <= 20) {
            return [ 'confirmation text' => 'Slight Increase',
                     'category'          => 'GOOD'
            ];
        } elseif ($recovery >= -5 && $recovery <= 5) {
            return [ 'confirmation text' => 'No Significant Change',
                'category'          => 'OKAY'
            ];
        } elseif ($recovery <= -6 && $recovery >= -20) {
            return [ 'confirmation text' => 'Slight Decrease',
                     'category'          => 'NOT GOOD'
            ];
        } elseif ($recovery < -21) {
            return [ 'confirmation text' => 'Large Decrease',
                     'category'          => 'BAD'
            ];
        }
    }

    /**
     * Get message for Heart Rate daily report
     *
     * @param $average
     * @return string
     */
    public function getHeartRateMessage($average)
    {
        if ($average < 60) {
            return ['confirmation text' => 'Very Healthy Heart Rate',
                    'category'          => 'VERY GOOD'
            ];
        } elseif ($average >= 60 && $average <= 79) {
            return [ 'confirmation text' => 'Healthy Heart Rate',
                     'category'          => 'GOOD'
            ];
        } elseif ($average >= 80 && $average <= 99) {
            return [ 'confirmation text' => 'Average Heart Rate',
                     'category'          => 'OKAY'
            ];
        } elseif ($average <= 100 && $average >= 119) {
            return [ 'confirmation text' => 'Heart Rate High',
                     'category'          => 'NOT GOOD'
            ];
        } elseif ($average >= 120) {
            return [ 'confirmation text' => 'Heart Rate Too High',
                     'category'          => 'BAD'
            ];
        }
    }

    /**
     * Get message for Sleep Cycles daily report
     *
     * @param $durationInRem
     * @return string
     */
    public function getSleepCyclesMessage($durationInRem)
    {
        if ($durationInRem > 6480) {
            return ['confirmation text' => 'Excellent Night',
                    'category'          => 'VERY GOOD'
            ];
        } elseif ($durationInRem >= 5820 && $durationInRem < 6480) {
            return [ 'confirmation text' => 'Great Night',
                     'category'          => 'GOOD'
            ];
        } elseif ($durationInRem >= 5100 && $durationInRem < 5760) {
            return [ 'confirmation text' => 'Good Night',
                     'category'          => 'OKAY'
            ];
        } elseif ($durationInRem <= 4260 && $durationInRem < 5040) {
            return [ 'confirmation text' => 'Not Great',
                     'category'          => 'NOT GOOD'
            ];
        } elseif ($durationInRem < 4260) {
            return [ 'confirmation text' => 'Poor Night',
                     'category'          => 'BAD'
            ];
        }
    }

    /**
     * Get message for Stress daily report
     *
     * @param $lf
     * @param $hf
     * @return array
     */
    public function getStressMessage($lf, $hf)
    {
        if ($lf >= 45 && $lf <= 55 && $hf >= 45 && $hf <= 55) {
            return [
                'confirmation text' => 'Extremely Low',
                'category'          => 'VERY GOOD'
            ];
        } elseif ((($lf >= 35 && $lf <= 45) && ($hf >= 55 && $hf <= 65)) || (($lf >= 55 && $lf <= 65) && ($hf >= 35 && $hf <= 45))) {
            return [
                'confirmation text' => 'Relatively Low',
                'category'          => 'GOOD'
            ];
        } elseif ((($lf >= 25 && $lf <= 35) && ($hf >= 65 && $hf <= 75)) || (($lf >= 65 && $lf <= 75) && ($hf >= 25 && $hf <= 35))) {
            return [
                'confirmation text' => 'Average',
                'category'          => 'OKAY'
            ];
        } elseif ((($lf >= 75 && $lf <= 85) && ($hf >= 15 && $hf <= 25)) || (($lf >= 15 && $lf <= 25) && ($hf >= 75 && $hf <= 85))) {
            return [
                'confirmation text' => 'Relatively High',
                'category'          => 'NOT GOOD'
            ];
        } elseif (($lf <= 15 && $hf >= 85) || ($lf >= 85 && $hf <= 15)) {
            return [
                'confirmation text' => 'Unusually High',
                'category'          => 'BAD'
            ];
        }
    }
}

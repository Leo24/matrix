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
            return 'Very Healthy';
        } elseif ($average >= 16 && $average <= 20) {
            return 'Healthy';
        } elseif ($average >= 21 && $average <= 25) {
            return 'Relatively High';
        } elseif ($average > 25) {
            return 'Abnormally High';
        } elseif ($average < 12) {
            return 'Abnormally Low';
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
            return 'Great Increase';
        } elseif ($recovery >= 6 && $recovery <= 20) {
            return 'Slight Increase';
        } elseif ($recovery >= -5 && $recovery <= 5) {
            return 'No Significant Change';
        } elseif ($recovery <= -6 && $recovery >= -20) {
            return 'Slight Decrease';
        } elseif ($recovery < -21) {
            return 'Large Decrease';
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
            return 'Very Healthy Heart Rate';
        } elseif ($average >= 60 && $average <= 79) {
            return 'Healthy Heart Rate';
        } elseif ($average >= 80 && $average <= 99) {
            return 'Average Heart Rate';
        } elseif ($average <= 100 && $average >= 119) {
            return 'Heart Rate High';
        } elseif ($average >= 120) {
            return 'Heart Rate Too High';
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
            return 'Excellent Night';
        } elseif ($durationInRem >= 5820 && $durationInRem < 6480) {
            return 'Great Night';
        } elseif ($durationInRem >= 5100 && $durationInRem < 5760) {
            return 'Good Night';
        } elseif ($durationInRem <= 4260 && $durationInRem < 5040) {
            return 'Not Great';
        } elseif ($durationInRem < 4260) {
            return 'Poor Night';
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
                'confirmation text' => 'Extremely Low :D',
                'category'          => 'VERY GOOD'
            ];
        } elseif ((($lf >= 35 && $lf <= 45) && ($hf >= 55 && $hf <= 65)) || (($lf >= 55 && $lf <= 65) && ($hf >= 35 && $hf <= 45))) {
            return [
                'confirmation text' => 'Relatively Low :)',
                'category'          => 'GOOD'
            ];
        } elseif ((($lf >= 25 && $lf <= 35) && ($hf >= 65 && $hf <= 75)) || (($lf >= 65 && $lf <= 75) && ($hf >= 25 && $hf <= 35))) {
            return [
                'confirmation text' => 'Average :|',
                'category'          => 'OKAY'
            ];
        } elseif ((($lf >= 75 && $lf <= 85) && ($hf >= 15 && $hf <= 25)) || (($lf >= 15 && $lf <= 25) && ($hf >= 75 && $hf <= 85))) {
            return [
                'confirmation text' => 'Relatively High :/',
                'category'          => 'NOT GOOD'
            ];
        } elseif (($lf <= 15 && $hf >= 85) || ($lf >= 85 && $hf <= 15)) {
            return [
                'confirmation text' => 'Unusually High :(',
                'category'          => 'BAD'
            ];
        }
    }
}
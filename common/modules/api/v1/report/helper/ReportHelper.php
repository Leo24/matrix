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
     * Get message for Heart Rate daily report
     *
     * @param $average
     * @return string
     */
    public function getStressMessage($LF, $HF)
    {

        $LFpercent = $LF / ( $LF + $HF ) * 100;
        $HFpercent = $HF / ( $LF + $HF ) * 100;

        if ($LFpercent>=45 && $LFpercent<=55 && $HFpercent>=45 && $HFpercent<=55) {
            return [
                'confirmation text' => 'Extremely Low :D',
                'category' => 'VERY GOOD'
            ];
        } elseif (($LFpercent>=35 && $LFpercent<=45 && $HFpercent>=55 && $HFpercent<=65)
            ||
            ($LFpercent>=55 && $LFpercent<=65 && $HFpercent>=35 && $HFpercent<=45)
        ) {
            return [
                    'confirmation text' => 'Relatively Low :)',
                    'category' => 'GOOD'
                ];
        } elseif (($LFpercent>=25 && $LFpercent<=35 && $HFpercent>=65 && $HFpercent<=75)
            ||
            ($LFpercent>=65 && $LFpercent<=75 && $HFpercent>=25 && $HFpercent<=35)) {
            return [
                    'confirmation text' => 'Average :|',
                    'category' => 'OKAY'
                ];
        } elseif (($LFpercent>=75 && $LFpercent<=85 && $HFpercent>=15 && $HFpercent<=25)
            ||
            ($LFpercent>=15 && $LFpercent<=25 && $HFpercent>=75 && $HFpercent<=85)
        ) {
            return [
                    'confirmation text' => 'Relatively High :/',
                    'category' => 'NOT GOOD'
                ];
        } elseif (($LFpercent<=15 && $HFpercent>=85)
            ||
            ($LFpercent>=85 && $HFpercent<=15)) {
            return [
                    'confirmation text' => 'Unusually High :(',
                    'category' => 'BAD'
                ];
        }




        /*
         * VERY GOOD 45 < %LF < 55 / 45 <
%HF < 55 Extremely Low :D
GOOD 35 < %LF < 45 / 55 <
%HF < 65
55 < %LF < 65 / 35 <
%HF < 45 Relatively Low :)
OKAY 25 < %LF < 35 / 65 <
%HF < 75
65 < %LF < 75 / 25 <
%HF < 35 Average :|
NOT GOOD 75 < %LF < 85 / 15 <
%HF < 25
15 < %LF < 25 / 75 <
%HF < 85 Relatively High :/
BAD %LF < 15 / %HF > 85
%LF > 85 / %HF < 15 Unusually High :(
         * */



    }
    
    
    
}
<?php
/**
 * DepartureTimeFrame.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\ScoringRules;

use FraudDetector\ScoringRules\ScoringRule as ScoringRule;

/**
 * Rule to get scoring for an order checking order and departure time frame
 *
 * @package FraudDetector\Rules
 */
class DepartureTimeFrame extends ScoringRule
{
    /**
     * @var int Number of seconds
     */
    protected $timeFrame;

    /**
     * Returns the scoring for the order according the time frame between time
     * ordered and departure date.
     *
     * If time difference is less than configured accepted time frame,
     * scoring number is returned.
     *
     * @param array $order
     *
     * @return int
     */
    public function getScoring($order)
    {

    }

    /**
     * Set accepted time frame for rule
     *
     * @param int $seconds
     *
     * @return void
     */
    public function setAcceptedTimeFrame($seconds)
    {
        $this->timeFrame = $seconds;
    }

    /**
     * Get accepted time frame for rule
     *
     * @return int
     */
    public function getAcceptedTimeFrame()
    {
        return $this->timeFrame;
    }
}

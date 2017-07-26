<?php
/**
 * PaxLastName.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\ScoringRules;

use FraudDetector\ScoringRules\ScoringRule as ScoringRule;

/**
 * Rule to get scoring for an order checking last name of all passengers.
 *
 * An order is considered risky if none of the passengers last names matches.
 *
 * @package FraudDetector\Rules
 */
class PaxLastName extends ScoringRule
{
    /**
     * Returns the scoring for the order according to the check of all
     * passengers last names.
     *
     * An order is considered risky if none of the passengers last names matches.
     *
     * @param array $order
     *
     * @return int
     */
    public function getScoring($order)
    {

    }
}

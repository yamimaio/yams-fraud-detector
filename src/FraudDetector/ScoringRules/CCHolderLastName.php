<?php
/**
 * CCHolderLastName.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\ScoringRules;

use FraudDetector\ScoringRules\ScoringRule as ScoringRule;

/**
 * Rule to get scoring for an order checking the credit card holder's lastname
 * against passengers.
 *
 * An order is considered risky if holder last name does not match any of the
 * passengers last names.
 *
 * @package FraudDetector\Rules
 */
class CCHolderLastName extends ScoringRule
{
    /**
     * Returns the scoring for the order checking the credit card holder's
     * lastname against passengers.
     *
     * An order is considered risky if holder last name does not match any of
     * the passengers last names.
     *
     * @param array $order
     *
     * @return int
     */
    public function getScoring($order)
    {

    }
}

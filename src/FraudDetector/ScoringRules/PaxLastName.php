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
        //can't evaluate is only one passenger
        if ($this->isOnlyPassenger($order)) {
            return 0;
        }

        $lastNames = $this->getPassengerLastNameList($order);

        //get number of times each lastname occurs
        $lastNameCount = array_count_values($lastNames);

        //check if any lastName is repeated
        $matches = array_filter($lastNameCount, function ($count) {
            return $count > 1;
        });

        //repeated lastNames means no risk
        return $matches ? 0 : $this->scoring;
    }

    /**
     * Indicates it order is for only one passenger.
     *
     * @param array $order Order to check
     *
     * @return bool
     */
    protected function isOnlyPassenger($order)
    {
        return $order['travel_ticket']['passengers'] == 1;
    }

    /**
     * Returns list of passengers lastnames.
     *
     * @param array $order Order to check
     *
     * @return array
     */
    protected function getPassengerLastNameList($order)
    {
        $lastNames = array_column($order['travel_passengers'], 'last_name');
        //normalize last names
        return array_map(function ($lastName) {
            return strtolower(trim($lastName));
        }, $lastNames);
    }
}

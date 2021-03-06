<?php
/**
 * CCHolderLastName.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\ScoringRules;

use FraudDetector\ScoringRules\ScoringRule;

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
    public function getScoring($order): int
    {
        //holder lastName comes together with first name, so we'll iterate
        //over passengers lastnames and find in holder string
        $holder = $order['payment']["cc_holder"];
        $lastNames = $this->getPassengerLastNameList($order);

        //return all $lastnames that are included in holder name
        $matches = array_filter($lastNames, static function ($lastName) use ($holder) {
            return stripos($holder, $lastName) !== false;
        });

        //if any match is found, no risk
        return count($matches) > 0 ? 0 : $this->scoring;
    }

    /**
     * Returns list of passengers lastnames.
     *
     * @param array $order Order to check
     *
     * @return array
     */
    protected function getPassengerLastNameList($order): array
    {
        $lastNames = array_column($order['travel_passengers'], 'last_name');

        //normalize last names
        return array_map(static function ($lastName) {
            return strtolower(trim($lastName));
        }, $lastNames);
    }
}

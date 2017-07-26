<?php
/**
 * BlacklistedCard.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\ScoringRules;

use FraudDetector\ScoringRules\ScoringRule as ScoringRule;

/**
 * Rule to get scoring for an order checking against a blacklist of credit cards
 *
 * @package FraudDetector\Rules
 */
class BlacklistedCard extends ScoringRule
{
    /**
     * Returns the scoring for the order according to the credit card.
     *
     * If the credit card used for payment matches a credit card blacklist.
     *
     * @param array $order
     *
     * @return int
     */
    public function getScoring($order)
    {

    }

    /**
     * Returns the blacklisted credit cards
     *
     * @todo Change method to load blacklist from a service
     *
     * return array
     */
    protected function getBlacklist()
    {
        return ['5665777755559999'];
    }
}

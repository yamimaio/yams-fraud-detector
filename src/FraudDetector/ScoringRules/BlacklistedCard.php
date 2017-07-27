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
     * If the credit card used for payment matches a credit card blacklist,
     * scoring number is returned.
     *
     * @param array $order
     *
     * @return int
     */
    public function getScoring($order)
    {
        $creditCard = $order['payment']['cc_number'];

        return $this->isBlacklisted($creditCard) ? $this->scoring : 0;
    }

    /**
     * Indicates if credit card has been blacklisted
     *
     * @param int $creditCard
     *
     * @return bool
     */
    protected function isBlacklisted($creditCard)
    {
        $blacklisted = $this->getBlacklist();
        return in_array($creditCard, $blacklisted);
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

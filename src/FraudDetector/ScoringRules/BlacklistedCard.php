<?php
/**
 * BlacklistedCard.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\ScoringRules;

use FraudDetector\Interfaces\ScoringRuleInterface;

/**
 * Rule to get scoring for an order checking against a blacklist of credit cards
 *
 * @package FraudDetector\Rules
 */
class BlacklistedCard implements ScoringRuleInterface
{
    /**
     * @var int scoring returned if fraud suspected
     */
    protected $scoring;

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
     * Returns the scoring the rule adds if fraud suspected
     *
     * @return int
     */
    public function getRuleScoring()
    {
        return $this->scoring;
    }

    /**
     * Sets the scoring the rule adds if fraud suspected
     *
     * @param int $scoring
     *
     * @return void
     */
    public function setRuleScoring($scoring)
    {
        $this->scoring = $scoring;
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

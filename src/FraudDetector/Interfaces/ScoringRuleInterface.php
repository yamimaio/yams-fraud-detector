<?php
/**
 * ScoringRuleInterface.phpace.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\Interfaces;

/**
 * Interface ScoringRuleInterface for scoring rules
 *
 * @package FraudDetector\Interfaces
 */
interface ScoringRuleInterface
{

    /**
     * Returns the scoring for the order according to the implemented rule
     *
     * @param array $order
     *
     * @return int
     */
    public function getScoring($order): int;

    /**
     * Returns the scoring the rule adds if fraud suspected
     *
     * @return int
     */
    public function getRuleScoring(): int;

    /**
     * Sets the scoring the rule adds if fraud suspected
     *
     * @param int $scoring
     *
     * @return void
     */
    public function setRuleScoring($scoring): void;
}

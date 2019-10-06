<?php
/**
 * ScoringRule.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\ScoringRules;

use FraudDetector\Interfaces\ScoringRuleInterface;

/**
 * Class ScoringRule
 *
 * @package FraudDetector\ScoringRules
 */
abstract class ScoringRule implements ScoringRuleInterface
{
    /**
     * @var int scoring returned if fraud suspected
     */
    protected $scoring;

    /**
     * ScoringRule constructor.
     *
     * @param int $scoring
     */
    public function __construct(int $scoring)
    {
        $this->scoring = $scoring;
    }

    /**
     * Returns the scoring the rule adds if fraud suspected
     *
     * @return int
     */
    public function getRuleScoring(): int
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
    public function setRuleScoring($scoring): void
    {
        $this->scoring = $scoring;
    }
}

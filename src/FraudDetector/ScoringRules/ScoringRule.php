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
}

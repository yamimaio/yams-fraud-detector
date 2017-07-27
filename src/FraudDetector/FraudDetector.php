<?php
/**
 * FraudDetector.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector;

use FraudDetector\Interfaces\ScoringRuleInterface;

/**
 * Checks fraud condition for a given order.
 *
 * Can indicate if fraud has been committed and return fraud scoring.
 *
 * @package FraudDetector
 */
class FraudDetector
{
    /**
     * List of rules to check which will automatically indicate Fraud
     *
     * @var array
     */
    protected $maxScoringRules;
    /**
     * List of rules to check.
     *
     * @var array
     */
    protected $rules;
    /**
     * Scoring in which order is considered frauds
     *
     * @var int
     */
    protected $fraudScoring;
    /**
     * Maximum scoring allowed (100% fraud)
     *
     * @var int
     */
    protected $maxScoring;

    /**
     * FraudDetector constructor.
     *
     * @param array                  $config           Fraud detector
     *                                                 configuration
     * @param ScoringRuleInterface[] $rules            List of rules to check
     * @param ScoringRuleInterface[] $maxScoringRules  List of rules to check
     *                                                 that will give max
     *                                                 scoring
     */
    public function __construct(
        array $config,
        array $rules,
        array $maxScoringRules = []
    ) {
        $this->rules = $rules;
        $this->fraudScoring = $config['fraudScoring'];
        $this->maxScoring = $config['maxScoring'];
        $this->maxScoringRules = $maxScoringRules;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @param array $rules
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @return int
     */
    public function getFraudScoring(): int
    {
        return $this->fraudScoring;
    }

    /**
     * @param int $fraudScoring
     */
    public function setFraudScoring(int $fraudScoring)
    {
        $this->fraudScoring = $fraudScoring;
    }

    /**
     * @return int
     */
    public function getMaxScoring(): int
    {
        return $this->maxScoring;
    }

    /**
     * @param int $maxScoring
     */
    public function setMaxScoring(int $maxScoring)
    {
        $this->maxScoring = $maxScoring;
    }

    /**
     * @return array
     */
    public function getMaxScoringRules(): array
    {
        return $this->maxScoringRules;
    }

    /**
     * @param array $maxScoringRules
     */
    public function setMaxScoringRules(array $maxScoringRules)
    {
        $this->maxScoringRules = $maxScoringRules;
    }

    /**
     * Indicates if order can be considered fraud
     *
     * @param array $order
     *
     * @return bool
     */
    public function isFraud($order)
    {
        $scoring = $this->getScoring($order);
        return $scoring >= $this->fraudScoring;
    }

    /**
     * Returns fraud scoring for given order
     *
     * @param array $order Order to check
     *
     * @return int
     */
    public function getScoring($order)
    {
        //if any critical rule returns fraud, automatically return max scoring
        if ($this->maxedOut($order)) {
            return $this->maxScoring;
        }

        $scoring = 0;

        //for non critical rules
        foreach ($this->rules as $rule) {
            //add up each rule scoring
            $scoring += $rule->getScoring($order);

            //if scoring is greater than max, return max
            if ($this->reachedMaxScoring($scoring)) {
                return $this->maxScoring;
            }
        }

        return $scoring;
    }

    /**
     * Indicates if order has full fraud scoring for having broken a critical
     * rule
     *
     * @param array $order
     *
     * @return bool
     */
    protected function maxedOut($order)
    {
        foreach ($this->maxScoringRules as $rule) {
            if ($rule->getScoring($order)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Indicates if order has reached max scoring
     *
     * @param int $scoring
     *
     * @return bool
     */
    protected function reachedMaxScoring($scoring)
    {
        return $scoring >= $this->maxScoring;
    }
}

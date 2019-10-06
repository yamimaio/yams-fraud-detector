<?php
/**
 * fraud
 * FraudDetectorBuilder.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\Builders;

use FraudDetector\Factories\RuleFactory;
use FraudDetector\FraudDetector;

/**
 * FraudDetectorBuilder
 *
 * @package FraudDetector\Builders
 */
class FraudDetectorBuilder
{
    /**
     * @var RuleFactory
     */
    protected $factory;

    /**
     * FraudDetectorBuilder constructor.
     *
     * @param RuleFactory $ruleFactory
     */
    public function __construct(RuleFactory $ruleFactory)
    {
        $this->factory = $ruleFactory;
    }

    /**
     * @return RuleFactory
     */
    public function getFactory(): RuleFactory
    {
        return $this->factory;
    }

    /**
     * @param RuleFactory $factory
     */
    public function setFactory(RuleFactory $factory): void
    {
        $this->factory = $factory;
    }

    /**
     * Returns a FraudDetector
     *
     * @param array $config           Fraud detector configuration. Must have:
     *                                - fraudScoring: value that determines fraud
     *                                - maxScoring: maximum possible scoring
     *                                - rules: array of rule configurations. Each
     *                                rule must have a configuration array,
     *                                with the following elements:
     *                                -- name (Rule Name)
     *                                -- scoring (Scoring assigned to the rule).
     *                                Optional. Defaults to 10.
     *                                -- any extra config options needed
     *                                - maxScoringRules: array of rule
     *                                configurations for rules which will max
     *                                out score if broken. Optional. Each rule
     *                                must have a configuration array, with the
     *                                following elements:
     *                                -- name (Rule Name)
     *                                -- any extra config options needed
     *
     * @return FraudDetector
     */
    public function getFraudDetector(array $config): FraudDetector
    {
        $detectorConfig['fraudScoring'] = $config['fraudScoring'];
        $detectorConfig['maxScoring'] = $config['maxScoring'];

        $rules = $this->getRules($config['rules']);
        $maxScoringRules = $this->getRules(
            $config['maxScoringRules'],
            $config['maxScoring']
        );

        return new FraudDetector($detectorConfig, $rules, $maxScoringRules);
    }

    /**
     * Returns list of rules.
     *
     * @param array    $rulesConfig Each rule must have a configuration array,
     *                              with the following elements:
     *                              - name (Rule Name)
     *                              - scoring (Scoring assigned to the rule).
     *                              Optional. Defaults to 10.
     * @param bool|int $maxScoring  Max scoring possible value. If set, rule
     *                              will be set as maxScoring. False, otherwise.
     *                              A max scoring rule will return the maximum
     *                              amount of scoring possible and define a fraud.
     *
     * @return array;
     */
    protected function getRules(array $rulesConfig, $maxScoring = false): array
    {
        $rules = [];
        foreach ($rulesConfig as $ruleConfig) {
            if ($maxScoring !== false) {
                $ruleConfig['scoring'] = $maxScoring;
            }

            $rules[$ruleConfig['name']] = $this->factory->getRule($ruleConfig);
        }

        return $rules;
    }
}

<?php
/**
 * fraud
 * RuleFactory.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\Factories;

use FraudDetector\Interfaces\ScoringRuleInterface;

/**
 * Class RuleFactory
 *
 * @package FraudDetector\Factories
 *
 * @return ScoringRuleInterface
 */
class RuleFactory
{
    /**
     * Returns a Rule corresponding to configuration
     *
     * @param array $config           Configuration array with the following
     *                                elements:
     *                                -- name (Rule Name)
     *                                -- scoring (Scoring assigned to the rule).
     *                                Optional. Defaults to 10.
     *                                -- any extra config options needed
     *
     * @return ScoringRuleInterface
     */
    public function getRule(array $config): ScoringRuleInterface
    {
        $fqdn = $this->getFQDN($config['name']);
        $scoring = !empty($config['scoring']) ? $config['scoring'] : 10;
        unset($config['name'], $config['scoring']);

        return new $fqdn($scoring, $config);
    }

    /**
     * Returns fully qualified class name
     *
     * @param string $name Rule name
     *
     * @return string
     */
    protected function getFQDN($name)
    {
        $namespace = '\\FraudDetector\\ScoringRules\\';
        return $namespace . $name;
    }
}
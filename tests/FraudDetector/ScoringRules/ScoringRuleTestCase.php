<?php
/**
 * fraud
 * ScoringRuleTestCaseTestCase.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace tests\FraudDetector\ScoringRules;

use PHPUnit\Framework\TestCase;

/**
 * Base Class for testing ScoringRules
 *
 * @package FraudDetector\ScoringRules
 */
class ScoringRuleTestCase extends TestCase
{
    /**
     * @var BlacklistedCard
     */
    protected $rule;
    /**
     * @var array Standard order
     */
    protected $order;

    /**
     * @var int scoring expected for rule
     */
    protected $scoring;

    /**
     * Test Scoring number setter and getter
     */
    public function testSetScoringSetsGivenScoring()
    {
        $this->rule->setRuleScoring($this->scoring);

        $this->assertEquals($this->scoring, $this->rule->getRuleScoring());
    }

    /**
     * Setup Test
     */
    protected function setUp()
    {
        $order = file_get_contents(__DIR__ . '/order.json');
        $this->order = json_decode($order, true);
    }

    /**
     * Get protected method invocation
     *
     * @param string $name Method to invoke
     *
     * @return \ReflectionMethod
     */
    protected function getMethod($name)
    {
        $class = new \ReflectionClass(get_class($this->rule));
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}

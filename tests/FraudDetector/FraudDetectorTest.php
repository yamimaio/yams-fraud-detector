<?php
/**
 * fraud
 * FraudDetectorTest.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\Tests;

use FraudDetector\FraudDetector;
use FraudDetector\ScoringRules\BlacklistedCard;
use FraudDetector\ScoringRules\CCHolderLastName;
use FraudDetector\ScoringRules\DepartureTimeFrame;
use FraudDetector\ScoringRules\PaxLastName;
use FraudDetector\ScoringRules\RiskyCountry;
use PHPUnit\Framework\TestCase;

/**
 * Test for Class FraudDetectorTest
 *
 * @package FraudDetector\Tests
 */
class FraudDetectorTest extends TestCase
{
    /**
     * @var array
     */
    protected $order;

    /**
     * @var FraudDetector
     */
    protected $detector;

    /**
     * @var array
     */
    protected $rules;

    /**
     * @var array
     */
    protected $maxScoringRules;

    /**
     * @var array
     */
    protected $config;

    public function testIsFraudReturnsTrueWhenOverFraudLevelScoring()
    {
        //get blacklist
        $blacklistMethod = $this->getMethod(
            $this->maxScoringRules['BlacklistedCard'],
            'getBlacklist'
        );
        $blacklist = $blacklistMethod->invokeArgs(
            $this->maxScoringRules['BlacklistedCard'],
            []
        );
        //get any blacklist element
        $blacklistedCard = $blacklist[0];
        //modify order to have blacklisted card
        $this->order['payment']['cc_number'] = $blacklistedCard;

        $this->assertTrue(
            $this->detector->isFraud($this->order)
        );
    }

    /**
     * Get protected method invocation
     *
     * @param string $rule Rule to invoke
     * @param string $name Method to invoke
     *
     * @return \ReflectionMethod
     */
    protected function getMethod($rule, $name)
    {
        $class = new \ReflectionClass(get_class($rule));
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    public function testIsFraudReturnsFalseWhenUnderFraudLevelScoring()
    {
        $this->assertFalse(
            $this->detector->isFraud($this->order)
        );
    }

    public function testGetScoringReturnsMaxScoringWhenCriticalRuleBroken()
    {
        //get blacklist
        $blacklistMethod = $this->getMethod(
            $this->maxScoringRules['BlacklistedCard'],
            'getBlacklist'
        );
        $blacklist = $blacklistMethod->invokeArgs(
            $this->maxScoringRules['BlacklistedCard'],
            []
        );
        //get any blacklist element
        $blacklistedCard = $blacklist[0];
        //modify order to have blacklisted card
        $this->order['payment']['cc_number'] = $blacklistedCard;

        $this->assertSame(
            $this->config['maxScoring'],
            $this->detector->getScoring($this->order)
        );
    }

    public function testGetScoringReturnsMaxScoringWhenScoringOverMax()
    {
        $this->detector->setMaxScoringRules([]);

        $this->rules['PaxLastName'] = new PaxLastName(60);
        $this->rules['CCHolderLastName'] = new CCHolderLastName(70);

        $this->detector->setRules($this->rules);

        $this->order['travel_ticket']['passengers'] = 3;

        $this->order['travel_passengers'][] = [
            'first_name' => 'Yamila',
            'last_name' => 'NoMaior'
        ];

        $this->order['travel_passengers'][] = [
            'first_name' => 'Yamila',
            'last_name' => 'NoMaio'
        ];

        $this->assertSame(
            $this->config['maxScoring'],
            $this->detector->getScoring($this->order)
        );
    }

    public function testGetScoringReturnsCorrectValue()
    {
        $this->detector->setMaxScoringRules([]);

        $this->detector->setRules($this->rules);

        $this->order['travel_ticket']['passengers'] = 3;

        $this->order['transaction']['ordered_on'] = "2017-05-15 10:30:00";
        $this->order['travel_ticket']['depart_on'] = "2017-05-15 10:00:00";

        $this->order['travel_passengers'][] = [
            'first_name' => 'Yamila',
            'last_name' => 'NoMaior'
        ];

        $this->order['travel_passengers'][] = [
            'first_name' => 'Yamila',
            'last_name' => 'NoMaio'
        ];

        $this->assertSame(40, $this->detector->getScoring($this->order));
    }

    /**
     * Test setters and getters
     *
     * @dataProvider attributesProvider
     */
    public function testSettersAndGetters($property, $value)
    {
        $setter = 'set' . $property;
        $getter = 'get' . $property;

        $this->detector->$setter($value);

        $this->assertEquals($value, $this->detector->$getter());
    }

    /**
     * Attributes provider
     * @return array
     */
    public function attributesProvider()
    {
        return [
            ['maxScoringRules', []],
            ['fraudScoring', 80],
            ['rules', []],
            ['maxScoring', 100]
        ];
    }

    protected function setUp()
    {
        parent::setUp();
        $order = file_get_contents(__DIR__ . '/order.json');
        $this->order = json_decode($order, true);

        $this->config = ['fraudScoring' => 80, 'maxScoring' => 100];

        $this->maxScoringRules['BlacklistedCard'] = new BlacklistedCard(100);

        $this->rules['CCHolderLastName'] = new CCHolderLastName(10);
        $this->rules['PaxLastName'] = new PaxLastName(20);
        $this->rules['RiskyCountry'] = new RiskyCountry(20);
        $this->rules['DepartureTimeFrame'] = new DepartureTimeFrame(10, ['timeFrame' => 86400]);

        $this->detector = new FraudDetector(
            $this->config,
            $this->rules,
            $this->maxScoringRules
        );
    }
}

<?php
/**
 * RiskyCountryTest.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\Tests\ScoringRules;

use FraudDetector\ScoringRules\RiskyCountry;
use FraudDetector\Tests\ScoringRules\ScoringRuleTestCase as ScoringRuleTestCase;

/**
 * Test for Class RiskyCountryTest
 *
 * @package FraudDetector\Tests\ScoringRules
 */
class RiskyCountryTest extends ScoringRuleTestCase
{
    /**
     * Assert that when destiny country is a listed as risky the returned score
     * is the defined scoring number.
     */
    public function testGetScoringReturnsScoringNumberWhenDestinyIsRiskyCountry()
    {
        // Create a mock for the Rule class,
        // only mock the getRiskyCountries() and getNeighborCountries methods
        $riskyRule = $this->getMockBuilder(RiskyCountry::class)
            ->setConstructorArgs([20])
            ->setMethods(['getRiskyCountries', 'getNeighborCountries'])
            ->getMock();

        //modifiy class so that risky countries includes neighbor country
        // Set up the expectation for the getRiskyCountries()
        $riskyRule->expects($this->once())
            ->method('getRiskyCountries')
            ->willReturn([$this->order['travel_ticket']['to_country']]);

        // Set up the expectation for the getNeighborCountries()
        $riskyRule->expects($this->once())
            ->method('getNeighborCountries')
            ->with($this->equalTo($this->order['travel_ticket']['from_country']))
            ->willReturn([]);

        $riskyRule->setRuleScoring($this->scoring);

        $this->assertSame(
            $this->scoring,
            $riskyRule->getScoring($this->order)
        );
    }

    /**
     * Assert that when destiny country is not listed as risky and not neighbor
     * the returned score is zero.
     */
    public function testGetScoringReturnsZeroWhenDestinyIsNotRiskyNorNeighborCountry()
    {
        //modify order to have non existing country (for sure not risky or
        // neighbor!)
        $this->order['travel_ticket']['to_country'] = 'fake_country';

        $this->assertSame(
            0,
            $this->rule->getScoring($this->order)
        );
    }

    /**
     * Assert that when destiny country is a neighbor of origin country
     * the returned score is the defined scoring number.
     */
    public function testGetScoringReturnsScoringNumberWhenDestinyIsNeighborCountry()
    {
        //get risky countries
        $neighborCountriesMethod = $this->getMethod('getNeighborCountries');
        $neighborCountries = $neighborCountriesMethod->invokeArgs(
            $this->rule,
            [$this->order['travel_ticket']['from_country']]
        );
        //get any neighbor country element
        $neighborCountry = $neighborCountries[0];
        //modify order to have neighbor country
        $this->order['travel_ticket']['to_country'] = $neighborCountry;

        $this->assertSame(
            $this->scoring,
            $this->rule->getScoring($this->order)
        );
    }

    /**
     * Assert that when destiny country is a neighbor of origin country and also
     * a risky country the returned score is the double of the defined scoring
     * number.
     */
    public function testGetScoringReturnsDoubleScoringNumberWhenDestinyIsNeighborAndRisky()
    {
        // Create a mock for the Rule class,
        // only mock the getRiskyCountries() method.
        $riskyRule = $this->getMockBuilder(RiskyCountry::class)
            ->setConstructorArgs([20])
            ->setMethods(['getRiskyCountries'])
            ->getMock();

        //get neighbor countries
        $neighborCountriesMethod = $this->getMethod('getNeighborCountries');
        $neighborCountries = $neighborCountriesMethod->invokeArgs(
            $this->rule,
            [$this->order['travel_ticket']['from_country']]
        );

        //get any neighbor country element
        $neighborCountry = $neighborCountries[0];

        //modify order to have neighbor country
        $this->order['travel_ticket']['to_country'] = $neighborCountry;

        //modifiy class so that risky countries includes neighbor country
        // Set up the expectation for the getRiskyCountries()
        $riskyRule->expects($this->once())
            ->method('getRiskyCountries')
            ->willReturn([$neighborCountry]);

        $riskyRule->setRuleScoring($this->scoring);

        $this->assertSame(
            $this->scoring * 2,
            $riskyRule->getScoring($this->order)
        );
    }

    /**
     * Setup Test
     */
    protected function setUp()
    {
        parent::setUp();
        $this->scoring = 20;
        $this->rule = new RiskyCountry($this->scoring);
    }
}

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
        $this->rule->setRuleScoring($this->scoring);

        //get risky countries
        $riskyCountriesMethod = $this->getMethod('getRiskyCountries');
        $riskyCountries = $riskyCountriesMethod->invokeArgs($this->rule, []);
        //get any risky country element
        $riskyCountry = $riskyCountries[0];
        //modify order to have risky country
        $this->order['travel_ticket']['to_country'] = $riskyCountry;

        $this->assertSame(
            $this->scoring,
            $this->rule->getScoring($this->order)
        );
    }

    /**
     * Assert that when destiny country is not listed as risky and not neighbor
     * the returned score is zero.
     */
    public function testGetScoringReturnsZeroWhenDestinyIsNotRiskyNorNeighborCountry()
    {
        $this->rule->setRuleScoring($this->scoring);

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
        $this->rule->setRuleScoring($this->scoring);

        //get risky countries
        $neighborCountriesMethod = $this->getMethod('getNeighborCountries');
        $neighborCountries = $neighborCountriesMethod->invokeArgs(
            $this->rule,
            []
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
            ->with($this->equalTo([$neighborCountry]));

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
        $this->rule = new RiskyCountry();
        $this->rule->setRuleScoring($this->scoring);
    }
}

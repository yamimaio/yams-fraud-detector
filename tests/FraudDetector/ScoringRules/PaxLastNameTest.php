<?php
/**
 * PaxLastNameTest.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\Tests\ScoringRules;

use FraudDetector\ScoringRules\PaxLastName;
use FraudDetector\Tests\ScoringRules\ScoringRuleTestCase as ScoringRuleTestCase;

/**
 * Test for Class PaxLastNameTest
 *
 * @package FraudDetector\Tests\ScoringRules
 */
class PaxLastNameTest extends ScoringRuleTestCase
{
    /**
     * Assert that when non of the passengers last names match, scoring number
     * is returned.
     *
     * @todo could providers to test multiple passangers
     */
    public function testGetScoringReturnsScoringNumberWhenNoLastNameMatches()
    {
        //modify order to have 2 pax with different last names
        $this->order['travel_ticket']['passengers'] = 2;
        $this->order['travel_passengers'][] = [
            "first_name" => "John",
            "last_name" => "NotSmith"
        ];

        $this->assertSame(
            $this->scoring,
            $this->rule->getScoring($this->order)
        );
    }

    /**
     * Assert that if any of the passengers last names matches, zero
     * is returned
     *
     * This must be case insensitive
     *
     *
     * @dataProvider lastnameProvider
     */
    public function testGetScoringReturnsZeroWhenLastNamesMatch($lastname)
    {
        //modify order to have matching lastnames for passengers
        $this->order['travel_ticket']['passengers'] = 2;
        $this->order['travel_passengers'][] = [
            "first_name" => "John",
            "last_name" => $lastname
        ];

        $this->assertSame(
            0,
            $this->rule->getScoring($this->order)
        );
    }

    /**
     * Provides lastnames that should match with smith
     *
     * @return array
     */
    public function lastnameProvider()
    {
        return [
            ['SMITH'],
            ['Smith'],
            ['smith'],
            ['SmiTH'],
        ];
    }


    /**
     * Assert that when there is only one passenger, zero is returned.
     */
    public function testGetScoringReturnsZeroWhenOnlyPax()
    {
        $this->assertSame(
            0,
            $this->rule->getScoring($this->order)
        );
    }

    /**
     * Setup Test
     */
    protected function setUp()
    {
        parent::setUp();
        $this->scoring = 10;
        $this->rule = new PaxLastName($this->scoring);
    }
}

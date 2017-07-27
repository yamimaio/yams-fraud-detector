<?php
/**
 * CCHolderLastNameTest.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\Tests\ScoringRules;

use FraudDetector\ScoringRules\CCHolderLastName;
use FraudDetector\Tests\ScoringRules\ScoringRuleTestCase as ScoringRuleTestCase;

/**
 * Test for Class CCHolderLastNameTest
 *
 * @package FraudDetector\Tests\ScoringRules
 */
class CCHolderLastNameTest extends ScoringRuleTestCase
{
    /**
     * Assert that when credit card holder lastname does not match any of the
     * passengers lastname scoring number is returned.
     *
     * @todo could providers to test multiple passangers
     */
    public function testGetScoringReturnsScoringNumberWhenNoLastNameMatches()
    {
        //modify order to have 2 pax with different last names
        // (holder musn't match any of them)
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
     * Assert that if any of the passengers last names matches the holders
     * lastname zero is returned
     *
     * This must be case insensitive
     *
     * @dataProvider lastnameProvider
     */
    public function testGetScoringReturnsZeroWhenLastNamesMatch($lastname)
    {
        //modify order to have matching lastnames for passengers and holder
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
     * Provides lastnames that should match with Maio
     *
     * @return array
     */
    public function lastnameProvider()
    {
        return [
            ['MAIO'],
            ['Maio'],
            ['maio'],
            ['MaiO'],
        ];
    }

    /**
     * Setup Test
     */
    protected function setUp()
    {
        parent::setUp();
        $this->scoring = 10;
        $this->rule = new CCHolderLastName();
        $this->rule->setRuleScoring($this->scoring);
    }
}

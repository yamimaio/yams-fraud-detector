<?php
/**
 * BlacklistedCardTest.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\Tests\ScoringRules;

use FraudDetector\ScoringRules\BlacklistedCard;
use FraudDetector\Tests\ScoringRules\ScoringRuleTestCase as ScoringRuleTestCase;

/**
 * Test for Class BlacklistedCard
 *
 * @package FraudDetector\ScoringRules
 */
class BlacklistedCardTest extends ScoringRuleTestCase
{
    /**
     * Assert that when credit card in number is in blacklist, returned score
     * defined scoring number.
     */
    public function testGetScoringReturnsScoringNumberWhenCardIsBlackListed()
    {
        //get blacklist
        $blacklistMethod = $this->getMethod('getBlacklist');
        $blacklist = $blacklistMethod->invokeArgs($this->rule, []);
        //get any blacklist element
        $blacklistedCard = $blacklist[0];
        //modify order to have blacklisted card
        $this->order['payment']['cc_number'] = $blacklistedCard;

        $this->assertSame(
            $this->scoring,
            $this->rule->getScoring($this->order)
        );
    }

    /**
     * Assert that when credit card in number is not in blacklist, returned
     * score is 0.
     */
    public function testGetScoringReturnsZeroWhenCardIsNotBlackListed()
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
        $this->scoring = 100;
        $this->rule = new BlacklistedCard($this->scoring);
    }
}

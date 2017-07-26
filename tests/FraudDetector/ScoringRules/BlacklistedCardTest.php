<?php
/**
 * BlacklistedCardTest.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace tests\FraudDetector\ScoringRules;

use FraudDetector\ScoringRules\BlacklistedCard;
use tests\FraudDetector\ScoringRules\ScoringRuleTestCase as ScoringRuleTestCase;

require_once 'ScoringRuleTestCase.php';

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
        $this->rule->setRuleScoring($this->scoring);

        //get blacklist
        $blacklist_method = $this->getMethod('getBlacklist');
        $blacklist = $blacklist_method->invokeArgs($this->rule, []);
        //get any blacklist element
        $blacklisted_card = $blacklist[0];
        //modify order to have blacklisted card
        $this->order['payment']['cc_number'] = $blacklisted_card;

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
        $this->rule->setRuleScoring($this->scoring);

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
        $this->rule = new BlacklistedCard();
        $this->rule->setRuleScoring($this->scoring);
    }
}

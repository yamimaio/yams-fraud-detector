<?php
/**
 * DepartureTimeFrameTest.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\Tests\ScoringRules;

use FraudDetector\ScoringRules\DepartureTimeFrame;
use FraudDetector\Tests\ScoringRules\ScoringRuleTestCase as ScoringRuleTestCase;

/**
 * Test for Class DepartureTimeFrameTest
 *
 * @package FraudDetector\Tests\ScoringRules
 */
class DepartureTimeFrameTest extends ScoringRuleTestCase
{
    /**
     * Assert that when time frame between order and departure is less than
     * accepted time frame, scoring number is returned.
     */
    public function testGetScoringReturnsScoringNumberWhenTimeFrameLessThanAccepted(
    )
    {
        //modify order to have less than accepted time frame
        $orderDate = new \DateTime($this->order['transaction']['ordered_on']);
        $interval = \DateInterval::createFromDateString(
            $this->rule->getAcceptedTimeFrame() - 1000 . ' seconds'
        );
        $departureDate = $orderDate->add($interval);
        $this->order['travel_ticket']['depart_on']
            = $departureDate->format("Y-m-d H:i:s");

        $this->assertSame(
            $this->scoring,
            $this->rule->getScoring($this->order)
        );
    }

    /**
     * Assert that when time frame between order and departure is larger than
     * accepted time frame, scoring number is zero.
     */
    public function testGetScoringReturnsZeroWhenTimeFrameIsAccepted()
    {
        //modify order to have more than accepted time frame
        $orderDate = new \DateTime($this->order['transaction']['ordered_on']);
        $interval = \DateInterval::createFromDateString(
            $this->rule->getAcceptedTimeFrame() + 1000 . ' seconds'
        );
        $departureDate = $orderDate->add($interval);
        $this->order['travel_ticket']['depart_on']
            = $departureDate->format("Y-m-d H:i:s");

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
        $this->scoring = 20;
        $this->rule = new DepartureTimeFrame();
        $this->rule->setRuleScoring($this->scoring);
        $this->rule->setAcceptedTimeFrame(86400); //24hs
    }
}

<?php
/**
 * RiskyCountry.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\ScoringRules;

/**
 * Rule to get scoring for an order checking against a list of risky destiny
 *  countries
 *
 * A destiny country is considered risky if it's:
 *  - neighbour country of the origin country
 *  - in the risky countries list
 *
 * @package FraudDetector\Rules
 */
class RiskyCountry extends ScoringRule
{

    /**
     * Returns the scoring for the order according to a list of risky destiny
     *  countries
     *
     * A destiny country is considered risky if it's:
     *  - neighbour country of the origin country
     *  - in the risky countries list
     *
     * If a given destiny country is both risky and neighbor, scoring is
     * doubled.
     *
     * @param array $order
     *
     * @return int
     */
    public function getScoring($order)
    {

    }


    /**
     * Returns the list of destiny neighbor countries
     *
     * @todo Change method to load neighbor countries list from a service
     *
     * return array
     */
    protected function getNeighborCountries()
    {
        return ['Iran', 'Irak', 'Palestine'];
    }

    /**
     * Returns the risky countries list
     *
     * @todo Change method to load risky countries list from a service
     *
     * return array
     */
    protected function getRiskyCountries()
    {
        return ['Iran', 'Irak', 'Palestine'];
    }
}

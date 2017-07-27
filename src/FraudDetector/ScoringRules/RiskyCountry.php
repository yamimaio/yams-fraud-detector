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
        $country = $order['travel_ticket']['to_country'];
        $origin = $order['travel_ticket']['from_country'];

        $scoring = 0;
        $scoring += $this->isNeighborCountry($country, $origin) ? $this->scoring
            : 0;
        $scoring += $this->isRiskyCountry($country) ? $this->scoring : 0;

        return $scoring;
    }


    /**
     * Indicates if given country limits with country of origin.
     *
     * @param string $country
     * @param string $origin
     *
     * @return bool
     */
    protected function isNeighborCountry($country, $origin)
    {
        $neighbors = $this->getNeighborCountries($origin);
        return in_array($country, $neighbors);
    }

    /**
     * Returns the list of destiny neighbor countries
     *
     * @param string $origin Country of departure.
     *
     * @todo Change method to load neighbor countries list from a service
     *
     * @return array
     */
    protected function getNeighborCountries($origin)
    {
        return ['Brasil', 'Paraguay', 'Palestine'];
    }

    /**
     * Indicates if given country limits with country of origin.
     *
     * @param string $country
     * @param string $origin
     *
     * @return bool
     */
    protected function isRiskyCountry($country)
    {
        $riskyCountries = $this->getRiskyCountries();
        return in_array($country, $riskyCountries);
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

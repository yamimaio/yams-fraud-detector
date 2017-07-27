# README #

Yam's Fraud Detector

### System Requirements ###
* Web server with URL rewriting
* PHP 7.0 or newer

### Setup ###
1. cd to project root 
1. ```git clone https://yamimaio@bitbucket.org/yamimaio/yams-fraud-detector.git .```
1. Navigate into your project’s root directory and execute the bash command ```composer install```.
1. Setup your localhost, Apache or Nginx to serve /app

### Available Routes ###
Only routes accesible are:

### fraud/status ###
Indicates if the given order is a fraud or not.
Response:
```javascript
{
    "status": false
}
```

Method: POST
Content-type: application/json
Payload: Order information (see Order definition)
Check it out on Heroku: https://warm-plateau-67794.herokuapp.com/fraud/status

### fraud/scoring ###
Returns the fraud scoring for the given order.
Response:
```javascript
{
    "scoring": 70
}
```

Method: POST
Content-type: application/json
Payload: Order information (see Order definition)
Check it out on Heroku:  https://warm-plateau-67794.herokuapp.com/fraud/scoring

### Order Definition ###
```javascript
{
  "transaction": {
    "order_id": "1234-A",
    "ordered_on": "2017-02-13 21:27:45",
    "order_total": 122.23,
    "order_currency": "USD",
    "user_id": "22",
    "user_first_name": "Yamila",
    "user_last_name": "Maio"
  },
  "billing": {
    "first_name": "John",
    "last_name": "Smith",
    "address1": "123 Main Street",
    "company": "Acme Inc",
    "city": "New York City",
    "region": "NY",
    "postal_code": "10001",
    "country": "US",
    "email": "email@acme.com",
    "phone": "212-289-1293"
  },
  "payment": {
    "method": "credit_card",
    "type": "mc",
    "cc_holder": "Yamila Maio",
    "cc_number": "56657577655559999",
    "exp_date": "0221",
    "payment_status": "paid"
  },
  "travel_ticket": {
    "trip_currency": "USD",
    "method": "plane",
    "from_code": "LAXTUF2",
    "from_name": "5201 E Olympic Blvd E",
    "from_city": "Los Angeles",
    "from_country": "USA",
    "depart_on": "2017-05-15 10:00:00",
    "to_code": "LASTUF",
    "to_name": "99 S Martin L King Blvd",
    "return_on:": "2017-05-15 15:05:00",
    "to_city": "Las Vegas",
    "to_country": "USA",
    "trip_type": "one-way",
    "passengers": 1
  },
  "travel_passengers": [
    {
      "first_name": "John",
      "last_name": "Smith"
    }
  ]
}
```
### Fraud Detector Configuration ###

Fraud Detector and scoring system can be configured in ```app/config/config.json``` file.

#### Options ####
* fraudScoring: Int. Scoring which will define an order is fraud. Default: 80.
* maxScoring: Int. Maximum possible scoring. Default: 100.
* rules: Rules for risk scoring. Array of objects. Each object MUST have at least a name property with name of the rule. If no rule should be applied, set as an empty array. See available list of Rule names.
* maxScoringRules: Rules that if broken, define immediate fraud.  Array of objects. Each object MUST have at least a name property with name of the rule. If no rule should be applied, set as an empty array. See available list of Rule names.


#### Rules ####
The following rules are available (for either standar or max scoring rule. You may use them as you wish).

For each rule you choose as standard you may indicate the property "scoring" which will set the scoring level this rule adds if broken. If scoring is not set it defaults to 10.

For each rule you choose as maxScoringRule the scoring level will be automatically set to whatever you have configured as maxScoring.

* CCHolderLastName: Credit Card Holder last name is not found in passengers last names. 
* DepartureTimeFrame: Date/Time of order is less than a given interval away from Date/Time of departure. For this rule you must add the property "timeFrame" (in seconds). TimeFrame will be the minimum interval of time from order request to departure that is accetable as not risky.
* PaxLastName: None of the passengers share last name.
* RiskyCountry: Destiny country is marked as risky. This can happen under 2 circumstances. a) the country is marked as risky b) the country limits with the departure country. If both cases are met (limit country is also risky), scoring assigned is doubled.
* BlacklistedCard: Credit Card it blacklisted as stolen. This rule defaults to a maxScoringRule. 

#### Try it out ####
If you wish to test rules you can use:

* Blacklisted credit card: 5665777755559999
* Risky Countries: Iran, Irak or Palestine
* Neighbor Countries: Brasil, Paraguay or Palestine


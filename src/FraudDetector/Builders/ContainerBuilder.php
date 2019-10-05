<?php
/**
 * ContainerBuilder.php
 *
 * @author Yamila Maio <yamimaio@gmail.com
 */

namespace FraudDetector\Builders;

use FraudDetector\Actions\FraudScoringAction;
use FraudDetector\Actions\FraudStatusAction as FraudStatusAction;
use FraudDetector\Factories\RuleFactory;
use Monolog\Handler\StreamHandler as StreamHandler;
use Monolog\Logger as Logger;
use Psr\Container\ContainerInterface as Container;

/**
 * Configures a Slim containers
 */
class ContainerBuilder
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * ContainerBuilder constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Configures the Slim Container
     *
     * @return Container
     */
    public function getContainer()
    {
        $this->addLogger();
        $this->addFraudStatusAction();
        $this->addFraudScoreAction();

        return $this->container;
    }


    /**
     * Adds logger to container
     */
    protected function addLogger()
    {
        $this->container['logger'] = function ($c) {
            $logger = new Logger('fraud_logger');
            $file_handler = new StreamHandler('php://stderr');
            $logger->pushHandler($file_handler);
            return $logger;
        };
    }


    /**
     * Adds FraudStatusAction to container
     */
    protected function addFraudStatusAction()
    {
        $this->container['FraudStatusAction'] = function ($c) {
            $detector = $this->getFraudDetector();
            return new FraudStatusAction([
                'logger' => $c['logger'],
                'detector' => $detector
            ]);
        };
    }

    /**
     * Returns a FraudDetector
     *
     * @return \FraudDetector\FraudDetector
     */
    protected function getFraudDetector()
    {
        $factory = new RuleFactory();
        $builder = new FraudDetectorBuilder($factory);
        $config = $this->container->get('settings')['detectorConfig'];
        return $builder->getFraudDetector($config);
    }

    /**
     * Adds FraudScoringAction to container
     *
     */
    protected function addFraudScoreAction()
    {
        $this->container['FraudScoringAction'] = function ($c) {
            $detector = $this->getFraudDetector();
            return new FraudScoringAction([
                'logger' => $c['logger'],
                'detector' => $detector
            ]);
        };
    }
}

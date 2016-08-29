<?php
/**
 * ZF3 book Entity Builder Console Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/entity-builder
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace EntityBuilder\Command;

use Zend\Config\Factory;
use Zend\Console\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Exception\InvalidArgumentException;
use Zend\Db\Exception\RuntimeException;

/**
 * Abstract class AbstractCommand
 *
 * @package EntityBuilder\Command
 */
abstract class AbstractCommand
{
    /**
     * @var Adapter
     */
    private $dbAdapter;

    /**
     * @return Adapter
     */
    protected function getDbAdapter()
    {
        return $this->dbAdapter;
    }

    /**
     * @param AdapterInterface $console
     *
     * @return boolean
     */
    protected function connectToDatabase(AdapterInterface $console)
    {
        $config = Factory::fromFile(PROJECT_ROOT . '/config/database.php');

        try {
            $this->dbAdapter = new Adapter($config['db']);
        } catch (InvalidArgumentException $e) {
            $console->writeLine(
                '[ERROR] Database configuration seems to be inconsistent'
            );

            return false;
        }

        try {
            $this->dbAdapter->getDriver()->getConnection()->connect();
        } catch (RuntimeException $e) {
            $console->writeLine(
                '[ERROR] Database connection failed'
            );

            return false;
        }

        return true;
    }
}

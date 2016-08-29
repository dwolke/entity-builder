<?php
/**
 * ZF3 book Entity Builder Console Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/entity-builder
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace EntityBuilder\Command;

use Zend\Console\Adapter\AdapterInterface;
use Zend\Db\Metadata\Metadata;
use Zend\Db\Metadata\Object\TableObject;
use ZF\Console\Route;

/**
 * Class ShowTables
 *
 * @package EntityBuilder\Command
 */
class ShowTables extends AbstractCommand
{
    /**
     * @param Route            $route
     * @param AdapterInterface $console
     *
     * @return int
     */
    public function __invoke(Route $route, AdapterInterface $console)
    {
        $result = $this->connectToDatabase($console);

        if (!$result) {
            return 1;
        }

        $metaData = new Metadata($this->getDbAdapter());

        $database = $this->getDbAdapter()->getCurrentSchema();

        $console->writeLine(
            '[INFO] Connected to database "' . $database . '"'
        );
        $console->writeLine();

        $tables = $metaData->getTables();

        if (count($tables) > 0) {
            $console->writeLine(
                '[INFO] Found the following tables'
            );
        }

        /** @var TableObject $tableObject */
        foreach ($tables as $tableObject) {
            $console->writeLine(
                '       * ' . $tableObject->getName()
            );
        }

        return 0;
    }
}

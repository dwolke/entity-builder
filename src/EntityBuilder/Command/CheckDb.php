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
use ZF\Console\Route;

/**
 * Class CheckDb
 *
 * @package EntityBuilder\Command
 */
class CheckDb extends AbstractCommand
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

        $console->writeLine(
            '[SUCCESS] Database connection was successful'
        );

        return 0;
    }
}

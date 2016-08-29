<?php
/**
 * ZF3 book Entity Builder Console Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/entity-builder
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/**
 * ZF3 book Entity Builder Console Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/entity-builder
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace EntityBuilder\Command;

use EntityBuilder\Generator\EntityClassGenerator;
use EntityBuilder\Generator\EntityFileGenerator;
use Zend\Console\Adapter\AdapterInterface;
use Zend\Db\Metadata\Metadata;
use Zend\Db\Metadata\Object\ColumnObject;
use Zend\Db\Metadata\Object\TableObject;
use Zend\Filter\StaticFilter;
use ZF\Console\Route;

/**
 * Class CreateEntity
 *
 * @package EntityBuilder\Command
 */
class CreateEntity extends AbstractCommand
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

        $tableParam = $route->getMatchedParam('table');

        try {
            $tableObject = $metaData->getTable($tableParam);
        } catch (\Exception $e) {
            $console->writeLine(
                '[ERROR] Table "' . $tableParam . '" not found'
            );

            return 1;
        }

        $console->writeLine(
            '[INFO] Table "' . $tableObject->getName() . '" was found'
        );
        $console->writeLine();

        $tableColumns = $this->fetchTableColumn($tableObject);

        $camelCasedName = StaticFilter::execute(
            $tableParam, 'WordDashToCamelCase'
        );

        $className = $camelCasedName . 'Entity';
        $nameSpace = $camelCasedName . '\\Entity';

        $entityClass = new EntityClassGenerator(
            $className, $nameSpace, $tableColumns
        );
        $entityFile = new EntityFileGenerator($entityClass);

        $entityDirectory = PROJECT_ROOT . '/data/classes/';
        $entityDirectory .= $camelCasedName . '/Entity';

        $entityFileName = $className . '.php';

        $this->saveEntityFile(
            $entityFile, $entityDirectory, $entityFileName
        );

        $console->writeLine(
            '[SUCCESS] Entity ' . $entityDirectory .
            '/' . $entityFileName . ' generated'
        );

        return 0;
    }

    /**
     * @param TableObject $tableObject
     *
     * @return array
     */
    private function fetchTableColumn(TableObject $tableObject)
    {
        $tableColumns = [];

        foreach ($tableObject->getColumns() as $column) {
            /** @var $column ColumnObject */
            switch ($column->getDataType()) {
                case 'varchar':
                case 'text':
                case 'timestamp':
                case 'datetime':
                    $type = 'string';
                    break;

                default:
                    $type = 'integer';
            }

            $tableColumns[$column->getName()] = $type;
        }

        return $tableColumns;
    }

    /**
     * @param EntityFileGenerator $entityFile
     * @param $entityDirectory
     * @param $entityFileName
     */
    private function saveEntityFile(
        EntityFileGenerator $entityFile, $entityDirectory, $entityFileName
    ) {
        if (!is_dir($entityDirectory)) {
            mkdir($entityDirectory, 0777, true);
        }

        file_put_contents(
            $entityDirectory . '/' . $entityFileName,
            $entityFile->generate()
        );
    }
}

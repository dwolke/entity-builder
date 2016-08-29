<?php
/**
 * ZF3 book Entity Builder Console Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/entity-builder
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace EntityBuilder\Generator;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Db\Metadata\Object\ColumnObject;
use Zend\Filter\StaticFilter;

/**
 * Class EntityClassGenerator
 *
 * @package EntityBuilder\Generator
 */
class EntityClassGenerator extends ClassGenerator
{
    /**
     * EntityClassGenerator constructor.
     *
     * @param null|string $name
     * @param null|string $namespaceName
     * @param array       $columns
     */
    public function __construct($name, $namespaceName, array $columns = [])
    {
        parent::__construct($name, $namespaceName);

        $this->setDocBlock(
            new DocBlockGenerator(
                'Class ' . $namespaceName . '\\' . $name
            )
        );

        /** @var ColumnObject $column */
        foreach ($columns as $colName => $colType) {
            $property  = $this->generateProperty($colName, $colType);
            $getMethod = $this->generateGetMethod($colName, $colType);
            $setMethod = $this->generateSetMethod($colName, $colType);

            $this->addPropertyFromGenerator($property);
            $this->addMethodFromGenerator($getMethod);
            $this->addMethodFromGenerator($setMethod);
        }
    }

    /**
     * @param $colName
     * @param $colType
     *
     * @return PropertyGenerator
     */
    protected function generateProperty($colName, $colType)
    {
        $property = new PropertyGenerator($colName);
        $property->addFlag(PropertyGenerator::FLAG_PROTECTED);
        $property->setDocBlock(
            new DocBlockGenerator(
                $colName . ' property',
                null,
                [
                    [
                        'name'        => 'var',
                        'description' => $colType,
                    ]
                ]
            )
        );

        return $property;
    }

    /**
     * @param $colName
     * @param $colType
     *
     * @return MethodGenerator
     */
    protected function generateGetMethod($colName, $colType)
    {
        $getMethodName = 'get' . StaticFilter::execute(
                $colName, 'WordUnderscoreToCamelCase'
            );

        $getMethod = new MethodGenerator($getMethodName);
        $getMethod->addFlag(MethodGenerator::FLAG_PUBLIC);
        $getMethod->setDocBlock(
            new DocBlockGenerator(
                'Get ' . $colName,
                null,
                [
                    [
                        'name'        => 'return',
                        'description' => $colType,
                    ]
                ]
            )
        );
        $getMethod->setBody('return $this->' . $colName . ';');

        return $getMethod;
    }

    /**
     * @param $colName
     * @param $colType
     *
     * @return MethodGenerator
     */
    protected function generateSetMethod($colName, $colType)
    {
        $setMethodName = 'set' . StaticFilter::execute(
                $colName, 'WordUnderscoreToCamelCase'
            );

        $setMethod = new MethodGenerator($setMethodName);
        $setMethod->addFlag(MethodGenerator::FLAG_PUBLIC);
        $setMethod->setParameter(
            new ParameterGenerator($colName)
        );
        $setMethod->setDocBlock(
            new DocBlockGenerator(
                'Set ' . $colName,
                null,
                [
                    [
                        'name'        => 'param',
                        'description' => $colType . ' $' . $colName,
                    ]
                ]
            )
        );
        $setMethod->setBody(
            '$this->' . $colName . ' = $' . $colName . ';'
        );

        return $setMethod;
    }
}

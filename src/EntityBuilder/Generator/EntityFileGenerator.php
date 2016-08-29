<?php
/**
 * ZF3 book Entity Builder Console Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/entity-builder
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace EntityBuilder\Generator;

use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\FileGenerator;

/**
 * Class EntityFileGenerator
 *
 * @package EntityBuilder\Generator
 */
class EntityFileGenerator extends FileGenerator
{
    /**
     * EntityClassGenerator constructor.
     *
     * @param EntityClassGenerator $class
     */
    public function __construct(EntityClassGenerator $class)
    {
        parent::__construct();

        $this->setClass($class);

        $this->setDocBlock(
            new DocBlockGenerator(
                'Automatically generated file',
                null,
                [
                    [
                        'name'        => 'package',
                        'description' => $class->getNamespaceName(),
                    ]
                ]
            )
        );
    }
}

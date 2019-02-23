<?php

namespace GraphQL\SchemaGenerator\CodeGenerator;

use GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile;

/**
 * Class QueryObjectClassBuilder
 *
 * @package GraphQL\SchemaManager\CodeGenerator
 */
class QueryObjectClassBuilder implements ObjectBuilderInterface
{
    /**
     * @var ClassFile
     */
    protected $classFile;

    /**
     * QueryObjectClassBuilder constructor.
     *
     * @param string $writeDir
     * @param string $objectName
     */
    public function __construct($writeDir, $objectName)
    {
        $className = $objectName . 'QueryObject';

        $this->classFile = new ClassFile($writeDir, $className);
        $this->classFile->setNamespace('GraphQL\\SchemaObject');
        $this->classFile->extendsClass('QueryObject');
        $this->classFile->addConstant('OBJECT_NAME', $objectName);
    }

    /**
     * @param string $propertyName
     */
    public function addProperty($propertyName)
    {
        $this->classFile->addProperty($propertyName);
    }

    /**
     * @param string $propertyName
     * @param string $upperCamelName
     */
    public function addSimpleSetter($propertyName, $upperCamelName)
    {
        $lowerCamelName = lcfirst($upperCamelName);
        $method = "public function set$upperCamelName($$lowerCamelName)
{
    \$this->$propertyName = $$lowerCamelName;

    return \$this;
}";
        $this->classFile->addMethod($method);
    }

    /**
     * @param $propertyName
     * @param $upperCamelName
     * @param $propertyType
     */
    public function addListSetter($propertyName, $upperCamelName, $propertyType)
    {
        $lowerCamelName = lcfirst($upperCamelName);
        $method = "public function set$upperCamelName(array $$lowerCamelName)
{
    \$this->$propertyName = $$lowerCamelName;

    return \$this;
}";
        $this->classFile->addMethod($method);
    }

    /**
     * @param string $propertyName
     * @param string $upperCamelName
     * @param string $objectClass
     */
    public function addInputObjectSetter($propertyName, $upperCamelName, $objectClass)
    {
        $lowerCamelName = lcfirst(str_replace('_', '', $objectClass));
        $method = "public function set$upperCamelName($objectClass $$lowerCamelName)
{
    \$this->$propertyName = $$lowerCamelName;

    return \$this;
}";
        $this->classFile->addMethod($method);
    }

    /**
     * @param string $propertyName
     * @param string $upperCamelName
     */
    public function addSimpleSelector($propertyName, $upperCamelName)
    {
        $method = "public function select$upperCamelName()
{
    \$this->selectField('$propertyName');

    return \$this;
}";
        $this->classFile->addMethod($method);
    }

    /**
     * @param string $fieldName
     * @param string $upperCamelName
     * @param string $fieldTypeName
     */
    public function addObjectSelector($fieldName, $upperCamelName, $fieldTypeName)
    {
        $objectClassName = $fieldTypeName . 'QueryObject';
        $method = "public function select$upperCamelName()
{
    \$object = new $objectClassName('$fieldName');
    \$this->selectField(\$object);

    return \$object;
}";
        $this->classFile->addMethod($method);
    }

    /**
     * This method builds the class and writes it to the file system
     */
    public function build()
    {
        $this->classFile->writeFile();
    }
}
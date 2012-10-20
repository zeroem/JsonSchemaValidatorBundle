<?php

namespace Zeroem\JsonSchemaValidatorBundle;

use Zeroem\JsonBundle\Json\JsonInterface;

class JsonSchemaValidator
{
    private $schema;
    private $validator;
    private $json;

    public function __construct(Validator $validator, JsonInterface $json, $schema) {
        $this->json = $json;
        $this->schema = $schema;
    }

    public function validate($json) {
        if(is_string($json)) {
            $json = $this->json->decode($json);
        }

        $this->validator->check($json, $this->getSchema());

        return $this->validator->isValid();
    }

    public function getErrors() {
        return $this->validator->getErrors();
    }

    public function getSchema() {
        if(is_string($this->schema)) {
            $this->schema = $this->json->decode($this->schema);
            assert(is_object($this->schema));
        }

        return $this->schema;
    }
}

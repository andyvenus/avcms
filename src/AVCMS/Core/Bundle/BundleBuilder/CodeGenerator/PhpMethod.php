<?php

/*
 * Copyright 2011 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace AVCMS\Core\Bundle\BundleBuilder\CodeGenerator;

class PhpMethod extends \CG\Generator\PhpMethod
{
    public static function fromReflection(\ReflectionMethod $ref, $body = false)
    {
        $method = parent::fromReflection($ref);

        if ($body === true) {
            $start_line = $ref->getStartLine() - 1;
            $end_line = $ref->getEndLine();
            $length = $end_line - $start_line;

            $source = file($ref->getFileName());
            $extracted_method = implode("", array_slice($source, $start_line, $length));

            $start = strpos($extracted_method, '{') + 2;
            $end = strrpos($extracted_method, '}');
            $length = $end - $start;

            $extracted_body = substr($extracted_method, $start, $length);

            $lines = explode("\n", $extracted_body);
            $final = '';
            foreach ($lines as $line) {
                $final .= substr($line, 8)."\n";
            }

            $method->setBody($final);
        }

        return $method;
    }
}
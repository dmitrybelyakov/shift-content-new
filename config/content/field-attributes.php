<?php
return array(

/*
 * Content field attributes
 */

//filters
'filters' => array(

    'Alnum' => array(
        'name' => 'Alphabetic and numbers',
        'description' => 'This filter allows only whitespace and characters',
        'class' => 'Zend\I18n\Filter\Alnum',
        'options' => array(
            'allowWhitespace' => array('name' => 'Allow whitespaces', 'type' => 'bool')
        )
    ),

    'Alpha' => array(
        'name' => 'Alphabetic and numbers',
        'description' => 'This filter allows only whitespace and characters',
        'class' => 'Zend\I18n\Filter\Alnum',
        'options' => array(
            'allowWhitespace' => array('name' => 'Allow whitespaces', 'type' => 'bool')
        )
    ),

    'Digits' => array(
        'name' => 'Digits',
        'description' => 'This filters allows only numbers',
        'class' => 'Zend\Filter\Digits',
        'options' => array()
    ),

    'HtmlEntities' => array(
        'name' => 'HTML Entities',
        'description' => 'This filter converts all HTML tags to special characters',
        'class' => 'Zend\Filter\Digits',
        'options' => array(
            'quoteStyle' => array('name' => 'Quote style', 'type' => 'string'),
            'encoding' => array('name' => 'Character set', 'type' => 'string'),
            'doubleQuote' => array('name' => 'Double quote', 'type' => 'bool')
        )
    ),

    'Int' => array(
        'name' => 'Convert to integer',
        'description' => 'This filter converts any given input to an integer',
        'class' => 'Zend\Filter\Int',
        'options' => array()
    ),

    'StringToLower' => array(
        'name' => 'Lowercase',
        'description' => 'Converts the given input to lower case',
        'class' => 'Zend\Filter\StringToLower',
        'options' => array(
            'encoding' => array('name' => 'Character set', 'type' => 'string'),
        )
    ),

    'StringTrim' => array(
        'name' => 'Trim',
        'description' => 'Trims whitespace or any defined characters from beginning and end of input',
        'class' => 'Zend\Filter\StringTrim',
        'options' => array(
            'charList' => array('name' => 'Character list', 'type' => 'string')
        )
    ),

    'StripNewLines' => array(
        'name' => 'Strip new lines',
        'description' => 'Removes all new line characters from input',
        'class' => 'Zend\Filter\StripNewLines',
        'options' => array()
    ),

    'StripTags' => array(
        'name' => 'Strip tags',
        'description' => 'Removes XML and HTML tags from input',
        'class' => 'Zend\Filter\StripTags',
        'options' => array(
            'allowTags' => array('name' => 'Allow these tags', 'type' => 'string'),
            'allowAttribs' => array('name' => 'Allow these tag attributes', 'type' => 'string'),
        )
    ),

    'StringToUpper' => array(
        'name' => 'Uppercase',
        'description' => 'Converts the given input to upper case',
        'class' => 'Zend\Filter\StringToUpper',
        'options' => array(
            'encoding' => array('name' => 'Character set', 'type' => 'string'),
        )
    ),



),

//validators
'validators' => array(

    'Alnum' => array(
        'name' => 'Alphabetic and numbers',
        'description' => 'Checks that input contains only alphabetic characters and numbers',
        'class' => 'Zend\I18n\Validator\Alnum',
        'options' => array(
            'allowWhitespace' => array('name' => 'Allow whitespaces', 'type' => 'bool')
        )
    ),

    'Alpha' => array(
        'name' => 'Alphabetic',
        'description' => 'Checks that input contains only alphabetic characters',
        'class' => 'Zend\I18n\Validator\Alpha',
        'options' => array(
            'allowWhitespace' => array('name' => 'Allow whitespaces', 'type' => 'bool')
        )
    ),

    'Between' => array(
        'name' => 'Between',
        'description' => 'Checks if the given value is in certain range',
        'class' => 'Zend\Validator\Between',
        'options' => array(
            'min' => array('name' => 'Minimum', 'type' => 'int'),
            'max' => array('name' => 'Maximum', 'type' => 'int'),
        )
    ),

    'Digits' => array(
        'name' => 'Digits',
        'description' => 'Checks if the given value contains only digits',
        'class' => 'Zend\Validator\Digits',
        'options' => array()
    ),

    'EmailAddress' => array(
        'name' => 'Email address',
        'description' => 'Checks if the given input is a valid email address',
        'class' => 'Zend\Validator\EmailAddress',
        'options' => array()
    ),

    'Float' => array(
        'name' => 'Float',
        'description' => 'Check if input is a floating point value',
        'class' => 'Zend\Validator\Float',
        'options' => array()
    ),

    'GreaterThan' => array(
        'name' => 'Greater than',
        'description' => 'Check if input is greater than defined value',
        'class' => 'Zend\Validator\GreaterThan',
        'options' => array(
            'min' => array('name' => 'Minimum', 'type' => 'int'),
        )
    ),

    'Hostname' => array(
        'name' => 'Host name',
        'description' => 'Check if input is a valid host name or IP address',
        'class' => 'Zend\Validator\Hostname',
        'options' => array()
    ),

    'Ip' => array(
        'name' => 'IP address',
        'description' => 'Check if input is a valid IP address',
        'class' => 'Zend\Validator\Ip',
        'options' => array()
    ),

    'Isbn' => array(
        'name' => 'ISBN code',
        'description' => 'Check if input is a valid ISBN book code',
        'class' => 'Zend\Validator\Isbn',
        'options' => array()
    ),

    'LessThan' => array(
        'name' => 'Less than',
        'description' => 'Check if input is less than defined value',
        'class' => 'Zend\Validator\LessThan',
        'options' => array(
            'max' => array('name' => 'Maximim', 'type' => 'int'),
        )
    ),

    'NotEmpty' => array(
        'name' => 'Not empty',
        'description' => 'Checks that input is not empty',
        'class' => 'Zend\Validator\NotEmpty',
        'options' => array()
    ),

    'StringLength' => array(
        'name' => 'String length',
        'description' => 'Checks if input length is withing minimum and maximum',
        'class' => 'Zend\Validator\StringLength',
        'options' => array(
            'min' => array('name' => 'Minimum', 'type' => 'int'),
            'max' => array('name' => 'Maximum', 'type' => 'int'),
        )
    ),

),

);

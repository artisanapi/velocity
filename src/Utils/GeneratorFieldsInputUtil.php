<?php

namespace ArtisanApi\Velocity\Utils;

use ArtisanApi\Velocity\Common\GeneratorField;

class GeneratorFieldsInputUtil
{
    public static function validateFieldInput($fieldInputStr)
    {
        $fieldInputs = explode(' ', $fieldInputStr);

        if (count($fieldInputs) < 2) {
            return false;
        }

        return true;
    }

    /**
     * @param string $fieldInput
     * @param string $validations
     *
     * @return GeneratorField
     */
    public static function processFieldInput($fieldInput, $validations)
    {
        /*
         * Field Input Format: field_name <space> db_type <space> html_type(optional) <space> options(optional)
         * Options are to skip the field from certain criteria like searchable, fillable, not in form, not in index
         * Searchable (s), Fillable (f), In Form (if), In Index (ii)
         * Sample Field Inputs
         *
         * title string text
         * body text textarea
         * name string,20 text
         * post_id integer:unsigned:nullable
         * post_id integer:unsigned:nullable:foreign,posts,id
         * password string text if,ii,s - options will skip field from being added in form, in index and searchable
         */

        $fieldInputsArr = explode(' ', $fieldInput);

        $field = new GeneratorField();
        $field->name = $fieldInputsArr[0];
        $field->parseDBType($fieldInputsArr[1]);

        if (count($fieldInputsArr) > 2) {
            $field->parseOptions($fieldInputsArr[2]);
        }

        $field->validations = $validations;

        return $field;
    }

    public static function prepareValuesArrayStr($arr)
    {
        $arrStr = '[';
        foreach ($arr as $item) {
            $arrStr .= "'$item', ";
        }

        $arrStr = substr($arrStr, 0, strlen($arrStr) - 2);

        $arrStr .= ']';

        return $arrStr;
    }
}

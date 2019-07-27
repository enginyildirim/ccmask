<?php
namespace enginyildirim
class ccMask{

    public function mask($string)
    {
        $regex = '/(?:\d[ \t-]*?){13,19}/m';

        $matches = [];

        preg_match_all($regex, $string, $matches);

        // No credit card found
        if (!isset($matches[0]) || empty($matches[0]))
        {
            return $string;
        }

        foreach ($matches as $match_group)
        {
            foreach ($match_group as $match)
            {
                $stripped_match = preg_replace('/[^\d]/', '', $match);

                if($this->validateLuhn($stripped_match)){
                    $card_length = strlen($stripped_match);
                    $replacement = substr($stripped_match, 0,6) .str_pad('', $card_length - 10, '*') . substr($stripped_match, -4);
                    $string = str_replace($match, $replacement, $string);
                }
            }
        }

        return $string;
    }

    function validateLuhn($cc_number)
    {
        $positions = [0,2,4,6,8,1,3,5,7,9];
        $length = strlen($cc_number);
        $bit = true;
        $sum = 0;
        $val = null;
        while ($length) {
            $val = $cc_number[--$length];
            if($bit xor true){
                $sum += $positions[$val];
                $bit = true;
            }
            else
            {
                $sum += $val;
                $bit = false;
            }
        }

        return $sum && $sum % 10 === 0;
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SplFixedArray;

class ProblemSolvingController extends Controller
{
    // problem solving num1
    public function getCount()
    {
        $validator = Validator::make(
            $_GET,
            [
                'number1' => 'required',
                'number2' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        /* having 2 ways to get range of number1 & number2
            1- range(number1,number2)
            2- for loop with array push 
                $arr = [];    
                for($i = $_GET['number1'] ; $i <= $_GET['number1'] ; $i++) 
                {
                    array_push($arr, $i);  
                }
        */
        $arr = [];
        $count = 0;
        for ($i = $_GET['number1']; $i <= $_GET['number2']; $i++) {
            $str_i = strval($i);
            if (!str_contains($str_i, '5')) {
                array_push($arr, $i);
                $count++;
            }
        }

        return response()->json([
            'success' => true,
            'number 1 ' => $_GET['number1'],
            'number 2 ' => $_GET['number2'],
            'Count' => $count,
            'array' => $arr,
        ]);
    }

    // problem solving num2
    public function inputString()
    {
        $validator = Validator::make(
            $_GET,
            [
                'input_string' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        /*#Algorithem
                1- get string length = n 
                2- value of Char * 26 ^ n-1
                3- n--
            #end
        */

        //By ASCII code  *** 
        $str = $_GET['input_string'];
        $result = 0;
        for ($i = 0; $i < strlen($str); $i++) {
            $result *= 26;
            $result += ord($str[$i]) - ord('A') + 1;
        }
        return response()->json([
            'success' => true,
            'result' => $result,
        ]);
        // By List tryal ***

        /* 
            $char = array(
                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
                'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
            ); // list of characters

            $str = "BFG"; //inpust string
            $res = 0;   //result of inputs
            $allChar = str_split($str);  // Characters on array

            for ($n = strlen($str); $n > 0; $n--) {
                $key = array_search($allChar[$n], $char) + 1;
                $res += $key * pow(26, $n - 1);
            }
            die(var_dump($res));
        */
    }

    // problem solving num3
    public function minSteps()
    {
        $validator = Validator::make(
            $_GET,
            [
                'N' => 'required',
                'Q' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        $steps = 0;
        $arr_steps = [];

        foreach ($_GET['Q'] as $val) {
            $num = intval($val);

            for ($i = $num; $i >= 0; $i--) {
                $flag = $this->checkPrime($i);     // check number is prime or not
                if ($flag == 0 && $i > 1)
                    $i = $i / 2;
                
                if ($i != 0)
                    $steps++;
            }
            array_push($arr_steps, $steps);
            $steps = 0;
        }

        return response()->json([
            'success' => true,
            'N' => $_GET['N'],
            'array' => $arr_steps,
        ]);
    }

    protected function checkPrime($num)
    {
        if ($num <= 1)
            return 0;
        for ($x = 2; $x <= $num / 2; $x++) {
            if (fmod($num, $x) == 0)
                return 0;
        }
        return 1;
    }
}

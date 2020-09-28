<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

class TestAPIController extends AppBaseController
{
    /**
     *
     * @param Request $request
     * @return Response
     */
    public function test()
    {
        if ($handle = opendir('/var/task')) {

            while (false !== ($entry = readdir($handle))) {

                if ($entry != "." && $entry != "..") {

                    echo "$entry\n";
                }
            }

            closedir($handle);


            echo "<br>";
        }

        if ($handle = opendir('/var/task/storage')) {

            while (false !== ($entry = readdir($handle))) {

                if ($entry != "." && $entry != "..") {

                    echo "$entry\n";
                }
            }

            closedir($handle);


            echo "<br>";
        }

        if ($handle = opendir('/var/task/app')) {

            while (false !== ($entry = readdir($handle))) {

                if ($entry != "." && $entry != "..") {

                    echo "$entry\n";
                }
            }

            closedir($handle);


            echo "<br>";
        }

        if ($handle = opendir('/var/task/app/Components')) {

            while (false !== ($entry = readdir($handle))) {

                if ($entry != "." && $entry != "..") {

                    echo "$entry\n";
                }
            }

            closedir($handle);


            echo "<br>";
        }

        if ($handle = opendir('/var/task/app/Components/plantillas_pdf')) {

            while (false !== ($entry = readdir($handle))) {

                if ($entry != "." && $entry != "..") {

                    echo "$entry\n";
                }
            }

            closedir($handle);


            echo "<br>";
        }
    }
}

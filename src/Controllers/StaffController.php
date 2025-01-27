<?php

namespace Src\Controllers;

class StaffController
{
    // Handle the /staffs route
    public function index()
    {
        echo "List of Staffs:";
        // Simulate fetching data (just for example)
        echo "<br>Staff 1: John Doe";
        echo "<br>Staff 2: Jane Smith";
        echo "<br>Staff 3: Alice Johnson";
    }
}

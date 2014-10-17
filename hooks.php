<?php

/**
 * Auther : Stratusclear
 * 
 */
#***************************************************
# Get public IP address
#***************************************************

function get_user_ip_address() {
    // Consider: http://stackoverflow.com/questions/4581789/how-do-i-get-user-ip-address-in-django
    // Consider: http://networkengineering.stackexchange.com/questions/2283/how-to-to-determine-if-an-address-is-a-public-ip-address

    $ip_addresses = array();
    $ip_elements = array(
        'HTTP_X_FORWARDED_FOR', 'HTTP_FORWARDED_FOR',
        'HTTP_X_FORWARDED', 'HTTP_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_CLUSTER_CLIENT_IP',
        'HTTP_X_CLIENT_IP', 'HTTP_CLIENT_IP',
        'REMOTE_ADDR'
    );

    foreach ($ip_elements as $element) {
        if (isset($_SERVER[$element])) {
            if (!is_string($_SERVER[$element])) {
                // Log the value somehow, to improve the script!
                continue;
            }

            $address_list = explode(',', $_SERVER[$element]);
            $address_list = array_map('trim', $address_list);

            // Not using array_merge in order to preserve order
            foreach ($address_list as $x) {
                $ip_addresses[] = $x;
            }
        }
    }

    if (count($ip_addresses) == 0) {
        return FALSE;
    } elseif ($force_string === TRUE || ( $force_string === NULL && count($ip_addresses) == 1 )) {
        return $ip_addresses[0];
    } else {
        return $ip_addresses;
    }
}

function hook_create_asana_task($vars) {

    $ticketid = $vars['ticketid'];
    $deptid = $vars['deptid'];
    $deptname = $vars['deptname'];
    $subject = $vars['subject'];
    $message = $vars['message'];
    $priority = $vars['priority'];
    $userid = $vars['userid'];
    #---------------------------------------------------------------------------------
    # Retrieve data from databases........
    #---------------------------------------------------------------------------------

    $query = 'SELECT `workspace_id`, `project_id`, `api_key`,`license_key` FROM `mod_asana_addon`';
    $result = mysql_query($query) or die(mysql_error());
    $data = mysql_fetch_assoc($result);

    $workspce_id = $data['workspace_id'];
    $project_id = $data['project_id'];
    $api_key = $data['api_key'];
    $License_Key = $data['license_key'];
    
    $query1 = 'SELECT `tid` FROM `tbltickets` WHERE `id` =' . $ticketid;
    $result1 = mysql_query($query1) or die(mysql_error());
    $data1 = mysql_fetch_assoc($result1);

    $ticketid = $data1['tid'];

    $query2 = 'SELECT `email` FROM `tblclients` WHERE `id` =' . $userid;
    $result2 = mysql_query($query2) or die(mysql_error());
    $data2 = mysql_fetch_assoc($result2);

    $emailid = $data2['email'];

    $headers = apache_request_headers();

    $clientIP = $headers['X-Forwarded-For'];

    if (!empty($clientIP)) {

        $geoPlugin_array = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $clientIP));
    } else {

        $geoPlugin_array = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . get_user_ip_address()));
    }

    $country = $geoPlugin_array['geoplugin_countryName'];
    $browser = $_SERVER['HTTP_USER_AGENT'];

    require_once("asana.php");

    #-------------------------------------------------------------------------------
    #See class comments and Asana API for full info
    #-------------------------------------------------------------------------------

    $asana = new Asana($api_key);  // Your API Key, you can get it in Asana
    #-----------------------------------------------------------------------------
    #First we create the task
    #-----------------------------------------------------------------------------

    $task_name = $subject . "[#$ticketid]";

    $notes = "Ticket Priority : " . $priority . "\n Department Name : " . $deptname . "\n Submitter Email ID : " . $emailid . "\n Message : " . $message . "\n Country : " . $country . "\n Browser & OS : " . $browser;

    #-------------------------------------------------------------------------------
    #Check for license key.
    #-------------------------------------------------------------------------------

    $query = 'SELECT `local_key` FROM `mod_asana_addon`';
    $result = mysql_query($query) or die(mysql_error());
    $data = mysql_fetch_assoc($result);
    $localkey = $data['local_key'];
    require_once 'checklicense.php';
    if ($localkey == "") {
        $localkey = "9tjI4EDMxMTMwIjI6gjOztjIlRXYkt2Ylh2YioTO6M3OiIjY3M2NjhTYwImYhJTNjFmN2M2YyMGN5EWO
       yYzMiRjYiojMzozc7ICazFGa1QWbiozN6M3Oic3d3xFctF2dcpzQioTMxozc7ISey9GdjVmcpRGZpxWY
2JiO0EjOztjIx4CMuAjL3ITMioTO6M3OiAXaklGbhZnI6cjOztjI0N3boxWYj9Gbuc3d3xCdz9GasF2Y
vxmI6MjM6M3Oi4Wah12bkRWasFmdioTMxozc7ICduV3bjNWQgUWZyZkI6ITM6M3OiUGbjl3Yn5WasxWa
iJiOyEjOztjIwATLwATLwADMwIiOwEjOztjIlRXYkVWdkRHel5mI6ETM6M3OicTMtATMtMTMwIjI6ATM
6M3OiUGdhR2ZlJnI6cjOztjIzt2chRFIh5WYzFEIoRXa3Byc0V2ajlGVgQncvBHc1NFITNUTIdlI6gzM
6M3OiUWbh5GdjVHZvJHcioTMxozc7IyNioTM6M3OiQWa0NWdk9mcwJiO5ozc7ICOyEjI6MjOztjIklWZ
jlmdyV2cioTO6M3Oi02bj5CbpFWbnBkMxAjMvJHcyVGZuFGah1mI6UjM6M3OiwWah1WZioTN6M3OiIXY
lx2YzVHdhJHdTJiOyEjOztjIl1WYulnbhBXbvNmI6ETM6M3Oig2Zul2UgIXZk5WYoFWTioDNxozc7ISZ
tFmbkVmclR3cpdWZyJiO0EjOztjIlZXa0NWQiojN6M3OiMXd0FGdzJiO2ozc7pTNxoTYd850bf9c8bc8
40fb58907a79499efdadfc5673388be228d18876a858075b730c";
    }

    $results = check_license($License_Key, $localkey);
    if ($results["status"] == "Active") {

        $result = $asana->createTask(array(
            "workspace" => $workspce_id, // Workspace ID
            "name" => $task_name, // Name of task
            "notes" => $notes,
                ));
    }
    #----------------------------------------------------------------------------------
    #As Asana API documentation says, when a task is created, 201 response code is sent back so...
    #----------------------------------------------------------------------------------

    if ($asana->responseCode == "201" && !is_null($result)) {
        $resultJson = json_decode($result);

        $taskId = $resultJson->data->id; // Here we have the id of the task that have been created
        #-----------------------------------------------------------------------
        #Now we do another request to add the task to a project....
        #-----------------------------------------------------------------------

        $result = $asana->addProjectToTask($taskId, $project_id);
        if ($asana->responseCode != "200") {
            echo "Error while assigning project to task: {$asana->responseCode}";
        }
    } else {
        echo "Error while trying to connect to Asana, response code: {$asana->responseCode}";
    }
}

#****************************************************************************************************
# Ticket submit from addon
#****************************************************************************************************

function hook_create_asana_task1($vars) {

    $ticketid = $vars['ticketid'];
    $deptid = $vars['deptid'];
    $deptname = $vars['deptname'];
    $subject = $vars['subject'];
    $message = $vars['message'];
    $priority = $vars['priority'];

    #---------------------------------------------------------------------------------
    # Retrieve data from databases........
    #---------------------------------------------------------------------------------

    $query = 'SELECT `workspace_id`, `project_id`, `api_key`,`license_key` FROM `mod_asana_addon`';
    $result = mysql_query($query) or die(mysql_error());
    $data = mysql_fetch_assoc($result);

    $workspce_id = $data['workspace_id'];
    $project_id = $data['project_id'];
    $api_key = $data['api_key'];
    $License_Key = $data['license_key'];

    $query1 = 'SELECT `tid` FROM `tbltickets` WHERE `id` =' . $ticketid;
    $result1 = mysql_query($query1) or die(mysql_error());
    $data1 = mysql_fetch_assoc($result1);

    $ticketid = $data1['tid'];

    $headers = apache_request_headers();
    $clientIP = $headers['X-Forwarded-For'];
    $country = $headers['Cf-Ipcountry'];
    $browser = $headers['User-Agent'];

    require_once("asana.php");

    #-------------------------------------------------------------------------------
    #See class comments and Asana API for full info
    #-------------------------------------------------------------------------------

    $asana = new Asana($api_key);  // Your API Key, you can get it in Asana
    #-----------------------------------------------------------------------------
    #First we create the task
    #-----------------------------------------------------------------------------

    $task_name = $subject . "[#$ticketid]";
    $notes = "Ticket Priority : " . $priority . "\n Department Name : " . $deptname . "\n Submitter Email ID : Ticket submit by admin. \n Message : " . $message . "\n\n Client IP : " . $clientIP . "\n Countrycode : " . $country . "\n Browser & OS : " . $browser;

    #-------------------------------------------------------------------------------
    #Check for license key.
    #-------------------------------------------------------------------------------

    $query = 'SELECT `local_key` FROM `mod_asana_addon`';
    $result = mysql_query($query) or die(mysql_error());
    $data = mysql_fetch_assoc($result);
    $localkey = $data['local_key'];
    require_once 'checklicense.php';
    if ($localkey == "") {
        $localkey = "9tjI4EDMxMTMwIjI6gjOztjIlRXYkt2Ylh2YioTO6M3OiIjY3M2NjhTYwImYhJTNjFmN2M2YyMGN5EWO
       yYzMiRjYiojMzozc7ICazFGa1QWbiozN6M3Oic3d3xFctF2dcpzQioTMxozc7ISey9GdjVmcpRGZpxWY
2JiO0EjOztjIx4CMuAjL3ITMioTO6M3OiAXaklGbhZnI6cjOztjI0N3boxWYj9Gbuc3d3xCdz9GasF2Y
vxmI6MjM6M3Oi4Wah12bkRWasFmdioTMxozc7ICduV3bjNWQgUWZyZkI6ITM6M3OiUGbjl3Yn5WasxWa
iJiOyEjOztjIwATLwATLwADMwIiOwEjOztjIlRXYkVWdkRHel5mI6ETM6M3OicTMtATMtMTMwIjI6ATM
6M3OiUGdhR2ZlJnI6cjOztjIzt2chRFIh5WYzFEIoRXa3Byc0V2ajlGVgQncvBHc1NFITNUTIdlI6gzM
6M3OiUWbh5GdjVHZvJHcioTMxozc7IyNioTM6M3OiQWa0NWdk9mcwJiO5ozc7ICOyEjI6MjOztjIklWZ
jlmdyV2cioTO6M3Oi02bj5CbpFWbnBkMxAjMvJHcyVGZuFGah1mI6UjM6M3OiwWah1WZioTN6M3OiIXY
lx2YzVHdhJHdTJiOyEjOztjIl1WYulnbhBXbvNmI6ETM6M3Oig2Zul2UgIXZk5WYoFWTioDNxozc7ISZ
tFmbkVmclR3cpdWZyJiO0EjOztjIlZXa0NWQiojN6M3OiMXd0FGdzJiO2ozc7pTNxoTYd850bf9c8bc8
40fb58907a79499efdadfc5673388be228d18876a858075b730c";
    }
    $results = check_license($License_Key, $localkey);
    if ($results["status"] == "Active") {
        $result = $asana->createTask(array(
            "workspace" => $workspce_id, // Workspace ID
            "name" => $task_name, // Name of task
            "notes" => $notes,
                ));
    }

    #----------------------------------------------------------------------------------
    #As Asana API documentation says, when a task is created, 201 response code is sent back so...
    #----------------------------------------------------------------------------------

    if ($asana->responseCode == "201" && !is_null($result)) {
        $resultJson = json_decode($result);

        $taskId = $resultJson->data->id; // Here we have the id of the task that have been created
        #-----------------------------------------------------------------------
        #Now we do another request to add the task to a project....
        #-----------------------------------------------------------------------

        $result = $asana->addProjectToTask($taskId, $project_id);
        if ($asana->responseCode != "200") {
            echo "Error while assigning project to task: {$asana->responseCode}";
        }
    } else {
        echo "Error while trying to connect to Asana, response code: {$asana->responseCode}";
    }
}

add_hook("TicketOpenAdmin", 2, "hook_create_asana_task1");
add_hook("TicketOpen", 1, "hook_create_asana_task");
?>

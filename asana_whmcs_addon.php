<?php
if (!defined("WHMCS"))
    die("This file cannot be accessed directly");
/*
 * ********************************************************

 * ** Addon Module ***

  This addon is developed to communicate with ASANA system.
  for the client by Stratusclear
 * ********************************************************
 */


#------------------------------------------------------------------------------------------
# Update Addon License key.
#------------------------------------------------------------------------------------------

$license_key = $_POST['fields']['asana_addon']['license_key'];
if ($license_key != '') {

    $query = 'UPDATE `mod_asana_addon` SET `license_key`="' . $license_key . '"';
    $result = mysql_query($query) or die(mysql_error());
}

function asana_addon_config() {
    $configarray = array(
        "name" => "ASANA ADDON",
        "description" => "This Addon is used to communicate WHMCS support with ASANA system.",
        "version" => "1.1",
        "author" => "Stratusclear",
        "language" => "english",
        "fields" => array(
            "api_key" => array("FriendlyName" => "ASANA API Key", "Type" => "text", "Size" => "35", "Description" => "Enter ASANA API Key.", "Default" => "",),
            "license_key" => array("FriendlyName" => "Addon License Key", "Type" => "text", "Size" => "35", "Description" => "Enter ASANA Addon License Key.", "Default" => "",),
            ));
    return $configarray;
}

function asana_addon_activate() {
    $query = "CREATE TABLE `mod_asana_addon` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`workspace_id` VARCHAR( 100 ) NOT NULL, `project_id` VARCHAR( 100 ) NOT NULL, `api_key` VARCHAR( 100 ) NOT NULL ,`local_key` VARCHAR( 100 ) NOT NULL ,`license_key` VARCHAR( 100 ) NOT NULL,`created` DATETIME NOT NULL ,`modified` DATETIME NOT NULL)";
    $result = mysql_query($query) or die(mysql_error());
    $query = 'INSERT INTO `mod_asana_addon` (`workspace_id`,`project_id`,`api_key`,`local_key`,`license_key`) VALUES ("XXX","XXX","XXX","","XXX")';
    $result = mysql_query($query) or die(mysql_error());
    # Return Result
    return array('status' => 'success', 'description' => 'Addon Successfully Installed');
    return array('status' => 'error', 'description' => 'There was a problem in activating the addon. Please try again later');
    return array('status' => 'info', 'description' => 'You can use the info status return to display a message to the user');
}

function asana_addon_deactivate() {

# Remove Custom DB Table
    $query = "DROP TABLE `mod_asana_addon`";
    $result = mysql_query($query);
# Return Result
    return array('status' => 'success', 'description' => 'If successful, you can return a message to show the user here');
    return array('status' => 'error', 'description' => 'If an error occurs you can return an error message for display here');
    return array('status' => 'info', 'description' => 'If you want to give an info message to a user you can return it here');
}

function asana_addon_upgrade($vars) {
    $version = $vars['version'];
# Run SQL Updates for V1.0 to V1.1
    if ($version < 1.2) {
        $query = "ALTER `mod_asana_addon` ADD `demo2` TEXT NOT NULL ";
        $result = mysql_query($query);
    }

# Run SQL Updates for V1.1 to V1.2
    if ($version < 1.2) {
        $query = "ALTER `mod_asana_addon` ADD `demo3` TEXT NOT NULL ";
        $result = mysql_query($query);
    }
}

function asana_addon_output($vars) {

    $modulelink = $vars['modulelink'];
    $version = $vars['version'];
    $Api_Key = $vars['api_key'];
    $License_Key = $vars['license_key'];
    $companyusername = $vars['companyusername'];
    $password = $vars[''];
    $option4 = $vars['optpasswordion4'];
    $option5 = $vars['option5'];
    $LANG = $vars['_lang'];

    #---------------------------------------------------------------------------------------------
    # Check licence validation
    #---------------------------------------------------------------------------------------------
    #--------------------------------------------------------------------------------------------
    # Get local key...
    #--------------------------------------------------------------------------------------------

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
    #------------------------------------------------------------------------------------------------------------------------
    # The call below actually performs the license check. You need to pass in the license key and the local key data.
    #------------------------------------------------------------------------------------------------------------------------
    $results = check_license($License_Key, $localkey);
    if ($results["status"] == "Active") {

        #---------------------------------------------------------------------------------------------
        # Get ASANA Workspace
        #---------------------------------------------------------------------------------------------

        require_once("asana.php");

        #----------------------------------------------------------------------------------
        #See class comments and Asana API for full info
        #----------------------------------------------------------------------------------

        $asana = new Asana($Api_Key); // Your API Key, you can get it in Asana
        #---------------------------------------------------------------------------------
        #Get all workspaces
        #---------------------------------------------------------------------------------

        $workspaces = $asana->getWorkspaces();
        if (isset($_POST['action']) & $_POST['action'] == 'asana_addon') {
            create_tasks($vars);
        }
        if (isset($_GET['action']) & $_GET['action'] == 'asanasupport') {
            asanaSupport($vars);
        }
        if (isset($_POST['action']) & $_POST['action'] == 'asana_support') {
            asanaSupport($vars);
        } else {
            ?>

            <!--  JS to retrieve ASANA projects  using AJAX  -->

            <script>
                                                                                                                                                                                                                                                                                                                            
                $( document ).ready(function () {
                    $('#workspace').change(function () {
                        var location = window.location.host + window.location.pathname;
                        var n = location.split("/");
                        var ajaxURL = '';
                        for (var i=0;i<n.length-2;i++)
                        {
                            var ajaxURL = ajaxURL+ n[i]+"/";
                        }
                        if (window.location.protocol == "https:") {
                            var url = 'https://'+ajaxURL;
                        }
                        if (window.location.protocol == "http:") {
                            var url = 'http://'+ajaxURL;
                        }
                        $.get(url+'modules/addons/asana_addon/ajax.php?option=' + $('#workspace').val(), function(data) {
                            if(data == "error")
                            {
                                alert("Ajax error");
                            }
                            else {
                                $('div.projects').html(data); //create an element where you want to add 
                            }
                        });
                    });
                });
                                                                                                                                                                                                                                                                                                                                    
            </script>

            <div class="contentarea">
                <div id="tabs"><ul><li class="tabselected"><a href="addonmodules.php?module=asana_addon">Create Asana Tasks</a></li><li class="tab"> <a href="addonmodules.php?module=asana_addon&action=asanasupport">Asana Setting with Support System</a></li></ul></div>
                <form action="<?php echo $modulelink ?>" method="post">
                    <input type="hidden" value="0e72d2d840039be33494c469ecc576894c78c420" name="token">
                    <input type="hidden" value="asana_addon" name="action">
                    <div class="tablebg">
                        <table width="100%" cellspacing="1" cellpadding="3" border="0" class="datatable">
                            <tbody>
                                <tr>
                                    <td  colspan="2" style="border-bottom: 0px solid #EBEBEB;">&nbsp;</td>  
                                </tr>
                                <tr>
                                    <td style="width:450px;border-bottom: 0px solid #EBEBEB;" align="right">Choose the Asana Workspace to which the tasks should be assigned:</td>
                                    <td style="border-bottom: 0px solid #EBEBEB;">
                                        <?php
                                        if ($asana->responseCode == "200" && !is_null($workspaces)) {
                                            $workspacesJson = json_decode($workspaces);
                                            ?> 
                                            <select id="workspace" name="workspace">
                                                <?php
                                                $i = 1;
                                                foreach ($workspacesJson->data as $workspace) {
                                                    if ($i == 1) {
                                                        $workspace_id = (string)$workspace->id;
                                                    }
                                                    ?>

                                                    <option value="<?php echo (string)$workspace->id . "@" . $Api_Key ?>"><?php echo $workspace->name ?></option>

                                                    <?php
                                                    $i++;
                                                }
                                                ?> 
                                            </select>
                                            <?php
                                        } else {
                                            echo " Error :Invalid ASANA API Key.";
                                        }
                                        ?> 
                                    </td>   
                                </tr>
                                <tr>
                                    <td style="width:450px;border-bottom: 0px solid #EBEBEB;" align="right">Task name:</td>
                                    <td style="border-bottom: 0px solid #EBEBEB;">
                                        <input type="text" value="" name="asana_new_task_name" size="50"> 
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:450px;border-bottom: 0px solid #EBEBEB;" align="right">Notes:</td>
                                    <td style="border-bottom: 0px solid #EBEBEB;">
                                        <textarea name="asana_new_task_notes" cols="47" rows="4"> </textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:450px;border-bottom: 0px solid #EBEBEB;" align="right">Choose the Asana Project to which the tasks should be assigned:</td>
                                    <td style="border-bottom: 0px solid #EBEBEB;">
                                        <div class="projects">
                                            <?php
                                            $asana1 = new Asana($Api_Key);

                                            // Get all projects in the current workspace (all non-archived projects)

                                            $projects = $asana1->getProjectsInWorkspace($workspace_id, $archived = false);

                                            // As Asana API documentation says, when response is successful, we receive a 200 in response so...
                                            ?>
                                            <select name="project">
                                                <option>Choose a Project</option>
                                                <?php
                                                if ($asana->responseCode == "200" && !is_null($projects)) {

                                                    $projectsJson = json_decode($projects);
                                                    foreach ($projectsJson->data as $project) {
                                                        ?>
                                                        <option value="<?php echo (string)$project->id; ?>"> <?php echo $project->name; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>

                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:450px;border-bottom: 0px solid #EBEBEB;" align="right">Assignee Email ID :</td>
                                    <td style="border-bottom: 0px solid #EBEBEB;">
                                        <input type="text" value="" name="assignee" size="50">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:450px;border-bottom: 0px solid #EBEBEB;" align="right">Due Date :</td>
                                    <td style="border-bottom: 0px solid #EBEBEB;">
                                        <input type="text" value="" name="asana_due_date" size="50"> &nbsp; (YYYY-MM-DD)
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:450px;border-bottom: 0px solid #EBEBEB;" align="right">Add Tags :</td>
                                    <td style="border-bottom: 0px solid #EBEBEB;">
                                        <input type="text" value="" name="tag" size="50"> &nbsp; Separated by commas e.g. domain, hosting, software etc.
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-bottom: 0px solid #EBEBEB;" align="center"><br/>
                                        <input type="submit" name="asanabtn" value="Create Task"/>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <?php
            if ($results["localkey"]) {
                # Save Updated Local Key to DB or File
                
                $localkeydata = $results["localkey"];
                $query = 'UPDATE `mod_asana_addon` SET `local_key`="' . $localkeydata . '"';
                $result = mysql_query($query) or die(mysql_error());
            }
        }
    } // End of license check script...
    elseif ($results["status"] == "Invalid") {
        echo '<h2><font color="red">Invalid License Key</font></h2>';
    } elseif ($results["status"] == "Expired") {
        echo '<h2><font color="red">Expired License Key</font></h2>';
    } elseif ($results["status"] == "Suspended") {
        echo '<h2><font color="red">Suspended License Key</font></h2>';
    } else {
        echo '<h2><font color="red">License check script error</font></h2>';
    }
}

function asanaSupport($vars) {

    $modulelink = $vars['modulelink'];
    $version = $vars['version'];
    $Api_Key = $vars['api_key'];
    $companyusername = $vars['companyusername'];
    $password = $vars['password'];
    $option4 = $vars['option4'];
    $option5 = $vars['option5'];
    $LANG = $vars['_lang'];


    #---------------------------------------------------------------------------------------------
    # Get ASANA Workspace
    #---------------------------------------------------------------------------------------------

    require_once("asana.php");

    #----------------------------------------------------------------------------------
    #See class comments and Asana API for full info
    #----------------------------------------------------------------------------------

    $asana = new Asana($Api_Key); // Your API Key, you can get it in Asana
    #---------------------------------------------------------------------------------
    #Get all workspaces
    #---------------------------------------------------------------------------------

    $workspaces = $asana->getWorkspaces();

    $option = $_POST['workspace'];
    $workspace_id_api_key = explode("@", $option);
    $workspace_id = $workspace_id_api_key[0];
    $workspace_id1 = $workspace_id_api_key[0];
    if (isset($_POST['action']) && $_POST['project'] != 'Choose a Project') {

        $query = 'UPDATE `mod_asana_addon` SET `workspace_id`="' . $workspace_id . '" , `project_id`="' . $_POST['project'] . '" , `api_key`="' . $Api_Key . '"';
        $result = mysql_query($query) or die(mysql_error());
    }

    #-----------------------------------------------------------------------------------
    # Retrieve data from database.
    #-----------------------------------------------------------------------------------

    $query = 'SELECT `workspace_id`, `project_id`, `api_key` FROM `mod_asana_addon`';
    $result = mysql_query($query) or die(mysql_error());
    $data = mysql_fetch_assoc($result);

    #-----------------------------------------------------------------------------------
    # Validation for project dropdown.....
    #-----------------------------------------------------------------------------------

    if ($_POST['project'] == 'Choose a Project') {
        echo '<font color="red">*Please select a project.</font>';
    }
    ?>    
    <script>
                                                                                                                                                                
        $( document ).ready(function () {
            $('#workspace').change(function () {
                            
                var location = window.location.host + window.location.pathname;
                var n = location.split("/");
                var ajaxURL = '';
                for (var i=0;i<n.length-2;i++)
                {
                    var ajaxURL = ajaxURL+ n[i]+"/";
                }
                var url = 'http://'+ajaxURL;
                $.get(url+'modules/addons/asana_addon/ajax.php?option=' + $('#workspace').val(), function(data) {
                    if(data == "error")
                    {
                        alert("Ajax error");
                    }
                    else {
                        $('div.projects').html(data); //create an element where you want to add 
                    }
                });
            });
        });
                                                                                                                                                                        
    </script>    
    <div class="contentarea">
        <div id="tabs"><ul><li class="tabs"><a href="addonmodules.php?module=asana_addon">Create Asana Tasks</a></li><li class="tabselected"> <a href="addonmodules.php?module=asana_addon&action=asanasupport">Asana Setting with Support System</a></li></ul></div>
        <form action="addonmodules.php?module=asana_addon" method="post">
            <input type="hidden" value="0e72d2d840039be33494c469ecc576894c78c420" name="token">
            <input type="hidden" value="asana_support" name="action">
            <div class="tablebg">
                <table width="100%" cellspacing="1" cellpadding="3" border="0" class="datatable">
                    <tbody>
                        <tr>
                    <br/> <td style="width:450px;border-bottom: 0px solid #EBEBEB;" align="right">Choose the Asana Workspace :</td>
                    <td style="border-bottom: 0px solid #EBEBEB;">
                        <?php
                        if ($asana->responseCode == "200" && !is_null($workspaces)) {
                            $workspacesJson = json_decode($workspaces);
                            ?> 
                            <select id="workspace" name="workspace">
                                <?php
                                $i = 1;
                                foreach ($workspacesJson->data as $workspace) {
                                    if ($i == 1) {
                                        $workspace_id = (string)$workspace->id;
                                    }

                                    #-----------------------------------------------------------------
                                    #Preserve select dropdown value 
                                    #-----------------------------------------------------------------

                                    if ($data['workspace_id'] == $workspace->id) {

                                        echo '<option selected="selected" value ="' . (string)$workspace->id . '@' . $Api_Key . '">' . $workspace->name . '</option>';
                                    } else {
                                        ?>
                                        <option value="<?php echo (string)$workspace->id . "@" . $Api_Key ?>"><?php echo $workspace->name ?></option>

                                        <?php
                                    }
                                    $i++;
                                }
                                ?> 
                            </select>
                            <?php
                        } else {
                            echo " Error :Invalid ASANA API Key.";
                        }
                        ?> 
                    </td>   
                    </tr>
                    <?php
                    $asana = new Asana($Api_Key);

                    // Get project
                    $result = $asana->getProject($data['project_id']);
                    // As Asana API documentation says, when response is successful, we receive a 200 in response so...
                    if ($asana->responseCode == "200" && !is_null($result)) {
                        $resultJson = json_decode($result);
                        // $resultJson contains an object in json with all projects
                        ?>
                        <tr>
                            <td style="width:450px;border-bottom: 0px solid #EBEBEB;" align="right">Previous stored project for selected Workspace  :</td>
                            <td style="border-bottom: 0px solid #EBEBEB;">
                                <input type="text" value="<?php echo $resultJson->data->name; ?>" name="" size="30" disabled="disabled">
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td style="width:450px;border-bottom: 0px solid #EBEBEB;" align="right">Choose the Asana Project :</td>
                        <td style="border-bottom: 0px solid #EBEBEB;">
                            <div class="projects">
                                <?php
                                $asana1 = new Asana($Api_Key);

                                // Get all projects in the current workspace (all non-archived projects)

                                $projects = $asana1->getProjectsInWorkspace($workspace_id, $archived = false);

                                // As Asana API documentation says, when response is successful, we receive a 200 in response so...
                                ?>
                                <select name="project">
                                    <option>Choose a Project</option>
                                    <?php
                                    if ($asana1->responseCode == "200" && !is_null($projects)) {

                                        $projectsJson = json_decode($projects);
                                        foreach ($projectsJson->data as $project) {

                                            #-----------------------------------------------------------------
                                            #Preserve select dropdown value 
                                            #-----------------------------------------------------------------

                                            if ($data['project_id'] == $project->id) {

                                                echo '<option selected="selected" value ="' . (string)$project->id . '">' . $project->name . '</option>';
                                            } else {
                                                ?>
                                                <option value="<?php echo (string)$project->id; ?>"> <?php echo $project->name; ?></option>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-bottom: 0px solid #EBEBEB;" align="center"><br/><input type="submit" name="setting" value="Save Setting"/></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </div>    

    <?php
}

function create_tasks($vars) {


    #--------------------------------------------------------------------------------
    # Form post and other variables
    #--------------------------------------------------------------------------------
    $modulelink = $vars['modulelink'];
    $version = $vars['version'];
    $Api_Key = $vars['api_key'];
    $companyusername = $vars['companyusername'];
    $password = $vars['password'];
    $option4 = $vars['option4'];
    $option5 = $vars['option5'];
    $LANG = $vars['_lang'];

    $option = $_POST['workspace'];
    $workspace_id_api_key = explode("@", $option);
    $workspace_id = $workspace_id_api_key[0];
    $task_name = $_POST['asana_new_task_name'];
    $task_notes = $_POST['asana_new_task_notes'];
    $project_id = $_POST['project'];
    $due_date = $_POST['asana_due_date'];
    $assignee = $_POST['assignee'];
    if ($project_id == 'Choose a Project') {
        echo '<font color="red">*Please select a project.</font>';
    } else {
        #--------------------------------------------------------------------------------
        #Get URL of the current page.
        #--------------------------------------------------------------------------------


        require_once("asana.php");

        #-------------------------------------------------------------------------------
        #See class comments and Asana API for full info
        #-------------------------------------------------------------------------------

        $asana = new Asana($Api_Key);  // Your API Key, you can get it in Asana

        $workspaceId = $workspace_id;  // The workspace where we want to create our task
        $projectId = $project_id;  // The project where we want to save our task
        #-----------------------------------------------------------------------------
        #First we create the task
        #-----------------------------------------------------------------------------

        $result = $asana->createTask(array(
            "workspace" => $workspaceId, // Workspace ID
            "name" => $task_name, // Name of task
            "notes" => $task_notes,
            "due_on" => $due_date,
            "assignee" => $assignee, // Assign task to...
                ));

        #----------------------------------------------------------------------------------
        #As Asana API documentation says, when a task is created, 201 response code is sent back so...
        #----------------------------------------------------------------------------------

        if ($asana->responseCode == "201" && !is_null($result)) {
            $resultJson = json_decode($result);

            $taskId = $resultJson->data->id; // Here we have the id of the task that have been created
            #Now we do another request to add the task to a project
            $result = $asana->addProjectToTask($taskId, $projectId);
            if ($asana->responseCode != "200") {
                echo "Error while assigning project to task: {$asana->responseCode}";
            }
        } else {
            echo "Error while trying to connect to Asana, response code: {$asana->responseCode}";
        }

        #-------------------------------------------------------------------------------------
        # Create new tag....................
        #-------------------------------------------------------------------------------------

        if ($_POST['tag'] != ' ') {

            $tags = explode(",", $_POST['tag']);
            for ($i = 0; $i < count($tags); $i++) {
                $result = $asana->createTag(array(
                    "name" => $tags[$i], // Name of task
                    "workspace" => $workspaceId, // Workspace ID
                        ));
                if ($asana->responseCode == "201" && !is_null($result)) {
                    $resultJson = json_decode($result);
                    $tagId = $resultJson->data->id; // Here we have the id of the task that have been created
                    #Now we do another request to add the task to a project
                    $result = $asana->addTagToTask($taskId, $tagId);
                    if ($asana->responseCode != "200") {
                        echo "Error while add tag to task: {$asana->responseCode}";
                    }
                } else {
                    echo "Error while trying to connect to Asana, response code: {$asana->responseCode}";
                }
            } // End for loop........
        }
        $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
        $protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/") . $s;
        $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
        $CurUrl = $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
        header('Location:' . $CurUrl);
    }
}

function strleft($s1, $s2) {
    return substr($s1, 0, strpos($s1, $s2));
}
?>

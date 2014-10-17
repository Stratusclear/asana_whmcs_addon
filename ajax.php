<?php
if (isset($_REQUEST['option'])) {

    require_once("asana.php");
    $option = $_REQUEST['option'];
    $workspace_id_api_key = explode("@", $option);
    $workspace_id = $workspace_id_api_key[0];
    $api_key = $workspace_id_api_key[1];

    $asana = new Asana($api_key); // Your API Key, you can get it in Asana

    $result = $asana->getProjectsInWorkspace($workspace_id, $archived = false);

// As Asana API documentation says, when response is successful, we receive a 200 in response so...
    if ($asana->responseCode == "200" && !is_null($result)) {
        $resultJson = json_decode($result);

        // $resultJson contains an object in json with all projects
        ?>
        <select name="project">
            <option> Choose a Project </option>
            <?php
            foreach ($resultJson->data as $project) {
                ?>        
                <option value="<?php echo (string)$project->id ?>" > <?php echo $project->name ?> </option>
                <?php
            } // END foreach
            ?>    
        </select>        
            <?php
        } else {
            echo "Error while trying to connect to Asana, response code: {$asana->responseCode}";
        }
    }
    ?>

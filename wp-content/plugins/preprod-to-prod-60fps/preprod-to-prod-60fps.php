<?php
/**
 * Plugin Name: Deploy from preprod to prod 60fps
 * Plugin URI: http://www.60fps.fr
 * Description: Deploys the preproduction to production
 * Version: 1.0
 * Author: 60fps
 * Author URI: http://www.60fps.fr
 */


if (!function_exists('preprod_to_prod_60fps_can_execute')) {
	function preprod_to_prod_60fps_can_execute(){
		$user = wp_get_current_user();
		$allowed_roles = array('administrator', 'administrator_restricted');
		return !empty(array_intersect($allowed_roles, (array)$user->roles ));
	}
}

if (!function_exists('preprod_to_prod_60fps_can_edit_params')) {
	function preprod_to_prod_60fps_can_edit_params(){
		$user = wp_get_current_user();
		$allowed_roles = array('administrator');
		return !empty(array_intersect($allowed_roles, (array)$user->roles ));
	}
}

if (!function_exists('preprod_to_prod_60fps_menu')) {
	function preprod_to_prod_60fps_menu() {
		if(preprod_to_prod_60fps_can_execute()){
			add_options_page( 'Deploy to production', 'Deploy to production', 'manage_options', 'preprod_to_prod_60fps', 'preprod_to_prod_60fps_options' );
		}
	}
}

if (!function_exists('preprod_to_prod_60fps_options')) {
	function preprod_to_prod_60fps_options() {
	    //must check that the user has the required capability
	    if (!preprod_to_prod_60fps_can_execute())
	    {
	      wp_die( 'You do not have sufficient permissions to access this page.');
	    }


	    // variables for the field and option names
	    $username_opt_name 		= 'preprod_to_prod_60fps_username';
	    $usertoken_opt_name 	= 'preprod_to_prod_60fps_usertoken';
	    $projectname_opt_name 	= 'preprod_to_prod_60fps_projectname';
	    $allowon_opt_name 		= 'preprod_to_prod_60fps_allowon';
	    $hidden_field_name 		= 'preprod_to_prod_60fps_submit_hidden';
	    $username_field_name 	= 'preprod_to_prod_60fps_username';
	    $usertoken_field_name 	= 'preprod_to_prod_60fps_usertoken';
	    $projectname_field_name = 'preprod_to_prod_60fps_projectname';
	    $allowon_field_name = 'preprod_to_prod_60fps_allowon';

	    // Read in existing option value from database
	    $username_opt_val 		= get_option( $username_opt_name );
	    $usertoken_opt_val 		= get_option( $usertoken_opt_name );
	    $projectname_opt_val 	= rawurldecode(get_option( $projectname_opt_name ));
	    $allowon_opt_val 		= get_option( $allowon_opt_name );

	    // See if the user has posted us some information
	    // If they did, this hidden field will be set to 'Y'
	    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
	        // Read their posted value
	        $username_opt_val = $_POST[ $username_field_name ];
	        $usertoken_opt_val = $_POST[ $usertoken_field_name ];
	        $projectname_opt_val = $_POST[ $projectname_field_name ];
	        $allowon_opt_val = $_POST[ $allowon_field_name ];

	        // Save the posted value in the database
	        update_option( $username_opt_name, $username_opt_val );
	        update_option( $usertoken_opt_name, $usertoken_opt_val );
	        update_option( $projectname_opt_name, rawurlencode($projectname_opt_val) );
	        update_option( $allowon_opt_name, $allowon_opt_val );

	        // Put a "settings saved" message on the screen

			?>
			<div class="updated"><p><strong><?php _e('Settings saved.', 'preprod_to_prod_60fps' ); ?></strong></p></div>
			<?php

		}

		$settingsSet = !empty($username_opt_val) && !empty($usertoken_opt_val) && !empty($projectname_opt_val) && $_SERVER['SERVER_NAME'] == $allowon_opt_val;
	    ?>



        <div class="wrap">

	        <h1><?php _e( 'Deploy to production', 'preprod_to_prod_60fps' ); ?></h1>

	        <?php if($settingsSet) : ?>
	        <table class="form-table" role="presentation">
	            <tbody>
	                <tr>
	                    <th scope="row"><label>Latest deploy</label></th>
	                    <td>
	    					<div id="preprod_to_prod_60fps_lastest-deploys"></div>
	                    </td>
	                </tr>
	                <tr>
	                    <th scope="row"><label>Actions</label></th>
	                    <td>
	                    	<button id="preprod_to_prod_60fps_deploy-button" class="button-primary" disabled>Deploy</button>
	                    	&nbsp;&nbsp;&nbsp;&nbsp;
	                    	<label><input type="checkbox" id="preprod_to_prod_60fps_disable-deploy" disabled> Disable Deploys</label>
	                    </td>
	                </tr>
	            </tbody>
	        </table>
    		<br/><br/><br/>
    		<?php endif; ?>

			<?php if (preprod_to_prod_60fps_can_edit_params()) {?>
				<form name="form1" method="post" action="">
				<h2><?php _e( 'Settings', 'preprod_to_prod_60fps' ); ?></h2>

				<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row"><label for="blogname"><?php _e("User:", 'preprod_to_prod_60fps' ); ?></label></th>
							<td><input type="text" name="<?php echo $username_field_name; ?>" value="<?php echo $username_opt_val; ?>" class="regular-text"></td>
						</tr>
						<tr>
							<th scope="row"><label for="blogname"><?php _e("Token:", 'preprod_to_prod_60fps' ); ?></label></th>
							<td><input type="text" name="<?php echo $usertoken_opt_name; ?>" value="<?php echo $usertoken_opt_val; ?>" class="regular-text"></td>
						</tr>
						<tr>
							<th scope="row"><label for="blogname"><?php _e("Project name:", 'preprod_to_prod_60fps' ); ?></label></th>
							<td><input type="text" name="<?php echo $projectname_opt_name; ?>" value="<?php echo $projectname_opt_val; ?>" class="regular-text"></td>
						</tr>
						<tr>
							<th scope="row"><label for="blogname"><?php _e("Allow on:", 'preprod_to_prod_60fps' ); ?></label></th>
							<td><input type="text" name="<?php echo $allowon_opt_name; ?>" value="<?php echo $allowon_opt_val; ?>" class="regular-text"></td>
						</tr>

					</tbody>
				</table>

				<p class="submit">
				<input type="submit" name="Update settings" class="button-primary" value="<?php esc_attr_e('Update Settings', 'preprod_to_prod_60fps') ?>" />
				</p>

				</form>
			<?php } ?>
    	</div>


	    <?php if($settingsSet) : ?>
		<script>
			(() => {
				const rootUrl = "https://ci.60fps.fr";
				const jobName = "<?php echo rawurlencode($projectname_opt_val); ?>";
				const authUser = "<?php echo $username_opt_val; ?>";
				const authToken = "<?php echo $usertoken_opt_val; ?>";

				let building 	 		= true;
				let previousBuilding 	= true;

				let disabled 	 		= true;
				let previousDisabled    = false;

				let deployTimer = null;

				const latestDeploys = document.getElementById('preprod_to_prod_60fps_lastest-deploys');
				const deployButton  = document.getElementById('preprod_to_prod_60fps_deploy-button');
				const disableDeployCheckbox  = document.getElementById('preprod_to_prod_60fps_disable-deploy');

				const getEndpoint 	= url => url.replace("https://ci.60fps.fr", '');
				const fetchData 	= (endpoint, type="GET") => {
					return window.fetch(rootUrl+(endpoint.replaceAll('%2F', '/')), {
						method:type,
					    headers: {
					      	'Accept': 'application/json',
					      	'Content-Type': 'application/json',
							'Authorization' : 'Basic ' + btoa(`${authUser}:${authToken}`)
					    }
					});
				};

				const lineDisplay = (label, value) => {
					return `<b>${label} : </b><span>${value}</span><br/>`;
				}

				const toggleDeploy = async () => {
					if(disabled)
						fetchData(`/job/${jobName}/enable`, 'POST');
					else{
						deployButton.removeEventListener('click', runDeploy);
						deployButton.setAttribute('disabled', true);
						fetchData(`/job/${jobName}/disable`, 'POST');
					}
					setTimeout(updateStats, 2000);
				}

				const runDeploy = async () => {
					deployButton.removeEventListener('click', runDeploy);
					deployButton.setAttribute('disabled', true);
					fetchData(`/job/${jobName}/build`, 'POST');
					setTimeout(updateStats, 2000);
				}

				const updateStats = async () => {
					const jobData = await (await fetchData(`/job/${jobName}/api/json?pretty=true`)).json();
					const lastBuildEndpoint = getEndpoint(jobData.lastBuild.url) + "/api/json?pretty=true";
					const lastBuildData = await (await fetchData(lastBuildEndpoint)).json();

					const now 		= Date.now();
					const timestamp = lastBuildData.timestamp;
					let html = [
						lineDisplay(`N°`, `${lastBuildData.displayName}`)
					];
					if(jobData.inQueue){
						html.push(lineDisplay(`Running`, `Pending`));
						setTimeout(updateStats, 2000);
					}
					else if(lastBuildData.building){
						const duration 	= now - timestamp;
						const eta 		= (lastBuildData.estimatedDuration - duration) / 1000 | 0;
						html.push(lineDisplay(`Running`, `ETA ${eta}s`));
						building = true;
						setTimeout(updateStats, 2000);
					}
					else{
						html.push(
							lineDisplay(`Result`, `${lastBuildData.result}`),
							lineDisplay(`Took`, `${lastBuildData.duration/1000|0}s`),
							lineDisplay(`Date`, `${(new Date(timestamp)).toLocaleString()}`)
						);
						building = false;
					}

					latestDeploys.innerHTML = html.join('');

					disabled = jobData.disabled;
					disabled ? disableDeployCheckbox.setAttribute('checked', 'checked') : disableDeployCheckbox.removeAttribute('checked');

					disableDeployCheckbox.removeAttribute('disabled');


					if(previousBuilding != building){
						if(!building){
							deployButton.addEventListener('click', runDeploy);
							deployButton.removeAttribute('disabled');
						}
					}


					if(previousDisabled != disabled){
						if(!disabled){
							deployButton.addEventListener('click', runDeploy);
							deployButton.removeAttribute('disabled');
						}
						else if(!building){
							deployButton.setAttribute('disabled', true);
							deployButton.removeEventListener('click', runDeploy);
						}
					}

					previousBuilding = building;
					previousDisabled = disabled;
				}

				disableDeployCheckbox.addEventListener('change', toggleDeploy);

				updateStats();
			})()
		</script>
		<?php endif; ?>
	<?php

	}
}
add_action( 'admin_menu', 'preprod_to_prod_60fps_menu' );
?>
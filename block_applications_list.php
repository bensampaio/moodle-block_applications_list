<?php

class block_applications_list extends block_list {
    function init() {
        $this->title = get_string('pluginname', 'block_applications_list');
    }

    function get_content() {
        global $CFG, $USER, $DB, $OUTPUT;

        if($this->content !== NULL) {
            return $this->content;
        }

        $this->content = new stdClass;
		$this->content->icons = '';
        $this->content->items = array();
		$this->content->footer = '';
		
		$appLib = $CFG->dirroot . '/local/applications/lib.php';
		if(file_exists($appLib)) {
			include_once($appLib);

			if(local_applications_table_exists()) {

				$this->title = get_string('my-applications', 'local_applications');

				if (isloggedin() and !isguestuser()) {    // Just print Favorite Applications

					$this->content->items[] = 
						"<a class=\"option\" href=\"$CFG->wwwroot/local/applications/edit.php\">".
							'<img class="icon" src="'.$OUTPUT->pix_url('t/add').'" />'.
							format_string(get_string('add')).
						"</a>";

					$applications = local_applications_get_user_favorite_apps();

		            if (count($applications) > 0) {
		                foreach ($applications as $application) {
		                    $this->content->items[] = 
								"<a href=\"$CFG->wwwroot/local/applications/view.php?id=$application->id\">".
									'<img class="icon" src="'.$application->icon.'" />'.
									format_string($application->name).
								"</a>";
		                }
		            }
					else {
						$this->content->items[] = '<p class="empty">'.get_string('empty-applications', 'block_applications_list').'</p>';
					}

					$this->content->footer =
						"<a href=\"$CFG->wwwroot/local/applications/index.php\">".
							format_string(get_string('all-applications', 'block_applications_list')).
						"</a>";
		        }
			}
			else {
				$this->content->items[] = local_applications_print_table_error('notifytiny');
			}
		}
		else {
			$url = 'https://moodle.org/plugins/view.php?plugin=local_applications';
			$this->content->items[] =
				html_writer::start_tag('div', array('class' => "notifyproblem notifytiny")).
					get_string('plugin-missing', 'block_applications_list')." <a href=\"$url\">$url</a>".
				html_writer::end_tag('div');
		}
		
        return $this->content;
    }
}


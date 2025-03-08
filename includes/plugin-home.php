<?php
function vpg_admin_page() {
    ?>
    <div class="wrapper">
        <h1 class="vpg-admin-heading mb-3">Virtual Page Generator</h1>

        <form method="post" enctype="multipart/form-data">
            <div class="vpg-form-wrapper">
                <h3>Upload CSV File</h3>
                <input type="file" name="csv_file" accept=".csv" required>

                <h3 class="mt-3">Select Template</h3>
                <select name="vpg_template_page" required>
                    <?php
                    $templates = get_posts(array('post_type' => 'vpg_template', 'numberposts' => -1));
                    foreach ($templates as $template) {
                        echo '<option value="' . esc_attr($template->ID) . '">' . esc_html($template->post_title) . '</option>';
                    }
                    ?>
                </select>

                <?php submit_button('Upload and Save'); ?>
            </div>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
            vpg_handle_csv_upload($_FILES['csv_file']);
        }
        ?>

        <div class="vpg-note">
            <h2 class="vpg-visit-sitemap">For visiting the sitemap: <a target="_blank" href="<?php echo get_site_url(); ?>/vpg-sitemap.xml"><?php echo get_site_url(); ?>/vpg-sitemap.xml</a></h2>
            <div class="vpg-how-to-use">
                <h4>How to use:</h4>
                <ul>
                    <li>1. Create VPG templates post and put the placeholders to it. e.g. [vpg_hero_title]</li>
                    <li>2. Upload your CSV file, select the template, and save.</li>
                </ul>
            </div>    
        </div>
        
    </div>
    <?php
}

// Handle CSV Upload
function vpg_handle_csv_upload($file) {
    if ($file['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $file['tmp_name'];
        $csv_data = array_map('str_getcsv', file($file_tmp));
        array_shift($csv_data);  // Remove the header row

        // Get selected template from the form
        if (!empty($_POST['vpg_template_page'])) {
            $selected_template = intval($_POST['vpg_template_page']);
        } else {
            echo '<div class="error notice"><p>Please select a template.</p></div>';
            return;
        }

        // Save CSV data and template association
        $existing_data = get_option('vpg_csv_template_data', []);
        $new_entry = [
            'template_id' => $selected_template,
            'csv_data' => $csv_data
        ];
        $existing_data[] = $new_entry;
        update_option('vpg_csv_template_data', $existing_data);

        // Generate the sitemap
        vpg_generate_sitemap($existing_data);

        echo '<div class="updated notice"><p>Sitemap has been updated successfully!</p></div>';
    } else {
        echo '<div class="error notice"><p>Failed to upload the CSV file. Please try again.</p></div>';
    }
}




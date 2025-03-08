<?php
add_action('before_delete_post', 'vpg_handle_deleted_template');

function vpg_handle_deleted_template($post_id) {
    $csv_template_data = get_option('vpg_csv_template_data', []);
    $updated_csv_data = [];
    $template_removed = false;

    // Check and remove data related to the deleted template
    foreach ($csv_template_data as $index => $entry) {
        if ($entry['template_id'] == $post_id) {
            unset($csv_template_data[$index]);
            $template_removed = true;
            error_log('Deleted template data for template ID: ' . $post_id);

            // Remove related URLs from the sitemap
            vpg_remove_urls_from_sitemap($entry['csv_data']);
        } else {
            $updated_csv_data[] = $entry;
        }
    }

    // If a template was removed, update the vpg_csv_template_data option
    if ($template_removed) {
        update_option('vpg_csv_template_data', $updated_csv_data);
        vpg_regenerate_sitemap($updated_csv_data);
    }
}

function vpg_remove_urls_from_sitemap($csv_data) {
    $sitemap_file = ABSPATH . 'vpg-sitemap.xml';

    if (file_exists($sitemap_file)) {
        $sitemap_content = file_get_contents($sitemap_file);

        foreach ($csv_data as $row) {
            if (!empty($row[1])) {
                $url_to_remove = '<loc>' . home_url($row[1]) . '</loc>';
                $sitemap_content = str_replace($url_to_remove, '', $sitemap_content);
                error_log('Removed URL from sitemap: ' . $url_to_remove);
            }
        }

        // Save the updated sitemap after cleaning
        file_put_contents($sitemap_file, $sitemap_content);
    }
}

function vpg_regenerate_sitemap($csv_template_data) {
    require_once 'sitemap-generator.php';
    vpg_generate_sitemap($csv_template_data);
}

function vpg_is_elementor_preview() {
    if (isset($_GET['elementor-preview'])) {
        return true;
    }
    if (defined('ELEMENTOR_VERSION') && \Elementor\Plugin::$instance->preview->is_preview_mode()) {
        return true;
    }
    return false;
}




use Elementor\Plugin as ElementorPlugin;

function vpg_virtual_page_handler() {
    if (is_admin() || vpg_is_elementor_preview()) {
        return; // Don't hijack Elementor preview requests
    }

    $slug = get_query_var('vpg_virtual_page');
    if (empty($slug)) return;

    if (get_page_by_path($slug) || term_exists($slug) || post_type_exists($slug)) {
        return;
    }

    $csv_template_data = get_option('vpg_csv_template_data', []);

    foreach ($csv_template_data as $entry) {
        $csv_data = $entry['csv_data'];
        $template_id = $entry['template_id'];

        foreach ($csv_data as $row) {
            if (!empty($row[1]) && $slug === $row[1]) {

                $template_post = get_post($template_id);
                if (!$template_post) return;

                $group = isset($row[48]) ? esc_html($row[48]) : ''; // Assuming column 48 is the 'Group'
                $similar_pages = vpg_get_similar_pages($csv_data, $group, $slug);

                $placeholders = array_merge(
                    ['[vpg_hero_heading]', '[vpg_hero_subheading]', '[vpg_hero_cta]', '[vpg_hero_img_url]',
                    '[vpg_challenge_title]',
                    '[vpg_challenge_1_title]', '[vpg_challenge_1_des]', '[vpg_challenge_2_title]', '[vpg_challenge_2_des]', '[vpg_challenge_3_title]', '[vpg_challenge_3_des]',
                    '[vpg_usecase_heading]',
                    '[vpg_usecase_1_heading]', '[vpg_usecase_1_content]', '[vpg_usecase_1_img_url]',
                    '[vpg_usecase_2_heading]', '[vpg_usecase_2_content]', '[vpg_usecase_2_img_url]',
                    '[vpg_usecase_3_heading]', '[vpg_usecase_3_content]', '[vpg_usecase_3_img_url]',
                    '[vpg_usecase_4_heading]', '[vpg_usecase_4_content]', '[vpg_usecase_4_img_url]',
                    '[vpg_usecase_5_heading]', '[vpg_usecase_5_content]', '[vpg_usecase_5_img_url]',
                    '[vpg_usecase_cta]',
                    '[vpg_integration_heading]', '[vpg_integration_cta]',
                    '[vpg_faq_heading]',
                    '[vpg_faq_1_title]', '[vpg_faq_1_des]',
                    '[vpg_faq_2_title]', '[vpg_faq_2_des]',
                    '[vpg_faq_3_title]', '[vpg_faq_3_des]',
                    '[vpg_faq_4_title]', '[vpg_faq_4_des]',
                    '[vpg_faq_5_title]', '[vpg_faq_5_des]',
                    '[vpg_faq_6_title]', '[vpg_faq_6_des]',
                    '[vpg_faq_cta]',
                    '[vpg_group]'
                ],
                    vpg_generate_similar_page_placeholders()
                );

                $values = array_merge(
                    [
                        isset($row[4]) ? esc_html($row[4]) : '',
                        isset($row[5]) ? esc_html($row[5]) : '',
                        isset($row[6]) ? esc_html($row[6]) : '',
                        isset($row[7]) ? esc_html($row[7]) : '',
        
                        isset($row[8]) ? esc_html($row[8]) : '',
    
                        isset($row[9]) ? esc_html($row[9]) : '',
                        isset($row[10]) ? esc_html($row[10]) : '',
                        isset($row[11]) ? esc_html($row[11]) : '',
                        isset($row[12]) ? esc_html($row[12]) : '',
                        isset($row[13]) ? esc_html($row[13]) : '',
                        isset($row[14]) ? esc_html($row[14]) : '',
        
                        isset($row[15]) ? esc_html($row[15]) : '',
    
                        isset($row[16]) ? esc_html($row[16]) : '',
                        isset($row[17]) ? esc_html($row[17]) : '',
                        isset($row[18]) ? esc_html($row[18]) : '',
    
                        isset($row[19]) ? esc_html($row[19]) : '',
                        isset($row[20]) ? esc_html($row[20]) : '',
                        isset($row[21]) ? esc_html($row[21]) : '',
    
                        isset($row[22]) ? esc_html($row[22]) : '',
                        isset($row[23]) ? esc_html($row[23]) : '',
                        isset($row[24]) ? esc_html($row[24]) : '',
    
                        isset($row[25]) ? esc_html($row[25]) : '',
                        isset($row[26]) ? esc_html($row[26]) : '',
                        isset($row[27]) ? esc_html($row[27]) : '',
    
                        isset($row[28]) ? esc_html($row[28]) : '',
                        isset($row[29]) ? esc_html($row[29]) : '',
                        isset($row[30]) ? esc_html($row[30]) : '',
    
                        isset($row[31]) ? esc_html($row[31]) : '',
    
                        isset($row[32]) ? esc_html($row[32]) : '',
                        isset($row[33]) ? esc_html($row[33]) : '',
    
                        isset($row[34]) ? esc_html($row[34]) : '',
    
                        isset($row[35]) ? esc_html($row[35]) : '',
                        isset($row[36]) ? esc_html($row[36]) : '',
    
                        isset($row[37]) ? esc_html($row[37]) : '',
                        isset($row[38]) ? esc_html($row[38]) : '',
    
                        isset($row[39]) ? esc_html($row[39]) : '',
                        isset($row[40]) ? esc_html($row[40]) : '',
    
                        isset($row[41]) ? esc_html($row[41]) : '',
                        isset($row[42]) ? esc_html($row[42]) : '',
    
                        isset($row[43]) ? esc_html($row[43]) : '',
                        isset($row[44]) ? esc_html($row[44]) : '',
    
                        isset($row[45]) ? esc_html($row[45]) : '',
                        isset($row[46]) ? esc_html($row[46]) : '',
    
                        isset($row[47]) ? esc_html($row[47]) : '',
    
                        isset($row[48]) ? esc_html($row[48]) : ''
                    ],
                    vpg_generate_similar_page_values($similar_pages)
                );

                // $content = str_replace($placeholders, $values, $template_post->post_content);

                $template_content = str_replace($placeholders, $values, $template_post->post_content);

                if (did_action('elementor/loaded') && ElementorPlugin::instance()->documents->get($template_post->ID)->is_built_with_elementor()) {
                    $content = ElementorPlugin::instance()->frontend->get_builder_content_for_display($template_post->ID);
                } else {
                    $content = apply_filters('the_content', $template_content); // Use processed content with placeholders replaced
                }

                $vpg_meta_title = isset($row[2]) ? esc_html($row[2]) : '';
                $vpg_meta_description = isset($row[3]) ? esc_html($row[3]) : '';

                add_action('wp_head', function () use ($vpg_meta_title, $vpg_meta_description) {
                    echo '<meta name="title" content="' . $vpg_meta_title . '">' . "\n";
                    echo '<meta name="description" content="' . $vpg_meta_description . '">' . "\n";
                });

                add_action('wp_enqueue_scripts', 'vpg_enqueue_scripts');

                status_header(200);
                get_header(); 

                // echo apply_filters('the_content', $content);
                echo $content;

                get_footer();

                exit;
            }
        }
    }
}
add_action('template_redirect', 'vpg_virtual_page_handler');


function vpg_get_similar_pages($csv_data, $group, $current_slug) {
    $similar_pages = array_filter($csv_data, function ($row) use ($group, $current_slug) {
        return isset($row[48]) && $row[48] === $group && $row[1] !== $current_slug; // Group match and not the current page
    });

    $similar_pages = array_values($similar_pages);

    if (count($similar_pages) > 3) {
        $random_keys = array_rand($similar_pages, 3);
        $selected_pages = [];
        foreach ((array) $random_keys as $key) {
            $selected_pages[] = $similar_pages[$key];
        }
        return $selected_pages;
    }

    return $similar_pages;
}


function vpg_generate_similar_page_placeholders() {
    $placeholders = [];
    for ($i = 1; $i <= 3; $i++) {
        $placeholders[] = "[similar_page_{$i}_title]";
        $placeholders[] = "[similar_page_{$i}_hero_img_url]";
        $placeholders[] = "[similar_page_{$i}_url]";
    }
    return $placeholders;
}

function vpg_generate_similar_page_values($similar_pages) {
    $values = [];
    for ($i = 0; $i < 3; $i++) {
        if (isset($similar_pages[$i])) {
            $values[] = esc_html($similar_pages[$i][4]);  // Title
            $values[] = esc_url($similar_pages[$i][7]);   // Hero image URL
            $values[] = esc_url(home_url($similar_pages[$i][1])); // Page URL
        } else {
            $values[] = ''; // Empty if less than 3 similar pages
            $values[] = '';
            $values[] = '';
        }
    }
    return $values;
}

add_filter('elementor/frontend/the_content', 'vpg_replace_placeholders', 10, 2);

function vpg_replace_placeholders($content) {
    // Only replace placeholders if we are on a virtual page
    $slug = get_query_var('vpg_virtual_page');
    if (empty($slug)) {
        return $content;
    }

    // Get the CSV template data
    $csv_template_data = get_option('vpg_csv_template_data', []);

    foreach ($csv_template_data as $entry) {
        $csv_data = $entry['csv_data'];

        foreach ($csv_data as $row) {
            if (!empty($row[1]) && $slug === $row[1]) {
                // Define all placeholders and their corresponding values


                $group = isset($row[48]) ? esc_html($row[48]) : ''; // Assuming column 48 is the 'Group'
                $similar_pages = vpg_get_similar_pages($csv_data, $group, $slug);

                $placeholders = array_merge(
                    ['[vpg_hero_heading]', '[vpg_hero_subheading]', '[vpg_hero_cta]', '[vpg_hero_img_url]',
                    '[vpg_challenge_title]',
                    '[vpg_challenge_1_title]', '[vpg_challenge_1_des]', '[vpg_challenge_2_title]', '[vpg_challenge_2_des]', '[vpg_challenge_3_title]', '[vpg_challenge_3_des]',
                    '[vpg_usecase_heading]',
                    '[vpg_usecase_1_heading]', '[vpg_usecase_1_content]', '[vpg_usecase_1_img_url]',
                    '[vpg_usecase_2_heading]', '[vpg_usecase_2_content]', '[vpg_usecase_2_img_url]',
                    '[vpg_usecase_3_heading]', '[vpg_usecase_3_content]', '[vpg_usecase_3_img_url]',
                    '[vpg_usecase_4_heading]', '[vpg_usecase_4_content]', '[vpg_usecase_4_img_url]',
                    '[vpg_usecase_5_heading]', '[vpg_usecase_5_content]', '[vpg_usecase_5_img_url]',
                    '[vpg_usecase_cta]',
                    '[vpg_integration_heading]', '[vpg_integration_cta]',
                    '[vpg_faq_heading]',
                    '[vpg_faq_1_title]', '[vpg_faq_1_des]',
                    '[vpg_faq_2_title]', '[vpg_faq_2_des]',
                    '[vpg_faq_3_title]', '[vpg_faq_3_des]',
                    '[vpg_faq_4_title]', '[vpg_faq_4_des]',
                    '[vpg_faq_5_title]', '[vpg_faq_5_des]',
                    '[vpg_faq_6_title]', '[vpg_faq_6_des]',
                    '[vpg_faq_cta]',
                    '[vpg_group]'
                ],
                    vpg_generate_similar_page_placeholders()
                );

                $values = array_merge(
                    [
                        isset($row[4]) ? esc_html($row[4]) : '',
                        isset($row[5]) ? esc_html($row[5]) : '',
                        isset($row[6]) ? esc_html($row[6]) : '',
                        isset($row[7]) ? esc_html($row[7]) : '',
        
                        isset($row[8]) ? esc_html($row[8]) : '',
    
                        isset($row[9]) ? esc_html($row[9]) : '',
                        isset($row[10]) ? esc_html($row[10]) : '',
                        isset($row[11]) ? esc_html($row[11]) : '',
                        isset($row[12]) ? esc_html($row[12]) : '',
                        isset($row[13]) ? esc_html($row[13]) : '',
                        isset($row[14]) ? esc_html($row[14]) : '',
        
                        isset($row[15]) ? esc_html($row[15]) : '',
    
                        isset($row[16]) ? esc_html($row[16]) : '',
                        isset($row[17]) ? esc_html($row[17]) : '',
                        isset($row[18]) ? esc_html($row[18]) : '',
    
                        isset($row[19]) ? esc_html($row[19]) : '',
                        isset($row[20]) ? esc_html($row[20]) : '',
                        isset($row[21]) ? esc_html($row[21]) : '',
    
                        isset($row[22]) ? esc_html($row[22]) : '',
                        isset($row[23]) ? esc_html($row[23]) : '',
                        isset($row[24]) ? esc_html($row[24]) : '',
    
                        isset($row[25]) ? esc_html($row[25]) : '',
                        isset($row[26]) ? esc_html($row[26]) : '',
                        isset($row[27]) ? esc_html($row[27]) : '',
    
                        isset($row[28]) ? esc_html($row[28]) : '',
                        isset($row[29]) ? esc_html($row[29]) : '',
                        isset($row[30]) ? esc_html($row[30]) : '',
    
                        isset($row[31]) ? esc_html($row[31]) : '',
    
                        isset($row[32]) ? esc_html($row[32]) : '',
                        isset($row[33]) ? esc_html($row[33]) : '',
    
                        isset($row[34]) ? esc_html($row[34]) : '',
    
                        isset($row[35]) ? esc_html($row[35]) : '',
                        isset($row[36]) ? esc_html($row[36]) : '',
    
                        isset($row[37]) ? esc_html($row[37]) : '',
                        isset($row[38]) ? esc_html($row[38]) : '',
    
                        isset($row[39]) ? esc_html($row[39]) : '',
                        isset($row[40]) ? esc_html($row[40]) : '',
    
                        isset($row[41]) ? esc_html($row[41]) : '',
                        isset($row[42]) ? esc_html($row[42]) : '',
    
                        isset($row[43]) ? esc_html($row[43]) : '',
                        isset($row[44]) ? esc_html($row[44]) : '',
    
                        isset($row[45]) ? esc_html($row[45]) : '',
                        isset($row[46]) ? esc_html($row[46]) : '',
    
                        isset($row[47]) ? esc_html($row[47]) : '',
    
                        isset($row[48]) ? esc_html($row[48]) : ''
                    ],
                    vpg_generate_similar_page_values($similar_pages)
                );

                // Replace placeholders in the Elementor content
                $content = str_replace($placeholders, $values, $content);
                break;
            }
        }
    }

    return $content;
}


add_action('template_redirect', 'vpg_render_virtual_page');
function vpg_render_virtual_page() {
    if (get_query_var('vpg_virtual_page')) {
        $slug = get_query_var('vpg_virtual_page');

        // Check if the slug already exists (as a page, post, or taxonomy)
        if (post_type_exists($slug) || term_exists($slug) || get_page_by_path($slug)) {
            return; // Exit to avoid conflicts
        }

        // Render your virtual page here
        echo "This is a virtual page for: " . esc_html($slug);
        exit;
    }
}

// function vpg_add_rewrite_rules() {
//     add_rewrite_rule('^([^/]+)/?$', 'index.php?vpg_virtual_page=$matches[1]', 'top');
// }
// add_action('init', 'vpg_add_rewrite_rules');

function vpg_get_all_virtual_slugs() {
    $virtual_slugs = [];
    $csv_template_data = get_option('vpg_csv_template_data', []);

    foreach ($csv_template_data as $entry) {
        $csv_data = $entry['csv_data'];

        foreach ($csv_data as $row) {
            if (!empty($row[1])) {  // Assuming the first column in each row contains the slug
                $virtual_slugs[] = sanitize_title($row[1]);
            }
        }
    }

    return array_unique($virtual_slugs);  // Return only unique slugs to avoid duplicate rewrite rules
}



function vpg_add_rewrite_rules() {
    $virtual_slugs = vpg_get_all_virtual_slugs();
    foreach ($virtual_slugs as $slug) {
        add_rewrite_rule("^{$slug}/?$", 'index.php?vpg_virtual_page=' . $slug, 'top');
    }
}
add_action('init', 'vpg_add_rewrite_rules');


function vpg_add_query_vars($vars) {
    $vars[] = 'vpg_virtual_page';
    return $vars;
}
add_filter('query_vars', 'vpg_add_query_vars');
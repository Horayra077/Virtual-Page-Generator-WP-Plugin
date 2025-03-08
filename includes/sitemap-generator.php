<?php
function vpg_add_sitemap_to_robots() {
    $sitemap_url = get_site_url() . '/vpg-sitemap.xml';
    $robots_file = ABSPATH . 'robots.txt';

    // Check if robots.txt exists; if not, create it
    if (!file_exists($robots_file)) {
        $robots_content = "User-agent: *\nDisallow:\n";
        file_put_contents($robots_file, $robots_content);
        error_log('Created robots.txt file');
    }

    // Add Sitemap entry to robots.txt if not already present
    $existing_content = file_get_contents($robots_file);
    if (strpos($existing_content, $sitemap_url) === false) {
        file_put_contents($robots_file, $existing_content . "\nSitemap: " . esc_url($sitemap_url) . "\n");
        error_log('Added sitemap to robots.txt: ' . $sitemap_url);
    }
}

function vpg_generate_sitemap($csv_template_data) {
    $sitemap_file = ABSPATH . 'vpg-sitemap.xml'; // Save in the root directory

    // Remove the old sitemap if it exists
    if (file_exists($sitemap_file)) {
        unlink($sitemap_file);
        error_log('Deleted old sitemap');
    }

    // Create a new XML sitemap
    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');
    $site_url = get_site_url();

    foreach ($csv_template_data as $entry) {
        foreach ($entry['csv_data'] as $row) {
            $slug = isset($row[1]) ? sanitize_title($row[1]) : '';
            if (!empty($slug)) {
                $page_url = trailingslashit($site_url) . $slug;

                $url = $xml->addChild('url');
                $url->addChild('loc', esc_url($page_url));
                $url->addChild('lastmod', date('Y-m-d'));
                $url->addChild('changefreq', 'weekly');
                $url->addChild('priority', '0.8');
            }
        }
    }

    // Save the XML file
    $xml->asXML($sitemap_file);
    error_log('Generated new sitemap: ' . $sitemap_file);

    // Ensure the sitemap is added to robots.txt
    vpg_add_sitemap_to_robots();

    return $sitemap_file;
}

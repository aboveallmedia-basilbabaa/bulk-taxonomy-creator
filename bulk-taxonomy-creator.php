<?php
/**
 * Plugin Name: Bulk Taxonomy Creator
 * Description: Allows you to create post categories and tags in bulk.
 * Version: 1.3
 * Author: Above All Media
 */

// Hook to add the bulk category and tag creation form to the settings menu
add_action('admin_menu', 'bulk_taxonomy_creator_menu');

function bulk_taxonomy_creator_menu() {
    add_options_page(
        'Bulk Taxonomy Creator',
        'Bulk Taxonomy Creator',
        'manage_options',
        'bulk-taxonomy-creator',
        'bulk_taxonomy_creator_form'
    );
}

// Function to display the bulk category and tag creation form
function bulk_taxonomy_creator_form() {
    if (isset($_POST['submit'])) {
        $categories = sanitize_text_field($_POST['categories']);
        $tags = sanitize_text_field($_POST['tags']);

        // Split input by comma and create categories
        $category_ids = bulk_create_terms(explode(',', $categories), 'category');

        // Split input by comma and create tags
        $tag_ids = bulk_create_terms(explode(',', $tags), 'post_tag');

        // Display success message
        echo '<div class="updated"><p>Categories and Tags created successfully.</p></div>';
    }
    ?>
    <style>
        /* CSS to increase the input field size */
        #categories,
        #tags {
            width: 50%;
            height: 100px; /* Adjust the height as needed */
        }
    </style>

    <div class="wrap">
        <h2>Bulk Taxonomy Creator</h2>
        <form method="post" action="">
            <label for="categories">Categories (comma-separated):</label><br>
            <textarea id="categories" name="categories" rows="4"></textarea><br><br>

            <label for="tags">Tags (comma-separated):</label><br>
            <textarea id="tags" name="tags" rows="4"></textarea><br><br>

            <input type="submit" name="submit" class="button button-primary" value="Create Categories and Tags">
        </form>
    </div>
    <?php
}

// Function to create terms (categories or tags) in bulk
function bulk_create_terms($terms, $taxonomy) {
    $term_ids = array();

    foreach ($terms as $term_name) {
        $term = wp_insert_term($term_name, $taxonomy);
        if (!is_wp_error($term)) {
            $term_ids[] = $term['term_id'];
        }
    }

    return $term_ids;
}

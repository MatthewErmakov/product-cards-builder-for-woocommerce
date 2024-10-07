<?php 

namespace PCCW\App\Includes\Services;

use PCCW\App\Kernel;

class TaxonomyDataHandlerService {

    public function __construct(Kernel $plugin, int $product_id, array $atts) {
        $this->plugin = $plugin;
        $this->product_id = $product_id;

        $this->taxonomy = !empty($atts['taxonomy']) ? $atts['taxonomy'] : '';
        $this->links = !empty($atts['links']) && $atts['links'] === 'true';
        $this->limit = !empty($atts['limit']) ? $atts['limit'] : -1;
        $this->order = !empty($atts['order']) ? $atts['order'] : 'ASC';
        $this->orderby = !empty($atts['orderby']) ? $atts['orderby'] : 'name';
        $this->count = !empty($atts['count']) && $atts['count'] === 'true';
        $this->links_target = !empty($atts['links_target']) ? $atts['links_target'] : '_self';

        $this->data = [];
    }

    public function request(): array
    {
        $taxonomy = $this->taxonomy;

        $terms = get_the_terms($this->product_id, $taxonomy);

        if (is_wp_error($terms) || empty($terms)) {
            return [
                'error' => [
                    'status' => 'Not found',
                    'message' => 'Terms list is empty'
                ]
            ];
        }

        $filtered_array = [];

        foreach ($terms as $key => $term) {
            if ( $term instanceof \WP_Term ) {
                $filtered_array[$key] = [
                    'slug' => $term->slug,
                    'name' => $term->name,
                    'count' => $this->get_term_products_count($term->slug),
                ];
    
                if ($this->links) {
                    /**
                     * Added additional checking for the terms and have parents
                     * but this parent couldn't exist
                     */
                    if ( $term->parent === 0 || ! ( get_term_link( $term->parent ) instanceof \WP_Error ) ) {
                        $filtered_array[$key]['link'] = get_term_link($term->term_id, $taxonomy);
                    }
                }    
            }  
        }

        // apply sorting
        $filtered_array = $this->apply_sorting( $filtered_array );

        // apply limit
        if ( $this->limit > -1 ) {
            $filtered_array = array_slice( $filtered_array, 0, $this->limit );
        }

        $this->data = $filtered_array;

        return $filtered_array;
    }

    /**
     * Apply sorting for the array
     * of terms
     * 
     * @param array $terms
     * @return array $terms
     */
    private function apply_sorting(array $terms): array
    {
        if ( ! empty( $terms ) ) {
            usort($terms, function ($a, $b) {
                if ($this->orderby === 'name') {
                    return strcasecmp($a['name'], $b['name']);
                } elseif ($this->orderby === 'slug') {
                    return strcasecmp($a['slug'], $b['slug']);
                } elseif ($this->orderby === 'count') {
                    return $a['count'] <=> $b['count'];
                } else {
                    return 0;
                }
            });    
        }

        if ($this->order === 'DESC') {
            $terms = array_reverse($terms);
        }

        return $terms;
    }

    /**
     * NOTE: the function is not used
     * but in plans I have to implement
     * hierarchical output of the terms
     * so gonna leave this function for future changes
     * 
     * @param int $term_id
     * @param string $taxonomy
     * 
     * @return array $parent_terms
     */
    public function get_parent_terms($term_id, $taxonomy) {
        $parent_terms = [];

        while ($parent_id = wp_get_term_taxonomy_parent_id($term_id, $taxonomy)) {
            $parent_term = get_term_by('id', $parent_id, $taxonomy);
            $parent_terms[] = $parent_term;
            $term_id = $parent_id;
        }

        return array_reverse($parent_terms);
    }

    public function get_term_products_count( string $term_slug ): int
    {
        $taxonomy_name = $this->taxonomy;
        $term = get_term_by( 'slug', $term_slug, $taxonomy_name );

        return $term->count;
    }

    /**
     * NOTE: the function is not used
     * but in plans I have to implement
     * hierarchical output of the terms
     * so gonna leave this function for future changes
     * 
     * @param array &$categories
     * @param array &$hierarchy
     * 
     * @return void
     */
    private function get_product_taxonomy_terms_hierarchy( &$categories, &$hierarchy): void {
        foreach ( $categories as $category ) {
            $hierarchy[$category->term_id] = [
                'name' => $category->name,
                'slug' => $category->slug,
                'parent' => $category->parent,
                'count' => $this->get_term_products_count( $category->slug ),
                'children' => array()
            ];

            if ( $this->links ) {
                $hierarchy[$category->term_id]['link'] = get_term_link($category);
            }
        }
    
        $result = array();
        foreach ( $hierarchy as $term_id => $term ) {
            if ($term['parent'] == 0) {
                $result[$term_id] = $term;
            } else {
                if ( $this->display_parents ) {
                    $hierarchy[$term['parent']]['children'][$term_id] = $term;
                }
            }
        }
    
        $this->build_recursive_hierarchy($result, $hierarchy);
    }
    
    /**
     * NOTE: the function is not used
     * but in plans I have to implement
     * hierarchical output of the terms
     * so gonna leave this function for future changes
     * 
     * @param array &$result
     * @param array &$hierarchy
     * 
     * @return array $result
     */
    private function build_recursive_hierarchy(&$result, &$hierarchy) {
        foreach ($result as $key => &$category) {
            if (isset($hierarchy[$key]['children']) && !empty($hierarchy[$key]['children'])) {
                $category['children'] = $this->build_recursive_hierarchy($hierarchy[$key]['children'], $hierarchy);
            }
        }
        return $result;
    }

    /**
     * NOTE: the function is not used
     * but in plans I have to implement
     * hierarchical output of the terms
     * so gonna leave this function for future changes
     * 
     * @param array &$array
     * 
     * @return void
     */
    private function remove_empty_children_recursive( array &$array ): void {
        foreach ( $array as $key => &$item ) {
            if ( isset($array[$key]['children']) && count( $array[$key]['children'] ) === 0 ) {
                unset( $array[$key]['children'] );
            }

            if ( is_array( $item ) ) {
                $this->remove_empty_children_recursive($item);
            }
        }
    }
}
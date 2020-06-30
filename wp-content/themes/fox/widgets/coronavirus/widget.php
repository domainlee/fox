<?php
extract( $args );
extract( wp_parse_args( $instance, array(
    'title' => '',
    'big_number_display' => 'all',
    'country' => '',
    'state' => '',
    'table_display'  => 'all',
    'cache_time' => '2',
) ) );
echo $before_widget;

$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
if ( !empty( $title ) ) {	
    echo $before_title . $title . $after_title;
}

// 2 hours cache time
$cache_time = floatval( $cache_time ) * HOUR_IN_SECONDS;
if ( $cache_time < 0 ) {
    $cache_time = HOUR_IN_SECONDS;
}

/**
 * 01 - BIG NUMBERS
 */
$endpoint = '';
switch( $big_number_display ) {
        
    case 'all' :
        $endpoint = 'all';
        break;
        
    case 'Europe':
    case 'Asia':
    case 'Africa':
    case 'Oceania':
    case 'North America':
    case 'South America':
        
        $endpoint = 'continents/' . $big_number_display;
        break;
        
    case 'country' :
        
        $endpoint = 'countries/' . rawurlencode( $country );
        break;
        
    case 'state' :
        
        $endpoint = 'states/' . rawurlencode( $state );
        break;
        
    default :
        break;
}

$big_json = '';
if ( $endpoint ) {
    
    $big_url = 'https://ev3klr6bchdcdowp.disease.sh/v2/' . $endpoint;
    
    $key = sanitize_title_with_dashes( 'fox-coronavirus-big-numbers-' . $endpoint );
    $body = get_transient( $key );
    
    if ( false === $body ) {
        $response = wp_remote_get( $big_url, array(
            'user-agent' => 'Coronavirus/1.0.0'
        ));
        if ( ! is_wp_error( $response ) ) {
            
            $body = wp_remote_retrieve_body( $response );
            $big_json = json_decode( $body );
            
            if ( ! empty( $big_json ) ) {
                set_transient( $key , $body, $cache_time );
            }
            
        }
    } else {
        
        $big_json = json_decode( $body );
        
    }
    
}

/**
 * 02 - SMALL TABLE DATA
 */
$endpoint = '';
$name_label = esc_html__( 'Area', 'wi' );
switch( $table_display ) {
        
    case 'all' :
        $endpoint  = 'countries';
        $table_key = 'country';
        $name_label = esc_html__( 'Country', 'wi' );
        break;
        
    case 'continents' :
        $endpoint = 'continents';
        $table_key = 'continent';
        $name_label = esc_html__( 'Continent', 'wi' );
        break;
        
    case 'states' :
        $endpoint = 'states';
        $table_key = 'state';
        $name_label = esc_html__( 'State', 'wi' );
        break;
        
    default :
        break;
}
if ( $endpoint ) {
    
    $url = 'https://ev3klr6bchdcdowp.disease.sh/v2/' . $endpoint;
    
    $key = sanitize_title_with_dashes( 'fox-coronavirus-table-' . $endpoint );
    $body = get_transient( $key );

    if ( false === $body ) {
        
        $response = wp_remote_get( $url, array(
            'user-agent' => 'Coronavirus/1.0.0'
        ));
        
        if ( ! is_wp_error( $response ) ) {
            
            $body = wp_remote_retrieve_body( $response );
            $table_json = json_decode( $body );
            
            if ( ! empty( $table_json ) ) {
                set_transient( $key , $body, $cache_time );
            }
            
        }
    } else {
        
        $table_json = json_decode( $body );
        
    }
    
    if ( ! empty( $table_json ) ) {
        usort( $table_json, 'fox_helper_corona_sort_by_cases' );
    }
    
}
?>

<div class="fox-coronavirus">

    <div class="coronavirus-inner">
    
        <?php if ( ! empty( $big_json ) && isset( $big_json->cases ) ) { ?>
        
        <div class="coronavirus-big-numbers">
        
            <div class="number-cases big-number">
                <span class="num" title="<?php echo esc_attr( $big_json->cases ); ?>"><?php echo fox_number( $big_json->cases, 2 ); ?></span>
                <span class="num-today"><?php echo '+' . fox_number( $big_json->todayCases ); ?> <?php echo esc_html__( 'today', 'wi' ); ?></span>
                <span class="num-label"><?php echo esc_html__( 'confirmed', 'wi' ); ?></span>
            </div>
            <div class="number-deaths big-number">
                <span class="num"><?php echo fox_number( $big_json->deaths, 2 ); ?></span>
                <span class="num-today"><?php echo '+' . fox_number( $big_json->todayDeaths ); ?> <?php echo esc_html__( 'today', 'wi' ); ?></span>
                <span class="num-label"><?php echo esc_html__( 'death', 'wi' ); ?></span>
            </div>
        
        </div><!-- .big-numbers -->
        
        <?php } ?>
        
        <?php if ( ! empty( $table_json ) ) { ?>
        
        <div class="coronavirus-table-outer">
            
            <div class="t-row-th">
                <div class="th th-name"><?php echo $name_label; ?></div>
                <div class="th th-case"><?php echo esc_html__( 'Cases' ,'wi' ); ?></div>
                <div class="th th-death"><?php echo esc_html__( 'Deaths' ,'wi' ); ?></div>
            </div>

            <div class="coronavirus-table-wrapper">

                <div class="coronavirus-table-container">

                    <div class="coronavirus-table">

                        <?php foreach ( $table_json as $row_json ) {
                        ?>

                        <div class="t-row">
                            <div class="td td-name"><?php echo $row_json->{$table_key}; ?></div>
                            <div class="td td-case"><?php echo fox_number( $row_json->cases ); ?></div>
                            <div class="td td-death"><?php echo fox_number( $row_json->deaths ); ?></div>
                        </div>

                        <?php } ?>

                    </div><!-- .coronavirus-table -->

                </div><!-- .coronavirus-table-container -->

            </div><!-- .coronavirus-table-wrapper -->
            
        </div><!-- .coronavirus-table-outer -->
        
        <?php } ?>
        
        <div class="coronavirus-source">
            
            <span><?php printf( esc_html__( 'Source: %s', 'wi' ), '<a href="https://github.com/CSSEGISandData/COVID-19" target="_blank">Johns Hopkins University</a>, <a href="https://github.com/nytimes/covid-19-data" target="_blank">New York Times</a>, <a href="https://www.worldometers.info/coronavirus/" target="_blank">Worldometers</a>' ); ?></span>
            
        </div>
        
    </div><!-- .coronavirus-inner -->

</div><!-- .fox-coronavirus -->

<?php echo $after_widget;
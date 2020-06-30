( function( $ ) {
	
    $( window ).on( 'elementor/frontend/init', function() {
        //hook name is 'frontend/element_ready/{widget-name}.{skin} - i dont know how skins work yet, so for now presume it will
        //always be 'default', so for example 'frontend/element_ready/slick-slider.default'
        //$scope is a jquery wrapped parent element
        
        elementorFrontend.hooks.addAction( 'frontend/element_ready/post-masonry.default', function( $scope, $ ) {
            WITHEMES.reInit();
        });
        
        elementorFrontend.hooks.addAction( 'frontend/element_ready/post-standard.default', function( $scope, $ ) {
            WITHEMES.reInit();
        });
        
        elementorFrontend.hooks.addAction( 'frontend/element_ready/post-newspaper.default', function( $scope, $ ) {
            WITHEMES.reInit();
        });
        
        elementorFrontend.hooks.addAction( 'frontend/element_ready/post-slider.default', function( $scope, $ ) {
            WITHEMES.reInit();
        });
        
        elementorFrontend.hooks.addAction( 'frontend/element_ready/post-group-1.default', function( $scope, $ ) {
            WITHEMES.reInit();
        });
        
        elementorFrontend.hooks.addAction( 'frontend/element_ready/post-group-2.default', function( $scope, $ ) {
            WITHEMES.reInit();
        });
        
    });
    
} )( jQuery );
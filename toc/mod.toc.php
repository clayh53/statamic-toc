<?php
/**
 * add-in_toc
 * generates table of contents from headings in content
 * creates table of contents div with links to sections 
 * and inserts 'to top' link after each heading to return
 * the reader to the top of the page
 *
 * returns the table of contents in a div of class .toc-block
 * and the ol list elements within are assigned a class of
 * .toc for ease in styling the look of the table of contents.
 * 
 * @author  Clay Harmon
 *
 * Add-in borrows heavily from Joost de Valk's 
 * Create Table of Contents located here:
 * http://www.westhost.com/contest/php/function/create-table-of-contents/124
 * modifications to original center around appending a return link at the
 * end of each section heading, and fixing behavior where descendant <ol> elements
 * did not have a 'toc' class appended to them
 */
class Modifier_toc extends Modifier
{
   	var $meta = array(
		'name'			 => 'toc',
		'version'		 => '1',
		'author'		 => 'Clay Harmon',
		'author_url' => 'http://clayharmon.com'
		);
    
    

    public function index($value, $parameters=array())  
        
       
        {	
	        $fully_cooked = create_toc($value);

            $modified = "<div class='toc-block'>".$fully_cooked['toc']."</div>"."<div class='content-block'>".$fully_cooked['content']."</div>";
	       	        
	        return $modified;
    	}
    	
}
// This function contains the guts of the routine, and has been very
// slightly modified from the original code by Joost de Valk
// 
function create_toc( $content ) {
    preg_match_all( '/<h([1-6])(.*)>([^<]+)<\/h[1-6]>/i', $content, $matches, PREG_SET_ORDER );
 
    global $anchors;
 
    $anchors = array();
    $toc     = '<ol class="toc">'."\n";
    $i       = 0;
 
    foreach ( $matches as $heading ) {
 
        if ($i == 0)
            $startlvl = $heading[1];
        $lvl        = $heading[1];
 
        $ret = preg_match( '/id=[\'|"](.*)?[\'|"]/i', stripslashes($heading[2]), $anchor );
        if ( $ret && $anchor[1] != '' ) {
            $anchor = stripslashes( $anchor[1] );
            $add_id = false;
        } else {
            $anchor = preg_replace( '/\s+/', '-', preg_replace('/[^a-z\s]/', '', strtolower( $heading[3] ) ) );
            $add_id = true;
        }
 
        if ( !in_array( $anchor, $anchors ) ) {
            $anchors[] = $anchor;
        } else {
            $orig_anchor = $anchor;
            $i = 2;
            while ( in_array( $anchor, $anchors ) ) {
                $anchor = $orig_anchor.'-'.$i;
                $i++;
            }
            $anchors[] = $anchor;
        }
 
        if ( $add_id ) {
            // This section is where you can mess with the 'to home arrow and remove it entirely if you don't want it
            // It is in the <span><a href="#.... section on the next line. Add a class to the span if you want to style it in your CSS
            $content = substr_replace( $content, '<h'.$lvl.' id="'.$anchor.'"'.$heading[2].'>'.$heading[3].'<span class="toc-arrow"><a href="#">&uarr;</a></span></h'.$lvl.'>', strpos( $content, $heading[0] ), strlen( $heading[0] ) );

        
        }
 
        $ret = preg_match( '/title=[\'|"](.*)?[\'|"]/i', stripslashes( $heading[2] ), $title );
        if ( $ret && $title[1] != '' )
            $title = stripslashes( $title[1] );
        else    
            $title = $heading[3];
        $title      = trim( strip_tags( $title ) );
 
        if ($i > 0) {
            if ($prevlvl < $lvl) {
                $toc .= "\n"."<ol class='toc'>"."\n";
            } else if ($prevlvl > $lvl) {
                $toc .= '</li>'."\n";
                while ($prevlvl > $lvl) {
                    $toc .= "</ol>"."\n".'</li>'."\n";
                    $prevlvl--;
                }
            } else {
                $toc .= '</li>'."\n";
            }
        }
 
        $j = 0;
        $toc .= '<li><a href="#'.$anchor.'">'.$title.'</a>';
        $prevlvl = $lvl;
 
        $i++;
    }
 
    unset( $anchors );
 
    while ( $lvl > $startlvl ) {
        $toc .= "\n</ol>";
        $lvl--;
    }
 
    $toc .= '</li>'."\n";
    $toc .= '</ol>'."\n";
 
    return array( 
        'toc' => $toc,
        'content' => $content
    );
}